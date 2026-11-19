<?php

require_once "conexao.php";

$editando = false;
$frete_edicao = null;

/* EXCLUIR FRETE */
if (isset($_GET["excluir"])) {

    $id = intval($_GET["excluir"]);

    // Primeiro exclui os produtos vinculados ao frete
    $sql_produtos = "DELETE FROM frete_produtos WHERE frete_id = $id";
    $conexao->query($sql_produtos);

    // Depois exclui o frete
    $sql_frete = "DELETE FROM fretes WHERE id = $id";

    if ($conexao->query($sql_frete)) {
        header("Location: fretes.php");
        exit;
    } else {
        $erro = "Não foi possível excluir o frete.";
    }
}

/* CADASTRAR FRETE */
if (isset($_POST["cadastrar"])) {

    $data_frete = $_POST["data_frete"];
    $origem_id = intval($_POST["origem_id"]);
    $destino_id = intval($_POST["destino_id"]);
    $transportador_id = intval($_POST["transportador_id"]);
    $produto_id = intval($_POST["produto_id"]);
    $quantidade = intval($_POST["quantidade"]);
    $valor_frete = $_POST["valor_frete"];
    $status = $_POST["status"];
    $observacoes = $_POST["observacoes"];

    $sql = "INSERT INTO fretes
            (data_frete, origem_id, destino_id, transportador_id, valor_frete, status, observacoes)
            VALUES
            ('$data_frete', $origem_id, $destino_id, $transportador_id, '$valor_frete', '$status', '$observacoes')";

    if ($conexao->query($sql)) {

        $frete_id = $conexao->insert_id;

        $sql_produto = "INSERT INTO frete_produtos
                        (frete_id, produto_id, quantidade)
                        VALUES
                        ($frete_id, $produto_id, $quantidade)";

        $conexao->query($sql_produto);

        header("Location: fretes.php");
        exit;
    }
}

/* EDITAR FRETE */
if (isset($_GET["editar"])) {

    $id = intval($_GET["editar"]);

    $sql = "SELECT
                f.*,
                fp.produto_id,
                fp.quantidade
            FROM fretes f
            INNER JOIN frete_produtos fp
                ON f.id = fp.frete_id
            WHERE f.id = $id";

    $resultado_edicao = $conexao->query($sql);

    if ($resultado_edicao && $resultado_edicao->num_rows > 0) {

        $editando = true;
        $frete_edicao = $resultado_edicao->fetch_assoc();
    }
}

/* ATUALIZAR FRETE */
if (isset($_POST["atualizar"])) {

    $id = intval($_POST["id"]);

    $data_frete = $_POST["data_frete"];
    $origem_id = intval($_POST["origem_id"]);
    $destino_id = intval($_POST["destino_id"]);
    $transportador_id = intval($_POST["transportador_id"]);
    $produto_id = intval($_POST["produto_id"]);
    $quantidade = intval($_POST["quantidade"]);
    $valor_frete = $_POST["valor_frete"];
    $status = $_POST["status"];
    $observacoes = $_POST["observacoes"];

    $sql = "UPDATE fretes SET
                data_frete = '$data_frete',
                origem_id = $origem_id,
                destino_id = $destino_id,
                transportador_id = $transportador_id,
                valor_frete = '$valor_frete',
                status = '$status',
                observacoes = '$observacoes'
            WHERE id = $id";

    if ($conexao->query($sql)) {

        $sql_produto = "UPDATE frete_produtos SET
                            produto_id = $produto_id,
                            quantidade = $quantidade
                        WHERE frete_id = $id";

        $conexao->query($sql_produto);

        header("Location: fretes.php");
        exit;
    }
}

/* LISTAR ENDEREÇOS */
$enderecos = $conexao->query(
    "SELECT * FROM enderecos ORDER BY cidade ASC"
);

/* LISTAR TRANSPORTADORES */
$transportadores = $conexao->query(
    "SELECT * FROM transportadores ORDER BY nome ASC"
);

/* LISTAR PRODUTOS */
$produtos = $conexao->query(
    "SELECT * FROM produtos ORDER BY nome ASC"
);


/* PESQUISA DE FRETES */
$pesquisa = "";

if (isset($_GET["pesquisa"])) {
    $pesquisa = trim($_GET["pesquisa"]);
}

$pesquisa_sql = $conexao->real_escape_string($pesquisa);


/* LISTAR FRETES */

