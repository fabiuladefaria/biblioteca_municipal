<?php
require '../config/config.php';
if (!isAdmin()) { die("Acesso negado."); }

$id = intval($_GET['id']);

// Verifica se leitor tem empréstimos ativos
$ativos = $pdo->prepare("SELECT COUNT(*) FROM Emprestimo WHERE idUsuario = ? AND dataDevolucaoReal IS NULL");
$ativos->execute([$id]);
$temAtivos = $ativos->fetchColumn();

if ($temAtivos > 0) {
    die("Não é possível excluir. O leitor possui empréstimos em aberto.");
}

$pdo->prepare("DELETE FROM Usuario WHERE idUsuario = ? AND tipo='usuario'")->execute([$id]);
header("Location: leitores.php");
exit;
