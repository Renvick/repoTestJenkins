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
		<h3>Inscription</h3>
		
		<fieldset>
		<legend>Merci de remplir ce formulaire</legend>		
			<form id="inscription" name="inscription" method="post" action="form_inscription.php">
					<p>	<label for="prenom">Prenom</label>
						<input required id="prenom" type="text" placeholder="Votre prenom" name="prenom">
					</p>
					<p>	<label for="nom">Nom</label>
						<input required id="nom" type="text" placeholder="Votre nom" name="nom">
					</p>
					<p>	<label for="type">Type</label>
						<select required name="type" id="type">
							<option id="type" value="0">Selectionnez un type...</option>
							<option id="type" value="1">Client</option>
							<option id="type" value="2">Photographe</option>
						</select>
					</p>
					<p>	<label for="pseudo">Nom d'utilisateur souhaité</label>
						<input required id="pseudo" type="text" placeholder="Votre Pseudonyme" name="pseudo">
					</p>
					<p>	<label for="mail">Adresse e-mail</label>
						<input required id="mail" type="email" placeholder="Votre adresse mail" name="mail">
					</p>
					<p>	<label for="motpass">Mot de passe</label>
						<input required id="mdp" type="password" placeholder="Mot de passe" name="mdp">
					</p>
					<p>	<label for="r_mdp">Confirmer le mot de passe</label>
						<input required id="c_mdp" type="password" placeholder="Verification du mot de passe" name="c_mdp">
					</p>
					<p>	<input type="submit" value="S'inscrire" style="margin-left: 150px;" name="send" class="formbutton"/>
					</p>
			</form>
		</fieldset>
	</div>
        
<?php include("include/cotedroit.inc.php") ?>        
</div> 
        
<div class="clear"></div>                  
<?php include("include/footer.inc.php") ?>
 
</div>
</body>
</html>
