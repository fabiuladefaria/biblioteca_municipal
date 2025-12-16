<?php
require '../config/config.php';

if (!isUsuario()) die("Acesso negado.");

$id = $_SESSION['idUsuario'];

// Buscar todos os empréstimos do usuário
$stmt = $pdo->prepare("
    SELECT e.*, l.titulo 
    FROM Emprestimo e
    JOIN Livro l ON e.idLivro = l.idLivro
    WHERE e.idUsuario = ?
    ORDER BY e.dataEmprestimo DESC
");
$stmt->execute([$id]);
$lista = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<title>Meus Empréstimos</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body {
    background-color: #e9f7ef;
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

h2 {
    color:rgba(4, 41, 77, 1);
    font-weight: bold;
}

.btn-back {
    background-color:rgba(4, 41, 77, 1);
    color: white;
}

.btn-back:hover {
    background-color:rgba(19, 82, 146, 1);
    color: white;
}

.table thead {
    background-color: #198754;
    color: white;
}

.badge-ativo {
    background-color: #198754;
}

.badge-devolvido {
    background-color: #6c757d;
}

.card-box {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
}

</style>
</head>

<body class="container py-4">

<a href="painel.php" class="btn btn-back mb-3">&larr; Voltar</a>

<h2 class="mb-4">Meus Empréstimos</h2>

<div class="card-box">

<table class="table table-striped align-middle">
<thead>
    <tr>
        <th>Livro</th>
        <th>Data Empréstimo</th>
        <th>Previsto</th>
        <th>Status</th>
        <th>Devolvido em</th>
    </tr>
</thead>

<tbody>
<?php foreach ($lista as $e): ?>

<?php
    $ativo = empty($e['dataDevolucaoReal']);
?>

<tr>
    <td><strong><?= htmlspecialchars($e['titulo']) ?></strong></td>

    <td><?= $e['dataEmprestimo'] ?></td>

    <td><?= $e['dataDevolucaoPrevista'] ?></td>

    <td>
        <?php if ($ativo): ?>
            <span class="badge badge-ativo">Ativo</span>
        <?php else: ?>
            <span class="badge badge-devolvido">Finalizado</span>
        <?php endif; ?>
    </td>

    <td>
        <?= $e['dataDevolucaoReal'] ?: '-' ?>
    </td>

</tr>

<?php endforeach; ?>
</tbody>

</table>

</div>

</body>
</html>
