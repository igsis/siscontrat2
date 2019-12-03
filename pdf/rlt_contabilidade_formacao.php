<?php

// INSTALAÇÃO DA CLASSE NA PASTA FPDF.
require_once("../include/lib/fpdf/fpdf.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");


$con = bancoMysqli();
session_start();

class PDF extends FPDF{

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(0, 15, "Clique aqui para ir para o ", 0, 0, 'L');
        $this->Image("../visual/images/logo_sei.jpg", 50, 286, 0, 5, "", "http://sei.prefeitura.sp.gov.br");
    }
}

$idPedido = $_SESSION['idPedido'];
$pedido = recuperaDados('pedidos', 'id', $idPedido);
$idFC = $pedido['origem_id'];
$idPf = $pedido['pessoa_fisica_id'];
$pessoa = recuperaDados('pessoa_fisicas', 'id', $idPf);
$contratacao = recuperaDados('formacao_contratacoes', 'id', $idFC);

$data = date("Y-m-d", strtotime("-3 hours"));

$dia = date("d");

$mes = date("m");

$ano = date("Y");

switch ($mes) {

    case 1:
        $mes = "Janeiro";
        break;
    case 2:
        $mes = "Fevereiro";
        break;
    case 3:
        $mes = "Março";
        break;
    case 4:
        $mes = "Abril";
        break;
    case 5:
        $mes = "Maio";
        break;
    case 6:
        $mes = "Junho";
        break;
    case 7:
        $mes = "Julho";
        break;
    case 8:
        $mes = "Agosto";
        break;
    case 9:
        $mes = "Setembro";
        break;
    case 10:
        $mes = "Outubro";
        break;
    case 11:
        $mes = "Novembro";
        break;
    case 12:
        $mes = "Dezembro";
        break;
}

$pdf = new PDF('P', 'mm', 'A4'); //CRIA UM NOVO ARQUIVO PDF NO TAMANHO A4
$pdf->AliasNbPages();
$pdf->AddPage();


$x = 20;
$l = 7; //DEFINE A ALTURA DA LINHA

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(22, $l, utf8_decode("Interessado:"), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(160, $l, utf8_decode($pessoa['nome']), 0, 'L', 0);

$idLinguagem = $contratacao['linguagem_id'];
$linguagem = recuperaDados('linguagens', 'id', $idLinguagem);

$idPrograma = $contratacao['programa_id'];
$programa = recuperaDados('programas', 'id', $idPrograma);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(19, $l, utf8_decode("Do evento:"), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(40, $l, utf8_decode($programa['programa'] . " - " . $linguagem['linguagem']), 0, 'L', 0);

$pdf->Ln(17);

$pdf->SetX($x);
$pdf->MultiCell(180,$l, utf8_decode("Atesto o recebimento em " . exibirDataBr($data) . ", de toda a documentação: recibo link SEI e arquivos consolidados, previstos na Portaria SF 08/16."),0,'L',0);

$pdf->Ln(17);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->MultiCell(180, $l, utf8_decode("SMC - CONTABILIDADE"), 0, 'L', 0);

$pdf->SetX($x);
$pdf->MultiCell(120, $l, utf8_decode("Sr.(a) Contador(a)"), 0, 'L', 0);

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(180, $l, utf8_decode("Encaminho o presente para providências quanto ao pagamento, uma vez que os serviços foram realizados e confirmados a contento conforme documento link SEI."), 0, 'L', 0);

$pdf->Ln(7);

$idCoord = $contratacao['coordenadoria_id'];
$coordenadoria = recuperaDados('coordenadorias','id', $idCoord);

$pdf->SetX($x);
$pdf->MultiCell(180, $l, utf8_decode("Em virtude da Regionalização e Georreferenciamento das Despesas Municipais com a nova implantação do Detalhamento da Ação em 2019 no Sistema SOF,  informamos que os valores do presente pagamento foram gastos na região ".$coordenadoria['coordenadoria']."."), 0, 'L', 0);

$pdf->Ln(17);

$pdf->SetX($x);
$pdf->MultiCell(180,$l,utf8_decode("INFORMAÇÕES COMPLEMENTARES"),'B', 'L', 0);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B',10);
$pdf->MultiCell(180,$l,utf8_decode("Nota de Empenho:"),0, 'L', 0);

$pdf->SetX($x);
$pdf->MultiCell(180,$l,utf8_decode("Anexo Nota de Empenho:"),0, 'L', 0);

$pdf->SetX($x);
$pdf->MultiCell(180,$l,utf8_decode("Recibo da Nota de Empenho:"),0, 'L', 0);

$pdf->SetX($x);
$pdf->MultiCell(180,$l,utf8_decode("Pedido de Pagamento:"),0, 'L', 0);

$pdf->SetX($x);
$pdf->MultiCell(180,$l,utf8_decode("Recibo de pagamento:"),0, 'L', 0);

$pdf->SetX($x);
$pdf->MultiCell(180,$l,utf8_decode("Relatório de Horas Trabalhadas:"),0, 'L', 0);

$pdf->SetX($x);
$pdf->MultiCell(180,$l,utf8_decode("NIT/PIS/PASEP:"),0, 'L', 0);

$pdf->SetX($x);
$pdf->MultiCell(180,$l,utf8_decode("Certidões fiscais:"),0, 'L', 0);

$pdf->SetX($x);
$pdf->MultiCell(180,$l,utf8_decode("CCM:"),0, 'L', 0);

$pdf->SetX($x);
$pdf->MultiCell(180,$l,utf8_decode("FACC:"),0, 'L', 0);

$pdf->Ln(17);

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(180,$l,utf8_decode("São Paulo, " . $dia . " de " . $mes . " de " . $ano));

$pdf->Output();
?>


