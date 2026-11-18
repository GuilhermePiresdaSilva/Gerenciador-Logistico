<?php

require_once "conexao.php";

$data_selecionada = "";
$resultado = null;

if (isset($_GET["data"])) {

    $data_selecionada = $_GET["data"];

    $sql = "SELECT
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

            WHERE f.data_frete = '$data_selecionada'

            ORDER BY f.id ASC";

    $resultado = $conexao->query($sql);
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Relatórios - Gerenciador Logístico</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>

<body>


<!-- MENU -->

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">

    <div class="container">

        <a
            class="navbar-brand fw-bold"
            href="index.php"
        >
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


        <div
            class="collapse navbar-collapse"
            id="menu"
        >

            <ul class="navbar-nav ms-auto">

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="index.php"
                    >
                        Início
                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="produtos.php"
                    >
                        Produtos
                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="enderecos.php"
                    >
                        Endereços
                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="transportadores.php"
                    >
                        Transportadores
                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="fretes.php"
                    >
                        Fretes
                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link active"
                        href="relatorio.php"
                    >
                        Relatórios
                    </a>

                </li>

            </ul>

        </div>

    </div>

</nav>


<!-- CONTEÚDO -->

<main class="container py-5">


    <div class="mb-4">

        <h1 class="fw-bold">
            Relatório de Fretes
        </h1>

        <p class="text-muted">
            Consulte os fretes realizados em uma determinada data.
        </p>

    </div>


    <!-- FILTRO -->

    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <h5 class="card-title mb-3">
                Selecionar data
            </h5>


            <form method="GET">

                <div class="row g-3 align-items-end">

                    <div class="col-md-6">

                        <label
                            for="data"
                            class="form-label"
                        >
                            Data do frete
                        </label>

                        <input
                            type="date"
                            class="form-control"
                            id="data"
                            name="data"
                            value="<?php echo $data_selecionada; ?>"
                            required
                        >

                    </div>


                    <div class="col-md-6">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            🔎 Consultar Fretes
                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    <?php if ($resultado !== null) { ?>


        <!-- RESULTADO -->

        <div class="card shadow-sm">

            <div class="card-body">


                <div class="d-flex justify-content-between align-items-center mb-4">

                    <div>

                        <h5 class="card-title mb-1">
                            Fretes do dia
                        </h5>

                        <p class="text-muted mb-0">

                            <?php
                            echo date(
                                "d/m/Y",
                                strtotime($data_selecionada)
                            );
                            ?>

                        </p>

                    </div>


                    <?php if ($resultado->num_rows > 0) { ?>

                        <a
                            href="baixar_relatorio.php?data=<?php echo $data_selecionada; ?>"
                            class="btn btn-success"
                        >
                            ⬇️ Baixar Relatório
                        </a>

                    <?php } ?>


                </div>


                <?php if ($resultado->num_rows > 0) { ?>


                    <div class="table-responsive">

                        <table class="table table-striped table-hover align-middle">

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

                                </tr>

                            </thead>


                            <tbody>


                                <?php

                                $total = 0;

                                while ($frete = $resultado->fetch_assoc()) {

                                    $total += $frete["valor_frete"];

                                ?>


                                    <tr>

                                        <td>
                                            <?php echo $frete["id"]; ?>
                                        </td>

                                        <td>
                                            <?php
                                            echo date(
                                                "d/m/Y",
                                                strtotime($frete["data_frete"])
                                            );
                                            ?>
                                        </td>

                                        <td>
                                            <?php echo $frete["cidade_origem"]; ?>
                                        </td>

                                        <td>
                                            <?php echo $frete["cidade_destino"]; ?>
                                        </td>

                                        <td>
                                            <?php echo $frete["produto"]; ?>
                                        </td>

                                        <td>
                                            <?php echo $frete["quantidade"]; ?>
                                        </td>

                                        <td>
                                            <?php echo $frete["transportador"]; ?>
                                        </td>

                                        <td>

                                            R$

                                            <?php
                                            echo number_format(
                                                $frete["valor_frete"],
                                                2,
                                                ",",
                                                "."
                                            );
                                            ?>

                                        </td>

                                        <td>

                                            <?php

                                            if ($frete["status"] == "Pendente") {

                                                echo "<span class='badge text-bg-warning'>
                                                        Pendente
                                                      </span>";

                                            } elseif ($frete["status"] == "Em transporte") {

                                                echo "<span class='badge text-bg-primary'>
                                                        Em transporte
                                                      </span>";

                                            } else {

                                                echo "<span class='badge text-bg-success'>
                                                        Concluído
                                                      </span>";

                                            }

                                            ?>

                                        </td>

                                    </tr>


                                <?php } ?>


                            </tbody>

                        </table>

                    </div>


                    <!-- TOTAL -->

                    <div class="text-end mt-3">

                        <h5>

                            Total dos fretes:

                            <strong>

                                R$

                                <?php
                                echo number_format(
                                    $total,
                                    2,
                                    ",",
                                    "."
                                );
                                ?>

                            </strong>

                        </h5>

                    </div>


                <?php } else { ?>


                    <div class="alert alert-warning">

                        Nenhum frete encontrado para a data selecionada.

                    </div>


                <?php } ?>


            </div>

        </div>


    <?php } ?>


</main>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>

</body>

</html>