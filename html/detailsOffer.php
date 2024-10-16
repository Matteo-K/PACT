<?php
session_start();

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
    <script src="js/setColor.js"></script>
    <?php require_once "components/header.php" ?>
    <main class="mainOffer">
        <h2 id="titleOffer">Le Village Gaulois</h2>
        <div>
            <!-- foreach ici !-->
            <a href="search.php?search=parc">Parc d'attraction</a>
            <a href="search.php?search=air">Plein air</a>
            <a href="search.php?search=familliale">Familliale</a>
            <a href="search.php?search=pleumeur">Pleumeur-Bodou</a>
            <a href="search.php?state=ouvert">Ouvert</a>
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
                delay: 3000,
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
