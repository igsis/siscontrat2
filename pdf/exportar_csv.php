<?php

require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");

$con = bancoMysqli();

if(isset($_POST['exportar'])) {

    $sql = $_POST['sqlConsulta'];

    $query = mysqli_query($con, $sql);

    header('Content-type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=eventos.csv');

    $arquivo = fopen("php://output", "w");
    fputcsv($arquivo, array('Nome do Evento', 'Categoria', 'Data de Inicio', 'Horario de Inicio', 'Valor', 'Descrição', 'Local'));

    while ($linha = mysqli_fetch_assoc($query)) {

        $categorias = '';
        $SqlAcao_atracao = "SELECT acao_id FROM acao_atracao WHERE atracao_id = '" . $linha['atracao_id'] . "'";

        $queryAcaoAtracao = mysqli_query($con, $SqlAcao_atracao);

        while ($acao_atracao = mysqli_fetch_array($queryAcaoAtracao)) {
            $sqlAcao = "SELECT acao FROM acoes WHERE id = '" . $acao_atracao['acao_id'] . "'";
            $queryAction = mysqli_query($con, $sqlAcao);

            while ($acoes = mysqli_fetch_array($queryAction)) {
                $categorias .= $acoes['acao'] . "; ";
            }
        }


        fputcsv($arquivo, $linha);

    }

    fclose($arquivo);

}