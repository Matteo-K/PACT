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
        <label for="show_nbPlace">Nombre de places : </label> <!-- Label nombre de place -->
        <div>
            <input type="number" id="show_nbPlace" name="show_nbPlace" min="0" placeholder="0" value="<?php echo $spectacle["nbplace"] ?>">
            <!-- Pour le nombre de place -->
            <label for="show_nbPlace">places</label>
        </div>
        <!-- Gestion du prix -->
        <label for="show_prixMin">Prix minimum : </label>
        <div>
            <input type="number" id="show_prixMin" name="show_prixMin" min="0" placeholder="0" value="<?php echo $spectacle["prixminimal"] ?>">
            <!-- Pour entrer un prix minimum -->
            <label for="show_prixMin">€</label>
        </div>
    </div>

    <div>
    <!-- Gestion de la durée -->
        <label for="show_hrMin" class="ligne1">Durée du Spectacle : </label>
        <input type="number" style="display : none;" id="show_min" name="show_min" placeholder="0" value="<?php echo $spectacle["duree"] ?>">
        
        <input type="time" id="show_hrMin" name="show_hrMin" placeholder="0">
    </div>

</section>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const minutesInput = document.getElementById("nbMin");
    const hoursInput = document.getElementById("nbMinutesHeure");

    // Synchroniser les minutes avec les heures
    minutesInput.addEventListener("input", function () {
        const totalMinutes = parseInt(minutesInput.value) || 0;
        const hours = Math.floor(totalMinutes / 60);
        const minutes = totalMinutes % 60;

        hoursInput.value = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}`;
    });

    // Synchroniser les heures avec les minutes
    hoursInput.addEventListener("input", function () {
        const timeParts = hoursInput.value.split(":");
        const hours = parseInt(timeParts[0]) || 0; // Récupérer les heures
        const minutes = parseInt(timeParts[1]) || 0; // Si minutes non saisies, elles valent 0

        const totalMinutes = hours * 60 + minutes;

        minutesInput.value = totalMinutes;
    });

    // Mettre les minutes à 0 si seule l'heure est saisie
    hoursInput.addEventListener("blur", function () {
        if (hoursInput.value && !hoursInput.value.includes(":")) {
            // Si aucune minute n'est saisie, ajouter ":00"
            hoursInput.value = `${String(hoursInput.value).padStart(2, '0')}:00`;
        }
    });
});
</script>