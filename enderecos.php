<?php

require_once "conexao.php";

$editando = false;
$endereco_edicao = null;

/* EXCLUIR ENDEREÇO */
if (isset($_GET["excluir"])) {

    $id = intval($_GET["excluir"]);

    // Verifica se o endereço está vinculado a algum frete
    $verificar = $conexao->query(
        "SELECT COUNT(*) AS total
         FROM fretes
         WHERE origem_id = $id
            OR destino_id = $id"
    );

    if ($verificar) {

        $vinculo = $verificar->fetch_assoc();

        if ($vinculo["total"] > 0) {

            $erro = "Não é possível excluir este endereço porque ele está vinculado a um frete. Exclua primeiro o frete relacionado.";

        } else {

            $sql = "DELETE FROM enderecos WHERE id = $id";

            if ($conexao->query($sql)) {
                header("Location: enderecos.php");
                exit;
            } else {
                $erro = "Não foi possível excluir o endereço.";
            }
        }

    } else {

        $erro = "Não foi possível verificar se o endereço está vinculado a um frete.";
    }
}


/* CADASTRAR ENDEREÇO */
if (isset($_POST["cadastrar"])) {

    $cep = $_POST["cep"];
    $logradouro = $_POST["logradouro"];
    $numero = $_POST["numero"];
    $complemento = $_POST["complemento"];
    $bairro = $_POST["bairro"];
    $cidade = $_POST["cidade"];
    $estado = $_POST["estado"];

    $sql = "INSERT INTO enderecos
            (cep, logradouro, numero, complemento, bairro, cidade, estado)
            VALUES
            ('$cep', '$logradouro', '$numero', '$complemento', '$bairro', '$cidade', '$estado')";

    if ($conexao->query($sql)) {
        header("Location: enderecos.php");
        exit;
    }
}


/* EDITAR ENDEREÇO */
if (isset($_GET["editar"])) {

    $id = intval($_GET["editar"]);

    $sql = "SELECT * FROM enderecos WHERE id = $id";

    $resultado_edicao = $conexao->query($sql);

    if ($resultado_edicao && $resultado_edicao->num_rows > 0) {

        $editando = true;
        $endereco_edicao = $resultado_edicao->fetch_assoc();
    }
}


/* ATUALIZAR ENDEREÇO */
if (isset($_POST["atualizar"])) {

    $id = intval($_POST["id"]);

    $cep = $_POST["cep"];
    $logradouro = $_POST["logradouro"];
    $numero = $_POST["numero"];
    $complemento = $_POST["complemento"];
    $bairro = $_POST["bairro"];
    $cidade = $_POST["cidade"];
    $estado = $_POST["estado"];

    $sql = "UPDATE enderecos SET
                cep = '$cep',
                logradouro = '$logradouro',
                numero = '$numero',
                complemento = '$complemento',
                bairro = '$bairro',
                cidade = '$cidade',
                estado = '$estado'
            WHERE id = $id";

    if ($conexao->query($sql)) {
        header("Location: enderecos.php");
        exit;
    }
}


/* PESQUISA DE ENDEREÇOS */
$pesquisa = "";

if (isset($_GET["pesquisa"])) {
    $pesquisa = trim($_GET["pesquisa"]);
}

if ($pesquisa !== "") {

    $pesquisa_sql = $conexao->real_escape_string($pesquisa);

    $sql = "SELECT * FROM enderecos
            WHERE cep LIKE '%$pesquisa_sql%'
               OR logradouro LIKE '%$pesquisa_sql%'
               OR bairro LIKE '%$pesquisa_sql%'
               OR cidade LIKE '%$pesquisa_sql%'
               OR estado LIKE '%$pesquisa_sql%'
            ORDER BY id DESC";

} else {

    $sql = "SELECT * FROM enderecos ORDER BY id DESC";
}

$enderecos = $conexao->query($sql);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Endereços - Gerenciador Logístico</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">

    <div class="container">

        <a class="navbar-brand" href="index.php">
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
                    <a class="nav-link active" href="enderecos.php">
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


