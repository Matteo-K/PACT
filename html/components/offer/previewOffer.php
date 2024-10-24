<form id="previewOffer" action="enregOffer.php" method="post">
    <?php
    require_once "config.php";

    // Vérifiez si l'idOffre est défini
    if (!$idOffre) {
        header("Location: index.php");
        exit();
    }

    // Fonction pour récupérer les détails de l'offre
    function getOfferDetails($conn, $idOffre) {
        $stmt = $conn->prepare("SELECT * FROM pact.offres WHERE idOffre = :idOffre");
        $stmt->bindParam(':idOffre', $idOffre, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Récupérer les détails de l'offre
    $offerDetails = getOfferDetails($conn, $idOffre);

    if (!$offerDetails) {
        echo "Aucune offre trouvée avec cet ID.<br>";
        exit();
    }

    // Récupérer les horaires
    $schedules = getSchedules($conn, $idOffre); // Assurez-vous que cette fonction existe

    // Récupérer les photos associées à l'offre
    $stmt = $conn->prepare("SELECT * FROM pact._illustre WHERE idOffre = :idOffre");
    $stmt->bindParam(':idOffre', $idOffre);
    $stmt->execute();
    $photos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <section id="previewOfferSection">
        <h2>Prévisualisation de l'Offre : <?php echo htmlspecialchars($offerDetails['nomOffre']); ?></h2>

        <div class="offer-details">
            <h3>Détails de l'Offre</h3>
            <p><strong>Nom :</strong> <?php echo htmlspecialchars($offerDetails['nomOffre']); ?></p>
            <p><strong>Description :</strong> <?php echo nl2br(htmlspecialchars($offerDetails['description'])); ?></p>
            <p><strong>Prix :</strong> <?php echo htmlspecialchars($offerDetails['prix']); ?> €</p>
            <p><strong>Type :</strong> <?php echo htmlspecialchars($offerDetails['type']); ?></p>
        </div>

        <div class="offer-schedules">
            <h3>Horaires</h3>
            <table>
                <thead>
                    <tr>
                        <th>Jour</th>
                        <th>Horaires</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($schedules['midi'] as $horaire) {
                        echo "<tr>
                                <td>" . htmlspecialchars($horaire['jour']) . "</td>
                                <td>" . htmlspecialchars($horaire['heureouverture']) . " à " . htmlspecialchars($horaire['heurefermeture']) . "</td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="offer-photos">
            <h3>Photos de l'Offre</h3>
            <div class="swiper-container">
                <div class="swiper mySwiper">
                    <div class="swiper-wrapper">
                    <?php
                        foreach ($photos as $picture) {
                            echo "<div class='swiper-slide'><img src='" . htmlspecialchars($picture['url']) . "' alt='Image de l\'offre'/></div>";
                        }
                    ?>
                    </div>
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>

        <div class="offer-actions">
            <button type="button" onclick="window.history.back();" class="blueBtnOffer">Modifier</button>
            <button type="submit" class="guideSelect">Finaliser</button>
        </div>
    </section>
</form>
