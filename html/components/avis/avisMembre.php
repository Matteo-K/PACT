<?php
$stmt = $conn->prepare("SELECT * from pact.proprive where idu = ?");
$stmt->execute([$offre[0]['idu']]);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$commentaire = $stmt->fetch(PDO::FETCH_ASSOC);

function nbChiffreNombre($number)
{
    return strlen((string)$number);
}

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
            return "Rédigé il y a " . ($diffInHours - 1) . " heure" . ($diffInHours > 1 ? 's' : '');
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
    $likeId = 'like_' . $a['idc'];
    $dislikeId = 'dislike_' . $a['idc'];
?>
    <div class="messageAvisReponse">
        <div class="messageAvis">
            <article class="user">
                <div class="infoUser">
                    <img src="<?= $a['membre_url'] ?>" alt="User Image">
                    <p><?= ucfirst($a['pseudo']) ?></p>
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
                        <section>
                            <p><?= $a['content'] ?></p>
                        </section>
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
                        <?php } ?>
                    </div>
                    <div class="container">
                        <label for="like">
                            <input type="checkbox" name="evaluation" id="<?= $likeId ?>" />
                            <svg class="icon like" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <path d="M20 8h-5.612l1.123-3.367c.202-.608.1-1.282-.275-1.802S14.253 2 13.612 2H12c-.297 0-.578.132-.769.36L6.531 8H4c-1.103 0-2 .897-2 2v9c0 1.103.897 2 2 2h13.307a2.01 2.01 0 0 0 1.873-1.298l2.757-7.351A1 1 0 0 0 22 12v-2c0-1.103-.897-2-2-2zM4 10h2v9H4v-9zm16 1.819L17.307 19H8V9.362L12.468 4h1.146l-1.562 4.683A.998.998 0 0 0 13 10h7v1.819z"></path>
                            </svg>
                        </label>
                        <div class="count likes">
                            <?php
                            $nbLike = (string)$a["nblike"];
                            for ($i = 0; $i < nbChiffreNombre($a["nblike"]); $i++) {
                            ?>
                                <div class="number" style="transform: var(--nb<?= $nbLike[$i] ?>);">
                                    <span>0</span>
                                    <span>1</span>
                                    <span>2</span>
                                    <span>3</span>
                                    <span>4</span>
                                    <span>5</span>
                                    <span>6</span>
                                    <span>7</span>
                                    <span>8</span>
                                    <span>9</span>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                        <div class="count dislikes">
                            <?php
                            $nbDislike = (string)$a["nbdislike"];
                            for ($i = 0; $i < strlen($nbDislike); $i++) {
                            ?>
                                <div class="number" style="transform: var(--nb<?= $nbDislike[$i] ?>);">
                                    <span>0</span>
                                    <span>1</span>
                                    <span>2</span>
                                    <span>3</span>
                                    <span>4</span>
                                    <span>5</span>
                                    <span>6</span>
                                    <span>7</span>
                                    <span>8</span>
                                    <span>9</span>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                        <label for="dislike">
                            <input type="checkbox" name="evaluation" id="<?= $dislikeId ?>" />
                            <svg class="icon dislike" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <path d="M20 3H6.693A2.01 2.01 0 0 0 4.82 4.298l-2.757 7.351A1 1 0 0 0 2 12v2c0 1.103.897 2 2 2h5.612L8.49 19.367a2.004 2.004 0 0 0 .274 1.802c.283.374.686.605 1.138.605h1.612c.297 0 .578-.132.769-.36L17.469 16H20c1.103 0 2-.897 2-2V6c0-1.103-.897-2-2-2zM6 13V4h2v9H6zm16-1.819L17.307 5H8v6.638L12.468 20h1.146l-1.562-4.683A.998.998 0 0 0 13 14h7v-1.819z"></path>
                            </svg>
                        </label>
                    </div>
                </div>
            </article>
        </div>
    </div>
<?php
}
?>
<script>
    document.addEventListener('DOMContentLoaded', () => {
    // Fonction pour mettre à jour les chiffres dynamiquement
    function updateNumber(countElement, number) {
        const digits = countElement.querySelectorAll('.number');
        const numString = number.toString();

        digits.forEach((digit, index) => {
            const digitValue = numString[index] || '0';
            digit.style.transform = `var(--nb${digitValue})`;
        });
    }

    // Initialiser les compteurs des likes et dislikes
    document.querySelectorAll('.likes').forEach(likeCountElement => {
        const likeCount = likeCountElement.getAttribute('data-like-count');
        updateNumber(likeCountElement, likeCount);
    });

    document.querySelectorAll('.dislikes').forEach(dislikeCountElement => {
        const dislikeCount = dislikeCountElement.getAttribute('data-dislike-count');
        updateNumber(dislikeCountElement, dislikeCount);
    });

    // Gérer les événements de clic pour les boutons like et dislike
    document.querySelectorAll('.like').forEach(likeButton => {
        likeButton.addEventListener('click', () => {
            const likeCheckbox = likeButton.closest('.messageAvis').querySelector('input[type="checkbox"][id^="like_"]');
            const likeCountElement = likeButton.closest('.messageAvis').querySelector('.likes');

            // Ajout d'une classe active au bouton Like
            likeButton.classList.toggle('active');
            
            if (likeCheckbox.checked) {
                // L'utilisateur a aimé l'avis
                let currentCount = parseInt(likeCountElement.getAttribute('data-like-count'), 10);
                currentCount++;
                likeCountElement.setAttribute('data-like-count', currentCount);
                updateNumber(likeCountElement, currentCount);
            }
        });
    });

    document.querySelectorAll('.dislike').forEach(dislikeButton => {
        dislikeButton.addEventListener('click', () => {
            const dislikeCheckbox = dislikeButton.closest('.messageAvis').querySelector('input[type="checkbox"][id^="dislike_"]');
            const dislikeCountElement = dislikeButton.closest('.messageAvis').querySelector('.dislikes');

            // Ajout d'une classe active au bouton Dislike
            dislikeButton.classList.toggle('active');
            
            if (dislikeCheckbox.checked) {
                // L'utilisateur a disliké l'avis
                let currentCount = parseInt(dislikeCountElement.getAttribute('data-dislike-count'), 10);
                currentCount++;
                dislikeCountElement.setAttribute('data-dislike-count', currentCount);
                updateNumber(dislikeCountElement, currentCount);
            }
        });
    });
});

</script>