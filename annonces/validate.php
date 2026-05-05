<?php
include '../config.php';

$data = json_decode(file_get_contents("php://input"));

if (empty($data->id) || empty($data->statut)) {
    sendResponse(false, "Données incomplètes");
}

if (!in_array($data->statut, ['valide', 'refuse'])) {
    sendResponse(false, "Statut invalide");
}

$query = "UPDATE annonces SET statut = :statut WHERE id = :id";
$stmt = $conn->prepare($query);
$stmt->bindParam(":id", $data->id);
$stmt->bindParam(":statut", $data->statut);

if ($stmt->execute()) {
    $message = $data->statut === 'valide' ? "Annonce validée" : "Annonce refusée";
    sendResponse(true, $message);
} else {
    sendResponse(false, "Erreur");
}
?>