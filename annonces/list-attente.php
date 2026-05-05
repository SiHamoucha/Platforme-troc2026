<?php
include '../config.php';

$query = "SELECT a.*, r.nom as nom_rubrique, u.nom as nom_user, u.prenom as prenom_user, u.email as email_user
          FROM annonces a
          JOIN rubriques r ON a.id_rubrique = r.id
          JOIN utilisateurs u ON a.id_utilisateur = u.id
          WHERE a.statut = 'en_attente'
          ORDER BY a.date_creation ASC";

$stmt = $conn->prepare($query);
$stmt->execute();

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
?>