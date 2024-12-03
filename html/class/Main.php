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
                <h2><?php echo $value["idOffre"] + " - " + $value["nomOffre"] ?></h2>
                <p>Data&nbsp;:&nbsp;</p>
                <?php print_r($value) ?>
                <input type="text" id="input-<?php echo $key ?>">
                <pre id="code-<?php echo $key ?>" style="color : green;"></pre>
            </section>
    <?php } ?>
    <div id="offers-data" data-offers='<?php echo htmlspecialchars(json_encode($offres->getArray($offres->recherche($idUser, $typeUser, $search)))); ?>'></div>
  </main>
  <script>

    document.addEventListener('DOMContentLoaded', function() {
        const offersDataElement = document.getElementById('offers-data');
        const offersData = offersDataElement.getAttribute('data-offers');
        // console.log(offersData); // Débugger

        try {
            arrayOffer = JSON.parse(offersData);
            arrayOffer = Object.values(arrayOffer);
        } catch (error) {
            console.error("Erreur de parsing JSON :", error);
        }
    });

    const input = document.querySelectorAll("[type='text']");
    const code = document.querySelectorAll("pre");

    input.forEach(element => {
        element.addEventListener("blur", (this) => {
            const pre = this.closest('.wrapper').querySelector('pre');
            pre.textContent = arrayOffer;
        });
    });
  </script>
</body>
</html>