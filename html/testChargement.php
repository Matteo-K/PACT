<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>PACT - Chargement...</title>
</head>
<body id="chargement">

    <div>
        <img id="logo" src="../img/logo.png" title="logo du site" class="chargementActif">
        <h1 id="logoText">PACT</h1>
    </div>
    <p>
        
    </p>


    <script>
    const slogans = [
        "Prépare ta prochaine escapade en Bretagne...",
        "Vérification des marées en cours...",
        "Chargement des crêpes et du cidre...",
        "Connexion aux légendes bretonnes...",
        "Préparation du kouign-amann, patience mon gourmand...",
        "Optimisation des bons plans bretons...",
        "Moulin à vent activé, ça charge plus vite !",
        "Ne pas déranger : les korrigans travaillent...",
        "Réglage de la météo : prévision d'une éclaircie !",
        "Répartition des bigoudènes sur la carte...",
        "Déploiement des spots secrets en cours...",
        "Préparation des sentiers côtiers, chaussures prêtes ?",
        "Recherche de la meilleure crêperie à proximité...",
        "Téléchargement des vagues pour les surfeurs...",
        "Tri des meilleures adresses : presque fini !",
        "PACT explore la Bretagne pour toi...",
        "PACT trace ta route en Bretagne...",
        "Exploration des trésors bretons en cours...",
        "PACT lève l’ancre pour une nouvelle aventure...",
        "Chargement des falaises et des plages secrètes...",
        "Vérification du bon dosage beurre/sucre...",
        "Les korrigans peaufinent ton séjour...",
        "Attention, ici il en pleut que sur les cons !",
        "On tisse ton programme comme une coiffe bigoudène...",
        "Chargement des vents iodés et des embruns...",
        "Calibration des crêpes dentelles...",
        "PACT explore les sentiers pour toi...",
        "Enquête sur la meilleure galette-saucisse en cours...",
        "On cartographie les pépites bretonnes...",
        "PACT suit les mouettes pour t’indiquer la voie...",
        "On ajuste la marée pour une sortie idéale...",
        "Déploiement des plus belles lumières bretonnes...",
        "PACT embarque à bord, tiens bon la barre !",
    ];

    const texte = document.querySelector("p");

    function changeSlogan() {
        // Ajoute une classe pour l'effet de disparition
        texte.classList.add("ancienSlogan");

        setTimeout(() => {
            // Change le texte
            texte.textContent = slogans[Math.floor(Math.random() * slogans.length)];
            // Enlève la classe pour l'effet d'apparition
            texte.classList.remove("ancienSlogan");
            texte.classList.add("nouveauSlogan");

            setTimeout(() => {
                texte.classList.remove("nouveauSlogan");
            }, 500); // Durée de l'animation d'apparition
        }, 500); // Correspond à la durée de l'animation de disparition
    }

    setInterval(changeSlogan, 3600); // Change toutes les 3.6 secondes

    // Première exécution immédiate
    changeSlogan();
</script>

<style>
    .ancienSlogan {
        transform: translateY(40);
        opacity: 0;
        transition: opacity 0.5s ease-out, transform 0.5s ease-out;
    }

    .nouveauSlogan {
        transform: translateY(0);
        opacity: 1;
        transition: opacity 0.5s ease-in, transform 0.5s ease-in;
    }

</style>

</body>
</html>
