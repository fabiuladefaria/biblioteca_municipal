<?php
require '../config/config.php';

if (!isUsuario()) die("Acesso negado.");

$id = $_SESSION['idUsuario'];

$stmt = $pdo->prepare("SELECT * FROM Usuario WHERE idUsuario = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];
    $cep = $_POST['cep'];
    $dataNascimento = $_POST['dataNascimento'];

    $fotoUrl = $user['fotoUrl'];

    // Upload da foto
    if (!empty($_FILES['foto']['name'])) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $nomeFoto = uniqid('user_') . "." . $ext;

        if (!is_dir('../uploads/fotos_leitores')) {
            mkdir('../uploads/fotos_leitores', 0777, true);
        }

        move_uploaded_file($_FILES['foto']['tmp_name'], "../uploads/fotos_leitores/" . $nomeFoto);
        $fotoUrl = "uploads/fotos_leitores/" . $nomeFoto;
    }

    // SE ALTERAR A SENHA → FAZ LOGOUT AUTOMÁTICO
    if (!empty($_POST['senha'])) {
        $senhaHash = password_hash($_POST['senha'], PASSWORD_DEFAULT);
        $pdo->prepare("UPDATE Usuario SET senhaHash=? WHERE idUsuario=?")
            ->execute([$senhaHash, $id]);

        // Encerrar sessão para segurança
        session_destroy();
        header("Location: ../auth/login_usuario.php?senha_alterada=1");
        exit;
    }

    // Atualiza perfis
    $sql = "UPDATE Usuario SET nome=?, telefone=?, endereco=?, cidade=?, estado=?, cep=?, 
            dataNascimento=?, fotoUrl=? WHERE idUsuario=?";

    $pdo->prepare($sql)->execute([
        $nome, $telefone, $endereco, $cidade, $estado, $cep, 
        $dataNascimento, $fotoUrl, $id
    ]);

    header("Location: painel.php");
    exit;
}
?>
<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<title>Editar Perfil</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body {
    background-color: #e9f7ef;
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

.card-perfil {
    max-width: 820px;
    margin: 40px auto;
    padding: 35px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
}

h2 {
    color: rgba(4, 41, 77, 1);
    font-weight: bold;
}

.foto-perfil {
    width: 140px;
    height: 140px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid rgba(4, 41, 77, 1);
}

.btn-green {
    background-color: rgba(4, 41, 77, 1);
    color: white;
}

.btn-green:hover {
    background-color: rgba(9, 75, 141, 1);
    color: white;
}

.btn-back {
    background-color:rgba(4, 41, 77, 1);
    color: white;
}

.btn-back:hover {
    background-color:  rgba(9, 75, 141, 1);
    color: white;
}

</style>

</head>
<body>

<!-- VOLTAR -->
<div class="p-3">
    <a href="painel.php" class="btn btn-back">&larr; Voltar</a>
</div>

<div class="card-perfil">

    <h2 class="text-center mb-4">Editar Meu Perfil</h2>

    <div class="text-center mb-4">
        <img src="../<?= $user['fotoUrl'] ?: 'assets/img/user-default.png' ?>" 
             class="foto-perfil">
        <p class="mt-2"><strong><?= htmlspecialchars($user['nome']) ?></strong></p>
    </div>

    <form method="post" enctype="multipart/form-data">

        <div class="row g-3">

            <div class="col-md-6">
                <label>Nome:</label>
                <input type="text" class="form-control" name="nome" value="<?= htmlspecialchars($user['nome']) ?>" required>
            </div>

            <div class="col-md-6">
                <label>Telefone:</label>
                <input type="text" class="form-control" name="telefone" value="<?= htmlspecialchars($user['telefone']) ?>">
            </div>

            <div class="col-md-12">
                <label>Endereço:</label>
                <input type="text" class="form-control" name="endereco" value="<?= htmlspecialchars($user['endereco']) ?>">
            </div>

            <div class="col-md-4">
                <label>Cidade:</label>
                <input type="text" class="form-control" name="cidade" value="<?= htmlspecialchars($user['cidade']) ?>">
            </div>

            <div class="col-md-2">
                <label>Estado:</label>
                <input type="text" class="form-control" name="estado" maxlength="2" value="<?= htmlspecialchars($user['estado']) ?>">
            </div>

            <div class="col-md-3">
                <label>CEP:</label>
                <input type="text" class="form-control" name="cep" value="<?= htmlspecialchars($user['cep']) ?>">
            </div>

            <div class="col-md-3">
                <label>Data de Nascimento:</label>
                <input type="date" class="form-control" name="dataNascimento" 
                       value="<?= htmlspecialchars($user['dataNascimento']) ?>">
            </div>

            <div class="col-md-12">
                <label>Foto de Perfil:</label>
                <input type="file" class="form-control" name="foto">
            </div>

            <div class="col-md-12">
                <label>Alterar Senha (opcional):</label>
                <input type="password" class="form-control" name="senha" placeholder="Nova senha…">
            </div>

        </div>

        <button class="btn btn-green mt-4">Salvar Alterações</button>
    </form>

</div>

</body>
</html>
