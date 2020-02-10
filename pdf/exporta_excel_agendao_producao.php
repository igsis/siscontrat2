<?php

setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');

include '../funcoes/funcoesConecta.php';
$con = bancoMysqli();
// Incluimos a classe PHPExcel
require_once("../include/phpexcel/Classes/PHPExcel.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");
session_start();

$idAgendao = $_SESSION['idAgendaoProd'];
$sql = "SELECT
                agendao.id AS 'evento_id',
                agendao.nome_evento AS 'nome',
                agendao.espaco_publico AS 'espaco_publico',
                agendao.quantidade_apresentacao AS 'apresentacoes',
                PE.projeto_especial AS 'projeto_especial',
                O.id AS 'idOcorrencia',
                O.horario_inicio AS 'hora_inicio',
                O.data_inicio AS 'data_inicio',
                O.data_fim AS 'data_fim',
                O.horario_fim AS 'hora_fim',
                O.valor_ingresso AS 'valor_ingresso',
                O.segunda AS 'segunda',
                O.terca AS 'terca',
                O.quarta AS 'quarta',
                O.quinta AS 'quinta',
                O.sexta AS 'sexta',
                O.sabado AS 'sabado',
                O.domingo AS 'domingo',
                L.logradouro AS 'logradouro',
                L.numero AS 'numero',
                L.complemento AS 'complemento',
                L.bairro AS 'bairro',
                L.cidade AS 'cidade',
                L.uf AS 'estado',
                L.cep AS 'cep',
                agendao.ficha_tecnica AS 'artistas',
                CI.classificacao_indicativa AS 'classificacao',
                agendao.links AS 'divulgacao',
                agendao.sinopse AS 'sinopse',
                agendao.fomento AS 'fomento',
                P.nome AS 'produtor_nome',
                P.email AS 'produtor_email',
                P.telefone1 AS 'produtor_fone',
                U.nome_completo AS 'nomeCompleto',
                PE.projeto_especial,
                SUB_PRE.subprefeitura AS 'subprefeitura',
                DIA_PERI.periodo AS 'periodo',
                retirada.retirada_ingresso AS 'retirada',
                I.sigla AS 'instiSigla'
                FROM agendoes AS agendao
                LEFT JOIN agendao_ocorrencias AS O ON agendao.id = O.origem_ocorrencia_id
                LEFT JOIN locais AS L ON O.local_id = L.id
                LEFT JOIN instituicoes AS I ON O.instituicao_id = I.id
                LEFT JOIN subprefeituras AS SUB_PRE ON O.subprefeitura_id = SUB_PRE.id
                LEFT JOIN usuarios AS U ON agendao.usuario_id = U.id
                LEFT JOIN periodos AS DIA_PERI ON O.periodo_id = DIA_PERI.id
                LEFT JOIN projeto_especiais AS PE ON agendao.projeto_especial_id = PE.id
                LEFT JOIN retirada_ingressos AS retirada ON O.retirada_ingresso_id = retirada.id
                LEFT JOIN classificacao_indicativas AS CI ON agendao.classificacao_indicativa_id = CI.id
                LEFT JOIN produtores AS P ON agendao.produtor_id = P.id
                WHERE agendao.id = '$idAgendao' AND agendao.evento_status_id = 3 AND agendao.publicado = 1 AND O.publicado = 1
                ORDER BY O.data_inicio";

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
$objPHPExcel->getProperties()->setCategory("Agendões");

//Eventos Agendão
$proxLinha = 1;

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
    ->setCellValue("M" . $proxLinha, "Agendão")
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
    ->setCellValue("D" . $proxCabecalho, "Subpefeitura")
    ->setCellValue("E" . $proxCabecalho, "Nome do Evento")
    ->setCellValue("F" . $proxCabecalho, "Artistas")
    ->setCellValue("G" . $proxCabecalho, "Data de Início")
    ->setCellValue("H" . $proxCabecalho, "Data de Encerramento")
    ->setCellValue("I" . $proxCabecalho, "Horário de Início")
    ->setCellValue("J" . $proxCabecalho, "Nº de Apresentações")
    ->setCellValue("K" . $proxCabecalho, "Período")
    ->setCellValue("L" . $proxCabecalho, "Linguagem / Expressão Artística Principal")
    ->setCellValue("M" . $proxCabecalho, "Público / Representatividade Social Principal")
    ->setCellValue("N" . $proxCabecalho, "Espaço Público?")
    ->setCellValue("O" . $proxCabecalho, "Entrada")
    ->setCellValue("P" . $proxCabecalho, "Valor do Ingresso (no caso de cobrança)")
    ->setCellValue("Q" . $proxCabecalho, "Classificação Indicativa")
    ->setCellValue("R" . $proxCabecalho, "Link de Divulgação")
    ->setCellValue("S" . $proxCabecalho, "Sinopse")
    ->setCellValue("T" . $proxCabecalho, "Calendário Macro")
    ->setCellValue("U" . $proxCabecalho, "Caso Seja Fomento / Programa da smc Qual o Fomento ou Programa?")
    ->setCellValue("V" . $proxCabecalho, "Produtor do Evento")
    ->setCellValue("W" . $proxCabecalho, "E-mail de Contato")
    ->setCellValue("X" . $proxCabecalho, "Telefone de Contato");

// Definimos o estilo da fonte
$objPHPExcel->getActiveSheet()->getStyle('A' . $proxCabecalho . ':X' . $proxCabecalho)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A' . $proxCabecalho . ':X' . $proxCabecalho)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getRowDimension($proxCabecalho)->setRowHeight(40);
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
while ($linha = mysqli_fetch_array($query)) {

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

    $sqlLocal = "SELECT l.local FROM locais l INNER JOIN agendao_ocorrencias ao ON ao.local_id = l.id WHERE ao.origem_ocorrencia_id = $idAgendao AND ao.publicado = 1";
    $queryLocal = mysqli_query($con, $sqlLocal);
    $local = '';
    while ($locais = mysqli_fetch_array($queryLocal)) {
        $local = $local . '; ' . $locais['local'];
    }
    $local = substr($local, 1);

    $objPHPExcel->setActiveSheetIndex(0)        
        ->setCellValue($a, $linha['instiSigla'])
        ->setCellValue($b, $local)
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

$objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth(25);

$objPHPExcel->setActiveSheetIndex(0);
ob_end_clean();
ob_start();

$nome_arquivo = date("YmdHis", strtotime("-3 hours")) . "_agendao_pesquisa.xls";


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

?>
