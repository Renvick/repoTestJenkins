<?php
session_start()
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
        <h4>Connexion</h4>
		<fieldset>
            <form method="post" action="form_connexion.php">
                <p>	<label for="mailConnexion">Adresse Mail</label>
                <input required id="mailConnexion" type="email" placeholder="Votre adresse mail" name="mailConnexion">
				</p>
				<p>	<label for="mdpConnexion">Mot de passe</label>
					<input id="mdpConnexion" type="password" placeholder="Mot de passe" name="mdpConnexion" required>
				</p>
                <p>	<input type="submit" value="Login →" name="send" style="margin-left: 150px;" class="formbutton" />
				</p>
            </form>  
		<fieldset>			
	</div>    
</div> 
        
<div class="clear"></div>                  
<?php include("include/footer.inc.php") ?>
 
</div>
</body>
</html>
