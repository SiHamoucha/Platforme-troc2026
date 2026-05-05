<?php
include '../config.php';

$data = json_decode(file_get_contents("php://input"));

if (empty($data->id) || empty($data->nom) || empty($data->prenom)) {
    sendResponse(false, "Données incomplètes");
}

$query = "UPDATE utilisateurs 
          SET nom = :nom, prenom = :prenom, telephone = :tel, ville = :ville
          WHERE id = :id";

$stmt = $conn->prepare($query);
$stmt->bindParam(":id", $data->id);
$stmt->bindParam(":nom", $data->nom);
$stmt->bindParam(":prenom", $data->prenom);
$stmt->bindParam(":tel", $data->telephone);
$stmt->bindParam(":ville", $data->ville);

if ($stmt->execute()) {
    // Récupérer l'utilisateur mis à jour
    $q = "SELECT id, nom, prenom, email, telephone, ville, role, statut FROM utilisateurs WHERE id = :id";
    $s = $conn->prepare($q);
    $s->bindParam(":id", $data->id);
    $s->execute();
    sendResponse(true, "Profil mis à jour", $s->fetch(PDO::FETCH_ASSOC));
} else {
    sendResponse(false, "Erreur");
}
?>