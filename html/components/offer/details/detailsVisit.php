<!-- Visite -->
<?php
$langue = [];
$visite = [
    "guide" => true,
    "duree" => "",
    "prixminimal" => "",
    "accessibilite" => true,
    "hadicap" => [],
    "langue" => []
];

$stmt = $conn->prepare("SELECT * from pact._langue");
$stmt->execute();
while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $langue[] = $result["langue"];
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
        $visite["accessibilite"] = $result["accessibilite"];
    }

    $stmt = $conn->prepare("SELECT langue from pact._visite_langue where idoffre=?");
    $stmt->execute([$idOffre]);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $visite["langue"][] = $row["langue"];
    }

    // Ajouté une requête pour l'handicap
    /*
    $stmt = $conn->prepare("SELECT * from pact._handicap where idoffre=?");
    $stmt->execute([$idOffre]);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $visite["hadicap"][] = $row["hadicap"];
    }*/
}
// Il reste à initialisé les valeurs dans les input
?>
<section id="visit"> <!-- donne un id a la section pour l'identifier dans le css -->

    <div class="visGuideeEtDuree">
        <label>Visite Guidée </label>

        <input type="radio" id="guidee" name="VisiteGuidee" value="pasGuidee" checked>

        <label for="access"> Oui</label>


        <input type="radio" id="pasGuidee" name="VisiteGuidee" value="pasGuidee">

        <label for="pasAcces"> Non </label>


        <label class="labDureeVis">Durée :</label> <!-- Label durée -->

        <input type="number" id="numberHVisit" name="numberHVisit" min="0" placeholder="0" />
        <!-- zone de texte ou seul un chiffre/nombre est accepte -->
        <label class="labH">h</label> <!-- Label H (pour heure) -->

    </div>


    <div class="divAccessibliteVisit">
        <label>Accessibilité</label> <!-- Label Accessibilité -->

        <input type="radio" id="access" name="Accessibilité" value="access" checked>
        <!-- Bouton radio pour le choix de l'accesibilite PMR il est lier avec le 2eme et est selectionner par defaut -->
        <label for="access"> Accès personne handicapées</label>
        <!-- Label associé au bouton radio -->

        <input type="radio" id="pasAcces" name="Accessibilité" value="pasAcces">
        <!-- 2eme bouton radio liés au 1er via l'id -->
        <label for="pasAcces"> Pas d’accès personne handicapées </label>
        <!-- Label du 2eme bouton radio -->

    </div>


    <div class="divPrixMin">
        <label>Prix minimum</label>


        <input type="number" id="PrixMinVisit" name="PrixMinVisit" min="0" placeholder="0">
        <label class="labEuro">€</label>
    </div>


    <div class="languesProp">
        <label>Langue proposée(s) :</label> <!-- Label langue proposée -->
        <label>Entrez & selectionnez les langue proposée pour votre visite"</label>


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


<!-- Script Js -->
<script>
    // Récupération des éléments nécessaires
    const selectLangue = document.getElementById('selectionLangue');
    const sectionLangue = document.getElementById('sectionLangue');

    // Écouteur d'événement pour détecter un changement dans le select
    selectLangue.addEventListener('change', function () {
        const selectedValue = this.value; // Récupère la valeur sélectionnée

        // Vérifier si une langue a été sélectionnée et qu'elle n'est pas déjà ajoutée
        if (selectedValue !== 'selectionLangue' && !document.getElementById(`lang-${selectedValue}`)) {
            // Créer un conteneur pour la langue sélectionnée
            const langDiv = document.createElement('div');
            langDiv.className = 'lang-item';
            langDiv.id = `lang-${selectedValue}`; // ID unique pour éviter les doublons

            // Ajouter le nom de la langue
            langDiv.textContent = selectedValue;

            // Ajouter un bouton de suppression
            const removeBtn = document.createElement('button');
            removeBtn.textContent = 'X'; // Bouton pour retirer la langue
            removeBtn.className = 'remove-btn';

            // Action pour retirer la langue lorsqu'on clique sur le bouton
            removeBtn.addEventListener('click', function () {
                sectionLangue.removeChild(langDiv);
            });

            // Ajouter le bouton au conteneur de langue
            langDiv.appendChild(removeBtn);

            // Ajouter la langue au conteneur global
            sectionLangue.appendChild(langDiv);
        }

        // Réinitialiser le select après ajout
        this.value = 'selectionLangue';
    });
</script>