<?php
setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');

include '../funcoes/funcoesConecta.php';
$con = bancoMysqli();
// Incluimos a classe PHPExcel
require_once("../include/phpexcel/Classes/PHPExcel.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");

$sql = $_POST['sql'];
$query = mysqli_query($con, $sql);

$sqlAgendao = $_POST['sqlAgendao'];
$queryAgendao = mysqli_query($con, $sqlAgendao);
// Instanciamos a classe
$objPHPExcel = new PHPExcel();


// Podemos renomear o nome das planilha atual, lembrando que um único arquivo pode ter várias planilhas
$objPHPExcel->getProperties()->setCreator("Sistema SisContrat");
$objPHPExcel->getProperties()->setLastModifiedBy("Sistema SisContrat");
$objPHPExcel->getProperties()->setTitle("Relatório de Controle de Fotos");
$objPHPExcel->getProperties()->setSubject("Relatório de Controle de Fotos");
$objPHPExcel->getProperties()->setDescription("Gerado automaticamente a partir do Sistema SisContrat");
$objPHPExcel->getProperties()->setKeywords("office 2007 openxml php");
$objPHPExcel->getProperties()->setCategory("Inscritos");


$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue("A1")
    ->setCellValue("B1")
    ->setCellValue("C1")
    ->setCellValue("D1")
    ->setCellValue("E1")
    ->setCellValue("F1")
    ->setCellValue("G1")
    ->setCellValue("H1")
    ->setCellValue("I1")
    ->setCellValue("J1")
    ->setCellValue("K1")
    ->setCellValue("L1")
    ->setCellValue("M1", "Eventos Comum")
    ->setCellValue("N1")
    ->setCellValue("O1")
    ->setCellValue("P1")
    ->setCellValue("Q1")
    ->setCellValue("R1")
    ->setCellValue("S1")
    ->setCellValue("T1")
    ->setCellValue("U1")
    ->setCellValue("V1")
    ->setCellValue("W1")
    ->setCellValue("X1");

