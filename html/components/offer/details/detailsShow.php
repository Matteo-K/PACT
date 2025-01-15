<!-- Spectacle -->
<?php
// Initialisation des données à vide
$spectacle = [
    "duree" => "",
    "nbplace" => "",
    "prixminimal" => ""
];

// Si le spectacle était déjà existant, on récupère les données
if ($categorie["_spectacle"]) {
    $stmt = $conn->prepare("SELECT * from pact._spectacle where idoffre=?");
    $stmt->execute([$idOffre]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $spectacle["duree"] = $result["duree"];
        $spectacle["nbplace"] = $result["nbplace"];
        $spectacle["prixminimal"] = $result["prixminimal"];
    }
}

?>
<section id="show"> 
    <!-- Gestion du nombre de place pour le parc d'attraction -->
    <div id="show_nbPlacePrixMin">
        <!-- Gestion nombre d'attraction -->
        <div>
            <label class="labelTitre" for="show_nbPlace">Nombre de places*</label> <!-- Label nombre de place -->
            <div>
                <input type="number" id="show_nbPlace" name="show_nbPlace" min="0" placeholder="0" value="<?php echo $spectacle["nbplace"] ?>">
                <!-- Pour le nombre de place -->
                <label for="show_nbPlace">places</label>
            </div>
        </div>
        <!-- Gestion du prix -->
         <div>
            <label class="labelTitre" for="show_prixMin">Prix minimum*</label>
            <div>
                <input type="number" id="show_prixMin" name="show_prixMin" min="0" placeholder="0" value="<?php echo $spectacle["prixminimal"] ?>">
                <!-- Pour entrer un prix minimum -->
                <label for="show_prixMin">€</label>
            </div>
        </div>
        <!-- Gestion de la durée -->
        <div>
            <label for="show_hrMin" class="labelTitre">Durée du Spectacle*</label>
            <div>
                <input type="hidden" id="show_min" name="show_min" placeholder="0" value="<?php echo $spectacle["duree"] ?>">
                <input type="time" id="show_hrMin" name="show_hrMin" placeholder="0">
            </div>
        </div>
    </div>


</section>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const show_minutesInput = document.getElementById("show_min");
        const show_hoursInput = document.getElementById("show_hrMin");
        
        show_minutesInput.addEventListener("change", () => minutesToHours(show_minutesInput, show_hoursInput));
        show_hoursInput.addEventListener("change", () => hoursToMinutes(show_minutesInput, show_hoursInput));

        minutesToHours(show_minutesInput, show_hoursInput);
    });
</script>