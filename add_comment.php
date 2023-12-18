<?php

session_start();

if(empty($_GET['id'])){
    header('Location: index.php');
    exit;
}

require_once 'connexion.php';
$bdd = connectBdd('root','','blog_db');

//vérifier qu'un article existe sous cet ID
$query = $bdd->prepare("SELECT id FROM articles WHERE id = :id");
$query->bindValue(':id', $_GET['id']);
$query->execute();

$article = $query->fetch();

//si l'article n'existe pas, redirection
if (!$article){
    header('Location: index.php');
    exit;
}

$comment = htmlspecialchars(strip_tags($_POST['comment']));

//vérifier si le champ est bien rempli
if(!empty($comment)){
    
    //insertion en BDD
    $queryInsertInsertComment = $bdd->prepare("INSERT INTO comments(content, comment_date, user_id, article_id)
    VALUES (:content, :comment_date, :user_id, :article_id)");

    $queryInsertInsertComment->bindValue(':content', $comment);
    $queryInsertInsertComment->bindValue(':comment_date', (new DateTime())->format('Y-m-d H:i:s'));
    $queryInsertInsertComment->bindValue(':user_id', $_SESSION['user']['id']);
    $queryInsertInsertComment->bindValue(':article_id', $_GET['id']);
    $queryInsertInsertComment->execute();

    $_SESSION['success'] = 'Votre commentaire a été correctement publié';
}else{
    $_SESSION['error'] = 'Veuillez écrire un commentaire';
}

header("Location: article.php?id={$_GET['id']}#comments");