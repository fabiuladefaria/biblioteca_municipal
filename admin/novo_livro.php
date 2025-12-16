<?php
require '../config/config.php';
if (!isAdmin()) { die("Acesso negado."); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $autor = $_POST['autor'];
    $descricao = $_POST['descricao'];
    $categoria = $_POST['categoria'];
    $capaUrl = null;

    if (isset($_FILES['capa']) && $_FILES['capa']['error'] == 0) {
        $ext = strtolower(pathinfo($_FILES['capa']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg','jpeg','png'])) {
            $nome = uniqid('capa_') . '.' . $ext;
            if (!is_dir('../uploads/capas_livros')) mkdir('../uploads/capas_livros', 0777, true);
            move_uploaded_file($_FILES['capa']['tmp_name'], __DIR__.'/../uploads/capas_livros/'.$nome);
            $capaUrl = 'uploads/capas_livros/'.$nome;
        }
    } else if (!empty($_POST['capaUrl'])) {
        $capaUrl = $_POST['capaUrl'];
    }

    $stmt = $pdo->prepare("INSERT INTO Livro(titulo,autor,descricao,categoria,status,capaUrl) VALUES(?,?,?,?,?,?)");
    $stmt->execute([$titulo,$autor,$descricao,$categoria,'disponivel',$capaUrl]);
    header('Location: index.php');
    exit;
}
?>
<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<title>Novo Livro</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

<style>
/* ======================== LAYOUT PREMIUM – NOVO LIVRO ======================== */

body {
    background-color: #eef1f5;
    font-family: "Inter", "Segoe UI", Tahoma, sans-serif;
    padding-bottom: 40px;
}

/* CARD PRINCIPAL */
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

/* VOLTAR */
.btn-back {
    background: transparent;
    border: none;
    color:  rgba(0, 85, 165, 0.75);
    font-weight: 600;
    font-size: 17px;
    margin-bottom: 15px;
    display: inline-block;
}
.btn-back:hover {
    text-decoration: underline;
}

/* TÍTULO */
h2 {
    color:  rgba(0, 85, 165, 0.75);
    font-weight: 800;
    font-size: 32px;
    letter-spacing: -0.5px;
    margin-bottom: 35px;
}

/* LABEL */
label {
    font-weight: 700;
    color:  rgba(4, 13, 22, 0.75);
    margin-bottom: 6px;
}

/* INPUTS */
input.form-control,
textarea.form-control,
select.form-select {
    border-radius: 14px;
    border: 1px solid #cfd7df;
    background-color: #fbfdfd;
    padding: 12px 14px;
    transition: 0.25s ease;
}

textarea.form-control {
    height: 140px;
}

input.form-control:hover,
textarea.form-control:hover,
select.form-select:hover {
    border-color: rgba(0, 85, 165, 0.75);
}

input.form-control:focus,
textarea.form-control:focus,
select.form-select:focus {
    border-color:  rgba(0, 85, 165, 0.75) !important;
    box-shadow: 0 0 0 4px rgba(25,135,84,0.20) !important;
}

/* BOTÕES */
.btn-save {
    background-color:  rgba(0, 85, 165, 0.75);
    border: none;
    color: white;
    border-radius: 14px;
    padding: 12px 34px;
    font-weight: 700;
    font-size: 17px;
    transition: 0.25s;
}
.btn-save:hover {
    background-color:  rgba(6, 28, 49, 0.75);
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

/* INPUT FILE */
input[type="file"]::-webkit-file-upload-button {
    background-color:  rgba(0, 85, 165, 0.75);
    color: white;
    border-radius: 10px;
    padding: 8px 18px;
    border: none;
    transition: 0.25s ease;
    font-weight: 600;
}

input[type="file"]::-webkit-file-upload-button:hover {
    background-color: rgba(0, 85, 165, 0.75);
}

/* BOTÃO VOLTAR PREMIUM */
.btn-back-premium {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: #ffffff;
    color:  rgba(7, 28, 48, 0.75);
    font-weight: 700;
    border-radius: 14px;
    padding: 12px 22px;
    border: 2px solid  rgba(0, 85, 165, 0.75);
    text-decoration: none;
    font-size: 16px;
    box-shadow: 0px 4px 12px rgba(0,0,0,0.08);
    transition: 0.25s ease;
    position: fixed;
    bottom: 25px;
    left: 25px;
    z-index: 999;
}

.btn-back-premium:hover {
    background:  rgba(9, 41, 71, 0.75);
    color: white;
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0, 85, 165, 0.75);
}

.btn-back-premium .icon {
    font-size: 20px;
    font-weight: 900;
}
a{
    text-decoration: none;
}

</style>

</head>

<body>

<div class="container">
    

    <div class="card-edit">

        <h2 class="text-center">Novo Livro</h2>

        <form method="post" enctype="multipart/form-data" class="row g-4">

            <div class="col-md-6">
                <label>Título</label>
                <input name="titulo" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label>Autor</label>
                <input name="autor" class="form-control">
            </div>

            <div class="col-12">
                <label>Descrição</label>
                <textarea name="descricao" class="form-control"></textarea>
            </div>

            <div class="col-md-4">
                <label>Categoria</label>
                <select name="categoria" class="form-select" required>
                    <option value="">Selecione...</option>
                    <option>Romance</option>
                    <option>Ficção</option>
                    <option>Terror</option>
                    <option>Aventura</option>
                    <option>Fantasia</option>
                    <option>Suspense</option>
                    <option>Drama</option>
                    <option>Infantil</option>
                </select>
            </div>

            <div class="col-md-4">
                <label>Upload da capa</label>
                <input type="file" name="capa" class="form-control">
            </div>

            <div class="col-md-4">
                <label>Ou URL da capa</label>
                <input name="capaUrl" class="form-control">
            </div>

            <div class="col-12 text-end">
                <button class="btn-save">Salvar</button>
                <a href="index.php" class="btn-cancel ms-2">Cancelar</a>
            </div>
        </form>

    </div>
</div>
<a href="index.php" class="btn-back-premium">
    <span class="icon">←</span>
    Voltar ao Painel
</a>
</body>
</html>
