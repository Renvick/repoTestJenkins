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
            <fieldset>
                <legend>Contactez moi</legend>
                <form action="mailto:expert.adrien@gmail.com" method="post">
                    <table border="0">
                        <tr>
                            <td><label>Entrez votre nom complet.</label></td>
                            <td><input type="text" placeholder="Nom / Prénom" /></td>
                        </tr>
                        <tr>
                            <td><label>Entrez votre adresse email.</label></td>
                            <td><input type="text" value="Addresse Email" /></td>
                        </tr>
                        <tr>
                            <td><label>Entrez le sujet de votre message.</label></td>
                            <td><input type="text" value="Sujet" /></td>
                        </tr>
                        <tr>
                            <td><label class="msg">Entrez votre Message.</label></td>
                            <td><textarea cols="35" rows="8">Message</textarea></td>							
                        </tr>
                            <td></td>
                            <td><input type="submit" value="Envoyer" class="btn3" /></td>
                    </table>
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
