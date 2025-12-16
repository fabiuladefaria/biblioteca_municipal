<?php
require '../config/config.php';

if (!isAdmin()) {
    die("Acesso negado.");
}

// Validar ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID do livro não informado.");
}

$id = intval($_GET['id']);

// Excluir livro
$stmt = $pdo->prepare("DELETE FROM Livro WHERE idLivro = ?");
$ok = $stmt->execute([$id]);

if ($ok) {
    header("Location: livros.php?msg=excluido");
    exit;
} else {
    die("Erro ao excluir o livro.");
}
