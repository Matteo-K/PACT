<?php
$is_show;
?>
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
            <h4>Ajouter une date pour le spectacle&nbsp;:&nbsp;</h4><span id="msgHoraireSupr"></span>          
        </div>
        <div id="Representation">
        </div>
        <input type="button" value="Ajouter une date" name="addRep" id="addRep" class="guideSelect" onclick="addDateRep()">
        <?php 
            $ar = new ArrayOffer($idOffre);
            $data = $ar->getArray();
        ?>
        <div id="offers-data" data-offers='<?php echo htmlspecialchars(json_encode($data)); ?>'></div>
        <script>
            let duree;
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

                duree = parseInt(arrayOffer[0].duree);
                const listHoraire = arrayOffer[0].horaire.map(item => JSON.parse(item));

                if (listHoraire.length != 0) {
                    listHoraire.forEach(element => {
                        addDateRep(element);
                    });
                } else {
                    addDateRep();
                }
            });
            /* Interraction horaire */
            function formatDate(date_) {
                let date = new Date(date_);
                return date.getUTCFullYear() + "-" +
                    String(date.getUTCMonth() + 1).padStart(2, "0") + "-" +
                    String(date.getUTCDate()).padStart(2, "0");
            }
            let counterRep = 0;
            let date_ = new Date();
            let current_date = formatDate(date_);
            /**
             * @brief Ajout d'un div représentation à chaque click du bouton ajouter bloc
             */
            function addDateRep(data = 0) {
                if (current_date <= formatDate(data.daterepresentation)) {
                    counterRep++;
                    const dateContainer = document.getElementById("Representation");

                    // Création d'un nouveau bloc
                    const newBlock = document.createElement("div");
                    let date = document.createElement("input");
                    date.setAttribute("type", "text");
                    date.setAttribute("name", "dates["+counterRep+"][trip-start]");
                    date.id = "dateRepN"+counterRep;

                    const span = document.createElement("span");
                    span.classList.add("hourly1");

                    const lblRep2 = document.createElement("label");
                    lblRep2.setAttribute("for", "HRepN"+ counterRep +"_part1.2");
                    lblRep2.textContent = "à";
                    
                    const inputHoraire2 = document.createElement("input");
                    inputHoraire2.setAttribute("type", "time");
                    inputHoraire2.setAttribute("name", "dates["+counterRep+"][HRep_part1.2]");
                    inputHoraire2.id = "HRepN" + counterRep + "_part1.2";
                    inputHoraire2.addEventListener("change", () => {removeMsgErreur()});
                    
                    const lblRep1 = document.createElement("label");
                    lblRep1.setAttribute("for", "HRepN"+ counterRep +"_part1.1");
                    lblRep1.textContent = "Représentation de";

                    const inputHoraire1 = document.createElement("input");
                    inputHoraire1.setAttribute("type", "time");
                    inputHoraire1.setAttribute("name", "dates["+counterRep+"][HRep_part1.1]");
                    inputHoraire1.id = "HRepN"+counterRep+"_part1.1";
                    inputHoraire1.addEventListener("change", () => {
                        removeMsgErreur();
                        updateHeureFin(inputHoraire1, inputHoraire2, duree);
                    });

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
                    
                    if (!data) {
                        flatpickr("#"+date.id, {
                            altInput: true,
                            altFormat: "l j F Y",
                            dateFormat: "Y-m-d",
                            locale: "fr",
                            defaultDate: current_date,
                            minDate: current_date
                        });
                    } else {
                        
                        inputHoraire1.setAttribute("value", data.heureouverture);
                        inputHoraire2.setAttribute("value", data.heurefermeture);
                        
                        flatpickr("#"+date.id, {
                            altInput: true,
                            altFormat: "l j F Y",
                            dateFormat: "Y-m-d",
                            locale: "fr",
                            defaultDate: formatDate(data.daterepresentation),
                            minDate: current_date
                        });
                    }
                } else {
                    const suppr = document.getElementById("msgHoraireSupr");
                    suppr.textContent = "Certaines horaires ont été supprimées pour cause d'expiration.";
                    setTimeout(function() {
                        suppr.style.display = "none";
                    }, 4000); // 1s
                }
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

            /**
             * Modifie l'horaire de fin suivant l'horaire du début et la durée du spectacle
             */
            function updateHeureFin(inputHoraire1, inputHoraire2, duree) {
                let startTime = inputHoraire1.value || "00:00";
                
                let [startHours, startMinutes] = startTime.split(":").map(Number);

                // Si l'heure ou les minutes sont vides, les mettre à 0
                startHours = isNaN(startHours) ? 0 : startHours;
                startMinutes = isNaN(startMinutes) ? 0 : startMinutes;

                let dureeHeures = Math.floor(duree / 60);
                let dureeMinutes = duree % 60;

                let endHours = startHours + dureeHeures;
                let endMinutes = startMinutes + dureeMinutes;

                if (endMinutes >= 60) {
                    endMinutes -= 60;
                    endHours++;
                }

                if (endHours >= 24) {
                    endHours -= 24;
                }

                let formattedEndTime = `${String(endHours).padStart(2, '0')}:${String(endMinutes).padStart(2, '0')}`;
                inputHoraire2.value = formattedEndTime;
            }


            function checkOfferValidity(event) {
                let inputime = checkInput();
                let inputDate = checkEmptyDates();
                let unique = checkUniqueDate();
                return inputime && inputDate && unique;
            }

            /**
             * Vérifie si les input time ne sont pas vide ou partiellement vide
             * @returns {boolean} - Renvoie true si les inputs est conforme. False sinon.
             */
            function checkInput() {
                const timeInputs = document.querySelectorAll('input[type="time"]');
                
                for (let input of timeInputs) {
                    if (!input.value || input.value.length < 5) {
                        const dateInput = input.closest("div").querySelector('input[name*="trip-start"]');
                        if (dateInput) {
                            const dateValue = dateInput.value;
                            const dateObj = new Date(dateValue);
                            
                            const formattedDate = `${dateObj.getDate()}/${dateObj.getMonth() + 1}/${dateObj.getFullYear()}`;
                            
                            document.getElementById("msgHoraireSupr").textContent = `Il manque une horaire de spectacle au jour ${formattedDate}`;
                        }
                        return false;
                    }
                }    
                return true;
            }
            /**
             * @brief Vérifie si une date est vide
             */
            function checkEmptyDates() {
                const dateInputs = document.querySelectorAll('input[name^="dates["][name$="][trip-start]"]');
                for (let input of dateInputs) {
                    if (input.value.trim() === "") {
                        document.getElementById("msgHoraireSupr").textContent = `Veuillez remplir toutes les dates. Une date est manquante.`;
                        return false;
                    }
                }
                return true;
            }

            /**
             * @brief Vérifie si une date et l'heure de début est unique
             */
            function checkUniqueDate() {
                const dateInputs = document.querySelectorAll('input[name^="dates["][name$="][trip-start]"]');
                const timeInputs = document.querySelectorAll('input[name^="dates["][name$="][HRep_part1.1]"]');
                
                let dateTimeCombinations = [];

                for (let i = 0; i < dateInputs.length; i++) {
                    const date = dateInputs[i].value.trim();
                    const startTime = timeInputs[i].value.trim();
                    
                    if (!date || !startTime) {
                        continue;
                    }

                    const dateTime = `${date} ${startTime}`;
                    if (dateTimeCombinations.includes(dateTime)) {
                        document.getElementById("msgHoraireSupr").textContent = "Une combinaison de date et heure est déjà utilisée.";
                        return false;
                    } else {
                        dateTimeCombinations.push(dateTime);
                    }
                }
                return true;
            }

            function removeMsgErreur() {
                document.getElementById("msgHoraireSupr").textContent = "";
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
    <input type="hidden" name="typeOffre" id="typeOffre" value="<?php echo $is_show ?>">