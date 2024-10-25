<form id="hourlyOffer" action="enregOffer.php" method="post">
    <?php
        $is_show = false;
        if (!$is_show) {
            $jour_semaine = ["Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi","Dimanche"];
            foreach ($jour_semaine as $value) {
                $stmt = $conn->prepare("SELECT m.heureouverture AS heurOuvMidi, m.heurefermeture AS heurFermMidi, s.heureouverture AS heurOuvSoir, s.heurefermeture AS heurFermSoir from pact._horairemidi m left join pact._horairesoir s on m.idoffre=s.idoffre and m.jour = s.jour WHERE m.idoffre=? and m.jour=?");
                $stmt->execute([$idOffre, $value]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($result !== false) {
                    $horairesOuv1 = $result["heurOuvMidi"];
                    $horairesFerm1 = $result["heurFermMidi"];
                    $horairesOuv2 = $result["heurOuvSoir"] == null ? "" : $result["heurOuvSoir"];
                    $horairesFerm2 = $result["heurFermSoir"] == null ? "" : $result["heurFermSoir"];
                } else {
                    $horairesOuv1 = "";
                    $horairesFerm1 = "";
                    $horairesOuv2 = "";
                    $horairesFerm2 = "";
                }
    ?>
    <!-- Créer une ligne pour chaque jour de la semaine -->
     <div>
        <div>
            <h4><?php echo $value?>&nbsp;:&nbsp;</h4>
        </div>
        <div>
            <input type="checkbox" id="check<?php echo $value?>" name="check<?php echo $value?>" onclick="toggleInputs(this)"/>
            <label for="check<?php echo $value?>">Fermé</label>
            <span>
                <span class="hourly1">
                    <label for="horairesOuv1<?php echo $value?>">Ouvert de</label>
                    <input type="time" name="horairesOuv1<?php echo $value?>" id="horairesOuv1<?php echo $value?>" value="<?php echo $horairesOuv1; ?>">
                    <!-- Zone de texte de type time pour saisir uniquement des heures -->
                    <label for="horairesF1<?php echo $value?>">à</label>
                    <input type="time" name="horairesF1<?php echo $value?>" id="horairesF1<?php echo $value?>" value="">
                    <!-- Zone de texte de type time pour saisir uniquement des heures -->
                </span>
                <input type="button" value="Ajouter un horaire" name="btnAjout<?php echo $value?>" id="btnAjout<?php echo $value?>" class="blueBtnOffer btnAddOffer">
                <!-- Partie avec les horaire de l'après midi du Lundi-->
                <span class="hourly2 hourlyHide">
                    <label for="horairesOuv2<?php echo $value?>">et de</label>
                    <input type="time" name="horairesOuv2<?php echo $value?>" id="horairesOuv2<?php echo $value?>" value="<?php echo $horairesOuv2; ?>">
                    <!-- Zone de texte de type time pour saisir uniquement des heures -->
                    <label for="horairesF2<?php echo $value?>">à</label>
                    <input type="time" name="horairesF2<?php echo $value?>" id="horairesF2<?php echo $value?>">
                    <!-- Zone de texte de type time pour saisir uniquement des heures -->
                </span>
                <input type="button" value="Retirer" name="btnRetirer<?php echo $value?>" id="btnRetirer<?php echo $value?>" value="<?php echo $horairesFerm2; ?>" class="blueBtnOffer btnRmOffer hourlyHide">
                <!-- bouton pour retirer les horaires -->
            </span>
        </div>
    </div>
    <?php
        }
    } else {
    ?>
        <div>
            <h4>Ajouter une date pour le spectacle&nbsp;:&nbsp;</h4>            
        </div>
        <div id="Representation">
            <div>
                <!-- Saisie de la date -->
                <!-- Lors de l'ajout dans la base de donnée, il faut vérifier si la date éxiste déjà à la même heure -->
                <input type="date" name="dateRepN1" id="dateRepresentation" value="<?php echo date("Y-m-j"); ?>" min="<?php echo date("Y-m-j"); ?>">
                <!-- Saisie des heures -->
                <span class="hourly1">
                    <label for="HRepN1_part1.1">Représentation de</label>
                    <input type="time" name="HRepN1_part1.1" id="HRepN1_part1.1">
                    <label for="HRepN1_part1.2">à</label>
                    <input type="time" name="HRepN1_part1.2" id="HRepN1_part1.2">
                </span>
                <input type="button" value="Retirer" name="btnRetirerRepN1" id="btnRetirerRepN1" class="blueBtnOffer" onclick="removeDateRep(this)">
            </div>
        </div>
        <input type="button" value="Ajouter une date" name="addRep" id="addRep" class="guideSelect" onclick="addDateRep()">
    <?php
    }
    ?>