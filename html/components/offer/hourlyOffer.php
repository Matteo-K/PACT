<form id="hourlyOffer" action="enregOffer.php" method="post">
    <?php
        $is_show = false;
        if (!$is_show) {
            $jour_semaine = ["Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi","Dimanche"];
            foreach ($jour_semaine as $value) {
    ?>
    <!-- Créer une ligne pour chaque jour de la semaine -->
     <div>
        <div>
            <h4><?php echo $value?>&nbsp;:&nbsp;</h4>
        </div>
        <div>
            <input type="checkbox" id="check<?php echo $value?>" name="check<?php echo $value?>" />
            <label for="check<?php echo $value?>">Ferme</label>
            <span class="hourly1">
                <label for="horairesOuv1<?php echo $value?>">Ouvert de</label>
                <input type="time" name="horairesOuv1<?php echo $value?>" id="horairesOuv1<?php echo $value?>">
                <!-- Zone de texte de type time pour saisir uniquement des heures -->
                <label for="horairesF1<?php echo $value?>">à</label>
                <input type="time" name="horairesF1<?php echo $value?>" id="horairesF1<?php echo $value?>">
                <!-- Zone de texte de type time pour saisir uniquement des heures -->
            </span>
            <input type="button" value="Ajouter un horaire" name="btnAjout<?php echo $value?>" id="btnAjout<?php echo $value?>" class="blueBtnOffer btnAddOffer">
            <!-- Partie avec les horaire de l'après midi du Lundi-->
            <span class="hourly2 hourlyHide">
                <label for="horairesOuv2<?php echo $value?>">et de</label>
                <input type="time" name="horairesOuv2<?php echo $value?>" id="horairesOuv2<?php echo $value?>">
                <!-- Zone de texte de type time pour saisir uniquement des heures -->
                <label for="horairesF2<?php echo $value?>">à</label>
                <input type="time" name="horairesF2<?php echo $value?>" id="horairesF2<?php echo $value?>">
                <!-- Zone de texte de type time pour saisir uniquement des heures -->
            </span>
            <input type="button" value="Retirer" name="btnRetirer<?php echo $value?>" id="btnRetirer<?php echo $value?>" class="blueBtnOffer btnRmOffer hourlyHide">
            <!-- bouton pour retirer les horaires -->
        </div>
    </div>
    <?php
        }
    } else {
    ?>

    <?php
    }
    ?>