<?php
require '../../config/config.php';
require '../json_response.php';

$idUsuario = intval($_GET['idUsuario'] ?? 0);

$stmt = $pdo->prepare("
    SELECT e.idEmprestimo, e.dataEmprestimo, e.dataDevolucaoPrevista, e.dataDevolucaoReal,
           l.titulo, l.capaUrl
    FROM Emprestimo e
    JOIN Livro l ON e.idLivro = l.idLivro
    WHERE e.idUsuario = ?
    ORDER BY e.dataEmprestimo DESC
");
$stmt->execute([$idUsuario]);

jsonResponse($stmt->fetchAll(PDO::FETCH_ASSOC));
