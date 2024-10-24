<?php
  $montant = 99.98;
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
  </section>
