<?php

//création d'un article en BDD//

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


$title = htmlspecialchars(strip_tags($_POST['title']));
$content = htmlspecialchars(strip_tags($_POST['content']));
$cover = $_FILES['cover'];

$title = trim($title);
$content = trim($content);
$error = null;


if (!empty($title) && !empty($content) && !empty($_POST['categories']) && (isset($cover) && $cover['error'] === UPLOAD_ERR_OK)){

    $categories = array_map('strip_tags',$_POST['categories']);
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
                
                // insérer en BDD les données
                $query = $bdd->prepare("INSERT INTO articles (title, content, cover, publication_date, user_id) VALUES (:title, :content, :cover, :publication_date, :user_id)");
                $query->bindValue(':title', $title);
                $query->bindValue(':content', $content);
                $query->bindValue(':cover', $cover['name']);
                $query->bindValue(':publication_date', (new DateTime('now'))->format('Y-m-d H:i:s'));
                $query->bindValue(':user_id', $_SESSION['user']['id']);
                $query->execute();

                // récupére l'ID de l'article nouvellement créé
                $articleId = $bdd->lastInsertId();

                //renome
                $slugify = new \Cocur\Slugify\Slugify();
                $newName = $slugify->slugify("$title-$articleId");
                $cover = "$newName.$extension";

                //telecharge
                move_uploaded_file(
                    $_FILES['cover']['tmp_name'],
                    "../public/uploads/$cover"
                );

                //met à jour le nom de l'image dans la BDD
                //obligé de renommer après l'insertion et la récupération du nouvel id car l'id est nécessaire pour le renommage
                $queryUpdateCover = $bdd->prepare("UPDATE articles SET cover = :cover WHERE id = :id");
                $queryUpdateCover->bindValue(':cover', $cover);
                $queryUpdateCover->bindValue(':id', $articleId);
                $queryUpdateCover->execute();

                //insertion dans la table de relation "article_categories"
                $insertCategoryQuery = $bdd->prepare("INSERT INTO article_categories (article_id, category_id) VALUES (:article_id, :category_id)");
                foreach($categories as $category){
                $insertCategoryQuery->bindValue(':article_id', $articleId);
                $insertCategoryQuery->bindValue(':category_id', $category);
                $insertCategoryQuery->execute();
                }
            
                $_SESSION['success'] = 'Votre article a bien été ajouté';
                        header("Location: dashboard.php");
                        exit;
            }else{
                $error = 'l\'image ne doit pas dépasser les 1Mo';
            }
        }else{
           $error = 'Le fichier n\'est pas une image conforme';
        }
}else{
    $error = 'Veuillez remplir tous les champs!';
}

    if($error !== null){
       
        $_SESSION['error'] = $error;
    
        header("Location: add.php");
                exit;
    }
