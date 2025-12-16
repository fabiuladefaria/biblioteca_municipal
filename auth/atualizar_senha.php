<?php
require '../config/config.php';

$token = $_POST['token'];
$senha = password_hash($_POST['senha'], PASSWORD_BCRYPT);

$stmt = $pdo->prepare("
    UPDATE Usuario 
    SET senhaHash = ?, reset_token = NULL, reset_expira = NULL
    WHERE reset_token = ? 
      AND reset_expira > NOW()
      AND tipo = 'usuario'
");
$stmt->execute([$senha, $token]);

echo "Senha alterada com sucesso! Você já pode fazer login.";
