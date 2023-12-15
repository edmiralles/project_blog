<?php

//mise à jour d'un article en BDD//

session_start();

require_once '../vendor/autoload.php';

if(!isset($_SESSION['user'])){
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: dashboard.php');
    exit;
} 

require_once '../connexion.php';
$bdd = connectBdd('root','','blog_db');

$articleId = $_GET['id'];
$title = htmlspecialchars(strip_tags($_POST['title']));
$content = htmlspecialchars(strip_tags($_POST['content']));
//applique une fonction sur les valeurs d'un tableau -> attention une seule fonction
$categories = array_map('strip_tags',$_POST['categories']);

$title = trim($title);
$content = trim($content);
$error = null;



if (!empty($title) || !empty($content) || !empty($categories)){

    //selectioner le nom actuel de l'image dans la BDD
    $selectCoverQuery = $bdd->prepare("SELECT cover FROM articles WHERE id = :id");
    $selectCoverQuery->bindValue(':id', $articleId);
    $selectCoverQuery->execute();
    $cover = $selectCoverQuery->fetchColumn();

    //vérifie si un upload doit être fait
    if(isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK){
        $typeExt = [
            'png' => 'image/png',
            'jpg' => 'image/jpg',
            'jpeg' => 'image/jpeg',
            'webp' => 'image/webp'
        ];
        $sizeMax = 1 * 1024* 1024;
        $extension = strtolower(pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION));

        if(array_key_exists($extension, $typeExt) && in_array($_FILES['cover']['type'], $typeExt)){
            if($_FILES['cover']['size'] <= $sizeMax){
                //supprime
                if(file_exists("../public/uploads/$cover")){
                    //attention unlink ne demande pas confirmation, il supprime
                    unlink("../public/uploads/$cover");
                }
                //renome
                $slugify = new \Cocur\Slugify\Slugify();
                $newName = $slugify->slugify("$title-$articleId");
                $cover = "$newName.$extension";
                //telecharge
                move_uploaded_file(
                    $_FILES['cover']['tmp_name'],
                    "../public/uploads/$cover"
                );
            }else{
                $error = 'l\'image ne doit pas dépasser les 1Mo';
            }
        }else{
           $error = 'Le fichier n\'est pas une image conforme';
        }
    }

    $query = $bdd->prepare("UPDATE articles SET title = :title, content = :content, cover = :cover WHERE id = :id");
    $query->bindValue(':title', $title);
    $query->bindValue(':content', $content);
    $query->bindValue(':cover', $cover);
    $query->bindValue(':id', $articleId);
    $query->execute();

    //mise à jour des catégories liées à l'article // d'abord suppression
    $deleteQuerry = $bdd->prepare("DELETE FROM article_categories WHERE article_id =:id");
    $deleteQuerry->bindValue(':id', $articleId);
    $deleteQuerry->execute();

    //ensuite on remet
    $insertCategoryQuery = $bdd->prepare("INSERT INTO article_categories (article_id, category_id) VALUES (:article_id, :category_id)");
    foreach($categories as $category){
    $insertCategoryQuery->bindValue(':article_id', $articleId);
    $insertCategoryQuery->bindValue(':category_id', $category);
    $insertCategoryQuery->execute();
    }

    $_SESSION['validate'] = 'Les modifications ont bien été prise en compte';
            header("Location: edit.php?id=$articleId");
            exit;
    }else{
        $error = 'Veuillez remplir tous les champs!';
    }

    if($error !== null){
       
        $_SESSION['error'] = $error;
    
        header("Location: edit.php?id=$articleId");
                exit;
    }
