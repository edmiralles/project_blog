<?php
session_start();

//verifier si l'utilisateur peut accéder à cette page
if(!isset($_SESSION['user'])){
    header('Location: index.php');
    exit;
}
require_once '../connexion.php';
$bdd = connectBdd('root','','blog_db');

//selectionne tous les articles avec leurs categories
$query = $bdd->prepare("SELECT articles.id, articles.title, articles.publication_date,GROUP_CONCAT(categories.name SEPARATOR ', ') AS categories
 FROM articles LEFT JOIN article_categories ON article_categories.article_id = articles.id 
 LEFT JOIN categories ON categories.id = article_categories.category_id
 WHERE user_id = :id GROUP BY articles.id ORDER BY articles.publication_date DESC");
 $query->bindValue(':id', $_SESSION['user']['id']);
 $query->execute();
 $articles = $query->fetchAll();


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <nav class="navbar bg-body-tertiary">
        <form class="container-fluid justify-content-start">
            <a href="add.php" class="btn btn-outline-success me-2" >Nouvel article</a>
            <a href="logout.php" class="btn btn-sm btn-outline-secondary" >Déconnexion</a>
        </form>
    </nav>
    <h1>Administration</h1>
    

    <div class="container mt-5">
        <h2 class="mb-4">Liste des articles </h2>

                  <!-- Message de succès -->
                  <?php if(isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?php
                    echo $_SESSION['success'];
                    unset($_SESSION['success']);
                    ?>
                </div>
            <?php endif; ?>

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>TITLE</th>
                    <th>CATEGORIES</th>
                    <th>DATE</th>
                    <th>ACTIONS</th>
                </tr>
            </thead>
            <tboddy>
                <?php foreach($articles as $article): ?>
                <tr>
                    <td>
                        <?php echo $article['id']; ?>
                    </td>
                    <td>
                        <?php echo $article['title']; ?>
                    </td>
                    <td>
                        <?php echo $article['categories']; ?>
                    </td>
                    <td>
                        <?php
                        //changer le format de la date
                        $date= DateTime::createFromFormat('Y-m-d H:i:s', $article['publication_date']);
                        //modifier la date
                        //$date->modify('+2months');
                        //$date->modify('+1year');
                        echo $date->format('d.m.Y'); ?>
                    </td>
                    <td>
                        <a href="edit.php?id=<?php echo $article['id']; ?>" class="btn btn-light btn-sm">Editer</a> -
                        <a href="delete_article.php?id=<?php echo $article['id']; ?>" class="btn btn-danger btn-sm" 
                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?');">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</body>

</html>


