<?php
include '../config.php';

$data = json_decode(file_get_contents("php://input"));

if (empty($data->id) || empty($data->statut)) {
    sendResponse(false, "Données incomplètes");
}

$query = "UPDATE utilisateurs SET statut = :statut WHERE id = :id AND role != 'admin'";
$stmt = $conn->prepare($query);
$stmt->bindParam(":id", $data->id);
$stmt->bindParam(":statut", $data->statut);

if ($stmt->execute()) {
    $message = $data->statut === 'bloque' ? "Utilisateur bloqué" : "Utilisateur débloqué";
    sendResponse(true, $message);
} else {
    sendResponse(false, "Erreur");
}
?>