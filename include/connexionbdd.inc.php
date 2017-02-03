<?php
        //Connection à la BdD
        //Hébergée en local
        //Nom de la base de donnée : photoforyou
        //identifiant : root   /  mot de passe : N/A
        try
        {
                $bdd = new PDO('mysql:host=localhost;dbname=photoforyou', 'root', '');
        }
        catch (Exception $e)
        {
                die('Erreur : ' . $e->getMessage());
        }
 ?>

