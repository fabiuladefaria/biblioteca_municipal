<?php
require '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: esqueci.php");
    exit;
}

$email = trim($_POST['email']);

// Verifica se existe
$stmt = $pdo->prepare("SELECT * FROM Usuario WHERE email = ? AND tipo='usuario'");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Email não encontrado!");
}

// Gerar token seguro
$token = bin2hex(random_bytes(32));

// Salvar token no banco
$upd = $pdo->prepare("UPDATE Usuario SET tokenRecuperacao=? WHERE idUsuario=?");
$upd->execute([$token, $user['idUsuario']]);

// Link de redefinição
$link = "http://localhost/biblioteca_admin/auth/resetar.php?token=$token";

// Exibir o link (versão simples sem email)
echo "
<div style='padding:20px;font-family:Arial'>
    <h2>Link para redefinir senha</h2>
    <p>Copie e cole no navegador:</p>
    <p><strong>$link</strong></p>
    <br>
    <a href='login_usuario.php'>Voltar ao login</a>
</div>
";
