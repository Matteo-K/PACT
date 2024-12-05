<form id="hourlyOffer" action="enregOffer.php" method="post">
    <?php
        $stmt = $conn->prepare("SELECT table_name
            FROM (
                SELECT '_restauration' AS table_name, COUNT(*) AS rows FROM pact._restauration WHERE idoffre = ?
                UNION ALL
                SELECT '_spectacle', COUNT(*) FROM pact._spectacle WHERE idoffre = ?
                UNION ALL
                SELECT '_parcattraction', COUNT(*) FROM pact._parcattraction WHERE idoffre = ?
                UNION ALL
                SELECT '_visite', COUNT(*) FROM pact._visite WHERE idoffre = ?
                UNION ALL
                SELECT '_activite', COUNT(*) FROM pact._activite WHERE idoffre = ?
            ) AS result
            WHERE rows > 0;");
        $stmt->execute([$idOffre, $idOffre, $idOffre, $idOffre, $idOffre]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result == false) {
            $is_show = -1;
        } else if ($result["table_name"] == "_spectacle") {
            $is_show = 1;
        } else if ($result["table_name"] === "_restauration" 
        || $result["table_name"] === "_parcattraction" 
        || $result["table_name"] === "_visite" 
        || $result["table_name"] === "_activite") {
            $is_show = 0;
        }
        if ($is_show == 0) {
            $jour_semaine = ["Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi","Dimanche"];
            foreach ($jour_semaine as $value) {
                $stmt = $conn->prepare("SELECT heureouverture, heurefermeture FROM pact._horairemidi WHERE idoffre=? and jour=?");
                $stmt->execute([$idOffre, $value]);
                $jour = $stmt->fetch(PDO::FETCH_ASSOC);
                $stmt = $conn->prepare("SELECT heureouverture, heurefermeture FROM pact._horairesoir WHERE idoffre=? and jour=?");
                $stmt->execute([$idOffre, $value]);
                $soir = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($jour !== false) {
                    $horairesOuv1 = $jour["heureouverture"];
                    $horairesFerm1 = $jour["heurefermeture"];
                    if ($soir !== false) {
                        $horairesOuv2 = $soir["heureouverture"];
                        $horairesFerm2 = $soir["heurefermeture"];
                        $soir = true;
                    } else {
                        $horairesOuv2 = "";
                        $horairesFerm2 = "";
                        $soir = false;
                    }
                } else {
                    $horairesOuv1 = "";
                    $horairesFerm1 = "";
                    $horairesOuv2 = "";
                    $horairesFerm2 = "";
                    $soir = false;
                }
                // Mettre les boutons cacher ou non lors de l'ajout
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
                    <input type="time" name="horairesF1<?php echo $value?>" id="horairesF1<?php echo $value?>" value="<?php echo $horairesFerm1; ?>">
                    <!-- Zone de texte de type time pour saisir uniquement des heures -->
                </span>
                <input type="button" value="Ajouter un horaire" name="btnAjout<?php echo $value?>" id="btnAjout<?php echo $value?>" class="blueBtnOffer btnAddOffer <?php echo $soir?"hourlyHide" : ""?>">
                <!-- Partie avec les horaire de l'après midi du Lundi-->
                <span class="hourly2 <?php echo $soir?"" : "hourlyHide"?>">
                    <label for="horairesOuv2<?php echo $value?>">et de</label>
                    <input type="time" name="horairesOuv2<?php echo $value?>" id="horairesOuv2<?php echo $value?>" value="<?php echo $horairesOuv2; ?>">
                    <!-- Zone de texte de type time pour saisir uniquement des heures -->
                    <label for="horairesF2<?php echo $value?>">à</label>
                    <input type="time" name="horairesF2<?php echo $value?>" id="horairesF2<?php echo $value?>" value="<?php  echo $horairesFerm2;  ?>">
                    <!-- Zone de texte de type time pour saisir uniquement des heures -->
                </span>
                <input type="button" value="Retirer" name="btnRetirer<?php echo $value?>" id="btnRetirer<?php echo $value?>" value="<?php echo $horairesFerm2; ?>" class="blueBtnOffer btnRmOffer <?php echo $soir?"" : "hourlyHide"?>">
                <!-- bouton pour retirer les horaires -->
            </span>
        </div>
    </div>
    <?php
        }
    } else if ($is_show == 1) {
    ?>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/fr.js"></script>
        <div>
            <h4>Ajouter une date pour le spectacle&nbsp;:&nbsp;</h4>            
        </div>
        <div id="Representation">
            <div>
            <input type="text" id="start" name="trip-start">
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
        <script>
            flatpickr("#start", {
                altInput: true,
                altFormat: "l j F Y",
                dateFormat: "Y-m-d",
                locale: "fr",
                defaultDate: "2024-12-05",
                minDate: "2024-12-01"
            });
            /* Interraction horaire */
            let counterRep = 1;
            let date_ = new Date();
            let current_date = String(
                date_.getUTCFullYear() +
                "-" +
                ("0" + (date_.getUTCMonth() + 1)).slice(-2) +
                "-" +
                ("0" + date_.getUTCDate()).slice(-2)
            );
            /**
             * @brief Ajout d'un div représentation à chaque click du bouton ajouter bloc
             */
            function addDateRep() {
                counterRep++;
                const dateContainer = document.getElementById("Representation");

                // Création d'un nouveau bloc
                const newBlock = document.createElement("div");
                newBlock.innerHTML = `
                    <label for="dateRepresentation">jour</label>
                    <input type="date" name="dateRepN${counterRep}" id="dateRepresentation" value="${current_date}" min="${current_date}">
                    <span class="hourly1">
                        <label for="HRepN${counterRep}_part1.1">Représentation de</label>
                        <input type="time" name="HRepN${counterRep}_part1.1" id="HRepN${counterRep}_part1.1">
                        <label for="HRepN${counterRep}_part1.2">à</label>
                        <input type="time" name="HRepN${counterRep}_part1.2" id="HRepN${counterRep}_part1.2">
                    </span>
                    <input type="button" value="Retirer" name="btnRetirerRepN${counterRep}" id="btnRetirerRepN${counterRep}" class="blueBtnOffer" onclick="removeDateRep(this)">
                `;

                // Ajout du nouveau bloc au bloc de représentation
                dateContainer.appendChild(newBlock);
            }

            /**
             * @brief Retire le bloc Représentation
             */
            function removeDateRep(button) {
                const rep = button.parentElement;
                rep.remove();
            }

            function toggleInputs(checkbox) {
                const timeInputs = checkbox.parentNode.querySelectorAll('input[type="time"]');
                const buttons = checkbox.parentNode.querySelectorAll("input[type='button']");
                if (checkbox.checked) {
                // Désactiver les boutons et inputs time
                buttons.forEach((button) => {
                    button.disabled = true;
                    button.classList.add("btnDisabledHourly");
                });
                timeInputs.forEach((input) => {
                    input.disabled = true;
                    input.value = ""; // Réinitialiser le contenu des inputs time
                });
                } else {
                // Réactiver les boutons et inputs time
                buttons.forEach((button) => {
                    button.disabled = false;
                    button.classList.remove("btnDisabledHourly");
                });
                timeInputs.forEach((input) => (input.disabled = false));
                }
            }
        </script>
    <?php
    } else {
        ?>
        <div>
            <h3>Veuillez sélectionner Le type d'offre</h3>
            <button type="submit" onclick="submitForm(event,2)">Détails de l'offre</button>
        </div>
        <?php
    }
    ?>
    <input type="hidden" name="typeOffre" id="typeOffre" value="<?php echo $result ?>">