<?php
require '../config/config.php';
if (!isAdmin()) { die("Acesso negado."); }

$leitores = $pdo->query("SELECT * FROM Usuario WHERE tipo='usuario' ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Leitores</title>
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"></head>
<body class="container py-4">
    <h2>Leitores</h2>
    <a href="novo_leitor.php" class="btn btn-success mb-3">Novo Leitor</a>
    <a href="index.php" class="btn btn-secondary mb-3">Voltar</a>
    <table class="table table-striped">
        <thead><tr><th>ID</th><th>Nome</th><th>Email</th><th>Telefone</th><th>Cidade</th><th>Ações</th></tr></thead>
        <tbody>
            <?php foreach($leitores as $u): ?>
            <tr>
                <td><?=$u['idUsuario']?></td>
                <td><?=e($u['nome'])?></td>
                <td><?=e($u['email'])?></td>
                <td><?=e($u['telefone'])?></td>
                <td><?=e($u['cidade'])?></td>
                <td>
                    <a href="editar_leitor.php?id=<?=$u['idUsuario']?>" class="btn btn-sm btn-primary">Editar</a>
                    <a href="../ficha_leitor.php?id=<?=$u['idUsuario']?>" class="btn btn-sm btn-info">Ficha</a>
 <a href="excluir_leitor.php?id=<?=$u['idUsuario']?>" class="btn btn-sm btn-danger" onclick="return confirm('Excluir leitor?')">Excluir</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body></html>
