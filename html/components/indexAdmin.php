<div id="indexAdmin">
  <aside>
    <!-- Menu latérale -->
    <article>
      <h2>Paramètres</h2>
      <?php
        $stmt = $conn->prepare("SELECT dureeblacklistage, uniteblacklist from pact._parametre WHERE id=true;");
        $stmt->execute();
        $row = $stmt->fetch();
        $duree = $row["dureeblacklistage"];
        $unite = $row["uniteblacklist"];
      ?>
      <form action="ajax/duree_blacklist.php" method="post" id="form_duree_blacklist">
        <fieldset class="taille7">
          <legend>Récupération du blacklistage <span id="blacklist_update" class="updateForm"></span></legend>
          <div>
            <input type="number" name="duree_blacklist" id="duree_blacklist" value="<?= $duree ?>">
            <select name="intervall_blacklist" id="intervall_blacklist">
              <option value="minutes" <?= $unite === "minutes" ? "selected" : "" ?>>minutes</option>
              <option value="heures" <?= $unite === "heures" ? "selected" : "" ?>>heures</option>
              <option value="jours" <?= $unite === "jours" ? "selected" : "" ?>>jours</option>
            </select>
          </div>
          <input type="submit" value="confirmer" class="modifierBut">
        </fieldset>
      </form>
      <script>
        const form_blacklist = document.getElementById("form_duree_blacklist");
        const resLabel = document.getElementById("res_duree_blacklist");
        const dureeInput = document.getElementById("duree_blacklist");
        const intervallInput = document.getElementById("intervall_blacklist");
        const backlist_update = document.getElementById("blacklist_update");
        let dureeBefore = dureeInput.value.trim();
        let intervalBefore = intervallInput.value.trim();

        function checkDuree() {
          const duree = dureeInput.value.trim();

          checkDiff(duree);

          if (!/^\d+$/.test(duree) || parseInt(duree) < 1) {
              resLabel.textContent = "Veuillez entrer une durée valide (chiffre positif).";
              resLabel.style.color = "red";
              return false;
          }
          return true;
        }

        function checkDiff(duree) {
          if (duree !== dureeBefore || checkSelect()) {
            backlist_update.textContent = "*";
          } else {
            backlist_update.textContent = "";
          }
        }

        function checkSelect() {
          return intervallInput.value != intervalBefore;
        }
        
        intervallInput.addEventListener("change", () => checkDiff(dureeInput.value.trim()));
        dureeInput.addEventListener("change", () => checkDuree());
        form_blacklist.addEventListener("submit", (event) => {
          event.preventDefault();
          if (checkDuree()) {
            form_blacklist.submit();
            backlist_update.textContent = "";
          }
        });

      </script>
    </article>
  </aside>
  <!-- Bloc principale -->
  <section>
    <!-- Suppression d'offre -->

    <?php
      $stmt = $conn->prepare(
        "SELECT o.idoffre, o.nom, p.denomination, i.url
        FROM pact.offres o
        LEFT JOIN pact._pro p ON p.idu = o.idu
        LEFT JOIN (
            SELECT idoffre, MIN(url) AS url
            FROM pact._illustre
              GROUP BY idoffre
        ) i ON i.idoffre = o.idoffre
        WHERE o.statut = 'delete';"
      );
      $stmt->execute();
    ?>

    <details class="details-style" open>
      <summary>
        Demande de suppression d'offre
      </summary>
      <div class="details-content">
        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
          <div class="details-form">
            <div>
              <img src="<?= "." . $row["url"] ?>" alt="<?= $row["nom"] ?>" title="<?= $row["nom"] ?>">
              <div class="nomSupOffre">
                <h3><?= $row["nom"] ?></h3>
                <h4>Proposé par <?= $row["denomination"] ?></h4>
              </div>
            </div>
            <form action="../ajax/manageAdminOffer.php" method="post">
              <button type="submit" name="action" value="visualiser" class="modifierBut">Visualiser</button>
              <button type="submit" name="action" value="rejeter" class="modifierBut">Rejeter</button>
              <button type="submit" name="action" value="supprimer" class="modifierBut">Supprimer</button>
              <input type="hidden" name="idoffre" value="<?= $row["idoffre"] ?>">
            </form>
          </div>
        <?php } ?>
      </div>
    </details>
    <?php
      function signalRaison($raisonSignal) {
        switch ($raisonSignal) {
          case 'innaproprie':
            $raison = "Contenu inapproprié (injures, menaces, contenu explicite...)";
            break;

          case 'frauduleux':
            $raison = "Avis frauduleux ou trompeur (faux avis, publicité déguisée...)";
            break;

          case 'spam':
            $raison = "Spam ou contenu hors-sujet (multipostage, indésirable...)";
            break;

          case 'violation':
            $raison = "Violation des règles de la plateforme (vie privée, données seneibles...)";
            break;
          
          default:
            $raison = "Raison : " . $raisonSignal;
            break;
        }
        return $raison;
      }
    ?>
    <!-- Signalement -->
    <details class="details-style" open>
      <summary>
        Signalement
      </summary>
      <div class="details-content">
        <?php
          $stmt = $conn->prepare(
            "SELECT 
              s.dtsignalement, s.raison, s.complement, s.idu, s.idc,
              a.pseudo, a.idoffre,
              pp.url,
              o.nom
            FROM pact._signalementc s
            LEFT JOIN pact.avis a ON s.idc=a.idc
            LEFT JOIN pact._photo_profil pp ON pp.idu = a.idu
            LEFT JOIN pact._offre o ON o.idoffre = a.idoffre
            WHERE a.pseudo IS NOT NULL
            ORDER BY a.idc, a.idoffre;"
          );
          $stmt->execute();
        ?>
        <details class="details-style" open>
          <summary>
            Avis membre
          </summary>
          <div class="details-content">
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
              <div class="details-form signalAdmin">
                <div>
                  <figure>
                    <img src="<?= $row["url"] ?>" alt="<?= $row["pseudo"] ?>" title="<?= $row["pseudo"] ?>">
                    <figcaption><?= $row["pseudo"] ?></figcaption>
                  </figure>
                  <span><?= date("j/m/y - H:m"); ?></span>
                </div>
                <div class="contenueSignalement">
                  <h4><?= signalRaison($row["raison"]) ?></h4>
                  <p><?= $row["complement"] ?></p>
                </div>
                <form action="../ajax/actionSignalement.php" method="post">
                  <button type="submit" name="action" value="visualiser" class="modifierBut">Voir dans son contexte</button>
                  <button type="submit" name="action" value="rejeter" class="modifierBut">Rejeter</button>
                  <button type="submit" name="action" value="supprimer" class="modifierBut">Supprimer</button>
                  <input type="hidden" name="idoffre" value="<?= $row["idoffre"] ?>">
                  <input type="hidden" name="idavis" value="<?= $row["idc"] ?>">
                  <input type="hidden" name="signaleur" value="<?= $row["idu"] ?>">
                  <input type="hidden" name="type" value="reponse">
                </form>
              </div>
            <?php } ?>
          </div>
        </details>
        <?php
          $stmt = $conn->prepare(
            "SELECT 
              s.dtsignalement, s.raison, s.complement, s.idu,
              r.denomination, r.idoffre, r.idc_avis,
              pp.url,
              o.nom
            FROM pact._signalementc s
            LEFT JOIN pact.reponse r ON s.idc=r.idc_reponse
            LEFT JOIN pact._photo_profil pp ON pp.idu = r.idpro
            LEFT JOIN pact._offre o ON o.idoffre = r.idoffre
            WHERE r.denomination IS NOT NULL
            ORDER BY r.idc_reponse, r.idoffre;"
          );
          $stmt->execute();
        ?>
        <details class="details-style">
          <summary>
            Réponse professionnel
          </summary>
          <div class="details-content">
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
              <div class="details-form signalAdmin">
                <div>
                  <figure>
                    <img src="<?= $row["url"] ?>" alt="<?= $row["denomination"] ?>" title="<?= $row["denomination"] ?>">
                    <figcaption><?= $row["denomination"] ?></figcaption>
                  </figure>
                  <span><?= date("j/m/y - H:m"); ?></span>
                </div>
                <div class="contenueSignalement">
                  <h4><?= signalRaison($row["raison"]) ?></h4>
                  <p><?= $row["complement"] ?></p>
                </div>
                <form action="../ajax/actionSignalement.php" method="post">
                  <button type="submit" name="action" value="visualiser" class="modifierBut">Voir dans son contexte</button>
                  <button type="submit" name="action" value="rejeter" class="modifierBut">Rejeter</button>
                  <button type="submit" name="action" value="supprimer" class="modifierBut">Supprimer</button>
                  <input type="hidden" name="idoffre" value="<?= $row["idoffre"] ?>">
                  <input type="hidden" name="idavis" value="<?= $row["idc_avis"] ?>">
                  <input type="hidden" name="signaleur" value="<?= $row["idu"] ?>">
                  <input type="hidden" name="type" value="avis">
                </form>
              </div>
            <?php } ?>
          </div>
        </details>
      </div>
    </details>
  </section>
</div>