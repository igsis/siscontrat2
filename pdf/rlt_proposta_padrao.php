<?php

// INSTALAÇÃO DA CLASSE NA PASTA FPDF.
require_once("../include/lib/fpdf/fpdf.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");


$con = bancoMysqli();
session_start();

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

$idPedido = $_SESSION['idPedido'];
$pedido = recuperaDados('pedidos', 'id', $idPedido);
$idPf = $pedido['pessoa_fisica_id'];
$idFC = $pedido['origem_id'];
$evento = recuperaDados('eventos', 'id', $pedido['origem_id']);
$ocorrencia = recuperaDados('ocorrencias', 'origem_ocorrencia_id', $evento['id']);
$pessoa = recuperaDados('pessoa_fisicas', 'id', $idPf);
$nacionalidade = recuperaDados('nacionalidades', 'id', $pessoa['nacionalidade_id']);

$sqlTelefone = "SELECT * FROM pf_telefones WHERE pessoa_fisica_id = '$idPf'";
$tel = "";
$queryTelefone = mysqli_query($con, $sqlTelefone);

$idLocal = $ocorrencia['local_id'];
$sqlLocal = "SELECT local FROM locais WHERE id = '$idLocal'";
$locais = $con->query($sqlLocal)->fetch_array();

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

$sqlPenalidade = "SELECT texto FROM penalidades WHERE id = 13";
$penalidades = $con->query($sqlPenalidade)->fetch_array();

$sqlDRT = "SELECT drt FROM drts WHERE pessoa_fisica_id = $idPf";
$drt = $con->query($sqlDRT)->fetch_array();

$objeto = retornaTipo($evento['tipo_evento_id']) . " - " . $evento['nome_evento'];

$periodo = retornaPeriodoNovo($pedido['origem_id'], 'ocorrencias');

$pdf = new PDF('P', 'mm', 'A4'); //CRIA UM NOVO ARQUIVO PDF NO TAMANHO A4
$pdf->AliasNbPages();
$pdf->AddPage();


$x = 20;
$l = 7; //DEFINE A ALTURA DA LINHA

$pdf->SetXY($x, 35);// SetXY - DEFINE O X (largura) E O Y (altura) NA PÁGINA

