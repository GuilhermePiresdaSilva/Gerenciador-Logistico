<?php

require_once "conexao.php";


/* VERIFICAR DATA */

if (!isset($_GET["data"]) || empty($_GET["data"])) {

    die("Data não informada.");

}

$data = $_GET["data"];


/* BUSCAR FRETES */

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

        WHERE f.data_frete = '$data'

        ORDER BY f.id ASC";


$resultado = $conexao->query($sql);


if (!$resultado) {

    die("Erro ao gerar relatório: " . $conexao->error);

}


/* CONFIGURAR DOWNLOAD */

$nome_arquivo = "relatorio_fretes_" . $data . ".csv";


header("Content-Type: text/csv; charset=UTF-8");
header("Content-Disposition: attachment; filename=\"$nome_arquivo\"");


/* ABRIR SAÍDA */

$arquivo = fopen("php://output", "w");


/* BOM PARA O EXCEL RECONHECER UTF-8 */

fprintf($arquivo, chr(0xEF) . chr(0xBB) . chr(0xBF));


/* CABEÇALHO */

fputcsv(
    $arquivo,
    [
        "ID",
        "Data",
        "Origem",
        "Destino",
        "Produto",
        "Quantidade",
        "Transportador",
        "Valor do Frete",
        "Status",
        "Observações"
    ],
    ";"
);


/* DADOS */

while ($frete = $resultado->fetch_assoc()) {

    fputcsv(
        $arquivo,
        [
            $frete["id"],
            date("d/m/Y", strtotime($frete["data_frete"])),
            $frete["cidade_origem"],
            $frete["cidade_destino"],
            $frete["produto"],
            $frete["quantidade"],
            $frete["transportador"],
            number_format(
                $frete["valor_frete"],
                2,
                ",",
                "."
            ),
            $frete["status"],
            $frete["observacoes"]
        ],
        ";"
    );

}


fclose($arquivo);

exit;

?>