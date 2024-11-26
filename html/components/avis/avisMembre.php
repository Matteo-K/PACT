<?php 
    $stmt = $conn -> prepare("SELECT * FROM pact.avis a LEFT JOIN pact.membre m ON m.pseudo = a.pseudo LEFT JOIN pact.reponse r on r.idc_avis = a.idc where idoffre = ?");
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
                        for($i=1; $i < $a['note']; $i++){
                            echo "<div class='star starAvis'></div>";
                        }
                        if(5-$a['note'] != 0){
                            for($i=1; $i < 5-$a['note']; $i++){
                                echo "<div class='star starAvisIncolore'></div>";
                            }
                        }
                    ?>
                </div>
            </article>
            
        <?php
            
        ?>
        </div>
        <?php
    }
?>
</div>