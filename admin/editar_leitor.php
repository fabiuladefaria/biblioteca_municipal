<?php
// ====== DEBUG (opcional, pode remover depois) ======
ini_set('display_errors', 1);
error_reporting(E_ALL);

// ====== CONFIGURAÇÃO DO SISTEMA ======
require '../config/config.php';

// Função de segurança para escapar texto (se não existir)
if (!function_exists('e')) {
    function e($str) {
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }
}

// Verifica administrador
if (!isAdmin()) { 
    die("Acesso negado."); 
}

// ====== BUSCA O LEITOR PELO ID ======
$id = intval($_GET['id']);

$stmt = $pdo->prepare("SELECT * FROM Usuario WHERE idUsuario = ? AND tipo = 'usuario'");
$stmt->execute([$id]);
$leitor = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$leitor) {
    header("Location: leitores.php");
    exit;
}

// ====== SALVANDO ALTERAÇÕES ======
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];
    $bairro = $_POST['bairro'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];
    $cep = $_POST['cep'];
    $dataNascimento = $_POST['dataNascimento'];

    $fotoUrl = $leitor['fotoUrl'];

    // Upload da foto
    if (!empty($_FILES['foto']['name'])) {
        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));

        if (in_array($ext, ['jpg','jpeg','png'])) {
            $nomeFoto = uniqid('leitor_') . "." . $ext;

            if (!is_dir('../uploads/fotos_leitores')) {
                mkdir('../uploads/fotos_leitores', 0777, true);
            }

            move_uploaded_file(
                $_FILES['foto']['tmp_name'],
                __DIR__ . '/../uploads/fotos_leitores/' . $nomeFoto
            );

            $fotoUrl = 'uploads/fotos_leitores/' . $nomeFoto;
        }
    }

    // Atualiza os dados
    $stmt = $pdo->prepare("
        UPDATE Usuario SET 
        nome=?, email=?, telefone=?, endereco=?, bairro=?, cidade=?, estado=?, cep=?, 
        dataNascimento=?, fotoUrl=? 
        WHERE idUsuario=?
    ");

    $stmt->execute([
        $nome, $email, $telefone, $endereco, $bairro, $cidade, $estado, 
        $cep, $dataNascimento, $fotoUrl, $id
    ]);

    header("Location: leitores.php");
    exit;
}
?>
<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<title>Editar Leitor</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
/* ======================== LAYOUT PROFISSIONAL PREMIUM ======================== */

body {
    background-color: #eef1f5;
    font-family: "Inter", "Segoe UI", Tahoma, sans-serif;
    color: #1f2d3d;
    padding-bottom: 40px;
}

/* CARD PRINCIPAL */
.card-edit {
    max-width: 900px;
    margin: 55px auto;
    background: #ffffff;
    padding: 60px 60px;
    border-radius: 22px;
    border: 1px solid #e0e6ed;
    box-shadow:
        0 8px 24px rgba(0, 0, 0, 0.05),
        0 2px 8px rgba(0, 0, 0, 0.03);
    transition: 0.3s ease;
}

.card-edit:hover {
    box-shadow:
        0 12px 30px rgba(0, 0, 0, 0.07),
        0 4px 12px rgba(0, 0, 0, 0.04);
}

/* TÍTULO */
h2 {
    color: #155d41;
    font-weight: 800;
    font-size: 33px;
    letter-spacing: -0.8px;
    text-transform: uppercase;
    margin-bottom: 35px;
}

/* FOTO PERFIL */
.foto-leitor {
    width: 175px;
    height: 175px;
    border-radius: 22px;
    object-fit: cover;
    border: 4px solid #e3e6e9;
    background: #fff;
    box-shadow: 0 4px 14px rgba(0,0,0,0.12);
    transition: 0.25s;
}

.foto-leitor:hover {
    transform: scale(1.05);
}

/* LABELS */
label {
    font-weight: 700;
    color: #155d41;
    margin-bottom: 6px;
    font-size: 15px;
}

/* INPUTS */
input.form-control {
    border-radius: 14px;
    height: 50px;
    border: 1px solid #cfd7df;
    background-color: #fdfefe;
    padding-left: 15px;
    transition: 0.25s ease;
}

input.form-control:hover {
    border-color: #198754;
}

