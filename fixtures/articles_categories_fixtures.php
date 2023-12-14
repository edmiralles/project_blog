<?php

require_once '../vendor/autoload.php';

require_once '../connexion.php';
$bdd = connectBdd('root','','blog_db');

$faker = Faker\Factory::create();

$insertUser = $bdd->prepare("INSERT INTO article_categories (article_id, category_id) VALUES(:article_id, :category_id)");

$query = $bdd->query("SELECT id FROM categories");
$categories = $query->fetchAll();
$query = $bdd->query("SELECT id FROM articles");
$articles = $query->fetchAll();

foreach ($articles as $article){

    $iteration = rand(1, 4);
    
    for ($j = 0; $j < $iteration; $j++){

        $categorie = $faker->randomElement($categories);

        $insertUser->bindValue(':article_id', $article['id']);
        $insertUser->bindValue(':category_id', $categorie['id']);    
        $insertUser->execute();
}
}
