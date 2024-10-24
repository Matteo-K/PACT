<!-- Activité -->
<section id="activity">
    <article> <!-- Article pour le placement des elements de gauche-->
        <p>
            <label class="labDuréeAct" name="labDuréeAct"> Durée: </label>
            <br>
            <textarea name="duréeAct" class="txtAct" placeholder="0"></textarea>
            <label class="labHAct" name="labHAct"> H </label>
        </p>

        <p>
            <label class="labAgeAct" name="labAgeAct"> Age: </label>
            <textarea name="AgeAct" class="txtAct" placeholder="Age"></textarea>
            <label class="labAnsAct" name="labAgeAct"> Ans </label>
        </p>

        <div>
            <label>Accessibilité</label> <!-- Label Accessibilité -->
            <br>


            <input type="radio" id="radioButtonAccesPmrRequis" name="Accessibilité" value="Accès personne handicapées"
                checked>
            <!-- Bouton radio pour le choix de l'accesibilite PMR il est lier avec le 2eme et est selectionner par defaut -->
            <label for="radioButtonAccesPmrRequis"> Accès personne handicapées</label>
            <!-- Label associé au bouton radio -->
            <br>
            <input type="radio" id="pasAcces" name="Accessibilité"
                value="Pas d’accès personne handicapées"> <!-- 2eme bouton radio liés au 1er via l'id -->
            <label for="pasAcces"> Pas d’accès personne handicapées </label>
            <!-- Label du 2eme bouton radio -->
            <br>
        </div>



    </article>

</section>