input.form-control:focus {
    border-color: #198754 !important;
    box-shadow: 0 0 0 4px rgba(25,135,84,0.20) !important;
}

/* BOTÃO SALVAR */
.btn-green {
    background-color: #198754;
    border: none;
    color: white;
    border-radius: 14px;
    padding: 12px 34px;
    font-weight: 700;
    font-size: 17px;
    transition: 0.25s ease;
}

.btn-green:hover {
    background-color: #146c43;
    box-shadow: 0px 5px 14px rgba(25, 135, 84, 0.25);
}

/* BOTÃO VOLTAR */
.btn-back {
    background: transparent;
    border: none;
    color: #155d41;
    font-weight: 600;
    font-size: 17px;
    transition: 0.2s;
}

.btn-back:hover {
    text-decoration: underline;
    color: #0e3c2b;
}

/* MINI FOTO */
.img-thumb-small {
    width: 140px;
    border-radius: 14px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    margin-top: 12px;
    border: 2px solid #e5e7eb;
    transition: 0.25s;
}

.img-thumb-small:hover {
    transform: scale(1.03);
}

/* INPUT FILE */
input[type="file"] {
    padding: 10px;
    cursor: pointer;
    background: #ffffff;
}

input[type="file"]::-webkit-file-upload-button {
    background-color: #198754;
    color: white;
    border: none;
    padding: 8px 18px;
    border-radius: 10px;
    cursor: pointer;
    font-weight: 600;
    transition: 0.2s;
}

input[type="file"]::-webkit-file-upload-button:hover {
    background-color: #146c43;
}


</style>
</head>

<body>

<div class="p-3">
    <a href="leitores.php" class="btn-back">&larr; Voltar</a>
</div>

<div class="card-edit">

    <h2 class="text-center mb-4">Editar Leitor</h2>

    <div class="text-center mb-4">
        <img src="../<?= $leitor['fotoUrl'] ?: 'assets/img/user-default.png' ?>" 
             class="foto-leitor">
        <p class="mt-3 fs-4 fw-bold" style="color:#1d6c4d;">
            <?= htmlspecialchars($leitor['nome']) ?>
        </p>
    </div>

    <form method="post" enctype="multipart/form-data" class="row g-4">

        <div class="col-md-6">
            <label>Nome</label>
            <input name="nome" class="form-control" required value="<?= e($leitor['nome']) ?>">
        </div>

        <div class="col-md-6">
            <label>Email</label>
            <input name="email" class="form-control" required value="<?= e($leitor['email']) ?>">
        </div>

        <div class="col-md-4">
            <label>Telefone</label>
            <input name="telefone" class="form-control" value="<?= e($leitor['telefone']) ?>">
        </div>

        <div class="col-md-4">
            <label>Data de Nascimento</label>
            <input type="date" name="dataNascimento" class="form-control" value="<?= e($leitor['dataNascimento']) ?>">
        </div>

        <div class="col-12">
            <label>Endereço</label>
            <input name="endereco" class="form-control" value="<?= e($leitor['endereco']) ?>">
        </div>

        <div class="col-md-4">
            <label>Bairro</label>
            <input name="bairro" class="form-control" value="<?= e($leitor['bairro']) ?>">
        </div>

        <div class="col-md-4">
            <label>Cidade</label>
            <input name="cidade" class="form-control" value="<?= e($leitor['cidade']) ?>">
        </div>

        <div class="col-md-2">
            <label>Estado</label>
            <input name="estado" maxlength="2" class="form-control" value="<?= e($leitor['estado']) ?>">
        </div>

        <div class="col-md-2">
            <label>CEP</label>
            <input name="cep" class="form-control" value="<?= e($leitor['cep']) ?>">
        </div>

        <div class="col-md-6">
            <label>Foto do Leitor</label>
            <input type="file" name="foto" accept="image/*" class="form-control">

            <?php if ($leitor['fotoUrl']): ?>
                <img src="../<?= e($leitor['fotoUrl']) ?>" class="img-thumb-small img-thumbnail">
            <?php endif; ?>
        </div>

        <div class="col-12 text-end">
            <button class="btn-green">Salvar</button>
            <a href="leitores.php" class="btn btn-outline-secondary rounded-3 px-4">Cancelar</a>
        </div>

    </form>

</div>

</body>
</html>
