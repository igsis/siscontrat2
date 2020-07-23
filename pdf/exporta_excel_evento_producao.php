<?php

setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');

include '../funcoes/funcoesConecta.php';
$con = bancoMysqli();
// Incluimos a classe PHPExcel
require_once("../include/phpexcel/Classes/PHPExcel.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");
session_start(['name' => 'sis']);

$idEvento = $_POST['idEvento'];

$sql = "SELECT 
id AS 'evento_id', tipo_evento_id, nome_evento, sinopse, projeto_especial_id, fomento, contratacao
FROM eventos
WHERE id = $idEvento AND evento_status_id = 3 AND publicado = 1";

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
$objPHPExcel->getProperties()->setCategory("Eventos");

//Eventos Comum
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
    ->setCellValue("M" . $proxLinha, "Eventos Comum")
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
    ->setCellValue("D" . $proxCabecalho, "Subprefeitura")
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


    if ($linha['contratacao'] == 1) {
        //Endereço do proponente
        $testaTipoProponente = $con->query("SELECT pessoa_tipo_id, pessoa_fisica_id, pessoa_juridica_id FROM pedidos WHERE publicado = 1 AND origem_tipo_id = 1 AND origem_id = " . $linha['evento_id'])->fetch_array();
        if ($testaTipoProponente['pessoa_tipo_id'] == 1) {
            $testaEnderecos = $con->query("SELECT * FROM pf_enderecos WHERE pessoa_fisica_id = " . $testaTipoProponente['pessoa_fisica_id']);
            if ($testaEnderecos->num_rows > 0) {
                while ($enderecoArray = mysqli_fetch_array($testaEnderecos)) {
                    $endereco = $enderecoArray['logradouro'] . ", " . $enderecoArray['numero'] . " " . $enderecoArray['complemento'] . " / - " . $enderecoArray['bairro'] . " - " . $enderecoArray['cidade'] . " / " . $enderecoArray['uf'];
                }
            } else {
                $endereco = "Não cadastrado";
            }

        } else if ($testaTipoProponente['pessoa_tipo_id'] == 2) {
            $testaEnderecos = $con->query("SELECT * FROM pj_enderecos WHERE pessoa_juridica_id = " . $testaTipoProponente['pessoa_juridica_id']);
            if ($testaEnderecos->num_rows > 0) {
                while ($enderecoArray = mysqli_fetch_array($testaEnderecos)) {
                    $endereco = $enderecoArray['logradouro'] . ", " . $enderecoArray['numero'] . " " . $enderecoArray['complemento'] . " / - " . $enderecoArray['bairro'] . " - " . $enderecoArray['cidade'] . " / " . $enderecoArray['uf'];
                }
            } else {
                $endereco = "Não cadastrado";
            }
        }
    }else{
        $endereco = "Não se aplica";
    }

    //Público
    $checaPublico = $con->query("SELECT * FROM evento_publico WHERE evento_id = " . $linha['evento_id']);
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
                       o.segunda, o.terca, o.quarta, o.quinta, o.sexta, o.sabado, o.domingo 
                FROM ocorrencias AS o 
    INNER JOIN instituicoes AS i ON o.instituicao_id = i.id
    INNER JOIN subprefeituras AS s ON o.subprefeitura_id = s.id
    INNER JOIN locais AS l ON l.id = o.local_id
    INNER JOIN retirada_ingressos AS ri ON ri.id = o.retirada_ingresso_id
    WHERE o.origem_ocorrencia_id = " . $linha['evento_id'] . " AND o.publicado = 1";

    $siglas = "";
    $subprefeituras = "";
    $locais = "";
    $retiradas = "";
    $totalDias = "";
    $dias = "";

    $queryOco = mysqli_query($con, $sqlInst);

    while ($linhaOco = mysqli_fetch_array($queryOco)) {
        $siglas = $siglas . $linhaOco['sigla'] . '; ';
        $subprefeituras = $subprefeituras . $linhaOco['subprefeitura'] . '; ';
        $locais = $locais . $linhaOco['local'] . '; ';
        $retiradas = $retiradas . $linhaOco['retirada_ingresso'] . '; ';


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

    $valorIngresso_consulta = $con->query("SELECT valor_ingresso FROM ocorrencias WHERE publicado = 1 AND retirada_ingresso_id NOT IN (2,5,7,11) AND origem_ocorrencia_id = " . $linha['evento_id']);
    if ($valorIngresso_consulta->num_rows > 0) {
        $valorIngresso = mysqli_fetch_array($valorIngresso_consulta)['valor_ingresso'];
    } else {
        $valorIngresso = "0.00";
    }

    $horaInicio = $con->query("SELECT horario_inicio FROM ocorrencias WHERE publicado = 1 AND origem_ocorrencia_id = " . $linha['evento_id'])->fetch_array()['horario_inicio'];
    $horaInicio = exibirHora($horaInicio);

    $acoes = "";
    $qtAtracao = 0;
    $artistas = "";
    $links = "";
    $classificoes = "";
    $produtorNomes = "";
    $produtorEmails = "";
    $produtorTelefones = "";
    $count = 1;

    if ($linha['tipo_evento_id'] == 1) {

        //Artistas, ações e quantidade de apresentação
        $sqlAtracoes = "SELECT a.id, a.integrantes, a.quantidade_apresentacao, ci.classificacao_indicativa, a.produtor_id, links FROM atracoes AS a
        INNER JOIN classificacao_indicativas AS ci ON a.classificacao_indicativa_id = ci.id
        WHERE a.evento_id = " . $linha['evento_id'] . " AND a.publicado = 1";
        $queryAtracoes = mysqli_query($con, $sqlAtracoes);

        while ($linhaAtracoes = mysqli_fetch_array($queryAtracoes)) {
            $checaTipo = $con->query("SELECT acao_id FROM acao_atracao WHERE atracao_id = " . $linhaAtracoes['id']);
            while ($idAcoes = mysqli_fetch_array($checaTipo)) {
                $tipoAcao = $con->query("SELECT acao FROM acoes WHERE id = " . $idAcoes['acao_id'] . " AND publicado = 1")->fetch_array();
                $acoes = $acoes . "Ação#" . $count . ": " . $tipoAcao['acao'] . "; ";
            }

            $artistas = $artistas . $linhaAtracoes['integrantes'] . '; ';
            $qtAtracao = $qtAtracao + (int)$linhaAtracoes['quantidade_apresentacao'];
            $classificoes = $classificoes . $linhaAtracoes['classificacao_indicativa'] . "; ";
            if ($linhaAtracoes['links'] == "") {
                $links = $links . "Link#" . $count . ": Não cadastrado; ";
            } else {
                $links = $links . "Link#" . $count . ": " . $linhaAtracoes['links'] . "; ";
            }

            //dados dos produtores
            $produtorArray = $con->query("SELECT nome, email, telefone1 FROM produtores WHERE id = " . $linhaAtracoes['produtor_id'])->fetch_array();
            $produtorNomes = $produtorNomes . "Produtor#" . $count . ": " . $produtorArray['nome'] . "; ";
            $produtorEmails = $produtorEmails . "Produtor#" . $count . ": " . $produtorArray['email'] . "; ";
            $produtorTelefones = $produtorTelefones . "Produtor#" . $count . ": " . $produtorArray['telefone1'] . "; ";;
            $count += 1;
        }

        $acoes = substr($acoes, 0);
        $artistas = substr($artistas, 0);
        $classificoes = substr($classificoes, 0);
        $links = substr($links, 0);
        $produtorNomes = substr($produtorNomes, 0);
        $produtorEmails = substr($produtorEmails, 0);
        $produtorTelefones = substr($produtorTelefones, 0);
    }

    if ($linha['tipo_evento_id'] == 2) {
        $consultaFilmes = $con->query("SELECT filme_id FROM filme_eventos WHERE evento_id = " . $linha['evento_id']);
        while ($idFilmes = mysqli_fetch_array($consultaFilmes)) {
            $filmesArray = $con->query("SELECT ci.classificacao_indicativa, f.link_trailer FROM classificacao_indicativas AS ci INNER JOIN filmes AS f ON ci.id = f.classificacao_indicativa_id WHERE f.id = " . $idFilmes['filme_id'])->fetch_array();

            if ($filmesArray['link_trailer'] == "") {
                $links = $links . "Link#" . $count . ": Não cadastrado; ";
            } else {
                $links = $links . "Link#" . $count . ": " . $filmesArray['link_trailer'] . "; ";
            }

            $classificoes = $classificoes . $filmesArray['classificacao_indicativa'] . "; ";
            $count += 1;
        }
        $tipoAcao = $con->query("SELECT acao FROM acoes WHERE id = 1 AND publicado = 1")->fetch_array()['acao'];
        $acoes = $tipoAcao;


        $classificoes = substr($classificoes, 0);
    }

    $dataInicio = $con->query("SELECT data_inicio FROM ocorrencias oco WHERE oco.origem_ocorrencia_id = " . $linha['evento_id'] . " AND oco.publicado = '1' ORDER BY data_inicio ASC LIMIT 0,1")->fetch_array()['data_inicio'];
    $dataFim = $con->query("SELECT data_fim FROM ocorrencias oco WHERE oco.origem_ocorrencia_id = " . $linha['evento_id'] . " AND oco.publicado = '1' ORDER BY data_fim DESC LIMIT 0,1")->fetch_array()['data_fim'];
    $projeto_especial = $con->query("SELECT projeto_especial FROM projeto_especiais WHERE id = " . $linha['evento_id'])->fetch_array()['projeto_especial'];

    if ($linha['fomento'] != 0) {
        $consultaFomento = $con->query("SELECT fomento_id FROM evento_fomento WHERE evento_id = " . $linha['evento_id'])->fetch_array();
        $fomento = $con->query("SELECT fomento FROM fomentos WHERE id = " . $consultaFomento['fomento_id'])->fetch_array();
    } else {
        $fomento = "Não possuí";
    }

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue($a, $siglas)
        ->setCellValue($b, $locais)
        ->setCellValue($c, $endereco)
        ->setCellValue($d, $subprefeituras)
        ->setCellValue($e, $linha['nome_evento'])
        ->setCellValue($f, $artistas == "" ? "Este evento é filme!" : $artistas)
        ->setCellValue($g, exibirDataBr($dataInicio))
        ->setCellValue($h, $dataFim == "0000-00-00" ? "Não é Temporada" : exibirDataBr($dataFim))
        ->setCellValue($i, $horaInicio)
        ->setCellValue($j, $qtAtracao == 0 ? 'Este evento é filme!' : $qtAtracao)
        ->setCellValue($k, retornaPeriodoNovo($linha['evento_id'], 'ocorrencias'))
        ->setCellValue($l, $acoes == NULL ? "Não foi selecionada linguagem." : $acoes)
        ->setCellValue($m, $publicos)
        ->setCellValue($n, $linha['espaco_publico'] == 1 ? "SIM" : "NÃO")
        ->setCellValue($o, $retiradas)
        ->setCellValue($p, $valorIngresso != '0.00' ? "R$" . dinheiroParaBr($valorIngresso) : "Gratuito")
        ->setCellValue($q, $classificoes)
        ->setCellValue($r, $links)
        ->setCellValue($s, $linha['sinopse'])
        ->setCellValue($t, $projeto_especial)
        ->setCellValue($u, $fomento)
        ->setCellValue($v, $produtorNomes == "" ? "Este evento é filme!" : $produtorNomes)
        ->setCellValue($w, $produtorEmails == "" ? "Este evento é filme!" : $produtorEmails)
        ->setCellValue($x, $produtorTelefones == "" ? "Este evento é filme!" : $produtorTelefones);

    $objPHPExcel->getActiveSheet()->getStyle($a . ":" . $x)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($a . ":" . $x)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle($a . ":" . $x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->getActiveSheet()->getRowDimension($cont)->setRowHeight(70);

    $cont++;

}


// Renomeia a guia
$objPHPExcel->getActiveSheet()->setTitle('Inscritos');

foreach (range('A', 'X') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
        ->setAutoSize(true);
}

//Consertando a coluna referente aos locais
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(false);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(70);

$objPHPExcel->setActiveSheetIndex(0);
ob_end_clean();
ob_start();

$nome_arquivo = date("d-m-Y", strtotime("-3 hours")) . "_eventos_pesquisa.xls";


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

