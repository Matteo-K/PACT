<?php
$is_prive = false;
?>
<form id="selectOffer">
  <div>
    <div>
      <h2>Offre Gratuit</h2>
      <h3>0&euro;&nbsp;/&nbsp;mois</h3>
      <ul>
        <li>Réservée au public</li>
      </ul>
      <div>
        <input type="radio" name="selection" id="selectionOffer1">
        <label for="selectionOffer1">Sélectionner</label>
      </div>
    </div>
    <div>
      <h2>Offre Premium</h2>
      <h3>119,99&euro;&nbsp;/&nbsp;mois</h3>
      <ul>
        <li>Réservée au privé</li>
        <li>Saisie d’une grille tarifaire</li>
        <li>Blackliste sur 3 avis</li>
      </ul>
      <div>
        <input type="radio" name="selection" id="selectionOffer2">
        <label for="selectionOffer2">Sélectionner</label>
      </div>
    </div>
    <div>
      <h2>Offre Standard</h2>
      <h3>79,99&euro;&nbsp;/&nbsp;mois</h3>
      <ul>
        <li>Réservée au privé</li>
        <li>Saisie d’une grille tarifaire</li>
      </ul>
      <div>
        <input type="radio" name="selection" id="selectionOffer3">
        <label for="selectionOffer3">Sélectionner</label>
      </div>
    </div>
  </div>
  <div>
    <div>
      <span>(&nbsp;+14.99&euro;&nbsp;)</span>
      <input type="checkbox" name="enRelief" id="enRelief">
      <label for="enRelief"><span>En relief</span> : met votre offre en exergue lors de son affichage dans la liste d’offres</label></div>
    <div>
      <span>(&nbsp;+19.99&euro;&nbsp;)</span>
      <input type="checkbox" name="aLaUne" id="aLaUne">
      <label for="aLaUne"><span>À la une</span> : met votre offre sur la page d’accueil du site</label>
    </div>
    <p>Attention ! Vous ne pouvez pas changer d’offre une fois séléctionée.</p>
  </div>

</form>