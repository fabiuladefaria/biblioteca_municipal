<?php
require '../../config/config.php';
require '../json_response.php';

$idLivro = intval($_GET['idLivro'] ?? 0);

$stmt = $pdo->prepare("SELECT AVG(estrelas) as media, COUNT(*) as total FROM Avaliacao WHERE idLivro=?");
$stmt->execute([$idLivro]);

jsonResponse($stmt->fetch(PDO::FETCH_ASSOC));
