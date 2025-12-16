<?php
require '../../config/config.php';
require '../json_response.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(["status" => "erro", "mensagem" => "Método inválido"]);
}

$idUsuario = intval($_POST['idUsuario'] ?? 0);
$idLivro   = intval($_POST['idLivro'] ?? 0);
$estrelas  = intval($_POST['estrelas'] ?? 0);
$comentario = trim($_POST['comentario'] ?? '');

if ($idUsuario == 0 || $idLivro == 0 || $estrelas < 1 || $estrelas > 5) {
    jsonResponse(["status" => "erro", "mensagem" => "Dados inválidos"]);
}

// SE já avaliou → atualiza
$stmt = $pdo->prepare("SELECT idAvaliacao FROM Avaliacao WHERE idUsuario=? AND idLivro=?");
$stmt->execute([$idUsuario, $idLivro]);

if ($stmt->fetch()) {
    $update = $pdo->prepare("
        UPDATE Avaliacao SET estrelas=?, comentario=?, dataAvaliacao=NOW()
        WHERE idUsuario=? AND idLivro=?
    ");
    $update->execute([$estrelas, $comentario, $idUsuario, $idLivro]);
} else {
    $insert = $pdo->prepare("
        INSERT INTO Avaliacao (idUsuario, idLivro, estrelas, comentario)
        VALUES (?, ?, ?, ?)
    ");
    $insert->execute([$idUsuario, $idLivro, $estrelas, $comentario]);
}

jsonResponse(["status" => "ok"]);
