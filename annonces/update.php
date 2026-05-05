<?php
include '../config.php';

$data = json_decode(file_get_contents("php://input"));

if (empty($data->id) || empty($data->titre) || empty($data->description)) {
    sendResponse(false, "Données incomplètes");
}

$query = "UPDATE annonces 
          SET titre = :titre, description = :desc, id_rubrique = :rubrique, 
              etat_objet = :etat, echange_souhaite = :echange, statut = 'en_attente'
          WHERE id = :id AND id_utilisateur = :userId";

$stmt = $conn->prepare($query);
$stmt->bindParam(":id", $data->id);
$stmt->bindParam(":userId", $data->id_utilisateur);
$stmt->bindParam(":titre", $data->titre);
$stmt->bindParam(":desc", $data->description);
$stmt->bindParam(":rubrique", $data->id_rubrique);
$stmt->bindParam(":etat", $data->etat_objet);
$echange = isset($data->echange_souhaite) ? $data->echange_souhaite : null;
$stmt->bindParam(":echange", $echange);

if ($stmt->execute() && $stmt->rowCount() > 0) {
    sendResponse(true, "Annonce modifiée avec succès");
} else {
    sendResponse(false, "Erreur ou aucune modification");
}
?>