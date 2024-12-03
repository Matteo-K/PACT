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
            </section>
    <?php } ?>
  </main>
</body>
</html>