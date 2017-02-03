<?php
session_start();
if(isset($_POST['credit']) && $_SESSION['id']){ header('Refresh:0','URL=achatcredits.php'); }
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
            if(isset($_POST['credit']) && $_SESSION['id']){
                
            $req=$bdd->prepare('SELECT * FROM clients WHERE idClient=?');
            $req->execute(array($_SESSION['id']));
            $nombreCredit=0;
            while($resultat=$req->fetch()){
                $nombreCredit=$resultat['nbCredit'];                
            }
            
                switch ($_POST['credit']){
                    case 0:
                        $nombreCredit=$nombreCredit+1;
                        break;
                    case 1:
                        $nombreCredit=$nombreCredit+2;
                        break;
                    case 2:
                        $nombreCredit=$nombreCredit+5;
                        break;
                    case 3:
                        $nombreCredit=$nombreCredit+10;
                        break;
                    case 4:
                        $nombreCredit=$nombreCredit+20;
                        break;
                    case 5:
                        $nombreCredit=$nombreCredit+50;
                        break;
                    case 6:
                        $nombreCredit=$nombreCredit+100;
                        break;
                    case 7:
                        $nombreCredit=$nombreCredit+200;
                        break;
                }
                $req=$bdd->prepare('UPDATE clients SET nbCredit=? WHERE idClient=?');
                $req->execute(array($nombreCredit, $_SESSION['id'])
                      );
                $_SESSION['credit']=$nombreCredit;
            } 
            ?>
            
            <fieldset>
		<legend>Acheter des crédits <img src='images/icone_credit_16px.png' alt='icone credit' /></legend>		
                <form id="inscription" name="inscription" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <p><label for="label credit">Crédits</label>
                                <select required name="credit" id="credit">
                                        <option id="type" value="0">1 Crédit - 5€</option>
                                        <option id="type" value="1">2 Crédits - 8€</option>
                                        <option id="type" value="2">5 Crédits - 15€</option>
                                        <option id="type" value="3">10 Crédits - 25€</option>
                                        <option id="type" value="4">20 Crédits - 35€</option>
                                        <option id="type" value="5">50 Crédits - 50€</option>
                                        <option id="type" value="6">100 Crédits - 70€</option>
                                        <option id="type" value="7">200 Crédits - 100€</option>
                                </select>
                        </p> 
                    <p>	<input type="submit" value="Acheter" style="margin-left: 150px;" name="acheter" class="formbutton"/>
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
