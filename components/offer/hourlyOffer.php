<form action="" id="hourlyOffer">
<!-- Lundi -->
<?php echo "Lundi"; ?>
        <input type="checkbox" id="checkLundi" name="checkLundi" />
<?php 
    echo "Fermé";

    echo "Ouvert de : "
?>

        <input type="time" name="horairesOuv1Lundi" id="horairesOuv1Lundi">
    <?php echo "à" ?>
<input type="time" name="horairesF1Lundi" id="horairesF1Lundi">

        <input type="button" value="Ajouter un horaire" name="AjoutLundi" id="AjoutLundi">
    </div>
<!-- Mardi -->
    <div>
<?php echo "Mardi"; ?>
        <input type="checkbox" id="checkMardi" name="checkMardi" />
<?php 
    echo "Fermé";

    echo "Ouvert de : "
?>
        <input type="time" name="horairesOuv1Mardi" id="horairesOuv1Mardi">
    <?php echo "à" ?>
<input type="time" name="horairesF1Mardi" id="horairesF1Mardi">

<?php echo "et de " ?>
        <input type="time" name="horairesOuv2Mardi" id="horairesOuv2Mardi">
    <?php echo "à" ?>
        <input type="time" name="horairesF2Mardi" id="horairesFMardi">
        <input type="button" value="Retirer" name="Retirer" id="Retirer">

    </div>

    
    <!-- Mercredi -->

    <div>
        
        <?php echo "Mercredi"; ?>
        <input type="checkbox" id="checkMercredi" name="checkMercredi" />
        <?php
        echo "Fermé";

        echo "Ouvert de : "
            ?>

        <input type="time" name="horairesOuv1Mercredi" id="horairesOuv1Mercredi">
        <?php echo "à" ?>
        <input type="time" name="horairesF1Mercredi" id="horairesF1Mercredi">

        <input type="button" value="Ajouter un horaire" name="AjoutMercredi" id="AjoutMercredi">
        
    </div>

    <!-- Jeudi -->

    <div>
        
        <?php echo "Jeudi"; ?>
        <input type="checkbox" id="checkJeudi" name="checkJeudi" />
        <?php
        echo "Fermé";

        echo "Ouvert de : "
            ?>

        <input type="time" name="horairesOuv1Jeudi" id="horairesOuv1Jeudi">
        <?php echo "à" ?>
        <input type="time" name="horairesF1Jeudi" id="horairesF1Jeudi">

        <input type="button" value="Ajouter un horaire" name="AjoutJeudi" id="AjoutJeudi">
        
    </div>

        <!-- Vendredi -->

        <div>
        
        <?php echo "Vendredi"; ?>
        <input type="checkbox" id="checkVendredi" name="checkVendredi" />
        <?php
        echo "Fermé";

        echo "Ouvert de : "
            ?>

        <input type="time" name="horairesOuv1Vendredi" id="horairesOuv1Vendredi">
        <?php echo "à" ?>
        <input type="time" name="horairesF1Vendredi" id="horairesF1Vendredi">

        <input type="button" value="Ajouter un horaire" name="AjoutVendredi" id="AjoutVendredi">
        
    </div>
        <!-- Samedi -->

        <div>
        
        <?php echo "Samedi"; ?>
        <input type="checkbox" id="checkSamedi" name="checkSamedi" />
        <?php
        echo "Fermé";

        echo "Ouvert de : "
            ?>

        <input type="time" name="horairesOuv1Samedi" id="horairesOuv1Samedi">
        <?php echo "à" ?>
        <input type="time" name="horairesF1Samedi" id="horairesF1Samedi">

        <input type="button" value="Ajouter un horaire" name="AjoutSamedi" id="AjoutSamedi">
        
    </div>

            <!-- Dimanche -->

            <div>
        
        <?php echo "Dimanche"; ?>
        <input type="checkbox" id="checkDimanche" name="checkDimanche" />
        <?php
        echo "Fermé";

        echo "Ouvert de : "
            ?>

        <input type="time" name="horairesOuv1Dimanche" id="horairesOuv1Dimanche">
        <?php echo "à" ?>
        <input type="time" name="horairesF1Dimanche" id="horairesF1Dimanche">

        <input type="button" value="Ajouter un horaire" name="AjoutDimanche" id="AjoutDimanche">
        
    </div>
</form>
<!--


-->