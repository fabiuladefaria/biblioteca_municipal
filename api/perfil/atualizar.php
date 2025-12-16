<?php
require '../../config/config.php';
require '../json_response.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(["status" => "erro", "mensagem" => "Método inválido"]);
}

$id = intval($_POST['idUsuario']);
$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');

if ($nome === '' || $email === '') {
    jsonResponse(["status" => "erro", "mensagem" => "Nome e email obrigatórios"]);
}

$stmt = $pdo->prepare("UPDATE Usuario SET nome = ?, email = ? WHERE idUsuario = ?");
$stmt->execute([$nome, $email, $id]);

jsonResponse(["status" => "ok"]);
