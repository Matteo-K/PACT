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
  if ($result !== false) {
    $activite["duree"] = $result["duree"];
    $activite["agemin"] = $result["agemin"];
    $activite["prixminimal"] = $result["prixminimal"];
  }
}
// Il reste à initialisé les valeurs dans les input
?>

<section id="activity">
    <article> <!-- Article pour le placement des elements de gauche-->
        <p>
            <label class="labDuréeAct" name="labDuréeAct"> Durée: </label>
            <input type="number" id="numberAct" name="duréeAct" placeholder="0" />
            <label class="labHAct" name="labHAct"> H </label>
            <br>
        </p>

        <p>
            <label class="labAgeAct" name="labAgeAct"> Age: </label>
            <input type="number" id="numberAct" name="ageAct" placeholder="1" />
            <label class="labAnsAct" name="labAgeAct"> Ans </label>
        </p>

        <div>
            <label id="labAccess">Accessibilité</label> <!-- Label Accessibilité -->
            <br>


            <input type="radio" id="Acces" name="Accessibilite" value="Acces" checked>
            <!-- Bouton radio pour le choix de l'accesibilite PMR il est lier avec le 2eme et est selectionner par defaut -->
            <label for="Acces"> Accès personne handicapées</label>
            <!-- Label associé au bouton radio -->
            <br>
            <input type="radio" id="pasAcces" name="Accessibilite" value="pasAcces">
            <!-- 2eme bouton radio liés au 1er via l'id -->
            <label for="pasAcces"> Pas d’accès personne handicapées </label>
            <!-- Label du 2eme bouton radio -->
            <br>
        </div>
    </article>

    <article>
        <p>
            <label>Prestation(s)</label>
            
            <textarea name="textPrestationsAct" id="textePrestation" placeholder="Entrer une prestation "></textarea>
            <input type="button" id="buttonAjoutPresta" name="BtnAjoutPresta" value="Ajouter des presations">
            <br>

        </p>
        <p>
            <label>Prestation(s) non proposée(s)</label>
            <textarea name="textPrestationsNPAct" id="textePrestation" placeholder="Entrer une prestation "></textarea>
            <input type="button" id="buttonAjoutPrestaNp" name="BtnAjoutPrestaNp"
                value="Ajouter des presation(s) non proposée(s)">

        </p>
        


    </article>

</section>