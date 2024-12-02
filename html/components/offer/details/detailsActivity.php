<!-- Activité -->
<?php
// Initialisation des données à vide
$activite = [
    "duree" => "",
    "agemin" => "",
    "prixminimal" => "",
];
// Une activité peut avoir plusieurs prestations (Voir avec BDD)

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
}
// Il reste à initialisé les valeurs dans les input
?>

<section id="activity">



    <div class="divAge">
        <label class="labAgeAct" name="labAgeAct"> Age: </label>
        <input type="number" id="numberAct" name="ageAct" min="0" placeholder="0" />
        <label class="labAnsAct" name="labAgeAct"> Ans </label>
    </div>
    <div class="presta">
        <label>Prestation(s)</label>

        <textarea name="textPrestationsAct" id="textePrestation" placeholder="Entrer une prestation "></textarea>

        <input type="button" id="buttonAjoutPresta" name="BtnAjoutPresta" value="Ajouter des presations">


    </div>



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
    </div>




    <div class="divD">
        <label class="labDuréeAct" name="labDuréeAct"> Durée: </label>
        <div class="divD1">
            <input type="number" id="numberAct" name="duréeAct" placeholder="0" />
            <label class="labHAct" name="labHAct"> H </label>
        </div>

    </div>

</section>