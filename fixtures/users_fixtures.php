<?php

//chargement des dépendances composer
require_once '../vendor/autoload.php';

//connexion à la base de données
require_once '../connexion.php';
$bdd = connectBdd('root','','blog_db');

//utilisation de la bibliotheque faker
$faker = Faker\Factory::create();

//preparation de la requête d'insertion d'utilisateur
$insertUser = $bdd->prepare("INSERT INTO users (name, email, password) VALUES(:name, :email, :password)");

//generer 3 utilisateurs
for ($i = 0; $i < 8; $i++){
    $insertUser->bindValue(':name', $faker->name);
    $insertUser->bindValue(':email', $faker->unique()->email);
    $insertUser->bindValue(':password', password_hash('secret', PASSWORD_DEFAULT));
    $insertUser->execute();
}