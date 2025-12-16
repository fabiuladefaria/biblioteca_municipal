<?php
require '../../config/config.php';
require '../json_response.php';

$id = intval($_POST['idUsuario'] ?? 0);

if (!isset($_FILES['foto'])) {
    jsonResponse(["status" => "erro", "mensagem" => "Nenhuma imagem enviada"]);
}

$ext = strtolower(pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION));
if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
    jsonResponse(["status" => "erro", "mensagem" => "Formato inválido"]);
}

$dir = "../../uploads/fotos_perfil/";
if (!is_dir($dir)) mkdir($dir, 0777, true);

$nomeFoto = "perfil_" . $id . "_" . time() . "." . $ext;
$caminho = $dir . $nomeFoto;

move_uploaded_file($_FILES["foto"]["tmp_name"], $caminho);

// salva no banco
$urlSalva = "uploads/fotos_perfil/" . $nomeFoto;

$stmt = $pdo->prepare("UPDATE Usuario SET fotoUrl = ? WHERE idUsuario = ?");
$stmt->execute([$urlSalva, $id]);

jsonResponse(["status" => "ok", "fotoUrl" => $urlSalva]);
