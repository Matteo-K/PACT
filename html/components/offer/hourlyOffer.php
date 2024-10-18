<form action="" id="hourlyOffer">
    <!-- Lundi -->
    <div>
        <div>
            <p>Lundi :</p>
        </div>
        <div>
            <input type="checkbox" id="checkLundi" name="checkLundi" />

            <p> Ferme</p>
            <p> Ouvert de</p>

            <input type="time" name="horairesOuv1Lundi" id="horairesOuv1Lundi">
            <!-- Zone de texte de type time pour saisir uniquement des heures -->
            <!-- <?php echo "à" ?> -->
            <p>à</p>
            <input type="time" name="horairesF1Lundi" id="horairesF1Lundi">
            <!-- Zone de texte de type time pour saisir uniquement des heures -->

            <input type="button" value="Ajouter un horaire" name="btnAjoutLundi" id="btnAjoutLundi">

            <!-- Partie avec les horaire de "l'après midi du Lundi-->
            <p> et de </p>
            <input type="time" name="horairesOuv2Lundi" id="horairesOuv2Lundi" >
            <!-- Zone de texte de type time pour saisir uniquement des heures -->
            <p>à</p>
            <input type="time" name="horairesF2Lundi" id="horairesF2Lundi">
            <!-- Zone de texte de type time pour saisir uniquement des heures -->
            <div><input type="button" value="Retirer" name="btnRetirerLundi" id="btnRetirerLundi"></div>
            <!-- le bouton pour retirer les horaires -->
        </div>
    </div>
    
    <!-- Mardi -->
    <div>
        <div>
            <p>Mardi :</p>
        </div>
        <div>
            <input type="checkbox" id="checkMardi" name="checkMardi" />
            <p> Ferme</p>
            <p> Ouvert de</p>
            <input type="time" name="horairesOuv1Mardi" id="horairesOuv1Mardi">
            <p>à</p>
            <input type="time" name="horairesF1Mardi" id="horairesF1Mardi">
            <input type="button" value="Ajouter un horaire" name="btnAjoutMardi" id="btnAjoutMardi">

            <!-- Partie avec les horaires de l'après midi du mardi -->
            <p>et de</p>
            <input type="time" name="horairesOuv2Mardi" id="horairesOuv2Mardi">
            <p>à</p>
            <input type="time" name="horairesF2Mardi" id="horairesFMardi">
            <div><input type="button" value="Retirer" name="btnRetirer" id="btnRetirer"></div>
        </div>
    </div>


    <!-- Mercredi -->

    <div>

        <div>
            <p>Mercredi :</p>
        </div>
        <div>
            <input type="checkbox" id="checkMercredi" name="checkMercredi" />
            <p> Ferme</p>
            <p> Ouvert de</p>

            <input type="time" name="horairesOuv1Mercredi" id="horairesOuv1Mercredi">
            <p>à</p>
            <input type="time" name="horairesF1Mercredi" id="horairesF1Mercredi">

            <input type="button" value="Ajouter un horaire" name="btnAjoutMercredi" id="btnAjoutMercredi">

            <!-- Partie avec les horaire de "l'après midi du Mercredi-->
            <p> et de </p>
            <input type="time" name="horairesOuv2Mercredi" id="horairesOuv2Mercredi">
            <p>à</p>
            <input type="time" name="horairesF2Mercredi" id="horairesF2Mercredi">
            <div><input type="button" value="Retirer" name="btnRetirerMercredi" id="btnRetirerMercredi"></div>
        </div>
    </div>

    <!-- Jeudi -->

    <div>

        <div>
            <p>Jeudi :</p>
        </div>
        <div>
            <input type="checkbox" id="checkJeudi" name="checkJeudi" />
            <p> Ferme</p>
            <p> Ouvert de</p>

            <input type="time" name="horairesOuv1Jeudi" id="horairesOuv1Jeudi">
            <p>à</p>
            <input type="time" name="horairesF1Jeudi" id="horairesF1Jeudi">

            <input type="button" value="Ajouter un horaire" name="btnAjoutJeudi" id="btnAjoutJeudi">

            <!-- Partie avec les horaire de "l'après midi du Jeudi-->
            <p> et de </p>
            <input type="time" name="horairesOuv2Jeudi" id="horairesOuv2Jeudi">
            <p>à</p>
            <input type="time" name="horairesF2Jeudi" id="horairesF2Jeudi">
            <div><input type="button" value="Retirer" name="btnRetirerJeudi" id="btnRetirerJeudi"> </div>
        </div>

    </div>

    <!-- Vendredi -->

    <div>

        <div>
            <p>Vendredi :</p>
        </div>
        <div>
            <input type="checkbox" id="checkVendredi" name="checkVendredi" />
            <p> Ferme</p>
            <p> Ouvert de</p>

            <input type="time" name="horairesOuv1Vendredi" id="horairesOuv1Vendredi">
            <p>à</p>
            <input type="time" name="horairesF1Vendredi" id="horairesF1Vendredi">

            <input type="button" value="Ajouter un horaire" name="btnAjoutVendredi" id="btnAjoutVendredi">

            <!-- Partie avec les horaire de "l'après midi du Vendredi-->
            <p> et de </p>
            <input type="time" name="horairesOuv2Vendredi" id="horairesOuv2Vendredi">
            <p>à</p>
            <input type="time" name="horairesF2Vendredi" id="horairesF2Vendredi">
            <div><input type="button" value="Retirer" name="btnRetirerVendredi" id="btnRetirerVendredi"> </div>
        </div>
    </div>

    <!-- Samedi -->
    <div>

        <div>
            <p>Samedi :</p>
        </div>
        <div>
            <input type="checkbox" id="checkSamedi" name="checkSamedi" />

            <p> Ferme</p>
            <p> Ouvert de</p>

            <input type="time" name="horairesOuv1Samedi" id="horairesOuv1Samedi">
            <?php echo "à" ?>
            <input type="time" name="horairesF1Samedi" id="horairesF1Samedi">

            <input type="button" value="Ajouter un horaire" name="btnAjoutSamedi" id="btnAjoutSamedi">

            <!-- Partie avec les horaire de "l'après midi du Samedi-->
            <p> et de </p>
            <input type="time" name="horairesOuv2Samedi" id="horairesOuv2Samedi">
            <p>à</p>
            <input type="time" name="horairesF2Samedi" id="horairesF2Samedi">
            <div><input type="button" value="Retirer" name="btnRetirerSamedi" id="btnRetirerSamedi"> </div>
        </div>
    </div>

    <!-- Dimanche -->
    <div>

        <div>
            <p>Dimanche :</p>
        </div>
        <div>
            <input type="checkbox" id="checkDimanche" name="checkDimanche" />
            <p> Ferme</p>
            <p> Ouvert de</p>

            <input type="time" name="horairesOuv1Dimanche" id="horairesOuv1Dimanche">
            <p>à</p>
            <input type="time" name="horairesF1Dimanche" id="horairesF1Dimanche">

            <input type="button" value="Ajouter un horaire" name="btnAjoutDimanche" id="btnAjoutDimanche">

            <!-- Partie avec les horaire de "l'après midi du Dimanche-->
            <p> et de </p>
            <input type="time" name="horairesOuv2Dimanche" id="horairesOuv2Dimanche">
            <p>à</p>
            <input type="time" name="horairesF2Dimanche" id="horairesF2Dimanche">
            <div><input type="button" value="Retirer" name="btnRetirerDimanche" id="btnRetirerDimanche"> </div>
        </div>
    </div>



</form>
<!--


-->