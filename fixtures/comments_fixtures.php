<?php

require_once '../vendor/autoload.php';

require_once '../connexion.php';
$bdd = connectBdd('root','','blog_db');

$faker = Faker\Factory::create();

$insertUser = $bdd->prepare("INSERT INTO comments (content, comment_date, user_id, article_id) VALUES(:content, :comment_date, :user_id, :article_id)");

$query = $bdd->query("SELECT id FROM users");
$users = $query->fetchAll();
$query = $bdd->query("SELECT id, publication_date FROM articles");
$articles = $query->fetchAll();


for ($i = 0; $i < 180; $i++){

    $user = $faker->randomElement($users);
    $article = $faker->randomElement($articles);

    $insertUser->bindValue(':content', $faker->text);
    //pas de date avant la date de publication de l'article
    $commentDate = $faker->dateTimeBetween($article['publication_date'])->format('Y-m-d H:i:s');
    $insertUser->bindValue(':comment_date', $commentDate);
    $insertUser->bindValue(':user_id', $user['id']);
    $insertUser->bindValue(':article_id', $article['id']); 
    $insertUser->execute();
}
