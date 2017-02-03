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
            
            // SI CONNECTE
            if(isset($_SESSION['log'])){
                
                // CAS : Ajout d'article
                if(isset($_POST['idPhoto'])){
                    $idPhotoAjout=$_POST['idPhoto'];
                    $idClientAjout=$_SESSION['id'];
                    $selectPresence=$bdd->query("SELECT * FROM acheter WHERE idPhoto=$idPhotoAjout AND idClient=$idClientAjout");
                    if($selectPresence->rowCount()==0){
                        $article=$_SESSION['caddie'];
                        if(!in_array(array('id'=>$_POST['idPhoto'], 'prix'=>$_POST['prixPhoto']), $article)){
                            array_push($article, array('id'=>$_POST['idPhoto'], 'prix'=>$_POST['prixPhoto']));
                            $_SESSION['caddie']=$article;
                        }else echo "<h4>Article déjà dans le panier !</h4><br />";
                    } else echo "<h4>Vous avez déjà acheté cet article.</h4>";
                }
                // CAS : Notation photo
                elseif(isset($_POST['note'])){
                    $reqNote=$bdd->prepare("SELECT * FROM photos WHERE idPhoto=?");
                    $reqNote->execute(array($_POST['idPhotoNote']));
                    while($resultat=$reqNote->fetch()){
                        $note=$resultat['notePhoto'];                
                    }
                    $note=$note+$_POST['note'];
                    $id=$_POST['idPhotoNote'];
                    if($reqUpdateNote=$bdd->query("UPDATE photos SET notePhoto='$note' WHERE idPhoto='$id'")){
                        $reqUpdateNbVote=$bdd->query("UPDATE photos SET nbNotePhoto=nbNotePhoto+1 WHERE idPhoto='$id'");
                    }else{ print_r($bdd->errorInfo());}
                }
                
            }else echo "Connectez vous pour profiter de nos articles";
            
            // RECUPERATION DE TOUTES LES PHOTOS ET DE LEURS INFORMATIONS
            $req=$bdd->query('SELECT * FROM photos, photographes WHERE photos.idPhotographe=photographes.idPhotographe');
            while($photos=$req->fetch()){
            ?>
                <a href='<?php echo $photos['lienPhoto'] ?>' target='_blank'>
                    <img src='<?php echo $photos['lienPhoto'] ?>' 
                         alt='Photographie : <?php echo $photos['nomPhoto'] ?>'
                         width='150' height='150'/>
                </a><br />
                <p><img src='images/icone_photographe.png' alt='icone photographe' /> <?php echo $photos['nomPhotographe']." ".$photos['prenomPhotographe'] ?>
                   <img src='images/icone_credit.png' alt='icone credit' /> <?php echo $photos['prixPhoto']; ?> crédits
                
                <img src='images/icone_note.png' alt='icone note' />
                Note : <?php 
                            $id=$photos['idPhoto'];
                            $selectNote=$bdd->query("SELECT calculNote($id)");
                            while ($select=$selectNote->fetch()){
                                echo $select[0];
                            }
                        ?> /5
                    
                <!-- SI CONNECTE ET DE TYPE : CLIENT -->        
                <?php if(isset($_SESSION['log']) && $_SESSION['type']==1){ ?>
                   
                    <!-- FORMULAIRE DE NOTATION -->
                    <form method="post" action="achatphotos.php">
                         <label for="note">Noter :</label>
                         <select required name="note" id="note">
                            <option id="type" value="0">0</option>
                            <option id="type" value="1">1</option>
                            <option id="type" value="2">2</option>
                            <option id="type" value="3">3</option>
                            <option id="type" value="4">4</option>
                            <option id="type" value="5">5</option>
                         </select>
                         <input type="hidden" name="idPhotoNote" value="<?php echo $photos['idPhoto']; ?>"/>
                         <input type="submit" value="Noter" name="send" class="formbutton"/>
                    </form>
                   <br />
                   
                   <!-- FORMULAIRE D'AJOUT AU PANIER -->
                    <form method="post" action="achatphotos.php">
                        <input type="hidden" name="idPhoto" value="<?php echo $photos['idPhoto']; ?>"/>
                        <input type="hidden" name="prixPhoto" value="<?php echo $photos['prixPhoto']; ?>"/>
                        <label>Acheter </label><input type="image" src="images/icone_panier_ajouter.png" name="send"/></p>
                    </form>

            <?php
                    }
                echo "<p>-----------------------------------------------------------------------------------------------</p>";
            }
            $req->closeCursor();
            ?>
	</div>
        
<?php include("include/cotedroit.inc.php") ?>        
</div> 
        
<div class="clear"></div>                  
<?php include("include/footer.inc.php") ?>
 
</div>
</body>
</html>
