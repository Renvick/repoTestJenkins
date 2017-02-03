<?php
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
            
            // CAS : Suppression photo
            if(isset($_POST['idPhoto'])){
                $indice=$_POST['idPhoto']-1;
                unset($_SESSION['caddie'][$indice]);
                $_SESSION['caddie']=  array_slice($_SESSION['caddie'], 0);
            }

            // CAS : Achat
            if(isset($_POST['idPhotoAchat'])){
                $stringListeIdPhoto=substr($_POST['idPhotoAchat'],0,-1);
                $arrayListeIdPhoto=array();
                $arrayListeIdPhoto=explode(',', $stringListeIdPhoto);
                $idClient=$_POST['idClientAchat'];

                // Si le client possède suffisament de crédits
                $prixTotal=$_POST['prixTotalAchat'];
                if($_SESSION['credit']>=$prixTotal){
                    foreach ($arrayListeIdPhoto as $id){
                     $bdd->query("INSERT INTO acheter(idPhoto, idClient) VALUES($id,$idClient)");              
                    }
                    
                    //Paiement en crédit
                    $_SESSION['credit']-=$prixTotal;
                    $reqUpdateCredit=$bdd->query("UPDATE clients SET nbCredit=nbCredit-$prixTotal WHERE idClient=$idClient");
                    echo "Merci de votre achat !";
                    //Vidage du panier
                    $array_vide=array();
                    $_SESSION['caddie']=$array_vide;
                }
            }
            
            // SI le cadie n'est pas vide
            if(!empty($_SESSION['caddie'])){               
                
                $caddie=$_SESSION['caddie'];
                $idArray=array();
                $prixTotal=0;
                for ($i = 0 ; $i < count($caddie) ; $i++){
                    $id = $caddie[$i]["id"];
                    array_push($idArray, $id);
                }
                $idString=implode(',', $idArray);                      
                $req=$bdd->query("SELECT * FROM photos, photographes WHERE photos.idPhotographe=photographes.idPhotographe AND idPhoto IN($idString)");
                $listeId="";
                
                while($photos=$req->fetch()){
                    $listeId=$listeId.$photos['idPhoto'].",";
                    ?>  <table border="0">
                            <tr>
                                <td><img src='<?php echo $photos["lienPhoto"]; ?>' 
                                     alt='Photographie de <?php echo $photos["nomPhotographe"]." ".$photos["prenomPhotographe"]; ?>'
                                     width='150' height='150' /></td>
                                <td><img src='images/icone_credit.png' 
                                     alt='icone credit' /> 
                                <?php echo $photos['prixPhoto']; 
                                $prixTotal+=$photos['prixPhoto'];?></td>
                                <td>
                                    <form method="post" action="caddie.php">
                                        <input name="idPhoto" type="hidden" value="<?php echo $photos['idPhoto']; ?>" />
                                        <input name="retirer" type="image" src="images/icone_panier_supprimer.png"/>
                                    </form>
                                </td>                                
                            </tr>
                        </table>
            <?php
                }               
                echo 'Total : '.$prixTotal.' <img src=\'images/icone_credit.png\' alt=\'icone credit\' />';
            ?>
            <p>
                <form method="post" action="caddie.php">
                    <input type="hidden" name="idPhotoAchat" value="<?php echo $listeId; ?>" />
                    <input type="hidden" name="idClientAchat" value="<?php echo $_SESSION['id']; ?>" />
                    <input type="hidden" name="prixTotalAchat" value="<?php echo $prixTotal; ?>" />
                    <input type="submit" value="ACHETER" name="send" class="formbutton" style="margin-left: 83%;"/>
                </form>
            </p>
            <?php
        }else echo "<h4>Votre panier est vide ! </h4>";
            ?>
	</div>
        
<?php include("include/cotedroit.inc.php") ?>        
</div> 
        
<div class="clear"></div>                  
<?php include("include/footer.inc.php") ?>
 
</div>
</body>
</html>