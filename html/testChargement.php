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
        "Exploration des trésors bretons en cours...",
        "PACT lève l’ancre pour une nouvelle aventure...",
        "Chargement des falaises et des plages secrètes...",
        "Vérification du bon dosage beurre/sucre...",
        "Les korrigans peaufinent ton séjour...",
        "Attention, ici il ne pleut que sur les c...",
        "On tisse ton programme comme une coiffe bigoudène...",
        "Chargement des vents d'ouest et des embruns...",
        "Calibration des crêpes dentelles...",
        "Enquête sur la meilleure galette-saucisse en cours...",
        "On cartographie les pépites bretonnes...",
        "PACT suit les mouettes pour t’indiquer la voie...",
        "On ajuste la marée pour une sortie idéale...",
        "Déploiement des plus belles lumières bretonnes...",
        "Chargement des phares, attention aux tempêtes...",
        "Les druides concoctent de nouveaux bons plans...",
        "Mise en place des dolmens et menhirs...",
        "Chargement des marais salants...",
        "Révision des accords de bombarde et biniou...",
        "N'oublions pas la côte de granit rose...",
        "Préparation des fest-noz, échauffe tes jambes...",
        "Harmonisation du chant des goélands...",
        "Préparation de la potion magique...",
        "PACT envoie une jolie carte postale pour le départ...",
        "Réglage du compas vers la bonne direction...",
        "PACT cherche une connexion... aux esprits celtes...",
        "PACT demande à Merlin de valider ton parcours...",
        "Trop de beurre ajouté... Redémarrage en cours...",
        "Les korrigans sont en grève... Attendons qu'ils coopèrent...",
        "Un goéland a volé nos plans... On les récupère !",
        "PACT souffle sur les nuages... Beau temps en approche !"
    ];

    const texte = document.querySelector("p");

    // utilisation des classes avec des animations pour faire disparaître / apparaitre les slogans
    function changeSlogan() {
        texte.classList.add("ancienSlogan");

        setTimeout(() => {
            texte.textContent = slogans[Math.floor(Math.random() * slogans.length)];

            //
            texte.classList.remove("ancienSlogan");
            texte.classList.add("nouveauSlogan");

            setTimeout(() => {
                texte.classList.remove("nouveauSlogan");
            }, 400); 
        }, 400); 
    }

    setInterval(changeSlogan, 3600); // Active la fonction toutes les 3.6 secondes (= 3 rotations du logo pour être synchro)

    changeSlogan();
</script>

</body>
</html>
