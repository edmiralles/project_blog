<?php

require_once '../vendor/autoload.php';

require_once '../connexion.php';
$bdd = connectBdd('root','','blog_db');

$faker = Faker\Factory::create();

$insertUser = $bdd->prepare("INSERT INTO categories (name) VALUES(:name)");

for ($i = 0; $i < 6; $i++){
    $insertUser->bindValue(':name', $faker->word);
    $insertUser->execute();
}