<div class="container mt-5">

    <h1 class="mb-4">
        <?= $editando ? "Editar Endereço" : "Cadastro de Endereços" ?>
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
                        value="<?= $endereco_edicao["id"] ?>"
                    >

                <?php endif; ?>


                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            CEP
                        </label>

                        <input
                            type="text"
                            name="cep"
                            class="form-control"
                            value="<?= $editando ? $endereco_edicao["cep"] : "" ?>"
                            required
                        >

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Logradouro
                        </label>

                        <input
                            type="text"
                            name="logradouro"
                            class="form-control"
                            value="<?= $editando ? $endereco_edicao["logradouro"] : "" ?>"
                            required
                        >

                    </div>


                    <div class="col-md-2 mb-3">

                        <label class="form-label">
                            Número
                        </label>

                        <input
                            type="text"
                            name="numero"
                            class="form-control"
                            value="<?= $editando ? $endereco_edicao["numero"] : "" ?>"
                            required
                        >

                    </div>

                </div>


                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Complemento
                        </label>

                        <input
                            type="text"
                            name="complemento"
                            class="form-control"
                            value="<?= $editando ? $endereco_edicao["complemento"] : "" ?>"
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Bairro
                        </label>

                        <input
                            type="text"
                            name="bairro"
                            class="form-control"
                            value="<?= $editando ? $endereco_edicao["bairro"] : "" ?>"
                            required
                        >

                    </div>


                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            Cidade
                        </label>

                        <input
                            type="text"
                            name="cidade"
                            class="form-control"
                            value="<?= $editando ? $endereco_edicao["cidade"] : "" ?>"
                            required
                        >

                    </div>


                    <div class="col-md-1 mb-3">

                        <label class="form-label">
                            UF
                        </label>

                        <input
                            type="text"
                            name="estado"
                            class="form-control"
                            maxlength="2"
                            value="<?= $editando ? $endereco_edicao["estado"] : "" ?>"
                            required
                        >

                    </div>

                </div>


                <?php if ($editando): ?>

                    <button
                        type="submit"
                        name="atualizar"
                        class="btn btn-warning"
                    >
                        Atualizar Endereço
                    </button>

                    <a
                        href="enderecos.php"
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
                        Cadastrar Endereço
                    </button>

                <?php endif; ?>

            </form>

        </div>

    </div>


    <h2 class="mb-3">
        Endereços cadastrados
    </h2>


    <!-- PESQUISA -->

    <form method="GET" class="row g-2 mb-4">

        <div class="col-md-9">

            <input
                type="text"
                name="pesquisa"
                class="form-control"
                placeholder="Pesquisar por CEP, logradouro, bairro, cidade ou UF..."
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
                    href="enderecos.php"
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
                    <th>CEP</th>
                    <th>Logradouro</th>
                    <th>Número</th>
                    <th>Complemento</th>
                    <th>Bairro</th>
                    <th>Cidade</th>
                    <th>UF</th>
                    <th>Ações</th>

                </tr>

            </thead>

            <tbody>

                <?php if ($enderecos && $enderecos->num_rows > 0): ?>

                    <?php while ($endereco = $enderecos->fetch_assoc()): ?>

                        <tr>

                            <td>
                                <?= $endereco["id"] ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($endereco["cep"]) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($endereco["logradouro"]) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($endereco["numero"]) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($endereco["complemento"]) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($endereco["bairro"]) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($endereco["cidade"]) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($endereco["estado"]) ?>
                            </td>

                            <td>

                                <a
                                    href="enderecos.php?editar=<?= $endereco["id"] ?>"
                                    class="btn btn-sm btn-warning"
                                >
                                    ✏️ Editar
                                </a>

                                <a
                                    href="enderecos.php?excluir=<?= $endereco["id"] ?>"
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('Tem certeza que deseja excluir este endereço?');"
                                >
                                    🗑️ Excluir
                                </a>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>

                        <td
                            colspan="9"
                            class="text-center"
                        >
                            Nenhum endereço encontrado.
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