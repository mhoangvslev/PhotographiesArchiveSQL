-- connection: host='localhost' dbname='Photographies' user='postgres' password='postgres'

/*====================================================
 * La photo dans laquelle figure une personne.(fonction)
 *====================================================*/
DROP FUNCTION IF EXISTS chercher_photo_par_personne;
CREATE OR REPLACE FUNCTION chercher_photo_par_personne(str varchar)
RETURNS TABLE(Article integer, Discriminant integer, NomOeuvre varchar) AS $$
   BEGIN
       RETURN QUERY(
           SELECT DISTINCT PhotoArticle, D.Discriminant, I.NomOeuvre FROM
           Document D JOIN IndexPersonne I on D.idOeuvre=I.idOeuvre
           WHERE I.NomOeuvre LIKE '%' || str || '%'
       );
   END;
$$ language plpgsql;

SELECT * FROM chercher_photo_par_personne('Becquerel') LIMIT 10;

/*====================================================
 * Les photos qui présentent un sujet.(fonction)
 *====================================================*/
DROP FUNCTION IF EXISTS chercher_photo_par_sujet;
CREATE OR REPLACE FUNCTION chercher_photo_par_sujet(str varchar)
RETURNS TABLE(PhotoArticle integer, Discriminant integer, NomOeuvre varchar) AS $$
   BEGIN
       RETURN QUERY(
           SELECT DISTINCT D.PhotoArticle, D.Discriminant, S.DescSujet FROM
           Document D JOIN Sujet S on D.idSujet = S.idSujet
           WHERE S.DescSujet ILIKE '%' || str || '%'
       );
   END; 
$$ language plpgsql;

SELECT * FROM chercher_photo_par_sujet('église') LIMIT 10;

/*====================================================
 * Afficher que les photos en couleur (view)
 *====================================================*/
DROP VIEW IF EXISTS photo_en_couleur;
CREATE OR REPLACE VIEW photo_en_couleur AS
    SELECT Article, Remarques, NbrCli, DescDet, idSerie  
    FROM Photo P JOIN Document D ON P.Article = D.PhotoArticle
    WHERE d.C_G = 'CLR';

SELECT * FROM photo_en_couleur LIMIT 10;

/*====================================================
 * Afficher que les photos en noir et blanc (view)
 *====================================================*/
DROP VIEW IF EXISTS photo_en_noirblanc;
CREATE OR REPLACE VIEW photo_en_noirblanc AS
    SELECT DISTINCT(Article), Remarques, NbrCli, DescDet, idSerie  
    FROM Photo P JOIN Document D ON P.Article = D.PhotoArticle
    WHERE d.C_G = 'GSC';

SELECT * FROM photo_en_noirblanc LIMIT 10;

/*====================================================
 * Les photos qui ont un fichier numérique.(view)
 *====================================================*/ 
DROP INDEX IF EXISTS index_ficnum;
CREATE INDEX index_ficnum ON Document(FicNum);
CREATE OR REPLACE VIEW view_photo_numeric AS
SELECT DISTINCT(Article), Remarques, NbrCli, DescDet, idSerie 
FROM Photo P JOIN Document D ON P.Article = D.PhotoArticle
WHERE d.FicNum IS NOT NULL;

SELECT * FROM view_photo_numeric LIMIT 10;

/*====================================================
 * Les photos qui correspondent au fichier donné.(fonction)
 *====================================================*/ 
DROP FUNCTION IF EXISTS rechercher_cliche_photo;
CREATE OR REPLACE FUNCTION rechercher_cliche_photo(varchar)
    RETURNS TABLE(article integer, ficnum varchar, nbrcli integer, N_V varchar, C_G varchar, idserie integer)     
    LANGUAGE 'plpgsql'
AS $BODY$
    BEGIN
        RETURN QUERY(
            select P.Article,D.ficnum,P.NbrCli,D.N_V,D.C_G,P.idSerie from Photo P
            JOIN document D ON P.article = D.photoarticle
            where  D.ficnum= $1
        );
    END; 
$BODY$;

SELECT * FROM rechercher_cliche_photo('FRAD045_CLVUE000001_H');

