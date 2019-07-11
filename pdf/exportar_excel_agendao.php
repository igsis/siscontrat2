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

// Criamos as colunas
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue("A1", "Local do Evento")
    ->setCellValue("B1", "Endereço Completo")
    ->setCellValue("C1", "SubPrefeitura")
    ->setCellValue("D1", "Nome do Evento")
    ->setCellValue("E1", "Artistas")
    ->setCellValue("F1", "Data Início")
    ->setCellValue("G1", "Data Fim")
    ->setCellValue("H1", "Horário de início")
    ->setCellValue("I1", "Horário do fim")
    ->setCellValue("J1", "Nº de Apresentações")
    ->setCellValue("K1", "Período")
    ->setCellValue("L1", "Ação / Expressão Artística Principal")
    ->setCellValue("M1", "Público / Representatividade Social Principal")
    ->setCellValue("N1", "Espaço Público?" )
    ->setCellValue("O1", "Entrada")
    ->setCellValue("P1", "Valor do Ingresso (no caso de cobrança)")
    ->setCellValue("Q1", "Classificação indicativa")
    ->setCellValue("R1", "Link de Divulgação")
    ->setCellValue("S1", "Sinopse")
    ->setCellValue("T1", "Calendário Macro")
    ->setCellValue("U1", "Caso Seja Fomento / Programa da smc Qual o Fomento ou Programa?")
    ->setCellValue("V1", "Produtor do Evento")
    ->setCellValue("W1", "E-mail de contato")
    ->setCellValue("X1", "Telefone de contato");

// Definimos o estilo da fonte
$objPHPExcel->getActiveSheet()->getStyle('A1:AH1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A1:AH1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

//Colorir a primeira linha
$objPHPExcel->getActiveSheet()->getStyle('A1:AH1')->applyFromArray
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

$cont = 2;
while($linha = mysqli_fetch_array($query))
{
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

    $a = "A".$cont;
    $b = "B".$cont;
    $c = "C".$cont;
    $d = "D".$cont;
    $e = "E".$cont;
    $f = "F".$cont;
    $g = "G".$cont;
    $h = "H".$cont;
    $i = "I".$cont;
    $j = "J".$cont;
    $k = "K".$cont;
    $l = "L".$cont;
    $m = "M".$cont;
    $n = "N".$cont;
    $o = "O".$cont;
    $p = "P".$cont;
    $q = "Q".$cont;
    $r = "R".$cont;
    $s = "S".$cont;
    $t = "T".$cont;
    $u = "U".$cont;
    $v = "V".$cont;
    $w = "W".$cont;
    $x = "X".$cont;
    $y = "Y".$cont;
    $z = "Z".$cont;

    $enderecoCompleto = [
        $linha['logradouro'],
        $linha['numero'],
        $linha['bairro']
    ];

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue($a, $linha['nome_local'])
        ->setCellValue($b, implode(", ", $enderecoCompleto)." - CEP: ".$linha['cep'])
        ->setCellValue($c, $linha['subprefeitura'])
        ->setCellValue($d, $linha['nome'])
        ->setCellValue($e, $linha['artista'])
        ->setCellValue($f, exibirDataBr($linha['data_inicio']))
        ->setCellValue($g, ($linha['data_fim'] == "0000-00-00") ? "Não é Temporada" : exibirDataBr($linha['data_fim']))
        ->setCellValue($h, exibirHora($linha['hora_inicio']))
        ->setCellValue($i, exibirHora($linha['hora_fim']))
        ->setCellValue($j, $linha['apresentacoes'])
        ->setCellValue($k, $linha['periodo'])
        ->setCellValue($l, $stringAcoes ?? "Não foi selecionada linguagem.")
        ->setCellValue($m, $stringPublico ?? "Não foi selecionado público.")
        ->setCellValue($n, $linha['espaco_publico'] == 1 ? "SIM" : "NÃO")
        ->setCellValue($o, $linha['retirada'])
        ->setCellValue($p, $linha['valor_ingresso'] != '0.00' ? dinheiroParaBr($linha['valor_ingresso']) . " reais." : "Gratuito")
        ->setCellValue($q, $linha['classificacao'])
        ->setCellValue($r, isset($linha['divulgacao']) ? $linha['divulgacao'] : "Sem link de divulgação.")
        ->setCellValue($s, $linha['sinopse'])
        ->setCellValue($t, $linha['projetoEspecial'])
        ->setCellValue($u, isset($fomento['fomento']) ? $fomento['fomento'] : "Não")
        ->setCellValue($v, $linha['produtor_nome'])
        ->setCellValue($w, $linha['produtor_email'])
        ->setCellValue($x, $linha['produtor_fone']);

    $objPHPExcel->getActiveSheet()->getStyle($a . ":" . $z)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($a . ":" . $z)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle($a . ":" . $z)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $cont++;

}
// Renomeia a guia
$objPHPExcel->getActiveSheet()->setTitle('Inscritos');

for ($col = 'A'; $col !== 'Z'; $col++){
    $objPHPExcel->getActiveSheet()
        ->getColumnDimension($col)
        ->setAutoSize(true);
}

$objPHPExcel->setActiveSheetIndex(0);
ob_end_clean();
ob_start();

$nome_arquivo = "eventos_pesquisa.xls";


// Cabeçalho do arquivo para ele baixar(Excel2007)
header('Content-Type: text/html; charset=ISO-8859-1');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$nome_arquivo.'"');
header('Cache-Control: max-age=0');
// Se for o IE9, isso talvez seja necessário
header('Cache-Control: max-age=1');

// Acessamos o 'Writer' para poder salvar o arquivo
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

// Salva diretamente no output, poderíamos mudar arqui para um nome de arquivo em um diretório ,caso não quisessemos jogar na tela
$objWriter->save('php://output');

exit;
