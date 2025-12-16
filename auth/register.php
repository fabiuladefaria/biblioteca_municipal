<?php
require '../config/config.php';

$error = null;
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $confirmar = $_POST['confirmar'];

    $telefone = trim($_POST['telefone']);
    $endereco = trim($_POST['endereco']);
    $cidade = trim($_POST['cidade']);
    $estado = trim($_POST['estado']);
    $cep = trim($_POST['cep']);
    $dataNascimento = $_POST['dataNascimento'];

    $fotoUrl = null;

    if ($senha !== $confirmar) {
        $error = "As senhas não conferem.";
    } else {

        $stmt = $pdo->prepare("SELECT * FROM Usuario WHERE email=?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Email já cadastrado.";
        } else {

            if (!empty($_FILES['foto']['name'])) {
                $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
                $nomeFoto = uniqid('user_') . "." . $ext;

                if (!is_dir('../uploads/fotos_leitores')) {
                    mkdir('../uploads/fotos_leitores', 0777, true);
                }

                move_uploaded_file($_FILES['foto']['tmp_name'], "../uploads/fotos_leitores/" . $nomeFoto);
                $fotoUrl = "uploads/fotos_leitores/" . $nomeFoto;
            }

            $hash = password_hash($senha, PASSWORD_DEFAULT);

            $sql = "INSERT INTO Usuario (
                nome, email, senhaHash, tipo, telefone, endereco, cidade, estado, cep, dataNascimento, fotoUrl
            ) VALUES (?,?,?,?,?,?,?,?,?,?,?)";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $nome, $email, $hash, 'usuario', 
                $telefone, $endereco, $cidade, $estado, $cep, $dataNascimento, $fotoUrl
            ]);

            $success = "Conta criada com sucesso! Você já pode fazer login.";
        }
    }
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Criar Conta</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
        background-color: #e9f7ef;
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    }

    .card-register {
        max-width: 800px;
        margin: 40px auto;
        padding: 35px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    }

    h2 {
        color: #0F5132;
        font-weight: bold;
    }

    .btn-back {
        background-color: #0F5132;
        color: white;
    }

    .btn-back:hover {
        background-color: #0c4128;
        color: white;
    }

    .btn-green {
        background-color: #198754;
        color: white;
    }

    .btn-green:hover {
        background-color: #146c43;
        color: white;
    }
</style>

</head>
<body>

<!-- VOLTAR -->
<div class="p-3">
    <a href="../index.php" class="btn btn-back">&larr; Voltar</a>
</div>

<div class="card-register">

    <h2 class="text-center mb-4">Criar Conta</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <a href="login_usuario.php" class="btn btn-primary">Ir para Login</a>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">

        <div class="row g-3">

            <div class="col-md-6">
                <label>Nome Completo:</label>
                <input type="text" name="nome" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label>Email:</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label>Senha:</label>
                <input type="password" name="senha" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label>Confirmar Senha:</label>
                <input type="password" name="confirmar" class="form-control" required>
            </div>

            <div class="col-md-4">
                <label>Telefone:</label>
                <input type="text" name="telefone" class="form-control">
            </div>

            <div class="col-md-8">
                <label>Endereço:</label>
                <input type="text" name="endereco" class="form-control">
            </div>

            <div class="col-md-4">
                <label>Cidade:</label>
                <input type="text" name="cidade" class="form-control">
            </div>

            <div class="col-md-2">
                <label>Estado:</label>
                <input type="text" name="estado" maxlength="2" class="form-control">
            </div>

            <div class="col-md-3">
                <label>CEP:</label>
                <input type="text" name="cep" class="form-control">
            </div>

            <div class="col-md-3">
                <label>Data de Nascimento:</label>
                <input type="date" name="dataNascimento" class="form-control">
            </div>

            <div class="col-md-12">
                <label>Foto de Perfil:</label>
                <input type="file" name="foto" class="form-control">
            </div>

        </div>

        <button class="btn btn-green mt-4">Criar Conta</button>
        <a href="login_usuario.php" class="btn btn-link">Já tenho conta</a>
    </form>

</div>

</body>
</html>