if ($pesquisa !== "") {

    $sql_fretes = "SELECT
            f.id,
            f.data_frete,
            origem.cidade AS cidade_origem,
            destino.cidade AS cidade_destino,
            t.nome AS transportador,
            p.nome AS produto,
            fp.quantidade,
            f.valor_frete,
            f.status,
            f.observacoes
        FROM fretes f
        INNER JOIN enderecos origem
            ON f.origem_id = origem.id
        INNER JOIN enderecos destino
            ON f.destino_id = destino.id
        INNER JOIN transportadores t
            ON f.transportador_id = t.id
        INNER JOIN frete_produtos fp
            ON f.id = fp.frete_id
        INNER JOIN produtos p
            ON fp.produto_id = p.id
        WHERE CAST(f.id AS CHAR) LIKE '%$pesquisa_sql%'
           OR DATE_FORMAT(f.data_frete, '%d/%m/%Y') LIKE '%$pesquisa_sql%'
           OR f.data_frete LIKE '%$pesquisa_sql%'
           OR origem.cidade LIKE '%$pesquisa_sql%'
           OR destino.cidade LIKE '%$pesquisa_sql%'
           OR t.nome LIKE '%$pesquisa_sql%'
           OR p.nome LIKE '%$pesquisa_sql%'
           OR f.status LIKE '%$pesquisa_sql%'
        ORDER BY f.id DESC";

} else {

    $sql_fretes = "SELECT
            f.id,
            f.data_frete,
            origem.cidade AS cidade_origem,
            destino.cidade AS cidade_destino,
            t.nome AS transportador,
            p.nome AS produto,
            fp.quantidade,
            f.valor_frete,
            f.status,
            f.observacoes
        FROM fretes f
        INNER JOIN enderecos origem
            ON f.origem_id = origem.id
        INNER JOIN enderecos destino
            ON f.destino_id = destino.id
        INNER JOIN transportadores t
            ON f.transportador_id = t.id
        INNER JOIN frete_produtos fp
            ON f.id = fp.frete_id
        INNER JOIN produtos p
            ON fp.produto_id = p.id
        ORDER BY f.id DESC";
}

$fretes = $conexao->query($sql_fretes);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Fretes - Gerenciador Logístico</title>

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
                    <a class="nav-link" href="transportadores.php">
                        Transportadores
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link active" href="fretes.php">
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


