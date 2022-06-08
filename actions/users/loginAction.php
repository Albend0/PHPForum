<?php
session_start();
require('actions/database.php');

// Validation du formulaire //
if(isset($_POST['validate'])){

    // Vérifie si les champs du formulaire ont été remplis //
    if(!empty($_POST['pseudo']) AND !empty($_POST['password'])){
        
        // Données de l'utilisateur //
        $user_pseudo = htmlspecialchars($_POST['pseudo']);
        $user_password = htmlspecialchars($_POST['password']);

        // Vérifier si le pseudo est correct //
        $checkIfUserExists = $bdd->prepare('SELECT * FROM users WHERE pseudo = ?');
        $checkIfUserExists->execute(array($user_pseudo));


        if($checkIfUserExists->rowCount() > 0){

            // Récupérer les données de l'utilisateur //
            $usersInfos = $checkIfUserExists->fetch();

            // Vérifier si le mot de passe est correct //
            if(password_verify($user_password, $usersInfos['mdp'])){

                // Authentifier l'utilisateur sur le site et récupérer ses données dans des variables de session //
                $_SESSION['auth'] = true;
                $_SESSION['id'] = $usersInfos['id'];
                $_SESSION['lastname'] = $usersInfos['nom'];
                $_SESSION['firstname'] = $usersInfos['prenom'];
                $_SESSION['pseudo'] = $usersInfos['pseudo'];

                // Redirige vers la page d'accueil //
                header('Location: index.php');
     
            }else{
                $errorMsg = "Mot de passe incorrect";
            }

        }else{
            $errorMsg = "Votre pseudo est incorrect";
        }
    
    }else{
        $errorMsg = "Veuillez compléter tous les champs";
    }


}