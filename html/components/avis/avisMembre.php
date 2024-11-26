<?php 
    $stmt = $conn -> prepare("SELECT a.*, m.url, r.denomination, r.contenureponse, r.reponsedate FROM pact.avis a JOIN pact.membre m ON m.pseudo = a.pseudo LEFT JOIN pact.reponse r on r.idc_avis = a.idc where idoffre = ?");
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
                    $pictures = json_decode($a['listimage']);
                    print($pictures);
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
                ?>
            </article>
            
        <?php
            
        ?>
        </div>
        <?php
    }
?>
</div>