<?php

require_once "conexao.php";

$editando = false;
$transportador_edicao = null;

/* EXCLUIR TRANSPORTADOR */
if (isset($_GET["excluir"])) {

    $id = intval($_GET["excluir"]);

    $sql = "DELETE FROM transportadores WHERE id = $id";

    if ($conexao->query($sql)) {
        header("Location: transportadores.php");
        exit;
    } else {
        $erro = "Não foi possível excluir o transportador. Ele pode estar vinculado a um frete.";
    }
}

/* CADASTRAR TRANSPORTADOR */
if (isset($_POST["cadastrar"])) {

    $nome = $_POST["nome"];
    $documento = $_POST["documento"];
    $telefone = $_POST["telefone"];
    $email = $_POST["email"];
    $veiculo = $_POST["veiculo"];

    $sql = "INSERT INTO transportadores
            (nome, documento, telefone, email, veiculo)
            VALUES
            ('$nome', '$documento', '$telefone', '$email', '$veiculo')";

    if ($conexao->query($sql)) {
        header("Location: transportadores.php");
        exit;
    }
}

/* EDITAR TRANSPORTADOR */
if (isset($_GET["editar"])) {

    $id = intval($_GET["editar"]);

    $sql = "SELECT * FROM transportadores WHERE id = $id";

    $resultado_edicao = $conexao->query($sql);

    if ($resultado_edicao && $resultado_edicao->num_rows > 0) {

        $editando = true;
        $transportador_edicao = $resultado_edicao->fetch_assoc();
    }
}

/* ATUALIZAR TRANSPORTADOR */
if (isset($_POST["atualizar"])) {

    $id = intval($_POST["id"]);

    $nome = $_POST["nome"];
    $documento = $_POST["documento"];
    $telefone = $_POST["telefone"];
    $email = $_POST["email"];
    $veiculo = $_POST["veiculo"];

    $sql = "UPDATE transportadores SET
                nome = '$nome',
                documento = '$documento',
                telefone = '$telefone',
                email = '$email',
                veiculo = '$veiculo'
            WHERE id = $id";

    if ($conexao->query($sql)) {
        header("Location: transportadores.php");
        exit;
    }
}

/* PESQUISA DE TRANSPORTADORES */
$pesquisa = "";

if (isset($_GET["pesquisa"])) {
    $pesquisa = trim($_GET["pesquisa"]);
}

if ($pesquisa !== "") {

    $pesquisa_sql = $conexao->real_escape_string($pesquisa);

    $sql = "SELECT * FROM transportadores
            WHERE nome LIKE '%$pesquisa_sql%'
               OR documento LIKE '%$pesquisa_sql%'
               OR telefone LIKE '%$pesquisa_sql%'
               OR email LIKE '%$pesquisa_sql%'
               OR veiculo LIKE '%$pesquisa_sql%'
            ORDER BY id DESC";

} else {

    $sql = "SELECT * FROM transportadores ORDER BY id DESC";
}

$transportadores = $conexao->query($sql);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Transportadores - Gerenciador Logístico</title>

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
                    <a class="nav-link" href="index.php">
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
                    <a class="nav-link active" href="transportadores.php">
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


<div class="container mt-5">

    <h1 class="mb-4">
        <?= $editando ? "Editar Transportador" : "Cadastro de Transportadores" ?>
    </h1>

    <?php if (isset($erro)): ?>

        <div class="alert alert-danger">
            <?= $erro ?>
        </div>

    <?php endif; ?>


    <div class="card shadow-sm mb-5">

        <div class="card-body">

            <form method="POST">

                <?php if ($editando): ?>

                    <input
                        type="hidden"
                        name="id"
                        value="<?= $transportador_edicao["id"] ?>"
                    >

                <?php endif; ?>


                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Nome
                        </label>

                        <input
                            type="text"
                            name="nome"
                            class="form-control"
                            value="<?= $editando ? htmlspecialchars($transportador_edicao["nome"]) : "" ?>"
                            required
                        >

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            CPF/CNPJ
                        </label>

                        <input
                            type="text"
                            name="documento"
                            class="form-control"
                            value="<?= $editando ? htmlspecialchars($transportador_edicao["documento"]) : "" ?>"
                            required
                        >

                    </div>

                </div>


                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Telefone
                        </label>

                        <input
                            type="text"
                            name="telefone"
                            class="form-control"
                            value="<?= $editando ? htmlspecialchars($transportador_edicao["telefone"]) : "" ?>"
                            required
                        >

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            E-mail
                        </label>

                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            value="<?= $editando ? htmlspecialchars($transportador_edicao["email"]) : "" ?>"
                            required
                        >

                    </div>

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Veículo
                    </label>

                    <input
                        type="text"
                        name="veiculo"
                        class="form-control"
                        value="<?= $editando ? htmlspecialchars($transportador_edicao["veiculo"]) : "" ?>"
                        required
                    >

                </div>


                <?php if ($editando): ?>

                    <button
                        type="submit"
                        name="atualizar"
                        class="btn btn-warning"
                    >
                        Atualizar Transportador
                    </button>

                    <a
                        href="transportadores.php"
                        class="btn btn-secondary"
                    >
                        Cancelar
                    </a>

                <?php else: ?>

                    <button
                        type="submit"
                        name="cadastrar"
                        class="btn btn-primary"
                    >
                        Cadastrar Transportador
                    </button>

                <?php endif; ?>

            </form>

        </div>

    </div>


    <h2 class="mb-3">
        Transportadores cadastrados
    </h2>


    <!-- PESQUISA -->

    <form method="GET" class="row g-2 mb-4">

        <div class="col-md-9">

            <input
                type="text"
                name="pesquisa"
                class="form-control"
                placeholder="Pesquisar por nome, documento, telefone, e-mail ou veículo..."
                value="<?= htmlspecialchars($pesquisa) ?>"
            >

        </div>

        <div class="col-md-3 d-flex gap-2">

            <button
                type="submit"
                class="btn btn-primary"
            >
                Pesquisar
            </button>

            <?php if ($pesquisa !== ""): ?>

                <a
                    href="transportadores.php"
                    class="btn btn-secondary"
                >
                    Limpar
                </a>

            <?php endif; ?>

        </div>

    </form>


    <div class="table-responsive">

        <table class="table table-striped table-bordered align-middle">

            <thead class="table-dark">

                <tr>

                    <th>ID</th>
                    <th>Nome</th>
                    <th>Documento</th>
                    <th>Telefone</th>
                    <th>E-mail</th>
                    <th>Veículo</th>
                    <th>Ações</th>

                </tr>

            </thead>

            <tbody>

                <?php if ($transportadores && $transportadores->num_rows > 0): ?>

                    <?php while ($transportador = $transportadores->fetch_assoc()): ?>

                        <tr>

                            <td>
                                <?= $transportador["id"] ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($transportador["nome"]) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($transportador["documento"]) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($transportador["telefone"]) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($transportador["email"]) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($transportador["veiculo"]) ?>
                            </td>

                            <td>

                                <a
                                    href="transportadores.php?editar=<?= $transportador["id"] ?>"
                                    class="btn btn-sm btn-warning"
                                >
                                    ✏️ Editar
                                </a>

                                <a
                                    href="transportadores.php?excluir=<?= $transportador["id"] ?>"
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('Tem certeza que deseja excluir este transportador?');"
                                >
                                    🗑️ Excluir
                                </a>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>

                        <td
                            colspan="7"
                            class="text-center"
                        >
                            Nenhum transportador encontrado.
                        </td>

                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>

</body>

</html>