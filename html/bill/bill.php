<?php



require_once "../config.php";


$idOffre = $_POST['idOffre'];
$annee = $_POST['annee'];
$date = $_POST['date'];
$mois = $_POST['mois'];
$boole = $_POST['boole']=="true" ? true : false;

$stmt = $conn->prepare("SELECT * FROM pact.facture WHERE idoffre = :idOffre AND datefactue = :datefactue");

// Bind the parameters to the placeholders
$stmt->bindParam(':idOffre', $idOffre, PDO::PARAM_INT);
$stmt->bindParam(':datefactue', $date, PDO::PARAM_STR);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$denomination = $results[0]['denomination'];
$denominationL = $results[0]['nom'];
$rue = $results[0]['rue'];
$codePostal = $results[0]['codepostal'];
$ville = $results[0]['ville'];
$rueL = $results[0]['ruel'];
$codePostalL = $results[0]['codepostall'];
$villeL = $results[0]['villel'];
$idFacture = $results[0]['idfacture'];
$dateFacture = $results[0]['datefactue'];

$newDate = New DateTime($dateFacture);
$newDate = $newDate->format('d/m/Y');

$idU = $results[0]['idu'];
$tva = 20;
if ($results[0]['historiqueoption']) {
    $option = explode(';',$results[0]['historiqueoption']);
    $resultat=[];
    foreach ($option as $key => $value) {
        $resultat[] = json_decode($value,true);
    }
}

// "duree": 1, "option": "ALaUne", "prixBase": 20, "dureeBase": 7, "lancement": "2024-11-25"}

$tarif=['option'=>$results[0]['nomabonnement'],'prixBase'=>$results[0]['tarif']];
$v3=$results[0]['tarif'];

// {"ID": 1, "Duree": 6, "Lancement": "2024-11-01"};{"ID": 2, "Duree": null, "Lancement": "2024-11-15"}
$nbEnLigne = 0 ;
if ($results[0]['historiquestatut']) {
    $abonnement = explode(';',$results[0]['historiquestatut']);
    foreach ($abonnement as $key => $value) {
        $result = json_decode($value,true);
    $nbEnLigne = $nbEnLigne + intval($result['Duree']);
    }
}


$css = "
p{
    margin: 0;
}

header strong{
    text-align: right;
}
h1{
    text-align: center;
    background-color: lightgray;
    margin: 0;
    padding: 10px;
}

h2{
    text-align: center;
    background-color: lightgray;
    margin: 0;
    padding: 10px;
    font-size: 2rem;
}

table{
    width: 100%;
    margin-top: 10px;
    margin-bottom: 10px;
    border: 2px solid #000;
    border-collapse: collapse;
}

td{
    text-align: center;
}
th{
    background-color: lightgray;
}
#v3,#v5{
    width: 70px;
}

th,td{
    border: 1px solid #000;
    padding: 10px;
}
aside{
    text-align: right;
}

footer{
    position: absolute;
    bottom: 0;
    right: 0;
    text-align: right;
    padding: 10px;
}

#ouep{
    margin-bottom: 1em;
}
";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture</title>
    <style>
        <?php echo $css ?>
    </style>
</head>
<body>
    <header>
        <section>
            <p>Pact TripEnArvor</p>
            <p>Rue Edouard Branly</p>
            <p>22300 Lannion</p>
        </section>
        <strong>
            <section>
                <p><?php echo $denomination ?></p>
                <p><?php echo $rue ?></p>
                <p><?php echo $codePostal . " " . $ville ?></p>
                <p><?php echo $denominationL ?></p>
                <p><?php echo $rueL ?></p>
                <p><?php echo $codePostalL . " " . $villeL ?></p>
            </section>
        </strong>
        <h1>Facture du mois de <?php echo $mois . " " . $annee ?></h1>
        <h2><?php echo $denominationL ?></h2>
    </header>
    <main>
        <section>
            <strong>
                <p>Numéro de facture : <?php echo $idFacture ?></p>
                <p>Date de facture : <?php echo($newDate)  ?></p>
                <p>Numéro Client : <?php echo $idU ?></p>
            </strong>
        </section>
        <aside>
            <strong>
                <p>page 1</p>
            </strong>
        </aside>
        <table>
            <thead>
                <tr>
                    <th id="v1">Description</th>
                    <th id="v2">Quantité</th>
                    <th id="v3">Unité</th>
                    <th id="v4">Prix unitaire HT</th>
                    <th id="v5">TVA</th>
                    <th id="v6">Total HT</th>
                    <th id="v7">Total TTC</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $total=$nbEnLigne*$v3;
                    if ($results[0]['historiqueoption']) {
                        
                        foreach ($resultat as $key => $value) {
                            $v1 = intval($value['duree']);
                            $v2 = ($value['prixBase']);
                            $total += $v1 * $v2;
                            ?>
                                <tr>
                                    <td><?php echo $value['option'] ?></td>
                                    <td><?php echo $value['duree'] ?></td>
                                    <td>Semaine</td>
                                    <td><?php echo $value['prixBase'] ?></td>
                                    <td><?php echo $tva ?> %</td>
                                    <td><?php echo $v1 * $v2 ?> €</td>
                                    <td><?php echo round($v1*$v2+($v1*$v2*20/100),2) ?> €</td>
                                </tr>
                            <?php
                        }
                    }
                ?>
                <tr>
                    <td>Abonnement <?php echo $tarif['option'] ?></td>
                    <td><?php echo $nbEnLigne ?></td>
                    <td>Jour</td>
                    <td><?php echo $v3 ?></td>
                    <td><?php echo $tva ?> %</td>
                    <td><?php echo $nbEnLigne*$v3 ?> €</td>
                    <td><?php echo round($nbEnLigne*$v3+($nbEnLigne*$v3*20/100),2) ?> €</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th colspan="5">Total</th>
                    <th>HT : <?php echo $total ?> €</th>
                    <th>TTC : <?php echo round($total*20/100+$total,2) ?> €</th>
                </tr>
            </tbody>
        </table>
        <p id="ouep">Condition de paiement : paiement à réception de facture</p>
        <p>Nous vous remercions de votre confiance.</p>
        <p>Cordialement</p>
    </main>
    <footer>
        <section>
            <p>Pact TripEnArvor</p>
            <p>Rue Edouard Branly</p>
            <p>Ewen Jain</p>
            <p>22300 Lannion</p>
            <p>France</p>
            <p>Tél. : (+33)6 06 06 06 06</p>
            <p>E-Mail: ewen.jain@etudiant.univ-rennes.fr</p>
        </section>
    </footer>
</body>
</html>