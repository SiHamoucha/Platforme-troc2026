<?php
// 1. LES HEADERS DOIVENT ÊTRE ICI (AVANT TOUT LE RESTE)
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// 2. Gérer le "Preflight" (indispensable pour les requêtes complexes)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// 3. Ensuite vos inclusions et votre logique
include '../config.php';

// IMPORTANT : Avec FormData, on n'utilise PLUS json_decode. 
// On utilise directement $_POST et $_FILES.

// 1. Vérification des données textuelles obligatoires
if (empty($_POST['titre']) || empty($_POST['id_utilisateur']) || empty($_POST['id_rubrique'])) {
    sendResponse(false, "Données incomplètes");
    exit;
}

// 2. Gestion de l'upload de l'image
$nom_image = "default.jpg"; // Nom par défaut si pas d'image

if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
    $upload_dir = "../uploads/"; // Assurez-vous que ce dossier existe !
    
    // Créer le dossier s'il n'existe pas
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
    $nom_unique = uniqid('img_', true) . "." . $extension;
    $destination = $upload_dir . $nom_unique;

    if (move_uploaded_file($_FILES['photo']['tmp_name'], $destination)) {
        $nom_image = $nom_unique;
    } else {
        sendResponse(false, "Erreur lors du déplacement du fichier");
        exit;
    }
}

// 3. Insertion dans la base de données
// Note : J'ai ajouté le champ 'photo' dans la requête
$query = "INSERT INTO annonces (id_utilisateur, id_rubrique, titre, description, photo, etat_objet, echange_souhaite, statut) 
          VALUES (:user, :rubrique, :titre, :desc, :photo, :etat, :echange, 'en_attente')";

$stmt = $conn->prepare($query);

// Liaison des paramètres (on utilise $_POST maintenant)
$stmt->bindParam(":user", $_POST['id_utilisateur']);
$stmt->bindParam(":rubrique", $_POST['id_rubrique']);
$stmt->bindParam(":titre", $_POST['titre']);
$stmt->bindParam(":desc", $_POST['description']);
$stmt->bindParam(":photo", $nom_image); // On stocke le nom du fichier généré
$stmt->bindParam(":etat", $_POST['etat_objet']);

$echange = isset($_POST['echange_souhaite']) ? $_POST['echange_souhaite'] : null;
$stmt->bindParam(":echange", $echange);

if ($stmt->execute()) {
    sendResponse(true, "Annonce créée avec succès ! En attente de validation");
} else {
    // Si l'insertion échoue, on peut supprimer l'image uploadée pour ne pas encombrer le serveur
    if ($nom_image !== "default.jpg") {
        unlink($upload_dir . $nom_image);
    }
    sendResponse(false, "Erreur lors de la création en base de données");
}
?>