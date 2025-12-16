<?php
// nenhuma lógica aqui ainda, só exibe o formulário
?>

<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<title>Recuperar Senha - Leitor</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
        background-color: #e9f7ef;
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    }

    .login-card {
        max-width: 420px;
        margin: 80px auto;
        padding: 35px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    }

    .btn-green {
        background-color: #198754;
        color: white;
    }

    .btn-green:hover {
        background-color: #146c43;
        color: white;
    }

    .btn-back {
        background-color: #0F5132;
        color: white;
    }

    .btn-back:hover {
        background-color: #0c4128;
        color: white;
    }

    h2 {
        color: #0F5132;
    }

    a { text-decoration: none; }
</style>

</head>
<body>

<!-- BOTÃO VOLTAR -->
<div class="p-3">
    <a href="login_usuario.php" class="btn btn-back">&larr; Voltar</a>
</div>

<div class="login-card">

    <h2 class="text-center mb-4">Recuperar Senha</h2>

    <form method="POST" action="enviar_reset.php">

        <div class="mb-3">
            <label>Email cadastrado:</label>
            <input type="email" name="email" class="form-control" required/>
        </div>

        <button class="btn btn-green w-100 mt-2">Enviar link de recuperação</button>
    </form>

</div>

</body>
</html>
