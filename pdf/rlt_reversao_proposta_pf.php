<?php

// INSTALAÇÃO DA CLASSE NA PASTA FPDF.
require_once("../include/lib/fpdf/fpdf.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");


$con = bancoMysqli();


class PDF extends FPDF
{
    function Header()
    {
        // Move to the right

        // Logo
        $this->Cell(80);
        $this->Image('../pdf/logo_smc.jpg', 170, 10);

        // Line break
        $this->Ln(20);
    }
}

$idUser = $_POST['idUser'];
$idPedido = $_POST['idPedido'];
$pedido = recuperaDados('pedidos', 'id', $idPedido);
$idPf = $pedido['pessoa_fisica_id'];
$idFC = $pedido['origem_id'];
$evento = recuperaDados('eventos', 'id', $pedido['origem_id']);
$ocorrencias = $con->query("SELECT atracao_id, tipo_ocorrencia_id FROM ocorrencias WHERE tipo_ocorrencia_id != 3 AND publicado = 1 AND origem_ocorrencia_id =  " . $pedido['origem_id']);

$cargaHoraria = 0;

while ($linhaOco = mysqli_fetch_array($ocorrencias)) {

    if ($linhaOco['tipo_ocorrencia_id'] == 1) {
        $sqlCarga = "SELECT carga_horaria FROM oficinas WHERE atracao_id = " . $linhaOco['atracao_id'];
        $carga = $con->query($sqlCarga);

        if ($carga->num_rows > 0) {
            while ($cargaArray = mysqli_fetch_array($carga)) {
                $cargaHoraria = $cargaHoraria + (int)$cargaArray['carga_horaria'];
            }
        } else {
            $cargaHoraria = "Não possuí.";
        }
    }
}
$pessoa = recuperaDados('pessoa_fisicas', 'id', $idPf);

if ($pessoa['nacionalidade_id'] != NULL) {
    $nacionalidade = recuperaDados('nacionalidades', 'id', $pessoa['nacionalidade_id'])['nacionalidade'];
} else {
    $nacionalidade = "Não cadastrado";
}

$testaEnderecos = $con->query("SELECT * FROM pf_enderecos WHERE pessoa_fisica_id = $idPf");

if ($testaEnderecos->num_rows > 0) {
    while ($enderecoArray = mysqli_fetch_array($testaEnderecos)) {
        $endereco = $enderecoArray['logradouro'] . ", " . $enderecoArray['numero'] . " " . $enderecoArray['complemento'] . " / - " . $enderecoArray['bairro'] . " - " . $enderecoArray['cidade'] . " / " . $enderecoArray['uf'];
    }
} else {
    $endereco = "Não cadastrado";
}

$sqlTelefone = "SELECT * FROM pf_telefones WHERE pessoa_fisica_id = '$idPf' AND publicado = 1";
$tel = "";
$queryTelefone = mysqli_query($con, $sqlTelefone);

$idPenal = $_GET['penal'];

$ano = date('Y');

