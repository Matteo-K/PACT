<?php
// Démarrer la session
require "../config.php";

// Supprimer la valeur de '2fa_verified' si l'input est vide
unset($_SESSION['2fa_verified']);

// Retourner une confirmation
echo "Session réinitialisée.";
?>
