<?php
session_start();

if(!isset($_SESSION['user'])){
    header('Location: index.php');
    exit;
}
//verifie si le parametre id est present et/ou non vide
if(empty($_GET['id'])){
    header('Location: dashboard.php');
    exit;
}

require_once '../connexion.php';
$bdd = connectBdd('root','','blog_db');

$articleId = $_GET['id'];

$query = $bdd->prepare("SELECT * FROM articles WHERE articles.id = :id");
  $query->bindValue(':id', $articleId);
  $query->execute();
  $article = $query->fetch();

  //si aucun article n'existe avec cet id, redirection
  //verifier que l'article sékectionner appartient bien à l'utilisateur
  if(!$article || $article['user_id'] !== $_SESSION['user']['id']){
    header('Location: dashboard.php');
    exit;
  }

  $query = $bdd->query("SELECT * FROM categories");
  $categories = $query->fetchAll();

  $query = $bdd->prepare("SELECT category_id FROM article_categories WHERE article_id = :id");
  $query->bindValue(':id', $articleId);
  $query->execute();
  
  /**
   * PDO::FETCH_COLUMN
   * Retourne un tableau indexé contenant les valeurs extraites de la requête SQL pour une seule colonne
   */
  $articlesCategories = $query->fetchAll(PDO::FETCH_COLUMN);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
<div class="container p-3">
        <h1>Modifications de l'article</h1>
        <a href="dashboard.php">Retour</a>
        <form action="update_article.php?id=<?php echo $article['id']; ?>" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title" class="form-label">titre</label>
                <input type="text" class="form-control" id="title" name ="title" value="<?php echo $article['title']; ?>">
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Contenu</label>
                <textarea class="form-control" id="content" name ="content" rows="6"><?php echo $article['content']; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="cover" class="form-label">Couverture</label>
                <input type="file" class="form-control" id="cover" name ="cover">
            </div>
            <div class="mb-3">
                <label for="categories">Categories</label>
                <select multiple class="form-select form-select-lg mb-3" id="categories" name="categories[]">
                    <?php foreach($categories as $category): ?>
                    <option value="<?php echo $category['id']; ?>"
                    <?php echo in_array($category['id'], $articlesCategories) ? 'selected' : '' ?>
                    >
                    <?php echo $category['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</body>
</html>