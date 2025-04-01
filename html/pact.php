<?php
  require_once "config.php";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <link rel="icon" href="img/logo.png" type="image/x-icon">
  <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
  <title>PACT</title>
  <script src="js/setColor.js"></script>

    <!-- Leaflet CSS & JS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

</head>
<body id="sansScroll">
  <?php require_once "components/header.php"; ?>
  <main>
    <?php if ($typeUser == "admin") {
      require_once "components/indexAdmin.php";
    } else { ?>
      <div id="index" class="<?= ($typeUser == "pro_public" || $typeUser == "pro_prive") ? "indexPro" : "" ?>">

        <?php if ($typeUser != "pro_public" && $typeUser != "pro_prive") {

          $stmt = $conn->prepare("SELECT o.idoffre FROM pact._option_offre o 
            natural join pact._dateoption d
            left join pact._offre f on f.idoffre = o.idoffre
            where d.datelancement <= CURRENT_DATE 
              AND d.datefin > CURRENT_DATE 
              AND nomoption = 'ALaUne'
              AND statut = 'actif';"
          );
          $stmt->execute();
          $idOffres = [];
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $idOffres[] = $row['idoffre'];
          }
          if (count($idOffres) > 0) {
            ?>
        <div>
          <h2>Offres à la Une</h2>
          <div class="swiper-container gb">
            <div class="swiper gb">
              <div class="swiper-wrapper gb">
                <?php 
                  $elementStart = 0;
                  $nbElement = 20;
                  $offres = new ArrayOffer($idOffres);
                  $offres->displayCardALaUne();
                  //$offres->displayCard("<p>Aucune offre à la une </p>");
                  ?>
              </div>
            </div>
            <div class="swiper-button-next gb"></div>
            <div class="swiper-button-prev gb"></div>
          </div>
        </div>
        <?php 
        }
        $nbElement = 10;
        if ($typeUser == "membre") {
          $stmt = $conn->prepare("SELECT idoffre FROM pact._consulter WHERE idu = ? ORDER BY dateconsultation LIMIT ?");
          $stmt->execute([$_SESSION['idUser'], $nbElement]);
          $idOffres = [];
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $idOffres[] = $row['idoffre'];
          }
        } elseif ($typeUser == "visiteur") {
          $idOffres = $_SESSION["recent"] ?? [];
        }

        if (count($idOffres) > 0) {
        ?>
        <div id="consultationRecente">
          <h2>Consulté récemment</h2>
          <div class="swiper-container gb2">
            <div class="swiper gb2">
              <div class="swiper-wrapper gb2">
                <?php if (count($idOffres) > 0) {
                  $consultRecent = new ArrayOffer($idOffres);
                  $consultRecent->displayConsulteRecemment($nbElement);
                  ?>
                  <?php } else { ?>
                  <p>Aucune offre consultée récemment</p>
                  <?php } ?>
              </div>
            </div>
            <div class="swiper-button-next gb2"></div>
            <div class="swiper-button-prev gb2"></div>
          </div>
        </div>
        <?php
          }
          // Toute les nouvelles offres inférieurs à 2 semaines
          $stmt = $conn->prepare("SELECT idoffre FROM pact.offres WHERE datecrea >= NOW() - INTERVAL '14 days' AND statut = 'actif' ORDER BY datecrea LIMIT 10");

          $stmt->execute();
          $idOffres = [];
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $idOffres[] = $row['idoffre'];
          }
          if (count($idOffres) > 0) {
        ?>
        <div id="consultationNouvelle">
          <h2>Offres nouvelles</h2>
          <div class="swiper-container gb3">
            <div class="swiper gb3">
              <div class="swiper-wrapper gb3">
                <?php if (count($idOffres) > 0) {
                  $consultNouvelle = new ArrayOffer($idOffres);
                  $consultNouvelle->displayNouvelle();
                  //$consultNouvelle->displayCard("<p>Aucune nouvelle offres ont été posté</p>");
                ?>
                <?php } else { ?>
                  <p>Aucune nouvelle offres ont été posté</p>
                <?php } ?>
              </div>
            </div>
            <div class="swiper-button-next gb3"></div>
            <div class="swiper-button-prev gb3"></div>
          </div>
        </div>
        <?php 
            }
          } 
        ?>
        <div id="voirPlus">
          <?php if ($typeUser == "pro_public" || $typeUser == "pro_prive") { ?>
            <h2>Vous avez une activité à partager ?</h2>
            <p>Rejoignez notre communauté et proposez vos activités, événements, ou services en quelques clics.</p>
            <a href="manageOffer.php" class="modifierBut">Créer une offre</a>  
          <?php } ?>
        </div>
      </div>
        <!-- Partie de recherche -->
      <div id="searchIndex" class="search">

        <h2>
          <?php
            if ($typeUser == "pro_public" || $typeUser == "pro_prive") {
              echo "Mes offres";
            }

            else {
              echo "Recherche";
            }
          ?>
        </h2>

        <div id="blcSearch">
          <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.css" />
          <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.Default.css" />
          <script src="https://unpkg.com/leaflet.markercluster/dist/leaflet.markercluster.js"></script>
          <?php 

          require_once "components/asideTriFiltre.php";

          $offres = new ArrayOffer("enLigne", $typeUser);
          ?>
          <section class="searchoffre">
          </section>
        </div>
        <section id="pagination">
            <ul id="pagination-liste">
            </ul>
        </section>
      </div>
    <?php } ?>
    <?php require_once "components/footer.php"; ?>
    <?php if ($typeUser != "admin") { ?>
      <!-- Data -->
      <div id="offers-data" data-offers='<?php echo htmlspecialchars(json_encode($offres->getArray($offres->filtre($idUser, $typeUser)))); ?>'></div>
      <div id="user-data" data-user='<?php echo $typeUser ?>'></div>
      
      <script type="module" src="js/sortAndFilter.js"></script>
      <script>
        function redirectionDetails(event) {
          if (event.target.tagName.toLowerCase() === "a") {
            return;
          }
          event.preventDefault();
          event.currentTarget.submit();

          const logo = document.querySelector("#logo");
          logo.classList.add("chargementActif");
          setTimeout(() => {
              logo.classList.remove("chargementActif");
          }, 6000);
        }

        const forms = document.querySelectorAll("#index form");
        forms.forEach(form => {
          form.addEventListener("click", redirectionDetails);
          form.addEventListener("keydown", (event) => {
            if (event.key === "Enter") {
              redirectionDetails(event);
            }
          });
        });
      </script>

  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

      <script>
          document.addEventListener('DOMContentLoaded', function () {

            const isMobile = window.innerWidth <= 700

            try {
              const swiperWrapper = document.querySelector('.swiper-wrapper.gb');    
              const forms = swiperWrapper.querySelectorAll('form');
              forms.forEach(form => {
                const swiperSlide = document.createElement('div');  // Créer une div
                swiperSlide.classList.add('swiper-slide');
                swiperSlide.classList.add('gb');  // Ajouter la classe 'swiper-slide'

                // Déplacer le formulaire dans la nouvelle div
                swiperSlide.appendChild(form);

                // Ajouter la div contenant le formulaire dans le swiper-wrapper
                swiperWrapper.appendChild(swiperSlide);
              });

              if (!isMobile) {
                const swiper = new Swiper('.swiper.gb', {
                  loop: true, // Si vous ne voulez pas que les slides bouclent, mettez 'loop: false'
                  speed: 600, // La vitesse de transition entre les slides
                  spaceBetween: 25, // L'espace entre les slides
                  effect: 'slide', // Effet par défaut (il peut aussi être 'fade', 'cube', etc.)
                  slidesPerView: 3, // Nombre de slides visibles
                  centeredSlides: false, // Définit si la slide actuelle est centrée
                  watchOverflow: true, 
                  autoplay: {
                    delay: 5000,
                  },
                  navigation: {
                    nextEl: '.swiper-button-next.gb',
                    prevEl: '.swiper-button-prev.gb',
                  },
                });
              }else{
                const swiperT = new Swiper('.swiper.gb', {
                  loop: true, // Si vous ne voulez pas que les slides bouclent, mettez 'loop: false'
                  speed: 600, // La vitesse de transition entre les slides
                  spaceBetween: 0, // L'espace entre les slides
                  effect: 'slide', // Effet par défaut (il peut aussi être 'fade', 'cube', etc.)
                  slidesPerView: 1, // Nombre de slides visibles
                  centeredSlides: false, // Définit si la slide actuelle est centrée
                  watchOverflow: true, 
                  autoplay: {
                    delay: 5000,
                  },
                  navigation: {
                    nextEl: '.swiper-button-next.gb',
                    prevEl: '.swiper-button-prev.gb',
                  },
                });
              }


              
            } catch (error) {

            }

            try {
              const swiperWrapper2 = document.querySelector('.swiper-wrapper.gb2');
              const forms2 = swiperWrapper2.querySelectorAll('form');
              forms2.forEach(form => {
                const swiperSlide2 = document.createElement('div');  // Créer une div
                swiperSlide2.classList.add('swiper-slide');
                swiperSlide2.classList.add('gb2');  // Ajouter la classe 'swiper-slide'

                // Déplacer le formulaire dans la nouvelle div
                swiperSlide2.appendChild(form);

                // Ajouter la div contenant le formulaire dans le swiper-wrapper
                swiperWrapper2.appendChild(swiperSlide2);

                if (!isMobile) {
                  const swiper2 = new Swiper('.swiper.gb2', {
                    loop: true, // Si vous ne voulez pas que les slides bouclent, mettez 'loop: false'
                    speed: 600, // La vitesse de transition entre les slides
                    spaceBetween: 25, // L'espace entre les slides
                    effect: 'slide', // Effet par défaut (il peut aussi être 'fade', 'cube', etc.)
                    slidesPerView: 3, // Nombre de slides visibles
                    centeredSlides: false, // Définit si la slide actuelle est centrée
                    watchOverflow: true, 
                    autoplay: {
                      delay: 5000,
                    },
                    navigation: {
                      nextEl: '.swiper-button-next.gb2',
                      prevEl: '.swiper-button-prev.gb2',
                    },
                  });
                } else {
                  const swiper2T = new Swiper('.swiper.gb2', {
                    loop: true, // Si vous ne voulez pas que les slides bouclent, mettez 'loop: false'
                    speed: 600, // La vitesse de transition entre les slides
                    spaceBetween: 0, // L'espace entre les slides
                    effect: 'slide', // Effet par défaut (il peut aussi être 'fade', 'cube', etc.)
                    slidesPerView: 1, // Nombre de slides visibles
                    centeredSlides: false, // Définit si la slide actuelle est centrée
                    watchOverflow: true, 
                    autoplay: {
                      delay: 5000,
                    },
                    navigation: {
                      nextEl: '.swiper-button-next.gb2',
                      prevEl: '.swiper-button-prev.gb2',
                    },
                  });  
                }



              });
            } catch (error) {

            }

            try {
              const swiperWrapper3 = document.querySelector('.swiper-wrapper.gb3');

              const forms3 = swiperWrapper3.querySelectorAll('form');
              // Pour chaque formulaire, créer une div avec la classe 'swiper-slide' et y insérer le formulaire
              forms3.forEach(form => {
                const swiperSlide3 = document.createElement('div');  // Créer une div
                swiperSlide3.classList.add('swiper-slide');
                swiperSlide3.classList.add('gb3');  // Ajouter la classe 'swiper-slide'

                // Déplacer le formulaire dans la nouvelle div
                swiperSlide3.appendChild(form);

                // Ajouter la div contenant le formulaire dans le swiper-wrapper
                swiperWrapper3.appendChild(swiperSlide3);
              });

              if (!isMobile) {
                const swiper3 = new Swiper('.swiper.gb3', {
                  loop: true, // Si vous ne voulez pas que les slides bouclent, mettez 'loop: false'
                  speed: 600, // La vitesse de transition entre les slides
                  spaceBetween: 25, // L'espace entre les slides
                  effect: 'slide', // Effet par défaut (il peut aussi être 'fade', 'cube', etc.)
                  slidesPerView: 3, // Nombre de slides visibles
                  centeredSlides: false, // Définit si la slide actuelle est centrée
                  watchOverflow: true, 
                  autoplay: {
                    delay: 5000,
                  },
                  navigation: {
                    nextEl: '.swiper-button-next.gb3',
                    prevEl: '.swiper-button-prev.gb3',
                  },
                });
              } else {
                const swiper3T = new Swiper('.swiper.gb3', {
                  loop: true, // Si vous ne voulez pas que les slides bouclent, mettez 'loop: false'
                  speed: 600, // La vitesse de transition entre les slides
                  spaceBetween: 0, // L'espace entre les slides
                  effect: 'slide', // Effet par défaut (il peut aussi être 'fade', 'cube', etc.)
                  slidesPerView: 1, // Nombre de slides visibles
                  centeredSlides: false, // Définit si la slide actuelle est centrée
                  watchOverflow: true, 
                  autoplay: {
                    delay: 5000,
                  },
                  navigation: {
                    nextEl: '.swiper-button-next.gb3',
                    prevEl: '.swiper-button-prev.gb3',
                  },
                }); 
              }
            } catch (error) {

            }
        });  
      </script>
    <?php } ?>
</body>
</html>