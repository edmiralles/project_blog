<?php
//demarrage de la session
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion à l'administration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <div class="container p-3">
        <h1>Connexion à l'administration</h1>
        <form action="login.php" method="post">
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
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name ="email" aria-describedby="emailHelp" required="required">
                <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
            </div>
            <div class="mb-3">
                <label for="mdp" class="form-label">Password</label>
                <input type="password" class="form-control" id="mdp" name ="mdp" required="required">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</body>

</html>


<?php
