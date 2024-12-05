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
    <script>
        const btnsAddHourly = document.querySelectorAll(".btnAddOffer");
        const btnsRmHourly = document.querySelectorAll(".btnRmOffer");

        /**
         * @brief Ajoute une horaire à la journée
         */
        btnsAddHourly.forEach((button) => {
            button.addEventListener("click", () => {
            let nextSpan = button.nextElementSibling;
            let nextBtn = nextSpan.nextElementSibling;
            nextSpan.classList.remove("hourlyHide");
            nextBtn.classList.remove("hourlyHide");
            button.classList.add("hourlyHide");
            });
        });

        /**
         * @brief Retire une horaire à la journée
         */
        btnsRmHourly.forEach((button) => {
            button.addEventListener("click", () => {
            let span = button.previousElementSibling;
            span.querySelectorAll("input").forEach((input) => {
                input.value = "";
                console.log(input);
            });
            span.classList.add("hourlyHide");
            button.classList.add("hourlyHide");
            span.previousElementSibling.classList.remove("hourlyHide");
            });
        });
    </script>
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
        </div>
        <input type="button" value="Ajouter une date" name="addRep" id="addRep" class="guideSelect" onclick="addDateRep()">
        <?php 
            $ar = new ArrayOffer($idOffre);
            $data = $ar->getArray();
            print_r($data);
        ?>
        <div id="offers-data" data-offers='<?php echo htmlspecialchars(json_encode($data)); ?>'></div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const offersDataElement = document.getElementById('offers-data');
                
                const offersData = offersDataElement.getAttribute('data-offers');
                // console.log(offersData); // Débugger

                try {
                    arrayOffer = JSON.parse(offersData);
                    arrayOffer = Object.values(arrayOffer);
                } catch (error) {
                    console.error("Erreur de parsing JSON :", error);
                }

                console.log(arrayOffer[0].horaire);

                arrayOffer.forEach(element => {
                    
                });
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
            function addDateRep(data = 0) {
                counterRep++;
                const dateContainer = document.getElementById("Representation");

                // Création d'un nouveau bloc
                const newBlock = document.createElement("div");
                let date = document.createElement("input");
                date.setAttribute("type", "text");
                date.setAttribute("name", "trip-start");
                date.id = "dateRepN"+counterRep;

                const span = document.createElement("span");
                span.classList.add("hourly1");

                const lblRep1 = document.createElement("label");
                lblRep1.setAttribute("for", "HRepN"+ counterRep +"_part1.1");
                lblRep1.textContent = "Représentation de";

                const inputHoraire1 = document.createElement("input");
                inputHoraire1.setAttribute("type", "time");
                inputHoraire1.setAttribute("name", "HRepN"+counterRep+"_part1.1");
                inputHoraire1.id = "HRepN"+counterRep+"_part1.1";

                const lblRep2 = document.createElement("label");
                lblRep2.setAttribute("for", "HRepN"+ counterRep +"_part1.2");
                lblRep2.textContent = "à";

                const inputHoraire2 = document.createElement("input");
                inputHoraire2.setAttribute("type", "time");
                inputHoraire2.setAttribute("name", "HRepN" + counterRep + "_part1.2");
                inputHoraire2.id = "HRepN" + counterRep + "_part1.2";

                span.appendChild(lblRep1);
                span.appendChild(inputHoraire1);
                span.appendChild(lblRep2);
                span.appendChild(inputHoraire2);

                
                const ajouterDate = document.createElement("input");
                ajouterDate.setAttribute("type", "button");
                ajouterDate.setAttribute("value", "Retirer");
                ajouterDate.setAttribute("name", "btnRetirerRepN"+counterRep);
                ajouterDate.id = "btnRetirerRepN"+counterRep;
                ajouterDate.classList.add("blueBtnOffer");
                ajouterDate.setAttribute("onclick", "removeDateRep(this)");
                
                newBlock.appendChild(date);
                newBlock.appendChild(span);
                newBlock.appendChild(ajouterDate);

                // Ajout du nouveau bloc au bloc de représentation
                dateContainer.appendChild(newBlock);

                if (data = 0) {
                    flatpickr("#"+date.id, {
                        altInput: true,
                        altFormat: "l j F Y",
                        dateFormat: "Y-m-d",
                        locale: "fr",
                        defaultDate: current_date,
                        minDate: current_date
                    });
                } else {

                    inputHoraire1.setAttribute("value", "19:00");
                    inputHoraire2.setAttribute("value", "19:00");
                    
                    flatpickr("#"+date.id, {
                        altInput: true,
                        altFormat: "l j F Y",
                        dateFormat: "Y-m-d",
                        locale: "fr",
                        defaultDate: "2024-12-05",
                        minDate: current_date
                    });
                }


            }

            addDateRep();

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