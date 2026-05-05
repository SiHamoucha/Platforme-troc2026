<?php
include '../config.php';

$query = "SELECT a.*, r.nom as nom_rubrique, u.nom as nom_user, u.prenom as prenom_user, u.ville
          FROM annonces a
          JOIN rubriques r ON a.id_rubrique = r.id
          JOIN utilisateurs u ON a.id_utilisateur = u.id
          WHERE a.statut = 'valide'
          ORDER BY a.date_creation DESC";

$stmt = $conn->prepare($query);
$stmt->execute();
$annonces = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($annonces);
?>