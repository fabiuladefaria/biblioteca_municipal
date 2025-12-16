<?php
require '../config/config.php';
if (!isAdmin()) { die("Acesso negado."); }

// PARÂMETROS DE BUSCA
$busca = isset($_GET['busca']) ? trim($_GET['busca']) : '';
$categoria = isset($_GET['categoria']) ? trim($_GET['categoria']) : '';

// PAGINAÇÃO
$pagina = isset($_GET['p']) ? max(1, intval($_GET['p'])) : 1;
$porPagina = 12;
$offset = ($pagina - 1) * $porPagina;

// CATEGORIAS
$categorias = ['Romance','Ficção','Terror','Aventura','Fantasia','Suspense','Drama','Infantil'];

// CONSULTA BASE
$sql = "FROM Livro WHERE 1=1 ";
$params = [];

// FILTRO DE TÍTULO
if ($busca !== '') {
    $sql .= " AND titulo LIKE ? ";
    $params[] = "%$busca%";
}

// FILTRO CATEGORIA
if ($categoria !== '') {
    $sql .= " AND categoria = ? ";
    $params[] = $categoria;
}

// CONTAR TOTAL PARA PAGINAR
$count = $pdo->prepare("SELECT COUNT(*) $sql");
$count->execute($params);
$total = $count->fetchColumn();
$paginasTotais = ceil($total / $porPagina);

// BUSCAR LIVROS + STATUS (MYSQL CORRIGIDO)
$query = $pdo->prepare("
    SELECT * $sql
    ORDER BY idLivro DESC
    LIMIT $porPagina OFFSET $offset
");
$query->execute($params);
$livros = $query->fetchAll(PDO::FETCH_ASSOC);
?>


<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<title>Lista de Livros</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/admin.css">

<style>
/* CARD DE LIVRO */
.card-livro {
    border-radius: 16px;
    border: 1px solid #dde2e8;
    background: #fff;
    padding: 12px;
    transition: 0.25s ease;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
}
.card-livro:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 22px rgba(0,0,0,0.10);
}
.card-livro img {
    height: 180px;
    object-fit: cover;
    border-radius: 12px;
    width: 100%;
}
.card-title-book {
    font-weight: 700;
    color: #155d41;
    margin-top: 8px;
    font-size: 15px;
}

/* STATUS */
.tag-status {
    font-size: 12px;
    padding: 4px 10px;
    border-radius: 20px;
    font-weight: 600;
    display: inline-block;
    margin-top: 6px;
}
.tag-disponivel {
    background: #d1f2e0;
    color: #0f6c3f;
    border: 1px solid #9adbb9;
}
.tag-emprestado {
    background: #f8d7da;
    color: #842029;
    border: 1px solid #d87981;
}
a{
    text-decoration: none;
}

.top-actions a { margin-right: 6px; }
</style>

</head>
<body>

<div class="container py-4">

    <!-- TÍTULO -->
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="mb-4">Livros</h2>
        <a href="index.php" class="btn btn-outline-secondary">Voltar ao Painel</a>
    </div>

    <!-- FORMULÁRIO DE BUSCA -->
    <form method="get" class="row g-3 mb-4">

        <div class="col-md-6">
            <input type="text" name="busca" class="form-control"
                   placeholder="Pesquisar por título..."
                   value="<?=htmlspecialchars($busca)?>">
        </div>

        <div class="col-md-4">
            <select name="categoria" class="form-select">
                <option value="">Todas as categorias</option>
                <?php foreach ($categorias as $c): ?>
                    <option value="<?=$c?>" <?=$categoria==$c?'selected':''?>><?=$c?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-2">
            <button class="btn btn-success w-100">Buscar</button>
        </div>

    </form>

    <!-- LISTAGEM -->
    <div class="row g-4">

        <?php if (count($livros) == 0): ?>
            <div class="col-12 text-center">
                <p class="text-muted">Nenhum livro encontrado.</p>
            </div>
        <?php endif; ?>

        <?php foreach($livros as $l): 
            $capa = !empty($l['capaUrl']) ? '../'.$l['capaUrl'] : '../assets/img/no-cover.png';
            $status = strtolower($l['status']);
        ?>

        <div class="col-sm-6 col-md-3">
            <div class="card-livro">

                <img src="<?=$capa?>">

                <div class="card-title-book">
                    <?=htmlspecialchars($l['titulo'])?>
                </div>

                <div class="text-muted small">
                    <?=$l['categoria']?>
                </div>

                <!-- STATUS DO LIVRO -->
                <?php if ($status === 'disponivel'): ?>
                    <span class="tag-status tag-disponivel">Disponível</span>
                <?php else: ?>
                    <span class="tag-status tag-emprestado">Emprestado</span>
                <?php endif; ?>

                <div class="mt-3 top-actions">
                    <a href="editar_livro.php?id=<?=$l['idLivro']?>" class="btn btn-sm btn-outline-success">
                        Editar
                    </a>

                    <a href="excluir_livro.php?id=<?=$l['idLivro']?>"
   class="btn btn-sm btn-outline-danger"
   onclick="return confirm('Tem certeza que deseja excluir este livro?')">
    Excluir
</a>

                </div>

            </div>
        </div>

        <?php endforeach; ?>

    </div>

    <!-- PAGINAÇÃO -->
    <?php if ($paginasTotais > 1): ?>
    <nav class="mt-4">
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $paginasTotais; $i++): ?>
                <li class="page-item <?=$i==$pagina?'active':''?>">
                    <a class="page-link"
                       href="?p=<?=$i?>&busca=<?=urlencode($busca)?>&categoria=<?=urlencode($categoria)?>">
                       <?=$i?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
    <?php endif; ?>

</div>

</body>
</html>
