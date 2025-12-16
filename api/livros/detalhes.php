<?php
require '../../config/config.php';
require '../json_response.php';

$id = intval($_GET['id'] ?? 0);

$stmt = $pdo->prepare("
    SELECT * FROM Livro WHERE idLivro = ?
");
$stmt->execute([$id]);

$livro = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$livro) {
    jsonResponse(["status" => "erro", "mensagem" => "Livro não encontrado"]);
}

jsonResponse($livro);