/*====================================================
 * Chercher un certain type d’oeuvre.
 *====================================================*/ 
DROP FUNCTION IF EXISTS rechercher_type_oeuvre;
CREATE OR REPLACE FUNCTION rechercher_type_oeuvre(varchar)
RETURNS TABLE(article integer, ficnum varchar, nbrcli integer, N_V varchar, C_G varchar, idserie integer)    
AS $$
    BEGIN
        RETURN QUERY( 
            select DISTINCT(P.Article), D.ficnum,P.NbrCli,D.N_V,D.C_G,P.idSerie from Photo P
            join Document D on D.PhotoArticle=P.Article
            join IndexPersonne I on D.idoeuvre=I.idoeuvre
            join TypeOeuvre T on I.typeOeuvre=T.idType
            where T.NomType = $1
        );
    END;
$$ LANGUAGE plpgsql;

select * from rechercher_type_oeuvre('statue') LIMIT 10;

/*====================================================
 * Rechercher les photos qui appartiennent à une série.
 *====================================================*/ 
DROP FUNCTION IF EXISTS rechercher_serie_photo;
CREATE OR REPLACE FUNCTION rechercher_serie_photo(varchar)
RETURNS TABLE(article integer, ficnum varchar, nbrcli integer, N_V varchar, C_G varchar, idserie integer)    
AS $$
    BEGIN
        RETURN QUERY(
            select DISTINCT(P.Article), D.ficnum, P.NbrCli, D.N_V, D.C_G, P.idSerie from Photo P
            join Document D on P.Article=D.PhotoArticle
            join Serie S on S.IdSerie=P.IdSerie
            where S.nomSerie = $1
        );
        END;
$$ LANGUAGE plpgsql;

select * from rechercher_serie_photo('CLVUE') LIMIT 10;

/*====================================================
 * Les photos correspondant à un index iconographique précis
 *====================================================*/
CREATE OR REPLACE FUNCTION index_photo(varchar)
RETURNS TABLE(article integer, ficnum varchar, nbrcli integer, N_V varchar, C_G varchar, idserie integer)    
  AS $$
    BEGIN
RETURN QUERY

select P.Article,D.ficnum,P.NbrCli,D.N_V,D.C_G,P.idSerie from Photo P
join Document D on P.Article=D.PhotoArticle
join IndexIconographique I on I.IdIco = D.idico
where I.idx_ico = $1;
    END;
$$ LANGUAGE plpgsql;

select * from index_photo('paysage urbain') LIMIT 10;

/*====================================================
 * Le nombre de photos dont on ne connait pas la date
 *====================================================*/
DROP FUNCTION IF EXISTS date_photo CASCADE;
CREATE OR REPLACE FUNCTION date_photo()
RETURNS TABLE(article integer, ficnum varchar, nbrcli integer, N_V varchar, C_G varchar, idserie integer)    
  AS $$
    BEGIN
RETURN QUERY
select distinct(P.Article),D.ficnum,P.NbrCli,D.N_V,D.C_G,P.idSerie from Photo P
join Document D on P.Article=D.PhotoArticle
where  D.iddate is Null;
END;
$$ LANGUAGE plpgsql;

select COUNT(distinct article) from date_photo();

/*====================================================
 * Le nombre de photos dont on ne connait pas la ville
 *====================================================*/
drop function if exists ville_photo();
CREATE OR REPLACE FUNCTION ville_photo()
RETURNS TABLE(article integer, ficnum varchar, nbrcli integer, N_V varchar, C_G varchar, idserie integer) AS $$
    BEGIN
        RETURN QUERY
            select P.Article,D.ficnum,P.NbrCli,D.N_V,D.C_G,P.idSerie from Photo P
            join Document D on P.Article=D.PhotoArticle
            where D.idville is Null;
        END;
$$ LANGUAGE plpgsql;

select count(distinct article) from ville_photo();

/*====================================================
 * Le nombre moyen de photo par ville
 *====================================================*/
