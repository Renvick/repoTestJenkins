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
                if(isset($_SESSION['id'])){
                    // Calcul du nombre de photos dans l'album
                    $resultat=$bdd->query("SELECT calculNbPhotoUser(".$_SESSION['id'].",".$_SESSION['type'].") AS total");
                    while($total=$resultat->fetch()){
                        $nombrePhoto=$total[0];
                    }
                    
                    // CAS : Client
                    if($_SESSION['type']==1){                        
                        echo "<h4>Vous avez acheté ".$nombrePhoto." photo(s)</h4>";
                        $idClient=$_SESSION['id'];
                        $resultatC=$bdd->query("SELECT * FROM acheter, photos WHERE acheter.idPhoto=photos.idPhoto AND idClient=$idClient");
                        while($selectPhotos=$resultatC->fetch()){
                            ?>
                                <p>
                                    <img src='<?php echo $selectPhotos['lienPhoto']; ?>' 
                                    alt='Photographie : <?php echo $selectPhotos['nomPhoto']; ?>' /><br />
                                    <a href="<?php echo $selectPhotos['lienPhoto']; ?>" target="_blank"><input type="button" value="Télécharger"/></a>
                                </p>
                            <?php
                        }
                    }
                    
                    // CAS : Photographe
                    else{
                        echo "<h4>Vous avez mis en vente ".$nombrePhoto." photo(s)</h4>";
                        $resultatP=$bdd->prepare("SELECT * FROM photos WHERE idPhotographe=?");
                        $resultatP->execute(array($_SESSION['id']));
                        while($selectPhotos=$resultatP->fetch()){
                            ?>
                                <img src='<?php echo $selectPhotos['lienPhoto'] ?>' 
                                alt='Photographie : <?php echo $selectPhotos['nomPhoto'] ?>' /><br />
                            <?php
                            $id=$selectPhotos['idPhoto'];
                            $selectNote=$bdd->query("SELECT calculNote($id)");
                            while ($select=$selectNote->fetch()){
                                $notePhoto=$select[0];
                            }
                            echo '<p><img src=\'images/icone_note.png\' alt=\'icone note\' />Note : '.$notePhoto.'/5</p>';
                        }
                    }
                }else echo "Vous n'êtes pas connecté";
            ?>
	</div>
        
<?php include("include/cotedroit.inc.php") ?>        
</div> 
        
<div class="clear"></div>                  
<?php include("include/footer.inc.php") ?>
 
</div>
</body>
</html>
