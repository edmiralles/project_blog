<?php
session_start();

if(!isset($_SESSION['user'])){
    header('Location: index.php');
    exit;
}

require_once '../connexion.php';
$bdd = connectBdd('root','','blog_db');

$query = $bdd->query("SELECT * FROM categories");
$categories = $query->fetchAll();
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
            <h1>Ajout d'un article</h1>
            <a href="dashboard.php">Retour</a>
            <form action="create_article.php" method="post" enctype="multipart/form-data">
                <?php
                    if(isset($_SESSION['error'])):
                    ?>
                <div class="alert alert-danger">
                    <?php echo $_SESSION['error']; ?>
                </div>
                <?php
                    unset($_SESSION['error']);
                    endif
                    ?>
                <div class="mb-3">
                    <label for="title" class="form-label">titre</label>
                    <input type="text" class="form-control" id="title" name="title"
                        placeholder="ajoutez un titre" required="required">
                </div>
                <div class="mb-3">
                    <label for="content" class="form-label">Contenu</label>
                    <textarea class="form-control" id="content" name="content"
                        placeholder="ajoutez votre contenu" required="required"></textarea>
                </div>
                <div class="mb-3">
                    <label for="cover" class="form-label">Couverture</label>
                    <input type="file" class="form-control" id="cover" name="cover" required="required">
                </div>
                <div class="mb-3">
                    <label for="categories">Categories</label>
                    <select multiple class="form-select form-select-lg mb-3" id="categories" name="categories[]">
                        <?php foreach($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>">
                            <?php echo $category['name']; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </body>
    
    </html>


</body>
</html>