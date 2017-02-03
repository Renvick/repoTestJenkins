<?php 
header('Refresh: 10; URL=index.php');
session_start();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("include/head.inc.php") ?>
</head>
<body>
    
    <?php include("include/connexionbdd.inc.php") ?>   
 
        
    <div id="container">
    <?php include("include/header.inc.php") ?>
        
    <?php include("include/menu.inc.php") ?>
        
    <div id="body">
	<?php
            if($_POST['type']==0){ 
                echo 'Veuillez selectionner un type d\'utilisateur : Client ou Photographe';
            }
            else{
		//Requête servant à récupérer dans la BdD le Mail des utilisateurs en fonction des données entrées dans le formulaire                
                $reponse = $bdd->prepare('SELECT idClient FROM clients WHERE mailClient = ?');
                $reponse->execute(array($_POST['mail'])); 
                //Compte le nombre de résultats
                $count = $reponse->rowCount();                    

                $reponse = $bdd->prepare('SELECT idPhotographe FROM photographes WHERE mailPhotographe= ?');
                $reponse->execute(array($_POST['mail'])); 
                //Compte le nombre de résultats
                $count = $count+$reponse->rowCount(); 
                
		//S'il y a un retour alors le compte/mail existe déjà -> Message d'erreur
		if ($count != 0)
		{
			echo "Ce mail est déjà associé à un compte. Veuillez en choisir un autre";
		}
		//Sinon, inscription de l'utilisateur dans la BdD
		else
		{			
                        if($_POST['type']==1)
                            {$req = $bdd->prepare('INSERT INTO clients(prenomClient, nomClient, pseudoClient, mailClient, mdpClient) VALUES(:prenom, :nom, :pseudo, :mail, :mdp)');}
                        else
                            {$req = $bdd->prepare('INSERT INTO photographes(prenomPhotographe, nomPhotographe, pseudoPhotographe, mailPhotographe, mdpPhotographe) VALUES(:prenom, :nom, :pseudo, :mail, :mdp)');}
                        $req->execute(array(
				'prenom' => $_POST['prenom'],
				'nom' => $_POST['nom'],
				'pseudo' => $_POST['pseudo'],
				'mail' => sha1($_POST['mail']),
				'mdp' => $_POST['mdp']
			));
			echo "Merci de vous être enregistré ".$_POST['prenom']." ".$_POST['nom']."!";
			$reponse->closeCursor();
		}
            }
	?>
	<div id="content">
	</div>  
<?php include("include/cotedroit.inc.php") ?> 	
</div> 
        
<div class="clear"></div>                  
<?php include("include/footer.inc.php") ?>
 
</div>
</body>
</html>