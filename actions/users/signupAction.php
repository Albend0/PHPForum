<?php
session_start();
require('actions/database.php');

// Validation du formulaire //
if(isset($_POST['validate'])){

    // Vérifie si les champs du formulaire ont été remplis //
    if(!empty($_POST['pseudo']) AND !empty($_POST['lastname']) AND !empty($_POST['firstname']) AND !empty($_POST['password'])){
        
        // Récupère les valeurs du formulaire et les stock dans des variables //
        $user_pseudo = htmlspecialchars($_POST['pseudo']);
        $user_lastname = htmlspecialchars($_POST['lastname']);
        $user_firstname = htmlspecialchars($_POST['firstname']);
        $user_password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Permet de chiffrer le mot de passe //
        
        // Vérifie si l'utilisateur existe déjà //
        $checkIfUserAlreadyExists = $bdd->prepare('SELECT pseudo FROM users WHERE pseudo = ?');
        $checkIfUserAlreadyExists->execute(array($user_pseudo));

        // Si il n'existe pas, on l'ajoute dans la BDD //
        if($checkIfUserAlreadyExists->rowCount() == 0){
            $insertUserOnWebsite = $bdd->prepare('INSERT INTO users (pseudo, nom, prenom, mdp) VALUES (?, ?, ?, ?)');
            $insertUserOnWebsite->execute(array($user_pseudo, $user_lastname, $user_firstname, $user_password));
        
            // On récupère les infos de l'utilisateur pour la connexion //
            $getInfosOfThisUserReq = $bdd->prepare('SELECT id, pseudo, nom, prenom FROM users WHERE nom = ? AND prenom = ? AND pseudo');
            $getInfosOfThisUserReq->execute(array($user_lastname, $user_firstname, $user_pseudo));

            $usersInfos = $getInfosOfThisUserReq->fetch();

            // Authentifier l'utilisateur sur le site et récupérer ses données dans des variables de session //
            $_SESSION['auth'] = true;
            $_SESSION['id'] = $usersInfos['id'];
            $_SESSION['lastname'] = $usersInfos['nom'];
            $_SESSION['firstname'] = $usersInfos['prenom'];
            $_SESSION['pseudo'] = $usersInfos['pseudo'];

            // Permet de rediriger l'utilisateur connecté à une page d'accueil //
            header('Location: index.php'); 

        // Si il existe, on affiche un message d'erreur //
        }else{
            $errorMsg = "L'utilisateur existe déjà sur le site";
        }
    // Si les champs n'ont pas été remplis, on affiche un message d'erreur //
    }else{
        $errorMsg = "Veuillez compléter tous les champs";
    }


}