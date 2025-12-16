<?php
// config/config.php
session_start();

/* CONFIGURAÇÃO DO MYSQL */
$host = "localhost";
$db   = "Biblioteca";  // coloque exatamente o nome do seu banco no phpMyAdmin
$user = "root";
$pass = ""; // XAMPP não usa senha

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Conexão com MySQL falhou: " . $e->getMessage());
}

/* Funções de sessão / permissão */
function isAdmin() {
    return isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'admin';
}

function isUsuario() {
    return isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'usuario';
}

function isLogged() {
    return isset($_SESSION['idUsuario']);
}

/* Função utilitária para escapar */
function e($v) {
    return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
}
?>
