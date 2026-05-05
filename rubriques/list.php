<?php
include '../config.php';

$query = "SELECT * FROM rubriques ORDER BY nom";
$stmt = $conn->prepare($query);
$stmt->execute();

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
?>