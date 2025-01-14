<?php
  $stmt = $conn->prepare("SELECT 
    COALESCE((SELECT SUM(prixoffre) 
              FROM pact._option_offre 
              NATURAL JOIN pact._option 
              WHERE idoffre = ?), 0) + 
    COALESCE((SELECT tarif 
              FROM pact._abonner 
              NATURAL JOIN pact._abonnement 
              WHERE idoffre = ?), 0) AS total;"
  );
  $stmt->execute([$idOffre, $idOffre]);
  // si les options éxistent, on les ajoutent dans la base de donnée
  $montant = 0;
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $montant = $row["total"];
  }
?>
<form id="paymentOffer" action="enregOffer.php" method="post">
  <section>
    <!-- Saisi du type de moyen de paiement -->
    <article>
      <div>
        <input type="radio" name="typePaiement" id="carte_bancaire" checked>
        <label for="carte_bancaire">
          <figure>
            <figcaption>CARTE BANCAIRE</figcaption>
            <img src="../../img/logo/visa-mastercard.png" alt="logo visa-mastercard">
          </figure>
        </label>
      </div>
      <div>
        <input type="radio" name="typePaiement" id="virement_bancaire">
        <label for="virement_bancaire">
          <figure>
            <figcaption>VIREMENT BANCAIRE</figcaption>
          </figure>
        </label>
      </div>
      <div>
        <input type="radio" name="typePaiement" id="paypal">
        <label for="paypal">
          <figure>
            <figcaption>PAYPAL</figcaption>
            <img src="../../img/logo/paypal.png" alt="logo paypal">
          </figure>
        </label>
      </div>
    </article>
    <!-- Saisi du moyen de paiement -->
    <article>
      <!-- Carte bancaire -->
      <div id="Form_CB">
        <label for="CBnumCarte">Numéro de carte bancaire*&nbsp;:&nbsp;</label>
        <input type="number" name="CBnumCarte" id="CBnumCarte" placeholder="Numéro carte">
        <div>
          <div>
            <label for="CBDateExpir">Date expiration*&nbsp;:&nbsp;</label>
            <input type="month" name="CBDateExpir" id="CBDateExpir" value="<?php echo date('Y-m'); ?>">
          </div>
          <div>
            <label for="CBCodeSecur">Code sécurité*&nbsp;:&nbsp;</label>
            <input type="number" name="CBCodeSecur" id="CBCodeSecur" placeholder="CVC">
          </div>
        </div>
        <label for="CBNomCarte">Nom sur la carte*&nbsp;:&nbsp;</label>
        <input type="text" name="CBDateExpir" id="CBDateExpir" placeholder="Prénom et nom">

        <button class="blueBtnOffer">Valider le moyen de paiement</button>
      </div>
      <!-- Virement bancaire -->
      <div id="Form_VB" class="payment_hide">
        <label for="VBIban">IBAN*&nbsp;:&nbsp;</label>
        <input type="text" name="VBIban" id="VBIban" placeholder="IBAN">
        <label for="VBTitulaire">Nom du titulaire du compte*&nbsp;:&nbsp;</label>
        <input type="text" name="VBTitulaire" id="VBTitulaire" placeholder="Prénom et nom">

        <button class="blueBtnOffer">Valider le moyen de paiement</button>
      </div>
      <!-- Paypal -->
      <div id="Form_paypal" class="payment_hide">
        <h4>Connectez-vous à <br> votre compte paypal</h4>
        <label for="PaypalAdresse">Adresse mail*&nbsp;:&nbsp;</label>
        <input type="text" name="PaypalAdress" id="PaypalAdress" placeholder="Mail">
        <label for="PaypalPassword">Mot de passe*&nbsp;:&nbsp;</label>
        <input type="password" name="PaypalPassword" id="PaypalPassword" placeholder="Mot de passe">
        <button class="blueBtnOffer">Se connecter</button>
      </div>
    </article>
  </section>
  <section>
    <h4>Montant total à régler : <?php echo $montant; ?>€ (lors de la mise en ligne)</h4>
    <p onclick="toggleAsidePayment()" class="modifierBut">Retirer mes coordonnées</p>
  </section>
  <div id="removeCB" class="hidenAside">
    <div>
      <h3>Vos coordonnées bancaires ont bien été effacées</h3>
      <p onclick="toggleAsidePayment()" class="modifierBut">Continuer</p>
    </div>
  </div>
  <script>

    const asidePayment = document.querySelector("#removeCB");
    function toggleAsidePayment() {
      asidePayment.classList.toggle("hidenAside");
    }

    const radBtnCB = document.querySelector("#carte_bancaire");
    const radBtnVB = document.querySelector("#virement_bancaire");
    const radBtnPaypal = document.querySelector("#paypal");

    const Form_CB = document.getElementById("Form_CB");
    const Form_VB = document.getElementById("Form_VB");
    const Form_paypal = document.getElementById("Form_paypal");

    /**
     * @brief set-up tout les formulaires des moyens de payment
     */
    function updateForms() {
      hideCB();

      // Afficher le formulaire correspondant au bouton radio sélectionné
      if (radBtnCB.checked) {
        Form_CB.classList.remove("payment_hide");
      } else if (radBtnVB.checked) {
        Form_VB.classList.remove("payment_hide");
      } else if (radBtnPaypal.checked) {
        Form_paypal.classList.remove("payment_hide");
      }
    }

    function hideCB() {
      Form_CB.classList.add("payment_hide");
      Form_VB.classList.add("payment_hide");
      Form_paypal.classList.add("payment_hide");
    }
    radBtnCB.addEventListener("input", updateForms);
    radBtnVB.addEventListener("input", updateForms);
    radBtnPaypal.addEventListener("input", updateForms);

    updateForms();
  </script>
