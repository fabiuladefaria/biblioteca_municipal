<?php
require '../../config/config.php';
require '../json_response.php';

$sql = "SELECT DISTINCT categoria 
        FROM Livro 
        WHERE categoria IS NOT NULL 
        AND categoria <> '' 
        ORDER BY categoria ASC";

$stmt = $pdo->query($sql);

$cats = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $cats[] = $row['categoria'];
}

jsonResponse($cats);
