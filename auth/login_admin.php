<?php
require '../config/config.php';

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim(strtolower($_POST['email']));

    $senha = $_POST['senha'];

    $stmt = $pdo->prepare("SELECT * FROM Usuario WHERE email=? AND tipo='admin'");
    $stmt->execute([$email]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($senha, $admin['senhaHash'])) {

        $_SESSION['idUsuario'] = $admin['idUsuario'];
        $_SESSION['nome'] = $admin['nome'];
        $_SESSION['tipo'] = "admin";

        header("Location: ../admin/index.php");
        exit;

    } else {
        $error = "Email ou senha incorretos!";
    }
}
?>

<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<title>Login do Administrador</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
        background-color: #e9f7ef;
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    }

    .login-card {
        max-width: 420px;
        margin: 80px auto;
        padding: 35px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    }

    .btn-green {
        background-color:  rgba(0, 85, 165, 0.75);
        color: white;
    }

    .btn-green:hover {
        background-color: rgba(15, 8, 151, 0.97);
        color: white;
    }

    .btn-back {
        background-color: rgba(0, 85, 165, 0.75);
        color: white;
    }

    .btn-back:hover {
        background-color: rgba(15, 8, 151, 0.97);
        color: white;
    }

    h2 {
        color: #1741b6ff;
        font-weight: bold;
    }
</style>

</head>
<body>

<!-- BOTÃO VOLTAR -->
<div class="p-3">
    <a href="../index.php" class="btn btn-back">&larr; Voltar</a>
</div>

<div class="login-card">

    <h2 class="text-center mb-4">Login Administrador</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger text-center">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="post">

        <div class="mb-3">
            <label>Email:</label>
            <input type="email" name="email" class="form-control" required/>
        </div>

        <div class="mb-3">
            <label>Senha:</label>
            <input type="password" name="senha" class="form-control" required/>
        </div>

        <button class="btn btn-green w-100 mt-2">Entrar</button>
    </form>

</div>

</body>
</html>
