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
    <div>
        <label class="labelTitre" for="show_nbPlace">Nombre de places : </label> <!-- Label nombre de place -->
        <div>
            <input type="number" id="show_nbPlace" name="show_nbPlace" min="0" placeholder="0" value="<?php echo $spectacle["nbplace"] ?>">
            <!-- Pour le nombre de place -->
            <label for="show_nbPlace">places</label>
        </div>
        <!-- Gestion du prix -->
        <label class="labelTitre" for="show_prixMin">Prix minimum : </label>
        <div>
            <input type="number" id="show_prixMin" name="show_prixMin" min="0" placeholder="0" value="<?php echo $spectacle["prixminimal"] ?>">
            <!-- Pour entrer un prix minimum -->
            <label for="show_prixMin">€</label>
        </div>
    </div>

    <div>
    <!-- Gestion de la durée -->
        <label for="show_hrMin" class="labelTitre">Durée du Spectacle : </label>
        <input type="number" style="display : none;" id="show_min" name="show_min" placeholder="0" value="<?php echo $spectacle["duree"] ?>">
        
        <input type="time" id="show_hrMin" name="show_hrMin" placeholder="0">
    </div>

</section>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const minutesInput = document.getElementById("show_min");
    const hoursInput = document.getElementById("show_hrMin");

    function minutesToHours() {
        const totalMinutes = parseInt(minutesInput.value) || 0;
        const hours = Math.floor(totalMinutes / 60);
        const minutes = totalMinutes % 60;

        hoursInput.value = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}`;
    }

    function hoursToMinutes() {
        const timeParts = hoursInput.value.split(":");
        const hours = parseInt(timeParts[0]) || 0; // Récupérer les heures
        const minutes = parseInt(timeParts[1]) || 0; // Si minutes non saisies, elles valent 0

        const totalMinutes = hours * 60 + minutes;

        minutesInput.value = totalMinutes;
    }

    // Synchroniser les minutes avec les heures
    minutesInput.addEventListener("change", () => minuteToHours());

    // Synchroniser les heures avec les minutes
    hoursInput.addEventListener("change", () => hoursToMinutes());

    minuteToHours();
});
</script>