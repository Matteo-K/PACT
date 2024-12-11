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
  <title>PACT</title>
</head>
<body>
  <script src="js/setColor.js"></script>
  <?php require_once "components/header.php"; ?>
  <main>
    <div id="index">
      <div id="imgPhare">
        <div id="ALaUne">
          <?php if ($typeUser == "pro_public" || $typeUser == "pro_prive") { ?>
            <h2>Vos offres</h2>
          <?php } ?>
          <div>
            <?php 
            $elementStart = 0;
            $nbElement = 20;
            $offres = new ArrayOffer();
            $offres->displayCardALaUne($offres->filtre($idUser, $typeUser), $typeUser, $elementStart, $nbElement);
            ?>
          </div>
        </div>
        <div>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Dolorem obcaecati voluptate quae, molestias tempore ad sapiente eos dolore fugit nihil tempora voluptatibus? Nobis, est doloremque? Ullam facilis illo eaque sint.
        Corrupti autem voluptas voluptates praesentium eligendi quas modi eum excepturi debitis saepe! Hic vitae fugit itaque quo exercitationem odit quod tenetur iure accusantium nisi. Hic incidunt cumque autem repellendus alias.
        Enim hic cumque aliquam repellendus debitis vero, accusamus nisi nam rerum unde! Ex adipisci hic tempore sequi quae dolor alias natus. Voluptatibus deserunt culpa sint minima doloremque quo rerum ratione.
        Quibusdam minus ab odio suscipit quisquam necessitatibus id ipsa obcaecati et praesentium, placeat qui officia impedit dolore voluptates blanditiis omnis dolorum deleniti nesciunt! Molestiae quos minus, perspiciatis labore impedit doloribus?
        Quae laborum, debitis eaque ab laudantium beatae repellat excepturi sit, temporibus illum saepe accusamus, blanditiis aliquid iusto nesciunt quod libero delectus? Consequuntur cupiditate obcaecati dolores iste eaque necessitatibus corporis itaque?
        Soluta quis optio reprehenderit, alias, non officiis itaque dolor vero omnis quo neque iste distinctio provident debitis ea, impedit aliquam vel tenetur saepe laborum aspernatur. Saepe unde minima ratione numquam.
        Cumque beatae rerum facilis. Sapiente libero quae dolores nulla accusantium ducimus commodi aliquam odio facilis ipsa iure facere non nam similique impedit, amet numquam voluptatum praesentium ea deleniti possimus recusandae!
        Suscipit cupiditate neque numquam libero rerum voluptatum? Laboriosam atque maxime vel impedit repellendus soluta, laudantium officia nostrum quisquam recusandae sit maiores sequi. Minus, at qui blanditiis dolores dolor officia culpa.
        Magnam magni praesentium aspernatur unde excepturi hic quasi repellat, asperiores nemo perspiciatis fugiat dignissimos inventore tenetur quod natus laborum corrupti impedit, rem autem sapiente? Cum officia fugiat a voluptates natus.
        Neque harum exercitationem atque sit perspiciatis provident repellendus necessitatibus at est, nihil, ipsum corrupti eaque inventore nam animi. Dignissimos, doloremque expedita magni quam ullam aliquid debitis doloribus! Soluta, consequatur excepturi.
        Voluptatum alias, tenetur, commodi amet quisquam quod beatae minima recusandae optio ipsam quis consectetur quos, praesentium consequatur. Commodi deleniti id facilis mollitia veniam impedit corporis molestias tenetur autem at. Quis.
        Laborum incidunt dolorum, impedit excepturi laboriosam in accusamus iste sequi blanditiis reiciendis minima? Totam, doloremque facilis. Excepturi, animi? Dolore, rerum aut debitis dolores nesciunt neque adipisci! Possimus non error ullam.
        Illum animi magnam sint nihil hic eos eius excepturi laborum minima optio nobis molestias sed sequi odio, accusamus quaerat ipsam quod voluptas pariatur architecto soluta sapiente quae quos aut. Rerum.
        Quasi error facilis blanditiis distinctio alias modi obcaecati, sit enim provident consequatur ex! Numquam, nesciunt quidem nulla quos eveniet possimus adipisci accusamus totam quas explicabo, deserunt non tenetur voluptates doloribus.
        Quia, voluptate quod atque praesentium sit dolores mollitia neque omnis. Labore ut pariatur esse possimus sequi repellendus et, saepe sed inventore, non ab? Optio laudantium sapiente et quasi nisi cum.
        Provident atque aut culpa ab vitae quasi, cupiditate non, maiores placeat quos repudiandae aliquid a possimus vel commodi necessitatibus quis eveniet? Rem, eos excepturi. Cupiditate corrupti vitae quasi ducimus officiis.
        Odio doloremque ullam aut, magnam esse, error quos tempore maiores magni dolorem eveniet consectetur quasi. Alias eaque, eligendi deserunt culpa magnam nostrum libero in dolor tenetur quibusdam soluta cupiditate iusto.
        Accusamus, exercitationem. Deserunt, placeat accusamus. Dolores eveniet adipisci, iste, rerum nostrum quidem dignissimos, in molestias eaque facere veritatis! Porro laborum inventore consequuntur eligendi? Delectus voluptates voluptatum esse eos sit quo?
        Suscipit voluptate atque saepe culpa vel eaque. Incidunt laborum, vel accusamus inventore unde consequatur impedit numquam. Provident dolore quidem modi illum delectus dolor exercitationem veniam vero omnis quis, optio in.
        Alias expedita quis temporibus perferendis officiis dolor, commodi maiores consequuntur vitae eaque nisi odio vero eos illum magnam tempore quam cumque error ipsam veniam veritatis deleniti. Deserunt ducimus nam dolore.
        Aspernatur libero facere ut in numquam ipsam quia odit, perspiciatis laboriosam earum amet? Tempora aliquam quod, soluta ex dolore sit tempore praesentium quae, aperiam quia consequatur quis exercitationem laudantium ullam?
        Voluptates aspernatur amet vel architecto nam. Inventore, dolor dolorum sequi doloribus quo, eos omnis eum consequatur amet ad, temporibus autem rem porro? Rem minus doloribus sint asperiores ullam. Eaque, assumenda.
        Id et magnam blanditiis quo eos libero. Enim iste perspiciatis officia mollitia. Perferendis veniam reiciendis libero odio voluptatem qui nemo doloribus minus sint? Impedit libero eligendi illo tempora? Veritatis, ipsa?
        At, atque. Dolorum enim harum, earum, in dolorem fugit vitae perspiciatis iusto fuga accusamus qui modi. Odit delectus quia error possimus, facere neque temporibus, consequuntur perferendis quae adipisci dolorem molestiae.
        Suscipit voluptas, quas est quos itaque deleniti nostrum! Doloribus quo necessitatibus beatae rerum eveniet minima tempora laudantium recusandae accusantium porro totam ipsam officiis, provident culpa sit dignissimos neque amet fugit.
        Doloribus sint, nulla quis magni porro placeat enim unde aliquam in veritatis totam numquam possimus libero dolore perferendis quam eum deleniti molestias expedita, optio ea distinctio voluptates aut quos! Culpa.
        Ab modi ipsam soluta porro quas aliquid, optio cupiditate quia, deserunt, architecto molestiae at distinctio! Possimus cum nulla recusandae consectetur nesciunt rerum veniam. Minus quia, quos perferendis a quidem in?
        Facilis, quae voluptates dicta eligendi reiciendis debitis magnam esse asperiores enim itaque atque eaque laborum sequi similique minima, sed iure praesentium necessitatibus harum sit perferendis vitae amet ullam. Perspiciatis, eaque?
        Dolore vel cupiditate, et doloremque, labore repellendus, enim officiis quibusdam asperiores explicabo nihil consectetur praesentium architecto! Architecto, quibusdam. Libero adipisci magnam nobis repellat voluptates, possimus dolore veritatis dolorum molestiae accusamus.
        Harum voluptate magnam vitae ipsum consequatur sed aperiam corporis? Perspiciatis consequatur temporibus officia ad laborum nihil, repellendus numquam praesentium. Optio cumque dicta quam repellat labore aperiam excepturi reprehenderit eum aliquam.
        Reprehenderit illum eaque est nulla qui harum cupiditate assumenda minus totam facere voluptatum ex architecto molestias voluptatem consequuntur eius odio explicabo porro dolorem iure, praesentium corrupti! Cumque molestiae sequi corrupti!
        Fugit, sed. Magni perspiciatis amet eius possimus! Eveniet nulla deleniti neque ea placeat amet ducimus. Cumque a perspiciatis laboriosam perferendis recusandae ullam, maiores eveniet aperiam nisi. Vel sit fuga recusandae.
        Quaerat laboriosam adipisci quibusdam magni nihil. Impedit sapiente ipsum laudantium numquam ad repudiandae quae, officia quidem, rerum veritatis, pariatur velit! Dignissimos vitae dolorem culpa quaerat iure eligendi inventore modi? Sint.
        Laborum sint veniam tempore amet labore explicabo, deleniti quos eos! Totam, nisi doloribus? Natus temporibus ipsam, repellendus laborum quia tenetur exercitationem nihil, quibusdam veritatis et perspiciatis. Atque reprehenderit nostrum alias.
        Fugit autem nisi eum beatae deleniti dolore architecto inventore, ratione officia ipsa corporis in debitis! Libero quibusdam impedit placeat reprehenderit voluptatum? Quod quaerat dolorem esse fuga expedita, aperiam mollitia veritatis.
        Sunt officia commodi ipsa voluptatem ad id? Debitis beatae odio quidem? Quibusdam vero aliquam fugit nulla laboriosam adipisci nostrum quam id odio. Nostrum nam cumque sint sequi quod pariatur doloribus?
        Sunt enim vero omnis modi magni iure ad doloremque sit quia voluptate, voluptates aliquam laborum ex debitis animi fugit repellat sapiente veniam illo tempore nemo vitae iste. Soluta, alias perspiciatis!
        Itaque aliquam minus enim atque repellendus, molestiae quas libero modi voluptatum consequuntur quos tempore placeat, repellat ipsam? Ab, consequuntur omnis. Numquam sunt repellat expedita quos, iure accusantium enim cum tenetur.
        Natus et quis ipsam, minima doloremque error aspernatur sapiente, sequi mollitia impedit tempora. Ut, numquam rem! Ad ab est, animi facere temporibus rem, ipsam sit sequi sint magnam doloremque? Architecto?
        Fuga, aliquid fugiat libero, mollitia ipsam atque hic eligendi incidunt laborum voluptatibus quia perspiciatis necessitatibus! Suscipit in impedit cum vitae quaerat sit, error laboriosam rerum excepturi? Exercitationem totam aperiam aspernatur.
        Optio accusantium vitae suscipit cum repellendus nemo iste impedit eos alias excepturi adipisci quasi, iusto tenetur. Corrupti voluptas officia, labore tempore architecto, quisquam commodi quo reiciendis atque laborum similique dolor?
        Hic aliquid quia voluptas? Magnam dolores quos ducimus repudiandae, amet tenetur pariatur nulla itaque rem, velit laudantium molestias quo minima tempora quod. Corporis facilis id, odio omnis sapiente impedit temporibus.
        Provident totam quibusdam eos optio quidem quam doloremque quasi, possimus voluptatibus dolorum corrupti molestias expedita assumenda esse harum aliquid deleniti error quos delectus dolor minima molestiae ducimus reiciendis illum. Voluptatibus!
        Praesentium in animi natus porro mollitia cumque aspernatur debitis sed illum quos. Accusamus fugiat perferendis veritatis aspernatur, eos, consectetur eius et impedit qui molestias perspiciatis mollitia nihil excepturi cum asperiores?
        Quae asperiores corporis enim nam dolorem quo eius nemo architecto at, fuga, maiores autem! Maiores eaque accusantium iste a amet! Ab ipsa at mollitia inventore error cupiditate laudantium a molestias.
        Nobis voluptate quos aperiam? Fugiat error facere, corrupti omnis ducimus ratione distinctio sapiente cumque provident quasi itaque mollitia. Suscipit ea, placeat fuga impedit amet molestiae obcaecati doloremque id quis officiis!
        Nemo fugiat eius quod fugit amet mollitia, magnam ab. Debitis laboriosam qui quibusdam mollitia sit suscipit quis repellendus! Sit perferendis eos modi, vero ipsum eaque cum mollitia rem. Incidunt, minus!
        Sequi, impedit ipsum vitae dignissimos a error, est doloremque omnis laudantium, iste quibusdam ipsa blanditiis? A dolorem libero molestiae dolor hic velit facilis corporis doloribus similique quasi, cum obcaecati ipsam!
        Distinctio odit alias provident animi molestiae et aliquid autem debitis earum dolores eum maxime maiores rerum vero, labore minima doloremque eligendi, exercitationem quidem optio. Id, quasi quisquam. Sunt, laborum nulla!
        Error numquam neque quasi iure delectus recusandae sequi accusamus laboriosam sunt eaque sed, corrupti aspernatur. Minima tenetur delectus maiores, quasi, dolorum ab recusandae consequatur, fugit et quod aliquid eum porro.</div>
        <div id="voirPlus">
          <?php if ($typeUser == "pro_public" || $typeUser == "pro_prive") { ?>
            <a href="manageOffer.php" class="modifierBut">Créer une offre</a>  
          <?php } ?>
          <a href="search.php" class="modifierBut">Voir plus</a>
        </div>
        <?php if ($typeUser == "membre") {
          $stmt = $conn->prepare("SELECT idoffre FROM pact._consulter where idu = ? and dateconsultation = CURRENT_DATE;");
          $stmt->execute([$_SESSION['idUser']]);
          $idOffres = [];
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $idOffres[] = $row['idoffre'];
        } ?>
        <div id="consultationRecente">
          <h2>Consulté récemment</h2>
          <div>
            <?php if (count($idOffres) > 0) {
              $nbElement = 20;
              $consultRecent = new ArrayOffer($idOffres);
              $consultRecent->displayConsulteRecemment($nbElement);
              ?>
            <?php } else { ?>
              <p>Aucune offre consultée récemment</p>
            <?php } ?>
          </div>
        </div>
        <?php } ?>
      </div>
    </div>
      <!-- Partie de recherche -->
    <div id="searchIndex" class="search">
      <?php 
      require_once "components/asideTriFiltre.php";

      $offres = new ArrayOffer();
      ?>
      <section class="searchoffre">
      </section>
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
</body>
</html>