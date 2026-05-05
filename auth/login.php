<?php
include '../config.php';

$data = json_decode(file_get_contents("php://input"));

if (empty($data->email) || empty($data->mot_de_passe)) {
    sendResponse(false, "Email et mot de passe requis");
}

$query = "SELECT * FROM utilisateurs WHERE email = :email AND mot_de_passe = :pass";
$stmt = $conn->prepare($query);
$stmt->bindParam(":email", $data->email);
$stmt->bindParam(":pass", $data->mot_de_passe);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user['statut'] === 'bloque') {
        sendResponse(false, "Votre compte est bloqué");
    }
    
    unset($user['mot_de_passe']);
    sendResponse(true, "Connexion réussie", $user);
} else {
    sendResponse(false, "Email ou mot de passe incorrect");
}
?>