$pdf->SetX($x);
$pdf->SetFont('Arial','', 10);
$pdf->Cell(10,5,'(A)',0,0,'L');
$pdf->SetFont('Arial','B', 12);
$pdf->Cell(170,5,'CONTRATADO',0,1,'C');

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
$pdf->MultiCell(120, $l, utf8_decode($pessoa['nome_artistico']), 0, 'L', 0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(7, $l, utf8_decode('RG:'), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(50, $l, utf8_decode($pessoa['rg']), 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(9, $l, utf8_decode('CPF:'), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(45, $l, utf8_decode($pessoa['cpf']), 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(9, $l, utf8_decode("DRT:"), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(5, $l, utf8_decode($drt['drt']), 0, 0, 'L');

$pdf->Ln(7);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(35, $l, 'Data de Nascimento:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(25, $l, utf8_decode(exibirDataBr($pessoa['data_nascimento'])), 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(26, $l, "Nacionalidade:", 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(30, $l, utf8_decode($nacionalidade['nacionalidade']),0, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(10, $l, "CCM:", 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(30, $l, utf8_decode($pessoa['ccm']),0 ,0, 'L');

$pdf->Ln(7);

$endereco = recuperaDados('pf_enderecos', 'pessoa_fisica_id', $idPf);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(18, $l, utf8_decode("Endereço:"), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(160, $l, utf8_decode($endereco['logradouro'] . ", " . $endereco['numero'] . " " . $endereco['complemento'] . " / - " .$endereco['bairro'] . " - " . $endereco['cidade'] . " / " . $endereco['uf']), 0, 'L', 0);

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
$pdf->Cell(180,5,'','B',1,'C');

$pdf->Ln(5);

$pdf->SetX($x);
$pdf->SetFont('Arial','', 10);
$pdf->Cell(10,10,'(B)',0,0,'L');
$pdf->SetFont('Arial','B', 12);
$pdf->Cell(160,10,'PROPOSTA',0,0,'C');
$pdf->SetFont('Arial','', 10);
$pdf->Cell(10,10,utf8_decode($evento['protocolo']),0,1,'R');

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(13,$l,'Objeto:',0,0,'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(140,$l,utf8_decode($objeto),0,0,'L');

$pdf->Ln(6);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(26, $l, utf8_decode('Data / Período:'), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(50, $l, utf8_decode($periodo), 0, 'L', 0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(11, $l, 'Local:', '0', '0', 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(165, $l, utf8_decode($locais['local']), 0, 'L', 0);

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
$pdf->SetXY($x,262);
$pdf->SetFont('Arial','', 10);
$pdf->Cell(100,4,utf8_decode($pessoa['nome']),'T',1,'L');

$pdf->SetX($x);
$pdf->SetFont('Arial','', 10);
$pdf->Cell(100,4,"RG: ".$pessoa['rg'],0,1,'L');

$pdf->SetX($x);
$pdf->SetFont('Arial','', 10);
$pdf->Cell(100,4,"CPF: ".$pessoa['cpf'],0,0,'L');

$pdf->AddPage('','');

$pdf->SetX($x);
$pdf->SetFont('Arial','', 10);
$pdf->Cell(10,$l,'(C)',0,0,'L');
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(160,$l,utf8_decode('OBSERVAÇÃO'),0,1,'C');

$pdf->Ln(5);

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 8);
$pdf->MultiCell(155, $l, utf8_decode($Observacao),0, 'J', 0);

$pdf->AddPage('','');

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(160,$l,utf8_decode('DECLARAÇÕES'),0,1,'C');

$pdf->Ln(5);

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 8);
$pdf->MultiCell(155, $l, utf8_decode($penalidades['texto']),0, 'J', 0);

$pdf->Ln(5);

$pdf->SetX($x);
$pdf->SetFont('Arial','', 9);
$pdf->Cell(10,$l,'',0,0,'L');
$pdf->SetFont('Arial','B', 9);
$pdf->Cell(160,5,utf8_decode('NOS CASOS DE REVERSÃO DE BILHETERIA'),0,1,'C');

$pdf->SetX($x);
$pdf->SetFont('Arial','', 9);
$pdf->MultiCell(180,5,utf8_decode('1) No caso de pagamento do cachê por reversão de bilheteria, fica o valor dos ingressos sujeito ao atendimento no disposto nas Leis Municipais nº 10.973/91, regulamentada pelo Decreto Municipal nº 30.730/91; Leis Municipais 11.113/91; 11.357/93 e 12.975/2000 e Portaria nº 66/SMC/2007; Lei Estadual nº 7844/92, regulamentada pelo Decreto Estadual nº 35.606/92; Lei Estadual nº 10.858/2001, com as alterações da Lei Estadual 14.729/2012 e Lei Federal nº 12.933/2013.'));

$pdf->SetX($x);
$pdf->SetFont('Arial','', 9);
$pdf->MultiCell(180,5,utf8_decode('2) O pagamento do cachê corresponderá à reversão integral da renda obtida na bilheteria a/o ontratada/o, deduzidos os impostos e taxas pertinentes.'));

$pdf->SetX($x);
$pdf->SetFont('Arial','', 9);
$pdf->Cell(10,$l,'',0,0,'L');
$pdf->SetFont('Arial','B', 9);
$pdf->Cell(160,5,utf8_decode('RESCISÃO'),0,1,'C');

$pdf->SetX($x);
$pdf->SetFont('Arial','', 9);
$pdf->MultiCell(180,5,utf8_decode('Este instrumento poderá ser rescindido, no interesse da administração, devidamente justificado ou em virtude da inexecução total ou parcial do serviço sem prejuízo de multa, nos termos da legislação vigente.'));

$pdf->Ln(5);

$pdf->SetX($x);
$pdf->SetFont('Arial','', 9);
$pdf->Cell(10,$l,'',0,0,'L');
$pdf->SetFont('Arial','B', 9);
$pdf->Cell(160,5,utf8_decode('FORO'),0,1,'C');

$pdf->SetX($x);
$pdf->SetFont('Arial','', 9);
$pdf->MultiCell(180,5,utf8_decode('Fica eleito o foro da Fazenda Pública para todo e qualquer procedimento judicial oriundo deste instrumento.'));

$pdf->Ln(5);

$pdf->SetX($x);
$pdf->SetFont('Arial','', 10);
$pdf->Cell(180,$l,"Data: _________ / _________ / "."$ano".".",0,0,'L');

$pdf->SetXY($x,262);
$pdf->SetFont('Arial','', 10);
$pdf->Cell(100,4,utf8_decode($pessoa['nome']),'T',1,'L');

$pdf->SetX($x);
$pdf->SetFont('Arial','', 10);
$pdf->Cell(100,4,"RG: ".$pessoa['rg'],0,1,'L');

$pdf->SetX($x);
$pdf->SetFont('Arial','', 10);
$pdf->Cell(100,4,"CPF: ".$pessoa['cpf'],0,0,'L');

$pdf->AddPage('','');

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 12);
$pdf->Cell(180,5,"CRONOGRAMA",0,1,'C');

$pdf->Ln(5);

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(40, $l, utf8_decode($objeto), 0, 'L', 0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(9, $l, 'Tipo:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(40, $l, utf8_decode(retornaTipo($evento['id'])), 0, 'L', 0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(22, $l, utf8_decode('Data/Perído:'), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(40, $l, utf8_decode($periodo), 0, 'L', 0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(15, $l,utf8_decode('Horário:'),0,0,'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(40, $l, utf8_decode(exibirHora($ocorrencia['horario_inicio']) . " - " . exibirHora($ocorrencia['horario_fim'])), 0, 'L', 0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(11, $l,utf8_decode('Local:'),0,0,'L');
$pdf->SetFont('Arial', '', '10');
$pdf->MultiCell(140,$l, utf8_decode($locais['local']), 0,'L',0);

$pdf->SetXY($x,262);
$pdf->SetFont('Arial','', 10);
$pdf->Cell(100,4,utf8_decode($pessoa['nome']),'T',1,'L');

$pdf->SetX($x);
$pdf->SetFont('Arial','', 10);
$pdf->Cell(100,4,"RG: ".$pessoa['rg'],0,1,'L');

$pdf->SetX($x);
$pdf->SetFont('Arial','', 10);
$pdf->Cell(100,4,"CPF: ".$pessoa['cpf'],0,0,'L');

$pdf->Output();
?>


