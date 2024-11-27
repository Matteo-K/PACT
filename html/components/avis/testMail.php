<?php
    $destinataire = 'kylian.houedec.56@gmail.com'; // Adresse email du destinataire
    $sujet = 'Nouveau message de contact depuis votre site';
    $contenu = '<html><head><title>Titre du message</title><style>body {background-color: black;color: white;font-family:margin: 0;padding: 20px;}p {color: red;font-size: 16px;line-height: 1.5;margin-bottom: 10px;}strong {color: white;font-weight: bold; }h1 {color: white;border-bottom: 2px solid white;padding-bottom: 10px;}</style></head><body>';
    $contenu .= '<p>Bonjour, vous avez reçu un message à partir de votre site web.</p>';
    $contenu .= '<p><strong>Nom</strong>: Kylian Houedec</p>';
    $contenu .= '<p><strong>Email</strong>: kylian.houedec.56@gmail.com</p>';
    $contenu .= '<p><strong>Message</strong>: dxrfcgvbjnklbhcfxcvhkn,jvjcfxggvbnbvchfx</p>';
    $contenu .= '</body></html>'; // Contenu du message de l'email

    // Pour envoyer un email HTML, l'en-tête Content-type doit être défini
    $entetes = 'MIME-Version: 1.0' . "\r\n";
    $entetes .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $entetes .= "From: $nom <$email>";

    // Envoi de l'email
    if (mail($destinataire, $sujet, $contenu, $entetes)) {
        echo '<h2>Message envoyé!</h2>';
        // Réinitialiser les valeurs des champs après l'envoi du mail
    } else {
        echo '<p>Une erreur est survenue lors de l\'envoi du message.</p>';
    }
?>