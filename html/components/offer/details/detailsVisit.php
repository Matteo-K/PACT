<!-- Visite -->

<section id="visit"> <!-- donne un id a la section pour l'identifier dans le css -->
    <article id="ArtVisit"> <!-- separation en article pour l'alignement -->
        <div>
            <label>Durée :</label> <!-- Label durée -->
            <br>


            <input type="number" id="numberHVisit" name="numberHVisit" placeholder="0" />
            <!-- zone de texte ou seul un chiffre/nombre est accepte -->
            <label>H</label> <!-- Label H (pour heure) -->
            
        </div>
        <div>
            <label>Accessibilité</label> <!-- Label Accessibilité -->
            <br>


            <input type="radio" id="access" name="Accessibilité" value="access"
                checked>
            <!-- Bouton radio pour le choix de l'accesibilite PMR il est lier avec le 2eme et est selectionner par defaut -->
            <label for="access"> Accès personne handicapées</label>
            <!-- Label associé au bouton radio -->
            <br>
            <input type="radio" id="pasAcces" name="Accessibilité"
                value="pasAcces"> <!-- 2eme bouton radio liés au 1er via l'id -->
            <label for="pasAcces"> Pas d’accès personne handicapées </label>
            <!-- Label du 2eme bouton radio -->
            <br>
        </div>

        <div>
            <label>Langue proposée(s) :</label> <!-- Label langue proposée -->
            <br>
            <textarea name="texteLangueVisit" id="texteLangueVisit"
                placeholder="Entrer les langues proposées pour la visite"></textarea>
            <!-- Zone de texte avec les langues de la visite avec un affichage (griser) par default-->

        </div>

    </article>
</section>