<?php

/**
 * Connexion à la base de données
 * @throws Exception
 */
function connectBdd(
    string $user, 
    string $password, 
    string $database, 
    string $host = 'localhost' 
)
{
    try {
        $bdd = new PDO(
            "mysql:host=$host;dbname=$database",
            $user,
            $password,
            [
                
                PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,                
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
    } catch(Exception $exception) {
        throw new Exception(
            "La connexion à la base de donnée a échouée : {$exception->getMessage()}"
        );
    }

    return $bdd;
}
