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
        foreach ($getOffres as $key => $value) {
            print_r($value); ?> <br> <?php
        }
    ?>
  </main>
</body>
</html>