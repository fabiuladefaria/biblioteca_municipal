<?php
require '../config/config.php';

if (!isUsuario()) {
    die("Acesso negado.");
}

$id = $_SESSION['idUsuario'];

// Buscar dados do usuário
$stmt = $pdo->prepare("SELECT * FROM Usuario WHERE idUsuario = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Buscar empréstimos ativos
$emprestimos = $pdo->prepare("
    SELECT e.*, l.titulo 
    FROM Emprestimo e 
    JOIN Livro l ON e.idLivro = l.idLivro
    WHERE e.idUsuario = ? AND e.dataDevolucaoReal IS NULL
");
$emprestimos->execute([$id]);
$ativos = $emprestimos->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<title>Painel do Leitor</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

    body {
        background-color: #e9f7ef;
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    }

    .card-painel {
        max-width: 1000px;
        margin: 40px auto;
        padding: 35px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    }

    .foto-perfil {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid  rgba(0, 85, 165, 0.75);
        margin-bottom: 10px;
    }

    h2 {
        color:  rgba(6, 42, 78, 1);
        font-weight: bold;
    }

    .btn-menu {
        width: 100%;
        font-size: 18px;
        padding: 12px;
        margin-bottom: 12px;
    }

    .btn-green {
        background-color:  rgba(0, 85, 165, 0.75);
        color: white;
    }

    .btn-green:hover {
        background-color:  rgba(4, 41, 77, 1);
        color: white;
    }

    .btn-dark-green {
        background-color:  rgba(4, 35, 65, 0.75);
        color: white;
    }

    .btn-dark-green:hover {
        background-color: rgba(4, 41, 77, 1);
        color: white;
    }

</style>

</head>
<body>

<div class="card-painel">

    <div class="text-center">
        <img src="../<?= $user['fotoUrl'] ?: 'assets/img/user-default.png' ?>" class="foto-perfil">
        <h2>Olá, <?= htmlspecialchars($user['nome']) ?></h2>
        <p class="text-muted mb-4"><?= htmlspecialchars($user['email']) ?></p>
    </div>

    <div class="row text-center mb-4">

        <div class="col-md-3">
            <a href="editar_perfil.php" class="btn btn-dark-green btn-menu">Editar Perfil</a>
        </div>

        <div class="col-md-3">
            <a href="meus_emprestimos.php" class="btn btn-green btn-menu">Meus Empréstimos</a>
        </div>

        <div class="col-md-3">
            <a href="livros_disponiveis.php" class="btn btn-success btn-menu">Livros Disponíveis</a>
        </div>

        <div class="col-md-3">
            <a href="../auth/logout.php" class="btn btn-danger btn-menu">Sair</a>
        </div>

    </div>

    <hr>

    <h4 class="mt-4 mb-3">📚 Empréstimos Ativos</h4>

    <?php if (!$ativos): ?>
        <div class="alert alert-info">Você não possui nenhum empréstimo em andamento.</div>
    <?php else: ?>

        <table class="table table-striped">
            <thead class="table-success">
                <tr>
                    <th>Livro</th>
                    <th>Data Empréstimo</th>
                    <th>Devolução Prevista</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ativos as $e): ?>
                    <tr>
                        <td><?= htmlspecialchars($e['titulo']) ?></td>
                        <td><?= $e['dataEmprestimo'] ?></td>
                        <td><?= $e['dataDevolucaoPrevista'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php endif; ?>

</div>

</body>
</html>
