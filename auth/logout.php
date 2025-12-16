<?php
require '../config/config.php';
session_destroy();

// Redirecionar para HOME
header("Location: ../index.php");
exit;
