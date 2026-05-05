<?php
include '../config.php';

$id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$id) {
    sendResponse(false, "ID requis");
}

// Vérifier s'il y a des annonces
$check = $conn->prepare("SELECT COUNT(*) as count FROM annonces WHERE id_rubrique = :id");
$check->bindParam(":id", $id);
$check->execute();
$result = $check->fetch(PDO::FETCH_ASSOC);

if ($result['count'] > 0) {
    sendResponse(false, "Impossible : des annonces utilisent cette rubrique");
}

$query = "DELETE FROM rubriques WHERE id = :id";
$stmt = $conn->prepare($query);
$stmt->bindParam(":id", $id);

if ($stmt->execute()) {
    sendResponse(true, "Rubrique supprimée");
} else {
    sendResponse(false, "Erreur");
}
?>