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
                //On vérifie que l'utilisateur a correctement entré les informations nécessaires : Mail et Mot de Passe
                if(isset($_POST['mailConnexion']) AND isset($_POST['mdpConnexion']))
                {
                    //Récupération des informations utilisateur en fonction du Mail et MdP rentrés
                    $reponseClient = $bdd->prepare('SELECT * FROM clients WHERE mailClient = ? AND mdpClient = ?');
                    $reponseClient->execute(array($_POST['mailConnexion'], sha1($_POST['mdpConnexion'])));
                    $resultatClient = $reponseClient->fetch();

                    $reponsePhotographe = $bdd->prepare('SELECT * FROM  photographes WHERE mailPhotographe = ? AND mdpPhotographe = ?');
                    $reponsePhotographe->execute(array($_POST['mailConnexion'], sha1($_POST['mdpConnexion'])));
                    $resultatPhotographe = $reponsePhotographe->fetch();
                    //Si la requête de retourne rien : erreur
                    if (!$resultatClient)
                    {		
                        if(!$resultatPhotographe){
                            echo "Mauvais identifiant ou Mot de Passe";                                        
                        }
                        else{
                            $pseudo=$resultatPhotographe['pseudoPhotographe'];
                            $id=$resultatPhotographe['idPhotographe'];
                            $photographe=$resultatPhotographe['nomPhotographe']."_".$resultatPhotographe['prenomPhotographe']."_".$resultatPhotographe['pseudoPhotographe'];
                            connexion($pseudo,2,$id,$photographe);                                        
                        }
                    }else{
                        $pseudo=$resultatClient['pseudoClient'];
                        $credit=$resultatClient['nbCredit'];
                        $id=$resultatClient['idClient'];
                        connexion($pseudo,1,$id,null,$credit);                                    
                    }
                }
                $reponse->closeCursor();
            ?>
	</div> 	
    </div> 
        
<div class="clear"></div>                  
<?php include("include/footer.inc.php") ?>
 
</div>
</body>
</html>


<?php
function connexion($resultat,$type,$id,$photographe=NULL, $credit=NULL){
     include("include/connexionbdd.inc.php");
    //Etablissement d'un log de Session avec le pseudo de l'utilisateur,
    //et changement de l'état de 3 menus (Cacher : Connexion et S'inscrire - Visible : Déconnexion)
    echo "Vous vous êtes connecté ".$resultat."<br />";		
    echo "Vous allez être redirigé vers l'accueil.";			
    $update= $bdd->query('UPDATE menu SET DispoMenu=0 WHERE nomMenu LIKE "Connexion"');
    $update= $bdd->query('UPDATE menu SET DispoMenu=0 WHERE nomMenu LIKE "S\'inscrire"');
    $update= $bdd->query('UPDATE menu SET DispoMenu=1 WHERE nomMenu LIKE "Deconnexion"');
    $_SESSION['log']=$resultat;
    $_SESSION['type']=$type;
    $_SESSION['photographe']=$photographe;
    $_SESSION['id']=$id;
    $panier=array();
    $_SESSION['caddie']=$panier;
    $_SESSION['credit']=$credit;
}
?>
