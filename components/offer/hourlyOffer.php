<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horaires</title>
</head>
<body>
    <?php echo "Lundi"; ?>
    <input type="checkbox" id="Lundi" name="Lundi"  />
    <?php 
        echo "Fermé";

        echo "Ouvert de : "
    ?>
    
    <input type="time" name="horairesOMatLundi" id="horairesOMatLundi" >
        <?php echo "à" ?>
    <input type="time" name="horairesFMatLundi" id="horairesFMatLundi">

    
    
</body>
</html>