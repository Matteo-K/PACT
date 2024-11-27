<?php
$stmt = $conn->prepare("
    SELECT a.*, m.url AS membre_url,r.idc_reponse,r.denomination AS reponse_denomination, r.contenureponse, r.reponsedate, r.idpro
    FROM pact.avis a
    JOIN pact.membre m ON m.pseudo = a.pseudo
    LEFT JOIN pact.reponse r ON r.idc_avis = a.idc
    WHERE a.idoffre = ? 
    ORDER BY a.datepublie ASC
");
$stmt->execute([$idOffre]);
$avis = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conn->prepare("SELECT * from pact.proprive where idu = ?");
$stmt->execute([$offre[0]['idu']]);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

function formatDateDiff($date) {
    // Créer des objets DateTime à partir de la date passée en paramètre
    $dateDB = new DateTime($date);
    $dateNow = new DateTime();

    // Fixer les objets DateTime à minuit pour la différence en jours
    $dateDBMidnight = clone $dateDB;
    $dateDBMidnight->setTime(0, 0, 0);

    $dateNowMidnight = clone $dateNow;
    $dateNowMidnight->setTime(0, 0, 0);

    // Calculer la différence en jours (à partir de minuit)
    $intervalDays = $dateDBMidnight->diff($dateNowMidnight);
    $diffInDays = (int)$intervalDays->format('%r%a'); // %r pour prendre en compte les jours négatifs

    // Calculer la différence en heures pour le jour même
    $intervalHours = $dateDB->diff($dateNow);
    $diffInHours = $intervalHours->h; // Différence en heures
    $diffInMinutes = $intervalHours->i; // Différence en minutes

    // Déterminer le message à afficher
    if ($diffInDays === 0) {
        // La date est aujourd'hui, afficher la différence en heures
        if ($diffInHours > 0) {
            return "Rédigé il y a $diffInHours heure" . ($diffInHours > 1 ? 's' : '');
        } elseif ($diffInMinutes > 0) {
            return "Rédigé il y a $diffInMinutes minute" . ($diffInMinutes > 1 ? 's' : '');
        } else {
            return "Rédigé à l'instant";
        }
    } elseif ($diffInDays === -1) {
        // La date est hier
        return "Rédigé hier";
    } elseif ($diffInDays < -1 && $diffInDays >= -7) {
        // La date est dans les 7 derniers jours
        return "Rédigé il y a " . abs($diffInDays) . " jour" . (abs($diffInDays) > 1 ? 's' : '');
    } else {
        // La date est plus ancienne que 7 jours ou dans le futur
        return "Rédigé le " . $dateDB->format("d/m/Y à H:i");
    }
}


foreach ($avis as $a) {
?>
    <div class="messageAvisReponse">
        <div class="messageAvis">
            <article class="user">
                <div class="infoUser">
                    <img src="<?= $a['membre_url'] ?>">
                    <p><?= ucfirst(strtolower($a['pseudo'])) ?> </p>
                </div>
                <div class="autreInfoAvis">
                    <div class="noteEtoile">
                        <?php
                        for ($i = 0; $i < $a['note']; $i++) {
                            echo "<div class='star'></div>";
                        }
                        if (5 - $a['note'] != 0) {
                            for ($i = 0; $i < 5 - $a['note']; $i++) {
                                echo "<div class='star starAvisIncolore'></div>";
                            }
                        }
                        ?>
                        <p><?= $a['note'] ?> / 5</p>
                    </div>
                    <img src="./img/icone/trois-points.png" alt="icone de parametre">
                </div>
            </article>
            <article>
                <p><strong>Visité en</strong> <?= ucfirst(strtolower($a['mois'])) . " " . $a['annee'] ?></p>
                <p> • </p>
                <p class="tag"><?= $a['companie'] ?></p>
            </article>
            <article>
                <p><strong><?= ucfirst($a['titre']) ?></strong></p>
                <p><?= $a['content'] ?></p>
                <?php if ($a['listimage'] != null) {
                    $listimage = trim($a['listimage'], '{}');
                    $pictures = explode(',', $listimage);
                ?>

                    <div class="swiper-container">
                        <div class="swiper mySwiperAvis">
                            <div class="swiper-wrapper">
                                <?php
                                foreach ($pictures as $picture) {
                                ?>
                                    <div class="swiper-slide">
                                        <img src="<?php echo $picture; ?>" />
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                <?php
                }
                ?>
            </article>
            <?php
            if (isset($a['datepublie'])) {
                echo "<p>" . formatDateDiff($a["datepublie"]) . "</p>";
            }
            ?>
        </div>
        <?php
        if ($a['idc_reponse']) {
        ?>
            <div>
                <img src="./img/icone/reponse.png" alt="icone de reponse">
                <div class="reponseAvis">
                    <div class="infoProReponse">
                        <img src="<?= $result[0]['url'] ?>" alt="image de profile du pro">
                        <p><?= ucfirst(strtolower($a['reponse_denomination'])) ?> </p>
                    </div>
                    <img src="./img/icone/trois-points.png" alt="icone de parametre">
                </div>
                <article>
                    <p><?= $a['contenureponse'] ?></p>
                </article>
                <?php
                if (isset($a['reponsedate'])) {
                    echo "<p>" . formatDateDiff($a["reponsedate"]) . "</p>";
                }
                ?>
            </div>
        <?php
        }
        ?>

    </div>

<?php
}
?>

<script>
    var swiper3 = new Swiper(".mySwiperAvis", {
        loop: true,
        autoplay: {
            delay: 5000,
        },
        spaceBetween: 10,
        pagination: {
            el: ".swiper-pagination",
            dynamicBullets: true,
        },
        thumbs: {
            swiper: swiper,
        },
    });
</script>