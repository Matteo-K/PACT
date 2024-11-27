<header>
    <?php $search = isset($_GET["search"]) ? $_GET["search"] : ""; ?>
    <div>
        <a href="../index.php">
            <img id="logo" src="../img/logo.png" title="logo du site">
            <div>
                <h1 id="logoText">PACT</h1>
                <?php
                if ($isLoggedIn) {
                    if ($_SESSION["typeUser"] == "pro_public" || $_SESSION["typeUser"] === "pro_prive") {
                ?>
                        <h3 id="pro">PRO</h3>
                <?php
                    }
                }
                ?>
            </div>
        </a>
    </div>
    <div id="divFormHeader">
        <form method="get" action="search.php" id="formHeader">
            <input type="text" placeholder="Rechercher :" name="search" value="<?php echo $search ?>">
            <button type="submit"><img src="../img/icone/loupe.png" title="icone de recherche"></button>
        </form>
    </div>


    <div id="auth">
        <?php
        if ($isLoggedIn) {
            // Sélection de l'utilisateur en fonction de son type
            if ($typeUser === "admin") {
                $stmt = $conn->prepare("SELECT * FROM pact.admin WHERE idU = ?");
            } else if ($typeUser === "membre") {
                $stmt = $conn->prepare("SELECT * FROM pact.membre WHERE idU = ?");
            } else if ($typeUser === "pro_public") {
                $stmt = $conn->prepare("SELECT * FROM pact.proPublic WHERE idU = ?");
            } else if ($typeUser === "pro_prive") {
                $stmt = $conn->prepare("SELECT * FROM pact.proPrive WHERE idU = ?");
            }
            $stmt->execute([$_SESSION["idUser"]]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        ?>

            <img id="profilePic" src="<?php echo $user["url"] ?>" title="Photo de profil utilisateur">

            <!-- Menu caché intégré dans le header -->
            <div id="profileMenu" class="hidden">
                <div class="menuHeader">
                    <span id="backButton">< Retour</span>
                    <figure id="imagProfil">
                        <img src="<?php echo $user["url"] ?>" title="photo de profil utilisateur" id="menuProfilePic">
                        <figcaption>
                            <?php
                                if ($typeUser === "admin") {
                                    echo $user["login"];
                                } else if ($typeUser === "pro_public" || $typeUser === "pro_prive") {
                                    echo $user["denomination"];
                                } else if ($typeUser === "membre") {
                                    echo $user["pseudo"];
                                }
                            ?>
                        </figcaption>
                    </figure>
                    <ul>
                        <?php if ($typeUser === "pro_public" || $typeUser === "pro_prive") {?>
                            <li><a href="search.php">Mes offres</a></li>
                            <li><a href="manageOffer.php">Créer une offre</a></li>
                            <li class="liFact">Mes Factures</li>
                        <?php } ?>
                    </ul>
                    <div>
                        <a id="changeAccount" class="buttonMenu" href="logout.php?change=true">Changer de compte</a>
                        <a id="logoutButton" class="buttonMenu" href="logout.php">Déconnexion</a>
                    </div>
                </div>
                <div class="factue">
                    <h1>Mes Factures</h1>
                    <div class="details">
                    <?php 
                        $idu = $_SESSION["idUser"];
                        $stmt = $conn->prepare("SELECT nom,idoffre,ARRAY_AGG(DISTINCT datefactue ORDER BY datefactue DESC) AS datefactue FROM pact.facture WHERE idU = $idu GROUP BY nom,idOffre");
                        $stmt->execute();
                        $factures = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($factures as $key => $value) {
                            ?>
                                <details class="details-style">
                                    <summary><?php echo $value['nom'] ?></summary>
                                    <div class="details-content">
                                    <?php
                                        $date = explode(',',trim($value['datefactue'],'{}'));
                                        foreach ($date as $key => $value2) {
                                            $moisEnFrancais = [
                                                1 => 'janvier',
                                                2 => 'février',
                                                3 => 'mars',
                                                4 => 'avril',
                                                5 => 'mai',
                                                6 => 'juin',
                                                7 => 'juillet',
                                                8 => 'août',
                                                9 => 'septembre',
                                                10 => 'octobre',
                                                11 => 'novembre',
                                                12 => 'décembre'
                                            ];
                                            
                                            // Initialiser la date à partir de $value2
                                            $dateFacture = new DateTime($value2);
                                            
                                            // Soustraire un mois
                                            $dateFacture->modify('-1 month');
                                            
                                            // Obtenir le numéro du mois
                                            $numMois = (int)$dateFacture->format('n'); // 'n' donne le mois en entier sans zéro initial
                                            
                                            // Récupérer le mois en français
                                            $moisFrancais = $moisEnFrancais[$numMois];

                                            $annee = $dateFacture->format('Y');

                                            ?>
                                                <div class="details-form">
                                                    <p><?php echo "Facture du mois de " . $moisFrancais . " " . $annee ?></p>
                                                    <div>
                                                        <form action="bill/download.php" method="post">
                                                            <input type="hidden" name="idOffre" value="<?php echo $value['idoffre']; ?>">
                                                            <input type="hidden" name="mois" value="<?php echo $moisFrancais; ?>">
                                                            <input type="hidden" name="annee" value="<?php echo $annee; ?>">
                                                            <input type="hidden" name="boole" value="false">
                                                            <input type="hidden" name="date" value="<?php echo $value2; ?>">
                                                            <button class="modifierBut" type="submit">Visualiser</button>
                                                        </form>
                                                        <form action="bill/download.php" method="post">
                                                            <input type="hidden" name="idOffre" value="<?php echo $value['idoffre']; ?>">
                                                            <input type="hidden" name="mois" value="<?php echo $moisFrancais; ?>">
                                                            <input type="hidden" name="annee" value="<?php echo $annee; ?>">
                                                            <input type="hidden" name="boole" value="true">
                                                            <input type="hidden" name="date" value="<?php echo $value2; ?>">
                                                            <button class="modifierBut" type="submit">Télécharger</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            <?php
                                        }
                                    ?>
                                    </div>
                                </details>
                            <?php
                        }
                    ?>
                    </div>
                </div>
            </div>

        <?php
        } else {
        ?>
            <a href="../login.php">
                <div id="btnConn">Connexion</div>
            </a>
        <?php
        }
        ?>
    </div>

</header>


<script>
    try {
        document.addEventListener("DOMContentLoaded", function() {
            const body = document.body;
            const profilePic = document.getElementById("profilePic");
            const profileMenu = document.getElementById("profileMenu");
            const backButton = document.getElementById("backButton");
            const factu = document.getElementsByClassName("liFact")[0];
            const DivFactue = document.getElementsByClassName("factue")[0];

            function toggleFacture() {
                if (DivFactue.style.display === "none") {
                    DivFactue.style.display = "flex"
                    DivFactue.style.transform = "translateX(0)";  // Restaure la hauteur
                } else {
                    DivFactue.style.transform = "translateX(100)";         // Réduire la hauteur à 0
                    DivFactue.style.display = "none"; // Cache la div
                }
            }
            
            factu.addEventListener("click", toggleFacture);
            // Fonction pour afficher/cacher le menu
            function toggleMenu() {
                if (profileMenu.classList.contains("show")) {
                    profileMenu.classList.remove("show");
                    profileMenu.classList.add("hide");
                    body.classList.remove('no-scroll');

                    // Retirer la classe "hide" après la transition
                    setTimeout(() => {
                        profileMenu.classList.remove("hide");
                    }, 300); // Temps de la transition en ms
                } else {
                    profileMenu.classList.remove("hide");
                    profileMenu.classList.add("show");
                    body.classList.add('no-scroll');
                }
            }

            // Écouteur pour afficher le menu au clic sur l'image de profil
            if (profilePic) {
                profilePic.addEventListener("click", toggleMenu);
            }

            // Écouteur pour fermer le menu au clic sur le bouton "Retour"
            if (backButton) {
                backButton.addEventListener("click", toggleMenu);
            }

            // Écouteur pour fermer le menu en cliquant en dehors
            document.addEventListener("click", function(event) {
                if (typeof profileMenu !== "undefined" && profileMenu !== null && typeof profilePic !== "undefined" && profilePic !== null) {
                    if (!profileMenu.contains(event.target) && !profilePic.contains(event.target)) {
                        if (profileMenu.classList.contains("show")) {
                            toggleMenu();
                        }
                    }
                }
            });

        });
    } catch {

    }
</script>