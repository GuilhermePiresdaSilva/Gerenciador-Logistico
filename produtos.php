<?php

require_once "conexao.php";

$editando = false;
$produto_edicao = null;

/* EXCLUIR PRODUTO */
if (isset($_GET["excluir"])) {

    $id = intval($_GET["excluir"]);

    // Verifica se o produto está vinculado a algum frete
    $verificar = $conexao->query(
        "SELECT COUNT(*) AS total
         FROM frete_produtos
         WHERE produto_id = $id"
    );

    if ($verificar) {

        $vinculo = $verificar->fetch_assoc();

        if ($vinculo["total"] > 0) {

            $erro = "Não é possível excluir este produto porque ele está vinculado a um frete. Exclua primeiro o frete relacionado.";

        } else {

            $sql = "DELETE FROM produtos WHERE id = $id";

            if ($conexao->query($sql)) {
                header("Location: produtos.php");
                exit;
            } else {
                $erro = "Erro ao excluir produto.";
            }
        }

    } else {

        $erro = "Não foi possível verificar se o produto está vinculado a um frete.";
    }
}

/* CADASTRAR PRODUTO */
if (isset($_POST["cadastrar"])) {

    $codigo = $_POST["codigo"];
    $nome = $_POST["nome"];
    $descricao = $_POST["descricao"];
    $peso = $_POST["peso"];
    $quantidade = $_POST["quantidade"];

    $sql = "INSERT INTO produtos
            (codigo, nome, descricao, peso, quantidade)
            VALUES
            ('$codigo', '$nome', '$descricao', '$peso', '$quantidade')";

    if ($conexao->query($sql)) {
        header("Location: produtos.php");
        exit;
    }
}

/* BUSCAR PRODUTO PARA EDITAR */
if (isset($_GET["editar"])) {

    $id = intval($_GET["editar"]);

    $sql = "SELECT * FROM produtos WHERE id = $id";
    $resultado = $conexao->query($sql);

    if ($resultado && $resultado->num_rows > 0) {
        $produto_edicao = $resultado->fetch_assoc();
        $editando = true;
    }
}

/* ATUALIZAR PRODUTO */
if (isset($_POST["atualizar"])) {

    $id = intval($_POST["id"]);
    $codigo = $_POST["codigo"];
    $nome = $_POST["nome"];
    $descricao = $_POST["descricao"];
    $peso = $_POST["peso"];
    $quantidade = $_POST["quantidade"];

    $sql = "UPDATE produtos SET
                codigo = '$codigo',
                nome = '$nome',
                descricao = '$descricao',
                peso = '$peso',
                quantidade = '$quantidade'
            WHERE id = $id";

    if ($conexao->query($sql)) {
        header("Location: produtos.php");
        exit;
    }
}

/* PESQUISA DE PRODUTOS */
$pesquisa = "";

if (isset($_GET["pesquisa"])) {
    $pesquisa = trim($_GET["pesquisa"]);
}

if ($pesquisa !== "") {

    $pesquisa_sql = $conexao->real_escape_string($pesquisa);

    $sql = "SELECT * FROM produtos
            WHERE codigo LIKE '%$pesquisa_sql%'
               OR nome LIKE '%$pesquisa_sql%'
               OR descricao LIKE '%$pesquisa_sql%'
            ORDER BY id DESC";

} else {

    $sql = "SELECT * FROM produtos ORDER BY id DESC";
}

$resultado = $conexao->query($sql);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Produtos - Gerenciador Logístico</title>

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
                    <a class="nav-link active" href="produtos.php">
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

<div class="container py-5">

    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <h2 class="mb-4">
                <?= $editando ? "Editar Produto" : "Cadastro de Produto" ?>
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
                        value="<?= $produto_edicao["id"] ?>"
                    >

                <?php endif; ?>

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Código
                        </label>

                        <input
                            type="text"
                            name="codigo"
                            class="form-control"
                            required
                            value="<?= $editando ? htmlspecialchars($produto_edicao["codigo"]) : "" ?>"
                        >

                    </div>

                    <div class="col-md-8 mb-3">

                        <label class="form-label">
                            Nome
                        </label>

                        <input
                            type="text"
                            name="nome"
                            class="form-control"
                            required
                            value="<?= $editando ? htmlspecialchars($produto_edicao["nome"]) : "" ?>"
                        >

                    </div>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Descrição
                    </label>

                    <textarea
                        name="descricao"
                        class="form-control"
                        rows="3"
                        required
                    ><?= $editando ? htmlspecialchars($produto_edicao["descricao"]) : "" ?></textarea>

                </div>

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Peso (kg)
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            name="peso"
                            class="form-control"
                            required
                            value="<?= $editando ? $produto_edicao["peso"] : "" ?>"
                        >

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Quantidade
                        </label>

                        <input
                            type="number"
                            name="quantidade"
                            class="form-control"
                            required
                            value="<?= $editando ? $produto_edicao["quantidade"] : "" ?>"
                        >

                    </div>

                </div>

                <?php if ($editando): ?>

                    <button
                        type="submit"
                        name="atualizar"
                        class="btn btn-success"
                    >
                        Atualizar Produto
                    </button>

                    <a
                        href="produtos.php"
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
                        Cadastrar Produto
                    </button>

                <?php endif; ?>

            </form>

        </div>

    </div>

    <div class="card shadow-sm">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <h2 class="mb-0">
                    Produtos Cadastrados
                </h2>

            </div>

            <!-- PESQUISA -->
            <form method="GET" class="row g-2 mb-4">

                <div class="col-md-9">

                    <input
                        type="text"
                        name="pesquisa"
                        class="form-control"
                        placeholder="Pesquisar por código, nome ou descrição..."
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
                            href="produtos.php"
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
                            <th>Código</th>
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th>Peso</th>
                            <th>Quantidade</th>
                            <th>Ações</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php if ($resultado && $resultado->num_rows > 0): ?>

                            <?php while ($produto = $resultado->fetch_assoc()): ?>

                                <tr>

                                    <td>
                                        <?= $produto["id"] ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($produto["codigo"]) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($produto["nome"]) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($produto["descricao"]) ?>
                                    </td>

                                    <td>
                                        <?= number_format($produto["peso"], 2, ",", ".") ?> kg
                                    </td>

                                    <td>
                                        <?= $produto["quantidade"] ?>
                                    </td>

                                    <td>

                                        <a
                                            href="produtos.php?editar=<?= $produto["id"] ?>"
                                            class="btn btn-sm btn-warning"
                                        >
                                            Editar
                                        </a>

                                        <a
                                            href="produtos.php?excluir=<?= $produto["id"] ?>"
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Tem certeza que deseja excluir este produto?');"
                                        >
                                            Excluir
                                        </a>

                                    </td>

                                </tr>

                            <?php endwhile; ?>

                        <?php else: ?>

                            <tr>

                                <td colspan="7" class="text-center">

                                    Nenhum produto encontrado.

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