DROP FUNCTION IF EXISTS func_avg_cliche_ville CASCADE;
CREATE OR REPLACE FUNCTION func_avg_photo_ville()
RETURNS float AS $$
    BEGIN
        RETURN (
            WITH subquery AS (SELECT NomVille, COUNT(DISTINCT PhotoArticle) as nbPhoto
            FROM Document d, Ville v
            WHERE d.idVille = v.idVille
            GROUP BY NomVille)
            SELECT AVG(nbPhoto) from subquery
        );
    END;
$$ language plpgsql;

SELECT func_avg_photo_ville();

/*====================================================
 * Le nombre moyen de cliché par ville
 *====================================================*/
drop function if exists func_avg_cliche_ville() CASCADE;
CREATE OR REPLACE FUNCTION func_avg_cliche_ville()
RETURNS float AS $$
BEGIN
    RETURN(
        WITH subquery AS (
            SELECT NomVille, SUM(NbrCli::integer) as nbCliche
            FROM Photo p, Document d, Ville v
            WHERE p.Article = d.PhotoArticle and d.idVille = v.idVille
            GROUP BY NomVille
        )
        SELECT AVG(nbCliche) from subquery
    );
END;
$$ LANGUAGE plpgsql;

select func_avg_cliche_ville();

/*====================================================
 * Les n photos les plus anciennes 
 * flag: true (plus anciens), false (plus récent)
 *====================================================*/
DROP FUNCTION IF EXISTS func_photos_anciennes_recents CASCADE;
CREATE OR REPLACE FUNCTION func_photos_anciennes_recents(n integer, flag boolean)
RETURNS TABLE(PhotoArticle integer, Discriminant integer, DateP varchar) AS $$
    BEGIN
        IF flag THEN
            RETURN QUERY(
                WITH subqueryYear AS(SELECT MIN(DateAnnee::numeric) as minYear FROM DatePhoto),
                subquery1 AS(
                    SELECT D.PhotoArticle as pArticle, D.Discriminant as pDiscriminant, DateJour, DateMois, DateAnnee
                    FROM Document D, DatePhoto Da 
                    WHERE D.idDate = Da.idDate and Da.DateAnnee::numeric = (SELECT minYear FROM subqueryYear)
                    ORDER BY DateMois, DateAnnee ASC
                )
                SELECT pArticle, pDiscriminant, array_to_string(ARRAY[DateJour, num_to_month(DateMois), DateAnnee], ' ', '')::varchar 
                FROM subquery1
                LIMIT n
            );
        ELSE
            RETURN QUERY(
                WITH subqueryYear AS(SELECT MAX(DateAnnee::numeric) as minYear FROM DatePhoto),
                subquery1 AS(
                    SELECT D.PhotoArticle as pArticle, D.Discriminant as pDiscriminant, DateJour, DateMois, DateAnnee
                    FROM Document D, DatePhoto Da 
                    WHERE D.idDate = Da.idDate and Da.DateAnnee::numeric = (SELECT minYear FROM subqueryYear)
                    ORDER BY DateMois, DateAnnee DESC
                )
                SELECT pArticle, pDiscriminant, array_to_string(ARRAY[DateJour, num_to_month(DateMois), DateAnnee], ' ', '')::varchar 
                FROM subquery1
                LIMIT n
            );
        END IF;
    END; 
$$ LANGUAGE plpgsql;

SELECT * FROM func_photos_anciennes_recents(10, true);

CREATE OR REPLACE VIEW view_statistique AS 
    SELECT func_avg_cliche_ville() as nbre_moyen_cliche ,
        func_avg_photo_ville() as nbre_moyen_photo,
        (select count(*) from date_photo()) as photos_sans_date,
        (select ARRAY[PhotoArticle::varchar, DateP] FROM func_photos_anciennes_recents(1, true)) as photo_plus_ancienne,
        (select ARRAY[PhotoArticle::varchar, DateP] FROM func_photos_anciennes_recents(1, false)) as photo_plus_recente
;

select * from view_statistique;

/*================================================
 * FUNCTION: histogram
 * Représenter les pourcentages en visuel
 *================================================*/
