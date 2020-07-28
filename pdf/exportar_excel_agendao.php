<?php
setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');

include '../funcoes/funcoesConecta.php';
$con = bancoMysqli();
// Incluimos a classe PHPExcel
require_once("../include/phpexcel/Classes/PHPExcel.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");

$idAgendao = $_POST['idAgendao'];

$sql = "SELECT
                a.id AS 'id',
                a.nome_evento,
                a.espaco_publico,
                a.quantidade_apresentacao,
                PE.projeto_especial,
                a.ficha_tecnica,
                CI.classificacao_indicativa AS 'classificacao',
                a.links AS 'divulgacao',
                a.sinopse AS 'sinopse',
                a.fomento AS 'fomento',
                P.nome AS 'produtor_nome',
                P.email AS 'produtor_email',
                P.telefone1 AS 'produtor_fone',
                PE.projeto_especial
                FROM agendoes AS a
                LEFT JOIN projeto_especiais AS PE ON a.projeto_especial_id = PE.id
                LEFT JOIN classificacao_indicativas AS CI ON a.classificacao_indicativa_id = CI.id
                LEFT JOIN produtores AS P ON a.produtor_id = P.id
                WHERE a.evento_status_id = 3 AND a.publicado = 1 AND a.id = $idAgendao";

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

    //Públicos
    $checaPublico = $con->query("SELECT * FROM agendao_publico WHERE evento_id = " . $linha['id']);
    $publicos = "";
    if ($checaPublico->num_rows > 0) {
        while ($arrayPublico = mysqli_fetch_array($checaPublico)) {
            $publicoArray = $con->query("SELECT publico FROM publicos WHERE id = " . $arrayPublico['publico_id'])->fetch_array()['publico'];
            $publicos = $publicos . $publicoArray . "; ";
        }
        $publicos = substr($publicos, 0);
    } else {
        $publicos = "Não cadastrado";
    }

    //Instituições, Subprefeituras e Locais
    $sqlInst = "SELECT i.sigla, s.subprefeitura, l.local, ri.retirada_ingresso,
                       l.logradouro, l.numero, l.complemento, l.bairro, l.cidade, l.uf, l.cep,
                       o.segunda, o.terca, o.quarta, o.quinta, o.sexta, o.sabado, o.domingo 
                FROM agendao_ocorrencias AS o 
    INNER JOIN instituicoes AS i ON o.instituicao_id = i.id
    INNER JOIN subprefeituras AS s ON o.subprefeitura_id = s.id
    INNER JOIN locais AS l ON l.id = o.local_id
    INNER JOIN retirada_ingressos AS ri ON ri.id = o.retirada_ingresso_id
    WHERE o.origem_ocorrencia_id = " . $linha['id'] . " AND o.publicado = 1";

    $siglas = "";
    $subprefeituras = "";
    $locais = "";
    $retiradas = "";
    $totalDias = "";
    $dias = "";
    $enderecos = "";

    $queryOco = mysqli_query($con, $sqlInst);

    while ($linhaOco = mysqli_fetch_array($queryOco)) {
        $siglas = $siglas . $linhaOco['sigla'] . '; ';
        $subprefeituras = $subprefeituras . $linhaOco['subprefeitura'] . '; ';
        $locais = $locais . $linhaOco['local'] . '; ';
        $retiradas = $retiradas . $linhaOco['retirada_ingresso'] . '; ';
        $enderecos = $enderecos . $linhaOco['logradouro'] . ", " . $linhaOco['numero'] . " " . $linhaOco['complemento'] . " / - " . $linhaOco['bairro'] . " - " . $linhaOco['cidade'] . " / " . $linhaOco['uf'] . $linhaOco['cep'] . "\n";

        $linhaOco['segunda'] == 1 ? $dias .= "Segunda, " : '';
        $linhaOco['terca'] == 1 ? $dias .= "Terça, " : '';
        $linhaOco['quarta'] == 1 ? $dias .= "Quarta, " : '';
        $linhaOco['quinta'] == 1 ? $dias .= "Quinta, " : '';
        $linhaOco['sexta'] == 1 ? $dias .= "Sexta, " : '';
        $linhaOco['sabado'] == 1 ? $dias .= "Sabádo, " : '';
        $linhaOco['domingo'] == 1 ? $dias .= "Domingo. " : '';

        if ($dias != "") {
            $totalDias .= substr($dias, 0, -2);
        } else {
            $totalDias .= "Dias não especificados.";
        }
    }

    $locais = substr($locais, 0);
    $subprefeituras = substr($subprefeituras, 0);
    $siglas = substr($siglas, 0);
    $retiradas = substr($retiradas, 0);
    $enderecos = substr($enderecos, 0);

    $valorIngresso_consulta = $con->query("SELECT valor_ingresso FROM agendao_ocorrencias WHERE publicado = 1 AND retirada_ingresso_id NOT IN (2,5,7,11) AND origem_ocorrencia_id = " . $linha['id']);
    if ($valorIngresso_consulta->num_rows > 0) {
        $valorIngresso = mysqli_fetch_array($valorIngresso_consulta)['valor_ingresso'];
    } else {
        $valorIngresso = "0.00";
    }

    if ($linha['fomento'] != 0) {
        $sqlFomento = "SELECT * FROM fomentos WHERE id = '" . $linha['fomento'] . "'";
        $fomento = $con->query($sqlFomento)->fetch_assoc();
    }

    $acoes = "";
    $acoesID = $con->query("SELECT acao_id FROM acao_agendao WHERE evento_id = " . $linha['id']);
    while($idAcoesArray = mysqli_fetch_array($acoesID)){
        $acoes = $con->query("SELECT acao FROM acoes WHERE id = " . $idAcoesArray['acao_id'])->fetch_array()['acao'];
    }

    $dataInicio = $con->query("SELECT data_inicio FROM agendao_ocorrencias oco WHERE oco.origem_ocorrencia_id = " . $linha['id'] . " AND oco.publicado = '1' ORDER BY data_inicio ASC LIMIT 0,1")->fetch_array()['data_inicio'];
    $dataFim = $con->query("SELECT data_fim FROM agendao_ocorrencias oco WHERE oco.origem_ocorrencia_id = " . $linha['id'] . " AND oco.publicado = '1' ORDER BY data_fim DESC LIMIT 0,1")->fetch_array()['data_fim'];

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

    $objPHPExcel->setActiveSheetIndex(0)        
        ->setCellValue($a, $siglas)
        ->setCellValue($b, $locais)
        ->setCellValue($c, $enderecos)
        ->setCellValue($d, $subprefeituras)
        ->setCellValue($e, $linha['nome_evento'])
        ->setCellValue($f, $linha['ficha_tecnica'])
        ->setCellValue($g, exibirDataBr($dataInicio))
        ->setCellValue($h, $dataFim == "0000-00-00" ? "Não é Temporada" : exibirDataBr($linha['data_fim']))
        ->setCellValue($i, exibirHora($linha['hora_inicio']))
        ->setCellValue($j, $linha['quantidade_apresentacao'])
        ->setCellValue($k, retornaPeriodoNovo($linha['id'], "agendao_ocorrencias"))
        ->setCellValue($l, $acoes == "" ? "Não foi selecionada linguagem." : $acoes)
        ->setCellValue($m, $publicos)
        ->setCellValue($n, $linha['espaco_publico'] == 1 ? "SIM" : "NÃO")
        ->setCellValue($o, $retiradas)
        ->setCellValue($p, $valorIngresso == "0.00" ? "GRÁTIS" : "R$" . dinheiroParaBr($valorIngresso))
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

    $objPHPExcel->getActiveSheet()->getRowDimension($cont)->setRowHeight(85);

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

$nome_arquivo = date("d-m-Y", strtotime("-3 hours")) . "_agendoes_pesquisa.xls";

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
