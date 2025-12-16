<?php
require '../../config/config.php';
require '../json_response.php';

// Receber parâmetros por GET
$busca     = trim($_GET['busca'] ?? '');
$categoria = trim($_GET['categoria'] ?? '');
$status    = trim($_GET['status'] ?? '');
$ordenar   = trim($_GET['ordenar'] ?? 'novos');

// Consulta base
$sql = "SELECT 
            idLivro, 
            titulo, 
            autor,
            descricao,
            categoria, 
            status, 
            capaUrl 
        FROM Livro 
        WHERE 1=1 ";
$params = [];

// Filtro: busca por título
if ($busca !== '') {
    $sql .= " AND titulo LIKE ? ";
    $params[] = "%$busca%";
}

// Filtro: categoria
if ($categoria !== '') {
    $sql .= " AND categoria = ? ";
    $params[] = $categoria;
}

// Filtro: status
if ($status !== '' && ($status == 'disponivel' || $status == 'emprestado')) {
    $sql .= " AND status = ? ";
    $params[] = $status;
}

// Ordenação
switch ($ordenar) {
    case 'az':
        $sql .= " ORDER BY titulo ASC ";
        break;
    case 'za':
        $sql .= " ORDER BY titulo DESC ";
        break;
    case 'antigos':
        $sql .= " ORDER BY idLivro ASC ";
        break;
    case 'novos':
    default:
        $sql .= " ORDER BY idLivro DESC ";
        break;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

jsonResponse($stmt->fetchAll(PDO::FETCH_ASSOC));
