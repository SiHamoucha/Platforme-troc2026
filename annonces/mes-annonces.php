<?php
include '../config.php';

$userId = isset($_GET['userId']) ? $_GET['userId'] : null;

if (!$userId) {
    sendResponse(false, "ID utilisateur requis");
}

$query = "SELECT a.*, r.nom as nom_rubrique
          FROM annonces a
          JOIN rubriques r ON a.id_rubrique = r.id
          WHERE a.id_utilisateur = :userId
          ORDER BY a.date_creation DESC";

$stmt = $conn->prepare($query);
$stmt->bindParam(":userId", $userId);
$stmt->execute();

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
?>