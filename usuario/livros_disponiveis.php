<?php
require '../config/config.php';

if (!isUsuario()) die("Acesso negado.");

// BUSCA
$busca = isset($_GET['busca']) ? trim($_GET['busca']) : "";

// FILTRO POR CATEGORIA
$categoria = isset($_GET['categoria']) ? trim($_GET['categoria']) : "";

// Consulta base
$sql = "SELECT * FROM Livro WHERE status = 'disponivel'";

// Adicionar busca
if ($busca !== "") {
    $sql .= " AND (titulo LIKE '%$busca%' OR autor LIKE '%$busca%')";
}

// Adicionar filtro categoria
if ($categoria !== "") {
    $sql .= " AND categoria = '$categoria'";
}

$livros = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

// pegar categorias únicas
$cats = $pdo->query("SELECT DISTINCT categoria FROM Livro WHERE categoria IS NOT NULL AND categoria <> ''")->fetchAll(PDO::FETCH_COLUMN);
?>
<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<title>Livros Disponíveis</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body {
    background-color: #e9f7ef;
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

h2 {
    color: rgba(4, 41, 77, 1);
    font-weight: bold;
}

/* Botão voltar */
.btn-back {
    background-color: rgba(6, 37, 68, 1);
    color: white;
}
.btn-back:hover {
    background-color: rgba(5, 62, 119, 1);
    color: white;
}

.card-book {
    border: none;
    border-radius: 12px !important;
    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    transition: transform .2s;
}
.card-book:hover {
    transform: scale(1.04);
}

.card-img-top {
    border-radius: 12px 12px 0 0;
}

</style>

</head>
<body class="container py-4">

<a href="painel.php" class="btn btn-back mb-3">&larr; Voltar</a>

<h2 class="mb-4">Livros Disponíveis</h2>

<!-- FILTROS -->
<form method="get" class="row g-3 mb-4">

    <div class="col-md-6">
        <input type="text" name="busca" class="form-control" 
               placeholder="Buscar por título ou autor..." 
               value="<?= htmlspecialchars($busca) ?>">
    </div>

    <div class="col-md-4">
        <select name="categoria" class="form-select">
            <option value="">Todas categorias</option>
            <?php foreach ($cats as $c): ?>
                <option value="<?= htmlspecialchars($c) ?>" 
                <?= $categoria == $c ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-md-2">
        <button class="btn btn-success w-100">Filtrar</button>
    </div>

</form>

<!-- LISTA DE LIVROS -->
<div class="row">
<?php if (empty($livros)): ?>

    <div class="alert alert-warning">Nenhum livro encontrado.</div>

<?php else: ?>

<?php foreach ($livros as $l): ?>
    <div class="col-md-3 mb-4">

        <div class="card card-book">

            <?php if ($l['capaUrl']): ?>
                <img src="../<?= $l['capaUrl'] ?>" class="card-img-top" style="height: 230px; object-fit: cover;">
            <?php else: ?>
                <img src="../assets/img/no-cover.png" class="card-img-top" style="height: 230px;">
            <?php endif; ?>

            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($l['titulo']) ?></h5>
                <p class="text-muted"><?= htmlspecialchars($l['autor']) ?></p>
                <span class="badge bg-success"><?= htmlspecialchars($l['categoria']) ?></span>
            </div>

        </div>

    </div>
<?php endforeach; ?>

<?php endif; ?>
</div>


</body>
</html>
