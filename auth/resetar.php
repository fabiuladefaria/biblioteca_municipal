<?php
require '../config/config.php';

if (!isset($_GET['token'])) {
    die("Token inválido.");
}

$token = $_GET['token'];

// Verificar token
$stmt = $pdo->prepare("SELECT * FROM Usuario WHERE tokenRecuperacao = ?");
$stmt->execute([$token]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Token inválido ou expirado.");
}

// Salvar nova senha
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $senhaHash = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    $upd = $pdo->prepare("UPDATE Usuario SET senhaHash=?, tokenRecuperacao=NULL WHERE idUsuario=?");
    $upd->execute([$senhaHash, $user['idUsuario']]);

    echo "<script>alert('Senha redefinida com sucesso!'); window.location='login_usuario.php';</script>";
    exit;
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Redefinir Senha</title>
<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'>
</head>
<body class="bg-light p-5">

<div class="container">
    <div class="card p-4 mx-auto" style="max-width:420px">

        <h3 class="text-center mb-3">Redefinir Senha</h3>

        <form method="POST">

            <label>Nova senha:</label>
            <input type="password" name="senha" class="form-control mb-3" required>

            <button class="btn btn-success w-100">Salvar nova senha</button>
        </form>

    </div>
</div>

</body>
</html>
