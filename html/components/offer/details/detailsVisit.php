<!-- Visite -->

<section id="visit">
    <div>
        <label>Durée :</label>
        <br>


        <input type="number" id="numberHVisit" name="numberHVisit" placeholder="0" />
        <label>H</label>

    </div>
    <div>
        <label>Résumé</label>
        <br>
        
        <textarea name="texteResumeVisit" id="texteResumeVisit" placeholder="Résumé de la visite"></textarea>
    </div>

    <div>
        <label>Accessibilité</label>
        <br>


        <input type="checkbox" id="radioButtonAccesPmrRequis" name="radioButtonAccesPmrRequis">
        <!--il faut trouver le type du radio button -->
        <br>
        <input type="checkbox" id="radioButtonAccesPmrRequis" name="radioButtonAccesPmrRequis">
        <!--il faut trouver le type du radio button -->
        <?php echo "radio button test" ?>
        <br>
        
        <input type="radio" id="radioButtonAccesPmrRequis" name="radioButtonAccesPmrRequis" value="radioButtonAccesPmrRequis" >
        <label for="radioButtonAccesPmrRequis"> toto</label>
        <br>    
    </div>

    <div>
        <label>Langue proposée(s) :</label>
        <br>
        <textarea name="texteLangueVisit" id="texteLangueVisit" placeholder="Langue proposée" ></textarea>
        
    </div>

    
</section>