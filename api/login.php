<?php
require '../config/config.php';
require 'json_response.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(["status" => "erro", "mensagem" => "Método inválido"]);
}

$email = trim($_POST['email'] ?? '');
$senha = trim($_POST['senha'] ?? '');

if ($email === '' || $senha === '') {
    jsonResponse(["status" => "erro", "mensagem" => "Email e senha obrigatórios"]);
}
$stmt = $pdo->prepare("SELECT idUsuario, nome, email, senhaHash, tipo FROM Usuario WHERE email = ?");

$stmt->execute([$email]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario || !password_verify($senha, $usuario['senhaHash'])) {
    jsonResponse(["status" => "erro", "mensagem" => "Credenciais inválidas"]);
}

if ($usuario['tipo'] !== 'usuario') {
    jsonResponse(["status" => "erro", "mensagem" => "Somente leitores podem acessar"]);
}

jsonResponse([
    "status" => "ok",
    "idUsuario" => $usuario['idUsuario'],
    "nome" => $usuario['nome'],
    "email" => $usuario['email']   
]);
