<?php
require '../../config/config.php';
require '../json_response.php';

$q = trim($_GET['query'] ?? '');

$stmt = $pdo->prepare("
    SELECT idLivro, titulo, categoria, status, capaUrl
    FROM Livro
    WHERE titulo LIKE ?
    ORDER BY titulo
");
$stmt->execute(["%$q%"]);

jsonResponse($stmt->fetchAll(PDO::FETCH_ASSOC));
