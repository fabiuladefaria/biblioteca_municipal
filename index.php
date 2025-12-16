<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<title>Biblioteca Municipal</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

/* ======================= ESTILO MODERNO PREMIUM ======================= */

body {
    background-color: #f4f6f9;
    font-family: "Inter", "Segoe UI", Tahoma, sans-serif;
    color: #070707ff;
}

/* NAVBAR */
.navbar {
    padding: 18px 0;
    background: rgba(0, 85, 165, 0.75);
    backdrop-filter: blur(8px);
    transition: 0.3s;
}

.navbar.scrolled {
    background: rgba(0, 60, 120, 0.90);
    padding: 10px 0;
}

.navbar-brand {
    font-weight: 700;
    font-size: 1.45rem;
    letter-spacing: -.5px;
}

.navbar-brand img {
    height: 34px;
    width: auto;
    border-radius: 4px;
}


/* BOTÕES */
.btn-nav {
    border-radius: 40px;
    padding: 7px 22px;
    font-weight: 600;
    transition: 0.2s;
}

/* HERO / BANNER */
.hero {
    height: 460px;
    background-color: #cce5ff;

    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    text-align: center;
    padding: 20px;
}

.hero h1 {
    font-size: 3rem;
    font-weight: 800;
    letter-spacing: -1px;
    margin-bottom: 15px;
    color: #000;
}

.hero p {
    font-size: 1.25rem;
    opacity: 0.9;
    color: #08070794;
}


/* SOBRE */
.sobre {
    background: #fff;
    margin-top: -35px;
    border-radius: 20px;
    padding: 50px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    animation: fadeUp 0.8s ease-out forwards;
}

@keyframes fadeUp {
    from { opacity: 0; transform: translateY(30px); }
    to   { opacity: 1; transform: translateY(0); }
}

.sobre h3 {
    font-weight: 700;
    color: #004c99;
    margin-bottom: 15px;
}

/* FOOTER MODERNO */
footer {
    background: #002b4d;
    color: #e8eff5;
    padding: 50px 0 25px;
    margin-top: 50px;
}

footer h5 {
    font-weight: 700;
    margin-bottom: 18px;
}

footer p {
    margin-bottom: 8px;
    color: #c9d6df;
}

footer .copy {
    margin-top: 30px;
    padding-top: 15px;
    border-top: 1px solid rgba(255,255,255,0.15);
    color: #9fb4c7;
    font-size: .9rem;
}

@media (max-width: 768px) {
    .hero { height: 330px; }
    .hero h1 { font-size: 2rem; }
    .sobre { padding: 25px; }
}
</style>

</head>
<body>

<!-- ====================== NAVBAR ====================== -->
<nav class="navbar navbar-expand-lg navbar-dark shadow-sm fixed-top" id="navbar">
  <div class="container">

    <a class="navbar-brand d-flex align-items-center gap-2" href="#">
        <h2>Projeto Pessoal</h2>
    
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="menu">

      <a href="auth/login_admin.php" class="btn btn-light btn-nav mx-1">Administrador</a>
      <a href="auth/login_usuario.php" class="btn btn-outline-light btn-nav mx-1">Leitor</a>
      <a href="auth/register.php" class="btn btn-warning btn-nav mx-1">Criar Conta</a>

    </div>
  </div>
</nav>


<!-- ====================== HERO ====================== -->
<div class="hero mt-5">
    <div>
        <h1>Bem-vindo à Biblioteca Municipal</h1>
        <p>Um espaço dedicado à leitura, aprendizado e cultura para toda a comunidade.</p>
    </div>
</div>



<!-- ====================== SOBRE ====================== -->
<div class="container">
    <div class="sobre">

        <h3>Sobre a Biblioteca</h3>
        <p>
            A Biblioteca Municipal oferece um amplo acervo com livros de diversos gêneros,
            proporcionando acesso ao conhecimento, cultura e lazer para leitores de todas as idades.
        </p>

        <p>
            Nosso sistema digital permite cadastro de leitores, empréstimos, devoluções e organização completa
            do acervo, garantindo praticidade e eficiência no atendimento.
        </p>

        
    </div>
</div>



<!-- ====================== FOOTER ====================== -->
<footer>
    <div class="container">

        <div class="row text-center text-md-start">

            <div class="col-md-4 mb-4">
                <h5>Endereço</h5>
                <p>Pça. Amendoas, 47 - Parque dos Pinheiros</p>
                <p>Alfenas – MG</p>
            </div>

            <div class="col-md-4 mb-4">
                <h5>Contato</h5>
                <p>(35) 3291-4578</p>
                
            </div>

            <div class="col-md-4 mb-4">
                <h5>Horário</h5>
                <p>Segunda a Sexta</p>
                <p>08:00 às 19:00</p>
            </div>

        </div>

        <div class="copy text-center">
            Biblioteca Municipal — Todos os direitos reservados © <?=date('Y')?>
        </div>

    </div>
</footer>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
/* Navbar muda ao rolar */
window.addEventListener("scroll", function() {
    const nav = document.getElementById("navbar");
    if (window.scrollY > 20) nav.classList.add("scrolled");
    else nav.classList.remove("scrolled");
});
</script>

</body>
</html>
