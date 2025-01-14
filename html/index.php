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
</head>
<body id="sansScroll">
  <script src="js/setColor.js"></script>
  <?php require_once "components/header.php"; ?>
  <main>
    <div id="index" class="<?= ($typeUser == "pro_public" || $typeUser == "pro_prive") ? "indexPro" : "" ?>">
      <?php if ($typeUser != "pro_public" && $typeUser != "pro_prive") { ?>
      <div class="swiper-container">
        <div class="swiper-wrapper">
          <?php 
            $elementStart = 0;
            $nbElement = 20;
            $offres = new ArrayOffer();
            $offres->displayCardALaUne($offres->filtre($idUser, $typeUser), $typeUser, $elementStart, $nbElement);
          ?>
        </div>

        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        <!-- Pagination: Points -->
        <div class="swiper-pagination"></div>
      </div>
      <?php if ($typeUser == "membre") {
        $nbElement = 10;
        $stmt = $conn->prepare("SELECT * FROM pact._consulter WHERE idu = ? ORDER BY dateconsultation LIMIT ?");
        $stmt->execute([$_SESSION['idUser'], $nbElement]);
        $idOffres = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          $idOffres[] = $row['idoffre'];
        }
      } elseif ($typeUser == "visiteur") {
        $idOffres = $_SESSION["recent"] ?? [];
      }
      ?>
      <div id="consultationRecente">
        <h2>Consulté récemment</h2>
        <div>
          <?php if (count($idOffres) > 0) {
            $consultRecent = new ArrayOffer($idOffres);
            $consultRecent->displayConsulteRecemment($nbElement);
          ?>
          <?php } else { ?>
          <p>Aucune offre consultée récemment</p>
          <?php } ?>
        </div>
      </div>
      <?php
        // Toute les nouvelles offres inférieurs à 2 semaines
        $stmt = $conn->prepare("SELECT * FROM pact.offres WHERE datecrea >= NOW() - INTERVAL '14 days' AND statut = 'actif' ORDER BY datecrea");

        $stmt->execute();
        $idOffres = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          $idOffres[] = $row['idoffre'];
        }
      ?>
      <div id="consultationNouvelle">
        <h2>Offre Nouvelle</h2>
        <div>
          <?php if (count($idOffres) > 0) {
            $consultNouvelle = new ArrayOffer($idOffres);
            $consultNouvelle->displayNouvelle();
          ?>
          <?php } else { ?>
            <p>Aucune nouvelle offres ont été posté</p>
          <?php } ?>
        </div>
      </div>
      <?php } ?>
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
      <div id="blcSearch">
        <?php 
        require_once "components/asideTriFiltre.php";

        $offres = new ArrayOffer();
        ?>
        <section class="searchoffre">
        </section>
      </div>
      <section id="pagination">
          <ul id="pagination-liste">
          </ul>
      </section>
    </div>
    <?php require_once "components/footer.php"; ?>
    <!-- Data -->
    <div id="offers-data" data-offers='<?php echo htmlspecialchars(json_encode($offres->getArray($offres->filtre($idUser, $typeUser)))); ?>'></div>
    <div id="user-data" data-user='<?php echo $typeUser ?>'></div>
    <script src="js/sortAndFilter.js"></script>
    <?php require_once "components/footer.php"; ?>
    <script>
      const forms = document.querySelectorAll("#index form");
      forms.forEach(form => {
        form.addEventListener("click", (event) => {
          if (event.target.tagName.toLowerCase() === "a") {
            return;
          }
          event.preventDefault();
          form.submit();
        });
      });
    </script>

<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const swiperWrapper = document.querySelector('.swiper-wrapper');
            
        // Récupérer tous les formulaires à l'intérieur du swiper-wrapper
        const forms = swiperWrapper.querySelectorAll('form');
            
        // Pour chaque formulaire, créer une div avec la classe 'swiper-slide' et y insérer le formulaire
        forms.forEach(form => {
          const swiperSlide = document.createElement('div');  // Créer une div
          swiperSlide.classList.add('swiper-slide');  // Ajouter la classe 'swiper-slide'
        
          // Déplacer le formulaire dans la nouvelle div
          swiperSlide.appendChild(form);
        
          // Ajouter la div contenant le formulaire dans le swiper-wrapper
          swiperWrapper.appendChild(swiperSlide);
        });
      
        // Initialiser Swiper après avoir enveloppé les formulaires dans des divs
        const swiper = new Swiper('.swiper-container', {
          loop: true,
          slidesPerView: 1,
          spaceBetween: 10,
          autoplay: {
            delay: 3000,
          },
          navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
          },
          pagination: {
            el: '.swiper-pagination',
            clickable: true,
          },
        });
      });
      
    </script>
</body>
</html>