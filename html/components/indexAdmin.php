<div class="indexAdmin">
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

    <details>
      <summary>
        Demande de suppression d'offre
      </summary>
      <ul>
        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
          <?php print_r($row); ?>
            <li>
              <div>
                <img src="<?= "." . $row["url"] ?>" alt="<?= $row["nom"] ?>" title="<?= $row["nom"] ?>">
                <div>
                  <h3><?= $row["nom"] ?></h3>
                  <h4>Proposé par <?= $row["denomination"] ?></h4>
                </div>
              </div>
              <form action="../ajax/manageAdminOffer.php" method="post">
                <input type="submit" value="Visualiser">
                <input type="submit" value="Rejeter">
                <input type="submit" value="Supprimer">
                <input type="hidden" name="idoffre" value="<?= $row["idoffre"] ?>">
              </form>
            </li>
          <?php } ?>
      </ul>
    </details>

    <!-- Signalement -->
    <details>
      <summary>
        Signalement
      </summary>
      <ul>
        <li>
          <div>
            <figure>
              <img src="" alt="" title="">
              <figcaption>Nom de l'offre</figcaption>
            </figure>
            <span>27/03/2025 - 14:42</span>
          </div>
          <div>
            <h4>Raison : </h4>
            <p>Complement ...</p>
          </div>
        </li>
      </ul>
    </details>
  </section>
</div>