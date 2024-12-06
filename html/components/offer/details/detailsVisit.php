<!-- Visite -->
<?php
$langue = [];
$accessibilite = [];
$visite = [
    "guide" => true,
    "duree" => "",
    "prixminimal" => "",
    "accessibilite" => true,
    "nomAccess" => [],
    "langue" => []
];

$stmt = $conn->prepare("SELECT * from pact._langue");
$stmt->execute();
while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $langue[] = $result["langue"];
}

$stmt = $conn->prepare("SELECT * from pact._accessibilite");
$stmt->execute();
while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $accessibilite[] = $result["nomaccess"];
}

// Si la visite était déà existante, on récupère les données
if ($categorie["_visite"]) {
    $stmt = $conn->prepare("SELECT * from pact._visite where idoffre=?");
    $stmt->execute([$idOffre]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $visite["guide"] = $result["guide"];
        $visite["duree"] = $result["duree"];
        $visite["prixminimal"] = $result["prixminimal"];
        $visite["nomAccess"] = $result["nomAccess"];
    }
    // Langue
    $stmt = $conn->prepare("SELECT langue from pact._visite_langue where idoffre=?");
    $stmt->execute([$idOffre]);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $visite["langue"][] = $row["langue"];
    }

    // Accessibilité
    $stmt = $conn->prepare("SELECT * from pact._offreAccess where idoffre=?");
    $stmt->execute([$idOffre]);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $visite["nomAccess"][] = $row["nomAccess"];
    }
}

?>
<!-- Partie sur les informations de la visite -->
<section id="visit"> <!-- donne un id a la section pour l'identifier dans le css -->
    <!-- Visite guidée ou non  -->
    <div class="visGuideeEtDuree">
        <label>Visite Guidée </label>

        <input type="radio" id="guidee" name="VisiteGuidee" value="Guidee" checked>
        <!-- Par défaut la visite est guidée-->

        <label for="guidee"> Oui</label>

        <!-- Seul un des 2 radios button peut être coché car une visite ne peut pas être guidée et non guidée en même temps -->
        <input type="radio" id="pasGuidee" name="VisiteGuidee" value="pasGuidee">

        <label for="pasGuidee"> Non </label>


        <label class="labDureeVis">Durée :</label> <!-- Label durée -->

        <input type="number" id="numberHVisit" name="numberHVisit" min="0" placeholder="0" />
        <!-- zone de texte ou seul un chiffre/nombre est accepte -->
        <label class="labH">h</label> <!-- Label H (pour heure) -->

    </div>

    <!-- Gestion de l'accessibilité (handicap ) depuis la BDD -->
    <div class="access">
        <select name="nomAccess" id="nomAccess">
            <option value="SelectionAccess">-- Sélectionner un handicap --</option>
            <?php foreach ($accessibilite as $key => $value) { ?>
                <option value="<?php echo $value ?>"><?php echo $value ?></option>
            <?php } ?>
        </select>
    </div>

    <!-- Gestion du prix minimum pour une visite -->
    <div class="divPrixMin">
        <label for="PrixMinVisit">Prix minimum</label>


        <input type="number" id="PrixMinVisit" name="PrixMinVisit" min="0" placeholder="0">
        <label class="labEuro">€</label>
    </div>

    <!-- Partie pour la gestion des langues proposer par la visite -->
    <div class="languesProp">
        <label>Langue proposée(s) :</label> <!-- Label langue proposée -->
        <label>Sélectionner les langue(s) proposée(s) par votre visite."</label> <!-- Indication à l'utilisateur-->

        <!-- Proposition des langues disponible à partir de la BDD -->
        <select name="langue" id="selectionLangue">
            <option value="selectionLangue">-- Sélectionner une langue --</option>
            <?php foreach ($langue as $key => $value) { ?>
                <option value="<?php echo $value ?>"><?php echo $value ?></option>
            <?php } ?>
        </select>

        <section id="sectionLangue">
            <!-- Les langues ajoutées apparaîtront ici -->
        </section>
</section>










<!-- Script Js  -->

