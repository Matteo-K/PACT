<?php 
    $stmt = $conn -> prepare("SELECT a.*, m.url, r.denomination, r.contenureponse, r.reponsedate FROM pact.avis a JOIN pact.membre m ON m.pseudo = a.pseudo LEFT JOIN pact.reponse r on r.idc_avis = a.idc where idoffre = ? order by a.datepublie asc");
    $stmt -> execute([$idOffre]);
    $avis = $stmt -> fetchAll(PDO::FETCH_ASSOC);


    print_r($avis);


    foreach($avis as $a){
        ?>
        <div class="messageAvis"> 
            <article class="user">
                <img src="<?= $a['url']?>">
                <p><?= $a['pseudo']?> </p>
                <div class="noteEtoile">
                    <?php
                        for($i=0; $i < $a['note']; $i++){
                            echo "<div class='star starAvis'></div>";
                        }
                        if(5-$a['note'] != 0){
                            for($i=0; $i < 5-$a['note']; $i++){
                                echo "<div class='star starAvisIncolore'></div>";
                            }
                        }
                    ?>
                </div>
                <img src="./img/icone/trois-points.png" alt="icone de parametre">
            </article>
            <article>
                <p>Visité en <?= ucfirst(strtolower($a['mois'])) . " " . $a['annee']?></p>
                <p> • </p>
                <p class="tag"><?= $a['companie']?></p>
            </article>
            <article>
                <p><?= $a['titre']?></p>
                <p><?=$a['content']?></p>
                <?php if($a['listimage'] != null){
                    $listimage = trim($a['listimage'], '{}');
                    $pictures = explode(',', $listimage);
                    ?>
                    
                    <div class="swiper-container">
                    <div class="swiper mySwiper">
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
                </div>
                <?php
                }
                if (isset($a['datepublie'])) {
                    // Créer des objets DateTime et fixer l'heure à minuit
                    $dateDB = new DateTime($a['datepublie']);
            
                    $dateNow = new DateTime();

                    // Fixer les objets DateTime à minuit pour la différence en jours
                    $dateDBMidnight = clone $dateDB;
                    $dateDBMidnight->setTime(0, 0, 0);

                    $dateNowMidnight = clone $dateNow;
                    $dateNowMidnight->setTime(0, 0, 0);
            
                    // Calculer la différence en jours (à partir de minuit)
                    $intervalDays = $dateDBMidnight->diff($dateNowMidnight);
                    $diffInDays = (int)$intervalDays->format('%r%a');

                    // Calculer la différence en heures pour le jour même
                    $intervalHours = $dateDB->diff($dateNow);
                    $diffInHours = $intervalHours->h; // Différence en heures
                    $diffInMinutes = $intervalHours->i; // Différence en minutes

                    // Déterminer le message à afficher
                    if ($diffInDays === 0) {
                        // La date est aujourd'hui, afficher la différence en heures
                        if ($diffInHours > 0) {
                            echo "Rédigé il y a $diffInHours heure" . ($diffInHours > 1 ? 's' : '');
                        } elseif ($diffInMinutes > 0) {
                            echo "Rédigé il y a $diffInMinutes minute" . ($diffInMinutes > 1 ? 's' : '');
                        } else {
                            echo "Rédigé à l'instant";
                        }
                    } elseif ($diffInDays === -1) {
                        // La date est hier
                        echo "Rédigé hier";
                    } elseif ($diffInDays >= -7 && $diffInDays < -1) {
                        // La date est dans les 7 derniers jours
                        echo "Rédigé il y a " . abs($diffInDays) . " jour" . (abs($diffInDays) > 1 ? 's' : '');
                    } else {
                        // La date est plus ancienne que 7 jours ou dans le futur
                        echo "Rédigé le " . $dateDB->format("d/m/Y à H:i");
                    }
                }
                ?>
            </article>
            
        <?php
            
        ?>
        </div>
    <?php 
    }
?>