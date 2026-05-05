<?php
include '../config.php';

$data = json_decode(file_get_contents("php://input"));

if (empty($data->nom)) {
    sendResponse(false, "Nom de rubrique requis");
}

// Vérifier si existe déjà
$check = $conn->prepare("SELECT id FROM rubriques WHERE nom = :nom");
$check->bindParam(":nom", $data->nom);
$check->execute();

if ($check->rowCount() > 0) {
    sendResponse(false, "Cette rubrique existe déjà");
}

$query = "INSERT INTO rubriques (nom, description) VALUES (:nom, :desc)";
$stmt = $conn->prepare($query);
$stmt->bindParam(":nom", $data->nom);
$desc = isset($data->description) ? $data->description : null;
$stmt->bindParam(":desc", $desc);

if ($stmt->execute()) {
    sendResponse(true, "Rubrique créée");
} else {
    sendResponse(false, "Erreur");
}
?>