<script>

    // Récupération des éléments nécessaires
    const selectAccessibilite = document.getElementById('nomAccess');
    const sectionAccessibilite = document.createElement('div'); // Conteneur pour les tags
    sectionAccessibilite.id = 'sectionAccessibilite';
    sectionAccessibilite.style.marginTop = '10px';
    selectAccessibilite.parentNode.appendChild(sectionAccessibilite); // Ajout après le select

    // Écouteur d'événement pour détecter un changement dans le select
    selectAccessibilite.addEventListener('change', function () {
        const selectedValue = this.value; // Récupère la valeur sélectionnée

        // Vérifier si une option d'accessibilité a été sélectionnée
        if (selectedValue !== 'SelectionAccess') {
            // Vérifier si l'option est déjà ajoutée
            if (document.getElementById(`access-${selectedValue}`)) {
                alert(`L'accessibilité "${selectedValue}" est déjà ajoutée !`);
            } else {
                // Créer un conteneur pour l'option sélectionnée
                const accessDiv = document.createElement('div');
                accessDiv.className = 'access-item';
                accessDiv.id = `access-${selectedValue}`; // ID unique pour éviter les doublons

                // Ajouter le nom de l'option dans un élément stylisé
                const accessText = document.createElement('span');
                accessText.textContent = selectedValue;
                accessText.className = 'access-text';

                // Ajouter un bouton de suppression
                const removeBtn = document.createElement('button');
                removeBtn.innerHTML = '<span style="font-size: 1.2em; color: #d32f2f;">&times;</span>'; // Symbole "×" stylisé
                removeBtn.className = 'remove-btn';

                // Action pour retirer l'option lorsqu'on clique sur le bouton
                removeBtn.addEventListener('click', function () {
                    sectionAccessibilite.removeChild(accessDiv);
                });

                // Ajouter le texte et le bouton au conteneur de l'option
                accessDiv.appendChild(accessText);
                accessDiv.appendChild(removeBtn);

                // Style des "tag" pour le handicap
                accessDiv.style.display = 'inline-block';
                accessDiv.style.boxShadow = 'none';
                accessDiv.style.margin = '5px ';
                accessDiv.style.padding = '5px 15px';
                accessDiv.style.height = '30px';
                accessDiv.style.borderRadius = '20px';
                accessDiv.style.fontSize = '0.9em';
                accessDiv.style.backgroundColor="#d1ccdc";

                // Ajouter une animation pour l'apparition
                accessDiv.style.opacity = '0';
                accessDiv.style.transform = 'scale(0.95)';
                sectionAccessibilite.appendChild(accessDiv);
                setTimeout(() => {
                    accessDiv.style.opacity = '1';
                    accessDiv.style.transform = 'scale(1)';
                }, 10); // Transition après ajout
            }
        }

        // Réinitialiser le select après ajout
        this.value = 'SelectionAccess';
    });



    // Pour l'accessibilité 


    // Pour les langues 
    // Récupération des éléments nécessaires
    const selectLangue = document.getElementById('selectionLangue');
    const sectionLangue = document.getElementById('sectionLangue');

    // Écouteur d'événement pour détecter un changement dans le select
    selectLangue.addEventListener('change', function () {
        const selectedValue = this.value; // Récupère la valeur sélectionnée

        // Vérifier si une langue a été sélectionnée
        if (selectedValue !== 'selectionLangue') {
            // Vérifier si la langue est déjà ajoutée
            if (document.getElementById(`lang-${selectedValue}`)) {
                alert(`La langue "${selectedValue}" est déjà ajoutée !`);
            } else {
                // Créer un conteneur pour la langue sélectionnée
                const langDiv = document.createElement('div');
                langDiv.className = 'lang-item';
                langDiv.id = `lang-${selectedValue}`; // ID unique pour éviter les doublons

                // Ajouter le nom de la langue dans un élément stylisé
                const langText = document.createElement('span');
                langText.textContent = selectedValue;
                langText.className = 'lang-text';

                // Ajouter un bouton de suppression avec une icône
                let removeBtn = document.createElement("img");
                removeBtn.setAttribute("src", "../../img/icone/croix.png");
                removeBtn.className = 'remove-btn';
                
                //Style de l'image
                removeBtn.style.width='12px';
                removeBtn.style.height='18px';
                removeBtn.style.margin='auto 0 auto 5px'
                // Pour supprimer le tag 
                removeBtn.addEventListener('click', function () {
                sectionLangue.removeChild(langDiv);
                });

                
                langDiv.appendChild(langText);
                langDiv.appendChild(removeBtn);

                // Appliquer un style visuel au conteneur

                langDiv.style.display = 'inline-block';
                langDiv.style.boxShadow = 'none';
                langDiv.style.margin = '5px ';
                langDiv.style.padding = '5px 15px';
                langDiv.style.height = '30px';
                langDiv.style.borderRadius = '20px';
                langDiv.style.fontSize = '0.9em';
                langDiv.style.backgroundColor="#d1ccdc";
                // Ajouter une animation pour l'apparition
                langDiv.style.opacity = '0';
                langDiv.style.transform = 'scale(0.95)';
                sectionLangue.appendChild(langDiv);
                setTimeout(() => {
                    langDiv.style.opacity = '1';
                    langDiv.style.transform = 'scale(1)';
                }, 10); // Transition après ajout
            }
        }

        // Réinitialiser le select après ajout
        this.value = 'selectionLangue';
    });




</script>