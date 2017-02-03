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
                if(!isset($_POST['titre'])){ ?>
                <h3>Inscription</h3>

                    <fieldset>
                    <legend>Merci de remplir ce formulaire</legend>		
                        <form id="vente" name="vente" method="post" action="vendrephotos.php" enctype="multipart/form-data">
                            <p>	<label for="nom">Titre</label>
                                    <input required id="titre" type="text" placeholder="Titre de votre photographie" name="titre">
                            </p>
                            <p>	<label for="prix">Prix</label>
                                <input required id="prix" type="number" placeholder="Prix (en crédits)" name="prix">
                            </p>
                                <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
                            <p>	<label for="photo">Photographie (max : 10Mo)</label>
                                    <input required id="photo" type="file" name="photo">
                            </p>
                            <p>	<input type="submit" value="Vendre" style="margin-left: 150px;" name="send" class="formbutton"/>
                            </p>
                        </form>
                    </fieldset>
            <?php
                }else{
                    $erreur=NULL;
                    if ($_FILES['photo']['error'] > 0) $erreur = "Erreur lors du transfert";
                    
                    $maxsize=$_POST['MAX_FILE_SIZE'];
                    if ($_FILES['photo']['size'] > $maxsize) $erreur = "Le fichier est trop gros";                    
                    
                    $extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png' );
                    $extension_upload = strtolower(  substr(  strrchr($_FILES['photo']['name'], '.')  ,1)  );
                    if ( !in_array($extension_upload,$extensions_valides) ) $erreur = "Extension incorrecte";
                    
                    $image_sizes = getimagesize($_FILES['photo']['tmp_name']);
                    $maxwidth=1920;
                    $maxheight=1080;
                    if ($image_sizes[0] > $maxwidth OR $image_sizes[1] > $maxheight) $erreur = "Image trop grande";
                    
                    if ($erreur!=NULL){
                        echo $erreur;
                    }else{
                        $auteur=$_SESSION['photographe'];
                        $destination="images/photos/".$auteur."/";
                        $req=$bdd->prepare('INSERT INTO photos VALUES(:id, :nom, :taille, :height, :width, :type, :idPhotographe, :lien, :prix, :note, :nbVote)');
                         if($req->execute(array(
                                            'id' => '',
                                            'nom' => $_POST['titre'],
                                            'taille' => $_FILES['photo']['size'],
                                            'height' => $image_sizes[1],
                                            'width' => $image_sizes[0],
                                            'type' => $extension_upload,
                                            'idPhotographe' => (integer)$_SESSION['id'],
                                            'lien' => $destination.$_POST['titre'].".".$extension_upload,
                                            'prix' => (integer)$_POST['prix'],
                                            'note' => 0,
                                            'nbVote' => 0
                                ))
                         ){
                            if (is_dir("images/photos/".$auteur."/")){
                                move_uploaded_file($_FILES['photo']['tmp_name'], $destination.$_POST['titre'].".".$extension_upload);        
                                echo "Envoi réussi !";
                            }
                            else{
                                mkdir("images/photos/".$auteur."/",0777,TRUE);
                                move_uploaded_file($_FILES['photo']['tmp_name'], $destination.$_POST['titre'].".".$extension_upload);
                                echo "Envoi réussi !";

                            }
                        }else{ echo "Erreur !"; print_r($bdd->errorInfo()) ;}
                    }
                } 
            ?>
	</div>
        
<?php include("include/cotedroit.inc.php") ?>        
</div> 
        
<div class="clear"></div>                  
<?php include("include/footer.inc.php") ?>
 
</div>
</body>
</html>