<?php 
    require_once "config.php";
    require_once "db.php";
    
    // Check if idoffre is set
    if(!isset($_GET["idoffre"])){
        header("location: index.php");
        exit();
    }

    $idOffre = $_GET["idoffre"];

    // Prepare the SQL statement with placeholders
    $stmt = $conn->prepare("SELECT * FROM pact.parcs_attractions WHERE idoffre = '$idOffre'");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        $stmt = $conn->prepare("SELECT * FROM pact.restaurants WHERE idoffre = '$idOffre'");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            $stmt = $conn->prepare("SELECT * FROM pact.activites WHERE idoffre = '$idOffre'");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                $stmt = $conn->prepare("SELECT * FROM pact.spectacles WHERE idoffre = '$idOffre'");
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$result) {
                    $stmt = $conn->prepare("SELECT * FROM pact.visites WHERE idoffre = '$idOffre'");
                    $stmt->execute();
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Link Swiper's CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="style.css">
    <title><?php echo $result["nom_offre"] ?> </title>
</head>
<body>
    <?php require_once "components/headerTest.php"; ?>
    <script src="js/setColor.js"></script>
    
    <main class="mainOffer">
        <h2 id="titleOffer"><?php echo $result["nom_offre"] ?> </h2>
        <div>
        <?php 
            $stmt = $conn->prepare("SELECT t.nomTag FROM pact._offre o
                                    LEFT JOIN pact._tag_parc tp ON o.idOffre = tp.idOffre
                                    LEFT JOIN pact._tag_spec ts ON o.idOffre = ts.idOffre
                                    LEFT JOIN pact._tag_Act ta ON o.idOffre = ta.idOffre
                                    LEFT JOIN pact._tag_restaurant tr ON o.idOffre = tr.idOffre
                                    LEFT JOIN pact._tag_visite tv ON o.idOffre = tv.idOffre
                                    LEFT JOIN pact._tag t ON t.nomTag = COALESCE(tp.nomTag, ts.nomTag, ta.nomTag, tr.nomTag, tv.nomTag)
                                    WHERE o.idOffre = '$idOffre' ORDER BY o.idOffre");
            $stmt->execute();
            $tags = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        
            foreach ($tags as $tag): ?>
                <a class="tag" href="search.php"><?php echo htmlspecialchars($tag["nomtag"]); ?></a>
            <?php endforeach; ?>
        </div>

           
        </div>
        <div>
            <a href="https://maps.app.goo.gl/PSBboQALwGsqgqKM8">Route du Radome, 22560 Pleumeur-Bodou</a>
            <a href="tel: 0296918395">02 96 91 83 95</a>
            <a href="mailto: paulblanc@gmail.com">paulblanc@gmail.com</a>
            <a href="https://www.levillagegaulois.org/">https://www.levillagegaulois.org/</a>
        </div>
        <?php
            $stmt = $conn->prepare("SELECT * FROM pact._illustre WHERE idoffre = '$idOffre' ORDER BY url ASC");
            $stmt->execute();
            $photos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <div class="swiper-container">
            <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                <?php
                    foreach ($photos as $picture) {
                ?>
                        <div class="swiper-slide">
                            <img src="<?php echo $picture['url']; ?>" />
                        </div>
                <?php
                    }
                ?>
                </div>
            </div>

        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        </div>

        <div thumbsSlider="" class="swiper myThumbSlider">
            <div class="swiper-wrapper">
            <?php
                foreach ($photos as $picture) {
            ?>
                    <div class="swiper-slide">
                        <img src="<?php echo $picture['url']; ?>" />
                    </div>
            <?php
                }
            ?>
            </div>
        </div>

        <!-- Swiper JS -->
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
        
        <!-- Initialize Swiper -->
        <script>
        var swiper = new Swiper(".myThumbSlider", {
            loop: true,
            spaceBetween: 10,
            slidesPerView: 4,
            freeMode: true,
            watchSlidesProgress: true,
        });
        var swiper2 = new Swiper(".mySwiper", {
            loop: true,
            autoplay: {
                delay: 5000,
            },
            spaceBetween: 10,
            navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
            },
            thumbs: {
            swiper: swiper,
            },
        });
        </script>
    </main>
    <?php require_once "components/footer.php"; ?>
</body>
</html>
