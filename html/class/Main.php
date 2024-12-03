<?php require_once __DIR__."/../config.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Class Main</title>
</head>
<body>
  <?php $offres = new ArrayOffer(); ?>
  <main>
    <?php 
        $getOffres = $offres->getArray();
        foreach ($getOffres as $key => $value) { ?>
            <section>
                <h2><?php echo $value['idOffre'] . " - " . $value["nomOffre"]; ?></h2>
                <p>Data&nbsp;:&nbsp;</p>
                <?php print_r($value) ?>
                <br>
                <input type="text" id="input-<?php echo $key ?>" placeholder="nom de l'attribut">
                <br>
                <pre id="code-<?php echo $key ?>" style="color : green;"></pre>
            </section>
    <?php } ?>
    <div id="offers-data" data-offers='<?php echo htmlspecialchars(json_encode($offres->getArray())); ?>'></div>
  </main>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
        const offersDataElement = document.getElementById('offers-data');
        const offersData = offersDataElement.getAttribute('data-offers');
        
        let arrayOffer = [];
        
        try {
            arrayOffer = JSON.parse(offersData);
            arrayOffer = Object.values(arrayOffer); // Convertir l'objet en tableau
        } catch (error) {
            console.error("Erreur de parsing JSON :", error);
        }

        // Sélectionner tous les champs de recherche
        const searchInputs = document.querySelectorAll('.search-input');

        searchInputs.forEach(input => {
            input.addEventListener("input", function() {
                const searchTerm = this.value.toLowerCase(); // Obtenir le terme recherché

                // Parcourir arrayOffer pour trouver les correspondances
                let result = '';
                arrayOffer.forEach(item => {
                    // Assurez-vous que chaque élément de arrayOffer est un objet
                    for (let key in item) {
                        if (key.toLowerCase().includes(searchTerm)) {
                            result += `${key}: ${item[key]}\n`; // Afficher l'attribut trouvé
                        }
                    }
                });

                // Afficher le résultat dans le <pre> associé
                const pre = this.closest('section').querySelector('pre');
                if (pre) {
                    pre.textContent = result || 'Aucun résultat trouvé';
                }
            });
        });
    });

  </script>
</body>
</html>