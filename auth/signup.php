<?php
include '../config.php';

$data = json_decode(file_get_contents("php://input"));

if (empty($data->nom) || empty($data->prenom) || empty($data->email) || empty($data->mot_de_passe)) {
    sendResponse(false, "Tous les champs sont requis");
}

// Vérifier si email existe
$check = $conn->prepare("SELECT id FROM utilisateurs WHERE email = :email");
$check->bindParam(":email", $data->email);
$check->execute();

if ($check->rowCount() > 0) {
    sendResponse(false, "Cet email est déjà utilisé");
}

$query = "INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, telephone, ville, role) 
          VALUES (:nom, :prenom, :email, :pass, :tel, :ville, 'troqueur')";

$stmt = $conn->prepare($query);
$stmt->bindParam(":nom", $data->nom);
$stmt->bindParam(":prenom", $data->prenom);
$stmt->bindParam(":email", $data->email);
$stmt->bindParam(":pass", $data->mot_de_passe);

$tel = isset($data->telephone) ? $data->telephone : null;
$stmt->bindParam(":tel", $tel);

$ville = isset($data->ville) ? $data->ville : null;
$stmt->bindParam(":ville", $ville);

if ($stmt->execute()) {
    sendResponse(true, "Inscription réussie ! Vous pouvez maintenant vous connecter");
} else {
    sendResponse(false, "Erreur lors de l'inscription");
}
?>