<?php
// Définir le nombre d'avis à afficher par page
$limit = 15;

// Récupérer l'offset depuis l'URL ou le fixer à 0 par défaut
$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;

// Récupérer les avis avec pagination
$stmt = $conn->prepare("SELECT * FROM pact.proprive WHERE idu = ? LIMIT ? OFFSET ?");
$stmt->execute([$offre[0]['idu'], $limit, $offset]);
$avis = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer le nombre total d'avis pour calculer si d'autres avis existent
$totalStmt = $conn->prepare("SELECT COUNT(*) FROM pact.proprive WHERE idu = ?");
$totalStmt->execute([$offre[0]['idu']]);
$totalAvis = (int)$totalStmt->fetchColumn();

// Calculer si d'autres avis existent
$hasMore = $offset + $limit < $totalAvis;

// Fonction de formatage de la date
function formatDateDiff($date)
{
    $dateDB = new DateTime($date);
    $dateNow = new DateTime();

    $intervalDays = $dateDB->setTime(0, 0)->diff($dateNow->setTime(0, 0));
    $diffInDays = (int)$intervalDays->format('%r%a');

    $interval = $dateDB->diff($dateNow);
    $diffInHours = $interval->h + ($interval->days * 24);
    $diffInMinutes = $interval->i;

    if ($diffInDays === 0) {
        if ($diffInMinutes === 0) return "Rédigé à l'instant";
        return $diffInHours > 1 ? "Rédigé il y a " . ($diffInHours - 1) . " heure" . ($diffInHours > 1 ? 's' : '') : "Rédigé il y a $diffInMinutes minute" . ($diffInMinutes > 1 ? 's' : '');
    }
    if ($diffInDays === 1) return "Rédigé hier";
    if ($diffInDays > 1 && $diffInDays <= 7) return "Rédigé il y a " . abs($diffInDays) . " jour" . (abs($diffInDays) > 1 ? 's' : '');
    return "Rédigé le " . $dateDB->format("d/m/Y à H:i");
}

// Affichage des avis
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
                        for ($i = 0; $i < 5 - $a['note']; $i++) {
                            echo "<div class='star starAvisIncolore'></div>";
                        }
                        ?>
                        <p><?= $a['note'] ?> / 5</p>
                    </div>
                    <img src="./img/icone/trois-points.png" alt="icone de parametre" class="openPopup" />
                </div>
            </article>
            <article>
                <div>
                    <p><strong><?= ucfirst($a['titre']) ?></strong></p>
                    <p><?= isset($a['datepublie']) ? formatDateDiff($a["datepublie"]) : '' ?></p>
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
                                    <?php foreach ($pictures as $picture) { ?>
                                        <div class="swiper-slide">
                                            <img src="<?= $picture ?>" />
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="swiper-pagination"></div>
                        </div>
                    <?php } ?>
                </div>
            </article>
        </div>
        <?php if ($a['idc_reponse']) { ?>
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
                            <p><?= isset($a['reponsedate']) ? formatDateDiff($a["reponsedate"]) : '' ?></p>
                            <img src="./img/icone/trois-points.png" alt="icone de parametre">
                        </div>
                    </div>
                    <article>
                        <p><?= $a['contenureponse'] ?></p>
                    </article>
                </div>
            </div>
        <?php } ?>
    </div>
    <?php
}

// Bouton "Charger plus"
if ($hasMore) {
    $nextOffset = $offset + $limit;
    echo "<a href='?offset=$nextOffset' class='load-more'>Charger plus</a>";
}
?>
