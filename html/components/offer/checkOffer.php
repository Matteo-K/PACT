<?php

/**
 * Vérifie si la partie de sélection d'offre comporte des informations obligatoires manquants
 * Si la partie est complète : return "guideComplete"
 * Si la partie est incomplète : return "guideStartComplete"
 * Si la partie est vide : return ""
 */
// Partie de sélection de l'offre
function checkSelectOffer($idOffre) : string {
  return "";
}

// Partie de détails de l'offre
function checkDetailsOffer($idOffre) : string {
  return "";
}

// Partie de localisation de l'offre
function checkLocalisationOffer($idOffre) : string {
  return "";
}

// Partie de contact de l'offre
function checkContactOffer($idOffre) : string {
  return "";
}

// Partie d'horaire de l'offre
function checkHourlyOffer($idOffre) : string {
  return "";
}

// Partie de prévisualisation de l'offre
function checkPreviewOffer($idOffre) : string {
  return "";
}

// Partie de paiement de l'offre
function checkPayementOffer($idOffre) : string {
  return "";
}

function getNull($idOffre) : bool {
  require "../../config.php";
  $cook = $conn->prepare("SELECT o.idu,o.idoffre,o.nom,o.statut,o.description,o.mail,o.affiche,o.resume FROM pact._offre o WHERE idoffre=$idOffre");
  $cook->execute();
  $offre = $cook->fetchAll(PDO::FETCH_ASSOC);
  $affiche=false;
  foreach ($offre[0] as $key => $value) {
    if ($value == NULL) {
      $affiche=true;
    }
  }
  if ($affiche) {
    $resto = $conn->prepare("SELECT * FROM pact._restauration WHERE idoffre=$idOffre");
    $resto->execute();
    $restau = $resto->fetchAll(PDO::FETCH_ASSOC);

    $spec = $conn->prepare("SELECT * FROM pact._spectacle WHERE idspect=$idOffre");
    $spec->execute();
    $spect = $spec->fetchAll(PDO::FETCH_ASSOC);

    $visi = $conn->prepare("SELECT * FROM pact._visite WHERE idoffre=$idOffre");
    $visi->execute();
    $visit = $visi->fetchAll(PDO::FETCH_ASSOC);

    $act = $conn->prepare("SELECT * FROM pact._activite WHERE idoffre=$idOffre");
    $act->execute();
    $acti = $act->fetchAll(PDO::FETCH_ASSOC);

    $parc = $conn->prepare("SELECT * FROM pact._parcattraction WHERE idoffre=$idOffre");
    $parc->execute();
    $parca = $parc->fetchAll(PDO::FETCH_ASSOC);
    
    if ($restau) {
      $tema=$restau;
    }elseif ($spect) {
      $tema=$spect;
    }elseif ($visit) {
      $tema=$visit;
    }elseif ($acti) {
      $tema=$acti;
    }else {
      $tema=$parca;
    }

    foreach ($tema[0] as $key => $value) {
      if ($value==NULL) {
        $affiche=true;
      }
    }
    $adr = $conn->prepare("SELECT * FROM pact.localisation WHERE idoffre=$idOffre");
    $adr->execute();
    $loca = $adr->fetchAll(PDO::FETCH_ASSOC);

    if (!$loca) {
      $affiche=true;
    }
  }

  echo'ok';
  return true;
}
getNull(4);
?>