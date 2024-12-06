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
    $diffInDays = (int)$intervalDays->format('%r%a'); // %r pour prendre en compte les jours négatifs

    // Calculer la différence en heures et minutes
    $interval = $dateDB->diff($dateNow);
    $diffInHours = $interval->h + ($interval->days * 24); // Ajouter les heures des jours entiers
    $diffInMinutes = $interval->i;

    // Déterminer le message à afficher
    if ($diffInDays === 0) {
        if ($diffInMinutes === 0) {
            return "Rédigé à l'instant";
        } elseif ($diffInHours > 1) {
            return "Rédigé il y a " . $diffInHours - 1 . " heure" . ($diffInHours > 1 ? 's' : '');
        } else {
            return "Rédigé il y a $diffInMinutes minute" . ($diffInMinutes > 1 ? 's' : '');
        }
    } elseif ($diffInDays === 1) {
        // La date est hier
        return "Rédigé hier";
    } elseif ($diffInDays > 1 && $diffInDays <= 7) {
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
                    <p><?= ucfirst($a['pseudo']) ?> </p>
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
                    <!-- Icône de 3 points pour ouvrir la popup -->
                    <img src="./img/icone/trois-points.png" alt="icone de parametre" class="openPopup" />
                </div>
            </article>
            <article>
                <div>
                    <div>
                        <p><strong><?= ucfirst($a['titre']) ?></strong></p>
                        <p><?php if (isset($a['datepublie'])) {
                                echo formatDateDiff($a["datepublie"]);
                            } ?></p>
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
                                        print($pictures);
                                        foreach ($pictures as $picture) {
                                            print($picture);
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
                        <?php } ?>
                    </div>
                </div>
            </article>
        </div>
        <?php
        if ($a['idc_reponse']) {
        ?>
        <div>
            <img src="./img/icone/reponse.png" alt="icone de reponse">
            <div class="reponseAvis">
                <div class="user">
                    <div class="infoProReponse">
                        <div>
                            <img src="<?= $result[0]['url'] ?>" alt="image de profile du pro">
                            <p><?= ucfirst(strtolower($a['reponse_denomination'])) ?> </p>
                        </div>
                    </div>
                    <div class="autreInfoAvis">
                        <?php
                        if (isset($a['reponsedate'])) {
                            echo "<p>" . formatDateDiff($a["reponsedate"]) . "</p>";
                        }
                        ?>
                        <img src="./img/icone/trois-points.png" alt="icone de parametre">
                    </div>

                </div>
                <article>
                    <p><?= $a['contenureponse'] ?></p>
                </article>
            </div>
        </div>
        <?php
        }
        ?>

    </div>
    </div>
<?php
}
?>