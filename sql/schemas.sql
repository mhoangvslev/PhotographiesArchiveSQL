--
-- PostgreSQL database dump
--

-- Dumped from database version 11.1 (Ubuntu 11.1-3.pgdg18.10+1)
-- Dumped by pg_dump version 11.1 (Ubuntu 11.1-3.pgdg18.10+1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: btree_gin; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS btree_gin WITH SCHEMA public;


--
-- Name: EXTENSION btree_gin; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION btree_gin IS 'support for indexing common datatypes in GIN';


--
-- Name: pg_trgm; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS pg_trgm WITH SCHEMA public;


--
-- Name: EXTENSION pg_trgm; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION pg_trgm IS 'text similarity measurement and index searching based on trigrams';


--
-- Name: coords; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.coords AS (
	coordx numeric,
	coordy numeric
);


ALTER TYPE public.coords OWNER TO postgres;

--
-- Name: insert_array; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE public.insert_array AS (
	field character varying[]
);


ALTER TYPE public.insert_array OWNER TO postgres;

--
-- Name: array_expand(anyarray, integer, anyelement); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.array_expand(arr anyarray, maxlength integer, fill anyelement DEFAULT NULL::unknown) RETURNS anyarray
    LANGUAGE plpgsql
    AS $$
    DECLARE
        i int;
        length int := coalesce(array_length(arr, 1), 0);
    BEGIN
        --RAISE NOTICE 'MaxLength: %, Length: %', maxlength, length;
        IF (maxlength > length) THEN
            FOR i IN 1 .. (maxlength - length) LOOP
                arr := array_append(arr, fill);
            END LOOP;
        END IF;
        RETURN arr;
    END;
$$;


ALTER FUNCTION public.array_expand(arr anyarray, maxlength integer, fill anyelement) OWNER TO postgres;

--
-- Name: chercher_photo_par_personne(character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.chercher_photo_par_personne(str character varying) RETURNS TABLE(article integer, nomoeuvre character varying)
    LANGUAGE plpgsql
    AS $$
    BEGIN
        RETURN QUERY(
            SELECT PhotoArticle, I.NomOeuvre FROM
            Document D JOIN IndexPersonne I on D.idOeuvre=I.idOeuvre
            WHERE I.NomOeuvre ILIKE '%' || str || '%'
        );
    END; 
$$;


ALTER FUNCTION public.chercher_photo_par_personne(str character varying) OWNER TO postgres;

--
-- Name: chercher_photo_par_sujet(character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.chercher_photo_par_sujet(str character varying) RETURNS TABLE(photoarticle integer, nomoeuvre character varying)
    LANGUAGE plpgsql
    AS $$
    BEGIN
        RETURN QUERY(
            SELECT D.PhotoArticle, S.DescSujet FROM
            Document D JOIN Sujet S on D.idSujet = S.idSujet
            WHERE S.DescSujet ILIKE '%' || str || '%'
        );
    END;  
$$;


ALTER FUNCTION public.chercher_photo_par_sujet(str character varying) OWNER TO postgres;

--
-- Name: combine_date_string(character varying[]); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.combine_date_string(d character varying[]) RETURNS character varying
    LANGUAGE plpgsql
    AS $$
    DECLARE
        res varchar;
    BEGIN
        IF d[2] = '' THEN d[2] := '-1'; 
        END IF;
        res := array_to_string(ARRAY[d[1], num_to_month(d[2]), d[3]]::varchar[], ' ', '');
        res := regexp_replace(res, '^\s*(.*)', '\1');        
        RETURN trim(BOTH from res);
    END;
$$;


ALTER FUNCTION public.combine_date_string(d character varying[]) OWNER TO postgres;

--
-- Name: date_photo(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.date_photo() RETURNS TABLE(article integer, ficnum character varying, nbrcli integer, n_v character varying, c_g character varying, idserie integer)
    LANGUAGE plpgsql
    AS $$
    BEGIN
RETURN QUERY
select distinct(P.Article),D.ficnum,P.NbrCli,D.N_V,D.C_G,P.idSerie from Photo P
join Document D on P.Article=D.PhotoArticle
where  D.iddate is Null;
END;
$$;


ALTER FUNCTION public.date_photo() OWNER TO postgres;

--
-- Name: func_avg_cliche_ville(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.func_avg_cliche_ville() RETURNS double precision
    LANGUAGE plpgsql
    AS $$
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
$$;


ALTER FUNCTION public.func_avg_cliche_ville() OWNER TO postgres;

--
-- Name: func_avg_photo_ville(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.func_avg_photo_ville() RETURNS double precision
    LANGUAGE plpgsql
    AS $$
    BEGIN
        RETURN (
            WITH subquery AS (SELECT NomVille, COUNT(DISTINCT PhotoArticle) as nbPhoto
            FROM Document d, Ville v
            WHERE d.idVille = v.idVille
            GROUP BY NomVille)
            SELECT AVG(nbPhoto) from subquery
        );
    END;
$$;


ALTER FUNCTION public.func_avg_photo_ville() OWNER TO postgres;

--
-- Name: func_photos_anciennes_recents(integer, boolean); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.func_photos_anciennes_recents(n integer, flag boolean) RETURNS TABLE(photoarticle integer, discriminant integer, datep character varying)
    LANGUAGE plpgsql
    AS $$
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
$$;


ALTER FUNCTION public.func_photos_anciennes_recents(n integer, flag boolean) OWNER TO postgres;

--
-- Name: function_verificationsdate(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.function_verificationsdate() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
    BEGIN
        IF NEW.DateAnnee::integer < 1840 and NEW.DateMois::integer < 2 and NEW.DateJour::integer < 7 THEN
            RAISE EXCEPTION 'Annee de prise de la photo inferieure au 7 janvier 1839 (création de la photographie)';
        ELSIF NEW.DateMois != '' AND (NEW.DateMois::integer < 0 OR NEW.DateMois::integer > 12) THEN
            RAISE EXCEPTION 'Mois inferieur à 1 ou superieur a 12';
        ELSIF NEW.DateJour != '' AND (NEW.DateJour::integer < 0 OR NEW.DateJour::integer > 31) THEN
            RAISE EXCEPTION 'Jour inferieur a 1 ou superieur a 31';
        ELSE
            RETURN NEW;
        END IF;
    END; 
$$;


ALTER FUNCTION public.function_verificationsdate() OWNER TO postgres;

--
-- Name: getmaxlength(public.insert_array[]); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.getmaxlength(VARIADIC arr public.insert_array[]) RETURNS integer
    LANGUAGE plpgsql
    AS $_$
    DECLARE
        result int;
    BEGIN
        SELECT coalesce(max(array_length($1[i].field, 1)), 0) 
        FROM generate_subscripts($1, 1) g(i) 
        INTO result;
        --RAISE NOTICE 'maxLength: %', result;
        RETURN result;
    END;
$_$;


ALTER FUNCTION public.getmaxlength(VARIADIC arr public.insert_array[]) OWNER TO postgres;

--
-- Name: histogram(anyarray, anyelement); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.histogram(arr anyarray, freq anyelement) RETURNS character varying
    LANGUAGE plpgsql
    AS $$
    DECLARE
        v_max integer := (SELECT MAX(a) FROM unnest(arr) a);
    BEGIN
        IF v_max = 0 THEN v_max := 1; END IF;
        RETURN repeat('█', (freq::float/(v_max) *30)::int);
    
    END;
$$;


ALTER FUNCTION public.histogram(arr anyarray, freq anyelement) OWNER TO postgres;

--
-- Name: index_photo(character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.index_photo(character varying) RETURNS TABLE(article integer, ficnum character varying, nbrcli integer, n_v character varying, c_g character varying, idserie integer)
    LANGUAGE plpgsql
    AS $_$
    BEGIN
RETURN QUERY

select P.Article,D.ficnum,P.NbrCli,D.N_V,D.C_G,P.idSerie from Photo P
join Document D on P.Article=D.PhotoArticle
join IndexIconographique I on I.IdIco = D.idico
where I.idx_ico = $1;
    END;
$_$;


ALTER FUNCTION public.index_photo(character varying) OWNER TO postgres;

--
-- Name: month_to_num(character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.month_to_num(mois character varying) RETURNS numeric
    LANGUAGE plpgsql
    AS $$
    BEGIN
        IF mois ILIKE 'Janvier' THEN RETURN 1;
        ELSIF mois ILIKE 'Février' THEN RETURN 2;
        ELSIF mois ILIKE 'Mars' THEN RETURN 3;
        ELSIF mois ILIKE 'Avril' THEN RETURN 4;
        ELSIF mois ILIKE 'Mai' THEN RETURN 5;
        ELSIF mois ILIKE 'Juin' THEN RETURN 6;
        ELSIF mois ~* 'Jui{0,1}llet' THEN RETURN 7;
        ELSIF mois ILIKE 'Août' THEN RETURN 8;
        ELSIF mois ILIKE 'Septembre' THEN RETURN 9;
        ELSIF mois ILIKE 'Octobre' THEN RETURN 10;
        ELSIF mois ILIKE 'Novembre' THEN RETURN 11;
        ELSIF mois ILIKE 'Décembre' THEN RETURN 12;
        ELSE RETURN -1;
        END IF;
    END;
$$;


ALTER FUNCTION public.month_to_num(mois character varying) OWNER TO postgres;

--
-- Name: normalisation(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.normalisation() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
    DECLARE 
        v_idVille integer;
        v_idSerie integer;
        v_idDate integer;
        v_date varchar[] := split_date(NEW.Date);
        v_idOeuvre integer;
        v_idCliche integer;
        v_idSujet integer;
        v_idIco integer;
        v_idxPer varchar[];
    BEGIN       
        -- INSERT dans Ville
        IF (NEW.Ville IS NOT NULL ) THEN  
            SELECT INTO v_idVille COUNT(idVille) FROM Ville;
            INSERT INTO Ville(idVille, NomVille, Latitude, Longitude, CoordX, CoordY) 
            VALUES(
                (SELECT COUNT(idVille) FROM Ville)+1,
                NEW.Ville, 
                (NEW.Coords).CoordX, (NEW.Coords).CoordY,
                (NEW.Lamberts).CoordX, (NEW.Lamberts).CoordY) 
            ON CONFLICT(NomVille) DO NOTHING;
            
        END IF;
        
        -- INSERT dans serie;
        IF (NEW.Serie IS NOT NULL ) THEN 
            INSERT INTO Serie(idSerie, NomSerie) 
            VALUES(
                (SELECT COUNT(idSerie) FROM Serie)+1,
                NEW.Serie) 
            ON CONFLICT(NomSerie) DO NOTHING;
        END IF;
        
        -- INSERT dans date;
        IF (v_date[3] IS NOT NULL) THEN  
            --RAISE EXCEPTION 'Inserting % from %', v_date, NEW.Date;
            INSERT INTO DatePhoto(idDate, DateJour, DateMois, DateAnnee) 
            VALUES(
                (SELECT COUNT(idDate) FROM DatePhoto)+1,
                v_date[1], v_date[2], v_date[3])
            ON CONFLICT(DateJour, DateMois, DateAnnee) DO NOTHING;
        END IF;
        
        -- INSERT dans IdxPers;
        v_idxPer := split_oeuvre(NEW.idx_per);
        IF (NEW.Idx_per IS NOT NULL ) THEN
            IF v_idxPer[2] != '' THEN
                INSERT INTO TypeOeuvre(idType, NomType) 
                VALUES(
                    (SELECT COUNT(idType) FROM TypeOeuvre),
                    v_idxPer[2]
                ) ON CONFLICT(NomType) DO NOTHING;
            END IF;
            
            INSERT INTO IndexPersonne(idOeuvre, NomOeuvre, TypeOeuvre) 
            VALUES(
                (SELECT COUNT(idOeuvre) FROM IndexPersonne)+1,
                v_idxPer[1],
                (SELECT idType FROM TypeOeuvre WHERE NomType = v_idxPer[2])
            ) 
            ON CONFLICT(NomOeuvre, TypeOeuvre) DO NOTHING;    
        END IF;
        
        -- INSERT dans Cliche
        IF (NEW.TailleCli IS NOT NULL ) THEN                 
            INSERT INTO Cliche(idCliche, Taille) 
            VALUES (
                (SELECT COUNT(idCliche) FROM Cliche)+1,
                NEW.TailleCli) 
            ON CONFLICT DO NOTHING;
        END IF;
        
        -- INSERT dans IndexIco
        IF (NEW.Idx_Ico IS NOT NULL ) THEN   
            INSERT INTO IndexIconographique(idIco, Idx_Ico) 
            VALUES(
                (SELECT COUNT(idIco) FROM IndexIconographique)+1,
                NEW.Idx_Ico) 
            ON CONFLICT DO NOTHING;
        END IF;
        
        -- INSERT dans Sujet
        IF (NEW.Sujet IS NOT NULL ) THEN  
            INSERT INTO Sujet(idSujet, DescSujet) 
            VALUES(
                (SELECT COUNT(idSujet) FROM Sujet)+1,
                NEW.Sujet)
            ON CONFLICT DO NOTHING;
        END IF;
        
        -- INSERT dans Photo;
        SELECT INTO v_idSerie idSerie FROM Serie WHERE NomSerie = NEW.Serie;
        INSERT INTO Photo (Article, Remarques, NbrCli, DescDet, idSerie)
        VALUES (NEW.Article, NEW.Remarques, NEW.NbrCli::integer, NEW.DescDet, v_idSerie)
        ON CONFLICT DO NOTHING;
        
        -- INSERT dans Document
        SELECT INTO v_idVille idVille FROM Ville WHERE NomVille = NEW.Ville;        
        SELECT INTO v_idDate idDate FROM DatePhoto 
            WHERE DateJour = v_date[1] and DateMois = v_date[2] and DateAnnee = v_date[3];
            
        SELECT INTO v_idOeuvre idOeuvre FROM IndexPersonne i, TypeOeuvre t
        WHERE NomOeuvre = v_idxPer[1] and NomType = v_idxPer[2] and i.TypeOeuvre = t.idType;
        
        SELECT INTO v_idCliche idCliche FROM Cliche WHERE Taille = NEW.TailleCli;
        SELECT INTO v_idIco idIco FROM IndexIconographique WHERE Idx_Ico = NEW.Idx_Ico; 
        SELECT INTO v_idSujet idSujet FROM Sujet WHERE DescSujet = NEW.Sujet;  
        
        INSERT INTO Document(PhotoArticle, Discriminant, FicNum, NoteBP, ReferenceCindoc, N_V, C_G, idVille, idOeuvre, idDate, idCliche, idIco, idSujet)
        VALUES(NEW.Article, NEW.Discriminant, NEW.FicNum, NEW.NoteBP, NEW.ReferenceCindoc, NEW.N_V, NEW.C_G, v_idVille, v_idOeuvre, v_idDate, v_idCliche, v_idIco, v_idSujet)
        ON CONFLICT DO NOTHING;  
        
        RETURN NULL;
    END;
$$;


ALTER FUNCTION public.normalisation() OWNER TO postgres;

--
-- Name: num_to_month(character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.num_to_month(num character varying) RETURNS character varying
    LANGUAGE plpgsql
    AS $$
    BEGIN
        IF num = '1' THEN RETURN 'Janvier';
        ELSIF num = '2' THEN RETURN 'Février';
        ELSIF num = '3' THEN RETURN 'Mars';
        ELSIF num = '4' THEN RETURN 'Avril';
        ELSIF num = '5' THEN RETURN 'Mai';
        ELSIF num = '6' THEN RETURN 'Juin';
        ELSIF num = '7' THEN RETURN 'Juillet';
        ELSIF num = '8' THEN RETURN 'Août';
        ELSIF num = '9' THEN RETURN 'Septembre';
        ELSIF num = '10' THEN RETURN 'Octobre';
        ELSIF num = '11' THEN RETURN 'Novembre';
        ELSIF num = '12' THEN RETURN 'Décembre';
        ELSE RETURN '';
        END IF;
    END;
$$;


ALTER FUNCTION public.num_to_month(num character varying) OWNER TO postgres;

--
-- Name: pretraitement(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.pretraitement() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
    DECLARE
        ReferenceCindocVals insert_array;
        DiscriminantVals insert_array;
        VilleVals insert_array;
        SujetVals insert_array;
        DateVals insert_array;
        NoteBPVals insert_array;
        Idx_PerVals insert_array;
        FicNumVals insert_array;
        Idx_IcoVals insert_array;
        NbrCliVals insert_array;
        TailleCliVals insert_array;
        N_VVals insert_array;
        C_GVals insert_array;
        LambertVals Coords[];
        CoordVals Coords[];
                
        maxLength int;
        insertVals insert_array[];
        
    BEGIN       
        -----------------------------------------------------------------------------
        -- Traitement des données
        -----------------------------------------------------------------------------
        ReferenceCindocVals.field := string_to_array(NEW.ReferenceCindoc, '|');
        DiscriminantVals.field := string_to_array(NEW.Discriminant, '|');
        NEW.Ville := regexp_replace(NEW.Ville, '--', '-');
        VilleVals.field := pretty(string_to_array(regexp_replace(NEW.Ville, '\s', '', 'g'), ','));
        SujetVals.field := string_to_array(NEW.sujet, ','); 
        DateVals.field := traitement_date(NEW.Date);
        NoteBPVals.field := split_string(NEW.NoteBP, '|', '/');
        FicNumVals.field := split_string(
            regexp_replace(NEW.FicNum, '(.*)\.(.*)', '\1'), '|', '/');
        Idx_PerVals.field := traitement_oeuvre(NEW.Idx_per);
        Idx_IcoVals.field := split_string(lower(NEW.Idx_ico), '|', '/');
        Idx_IcoVals.field := ARRAY(SELECT unnest(string_to_array(a, ',')) FROM unnest(Idx_IcoVals.field) a);
        NbrCliVals.field := split_string(NEW.NbrCli, '|', '/');
        NbrCliVals.field := ARRAY(SELECT unnest(string_to_array(d, ',')) FROM unnest(NbrCliVals.field) d);
        TailleCliVals.field := traitement_cliche(NEW.TailleCli);
        N_VVals.field := traitement_n_v(string_to_array(NEW.n_v, ','));
        C_GVals.field := traitement_c_g(string_to_array(NEW.c_g, ','));
        LambertVals := traitement_lambert(VilleVals.field);
        CoordVals := traitement_coord(VilleVals.field);
        
        maxLength := getMaxLength(
                ReferenceCindocVals,
                DiscriminantVals,
                VilleVals,
                SujetVals,
                DateVals,
                NoteBPVals,
                Idx_PerVals,
                FicNumVals,
                Idx_IcoVals,
                NbrCliVals,
                TailleCliVals,
                N_VVals,
                C_GVals
        );
        
        VilleVals.field := array_expand(VilleVals.field, maxLength, VilleVals.field[1]);
        
        -----------------------------------------------------------------------------
        -- Insérer dans la bonne table les données séparées
        -----------------------------------------------------------------------------
        INSERT INTO Correction (ReferenceCindoc, Serie, Article, Discriminant, Ville, 
                Sujet, DescDet, Date, NoteBP, Idx_Per, FicNum, Idx_Ico, NbrCli, 
                TailleCli, N_V, C_G, Remarques, Lamberts, Coords)
            VALUES(
                unnest(
                    array_expand(ReferenceCindocVals.field, maxLength, ReferenceCindocVals.field[1])::int[]
                ),
                NEW.serie,
                cast(NEW.article as int),
                unnest(traitement_discriminant(
                    array_expand(DiscriminantVals.field, maxLength, DiscriminantVals.field[1]))
                ),
                unnest(VilleVals.field),
                unnest(pretty(array_expand(SujetVals.field, maxLength, SujetVals.field[1]))),
                NEW.DescDet,
                unnest(pretty(array_expand(DateVals.field, maxLength, DateVals.field[1]))),
                unnest(pretty(array_expand(NoteBPVals.field, maxLength, NoteBPVals.field[1]))),
                unnest(pretty(array_expand(Idx_PerVals.field, maxLength, Idx_PerVals.field[1]))),
                unnest(pretty(array_expand(FicNumVals.field, maxLength, FicNumVals.field[1]))),
                unnest(pretty(array_expand(Idx_IcoVals.field, maxLength, Idx_IcoVals.field[1]))),
                unnest(pretty(array_expand(NbrCliVals.field, maxLength, NbrCliVals.field[1]))),
                unnest(pretty(array_expand(TailleCliVals.field, maxLength, TailleCliVals.field[1]))),
                unnest(pretty(array_expand(N_VVals.field, maxLength, N_VVals.field[1]))),
                unnest(pretty(array_expand(C_GVals.field, maxLength, C_GVals.field[1]))),
                NEW.Remarques,
                unnest(array_expand(LambertVals, maxLength, LambertVals[1])::Coords[]),
                unnest(array_expand(CoordVals, maxLength, CoordVals[1])::Coords[])
            );
        RETURN NULL;
    END;
$$;


ALTER FUNCTION public.pretraitement() OWNER TO postgres;

--
-- Name: pretty(character varying[]); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.pretty(strarr character varying[]) RETURNS character varying[]
    LANGUAGE plpgsql
    AS $$
    DECLARE
        i int;
        length int := coalesce(array_length(strarr, 1), 0);
    BEGIN
        FOR i IN 1..length LOOP
            strarr[i] = TRIM(BOTH ' ' FROM strarr[i]);
            strarr[i] = regexp_replace(strarr[i], '([a-z])([A-Z])', '\1-\2');
        END LOOP;
        RETURN strarr;
    END;
$$;


ALTER FUNCTION public.pretty(strarr character varying[]) OWNER TO postgres;

--
-- Name: rechercher_cliche_photo(character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.rechercher_cliche_photo(character varying) RETURNS TABLE(article integer, ficnum character varying, nbrcli integer, n_v character varying, c_g character varying, idserie integer)
    LANGUAGE plpgsql
    AS $_$
    BEGIN
        RETURN QUERY(
            select P.Article, D.ficnum, P.NbrCli, D.N_V, D.C_G, P.idSerie from Photo P
            JOIN document D ON P.article = D.photoarticle
            where  D.ficnum= $1
        );
    END; 
$_$;


ALTER FUNCTION public.rechercher_cliche_photo(character varying) OWNER TO postgres;

--
-- Name: rechercher_serie_photo(character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.rechercher_serie_photo(character varying) RETURNS TABLE(article integer, ficnum character varying, nbrcli integer, n_v character varying, c_g character varying, idserie integer)
    LANGUAGE plpgsql
    AS $_$
    BEGIN
        RETURN QUERY(
            select DISTINCT(P.Article), D.ficnum, P.NbrCli, D.N_V, D.C_G, P.idSerie from Photo P
            join Document D on P.Article=D.PhotoArticle
            join Serie S on S.IdSerie=P.IdSerie
            where S.nomSerie = $1
        );
        END;
$_$;


ALTER FUNCTION public.rechercher_serie_photo(character varying) OWNER TO postgres;

--
-- Name: rechercher_type_oeuvre(character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.rechercher_type_oeuvre(character varying) RETURNS TABLE(article integer, ficnum character varying, nbrcli integer, n_v character varying, c_g character varying, idserie integer)
    LANGUAGE plpgsql
    AS $_$
    BEGIN
        RETURN QUERY( 
            select DISTINCT(P.Article), D.ficnum,P.NbrCli,D.N_V,D.C_G,P.idSerie from Photo P
            join Document D on D.PhotoArticle=P.Article
            join IndexPersonne I on D.idoeuvre=I.idoeuvre
            join TypeOeuvre T on I.typeOeuvre=T.idType
            where T.NomType = $1
        );
    END;
$_$;


ALTER FUNCTION public.rechercher_type_oeuvre(character varying) OWNER TO postgres;

--
-- Name: split_date(character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.split_date(str character varying) RETURNS character varying[]
    LANGUAGE plpgsql
    AS $$
    DECLARE
        res varchar[] = ARRAY['', '', '']::varchar[];
        arr varchar[] := string_to_array(str, ' ');
        length integer := array_length(arr, 1);
    BEGIN
    
        IF (str IS NULL) or (char_length(str) = 0) THEN
            RETURN NULL;
        END IF;
    
        IF length = 1 THEN res[3] := arr[1];
        ELSIF length = 2 THEN 
            res[2] := month_to_num(arr[1])::varchar;
            res[3] := arr[2];
        ELSE
            res[1] := arr[1];
            res[2] := month_to_num(arr[2])::varchar;
            res[3] := arr[3];
        END IF;
                
        RETURN res;
    END;
$$;


ALTER FUNCTION public.split_date(str character varying) OWNER TO postgres;

--
-- Name: split_oeuvre(character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.split_oeuvre(str character varying) RETURNS character varying[]
    LANGUAGE plpgsql
    AS $_$
    DECLARE
        res varchar[] = ARRAY['', '']::varchar[];
    BEGIN
    
        IF str ~* '.*,\s([A-zÀ-ÿ]+\s*[A-zÀ-ÿ]+$)' THEN
            str := regexp_replace(str, '(.*),\s([A-zÀ-ÿ]+\s*[A-zÀ-ÿ]+$)', '\1|\2');
            res[1] := split_part(str, '|', 1); 
            res[2] := split_part(str, '|', 2); 
        ELSIF str ~* '^([A-zÀ-ÿ]+\s*,(?:\s*[A-zÀ-ÿ]+\s*))\(([A-zÀ-ÿ]+\s*[A-zÀ-ÿ]+)\)' THEN
            str := regexp_replace(str, '^([A-zÀ-ÿ]+\s*,(?:\s*[A-zÀ-ÿ]+\s*))\(([A-zÀ-ÿ]+\s*[A-zÀ-ÿ]+)\)', '\1|\2');
            res[1] := split_part(str, '|', 1); 
            res[2] := split_part(str, '|', 2); 
        ELSE
            res[1] := str;
        END IF;
        
        res[1] := trim(BOTH from res[1]);
        res[2] := trim(BOTH from res[2]);
                
        RETURN res;
    END;
$_$;


ALTER FUNCTION public.split_oeuvre(str character varying) OWNER TO postgres;

--
-- Name: split_string(character varying, character varying, character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.split_string(_str character varying, _delim1 character varying, _delim2 character varying) RETURNS character varying[]
    LANGUAGE plpgsql
    AS $$
    DECLARE
        result varchar[] ;
    BEGIN
        result := ARRAY(
            SELECT unnest(string_to_array(a, _delim2))
            FROM   unnest(string_to_array(_str, _delim1)) a
        );       
        --RAISE NOTICE 'split_string(): %', result;
        RETURN result;
    END;
$$;


ALTER FUNCTION public.split_string(_str character varying, _delim1 character varying, _delim2 character varying) OWNER TO postgres;

--
-- Name: traitement_c_g(character varying[]); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.traitement_c_g(strset character varying[]) RETURNS character varying[]
    LANGUAGE plpgsql
    AS $$
    DECLARE
        i int;
    BEGIN
        IF strSet is NULL THEN 
            RETURN NULL;
        END IF;
        
        FOR i IN 1 .. array_length(strSet, 1) LOOP
            IF (strSet[i] ILIKE '%nb%') THEN
                strSet[i] := 'GSC';
            ELSE
                strSet[i] := 'CLR';
            END IF;
        END LOOP;
        RETURN strSet;
    END;
$$;


ALTER FUNCTION public.traitement_c_g(strset character varying[]) OWNER TO postgres;

--
-- Name: traitement_cliche(character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.traitement_cliche(str character varying) RETURNS character varying[]
    LANGUAGE plpgsql
    AS $$
    BEGIN
        RETURN string_to_array(
            regexp_replace(str, '([0-9][0-9]*),([0-9])', '\1.\2', 'g'), ','); 
    END;
$$;


ALTER FUNCTION public.traitement_cliche(str character varying) OWNER TO postgres;

--
-- Name: traitement_coord(anyarray); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.traitement_coord(villearr anyarray) RETURNS public.coords[]
    LANGUAGE plpgsql
    AS $$
    DECLARE
        res Coords ARRAY;
        row Coords;
        i int;
        CoordX numeric;
        CoordY numeric;
        CodePostal varchar;
    BEGIN
        FOR i IN 1..coalesce(array_length(villeArr, 1), 0) LOOP
        
            SELECT INTO CodePostal viCodePostal FROM VilleTemp WHERE viVille ILIKE villeArr[i];
            SELECT viLatitude, viLongitude INTO CoordX, CoordY
            FROM VilleTemp 
            WHERE viCodePostal = CodePostal;
    
            row.CoordX := CoordX; 
            row.CoordY := CoordY;
            
            res = array_append(res, row);
        END LOOP;
        RETURN res;
    END;
$$;


ALTER FUNCTION public.traitement_coord(villearr anyarray) OWNER TO postgres;

--
-- Name: traitement_date(character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.traitement_date(_str character varying) RETURNS character varying[]
    LANGUAGE plpgsql
    AS $$
    DECLARE
        res varchar[] := string_to_array(regexp_replace(_str, '.*:(.*)', '\1'), '/');
        temp varchar;
        i int;
    BEGIN
        FOR i IN 1..coalesce(array_length(res, 1), 0) LOOP
            -- Double jour: 29-30 Juin 2011
            IF res[i] ~* '[0-9]{1,2}-[0-9]{1,2}.*' THEN
                temp := res[i];
                res := array_append(
                    res, concat(
                        split_part(temp, '-', 1), 
                        substring(temp from '\s*\w+\s*[0-9]{4}')
                    )::varchar
                );
                res := array_append(res, split_part(temp, '-', 2)::varchar);
                res := array_remove(res, res[i]);
            
            -- Double mois: 29 Juin-Juillet 2011
            ELSIF res[i] ~* '.*\w+-\w+.*' THEN
                temp := res[i];
                res := array_append(
                    res, concat(
                        split_part(temp, '-', 1), 
                        substring(temp from '\s*[0-9]{4}')
                    )::varchar
                );
                res := array_append(res, split_part(temp, '-', 2)::varchar);
                res := array_remove(res, res[i]);
            END IF;
        END LOOP;
        
        /*FOR i IN 1..coalesce(array_length(res, 1), 0) LOOP
            res[i] := trim(BOTH FROM res[i]);
        END LOOP;*/
        res := ARRAY(SELECT trim(BOTH FROM d) FROM unnest(res) d);
                
        RETURN res;
    END;
$$;


ALTER FUNCTION public.traitement_date(_str character varying) OWNER TO postgres;

--
-- Name: traitement_discriminant(character varying[]); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.traitement_discriminant(arr character varying[]) RETURNS integer[]
    LANGUAGE plpgsql
    AS $$
    DECLARE
        i int;
        length int := coalesce(array_length(arr, 1), 0);
    BEGIN
        FOR i IN 1..length LOOP
            arr[i] = i;
        END LOOP;
        RETURN arr;
    END;
$$;


ALTER FUNCTION public.traitement_discriminant(arr character varying[]) OWNER TO postgres;

--
-- Name: traitement_lambert(anyarray); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.traitement_lambert(villearr anyarray) RETURNS public.coords[]
    LANGUAGE plpgsql
    AS $$
    DECLARE
        res Coords ARRAY;
        row Coords;
        i int;
        CoordX numeric;
        CoordY numeric;
        CodePostal varchar;
    BEGIN
        FOR i IN 1..coalesce(array_length(villeArr, 1), 0) LOOP
        
            SELECT INTO CodePostal viCodePostal FROM VilleTemp WHERE viVille ILIKE villeArr[i];
            SELECT viLambertX, viLambertY INTO CoordX, CoordY
            FROM VilleTemp 
            WHERE viCodePostal = CodePostal;
    
            row.CoordX := CoordX; 
            row.CoordY := CoordY;
            
            res = array_append(res, row);
        END LOOP;
        RETURN res;
    END;
$$;


ALTER FUNCTION public.traitement_lambert(villearr anyarray) OWNER TO postgres;

--
-- Name: traitement_n_v(character varying[]); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.traitement_n_v(strset character varying[]) RETURNS character varying[]
    LANGUAGE plpgsql
    AS $$
    DECLARE
        i int;
    BEGIN
        IF strSet is NULL THEN 
            RETURN NULL;
        END IF;
        
        FOR i IN 1 .. array_length(strSet, 1) LOOP
            IF (strSet[i] LIKE '%négatif%') THEN
                strSet[i] := 'NEG';
            ELSE
                strSet[i] := 'INV';
            END IF;
        END LOOP;
        RETURN strSet;
    END;
$$;


ALTER FUNCTION public.traitement_n_v(strset character varying[]) OWNER TO postgres;

--
-- Name: traitement_oeuvre(character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.traitement_oeuvre(str character varying) RETURNS character varying[]
    LANGUAGE plpgsql
    AS $$
    BEGIN
        IF str IS NULL THEN RETURN NULL; END IF;
        RETURN split_string(
            regexp_replace(
                regexp_replace(str, '([A-Z])\s*,\s*([A-Z])', '\1 \2'), 
                           ',\s*(\()', ' \1'
            ), '|', '/'
        );
    END;
$$;


ALTER FUNCTION public.traitement_oeuvre(str character varying) OWNER TO postgres;

--
-- Name: verifier_normalisation(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.verifier_normalisation() RETURNS boolean
    LANGUAGE plpgsql
    AS $$
    DECLARE
        bVille bool;
        bIndexPersonne bool;
        bDate bool;
        bSujet bool;
        bCliche bool;
        bIco bool;
        
        nbVilleDoc integer := (SELECT COUNT(DISTINCT idVille) FROM Document); 
        nbVille integer := (SELECT COUNT(DISTINCT idVille) FROM Ville);
        nbOeuvreDoc integer := (SELECT COUNT(DISTINCT idOeuvre) FROM Document); 
        nbOeuvre integer := (SELECT COUNT(DISTINCT idOeuvre) FROM IndexPersonne);
        nbDateDoc integer:= (SELECT COUNT(DISTINCT idDate) FROM Document); 
        nbDate integer := (SELECT COUNT(DISTINCT idDate) FROM DatePhoto);
        nbSujetDoc integer := (SELECT COUNT(DISTINCT idSujet) FROM Document); 
        nbSujet integer := (SELECT COUNT(DISTINCT idSujet) FROM Sujet);
        nbClicheDoc integer := (SELECT COUNT(DISTINCT idCliche) FROM Document); 
        nbCliche integer := (SELECT COUNT(DISTINCT idCliche) FROM Cliche);
        nbIdoDoc integer := (SELECT COUNT(DISTINCT idIco) FROM Document); 
        nbIdo integer := (SELECT COUNT(DISTINCT idIco) FROM IndexIconographique);
        
    BEGIN
        SELECT INTO bVille, bIndexPersonne, bDate, bSujet, bCliche, bIco
                (nbVilleDoc = nbVille),
                (nbOeuvreDoc = nbOeuvre),
                (nbDateDoc = nbDate),
                (nbSujetDoc = nbSujet),
                (nbClicheDoc = nbCliche),
                (nbIdoDoc = nbIdo);
                
        IF bVille and bIndexPersonne and bDate and bSujet and bCliche and bIco THEN
            DROP TABLE IF EXISTS Correction;   
            RETURN true;
        ELSE
            IF not bVille THEN
                RAISE NOTICE 'Perte de donnée dans Ville: actuel(%) vs attendu(%)', nbVilleDoc, nbVille;
            END IF;
            
            IF not bIndexPersonne THEN
                RAISE NOTICE 'Perte de donnée dans IndexPersonne: actuel(%) vs attendu(%)', nbOeuvreDoc, nbOeuvre;
            END IF;
            
            IF not bDate THEN
                RAISE NOTICE 'Perte de donnée dans Date: actuel(%) vs attendu(%)', nbDateDoc, nbDate;
            END IF;
            
            IF not bSujet THEN
                RAISE NOTICE 'Perte de donnée dans Sujet: actuel(%) vs attendu(%)', nbSujetDoc, nbSujet;
            END IF;
            
            IF not bCliche THEN
                RAISE NOTICE 'Perte de donnée dans Cliche: actuel(%) vs attendu(%)', nbClicheDoc, nbCliche;
            END IF;
            
            IF not bIco THEN
                RAISE NOTICE 'Perte de donnée dans IndexIco: actuel(%) vs attendu(%)', nbIdoDoc, nbIdo;
            END IF;
            
            RETURN false;
        END IF;
    END;
$$;


ALTER FUNCTION public.verifier_normalisation() OWNER TO postgres;

--
-- Name: ville_photo(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.ville_photo() RETURNS TABLE(article integer, ficnum character varying, nbrcli integer, n_v character varying, c_g character varying, idserie integer)
    LANGUAGE plpgsql
    AS $$
    BEGIN
        RETURN QUERY
            select P.Article,D.ficnum,P.NbrCli,D.N_V,D.C_G,P.idSerie from Photo P
            join Document D on P.Article=D.PhotoArticle
            where D.idville is Null;
        END;
$$;


ALTER FUNCTION public.ville_photo() OWNER TO postgres;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: cliche; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cliche (
    idcliche integer NOT NULL,
    taille character varying(15)
);


ALTER TABLE public.cliche OWNER TO postgres;

--
-- Name: datephoto; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.datephoto (
    iddate integer NOT NULL,
    datejour character varying(2),
    datemois character varying(2),
    dateannee character varying(4) NOT NULL
);


ALTER TABLE public.datephoto OWNER TO postgres;

--
-- Name: document; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.document (
    photoarticle integer NOT NULL,
    discriminant integer NOT NULL,
    ficnum character varying(28),
    notebp character varying,
    referencecindoc character varying(6),
    n_v character varying(3),
    c_g character varying(3),
    idville integer,
    iddate integer,
    idoeuvre integer,
    idsujet integer,
    idico integer,
    idcliche integer
);


ALTER TABLE public.document OWNER TO postgres;

--
-- Name: indexiconographique; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.indexiconographique (
    idico integer NOT NULL,
    idx_ico character varying
);


ALTER TABLE public.indexiconographique OWNER TO postgres;

--
-- Name: indexpersonne; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.indexpersonne (
    idoeuvre integer NOT NULL,
    nomoeuvre character varying,
    typeoeuvre character varying
);


ALTER TABLE public.indexpersonne OWNER TO postgres;

--
-- Name: photo; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.photo (
    article integer NOT NULL,
    remarques character varying,
    nbrcli integer,
    descdet character varying,
    idserie integer,
    CONSTRAINT chk_photo_nbrcli CHECK ((nbrcli >= 1))
);


ALTER TABLE public.photo OWNER TO postgres;

--
-- Name: serie; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.serie (
    idserie integer NOT NULL,
    nomserie character varying
);


ALTER TABLE public.serie OWNER TO postgres;

--
-- Name: sujet; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.sujet (
    idsujet integer NOT NULL,
    descsujet character varying
);


ALTER TABLE public.sujet OWNER TO postgres;

--
-- Name: typeoeuvre; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.typeoeuvre (
    idtype character varying NOT NULL,
    nomtype character varying
);


ALTER TABLE public.typeoeuvre OWNER TO postgres;

--
-- Name: view_statistique; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW public.view_statistique AS
 SELECT public.func_avg_cliche_ville() AS nbre_moyen_cliche,
    public.func_avg_photo_ville() AS nbre_moyen_photo,
    ( SELECT count(*) AS count
           FROM public.date_photo() date_photo(article, ficnum, nbrcli, n_v, c_g, idserie)) AS photos_sans_date,
    ( SELECT ARRAY[(func_photos_anciennes_recents.photoarticle)::character varying, func_photos_anciennes_recents.datep] AS "array"
           FROM public.func_photos_anciennes_recents(1, true) func_photos_anciennes_recents(photoarticle, discriminant, datep)) AS photo_plus_ancienne,
    ( SELECT ARRAY[(func_photos_anciennes_recents.photoarticle)::character varying, func_photos_anciennes_recents.datep] AS "array"
           FROM public.func_photos_anciennes_recents(1, false) func_photos_anciennes_recents(photoarticle, discriminant, datep)) AS photo_plus_recente;


ALTER TABLE public.view_statistique OWNER TO postgres;

--
-- Name: ville; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ville (
    idville integer NOT NULL,
    nomville character varying,
    latitude numeric,
    longitude numeric,
    coordx numeric,
    coordy numeric
);


ALTER TABLE public.ville OWNER TO postgres;

--
-- Name: cliche cliche_taille_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cliche
    ADD CONSTRAINT cliche_taille_key UNIQUE (taille);


--
-- Name: datephoto datephoto_datejour_datemois_dateannee_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.datephoto
    ADD CONSTRAINT datephoto_datejour_datemois_dateannee_key UNIQUE (datejour, datemois, dateannee);


--
-- Name: indexiconographique indexiconographique_idx_ico_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.indexiconographique
    ADD CONSTRAINT indexiconographique_idx_ico_key UNIQUE (idx_ico);


--
-- Name: indexpersonne indexpersonne_nomoeuvre_typeoeuvre_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.indexpersonne
    ADD CONSTRAINT indexpersonne_nomoeuvre_typeoeuvre_key UNIQUE (nomoeuvre, typeoeuvre);


--
-- Name: cliche pk_cliche; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cliche
    ADD CONSTRAINT pk_cliche PRIMARY KEY (idcliche);


--
-- Name: datephoto pk_datephoto; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.datephoto
    ADD CONSTRAINT pk_datephoto PRIMARY KEY (iddate);


--
-- Name: document pk_document; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.document
    ADD CONSTRAINT pk_document PRIMARY KEY (photoarticle, discriminant);


--
-- Name: indexiconographique pk_indexico; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.indexiconographique
    ADD CONSTRAINT pk_indexico PRIMARY KEY (idico);


--
-- Name: indexpersonne pk_indexpers; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.indexpersonne
    ADD CONSTRAINT pk_indexpers PRIMARY KEY (idoeuvre);


--
-- Name: photo pk_photo; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.photo
    ADD CONSTRAINT pk_photo PRIMARY KEY (article);


--
-- Name: serie pk_serie; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.serie
    ADD CONSTRAINT pk_serie PRIMARY KEY (idserie);


--
-- Name: sujet pk_sujet; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sujet
    ADD CONSTRAINT pk_sujet PRIMARY KEY (idsujet);


--
-- Name: typeoeuvre pk_typeoeuvre; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.typeoeuvre
    ADD CONSTRAINT pk_typeoeuvre PRIMARY KEY (idtype);


--
-- Name: ville pk_ville; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ville
    ADD CONSTRAINT pk_ville PRIMARY KEY (idville);


--
-- Name: serie serie_nomserie_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.serie
    ADD CONSTRAINT serie_nomserie_key UNIQUE (nomserie);


--
-- Name: sujet sujet_descsujet_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sujet
    ADD CONSTRAINT sujet_descsujet_key UNIQUE (descsujet);


--
-- Name: typeoeuvre typeoeuvre_nomtype_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.typeoeuvre
    ADD CONSTRAINT typeoeuvre_nomtype_key UNIQUE (nomtype);


--
-- Name: ville ville_nomville_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ville
    ADD CONSTRAINT ville_nomville_key UNIQUE (nomville);


--
-- Name: datephoto trigger_verificationsdate; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trigger_verificationsdate BEFORE INSERT OR UPDATE ON public.datephoto FOR EACH ROW EXECUTE PROCEDURE public.function_verificationsdate();


--
-- Name: document fk_document_cliche; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.document
    ADD CONSTRAINT fk_document_cliche FOREIGN KEY (idcliche) REFERENCES public.cliche(idcliche);


--
-- Name: document fk_document_date; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.document
    ADD CONSTRAINT fk_document_date FOREIGN KEY (iddate) REFERENCES public.datephoto(iddate);


--
-- Name: document fk_document_idxico; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.document
    ADD CONSTRAINT fk_document_idxico FOREIGN KEY (idico) REFERENCES public.indexiconographique(idico);


--
-- Name: document fk_document_oeuvre; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.document
    ADD CONSTRAINT fk_document_oeuvre FOREIGN KEY (idoeuvre) REFERENCES public.indexpersonne(idoeuvre);


--
-- Name: document fk_document_photo; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.document
    ADD CONSTRAINT fk_document_photo FOREIGN KEY (photoarticle) REFERENCES public.photo(article);


--
-- Name: document fk_document_sujet; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.document
    ADD CONSTRAINT fk_document_sujet FOREIGN KEY (idsujet) REFERENCES public.sujet(idsujet);


--
-- Name: document fk_document_ville; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.document
    ADD CONSTRAINT fk_document_ville FOREIGN KEY (idville) REFERENCES public.ville(idville);


--
-- Name: photo fk_photo_serie; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.photo
    ADD CONSTRAINT fk_photo_serie FOREIGN KEY (idserie) REFERENCES public.serie(idserie);


--
-- Name: indexpersonne fk_typeoeuvre; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.indexpersonne
    ADD CONSTRAINT fk_typeoeuvre FOREIGN KEY (typeoeuvre) REFERENCES public.typeoeuvre(idtype);


--
-- PostgreSQL database dump complete
--

