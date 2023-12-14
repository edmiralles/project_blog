<?php
//demarrer la session, doit etre placé au plus haut dans le code
session_start();

/**
 * login.php
 * permet de vérifier si un utilisateur peut accéder à l'administration
 */

 /**
  * logique :
  * 1. Vérifier si le formulaire est complet -> sinon erreur
  * 2. Nettoyer les données issues du formulaire
  * 3. Selectionner l'utilisateur en BDD via son email -> sinon erreur
  * 4. Verifier si le mdp du formulaire correspond à celui en BDD -> sinon erreur
  * 5. Rediriger l'utilisateur vers la page "dashboard.php"
  */
  require_once '../connexion.php';
  $email = htmlspecialchars(strip_tags($_POST['email']));
  $mdp = htmlspecialchars(strip_tags($_POST['mdp']));
  
  $email = trim($email);
  $mdp = trim($mdp);
  $error = null;
  
  if (empty($email) || empty($mdp)) {
      $error = 'Veuillez remplir tous les champs!';
    }else{
      if (filter_var($email, FILTER_VALIDATE_EMAIL)){
        
        $bdd = connectBdd('root','','blog_db');
        $query = $bdd->prepare("SELECT * FROM users WHERE email = :email");
        $query->bindvalue(':email', $email);
        $query->execute();
        $user = $query->fetch();
        //fetch() retourne un tableau associatif contenant soit:
        // les informations d'un utilisateur
        // false
        if($user && password_verify($mdp, $user['password'])){
            //password-verify arrive à comparer le mdp au mdp haché

            //stocker les infos de l'utilisateur en session
            $_SESSION['user'] = $user;
            //header va renvoyer vers l'url   
            header('Location: dashboard.php');
            exit;
        }else { 
            $error = 'identifiants invalides';
        } 
      }else{
          $error = 'votre adresse email est invalide';
      }
    }

//gestion de nos erreurs
if($error !== null){
    //declaration d'une session contenant l'erreur
    $_SESSION['error'] = $error;

    header('Location: index.php');
            exit;
}