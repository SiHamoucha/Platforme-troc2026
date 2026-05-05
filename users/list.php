<?php
include '../config.php';

$query = "SELECT id, nom, prenom, email, telephone, ville, role, statut, date_inscription
          FROM utilisateurs
          ORDER BY date_inscription DESC";

$stmt = $conn->prepare($query);
$stmt->execute();

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
?>