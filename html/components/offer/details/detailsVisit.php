<!-- Visite -->
<?php
$langue = [];
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
    // Langue
    $stmt = $conn->prepare("SELECT langue from pact._visite_langue where idoffre=?");
    $stmt->execute([$idOffre]);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $visite["langue"][] = $row["langue"];
    }



    // Accessibilité
    $stmt = $conn->prepare("SELECT * from pact._accessibilite where idoffre=?");
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
            <?php foreach ($nomAccess as $key => $value) { ?>
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



/* ----------------------- ACCESSIBILITE ------------------------------*/
// Script d'accessibilité pour améliorer l'expérience utilisateur

document.addEventListener("DOMContentLoaded", () => {
    // Ajouter un bouton pour augmenter/diminuer la taille du texte
    const controls = document.createElement("div");
    controls.id = "accessibility-controls";
    controls.innerHTML = `
        <button id="increase-font">A+</button>
        <button id="decrease-font">A-</button>
        <button id="reset-font">A</button>
    `;
    document.body.prepend(controls);

    const root = document.documentElement;
    let fontSize = parseFloat(window.getComputedStyle(root).fontSize);

    document.getElementById("increase-font").addEventListener("click", () => {
        fontSize += 1;
        root.style.fontSize = fontSize + "px";
    });

    document.getElementById("decrease-font").addEventListener("click", () => {
        fontSize = Math.max(10, fontSize - 1); // Empêche de descendre en dessous de 10px
        root.style.fontSize = fontSize + "px";
    });

    document.getElementById("reset-font").addEventListener("click", () => {
        fontSize = 16; // Valeur par défaut
        root.style.fontSize = fontSize + "px";
    });

    // Ajouter des rôles ARIA dynamiquement pour améliorer l'accessibilité
    const mainContent = document.querySelector("main");
    if (mainContent) {
        mainContent.setAttribute("role", "main");
    }

    const nav = document.querySelector("nav");
    if (nav) {
        nav.setAttribute("role", "navigation");
    }

    const footer = document.querySelector("footer");
    if (footer) {
        footer.setAttribute("role", "contentinfo");
    }

    // Ajout d'une gestion des touches pour la navigation clavier
    document.addEventListener("keydown", (event) => {
        if (event.key === "Tab") {
            document.body.classList.add("user-is-tabbing");
        }
    });

    // Supprimer les styles de navigation clavier si la souris est utilisée
    document.addEventListener("mousedown", () => {
        document.body.classList.remove("user-is-tabbing");
    });
});


</script>