$objPHPExcel->getActiveSheet()->getStyle('A1:X1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A1:X1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(40);
$objPHPExcel->getActiveSheet()->getStyle('A1:X1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);


// Criamos as colunas
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A2', 'Instituição/Coordenadoria')
    ->setCellValue("B2", "Local do Evento")
    ->setCellValue("C2", "Endereço Completo")
    ->setCellValue("D2", "SubPrefeitura")
    ->setCellValue("E2", "Nome do Evento")
    ->setCellValue("F2", "Artistas")
    ->setCellValue("G2", "Data de Início")
    ->setCellValue("H2", "Data de Encerramento")
    ->setCellValue("I2", "Horário de início")
    ->setCellValue("J2", "Nº de Apresentações")
    ->setCellValue("K2", "Período")
    ->setCellValue("L2", "Linguagem / Expressão Artística Principal")
    ->setCellValue("M2", "Público / Representatividade Social Principal")
    ->setCellValue("N2", "Espaço Público?")
    ->setCellValue("O2", "Entrada")
    ->setCellValue("P2", "Valor do Ingresso (no caso de cobrança)")
    ->setCellValue("Q2", "Classificação indicativa")
    ->setCellValue("R2", "Link de Divulgação")
    ->setCellValue("S2", "Sinopse")
    ->setCellValue("T2", "Calendário Macro")
    ->setCellValue("U2", "Caso Seja Fomento / Programa da smc Qual o Fomento ou Programa?")
    ->setCellValue("V2", "Produtor do Evento")
    ->setCellValue("W2", "E-mail de contato")
    ->setCellValue("X2", "Telefone de contato");

// Definimos o estilo da fonte
$objPHPExcel->getActiveSheet()->getStyle('A2:X2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A2:X2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getRowDimension(2)->setRowHeight(25);
$objPHPExcel->getActiveSheet()->getStyle('A2:X2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

//Colorir a primeira linha
$objPHPExcel->getActiveSheet()->getStyle('A1:X1')->applyFromArray
(
    array
    (
        'fill' => array
        (
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '3c8dbc')
        ),
    )
);


//Colorir a primeira linha
$objPHPExcel->getActiveSheet()->getStyle('A2:X2')->applyFromArray
(
    array
    (
        'fill' => array
        (
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'E0EEEE')
        ),
    )
);

$cont = 3;
while ($linha = mysqli_fetch_array($query)) {

    if ($linha['tipo_evento'] == 2) {
        $filme_evento = recuperaDados("filme_eventos", "evento_id", $linha['evento_id']);

        $filme = recuperaDados("filmes", "id", $filme_evento['filme_id']);
        $classificao = recuperaDados("classificacao_indicativas", "id", $filme['classificacao_indicativa_id']);

        $linha ['classificacao'] = $classificao['classificacao_indicativa'];
    }


    $totalDias = '';
    $dias = "";
    $linha['segunda'] == 1 ? $dias .= "Segunda, " : '';
    $linha['terca'] == 1 ? $dias .= "Terça, " : '';
    $linha['quarta'] == 1 ? $dias .= "Quarta, " : '';
    $linha['quinta'] == 1 ? $dias .= "Quinta, " : '';
    $linha['sexta'] == 1 ? $dias .= "Sexta, " : '';
    $linha['sabado'] == 1 ? $dias .= "Sabádo, " : '';
    $linha['domingo'] == 1 ? $dias .= "Domingo. " : '';

    if ($dias != "") {
        $totalDias .= substr($dias, 0, -2);
    } else {
        $totalDias .= "Dias não especificados.";
    }

    //Ações
    $sqlAcao = "SELECT * FROM acao_evento WHERE evento_id = '" . $linha['evento_id'] . "'";
    $queryAcao = mysqli_query($con, $sqlAcao);
    $acoes = [];
    $i = 0;

    while ($arrayAcoes = mysqli_fetch_array($queryAcao)) {
        $idAcao = $arrayAcoes['acao_id'];
        $sqlLinguagens = "SELECT * FROM acoes WHERE id = '$idAcao'";
        $linguagens = $con->query($sqlLinguagens)->fetch_assoc();
        $acoes[$i] = $linguagens['acao'];
        $i++;
    }

    if (count($acoes) != 0) {
        $stringAcoes = implode(", ", $acoes);
    }

    //Público
    $sqlPublico = "SELECT * FROM evento_publico WHERE evento_id = '" . $linha['evento_id'] . "'";
    $queryPublico = mysqli_query($con, $sqlPublico);
    $representatividade = [];
    $i = 0;

    while ($arrayPublico = mysqli_fetch_array($queryPublico)) {
        $idRepresentatividade = $arrayPublico['publico_id'];
        $sqlRepresen = "SELECT * FROM publicos WHERE id = '$idRepresentatividade'";
        $publicos = $con->query($sqlRepresen)->fetch_assoc();
        $representatividade[$i] = $publicos['publico'];
        $i++;
    }

    if (count($acoes) != 0) {
        $stringPublico = implode(", ", $representatividade);
    }

    if ($linha['fomento'] != 0) {
        $sqlFomento = "SELECT * FROM fomentos WHERE id = '" . $linha['fomento'] . "'";
        $fomento = $con->query($sqlFomento)->fetch_assoc();
    }

    $a = "A" . $cont;
    $b = "B" . $cont;
    $c = "C" . $cont;
    $d = "D" . $cont;
    $e = "E" . $cont;
    $f = "F" . $cont;
    $g = "G" . $cont;
    $h = "H" . $cont;
    $i = "I" . $cont;
    $j = "J" . $cont;
    $k = "K" . $cont;
    $l = "L" . $cont;
    $m = "M" . $cont;
    $n = "N" . $cont;
    $o = "O" . $cont;
    $p = "P" . $cont;
    $q = "Q" . $cont;
    $r = "R" . $cont;
    $s = "S" . $cont;
    $t = "T" . $cont;
    $u = "U" . $cont;
    $v = "V" . $cont;
    $w = "W" . $cont;
    $x = "X" . $cont;

    $enderecoCompleto = [
        $linha['logradouro'],
        $linha['numero'],
        $linha['bairro']
    ];

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue($a, $linha['instiSigla'])
        ->setCellValue($b, $linha['nome_local'])
        ->setCellValue($c, implode(", ", $enderecoCompleto) . " - CEP: " . $linha['cep'])
        ->setCellValue($d, $linha['subprefeitura'])
        ->setCellValue($e, $linha['nome'])
        ->setCellValue($f, $linha['artistas'])
        ->setCellValue($g, exibirDataBr($linha['data_inicio']))
        ->setCellValue($h, ($linha['data_fim'] == "0000-00-00") ? "Não é Temporada" : exibirDataBr($linha['data_fim']))
        ->setCellValue($i, exibirHora($linha['hora_inicio']))
        ->setCellValue($j, $linha['apresentacoes'] == '' ? 'Este evento é filme!' : $linha['apresentacoes'])
        ->setCellValue($k, $linha['periodo'])
        ->setCellValue($l, $stringAcoes ?? "Não foi selecionada linguagem.")
        ->setCellValue($m, $stringPublico ?? "Não foi selecionado público.")
        ->setCellValue($n, $linha['espaco_publico'] == 1 ? "SIM" : "NÃO")
        ->setCellValue($o, $linha['retirada'])
        ->setCellValue($p, $linha['valor_ingresso'] != '0.00' ? dinheiroParaBr($linha['valor_ingresso']) . " reais." : "Gratuito")
        ->setCellValue($q, $linha['classificacao'])
        ->setCellValue($r, isset($linha['divulgacao']) ? $linha['divulgacao'] : "Sem link de divulgação.")
        ->setCellValue($s, $linha['sinopse'])
        ->setCellValue($t, $linha['projeto_especial'])
        ->setCellValue($u, isset($fomento['fomento']) ? $fomento['fomento'] : "Não")
        ->setCellValue($v, $linha['produtor_nome'])
        ->setCellValue($w, $linha['produtor_email'])
        ->setCellValue($x, $linha['produtor_fone']);

    $objPHPExcel->getActiveSheet()->getStyle($a . ":" . $x)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($a . ":" . $x)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle($a . ":" . $x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->getActiveSheet()->getRowDimension($cont)->setRowHeight(20);

    $cont++;

}

$proxLinha = $cont + 1;


//Eventos Agendão


$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue("A" . $proxLinha)
    ->setCellValue("B" . $proxLinha)
    ->setCellValue("C" . $proxLinha)
    ->setCellValue("D" . $proxLinha)
    ->setCellValue("E" . $proxLinha)
    ->setCellValue("F" . $proxLinha)
    ->setCellValue("G" . $proxLinha)
    ->setCellValue("H" . $proxLinha)
    ->setCellValue("I" . $proxLinha)
    ->setCellValue("J" . $proxLinha)
    ->setCellValue("K" . $proxLinha)
    ->setCellValue("L" . $proxLinha)
    ->setCellValue("M" . $proxLinha, "Eventos Agendão")
    ->setCellValue("N" . $proxLinha)
    ->setCellValue("O" . $proxLinha)
    ->setCellValue("P" . $proxLinha)
    ->setCellValue("Q" . $proxLinha)
    ->setCellValue("R" . $proxLinha)
    ->setCellValue("S" . $proxLinha)
    ->setCellValue("T" . $proxLinha)
    ->setCellValue("U" . $proxLinha)
    ->setCellValue("V" . $proxLinha)
    ->setCellValue("W" . $proxLinha)
    ->setCellValue("X" . $proxLinha);

$objPHPExcel->getActiveSheet()->getStyle('A' . $proxLinha . ':X' . $proxLinha)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A' . $proxLinha . ':X' . $proxLinha)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getRowDimension($proxLinha)->setRowHeight(40);
$objPHPExcel->getActiveSheet()->getStyle('A' . $proxLinha . ':X' . $proxLinha)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);


$proxCabecalho = $proxLinha + 1;

// Criamos as colunas
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A' . $proxCabecalho, 'Instituição/Coordenadoria')
    ->setCellValue("B" . $proxCabecalho, "Local do Evento")
    ->setCellValue("C" . $proxCabecalho, "Endereço Completo")
    ->setCellValue("D" . $proxCabecalho, "SubPrefeitura")
    ->setCellValue("E" . $proxCabecalho, "Nome do Evento")
    ->setCellValue("F" . $proxCabecalho, "Artistas")
    ->setCellValue("G" . $proxCabecalho, "Data Início")
    ->setCellValue("H" . $proxCabecalho, "Data Fim")
    ->setCellValue("I" . $proxCabecalho, "Horário de início")
    ->setCellValue("J" . $proxCabecalho, "Nº de Apresentações")
    ->setCellValue("K" . $proxCabecalho, "Período")
    ->setCellValue("L" . $proxCabecalho, "Linguagem / Expressão Artística Principal")
    ->setCellValue("M" . $proxCabecalho, "Público / Representatividade Social Principal")
    ->setCellValue("N" . $proxCabecalho, "Espaço Público?")
    ->setCellValue("O" . $proxCabecalho, "Entrada")
    ->setCellValue("P" . $proxCabecalho, "Valor do Ingresso (no caso de cobrança)")
    ->setCellValue("Q" . $proxCabecalho, "Classificação indicativa")
    ->setCellValue("R" . $proxCabecalho, "Link de Divulgação")
    ->setCellValue("S" . $proxCabecalho, "Sinopse")
    ->setCellValue("T" . $proxCabecalho, "Calendário Macro")
    ->setCellValue("U" . $proxCabecalho, "Caso Seja Fomento / Programa da smc Qual o Fomento ou Programa?")
    ->setCellValue("V" . $proxCabecalho, "Produtor do Evento")
    ->setCellValue("W" . $proxCabecalho, "E-mail de contato")
    ->setCellValue("X" . $proxCabecalho, "Telefone de contato");

// Definimos o estilo da fonte
$objPHPExcel->getActiveSheet()->getStyle('A' . $proxCabecalho . ':X' . $proxCabecalho)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A' . $proxCabecalho . ':X' . $proxCabecalho)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getRowDimension($proxCabecalho)->setRowHeight(25);
$objPHPExcel->getActiveSheet()->getStyle('A' . $proxCabecalho . ':X' . $proxCabecalho)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

//Colorir a primeira linha
$objPHPExcel->getActiveSheet()->getStyle('A' . $proxLinha . ':X' . $proxLinha)->applyFromArray
(
    array
    (
        'fill' => array
        (
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '3c8dbc')
        ),
    )
);


//Colorir a primeira linha
$objPHPExcel->getActiveSheet()->getStyle('A' . $proxCabecalho . ':X' . $proxCabecalho)->applyFromArray
(
    array
    (
        'fill' => array
        (
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'E0EEEE')
        ),
    )
);