DROP FUNCTION IF EXISTS histogram(anyarray,anyelement);
CREATE OR REPLACE FUNCTION histogram(arr anyarray, freq anyelement)
RETURNS varchar AS $$
    DECLARE
        v_max integer := (SELECT MAX(a) FROM unnest(arr) a);
    BEGIN
        IF v_max = 0 THEN v_max := 1; END IF;
        RETURN repeat('█', (freq::float/(v_max) *30)::int);
    
    END;
$$ language plpgsql;

/*================================================
 * Le pourcentage de photos pris dans chaque ville. (la ville qui a plus de photos)
 *================================================*/
WITH 
    subquery AS(SELECT COUNT(Article) as nbPhoto FROM Photo),
    query AS(
        SELECT NomVille, 
            COUNT(DISTINCT PhotoArticle) as nbPhoto, 
            COUNT(DISTINCT PhotoArticle)*100/(SELECT nbPhoto FROM subquery) ::float as pourcentage
        FROM Document d, Ville v
        WHERE d.idVille = v.idVille
        GROUP BY NomVille
    )
SELECT NomVille, nbPhoto, pourcentage, histogram(ARRAY(SELECT pourcentage from query), pourcentage) as bar
FROM query
ORDER BY nbPhoto DESC
LIMIT 10;

/*================================================
 * Le pourcentage de prise de photos en fonction des années.(évolution sur une période)
 *================================================*/
WITH 
    subquery AS(SELECT COUNT(Article) as nbPhoto FROM Photo),
    query AS(
        SELECT DateAnnee::numeric as Annee, 
            COUNT(DISTINCT PhotoArticle) as nbPhoto, 
            COUNT(DISTINCT PhotoArticle)*100/(SELECT nbPhoto FROM subquery) ::float as pourcentage
        FROM Document d, DatePhoto dp
        WHERE d.idDate = dp.idDate
        GROUP BY Annee
    )
SELECT Annee, nbPhoto, pourcentage, histogram(ARRAY(SELECT pourcentage from query), pourcentage) as bar
FROM query
ORDER BY nbPhoto DESC
LIMIT 10;

/*================================================
 * La proportion de photos selon leur taille.
 *================================================*/
WITH 
    subquery AS(SELECT COUNT(Article) as nbPhoto FROM Photo),
    query AS(
        SELECT Taille, 
            COUNT(DISTINCT PhotoArticle) as nbPhoto, 
            COUNT(DISTINCT PhotoArticle)*100/(SELECT nbPhoto FROM subquery) ::float as pourcentage
        FROM Document d, Cliche c
        WHERE d.idCliche = c.idCliche
        GROUP BY Taille
    )
SELECT Taille, nbPhoto, pourcentage, histogram(ARRAY(SELECT pourcentage from query), pourcentage) as bar
FROM query
ORDER BY nbPhoto DESC
LIMIT 10;

/*================================================
 * Le pourcentage de personnes représentées ( + par ville, par année)
 *================================================*/
 
WITH 
    subquery AS(SELECT COUNT(Article) as nbPhoto FROM Photo),
    query AS(
        SELECT NomOeuvre, 
            COUNT(DISTINCT PhotoArticle) as nbPhoto, 
            COUNT(DISTINCT PhotoArticle)*100/(SELECT nbPhoto FROM subquery) ::float as pourcentage
        FROM Document d, IndexPersonne p
        WHERE d.idOeuvre = p.idOeuvre
        GROUP BY NomOeuvre
    )
SELECT NomOeuvre, nbPhoto, pourcentage, histogram(ARRAY(SELECT pourcentage from query), pourcentage) as bar
FROM query
ORDER BY nbPhoto DESC
LIMIT 10;

/*================================================
 * Le pourcentage de personnes représentées ( par ville)
 *================================================*/
WITH 
    subquery AS(SELECT COUNT(Article) as nbPhoto FROM Photo),
    query AS(
        SELECT NomOeuvre, NomVille,
            COUNT(DISTINCT PhotoArticle) as nbPhoto, 
            COUNT(DISTINCT PhotoArticle)*100/(SELECT nbPhoto FROM subquery) ::float as pourcentage
        FROM Document d, IndexPersonne p, Ville v
        WHERE d.idOeuvre = p.idOeuvre and d.idVille = v.idVille
        GROUP BY NomOeuvre, NomVille
    )
