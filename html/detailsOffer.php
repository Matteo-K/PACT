<?php 
    require_once "config.php";
    require_once "db.php";
    
    // Check if idoffre is set
    if(!isset($_GET["idoffre"])){
        header("location: index.php");
        exit();
    }

    $idOffre = $_GET["idoffre"];

    // Prepare the SQL statement with a placeholder
    $stmt = $conn->prepare("SELECT * FROM pact.parcs_attractions WHERE idoffre = '$idOffre'");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if a result was found
    if (!$result) {
        echo "No offer found with this ID.";
        exit();
    }

    print_r($result);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Link Swiper's CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="style.css">
    <title><?php echo "h" ?></title>
</head>
<body>
    <?php
        require_once "components/headerTest.php";
    ?>
    <script src="js/setColor.js"></script>
    
    <main class="mainOffer">
        <h2 id="titleOffer"><?php echo $result[0]["nom_offre"]?> </h2>
        <div>
            <!-- foreach ici !-->
            <a class="tag" href="search.php?search=parc">Parc d'attraction</a>
            <a class="tag" href="search.php?search=air">Plein air</a>
            <a class="tag" href="search.php?search=familliale">Familliale</a>
            <a class="tag" href="search.php?search=pleumeur">Pleumeur-Bodou</a>
            <a class="tag" href="search.php?state=ouvert">Ouvert</a>
        </div>
        <div>
            <a href="https://maps.app.goo.gl/PSBboQALwGsqgqKM8">Route du Radome, 22560 Pleumeur-Bodou</a>
            <a href="tel: 0296918395">02 96 91 83 95</a>
            <a href="mailto: paulblanc@gmail.com">paulblanc@gmail.com</a>
            <a href="https://www.levillagegaulois.org/">https://www.levillagegaulois.org/</a>
        </div>
        <div class="swiper-container">
            <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                    <!-- foreach !-->
                    <div class="swiper-slide">
                        <img src="img/1.jpeg" />
                    </div>
                    <div class="swiper-slide">
                        <img src="img/2.jpeg" />
                    </div>
                    <div class="swiper-slide">
                        <img src="img/3.jpeg" />
                    </div>
                    <div class="swiper-slide">
                        <img src="img/4.jpeg" />
                    </div>
                    <div class="swiper-slide">
                        <img src="img/5.jpeg" />
                    </div>
                </div>
                
            </div>

        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        </div>
        

        <div thumbsSlider="" class="swiper myThumbSlider">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <img src="img/1.jpeg" />
                </div>
                <div class="swiper-slide">
                    <img src="img/2.jpeg" />
                </div>
                <div class="swiper-slide">
                    <img src="img/3.jpeg" />
                </div>
                <div class="swiper-slide">
                    <img src="img/4.jpeg" />
                </div>
                <div class="swiper-slide">
                    <img src="img/5.jpeg" />
                </div>
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
    <?php require_once "components/footer.php" ?>
    
</body>
</html>
