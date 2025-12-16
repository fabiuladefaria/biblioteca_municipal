<?php
require '../../config/config.php';
require '../json_response.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(["status" => "erro", "mensagem" => "Método inválido"]);
}

$id = intval($_POST['idUsuario'] ?? 0);
$senhaAtual = trim($_POST['senhaAtual'] ?? '');
$novaSenha = trim($_POST['novaSenha'] ?? '');

$stmt = $pdo->prepare("SELECT senhaHash FROM Usuario WHERE idUsuario = ?");
$stmt->execute([$id]);
$dados = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$dados || !password_verify($senhaAtual, $dados["senhaHash"])) {
    jsonResponse(["status" => "erro", "mensagem" => "Senha atual incorreta"]);
}

$novaHash = password_hash($novaSenha, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("UPDATE Usuario SET senhaHash = ? WHERE idUsuario = ?");
$stmt->execute([$novaHash, $id]);

jsonResponse(["status" => "ok"]);
