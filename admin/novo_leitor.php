<?php
require '../config/config.php';
if (!isAdmin()) { die("Acesso negado."); }

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
    $senha = $_POST['senha'];
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
    $tipo = "usuario";

    $fotoUrl = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        if (in_array($ext,['jpg','jpeg','png'])) {
            $nomeFoto = uniqid('leitor_') . "." . $ext;
            if (!is_dir('../uploads/fotos_leitores')) mkdir('../uploads/fotos_leitores', 0777, true);
            move_uploaded_file($_FILES['foto']['tmp_name'], __DIR__.'/../uploads/fotos_leitores/'.$nomeFoto);
            $fotoUrl = 'uploads/fotos_leitores/'.$nomeFoto;
        }
    }

    $stmt = $pdo->prepare("
        INSERT INTO Usuario (nome,email,senhaHash,tipo,telefone,endereco,bairro,cidade,estado,cep,dataNascimento,fotoUrl)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?)
    ");
    $stmt->execute([$nome,$email,$senhaHash,$tipo,$telefone,$endereco,$bairro,$cidade,$estado,$cep,$dataNascimento,$fotoUrl]);
    header('Location: leitores.php');
    exit;
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Novo Leitor</title>
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"></head>
<body class="container py-4">
    <h2>Novo Leitor</h2>
    <form method="post" enctype="multipart/form-data" class="row g-3">
        <div class="col-md-6"><label>Nome</label><input name="nome" class="form-control" required></div>
        <div class="col-md-6"><label>Email</label><input name="email" type="email" class="form-control" required></div>
        <div class="col-md-4"><label>Telefone</label><input name="telefone" class="form-control"></div>
        <div class="col-md-4"><label>Data de Nascimento</label><input name="dataNascimento" type="date" class="form-control"></div>
      <div class="col-12"><label>Endereço</label><input name="endereco" class="form-control"></div>
        <div class="col-md-4"><label>Bairro</label><input name="bairro" class="form-control"></div>
        <div class="col-md-4"><label>Cidade</label><input name="cidade" class="form-control"></div>
        <div class="col-md-2"><label>Estado</label><input name="estado" maxlength="2" class="form-control"></div>
        <div class="col-md-2"><label>CEP</label><input name="cep" class="form-control"></div>
        <div class="col-md-6"><label>Foto do Leitor</label><input type="file" name="foto" accept="image/*" class="form-control"></div>
        <div class="col-12"><button class="btn btn-primary">Salvar</button> <a href="leitores.php" class="btn btn-secondary">Cancelar</a></div>
    </form>
</body></html>
