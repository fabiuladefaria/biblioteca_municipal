<?php
require 'config/config.php';
if (!isAdmin()) { die("Acesso negado."); }

$id = intval($_GET['id']);
$stmt = $pdo->prepare("SELECT * FROM Usuario WHERE idUsuario = ? AND tipo = 'usuario'");
$stmt->execute([$id]);
$u = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$u) { header("Location: admin/leitores.php"); exit; }

$historico = $pdo->prepare("
    SELECT e.*, l.titulo FROM Emprestimo e
    JOIN Livro l ON e.idLivro = l.idLivro
    WHERE e.idUsuario = ?
    ORDER BY e.dataEmprestimo DESC
");
$historico->execute([$id]);
$hist = $historico->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Ficha Leitor</title>
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"></head>
<body class="container py-4">
    <a href="admin/leitores.php" class="btn btn-sm btn-secondary mb-3">Voltar</a>
    <div class="row">
        <div class="col-md-4">
            <div class="card p-3">
                <?php if ($u['fotoUrl']): ?>
                    <img src="<?= e($u['fotoUrl']) ?>" class="img-fluid mb-3">
                <?php else: ?>
                    <img src="assets/img/user-placeholder.png" class="img-fluid mb-3">
                <?php endif; ?>
                <h4><?= e($u['nome']) ?></h4>
                <p><strong>Email:</strong> <?= e($u['email']) ?></p>
                <p><strong>Telefone:</strong> <?= e($u['telefone']) ?></p>
                <p><strong>Endereço:</strong> <?= e($u['endereco']) ?>, <?= e($u['cidade']) ?></p>
            </div>
        </div>
        <div class="col-md-8">
            <h4>Histórico de Empréstimos</h4>
            <table class="table">
                <thead><tr><th>Livro</th><th>Emprestado</th><th>Previsto</th><th>Devolvido</th></tr></thead>
                <tbody>
                    <?php foreach($hist as $h): ?>
                        <tr>
                            <td><?= e($h['titulo']) ?></td>
                            <td><?= e($h['dataEmprestimo']) ?></td>
                            <td><?= e($h['dataDevolucaoPrevista']) ?></td>
                            <td><?= $h['dataDevolucaoReal'] ? e($h['dataDevolucaoReal']) : '-' ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body></html>
