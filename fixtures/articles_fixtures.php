<?php

require_once '../vendor/autoload.php';

require_once '../connexion.php';
$bdd = connectBdd('root','','blog_db');

$faker = Faker\Factory::create();

$insertUser = $bdd->prepare("INSERT INTO articles (title, content, cover, publication_date, user_id) VALUES(:title, :content, :cover, :publication_date, :user_id)");

//selectionne tous les utilisateurs
$query = $bdd->query("SELECT id FROM users");
$users = $query->fetchAll();

for ($i = 0; $i < 46; $i++){
    //selectionne un utilisateur alétoire
    $user = $faker->randomElement($users);

    $insertUser->bindValue(':title', $faker->sentence);
    $insertUser->bindValue(':content', $faker->paragraphs(6, true));
    $insertUser->bindValue(':cover', $faker->imageUrl);
    //genere une date entre il y a deux ans et now, dans le bon format pour sql
    $publicationDate = $faker->dateTimeBetween('-2 years')->format('Y-m-d H:i:s');
    $insertUser->bindValue(':publication_date', $publicationDate);
    $insertUser->bindValue(':user_id', $user['id']);
    $insertUser->execute();
}

