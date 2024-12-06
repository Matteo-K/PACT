<!-- Activité -->
<?php
// Initialisation des données à vide
$activite = [
    "duree" => "",
    "agemin" => "",
    "prixminimal" => "",
    "accessibilite" => true,
    "nomAccess" => [],
];
$accessibilite = [];

// Une activité peut avoir plusieurs prestations (Voir avec BDD)
// Si l'offre n'existe pas 
$stmt = $conn->prepare("SELECT * from pact._accessibilite");
$stmt->execute();
while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $accessibilite[] = $result["nomaccess"];
}


// Si l'activité était déà existante, on récupère les données
if ($categorie["_activite"]) {
    $stmt = $conn->prepare("SELECT * from pact._activite where idoffre=?");
    $stmt->execute([$idOffre]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        $activite["duree"] = $result["duree"];
        $activite["agemin"] = $result["agemin"];
        $activite["prixminimal"] = $result["prixminimal"];
    }

    // Accessibilité
    $stmt = $conn->prepare("SELECT * from pact._offreAccess where idoffre=?");
    $stmt->execute([$idOffre]);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $visite["nomAccess"][] = $row["nomAccess"];
    }
}

?>

<section id="activity">

    <div class="divAge"> <!-- Zone pour l'age minimum pour l'activité -->
        <label class="labAgeAct" name="labAgeAct"> Age: </label>
        <input type="number" id="numberAct" name="ageAct" min="0" placeholder="0" />
        <label class="labAnsAct" name="labAgeAct"> Ans </label>
    </div>
    <!-- Gestion des prestations proposée dans l'activité -->

    <!-- Faire une select depuis la BDD--> <!------------------------------------------------------------------!-->
    <div class="presta">
        <label>Prestation(s)</label>

        <textarea name="textPrestationsAct" id="textePrestation" placeholder="Entrer une prestation "></textarea>

        <input type="button" id="buttonAjoutPresta" name="BtnAjoutPresta" value="Ajouter des presations">


    </div>


    <!-- Gestion des prix de l'activité -->
    <div class="divP">
        <label>Prix minimum</label>

        <div class="divP1">
            <input type="number" id="PrixMinAct" name="PrixMinAct" min="0" placeholder="0">
            <label>€</label>
        </div>
    </div>
    <!-- Partie accéssibilité à modifiier avec la bdd a jour -->
    <div class="access">
        <label id="labAccess">Accessibilité</label> <!-- Label Accessibilité -->


        <div class="acces1">
            <!-- Gestion de l'accessibilité (handicap ) depuis la BDD -->
            <div class="access">
                <select name="nomAccess" id="nomAccess">
                    <option value="SelectionAccess">-- Sélectionner un handicap --</option>
                    <?php foreach ($accessibilite as $key => $value) { ?>
                        <option value="<?php echo $value ?>"><?php echo $value ?></option>
                    <?php } ?>
                </select>
            </div>



            <!-- Gestion de la durée de l'activité -->
            <div class="divD">
                <label class="labDuréeAct" name="labDuréeAct"> Durée: </label>
                <div class="divD1">
                    <input type="number" id="numberAct" name="duréeAct" placeholder="0" />
                    <label class="labHAct" name="labHAct"> H </label>
                </div>

            </div>

</section>


<script>
    // Gestion de l'accésibilité
    // Récupération des éléments nécessaires
    const selectAccessibilite = document.getElementById('nomAccess');
    const sectionAccessibilite = document.createElement('div');
    sectionAccessibilite.id = 'sectionAccessibilite';
    sectionAccessibilite.style.marginTop = '10px';
    selectAccessibilite.parentNode.appendChild(sectionAccessibilite);


    selectAccessibilite.addEventListener('change', function () {
        const selectedValue = this.value; // Récupère la valeur sélectionnée


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
                let removeBtn = document.createElement("img");
                removeBtn.setAttribute("src", "../../img/icone/croix.png");
                removeBtn.className = 'remove-btn';


                //Style de l'image
                removeBtn.style.width = '12px';
                removeBtn.style.height = '12px';
                removeBtn.style.alignItems = 'center';
                removeBtn.style.margin = 'auto 0 auto 5px'

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
                accessDiv.style.backgroundColor = "#d1ccdc";

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

</script>