<form action="" id="hourlyOffer">
<!-- Lundi -->
<?php echo "Lundi"; ?>
<input type="checkbox" id="checkLundi" name="checkLundi"  />
<?php 
    echo "Fermé";

    echo "Ouvert de : "
?>

<input type="time" name="horairesOuv1Lundi" id="horairesOuv1Lundi" >
    <?php echo "à" ?>
<input type="time" name="horairesF1Lundi" id="horairesF1Lundi">

<input type="button" value="Ajouter un horaire">

<!-- Mardi -->
<?php echo "Mardi"; ?>
<input type="checkbox" id="checkMardi" name="checkMardi"  />
<?php 
    echo "Fermé";

    echo "Ouvert de : "
?>
<input type="time" name="horairesOuv1Mardi" id="horairesOuv1Mardi" >
    <?php echo "à" ?>
<input type="time" name="horairesF1Mardi" id="horairesF1Mardi">

<?php echo "et de " ?>
<input type="time" name="horairesOuv2Mardi" id="horairesOuv2Mardi" >
    <?php echo "à" ?>
    <input type="time" name="horairesF2Mardi" id="horairesFMardi" >

    
    </form>



<!--


-->