$Observacao = "1) A proponente tem ciência da obrigatoriedade de fazer menção dos créditos PREFEITURA DA CIDADE DE SÃO PAULO, SECRETARIA MUNICIPAL DE CULTURA, em toda divulgação, escrita ou falada, realizada sobre o espetáculo programado, sob pena de cancelamento sumário do evento.
2) Nos casos de comercialização de qualquer produto artístico-cultural, a proponente assume inteira responsabilidade fiscal e tributária quanto a sua comercialização, isentando a Municipalidade de quaisquer ônus ou encargos, nos termos da O.I. nº 01/2002 – SMC-G.
3) No caso de espetáculo musical, declara assumir quaisquer ônus decorrentes da fiscalização e autuação da Ordem dos Músicos do Brasil – OMB.
4) Declaro para os devidos fins que, estou ciente que no presente evento não será permitido nenhuma manifestação de cunho político ou partidário, conforme estabelecido no art. 37, § 1º, Constituição Federal e da Legislação Eleitoral vigente.
5) As ideias e opiniões expressas durante as apresentações artísticas e culturais não representam a posição da Secretaria Municipal de Cultura, sendo os artistas e seus representantes os únicos e exclusivos responsáveis pelo conteúdo de suas manifestações, ficando a Municipalidade de São Paulo com direito de regresso sobre os mesmos, inclusive em caso de indenização por dano material, moral ou à imagem de terceiros.
6) PAGAMENTO, nos casos de contratações com cachê:
- Só serão creditados pagamentos em CONTA CORRENTE (Não são aceitas contas: poupança, fácil e conjunta).
- Pagamentos acima de R$ 5.000,00 (cinco mil reais) somente serão creditados no BANCO DO BRASIL, por força de Decreto Municipal 51.197/10 - Portaria SF nº33/10.
- Pagamentos até R$ 5.000,00 (cinco mil reais), contratações pessoa jurídica, de natureza eventual e não continuada, poderão ser creditados em conta CORRENTE de qualquer Banco, por força de Decreto Municipal 51.197/10 - Portaria SF nº255/15.
Entenda-se como natureza eventual aquela originária de até duas prestações de serviço, realizada no âmbito de uma unidade orçamentária, no período dos últimos doze meses. Quando a contratação for pessoa física, até R$ 5.000,00 (cinco mil reais), o contratado pode receber por ordem bancária no Banco do Brasil e não por outra conta corrente de outro banco da rede, 30 dias a partir da data de previsão de pagamento, mediante apresentação de documento de identificação com foto, em qualquer agência do Banco do Brasil.
- As contas correntes deverão ser informadas em nome do CPF (PF) ou CNPJ (PJ) contratados.
- Contratações realizadas através de MEI, deverão informar conta corrente PESSOA JURÍDICA.
- Contratados Pessoa Jurídica não podem utilizar conta de pessoa física para o recebimento.
- Contratação de Pessoa Física: sempre informar Número do NIT ou PIS/PASEP.";

$sqlPenalidade = "SELECT texto FROM penalidades WHERE id = $idPenal";
$penalidades = $con->query($sqlPenalidade)->fetch_array();

$reversao = "1) No caso de pagamento do cachê por reversão de bilheteria, fica o valor dos ingressos sujeito ao atendimento no disposto nas Leis Municipais nº 10.973/91, regulamentada pelo Decreto Municipal nº 30.730/91; Leis Municipais 11.113/91; 11.357/93 e 12.975/2000 e Portaria nº 66/SMC/2007; Lei Estadual nº 7844/92, regulamentada pelo Decreto Estadual nº 35.606/92; Lei Estadual nº 10.858/2001, com as alterações da Lei Estadual 14.729/2012 e Lei Federal nº 12.933/2013." . "\n" .
            "2) O pagamento do cachê corresponderá à reversão integral da renda obtida na bilheteria a/o ontratada/o, deduzidos os impostos e taxas pertinentes.";

$testaDrt = $con->query("SELECT drt FROM drts WHERE pessoa_fisica_id = $idPf");
if ($testaDrt->num_rows > 0) {
    while ($drtArray = mysqli_fetch_array($testaDrt)) {
        $drt = $drtArray['drt'];
    }
} else {
    $drt = "Não Cadastrado.";
}

$objeto = retornaTipo($evento['tipo_evento_id']) . " - " . $evento['nome_evento'];

$periodo = retornaPeriodoNovo($pedido['origem_id'], 'ocorrencias');

if ($pessoa['ccm'] != "" || $pessoa['ccm'] != NULL) {
    $ccm = $pessoa['ccm'];
} else {
    $ccm = "Não Cadastrado.";
}

alteraStatusPedidoContratos($idPedido, "proposta", $idPenal, $idUser);

$pdf = new PDF('P', 'mm', 'A4'); //CRIA UM NOVO ARQUIVO PDF NO TAMANHO A4
$pdf->AliasNbPages();
$pdf->AddPage();


$x = 20;
$l = 7; //DEFINE A ALTURA DA LINHA

