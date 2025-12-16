<?php
require '../config/config.php';
if (!isAdmin()) { die("Acesso negado."); }

/* =========================
   REGISTRAR DEVOLUÇÃO
   ========================= */
if (isset($_GET['devolver'])) {
    $id = intval($_GET['devolver']);

    // CORRIGIDO: GETDATE() → NOW()
    $stmt = $pdo->prepare("UPDATE Emprestimo SET dataDevolucaoReal = NOW() WHERE idEmprestimo = ?");
    $stmt->execute([$id]);

    // Atualizar livro para disponível
    $stmt2 = $pdo->prepare("SELECT idLivro FROM Emprestimo WHERE idEmprestimo = ?");
    $stmt2->execute([$id]);
    $row = $stmt2->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $pdo->prepare("UPDATE Livro SET status = 'disponivel' WHERE idLivro = ?")
            ->execute([$row['idLivro']]);
    }

    header('Location: emprestimos.php');
    exit;
}

/* =========================
   REGISTRAR NOVO EMPRÉSTIMO
   ========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idLivro    = intval($_POST['idLivro']);
    $idUsuario  = intval($_POST['idUsuario']);
    $dias       = intval($_POST['dias']);

    $dataEmp = date('Y-m-d');
    $dataPrev = date('Y-m-d', strtotime($dataEmp . " +$dias days"));

    $pdo->prepare("
        INSERT INTO Emprestimo(idLivro, idUsuario, dataEmprestimo, dataDevolucaoPrevista)
        VALUES (?, ?, ?, ?)
    ")->execute([$idLivro, $idUsuario, $dataEmp, $dataPrev]);

    $pdo->prepare("UPDATE Livro SET status='emprestado' WHERE idLivro = ?")
        ->execute([$idLivro]);

    header('Location: emprestimos.php');
    exit;
}

/* =========================
   LISTAS
   ========================= */

