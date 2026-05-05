<?php
include '../config.php';

$id = isset($_GET['id']) ? $_GET['id'] : null;
$userId = isset($_GET['userId']) ? $_GET['userId'] : null;

if (!$id || !$userId) {
    sendResponse(false, "Paramètres manquants");
}

$query = "DELETE FROM annonces WHERE id = :id AND id_utilisateur = :userId";
$stmt = $conn->prepare($query);
$stmt->bindParam(":id", $id);
$stmt->bindParam(":userId", $userId);

if ($stmt->execute() && $stmt->rowCount() > 0) {
    sendResponse(true, "Annonce supprimée");
} else {
    sendResponse(false, "Erreur ou annonce introuvable");
}
?>