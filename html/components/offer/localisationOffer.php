  <form id="localisationOffer" action="enregOffer.php" method="post">
    <section class="map">
      <section class="sectionLoca">
        <div>
          <label for="Adresse">Adresse postale* :</label>
          <input type="text" id="adresse" placeholder="Adresse" required/>
        </div>
        <section>
          <div class="codeP">
            <label for="codepostal">Code postal* :</label>
            <input type="text" id="codepostal" placeholder="Code postal" required/>
          </div>
          <div class="villeL">
            <label for="ville">Ville* :</label>
            <input type="text" id="ville" placeholder="Ville" required/>
          </div>
        </section>
      </section>
      <!-- Carte Google Maps -->
      <div id="map"></div>
    </section>
      