// Usuários
$usuarios = $pdo->query("
    SELECT * FROM Usuario 
    WHERE tipo='usuario' 
    ORDER BY nome
")->fetchAll(PDO::FETCH_ASSOC);

// Livros disponíveis
$livrosDisponiveis = $pdo->query("
    SELECT * FROM Livro 
    WHERE status='disponivel'
")->fetchAll(PDO::FETCH_ASSOC);

// Empréstimos ativos
$ativos = $pdo->query("
    SELECT e.*, l.titulo, u.nome
    FROM Emprestimo e
    JOIN Livro l ON e.idLivro = l.idLivro
    JOIN Usuario u ON e.idUsuario = u.idUsuario
    WHERE e.dataDevolucaoReal IS NULL
    ORDER BY e.dataEmprestimo DESC
")->fetchAll(PDO::FETCH_ASSOC);

// Empréstimos devolvidos
$devolvidos = $pdo->query("
    SELECT e.*, l.titulo, u.nome
    FROM Emprestimo e
    JOIN Livro l ON e.idLivro = l.idLivro
    JOIN Usuario u ON e.idUsuario = u.idUsuario
    WHERE e.dataDevolucaoReal IS NOT NULL
    ORDER BY e.dataDevolucaoReal DESC
")->fetchAll(PDO::FETCH_ASSOC);

?>
<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<title>Empréstimos</title>

<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

<style>
/* Botões premium */
.btn-rounded {
    border-radius: 50px !important;
    padding: 8px 22px !important;
    font-weight: 600;
}

.table td, .table th {
    vertical-align: middle !important;
}
</style>

</head>

<body class="container py-4">

    <div class="d-flex justify-content-between mb-4">
        <h2 class="fw-bold">📚 Empréstimos</h2>

        <a href="index.php" class="btn btn-secondary btn-rounded">
            Voltar ao Painel
        </a>
    </div>

    <!-- REGISTRAR EMPRÉSTIMO -->
    <div class="card p-4 mb-4">
        <h4 class="mb-3">➕ Registrar Empréstimo</h4>

        <form method="post" class="row g-3">

            <!-- PESQUISAR LIVRO -->
            <div class="col-md-4">
                <label class="fw-bold">Livro</label>

                <!-- Busca -->
                <input type="text" id="buscarLivro" class="form-control mb-2" placeholder="Pesquisar livro...">

                <select name="idLivro" id="listaLivros" class="form-select" required>
                    <?php foreach($livrosDisponiveis as $ld): ?>
                        <option value="<?=$ld['idLivro']?>">
                            <?=e($ld['titulo'])?> (<?=e($ld['autor'])?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- PESQUISAR USUÁRIO -->
            <div class="col-md-4">
                <label class="fw-bold">Usuário</label>

                <!-- Busca -->
                <input type="text" id="buscarUsuario" class="form-control mb-2" placeholder="Pesquisar usuário...">

                <select name="idUsuario" id="listaUsuarios" class="form-select" required>
                    <?php foreach($usuarios as $u): ?>
                        <option value="<?=$u['idUsuario']?>"><?=e($u['nome'])?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-2">
                <label class="fw-bold">Dias</label>
                <input type="number" name="dias" value="7" min="1" class="form-control">
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-primary btn-rounded w-100">Salvar</button>
            </div>

        </form>
    </div>

    <!-- ATIVOS -->
    <h4 class="mt-4 mb-2">📌 Empréstimos Ativos</h4>

    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Livro</th>
                <th>Usuário</th>
                <th>Emprestado</th>
                <th>Previsto</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($ativos as $r): ?>
            <tr>
                <td><?=$r['idEmprestimo']?></td>
                <td><?=e($r['titulo'])?></td>
                <td><?=e($r['nome'])?></td>
                <td><?=$r['dataEmprestimo']?></td>
                <td><?=$r['dataDevolucaoPrevista']?></td>
                <td>
                    <a class="btn btn-sm btn-success btn-rounded"
                       href="?devolver=<?=$r['idEmprestimo']?>"
                       onclick="return confirm('Confirmar devolução?')">
                       ✔ Registrar Devolução
                    </a>

                    <a class="btn btn-sm btn-secondary btn-rounded"
                       href="editar_livro.php?id=<?=$r['idLivro']?>">
                       ✏ Editar Livro
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- DEVOLVIDOS -->
    <h4 class="mt-4 mb-2">📗 Devolvidos</h4>

    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Livro</th>
                <th>Usuário</th>
                <th>Previsto</th>
                <th>Devolvido</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($devolvidos as $r): ?>
            <tr>
                <td><?=$r['idEmprestimo']?></td>
                <td><?=e($r['titulo'])?></td>
                <td><?=e($r['nome'])?></td>
                <td><?=$r['dataDevolucaoPrevista']?></td>
                <td><?=$r['dataDevolucaoReal']?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

<!-- SCRIPTS PARA FILTRO -->
<!-- SCRIPTS PARA FILTRO -->
<script>
// FILTRAR LIVROS
document.getElementById("buscarLivro").addEventListener("keyup", function () {
    let filtro = this.value.toLowerCase();
    let select = document.getElementById("listaLivros");

    // Recuperar TODAS opções originais
    let opcoes = Array.from(select.querySelectorAll("option"));

    // Limpar select
    select.innerHTML = "";

    // Adicionar opções filtradas
    opcoes.forEach(op => {
        if (op.textContent.toLowerCase().includes(filtro)) {
            select.appendChild(op);
        }
    });
});

// FILTRAR USUÁRIOS
document.getElementById("buscarUsuario").addEventListener("keyup", function () {
    let filtro = this.value.toLowerCase();
    let select = document.getElementById("listaUsuarios");

    let opcoes = Array.from(select.querySelectorAll("option"));

    select.innerHTML = "";

    opcoes.forEach(op => {
        if (op.textContent.toLowerCase().includes(filtro)) {
            select.appendChild(op);
        }
    });
});
</script>


</body>
</html>
