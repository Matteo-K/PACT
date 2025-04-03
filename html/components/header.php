<header>
    <?php $search = isset($_GET["search"]) ? $_GET["search"] : ""; ?>
    <div>
        <a href="../index.php">
            <img id="logo" src="../img/logo.png" title="logo du site">
            <div>
                <h1 id="logoText">PACT</h1>
                <?php
                if ($isLoggedIn) {
                    if ($_SESSION["typeUser"] == "pro_public" || $_SESSION["typeUser"] === "pro_prive") { //si on est authentifer en tant que pro public ou pro privé
                ?>
                        <h3 id="pro">PRO</h3>
 
                    
                <?php
                    }
                }

                if ($isLoggedIn) {
                    if ($_SESSION["typeUser"] == "admin" ) { // si on est authentifer en tant qu'administrateur
                ?>
                        <h3 id="pro">ADMIN</h3>
                <?php
                    }
                }
                ?>
            </div>
        </a>
    </div>
    <div id="divFormHeader">
        <form method="get" action="index.php#searchIndex" id="formHeader">
            <input type="text" placeholder="Rechercher :" name="search" value="<?php echo $search ?>">
            <button type="submit"><img src="../img/icone/loupe.png" title="icone de recherche"></button>
        </form>
    </div>

    <script>
        const form = document.querySelector("#divFormHeader form");
        const input = form.querySelector("input");

        // Vérifie la page actuelle
        const currentFile = window.location.pathname.split('/').pop();

        input.addEventListener("input", () => {
            if (currentFile === 'pact.php') {
                const searchTarget = document.getElementById("searchIndex");
                searchTarget.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });

        /**
         * On envoie le formulaire si on est pas sur la page index
         */
        form.addEventListener('submit', (event) => {
            if (currentFile === 'index.php') {
                event.preventDefault();
            }
        });

    </script>
    <?php if (str_starts_with($typeUser, 'pro_')) { 
        $stmt = $conn->prepare(
            "SELECT count(1) as nbavis from pact.avis a
            LEFT JOIN pact._offre o on a.idoffre = o.idoffre
            WHERE lu=false AND o.idu=?
            GROUP BY o.idu;"
        );
        $stmt->execute([$idUser]);
        $resNotification = $stmt->fetch(PDO::FETCH_ASSOC);
        $quantite = 0;
        if ($resNotification) {
            $quantite = intval($resNotification["nbavis"]) > 99 ? "+99" : $resNotification["nbavis"];
        }
        ?>
        <label tabindex="0" for="notification" class="<?= $quantite === 0 ? "" : "haveNotification" ?>">
            <input type="checkbox" name="notification" id="notification">
            <img src="../img/icone/notification.png" alt="notifications" title="notifications">
            <span data-notif="<?= $quantite ?>"><?= $quantite ?></span>
        </label>
    <?php } ?>
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
            $stmt->execute([$idUser]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        ?>

            <img id="profilePic" src="<?php echo $user["url"] ?>" title="Photo de profil utilisateur">
            
            <!-- Menu caché intégré dans le header -->
            <div id="profileMenu" class="hidden">
                <div class="menuHeader">
                    <span id="backButton">< Retour</span>
                    <figure id="imagProfil">
                        <img src="<?php echo $user["url"] ?>" title="photo de profil utilisateur" id="menuProfilePic">
                        <img src="./img/icone/iconePro.png" alt="pro" id="iconePro" <?= $typeUser === "pro_public" || $typeUser === "pro_prive" ? null : 'hidden="true"'?>>
                        <figcaption>
                            <?php
                                if ($typeUser === "admin") {
                                    echo $user["login"];
                                } else if ($typeUser === "pro_public" || $typeUser === "pro_prive") {
                                    echo $user["denomination"] . " (pro)";
                                } else if ($typeUser === "membre") {
                                    echo $user["pseudo"];
                                }
                            ?>
                        </figcaption>
                    </figure>
                    <ul>
                        <?php if ($typeUser === "pro_public" || $typeUser === "pro_prive") {?>
                            <li><a href="changeAccountPro.php">Mon compte</a></li>
                            <li><a href="index.php#searchIndex">Mes offres</a></li>
                            <li><a href="manageOffer.php">Créer une offre</a></li>
                            <li class="liFact">Mes Factures</li>
                        <?php } else if ($typeUser === "membre") {?>
                            <li><a href="changeAccountMember.php">Mon compte</a></li>
                        <?php } ?>
                    </ul>
                    <div>
                        <a id="changeDataAccount" class="buttonMenu" href="changePassword.php">Changer mon mot de passe</a>
                        <a id="changeAccount" class="buttonMenu" href="logout.php?change=true">Changer de compte</a>
                        <a id="logoutButton" class="buttonMenu" href="logout.php">Déconnexion</a>
                    </div>
                </div>
                <div class="factue">
                    <?php 
                    if ($typeUser === "pro_public" || $typeUser === "pro_prive") {
                    ?>
                        <h1>Mes Factures</h1>
                        <div class="details">
                        <?php 
                            $idu = $idUser;
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
                                                            <form id="factureForm" action="bill/download.php" method="post" target="pdfWindow">
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
                    <?php 
                    }
                    ?>
                </div>
            </div>

        <?php
        } else {
        ?>
            <a href="../login.php" id="btnConn">Connexion</a>
        <?php
        }
        ?>
    </div>

</header>
<?php if (str_starts_with($typeUser, 'pro_')) {
    $stmt = $conn->prepare("SELECT * FROM pact._offre where idu=?;");
    $stmt->execute([$idUser]);
    $idoffres = $stmt->fetchAll(PDO::FETCH_ASSOC);

    ?>
    <aside id="notification_aside">
        <h3></h3>
        <section>
        </section>
    </aside>
    <script>
        const inputNotification = document.querySelector("[for='notification'] input");
        const span_notification = document.querySelector("[for='notification'] span");
        const label_notification = document.querySelector("[for='notification']");
        const blc_notification = document.querySelector("#notification_aside section");
        const aside_notif = document.getElementById("notification_aside");

        inputNotification.addEventListener("input", () => {
            if (inputNotification.checked) {
                fetch("ajax/notification.php?idu=" + encodeURIComponent(<?= $idUser ?>))
                .then(response => response.json())
                .then(data => {
                    const notification_size = data["avis"].length;
                    if (notification_size == 0) {
                        span_notification.textContent = "";
                        label_notification.classList.remove("haveNotification");
                    } else {
                        label_notification.classList.add("haveNotification");
                        span_notification.textContent = notification_size > 99 ? "+99" : notification_size;
                    }
                    document.querySelector("#notification_aside h3").textContent = "Notification" + (notification_size > 1 ? "s" : "");
                    span_notification.dataset.notif = notification_size;
                    displayNotification(data["avis"])
                })
                .catch(error => console.error("Erreur :", error));
            }
        });

        /**
         * Affiche la liste des notifications
         * @param liste d'avis
         * @return bloc avec les avis
         */
        function displayNotification(arrayAvis) {
            let notifications = "";
            let actuelle = new Date();
            let groupedAvis = {};

            if (arrayAvis.length > 0) {
                arrayAvis.forEach(avis => {
                    if (!groupedAvis[avis.nom]) {
                        groupedAvis[avis.nom] = [];
                    }
                    groupedAvis[avis.nom].push(displayAvisNotif(avis, actuelle));
                });

                for (let offre in groupedAvis) {
                    const count = groupedAvis[offre].length;
                    notifications += `
                    <details id="offreNotif_${offre}" class="details-style" data-nbavis="${count}" open>
                        <summary>(${count > 99 ? "+99" : count}) ${offre}</summary>
                        <div>
                            ${groupedAvis[offre].join('')}
                        </div>
                    </details>`;
                }
            } else {
                notifications = `<p> Vous avez aucune notification</p>`;
            }

            blc_notification.innerHTML = notifications;
        }

        /**
         * Affiche 1 avis
         * @param avis info à propos de l'avis
         * @param date actuelle
         * @return bloc avis
         */
        function displayAvisNotif(avis, date_actuelle) {
            const date_avis = new Date(avis.datepublie);
            const diff = Math.floor((date_actuelle - date_avis) / 1000);
            let temps = "";
            if (diff < 60) {
                temps = "Publié à l'instant";
            } else if (diff < 3600) {
                const minutes = Math.floor(diff / 60);
                temps = `Il y a ${minutes} minute${minutes > 1 ? 's' : ''}`;
            } else if (diff < 86400) {
                const heures = Math.floor(diff / 3600);
                temps = `Il y a ${heures} heure${heures > 1 ? 's' : ''}`;
            } else {
                const jours = Math.floor(diff / 86400);
                temps = `Il y a ${jours} jour${jours > 1 ? 's' : ''}`;
            }
            return `
                <form id="notification${avis.idc}" action="../detailsOffer.php#avis${avis.idc}" method="post">
                    <button type="submit"><img src="../img/icone/lien-externe.png" alt="vers l'avis" title="vers l'avis"></button>
                    <div>
                        <img src='.${avis.url}' alt='${avis.pseudo}' title='${avis.pseudo}'>
                        <span>${avis.pseudo} a commenté ${avis.titre}</span>
                    </div>
                    <time datetime='${avis.datepublie}'>
                        ${temps}
                    </time>
                    <input type="hidden" name="idoffre" value="${avis.idoffre}">
                </form>
            `;
        }

        function displayStar(note) {
            let stars = Array(5).fill('<div class="star vide"></div>');
            for (let i = 0; i < Math.floor(note); i++) stars[i] = '<div class="star pleine"></div>';
            if (note % 1 !== 0) stars[Math.floor(note)] = `<div class="star partielle" style="--pourcentage: ${(note % 1) * 100}%"></div>`;
            return `<div class="blocStar">${stars.join('')}<span>${note}/5</span></div>`;
        }
    </script>
<?php } ?>

<script>
    document.addEventListener("DOMContentLoaded", function () {
    const body = document.body;

    // Récupération des éléments (peuvent être absents selon le type d'utilisateur)
    const profilePic = document.getElementById("profilePic");
    const profileMenu = document.getElementById("profileMenu");
    const backButton = document.getElementById("backButton");
    const factu = document.querySelector(".liFact"); // Peut être null si pas un utilisateur Pro

    // Fonction pour afficher/masquer la section "Factures" (pour Pro uniquement)
    if (factu) {
        function toggleFacture() {
            profileMenu.classList.toggle("deplace");
            body.classList.toggle("no-scroll");
        }
        factu.addEventListener("click", toggleFacture);
    }

    // Fonction pour afficher/cacher le menu utilisateur
    function toggleMenu() {
        if (profileMenu) {
            if (profileMenu.classList.contains("show")) {
                profileMenu.classList.remove("show");
                profileMenu.classList.remove("deplace");
                profileMenu.classList.add("hide");
                body.classList.remove("no-scroll");
            } else {
                profileMenu.classList.add("show");
                profileMenu.classList.remove("hide");
            }
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
    document.addEventListener("click", function (event) {
        if (
            profileMenu &&
            profilePic &&
            !profileMenu.contains(event.target) &&
            !profilePic.contains(event.target)
        ) {
            if (profileMenu.classList.contains("show")) {
                toggleMenu();
            }
        }
    });
});


    try {
        
    } catch (error) {
    console.error("Erreur capturée :", error.message);
    }


</script>