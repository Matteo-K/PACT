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


    <div class="access">
        <label id="labAccess">Accessibilité</label> <!-- Label Accessibilité -->


        <div class="acces1">
            <input type="radio"  name="AccesH1" value="Acces" checked>

            <label for="Acces">Accès Personne à Modibilté Réduite</label>
            <!-- Label associé au bouton radio -->
        </div>
        <div class="access1">
            <input type="radio"  name="AccesH2" value="pasAcces">
            <label for="pasAcces">Accès personne sourde/malentendantes </label>
            <!-- Label du 2eme bouton radio -->
        </div>
        <div class="access1">
            <input type="radio"  name="AccesH3" value="pasAcces">
            
            <label for="pasAcces">Accès personnes aveugle/déficience visuelle </label>
            <!-- Label du 3eme bouton radio -->
        </div>


    <div class="divPrixMin">
        <label>Prix minimum</label>


        <input type="number" id="PrixMinVisit" name="PrixMinVisit" min="0" placeholder="0">
        <label class="labEuro">€</label>
    </div>


    <div class="languesProp">
        <label>Langue proposée(s) :</label> <!-- Label langue proposée -->
        <label>Sélectionner les langue(s) proposée(s) par votre visite."</label>


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


<!-- Script Js de activity -->
<script>
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
                
                const removeBtn = document.createElement('button');
                removeBtn.innerHTML = '<span style="font-size: 1.2em; color: #d32f2f;">&times;</span>'; // Symbole "×" stylisé
                removeBtn.className = 'remove-btn';

                // Action pour retirer la langue lorsqu'on clique sur le bouton
                removeBtn.addEventListener('click', function () {
                    sectionLangue.removeChild(langDiv);
                });

                // Ajouter le texte et le bouton au conteneur de langue
                langDiv.appendChild(langText);
                langDiv.appendChild(removeBtn);

                // Appliquer un style visuel au conteneur
                
                langDiv.style.display = 'inline-flex';
                langDiv.style.alignItems = 'center';
                langDiv.style.margin = '5px';
                langDiv.style.padding = '8px 12px';
                langDiv.style.backgroundColor = '#c8e6c9'; // Couleur similaire pour les tags ajoutés
                langDiv.style.border = '1px solid #66bb6a';
                langDiv.style.borderRadius = '20px';
                langDiv.style.fontSize = '0.9em';
                langDiv.style.color = '#2e7d32'; // Texte de même couleur que les tags ajoutés
                langDiv.style.transition = 'opacity 0.3s ease, transform 0.3s ease';

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