<?php
require '../config/config.php';
if (!isAdmin()) { die("Acesso negado."); }

$id = intval($_GET['id']);
$livro = $pdo->prepare("SELECT * FROM Livro WHERE idLivro = ?");
$livro->execute([$id]);
$l = $livro->fetch(PDO::FETCH_ASSOC);
if (!$l) { header('Location: index.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $autor = $_POST['autor'];
    $descricao = $_POST['descricao'];
    $categoria = $_POST['categoria'];
    $capaUrl = $l['capaUrl'];

    if (isset($_FILES['capa']) && $_FILES['capa']['error'] == 0) {
        $ext = strtolower(pathinfo($_FILES['capa']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg','jpeg','png'])) {
            $nome = uniqid('capa_').'.'.$ext;
            if (!is_dir('../uploads/capas_livros')) mkdir('../uploads/capas_livros', 0777, true);
            move_uploaded_file($_FILES['capa']['tmp_name'], __DIR__.'/../uploads/capas_livros/'.$nome);
            $capaUrl = 'uploads/capas_livros/'.$nome;
        }
    } else if (!empty($_POST['capaUrl'])) {
        $capaUrl = $_POST['capaUrl'];
    }

    $stmt = $pdo->prepare("UPDATE Livro SET titulo=?,autor=?,descricao=?,categoria=?,capaUrl=? WHERE idLivro=?");
    $stmt->execute([$titulo,$autor,$descricao,$categoria,$capaUrl,$id]);
    header('Location: index.php');
    exit;
}
?>
<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<title>Editar Livro</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

<style>
/* ======================== LAYOUT PREMIUM EDITAR LIVRO ======================== */

body {
    background-color: #eef1f5;
    font-family: "Inter", "Segoe UI", Tahoma, sans-serif;
}
a{
    text-decoration: none;
    
}

/* CARD */
.card-edit {
    max-width: 900px;
    margin: 55px auto;
    background: #ffffff;
    padding: 55px 60px;
    border-radius: 22px;
    border: 1px solid #dde2e8;
    box-shadow:
        0 8px 24px rgba(0,0,0,0.06),
        0 2px 8px rgba(0,0,0,0.03);
}

/* TÍTULO */
h2 {
    font-weight: 800;
    font-size: 32px;
    color: #155d41;
    letter-spacing: -0.5px;
}

/* LABEL */
label {
    font-weight: 700;
    color: #155d41;
    margin-bottom: 5px;
}

/* INPUTS E TEXTAREA */
input.form-control,
textarea.form-control,
select.form-select {
    border-radius: 14px;
    border: 1px solid #cfd7df;
    background-color: #fbfdfd;
    transition: 0.25s ease;
    padding: 12px 14px;
}

textarea.form-control {
    height: 140px;
}

input.form-control:hover,
textarea.form-control:hover,
select.form-select:hover {
    border-color: #198754;
}

input.form-control:focus,
textarea.form-control:focus,
select.form-select:focus {
    border-color: #198754 !important;
    box-shadow: 0 0 0 4px rgba(25,135,84,0.20) !important;
}

/* BOTÕES */
.btn-save {
    background-color: #198754;
    color: white;
    border-radius: 14px;
    padding: 12px 34px;
    font-size: 17px;
    font-weight: 700;
    border: none;
    transition: 0.25s ease;
}

.btn-save:hover {
    background-color: #146c43;
    box-shadow: 0px 5px 14px rgba(25,135,84,0.25);
}

.btn-cancel {
    border-radius: 12px;
    padding: 12px 30px;
    font-weight: 600;
    border: 1px solid #adb5bd;
    background: #f1f3f5;
}

.btn-cancel:hover {
    background: #e4e7ea;
}

/* CAPA PEQUENA */
.img-capa-preview {
    width: 140px;
    margin-top: 10px;
    border-radius: 14px;
    border: 2px solid #e5e7eb;
    box-shadow: 0 3px 10px rgba(0,0,0,0.10);
}

/* VOLTAR */
.btn-back {
    background: transparent;
    border: none;
    color: #155d41;
    font-weight: 600;
    font-size: 17px;
    margin-bottom: 20px;
    display: inline-block;
}

.btn-back:hover {
    text-decoration: underline;
}

</style>
</head>

<body>

<div class="container">
    <a href="index.php" class="btn-back">&larr; Voltar</a>

    <div class="card-edit">
        <h2 class="text-center mb-4">Editar Livro</h2>

        <form method="post" enctype="multipart/form-data" class="row g-4">

            <div class="col-md-6">
                <label>Título</label>
                <input name="titulo" class="form-control" required value="<?= e($l['titulo']) ?>">
            </div>

            <div class="col-md-6">
                <label>Autor</label>
                <input name="autor" class="form-control" value="<?= e($l['autor']) ?>">
            </div>

            <div class="col-12">
                <label>Descrição</label>
                <textarea name="descricao" class="form-control"><?= e($l['descricao']) ?></textarea>
            </div>

            <div class="col-md-4">
                <label>Categoria</label>
                <select name="categoria" class="form-select" required>
                    <option value="">Selecione...</option>
                    <?php 
                    $cats = ['Romance','Ficção','Terror','Aventura','Fantasia','Suspense','Drama','Infantil'];
                    foreach($cats as $c): ?>
                        <option value="<?=e($c)?>" <?=$l['categoria']==$c?'selected':''?>><?=e($c)?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-4">
                <label>Upload da capa</label>
                <input type="file" name="capa" class="form-control">
            </div>

            <div class="col-md-4">
                <label>Ou URL da capa</label>
                <input name="capaUrl" class="form-control" value="<?= e($l['capaUrl']) ?>">
            </div>

            <?php if (!empty($l['capaUrl'])): ?>
            <div class="col-12">
                <img src="../<?= e($l['capaUrl']) ?>" class="img-capa-preview">
            </div>
            <?php endif; ?>

            <div class="col-12 text-end">
                <button class="btn-save">Salvar</button>
                <a href="index.php" class="btn-cancel ms-2">Cancelar</a>
            </div>

        </form>

    </div>
</div>

</body>
</html>
