<?php
header('Refresh: 5; URL=index.php');
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
	<div id="content">
		<?php
			//Déconnexion : Supression du log de Session
			unset($_SESSION['log']);
			echo "Vous venez de vous déconnecter. Merci de votre visite !<br />";
			echo "Vous allez être redirigé vers l'accueil.";
			//Changement de l'état de 3 menus (Visible : Connexion et S'inscrire - Caché : Déconnexion)
            $update= $bdd->query('UPDATE menu SET DispoMenu=1 WHERE nomMenu LIKE "Connexion"');
			$update= $bdd->query('UPDATE menu SET DispoMenu=1 WHERE nomMenu LIKE "S\'inscrire"');
			$update= $bdd->query('UPDATE menu SET DispoMenu=0 WHERE nomMenu LIKE "Deconnexion"');
		?>
	</div>
        
<?php include("include/cotedroit.inc.php") ?>        
</div> 
        
<div class="clear"></div>                  
<?php include("include/footer.inc.php") ?>
 
</div>
</body>
</html>