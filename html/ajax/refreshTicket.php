<?php
require_once "../config.php";

// Récupérer et décoder les données JSON envoyées
$data = json_decode(file_get_contents("php://input"), true);
$idOffre = $data['idoffre'] ?? null;
$action = $data['action'] ?? null;

if ($idOffre === null) {
    echo json_encode(["error" => "ID offre manquant"]);
    exit;
}

if ($action === "nbTicket") {
    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM pact._blacklist WHERE idOffre = ? AND datefinblacklist >= CURRENT_TIMESTAMP");
    $stmt->execute([$idOffre]);
    $result = $stmt->fetch();

    $nbTicket = max(0, 3 - (int)$result['count']); // Empêche les valeurs négatives

    echo json_encode(["count" => $nbTicket]);
} elseif ($action === "duree") {
    $stmt = $conn->prepare("SELECT datefinblacklist FROM pact._blacklist WHERE idOffre = ? AND datefinblacklist >= CURRENT_TIMESTAMP");
    $stmt->execute([$idOffre]);
    $dates = $stmt->fetchAll(PDO::FETCH_COLUMN); // Récupère uniquement la colonne `datefinblacklist`

    echo json_encode(["dates" => $dates]);
} elseif ($action === "note") {
    $stmt = $conn->prepare("SELECT a.*,
    ROUND(AVG(a.note) OVER(), 1) AS moynote,
    COUNT(a.note) OVER() AS nbnote,
    SUM(CASE WHEN a.note = 1 THEN 1 ELSE 0 END) OVER() AS note_1,
    SUM(CASE WHEN a.note = 2 THEN 1 ELSE 0 END) OVER() AS note_2,
    SUM(CASE WHEN a.note = 3 THEN 1 ELSE 0 END) OVER() AS note_3,
    SUM(CASE WHEN a.note = 4 THEN 1 ELSE 0 END) OVER() AS note_4,
    SUM(CASE WHEN a.note = 5 THEN 1 ELSE 0 END) OVER() AS note_5,
    SUM(CASE WHEN a.lu = FALSE then 1 else 0 end) over() as avisnonlus,
	SUM(CASE WHEN r.idc_reponse is null then 1 else 0 end) over() as avisnonrepondus,
    m.url AS membre_url,
    m.idu,
    r.idc_reponse,
    r.denomination AS reponse_denomination,
    r.contenureponse,
    r.nblikepro as likereponse,
    r.nbdislikepro as dislikereponse,
    r.reponsedate,
    r.idpro
FROM 
    pact.avis a
JOIN 
    pact.membre m ON m.pseudo = a.pseudo
LEFT JOIN 
    pact.reponse r ON r.idc_avis = a.idc
WHERE 
    a.idoffre = ? and a.blacklist = false
ORDER BY 
    a.datepublie desc");
    $stmt->execute([$idOffre],);
    $notes = $stmt->fetchAll();

    echo json_encode(["notes" => $notes]);
} else {
    echo json_encode(["error" => "Action invalide"]);
}
?>
