<?php

require_once "conexao.php";

$total_produtos = 0;
$total_enderecos = 0;
$total_transportadores = 0;
$total_fretes = 0;

$resultado = $conexao->query("SELECT COUNT(*) AS total FROM produtos");

if ($resultado) {
    $dados = $resultado->fetch_assoc();
    $total_produtos = $dados["total"];
}

$resultado = $conexao->query("SELECT COUNT(*) AS total FROM enderecos");

if ($resultado) {
    $dados = $resultado->fetch_assoc();
    $total_enderecos = $dados["total"];
}

$resultado = $conexao->query("SELECT COUNT(*) AS total FROM transportadores");

if ($resultado) {
    $dados = $resultado->fetch_assoc();
    $total_transportadores = $dados["total"];
}

$resultado = $conexao->query("SELECT COUNT(*) AS total FROM fretes");

if ($resultado) {
    $dados = $resultado->fetch_assoc();
    $total_fretes = $dados["total"];
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Gerenciador Logístico</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">

        <a class="navbar-brand fw-bold" href="index.php">
            Gerenciador Logístico
        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#menu"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menu">

            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <a class="nav-link active" href="index.php">
                        Início
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="produtos.php">
                        Produtos
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="enderecos.php">
                        Endereços
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="transportadores.php">
                        Transportadores
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="fretes.php">
                        Fretes
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="relatorio.php">
                        Relatórios
                    </a>
                </li>

            </ul>

        </div>

    </div>
</nav>

<main class="container py-5">

    <div class="text-center mb-5">

        <h1 class="fw-bold">
            Gerenciador Logístico
        </h1>

        <p class="lead text-muted">
            Sistema para gerenciamento de produtos, endereços,
            transportadores e fretes.
        </p>

    </div>

    <div class="row g-4 mb-5">

        <div class="col-md-6 col-lg-3">

            <div class="card shadow-sm h-100 text-center">

                <div class="card-body">

                    <h5 class="card-title">
                        Produtos
                    </h5>

                    <p class="display-5 fw-bold">
                        <?= $total_produtos ?>
                    </p>

                    <p class="text-muted">
                        cadastrados
                    </p>

                    <a href="produtos.php" class="btn btn-primary">
                        Acessar
                    </a>

                </div>

            </div>

        </div>

        <div class="col-md-6 col-lg-3">

            <div class="card shadow-sm h-100 text-center">

                <div class="card-body">

                    <h5 class="card-title">
                        Endereços
                    </h5>

                    <p class="display-5 fw-bold">
                        <?= $total_enderecos ?>
                    </p>

                    <p class="text-muted">
                        cadastrados
                    </p>

                    <a href="enderecos.php" class="btn btn-primary">
                        Acessar
                    </a>

                </div>

            </div>

        </div>

        <div class="col-md-6 col-lg-3">

            <div class="card shadow-sm h-100 text-center">

                <div class="card-body">

                    <h5 class="card-title">
                        Transportadores
                    </h5>

                    <p class="display-5 fw-bold">
                        <?= $total_transportadores ?>
                    </p>

                    <p class="text-muted">
                        cadastrados
                    </p>

                    <a href="transportadores.php" class="btn btn-primary">
                        Acessar
                    </a>

                </div>

            </div>

        </div>

        <div class="col-md-6 col-lg-3">

            <div class="card shadow-sm h-100 text-center">

                <div class="card-body">

                    <h5 class="card-title">
                        Fretes
                    </h5>

                    <p class="display-5 fw-bold">
                        <?= $total_fretes ?>
                    </p>

                    <p class="text-muted">
                        cadastrados
                    </p>

                    <a href="fretes.php" class="btn btn-primary">
                        Acessar
                    </a>

                </div>

            </div>

        </div>

    </div>

    <div class="card shadow-sm">

        <div class="card-body text-center">

            <h3 class="fw-bold mb-3">
                Relatórios
            </h3>

            <p class="text-muted">
                Consulte os fretes realizados em uma determinada data
                e faça o download do relatório.
            </p>

            <a href="relatorio.php" class="btn btn-success">
                Acessar Relatórios
            </a>

        </div>

    </div>

</main>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>

</body>

</html>
