<?php
// Démarrer la session
require "../config.php";

// Supprimer la valeur de '2fa_verified' si l'input est vide
unset($_SESSION['a2f_verifier']);

// Retourner une confirmation
echo "Session réinitialisée.";
?>
