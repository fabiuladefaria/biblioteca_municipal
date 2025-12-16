
<?php
require '../../config/config.php';
require '../json_response.php';

$idUsuario = intval($_GET['idUsuario'] ?? 0);

$stmt = $pdo->prepare("SELECT idUsuario, nome, email, fotoUrl FROM Usuario WHERE idUsuario = ?");
$stmt->execute([$idUsuario]);

$dados = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$dados) {
    jsonResponse(["status" => "erro", "mensagem" => "Usuário não encontrado"]);
}

jsonResponse(["status" => "ok", "usuario" => $dados]);
