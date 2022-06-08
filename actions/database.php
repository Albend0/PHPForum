<?php
try {
    $bdd = new PDO('mysql:host=localhost;dbname=forum;charset=utf8;', 'root', 'root');
    // Activation des erreurs PDO
 $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(Exception $e) {
    die('Une erreur s\'est produite : ' . $e->getMessage());
}
