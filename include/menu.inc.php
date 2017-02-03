    <?php 
        if (isset($_SESSION['log'])){
            if ($_SESSION['type']==1){ 
    ?>
                <table border="0">
                    <tr>
                        <td><img src='images/icone_client.png' alt='icone client' /></td><td><?php echo $_SESSION['log']; ?></td>
                    </tr>
                    <tr>
                        <td><img src='images/icone_credit.png' alt='icone credit' /></td><td><?php echo $_SESSION['credit']; ?></td>
                    </tr>
                    <tr>
                        <td><a href='caddie.php'><img src='images/icone_panier.png' alt='icone panier' /></a></td>
                        <td><?php echo count($_SESSION['caddie']); ?></td>
                        <td>
                            <form method='post' action='<?php echo $_SERVER['PHP_SELF']; ?>' /><input type="hidden" name="preuve" /><input name='vider' type='image' src='images/icone_vider.png' /></form>
                        </td>
                    </tr>
                </table>
    <?php
                if(isset($_POST['preuve'])){
                    $array_vide=array();
                    $_SESSION['caddie']=$array_vide;
                }
            }else{ 
                ?>
                    <table border="0">
                        <tr>
                            <td><img src='images/icone_photographe.png' alt='icone photographe' /></td>
                            <td><?php echo $_SESSION['log'];?></td>
                        </tr>
                    </table>
                <?php              
            }            
        }  
    ?>
    
    <div id="nav">
        
    <?php 
		//Récupération de l'url de la page active
        $url = $_SERVER['PHP_SELF'];
		//analyse de la chaîne pour récupérer l'adresse de la page
        $reg = '#^(.+[\\\/])*([^\\\/]+)$#';
        define('onestla', preg_replace($reg, '$2', $url)); 

        //Exécution de la requête permettant de récupérer les menus dans la BdD
        $reponse = $bdd->query('SELECT NomMenu, Lien, DispoMenu FROM menu');
    ?>
    	<ul>
    <?php
        while($donnees=$reponse->fetch())
        {
            //Affichage des menus selon leur état : 1.Visible - 0.Caché
            if ($donnees['DispoMenu']==1)
            {
                ?>
                <li 
                    <?php //Permet l'animation du menu en fonction de là ou on est
                    if(onestla == $donnees['Lien']) { echo 'class="start selected"'; } ?>
                >
                    <a href="<?php echo $donnees['Lien']; ?>"><?php echo $donnees['NomMenu']; ?></a>
                </li>
                <?php
            }
        }        
    ?>
        </ul>
    </div>