SELECT NomOeuvre, NomVille, nbPhoto, pourcentage, histogram(ARRAY(SELECT pourcentage from query), pourcentage) as bar
FROM query
ORDER BY nbPhoto DESC
LIMIT 10;

/*================================================
 * Le pourcentage de personnes représentées ( par année)
 *================================================*/
WITH 
    subquery AS(SELECT COUNT(Article) as nbPhoto FROM Photo),
    query AS(
        SELECT NomOeuvre, DateAnnee::numeric,
            COUNT(DISTINCT PhotoArticle) as nbPhoto, 
            COUNT(DISTINCT PhotoArticle)*100/(SELECT nbPhoto FROM subquery) ::float as pourcentage
        FROM Document d, IndexPersonne p, DatePhoto dp
        WHERE d.idOeuvre = p.idOeuvre and d.idDate = dp.idDate
        GROUP BY NomOeuvre, DateAnnee
    )
SELECT NomOeuvre, DateAnnee, nbPhoto, pourcentage, histogram(ARRAY(SELECT pourcentage from query), pourcentage) as bar
FROM query
ORDER BY NomOeuvre
LIMIT 10;

/*================================================
 * Le pourcentage de type d’oeuvre représentées (+ par ville, par année)
 *================================================*/
 
WITH 
    subquery AS(SELECT COUNT(Article) as nbPhoto FROM Photo),
    query AS(
        SELECT NomType,
            COUNT(DISTINCT PhotoArticle) as nbPhoto, 
            COUNT(DISTINCT PhotoArticle)*100/(SELECT nbPhoto FROM subquery) ::float as pourcentage
        FROM Document d, IndexPersonne p, TypeOeuvre t
        WHERE d.idOeuvre = p.idOeuvre and p.TypeOeuvre = t.idType
        GROUP BY NomType
    )
SELECT NomType, nbPhoto, pourcentage, histogram(ARRAY(SELECT pourcentage from query), pourcentage) as bar
FROM query
ORDER BY nbPhoto DESC
LIMIT 10;

/*================================================
 * Le pourcentage de type d’oeuvre représentées (+ par ville)
 *================================================*/
 
WITH 
    subquery AS(SELECT COUNT(Article) as nbPhoto FROM Photo),
    query AS(
        SELECT NomType, NomVille,
            COUNT(DISTINCT PhotoArticle) as nbPhoto, 
            COUNT(DISTINCT PhotoArticle)*100/(SELECT nbPhoto FROM subquery) ::float as pourcentage
        FROM Document d, IndexPersonne p, TypeOeuvre t, Ville v
        WHERE d.idOeuvre = p.idOeuvre and p.TypeOeuvre = t.idType and d.idVille = v.idVille
        GROUP BY NomType, NomVille
    )
SELECT NomType, NomVille, nbPhoto, pourcentage, histogram(ARRAY(SELECT pourcentage from query), pourcentage) as bar
FROM query
ORDER BY nbPhoto DESC
LIMIT 10;

/*================================================
 * Le pourcentage de type d’oeuvre représentées (+ par année)
 *================================================*/
 
WITH 
    subquery AS(SELECT COUNT(Article) as nbPhoto FROM Photo),
    query AS(
        SELECT NomType, DateAnnee::numeric,
            COUNT(DISTINCT PhotoArticle) as nbPhoto, 
            COUNT(DISTINCT PhotoArticle)*100/(SELECT nbPhoto FROM subquery) ::float as pourcentage
        FROM Document d, IndexPersonne p, TypeOeuvre t, DatePhoto dp
        WHERE d.idOeuvre = p.idOeuvre and p.TypeOeuvre = t.idType and d.idDate = dp.idDate
        GROUP BY NomType, DateAnnee
    )
SELECT NomType, DateAnnee, nbPhoto, pourcentage, histogram(ARRAY(SELECT pourcentage from query), pourcentage) as bar
FROM query
ORDER BY nbPhoto DESC
LIMIT 10;