<?php 
    $stmt = $conn -> prepare("SELECT * FROM pact.avis a LEFT JOIN pact.membre m ON m.pseudo = a.pseudo LEFT JOIN pact.reponse r on r.idc_avis = a.idc where idoffre = ?");
    $stmt -> execute([$idOffre]);
    $avis = $stmt -> fetchAll(PDO::FETCH_ASSOC);


    print_r($avis);


    foreach($avis as $a){
        ?>
        <div>
        <?php
        if($a['listimage'] == null){
            
        }
        ?>
        </div>
        <?php
    }
?>
</div>