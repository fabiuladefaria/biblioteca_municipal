<?php
require '../../config/config.php';
require '../json_response.php';

$idLivro = intval($_GET['idLivro'] ?? 0);
if ($idLivro == 0) jsonResponse([]);

$stmt = $pdo->prepare("
    SELECT A.*, U.nome 
    FROM Avaliacao A
    JOIN Usuario U ON U.idUsuario = A.idUsuario
    WHERE A.idLivro = ?
    ORDER BY A.dataAvaliacao DESC
");
$stmt->execute([$idLivro]);

jsonResponse($stmt->fetchAll(PDO::FETCH_ASSOC));