<div class="container py-5">

    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <h2 class="mb-4">
                <?= $editando ? "Editar Frete" : "Cadastro de Frete" ?>
            </h2>

            <?php if (isset($erro)): ?>

                <div class="alert alert-danger">
                    <?= $erro ?>
                </div>

            <?php endif; ?>


            <form method="POST">

                <?php if ($editando): ?>

                    <input
                        type="hidden"
                        name="id"
                        value="<?= $frete_edicao["id"] ?>"
                    >

                <?php endif; ?>


                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Data do frete
                        </label>

                        <input
                            type="date"
                            name="data_frete"
                            class="form-control"
                            value="<?= $editando ? $frete_edicao["data_frete"] : date("Y-m-d") ?>"
                            required
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Origem
                        </label>

                        <select
                            name="origem_id"
                            class="form-select"
                            required
                        >

                            <option value="">
                                Selecione a origem
                            </option>

                            <?php while ($endereco = $enderecos->fetch_assoc()): ?>

                                <option
                                    value="<?= $endereco["id"] ?>"
                                    <?= $editando && $frete_edicao["origem_id"] == $endereco["id"] ? "selected" : "" ?>
                                >
                                    <?= htmlspecialchars($endereco["logradouro"]) ?>,
                                    <?= htmlspecialchars($endereco["numero"]) ?> -
                                    <?= htmlspecialchars($endereco["cidade"]) ?>
                                </option>

                            <?php endwhile; ?>

                        </select>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Destino
                        </label>

                        <select
                            name="destino_id"
                            class="form-select"
                            required
                        >

                            <option value="">
                                Selecione o destino
                            </option>

                            <?php

                            $enderecos_destino = $conexao->query(
                                "SELECT * FROM enderecos ORDER BY cidade ASC"
                            );

                            while ($endereco = $enderecos_destino->fetch_assoc()):

                            ?>

                                <option
                                    value="<?= $endereco["id"] ?>"
                                    <?= $editando && $frete_edicao["destino_id"] == $endereco["id"] ? "selected" : "" ?>
                                >
                                    <?= htmlspecialchars($endereco["logradouro"]) ?>,
                                    <?= htmlspecialchars($endereco["numero"]) ?> -
                                    <?= htmlspecialchars($endereco["cidade"]) ?>
                                </option>

                            <?php endwhile; ?>

                        </select>

                    </div>

                </div>


                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Transportador
                        </label>

                        <select
                            name="transportador_id"
                            class="form-select"
                            required
                        >

                            <option value="">
                                Selecione o transportador
                            </option>

                            <?php while ($transportador = $transportadores->fetch_assoc()): ?>

                                <option
                                    value="<?= $transportador["id"] ?>"
                                    <?= $editando && $frete_edicao["transportador_id"] == $transportador["id"] ? "selected" : "" ?>
                                >
                                    <?= htmlspecialchars($transportador["nome"]) ?>
                                </option>

                            <?php endwhile; ?>

                        </select>

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Produto
                        </label>

                        <select
                            name="produto_id"
                            class="form-select"
                            required
                        >

                            <option value="">
                                Selecione o produto
                            </option>

                            <?php while ($produto = $produtos->fetch_assoc()): ?>

                                <option
                                    value="<?= $produto["id"] ?>"
                                    <?= $editando && $frete_edicao["produto_id"] == $produto["id"] ? "selected" : "" ?>
                                >
                                    <?= htmlspecialchars($produto["nome"]) ?>
                                </option>

                            <?php endwhile; ?>

                        </select>

                    </div>

                </div>


                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Quantidade
                        </label>

                        <input
                            type="number"
                            name="quantidade"
                            class="form-control"
                            min="1"
                            value="<?= $editando ? $frete_edicao["quantidade"] : "1" ?>"
                            required
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Valor do frete
                        </label>

                        <input
                            type="number"
                            name="valor_frete"
                            class="form-control"
                            step="0.01"
                            min="0"
                            value="<?= $editando ? $frete_edicao["valor_frete"] : "" ?>"
                            required
                        >

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Status
                        </label>

                        <select
                            name="status"
                            class="form-select"
                            required
                        >

                            <option
                                value="Pendente"
                                <?= $editando && $frete_edicao["status"] == "Pendente" ? "selected" : "" ?>
                            >
                                Pendente
                            </option>

                            <option
                                value="Em transporte"
                                <?= $editando && $frete_edicao["status"] == "Em transporte" ? "selected" : "" ?>
                            >
                                Em transporte
                            </option>

                            <option
                                value="Entregue"
                                <?= $editando && $frete_edicao["status"] == "Entregue" ? "selected" : "" ?>
                            >
                                Entregue
                            </option>

                            <option
                                value="Cancelado"
                                <?= $editando && $frete_edicao["status"] == "Cancelado" ? "selected" : "" ?>
                            >
                                Cancelado
                            </option>

                        </select>

                    </div>

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Observações
                    </label>

                    <textarea
                        name="observacoes"
                        class="form-control"
                        rows="3"
                    ><?= $editando ? htmlspecialchars($frete_edicao["observacoes"]) : "" ?></textarea>

                </div>


                <?php if ($editando): ?>

                    <button
                        type="submit"
                        name="atualizar"
                        class="btn btn-warning"
                    >
                        Atualizar Frete
                    </button>

                    <a
                        href="fretes.php"
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
                        Cadastrar Frete
                    </button>

                <?php endif; ?>

            </form>

        </div>

    </div>


    <div class="card shadow-sm">

        <div class="card-body">

            <h2 class="mb-4">
                Fretes cadastrados
            </h2>


            <!-- PESQUISA -->

            <form method="GET" class="row g-2 mb-4">

                <div class="col-md-9">

                    <input
                        type="text"
                        name="pesquisa"
                        class="form-control"
                        placeholder="Pesquisar por ID, data, origem, destino, transportador, produto ou status..."
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
                            href="fretes.php"
                            class="btn btn-secondary"
                        >
                            Limpar
                        </a>

                    <?php endif; ?>

                </div>

            </form>


            <div class="table-responsive">

                <table class="table table-bordered table-striped align-middle">

                    <thead class="table-dark">

                        <tr>

                            <th>ID</th>
                            <th>Data</th>
                            <th>Origem</th>
                            <th>Destino</th>
                            <th>Produto</th>
                            <th>Qtd.</th>
                            <th>Transportador</th>
                            <th>Valor</th>
                            <th>Status</th>
                            <th>Ações</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php if ($fretes && $fretes->num_rows > 0): ?>

                            <?php while ($frete = $fretes->fetch_assoc()): ?>

                                <tr>

                                    <td>
                                        <?= $frete["id"] ?>
                                    </td>

                                    <td>
                                        <?= date("d/m/Y", strtotime($frete["data_frete"])) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($frete["cidade_origem"]) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($frete["cidade_destino"]) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($frete["produto"]) ?>
                                    </td>

                                    <td>
                                        <?= $frete["quantidade"] ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($frete["transportador"]) ?>
                                    </td>

                                    <td>
                                        R$
                                        <?= number_format($frete["valor_frete"], 2, ",", ".") ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($frete["status"]) ?>
                                    </td>

                                    <td>

                                        <a
                                            href="fretes.php?editar=<?= $frete["id"] ?>"
                                            class="btn btn-sm btn-warning"
                                        >
                                            ✏️ Editar
                                        </a>

                                        <a
                                            href="fretes.php?excluir=<?= $frete["id"] ?>"
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Tem certeza que deseja excluir este frete? Esta ação não poderá ser desfeita.');"
                                        >
                                            🗑️ Excluir
                                        </a>

                                    </td>

                                </tr>

                            <?php endwhile; ?>

                        <?php else: ?>

                            <tr>

                                <td
                                    colspan="10"
                                    class="text-center"
                                >
                                    Nenhum frete encontrado.
                                </td>

                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>

</body>

</html>