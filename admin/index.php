<?php
require '../config/config.php';
if (!isAdmin()) { die("Acesso negado."); }

// CONTADORES
$totalLivros = (int)$pdo->query("SELECT COUNT(*) FROM Livro")->fetchColumn();
$totalUsuarios = (int)$pdo->query("SELECT COUNT(*) FROM Usuario WHERE tipo='usuario'")->fetchColumn();
$totalEmprestimos = (int)$pdo->query("SELECT COUNT(*) FROM Emprestimo")->fetchColumn();
$totalAtivos = (int)$pdo->query("SELECT COUNT(*) FROM Emprestimo WHERE dataDevolucaoReal IS NULL")->fetchColumn();

// ATRASADOS (MYSQL)
$totalAtrasados = (int)$pdo->query("
    SELECT COUNT(*) FROM Emprestimo 
    WHERE dataDevolucaoReal IS NULL 
    AND dataDevolucaoPrevista < CURDATE()
")->fetchColumn();



// ÚLTIMOS EMPRÉSTIMOS (MYSQL)
$ultimos = $pdo->query("
    SELECT e.*, l.titulo, u.nome
    FROM Emprestimo e
    JOIN Livro l ON e.idLivro = l.idLivro
    JOIN Usuario u ON e.idUsuario = u.idUsuario
    ORDER BY e.dataEmprestimo DESC
    LIMIT 8
")->fetchAll(PDO::FETCH_ASSOC);

// ÚLTIMOS LIVROS (MYSQL)
$novosLivros = $pdo->query("SELECT * FROM Livro ORDER BY idLivro DESC LIMIT 8")->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<title>Painel Admin — Biblioteca</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- CSS PREMIUM -->
<link rel="stylesheet" href="../assets/css/admin.css">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

<div class="container-fluid px-4 py-4">

    <!-- TOPO -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="h3 mb-3">Painel Administrativo</h1>

           <div class="d-flex flex-wrap gap-3">

    <a href="novo_livro.php" 
       class="btn btn-success px-4 py-2 rounded-pill fw-bold shadow-sm">
        ➕ Novo Livro
    </a>

    <a href="livros.php" 
       class="btn btn-outline-success px-4 py-2 rounded-pill fw-bold shadow-sm">
        📚 Livros
    </a>

    <a href="novo_leitor.php"
       class="btn btn-primary px-4 py-2 rounded-pill fw-bold shadow-sm">
        👤 Novo Leitor
    </a>

    <a href="leitores.php"
       class="btn btn-info px-4 py-2 rounded-pill fw-bold shadow-sm text-white">
        🧑‍🤝‍🧑 Leitores
    </a>

    <a href="emprestimos.php"
       class="btn btn-secondary px-4 py-2 rounded-pill fw-bold shadow-sm">
        🔄 Empréstimos
    </a>

</div>

        </div>

        <div>
            <a href="../auth/logout.php" class="btn btn-outline-danger btn-lg">Sair</a>
        </div>

    </div>

    <!-- CARDS DE CONTADORES -->
    <div class="row g-3 mb-4">

        <div class="col-sm-6 col-md-3">
            <div class="card card-counter p-3">
                <h6>Total de Livros</h6>
                <h2><?=$totalLivros?></h2>
            </div>
        </div>

        <div class="col-sm-6 col-md-3">
            <div class="card card-counter p-3">
                <h6>Usuários</h6>
                <h2><?=$totalUsuarios?></h2>
            </div>
        </div>

        <div class="col-sm-6 col-md-3">
            <div class="card card-counter p-3">
                <h6>Empréstimos</h6>
                <h2><?=$totalEmprestimos?></h2>
            </div>
        </div>

        <div class="col-sm-6 col-md-3">
            <div class="card card-counter p-3">
                <h6>Atrasados</h6>
                <h2 class="text-danger"><?=$totalAtrasados?></h2>
            </div>
        </div>

    </div>

    <div class="row g-4">

      
        <!-- TABELA ÚLTIMOS EMPRÉSTIMOS -->
        <div class="col-lg-8">
            <div class="card p-3 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0">Últimos Empréstimos</h6>
                    <a href="emprestimos.php" class="btn btn-sm btn-success rounded-pill">Ver todos →</a>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Livro</th>
                                <th>Leitor</th>
                                <th>Data</th>
                                <th>Previsto</th>
                                <th>Devolução</th>
                            </tr>
                        </thead>

                        <tbody>
                        <?php foreach($ultimos as $e): ?>
                            <tr>
                                <td><?=$e['idEmprestimo']?></td>
                                <td><?=htmlspecialchars($e['titulo'])?></td>
                                <td><?=htmlspecialchars($e['nome'])?></td>
                                <td><?=$e['dataEmprestimo']?></td>
                                <td><?=$e['dataDevolucaoPrevista']?></td>
                                <td><?=$e['dataDevolucaoReal'] ?: '-'?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>

                    </table>
                </div>
            </div>

            <!-- ÚLTIMOS LIVROS ADICIONADOS -->
            <div class="card p-3">

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0">Últimos Livros Adicionados</h6>

                    <!-- BOTÃO CORRETO PARA OS LIVROS -->
                    <a href="livros.php" class="btn btn-sm btn-success rounded-pill">
                        Ver todos →
                    </a>
                </div>

                <div class="row g-3">

                    <?php foreach($novosLivros as $l):
                        $capa = !empty($l['capaUrl']) ? '../'.$l['capaUrl'] : '../assets/img/no-cover.png';
                    ?>

                    <div class="col-md-3 text-center">
                        <img src="<?=$capa?>" class="img-fluid rounded" style="height:140px;object-fit:cover;">

                        <div class="small mt-1"><?=htmlspecialchars($l['titulo'])?></div>

                        <a href="editar_livro.php?id=<?=$l['idLivro']?>" 
                           class="btn btn-sm btn-outline-success mt-2 rounded-pill">
                            Editar
                        </a>
                    </div>

                    <?php endforeach; ?>

                </div>

            </div>

        </div>
    </div>

</div>

<script>
const statusLabels = <?= json_encode($statusLabels) ?>;
const statusCounts = <?= json_encode($statusCounts) ?>;
</script>

<script src="../assets/js/admin.js"></script>

</body>
</html>
