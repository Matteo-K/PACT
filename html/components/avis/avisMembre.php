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
    $dateDB = new DateTime($date);
    $dateNow = new DateTime();

    $dateDBMidnight = clone $dateDB;
    $dateDBMidnight->setTime(0, 0, 0);

    $dateNowMidnight = clone $dateNow;
    $dateNowMidnight->setTime(0, 0, 0);

    $intervalDays = $dateDBMidnight->diff($dateNowMidnight);
    $diffInDays = (int)$intervalDays->format('%r%a');

    $interval = $dateDB->diff($dateNow);
    $diffInHours = $interval->h + ($interval->days * 24);
    $diffInMinutes = $interval->i;

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
                        <label for="like_<?= $a['idc'] ?>">
                            <input type="checkbox" name="evaluation" class="checkboxes likes" onchange="likeAndDislike(this, 'like')" id="<?= $likeId ?>" />
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
                        <label for="dislike_<?= $a['idc'] ?>">
                            <input type="checkbox" name="evaluation" class="checkboxes dislikes" onchange="likeAndDislike(this, 'dislike')" id="<?= $dislikeId ?>" />
                            <svg class="icon dislike" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <path d="M20 3H6.693A2.01 2.01 0 0 0 4.82 4.298l-2.757 7.351A1 1 0 0 0 2 12v2c0 1.103.897 2 2 2h5.612L8.49 19.367a2.004 2.004 0 0 0 .274 1.802c.376.52.982.831 1.624.831H12c.297 0 .578-.132.769-.36l4.7-5.64H20c1.103 0 2-.897 2-2V5c0-1.103-.897-2-2-2zm-8.469 17h-1.145l1.562-4.684A1 1 0 0 0 11 14H4v-1.819L6.693 5H16v9.638L11.531 20zM18 14V5h2l.002 9H18z"></path>
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
    function likeAndDislike(checkbox, action) {
        const container = document.getElementById(checkbox.id);
        const likeId = "like_" + checkbox.id.split("_")[1];
        const dislikeId = "dislike_" + checkbox.id.split("_")[1];

        const likeCheckbox = document.getElementById(likeId);
        const dislikeCheckbox = document.getElementById(dislikeId);

        console.log(checkbox.id);

        if (action === 'like') {
            if (checkbox.checked) {
                // Si "like" est coché, décocher "dislike" si nécessaire
                if (dislikeCheckbox.checked) {
                    updateCount('undislike', checkbox.id);
                    dislikeCheckbox.checked = false;
                }
                updateCount('like', checkbox.id); // Enregistrer le "like"
            } else {
                updateCount('unlike', checkbox.id); // Annuler le "like"
            }
        } else if (action === 'dislike') {
            if (checkbox.checked) {
                // Si "dislike" est coché, décocher "like" si nécessaire
                if (likeCheckbox.checked) {
                    updateCount('unlike', checkbox.id);
                    likeCheckbox.checked = false;
                }
                updateCount('dislike', checkbox.id); // Enregistrer le "dislike"
            } else {
                updateCount('undislike', checkbox.id); // Annuler le "dislike"
            }
        }
    }


    function updateCount(action, id) {
        // Envoyer une requête à `updateLike.php` avec l'ID de l'avis
        fetch('updateLike.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    action: action,
                    id: id
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Erreur HTTP ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Réponse du serveur :', data); // Ajoutez cette ligne pour afficher la réponse du serveur
                if (data.success) {
                    updateNumberDisplay(`#${id} .count.likes .number`, data.nblike);
                    updateNumberDisplay(`#${id} .count.dislikes .number`, data.nbdislike);
                } else {
                    alert('Erreur lors de la mise à jour des likes/dislikes.');
                }
            })
            .catch(error => {
                alert('Une erreur est survenue. Veuillez réessayer plus tard.');
            });

    }

    function updateNumberDisplay(selector, number) {
        // Convertir le nombre en chaîne
        const numberStr = number.toString();

        // Récupérer tous les éléments .number à l'intérieur du selector
        const numbers = document.querySelectorAll(selector);

        // Parcourir chaque élément .number
        numbers.forEach((el, index) => {
            const digit = numberStr[index] || '0'; // Si il n'y a pas assez de chiffres, utiliser '0'

            // Mettre à jour la position du chiffre
            el.style.transform = `var(--nb${digit})`;

            // Mettre à jour le contenu du chiffre
            el.querySelector('span').textContent = digit;
        });
    }
</script>