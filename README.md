# PhotographiesArchiveSQL
Demonstration of raw data handling using only PostgreSQL

## 1. Présentation du projet
Le projet de l’UE Bases de Données vous permettra de reprendre toutes les connaissances et compétences acquises ou renforcées depuis le début de l’UE sur des données réelles. En effet, ces données proviennent des Archives Départementales (AD) du Loiret (45) et concernent des photographies réalisées des années 1960 aux années 2000. Dans le cadre de la mise en ligne des archives, ces photographies doivent être numérisées et feront l’objet d’un géo-référencement. Mais avant cela, il faut pouvoir utiliser les informations, annotations et données qui les concernent. Vous devrez, par équipe de 3 personnes maximum, créer une base de données, un ensemble de requêtes, de vues, de transactions et de triggers afin de fiabiliser les données et de faciliter le travail des archivistes. Pour cela, il vous faudra observer les données, les comprendre, les corriger, les augmenter, les organiser dans une base de données normalisée et optimisée et proposer un ensemble de requêtes qui permettent aux archivistes de gérer ces données le plus efficacement possible. Votre travail sera suivi au second semestre d’un second projet sur le même thème mais sur d’autres aspects de gestion de bases de données, en particulier sur l’indexation et l’optimisation des requêtes.

## 2. Les données
Les données sont fournies en format CSV avec les intitulés suivants :
  1. Référence Cindoc : identifiant dans le logiciel de Gestion Electronique de Document (GED) utilisé par les archives
  2. Série : nom de la série à laquelle appartiennent les photographies
  3. Article : numéro de la photographie
  4. Discriminant : complément éventuel du numéro précédent (bis, ter, a, b…)
  5. Ville
  6. Sujet : descriptif de la photographie selon un thésaurus (voir fichier joint)
  7. Description détaillée
  8. Date
  9. Notes de bas de page : informations supplémentaires sur les photographies
  10. Index personnes : personnage historique représenté dans le cliché
  11. Fichier numérique : nom du fichier numérique associé à la photographie
  12. Index iconographique : descriptif selon un thésaurus iconographique (voir fichier joint)
  13. Nombre de clichés : nombre de documents pour la photographie
  14. Taille du/des cliché(s)
  15. Négatif ou inversible : support du cliché
  16. Couleur ou noir et blanc
  17. Remarques : informations du photographe des archives sur les photographies

## Organisation du répertoire:
|-/
|----docs: les pdfs des étapes 1 et 2
|----pdfsrc: les fichiers csv nécessaire pour les scripts sql
|----sql: les fichiers sql
|----grafana: le tableau de bord

## Rapport:
[Trello](https://trello.com/b/traOSgOr/tea-bd)

[![Waffle.io - Issues in progress](https://badge.waffle.io/mhoangvslev/PhotographiesArchiveSQL.png?label=in%20progress&title=In%20Progress)](http://waffle.io/mhoangvslev/PhotographiesArchiveSQL)

- Visualiser les notebooks avec NBViewer:
[requetes.ipynb (nbviewer)](http://nbviewer.jupyter.org/github/mhoangvslev/PhotographiesArchiveSQL/blob/master/requetes.ipynb)

[pretraitement.ipynb (nbviewer)](http://nbviewer.jupyter.org/github/mhoangvslev/PhotographiesArchiveSQL/blob/master/pretraitement.ipynb)
