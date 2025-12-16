<?php
require '../../config/config.php';
require '../json_response.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(["status" => "erro", "mensagem" => "Método inválido"]);
}

$idUsuario = intval($_POST['idUsuario'] ?? 0);
$idLivro = intval($_POST['idLivro'] ?? 0);

// Verificar se o livro está disponível
$stmt = $pdo->prepare("SELECT status FROM Livro WHERE idLivro = ?");
$stmt->execute([$idLivro]);
$livro = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$livro || $livro['status'] !== 'disponivel') {
    jsonResponse(["status" => "erro", "mensagem" => "Livro indisponível"]);
}

// Inserir empréstimo
$stmt = $pdo->prepare("
    INSERT INTO Emprestimo (idUsuario, idLivro, dataEmprestimo, dataDevolucaoPrevista)
    VALUES (?, ?, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 7 DAY))
");
$stmt->execute([$idUsuario, $idLivro]);

// Atualizar status do livro
$pdo->prepare("UPDATE Livro SET status='emprestado' WHERE idLivro=?")->execute([$idLivro]);

jsonResponse(["status" => "ok"]);
