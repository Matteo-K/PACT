<?php
$stmt = $conn->prepare("SELECT * from pact.proprive where idu = ?");
$stmt->execute([$offre[0]['idu']]);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

function formatDateDiff($date)
{
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
    $diffInDays = (int)$intervalDays->format('%r%a');

    // Calculer la différence en heures et minutes
    $interval = $dateDB->diff($dateNow);
    $diffInHours = $interval->h + ($interval->days * 24);
    $diffInMinutes = $interval->i;

    // Déterminer le message à afficher
    if ($diffInDays === 0) {
        if ($diffInMinutes === 0) {
            return "Rédigé à l'instant";
        } elseif ($diffInHours > 1) {
            return "Rédigé il y a " . ($diffInHours - 1) . " heure" . ($diffInHours > 1 ? 's' : '');
        } else {
            return "Rédigé il y a $diffInMinutes minute" . ($diffInMinutes > 1 ? 's' : '');
        }
    } elseif ($diffInDays === 1) {
        return "Rédigé hier";
    } elseif ($diffInDays > 1 && $diffInDays <= 7) {
        return "Rédigé il y a " . abs($diffInDays) . " jour" . (abs($diffInDays) > 1 ? 's' : '');
    } else {
        return "Rédigé le " . $dateDB->format("d/m/Y à H:i");
    }
}

// Boucle sur les avis
foreach ($avis as $a) {
?>
    <div class="messageAvisReponse">
        <div class="messageAvis">
            <article class="user">
                <div class="infoUser">
                    <img src="<?= $a['membre_url'] ?>" alt="Photo de profil">
                    <p><?= ucfirst($a['pseudo']) ?></p>
                </div>
                <div class="autreInfoAvis">
                    <div class="noteEtoile">
                        <?php
                        for ($i = 0; $i < $a['note']; $i++) {
                            echo "<div class='star'></div>";
                        }
                        if (5 - $a['note'] > 0) {
                            for ($i = 0; $i < 5 - $a['note']; $i++) {
                                echo "<div class='star starAvisIncolore'></div>";
                            }
                        }
                        ?>
                        <p><?= $a['note'] ?> / 5</p>
                    </div>
                    <img src="./img/icone/trois-points.png" alt="icone de paramètre" class="openPopup"/>
                </div>
            </article>
            <article>
                <div>
                    <p><strong><?= ucfirst($a['titre']) ?></strong></p>
                    <p><?php if (isset($a['datepublie'])) echo formatDateDiff($a['datepublie']); ?></p>
                </div>
                <div>
                    <p>Visité en <?= ucfirst(strtolower($a['mois'])) . " " . $a['annee'] ?></p>
                    <p> • </p>
                    <p class="tag"><?= $a['companie'] ?></p>
                </div>
                <div>
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
                                            <img src="<?= $picture; ?>" alt="Image de l'avis"/>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="swiper-pagination"></div>
                        </div>
                    <?php } ?>
                </div>
            </article>
        </div>
        <!-- Gestion des réponses -->
        <?php if (!empty($a['reponses'])) { ?>
            <div class="listeReponses">
                <?php foreach ($a['reponses'] as $r) { ?>
                    <div class="reponseAvis">
                        <article class="user">
                            <div class="infoUser">
                                <img src="<?= $r['membre_url'] ?>" alt="Photo de profil">
                                <p><?= ucfirst($r['pseudo']) ?></p>
                            </div>
                            <div>
                                <p><?= $r['content'] ?></p>
                                <p><?php if (isset($r['datepublie'])) echo formatDateDiff($r['datepublie']); ?></p>
                            </div>
                        </article>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
<?php
}
?>
