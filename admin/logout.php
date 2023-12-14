<?php
session_start();
//destruction de la session user
unset($_SESSION['user']);
//redirection vers le formulaire de connexion
header('Location: index.php');