$cont = $proxCabecalho + 1;
while ($linha = mysqli_fetch_array($queryAgendao)) {

    if ($linha['tipo_evento'] == 2) {
        $filme_evento = recuperaDados("filme_eventos", "evento_id", $linha['evento_id']);

        $filme = recuperaDados("filmes", "id", $filme_evento['filme_id']);
        $classificao = recuperaDados("classificacao_indicativas", "id", $filme['classificacao_indicativa_id']);

        $linha ['classificacao'] = $classificao['classificacao_indicativa'];
    }


    $totalDias = '';
    $dias = "";
    $linha['segunda'] == 1 ? $dias .= "Segunda, " : '';
    $linha['terca'] == 1 ? $dias .= "Terça, " : '';
    $linha['quarta'] == 1 ? $dias .= "Quarta, " : '';
    $linha['quinta'] == 1 ? $dias .= "Quinta, " : '';
    $linha['sexta'] == 1 ? $dias .= "Sexta, " : '';
    $linha['sabado'] == 1 ? $dias .= "Sabádo, " : '';
    $linha['domingo'] == 1 ? $dias .= "Domingo. " : '';

    if ($dias != "") {
        //echo "dias diferente de vazio " . $respectiva . $dias;
        $totalDias .= substr($dias, 0, -2);
    } else {
        $totalDias .= "Dias não especificados.";
    }

    //Ações
    $sqlAcao = "SELECT * FROM acao_evento WHERE evento_id = '" . $linha['evento_id'] . "'";
    $queryAcao = mysqli_query($con, $sqlAcao);
    $acoes = [];
    $i = 0;

    while ($arrayAcoes = mysqli_fetch_array($queryAcao)) {
        $idAcao = $arrayAcoes['acao_id'];
        $sqlLinguagens = "SELECT * FROM acoes WHERE id = '$idAcao'";
        $linguagens = $con->query($sqlLinguagens)->fetch_assoc();
        $acoes[$i] = $linguagens['acao'];
        $i++;
    }

    if (count($acoes) != 0) {
        $stringAcoes = implode(", ", $acoes);
    }

    //Público
    $sqlPublico = "SELECT * FROM evento_publico WHERE evento_id = '" . $linha['evento_id'] . "'";
    $queryPublico = mysqli_query($con, $sqlPublico);
    $representatividade = [];
    $i = 0;

    while ($arrayPublico = mysqli_fetch_array($queryPublico)) {
        $idRepresentatividade = $arrayPublico['publico_id'];
        $sqlRepresen = "SELECT * FROM publicos WHERE id = '$idRepresentatividade'";
        $publicos = $con->query($sqlRepresen)->fetch_assoc();
        $representatividade[$i] = $publicos['publico'];
        $i++;
    }

    if (count($acoes) != 0) {
        $stringPublico = implode(", ", $representatividade);
    }

    if ($linha['fomento'] != 0) {
        $sqlFomento = "SELECT * FROM fomentos WHERE id = '" . $linha['fomento'] . "'";
        $fomento = $con->query($sqlFomento)->fetch_assoc();
    }

    $a = "A" . $cont;
    $b = "B" . $cont;
    $c = "C" . $cont;
    $d = "D" . $cont;
    $e = "E" . $cont;
    $f = "F" . $cont;
    $g = "G" . $cont;
    $h = "H" . $cont;
    $i = "I" . $cont;
    $j = "J" . $cont;
    $k = "K" . $cont;
    $l = "L" . $cont;
    $m = "M" . $cont;
    $n = "N" . $cont;
    $o = "O" . $cont;
    $p = "P" . $cont;
    $q = "Q" . $cont;
    $r = "R" . $cont;
    $s = "S" . $cont;
    $t = "T" . $cont;
    $u = "U" . $cont;
    $v = "V" . $cont;
    $w = "W" . $cont;
    $x = "X" . $cont;

    $enderecoCompleto = [
        $linha['logradouro'],
        $linha['numero'],
        $linha['bairro']
    ];

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue($a, $linha['instiSigla'])
        ->setCellValue($b, $linha['nome_local'])
        ->setCellValue($c, implode(", ", $enderecoCompleto) . " - CEP: " . $linha['cep'])
        ->setCellValue($d, $linha['subprefeitura'])
        ->setCellValue($e, $linha['nome'])
        ->setCellValue($f, $linha['artistas'])
        ->setCellValue($g, exibirDataBr($linha['data_inicio']))
        ->setCellValue($h, ($linha['data_fim'] == "0000-00-00") ? "Não é Temporada" : exibirDataBr($linha['data_fim']))
        ->setCellValue($i, exibirHora($linha['hora_inicio']))
        ->setCellValue($j, $linha['apresentacoes'] == '' ? 'Este evento é filme!' : $linha['apresentacoes'])
        ->setCellValue($k, $linha['periodo'])
        ->setCellValue($l, $stringAcoes ?? "Não foi selecionada linguagem.")
        ->setCellValue($m, $stringPublico ?? "Não foi selecionado público.")
        ->setCellValue($n, $linha['espaco_publico'] == 1 ? "SIM" : "NÃO")
        ->setCellValue($o, $linha['retirada'])
        ->setCellValue($p, $linha['valor_ingresso'] != '0.00' ? dinheiroParaBr($linha['valor_ingresso']) . " reais." : "Gratuito")
        ->setCellValue($q, $linha['classificacao'])
        ->setCellValue($r, isset($linha['divulgacao']) ? $linha['divulgacao'] : "Sem link de divulgação.")
        ->setCellValue($s, $linha['sinopse'])
        ->setCellValue($t, $linha['projeto_especial'])
        ->setCellValue($u, isset($fomento['fomento']) ? $fomento['fomento'] : "Não")
        ->setCellValue($v, $linha['produtor_nome'])
        ->setCellValue($w, $linha['produtor_email'])
        ->setCellValue($x, $linha['produtor_fone']);

    $objPHPExcel->getActiveSheet()->getStyle($a . ":" . $x)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($a . ":" . $x)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle($a . ":" . $x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->getActiveSheet()->getRowDimension($cont)->setRowHeight(20);

    $cont++;

}


// Renomeia a guia
$objPHPExcel->getActiveSheet()->setTitle('Inscritos');

for ($col = 'A'; $col !== 'X'; $col++) {
    $objPHPExcel->getActiveSheet()
        ->getColumnDimension($col)
        ->setAutoSize(true);
}

$objPHPExcel->setActiveSheetIndex(0);
ob_end_clean();
ob_start();

$nome_arquivo = date("YmdHis", strtotime("-3 hours")) . "_eventos_pesquisa.xls";


// Cabeçalho do arquivo para ele baixar(Excel2007)
header('Content-Type: text/html; charset=ISO-8859-1');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $nome_arquivo . '"');
header('Cache-Control: max-age=0');
// Se for o IE9, isso talvez seja necessário
header('Cache-Control: max-age=1');

// Acessamos o 'Writer' para poder salvar o arquivo
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

// Salva diretamente no output, poderíamos mudar arqui para um nome de arquivo em um diretório ,caso não quisessemos jogar na tela
$objWriter->save('php://output');

exit;