$pdf->SetXY($x, 35);// SetXY - DEFINE O X (largura) E O Y (altura) NA PÁGINA

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(10, 5, '(A)', 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(170, 5, 'CONTRATADO', 0, 1, 'C');

$pdf->Ln(5);


$pdf->SetX($x);
$pdf->SetFont('Arial', 'I', 10);
$pdf->MultiCell(200, $l, utf8_decode("(Quando se tratar de grupo, o líder do grupo)"), 0, 'L', 0);

$pdf->Ln(5);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(12, $l, 'Nome:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(40, $l, utf8_decode($pessoa['nome']), 0, 'L', 0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(27, $l, utf8_decode("Nome Artístico:"), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(120, $l, utf8_decode($pessoa['nome_artistico'] == NULL ? "Não cadastrado" : $pessoa['nome_artistico']), 0, 'L', 0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);

if ($pessoa['passaporte'] != NULL) {
    $pdf->Cell(21, $l, utf8_decode('Passaporte:'), 0, 0, 'L');
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(50, $l, utf8_decode($pessoa['passaporte']), 0, 0, 'L');

} else {
    $pdf->Cell(7, $l, utf8_decode('RG:'), 0, 0, 'L');
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(50, $l, utf8_decode($pessoa['rg'] == NULL ? "Não cadastrado" : $pessoa['rg']), 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(9, $l, utf8_decode('CPF:'), 0, 0, 'L');
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(45, $l, utf8_decode($pessoa['cpf']), 0, 0, 'L');
}

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(9, $l, utf8_decode("DRT:"), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(5, $l, utf8_decode($drt), 0, 0, 'L');

$pdf->Ln(7);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(35, $l, 'Data de Nascimento:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(25, $l, utf8_decode(exibirDataBr($pessoa['data_nascimento'])), 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(26, $l, "Nacionalidade:", 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(30, $l, utf8_decode($nacionalidade), 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(10, $l, "CCM:", 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(30, $l, utf8_decode($ccm), 0, 0, 'L');

$pdf->Ln(7);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(18, $l, utf8_decode("Endereço:"), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(160, $l, utf8_decode($endereco), 0, 'L', 0);

while ($linhaTel = mysqli_fetch_array($queryTelefone)) {
    $tel = $tel . $linhaTel['telefone'] . ' | ';
}

$tel = substr($tel, 0, -3);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(21, $l, 'Telefone(s):', '0', '0', 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(168, $l, utf8_decode($tel), 0, 'L', 0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(11, $l, 'Email:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(168, $l, utf8_decode($pessoa['email']), 0, 'L', 0);

$pdf->SetX($x);
$pdf->Cell(180, 5, '', 'B', 1, 'C');

$pdf->Ln(5);

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(10, 10, '(B)', 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(160, 10, 'PROPOSTA', 0, 0, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(10, 10, utf8_decode($evento['protocolo'] . " - " . $pedido['numero_processo']), 0, 1, 'R');

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(13, $l, 'Objeto:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(140, $l, utf8_decode($objeto), 0, 0, 'L');

$pdf->Ln(6);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(26, $l, utf8_decode('Data / Período:'), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(50, $l, utf8_decode($periodo), 0, 'L', 0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(26, $l, utf8_decode('Carga Horária:'), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(50, $l, utf8_decode($cargaHoraria), 0, 'L', 0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(18, $l, 'Local(ais):', '0', '0', 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(165, $l, utf8_decode(listaLocais($evento['id'], '1')), 0, 'L', 0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(11, $l, 'Valor:', '0', '0', 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(168, $l, utf8_decode("R$ " . dinheiroParaBr($pedido['valor_total']) . " (" . valorPorExtenso($pedido['valor_total']) . " )"), 0, 'L', 0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(38, $l, 'Forma de Pagamento:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(122, $l, utf8_decode($pedido['forma_pagamento']));

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(22, $l, 'Justificativa:', '0', '0', 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(155, $l, utf8_decode($pedido['justificativa']));

//RODAPÉ PERSONALIZADO
$pdf->SetXY($x, 262);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(100, 4, utf8_decode($pessoa['nome']), 'T', 1, 'L');

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 10);

if ($pessoa['passaporte'] != NULL) {
    $pdf->Cell(100, 4, "Passaporte: " . $pessoa['passaporte'], 0, 1, 'L');
} else {
    $rg = $pessoa['rg'] == NULL ? "Não cadastrado" : $pessoa['rg'];
    $rg = "RG: " . $rg;
    $pdf->Cell(100, 4, utf8_decode($rg), 0, 1, 'L');
    $pdf->SetX($x);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(100, 4, "CPF: " . $pessoa['cpf'], 0, 0, 'L');
}

$pdf->AddPage('', '');

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(10, $l, '(C)', 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(160, $l, utf8_decode('OBSERVAÇÃO'), 0, 1, 'C');

$pdf->Ln(5);

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 8);
$pdf->MultiCell(155, 4, utf8_decode($Observacao), 0, 'J', 0);

$pdf->AddPage('', '');

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(160, $l, utf8_decode('DECLARAÇÕES'), 0, 1, 'C');

$pdf->Ln(5);

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 8);
$pdf->MultiCell(155, $l, utf8_decode($penalidades['texto']), 0, 'J', 0);

$pdf->AddPage('', '');

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(10, $l, '', 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(160, 5, utf8_decode('NOS CASOS DE REVERSÃO DE BILHETERIA'), 0, 1, 'C');

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 9);
$pdf->MultiCell(155, $l, utf8_decode($reversao),0, 'J', 0);

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(10, $l, '', 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(160, 5, utf8_decode('RESCISÃO'), 0, 1, 'C');

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 9);
$pdf->MultiCell(155, 5, utf8_decode('Este instrumento poderá ser rescindido, no interesse da administração, devidamente justificado ou em virtude da inexecução total ou parcial do serviço sem prejuízo de multa, nos termos da legislação vigente.'));

$pdf->Ln(5);

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(10, $l, '', 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(160, 5, utf8_decode('FORO'), 0, 1, 'C');

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 9);
$pdf->MultiCell(155, 5, utf8_decode('Fica eleito o foro da Fazenda Pública para todo e qualquer procedimento judicial oriundo deste instrumento.'));

$pdf->Ln(5);

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(180, $l, "Data: _________ / _________ / " . "$ano" . ".", 0, 0, 'L');

$pdf->SetXY($x, 235);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(100, 4, utf8_decode($pessoa['nome']), 'T', 1, 'L');

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 10);

if ($pessoa['passaporte'] != NULL) {
    $pdf->Cell(100, 4, "Passaporte: " . $pessoa['passaporte'], 0, 1, 'L');
} else {
    $rg = $pessoa['rg'] == NULL ? "Não cadastrado" : $pessoa['rg'];
    $rg = "RG: " . $rg;
    $pdf->Cell(100, 4, utf8_decode($rg), 0, 1, 'L');
    $pdf->SetX($x);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(100, 4, "CPF: " . $pessoa['cpf'], 0, 0, 'L');
}

$pdf->Ln(7);

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 9);
$pdf->MultiCell(180, 5, utf8_decode('Autorizo a execução do serviço.'));

$pdf->Ln(4);

$pdf->SetXY($x, 262);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(100, 4, utf8_decode('Carla Mingolla'), 'T', 1, 'L');

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(100, 4, "Chefe de Gabinete", 0, 1, 'L');

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(100, 4, "Secretaria Municipal de Cultura", 0, 0, 'L');

$pdf->AddPage('', '');

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(180, 5, "CRONOGRAMA", 0, 1, 'C');

$pdf->Ln(5);

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(100, $l, utf8_decode($objeto), 0, 'L', 0);

if ($evento['tipo_evento_id'] == 1) {
    $cronograma = $con->query("SELECT * FROM ocorrencias WHERE origem_ocorrencia_id = " . $evento['id'] . " AND tipo_ocorrencia_id = 1 AND publicado = 1");
    while ($aux = mysqli_fetch_array($cronograma)) {
        $checaTipo = $con->query("SELECT acao_id FROM acao_atracao WHERE atracao_id = " . $aux['atracao_id'])->fetch_array();
        $tipoAcao = $con->query("SELECT acao FROM acoes WHERE id = " . $checaTipo['acao_id'] . " AND publicado = 1")->fetch_array();
        $acao = $tipoAcao['acao'];

        $dia = retornaPeriodoNovo($aux['origem_ocorrencia_id'], 'ocorrencias');
        $hour = exibirHora($aux['horario_inicio']) . "h - " . exibirHora($aux['horario_fim']) . "h";
        $local = $con->query("SELECT local FROM locais WHERE id = " . $aux['local_id'] . " AND publicado = 1")->fetch_array();
        $lugar = $local['local'];

        $pdf->SetX($x);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(11, $l, utf8_decode('Ação:'), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->MultiCell(158, $l, utf8_decode($acao));

        $pdf->SetX($x);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(21, $l, utf8_decode('Data/Perído:'), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->MultiCell(148, $l, utf8_decode($dia));

        $pdf->SetX($x);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(15, $l, utf8_decode('Horário:'), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->MultiCell(155, $l, utf8_decode($hour));

        $pdf->SetX($x);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(11, $l, utf8_decode('Local:'), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->MultiCell(158, $l, utf8_decode($lugar));

        $pdf->Ln(5);
    }

} elseif ($evento['tipo_evento_id'] == 2) {
    $filmes = $con->query("SELECT id, filme_id FROM filme_eventos WHERE evento_id = " . $evento['id']);
    foreach ($filmes as $filme) {
        $dadosFilme = $con->query("SELECT duracao, titulo FROM filmes WHERE id = " . $filme['filme_id'] . " AND publicado = 1")->fetch_array();
        $cronograma = $con->query("SELECT * FROM ocorrencias WHERE publicado = 1 AND tipo_ocorrencia_id = 2 AND origem_ocorrencia_id = " . $evento['id'] . " AND atracao_id = " . $filme['id']);
        while ($aux = mysqli_fetch_array($cronograma)) {

            $tipoAcao = $con->query("SELECT acao FROM acoes WHERE id = 1")->fetch_array();
            $acao = $tipoAcao['acao'];

            $pdf->SetX($x);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(12, $l, utf8_decode('Título:'), 0, 0, 'L');
            $pdf->SetFont('Arial', '', 10);
            $pdf->MultiCell(158, $l, utf8_decode($dadosFilme['titulo']));

            $pdf->SetX($x);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(16, $l, utf8_decode('Duração:'), 0, 0, 'L');
            $pdf->SetFont('Arial', '', 10);
            $pdf->MultiCell(158, $l, utf8_decode($dadosFilme['duracao'] . " Minuto(s)"));

            $dia = retornaPeriodoNovo($aux['origem_ocorrencia_id'], 'ocorrencias');
            $hour = exibirHora($aux['horario_inicio']) . "h - " . exibirHora($aux['horario_fim']) . "h";
            $local = $con->query("SELECT local FROM locais WHERE id = " . $aux['local_id'] . " AND publicado = 1")->fetch_array();
            $lugar = $local['local'];

            $pdf->SetX($x);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(11, $l, utf8_decode('Ação:'), 0, 0, 'L');
            $pdf->SetFont('Arial', '', 10);
            $pdf->MultiCell(158, $l, utf8_decode($acao));

            $pdf->SetX($x);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(21, $l, utf8_decode('Data/Perído:'), 0, 0, 'L');
            $pdf->SetFont('Arial', '', 10);
            $pdf->MultiCell(148, $l, utf8_decode($dia));

            $pdf->SetX($x);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(15, $l, utf8_decode('Horário:'), 0, 0, 'L');
            $pdf->SetFont('Arial', '', 10);
            $pdf->MultiCell(155, $l, utf8_decode($hour));

            $pdf->SetX($x);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(11, $l, utf8_decode('Local:'), 0, 0, 'L');
            $pdf->SetFont('Arial', '', 10);
            $pdf->MultiCell(158, $l, utf8_decode($lugar));

            $pdf->Ln(5);
        }
    }
}

$pdf->SetXY($x, 262);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(100, 4, utf8_decode($pessoa['nome']), 'T', 1, 'L');

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 10);

if ($pessoa['passaporte'] != NULL) {
    $pdf->Cell(100, 4, "Passaporte: " . $pessoa['passaporte'], 0, 1, 'L');
} else {
    $rg = $pessoa['rg'] == NULL ? "Não cadastrado" : $pessoa['rg'];
    $rg = "RG: " . $rg;
    $pdf->Cell(100, 4, utf8_decode($rg), 0, 1, 'L');
    $pdf->SetX($x);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(100, 4, "CPF: " . $pessoa['cpf'], 0, 0, 'L');
}

$pdf->Output();
?>



