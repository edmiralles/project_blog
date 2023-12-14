<?php

//generer toutes les fixtures

/**
 * http://localhost/PHP/blog/fixtures/index.php?truncate=1
 * si le parametre "truncate" est présent dans l'url, on vide nos tables SQL
 */

if (isset($_GET['truncate'])){
    //connexion à la base données
    require_once '../connexion.php';
    $bdd = connectBdd('root','','blog_db');

    //requetes pour vider les tables SQL
    //ATTENTION// l'ordre est inversé par rapport à la construction de mes tables
    //but: supprimer les clés étrangères dans le bon ordre pour ne pas qu'il bloque
    $bdd->query("
    SET FOREIGN_KEY_CHECKS = 0;
    TRUNCATE article_categories; 
    TRUNCATE comments; 
    TRUNCATE articles; 
    TRUNCATE categories; 
    TRUNCATE users;
    SET FOREIGN_KEY_CHECKS = 1;
    ");
}



//liste des fichiers afin de generer les jeux de données d'essais dans l'ordre d'insertion en BDD

require_once 'users_fixtures.php';
require_once 'categories_fixtures.php';
require_once 'articles_fixtures.php';
require_once 'comments_fixtures.php';
require_once 'articles_categories_fixtures.php';

