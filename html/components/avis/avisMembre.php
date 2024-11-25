<?php 
    $stmt = $conn -> prepare("SELECT * FROM pact.avis a where idoffre = ? LEFT JOIN pact.reponse r on r.idc_avis = a.idc");
    $stmt -> execute([$idOffre]);
    $avis = $stmt -> fetchAll(PDO::FETCH_ASSOC);


    print_r($avis);

?>


<div class="avis">
    <?php
    foreach($avis as $a){
        if($a['listimage'] == null){
            
        }
    }
    ?>
</div>