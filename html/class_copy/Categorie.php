<?php

interface Categorie {

  /**
   * Charge les données suivant les attributs saisit
   * @param Array $parentAttribut liste des attributs dans le parent
   * @param Array $thisAttribut liste des attributs dans cette catégorie
   */
  public function getData($parentAttribut = [], $thisAttribut = []);

  /**
   * Charge les données suivant les attributs saisit
   * @param Array $attribut Liste de colonne désiré
   */
  public function loadData($attribut = []);
}


?>