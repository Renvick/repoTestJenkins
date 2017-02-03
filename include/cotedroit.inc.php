<div class="sidebar">
    <ul>	
    <?php
        //Si l'utilisateur est connecté : Affichage du menu
        if (isset($_SESSION['log']))
        {
            //Menu pour Clients
            if($_SESSION['type']==1){
    ?>
                <li>
                     <h3>Navigation</h3>
                     <ul class="blocklist">
                         <li><a href="index.php">Accueil</a></li>
                         <li><a href="album.php">Mon Album</a></li>
                     </ul>
                 </li>
    <?php
            }else{
                //Menu pour Photographes
                ?>
                <li>
                    <h3>Navigation</h3>
                    <ul class="blocklist">
                        <li><a href="index.php">Accueil</a></li>
                        <li><a href="vendrephotos.php">Vendre des photos</a></li>
                        <li><a href="album.php">Mon Album</a></li>
                    </ul>
                </li>
                <?php
            }
        }
    ?>        
        <!-- SEARCH mis de côté pour utilisation ultérieure
        <h3>Search</h3>
        <ul>
            <li>
                <form method="get" class="searchform" action="http://wpdemo.justfreetemplates.com/" >
                    <p>
                        <input type="text" size="12" value="" name="s" class="s" />
                        <input type="submit" class="searchsubmit formbutton" value="Search" />
                    </p>
                </form>	
            </li>
        </ul>
        -->
    <?php
        //Si l'utilisateur n'est pas connecté : Affichage du module de connexion
        if (!isset($_SESSION['log']))
        {
    ?>
            <li>
                <h3>Connexion</h3>
                <form method="post" action="form_connexion.php">
                    <label for="mailConnexion">Adresse Mail :</label>
                        <input required id="mailConnexion" type="email" placeholder="Votre adresse mail" name="mailConnexion"></br>
                    </input><br/>
                                    <label for="mdpConnexion">Mot de passe :</label>
                    <input id="mdpConnexion" type="password" placeholder="Mot de passe" name="mdpConnexion" required></br>
                    </input><br/>
                    <input type="submit" value="Login →" />
                </form> 
            </li>
    <?php
        }
    ?>
    </ul>
</div>