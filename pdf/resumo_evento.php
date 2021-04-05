<?php
// INSTALAÇÃO DA CLASSE NA PASTA FPDF.
require_once("../include/lib/fpdf/fpdf.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");

//CONEXÃO COM BANCO DE DADOS
$con = bancoMysqli();

   
class PDF extends FPDF
{
    function Header()
    {
        $this->Image('../pdf/logo_smc.jpg',165, 10);
        $this->Ln(20);
    }
}

//CONSULTA
$idEvento = $_GET['id'];

$con = bancoMysqli();
$evento = $con->query("SELECT  e.protocolo, e.tipo_evento_id, e.nome_evento, e.espaco_publico, e.fomento, f.fomento AS fomento_nome, rj.relacao_juridica, pe.projeto_especial, e.sinopse, uf.nome_completo AS fiscal_nome, us.nome_completo AS suplente_nome, uf.nome_completo AS user_nome
    FROM eventos AS e
    LEFT JOIN fomentos f on e.fomento = f.fomento
    INNER JOIN relacao_juridicas rj on e.relacao_juridica_id = rj.id
    INNER JOIN projeto_especiais pe on e.projeto_especial_id = pe.id
    INNER JOIN usuarios uf on e.fiscal_id = uf.id
    INNER JOIN usuarios us on e.suplente_id = us.id
    INNER JOIN usuarios ur on e.usuario_id = ur.id
    WHERE e.id = '$idEvento'
")->fetch_assoc();
if ($evento['espaco_publico'] == 1) $espaco = "Sim"; else $espaco = "Não";
if ($evento['fomento'] == 1) $fomento = $evento['fomento_nome']; else $fomento = "Não";



$pedido = $con->query("SELECT p.id, p.pessoa_tipo_id, p.pessoa_fisica_id, p.pessoa_juridica_id, p.numero_processo, l.extrato_liquidacao, l.retencoes_inss, l.retencoes_iss, l.retencoes_irrf FROM pedidos AS p INNER JOIN eventos AS e ON p.origem_id = e.id LEFT JOIN liquidacao l on p.id = l.pedido_id WHERE e.publicado = 1 AND p.publicado = 1 AND p.origem_id = '$idEvento'")->fetch_array();


// GERANDO O PDF:
$pdf = new PDF('P','mm','A4'); //CRIA UM NOVO ARQUIVO PDF NO TAMANHO A4
$pdf->AliasNbPages();
$pdf->AddPage();

   
$x=20;
$l=6; //DEFINE A ALTURA DA LINHA   

$pdf->SetXY( $x , 35 );// SetXY - DEFINE O X (largura) E O Y (altura) NA PÁGINA

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 14);
$pdf->Cell(160,5,utf8_decode($evento['nome_evento']),0,1,'C');

$pdf->Ln();

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 11);
$pdf->Cell(20, $l, utf8_decode("Protocolo:"), 0, 0, 'L');
$pdf->SetFont('Arial','', 11);
$pdf->Cell(155, $l, utf8_decode($evento['protocolo']), 0, 1, 'L');

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 11);
$pdf->Cell(31, $l, utf8_decode("Espaço público:"), 0, 0, 'L');
$pdf->SetFont('Arial','', 11);
$pdf->Cell(15, $l, utf8_decode($espaco), 0, 0, 'L');
$pdf->SetFont('Arial','B', 11);
$pdf->Cell(19, $l, utf8_decode("Fomento:"), 0, 0, 'L');
$pdf->SetFont('Arial','', 11);
$pdf->Cell(115, $l, utf8_decode($fomento), 0, 1, 'L');

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 11);
$pdf->Cell(32, $l, utf8_decode("Relação jurídica:"), 0, 0, 'L');
$pdf->SetFont('Arial','', 11);
$pdf->Cell(40, $l, utf8_decode($evento['relacao_juridica']), 0, 1, 'L');

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 11);
$pdf->Cell(32, $l, utf8_decode("Projeto especial:"), 0, 0, 'L');
$pdf->SetFont('Arial','', 11);
$pdf->Cell(115, $l, utf8_decode($evento['projeto_especial']), 0, 1, 'L');

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 11);
$pdf->Cell(17, $l, utf8_decode("Sinopse:"), 0, 0, 'L');
$pdf->SetFont('Arial','', 11);
$pdf->MultiCell(163,$l,utf8_decode($evento['sinopse']));

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 11);
$pdf->Cell(13, $l, utf8_decode("Fiscal:"), 0, 0, 'L');
$pdf->SetFont('Arial','', 11);
$pdf->Cell(115, $l, utf8_decode($evento['fiscal_nome']), 0, 1, 'L');

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 11);
$pdf->Cell(19, $l, utf8_decode("Suplente:"), 0, 0, 'L');
$pdf->SetFont('Arial','', 11);
$pdf->Cell(115, $l, utf8_decode($evento['suplente_nome']), 0, 1, 'L');

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 11);
$pdf->Cell(26, $l, utf8_decode("Responsável:"), 0, 0, 'L');
$pdf->SetFont('Arial','', 11);
$pdf->Cell(115, $l, utf8_decode($evento['user_nome']), 0, 1, 'L');

$pdf->Ln(10);

// início atração
if ($evento['tipo_evento_id'] == 1) {
    $sql_atracao = $con->query("SELECT * 
        FROM atracoes 
        INNER JOIN classificacao_indicativas ci on atracoes.classificacao_indicativa_id = ci.id
    WHERE evento_id = '$idEvento' AND publicado = 1");

    while ($atracao = mysqli_fetch_array($sql_atracao)) {
        $sql_acao = $con->query("SELECT a.acao FROM acao_atracao at INNER JOIN acoes a on at.acao_id = a.id WHERE atracao_id = '{$atracao['id']}'");
        $acoes = "";
        while ($acao = mysqli_fetch_array($sql_acao)) {
            $acoes .= $acao['acao'] . '; ';
        }
        $produtor = $con->query("SELECT * FROM produtores WHERE id = '{$atracao['produtor_id']}'")->fetch_assoc();
        $sql_ocorrencia = $con->query("SELECT * FROM ocorrencias WHERE origem_ocorrencia_id = '$idEvento' AND publicado = 1");

        $pdf->SetX($x);
        $pdf->SetFont('Arial','B', 11);
        $pdf->Cell(17, $l, utf8_decode("Atração:"), 'B', 0, 'L');
        $pdf->SetFont('Arial','', 11);
        $pdf->Cell(155, $l, utf8_decode($atracao['nome_atracao']), 'B', 1, 'L');

        $pdf->SetX($x);
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(12, $l, utf8_decode("Ação:"), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(115, $l, utf8_decode($acoes), 0, 1, 'L');

        $pdf->SetX($x);
        $pdf->SetFont('Arial','B', 11);
        $pdf->Cell(27, $l, utf8_decode("Ficha técnica:"), 0, 0, 'L');
        $pdf->SetFont('Arial','', 11);
        $pdf->MultiCell(163,$l,utf8_decode($atracao['ficha_tecnica']));

        $pdf->SetX($x);
        $pdf->SetFont('Arial','B', 11);
        $pdf->Cell(23, $l, utf8_decode("Integrantes:"), 0, 0, 'L');
        $pdf->SetFont('Arial','', 11);
        $pdf->MultiCell(163,$l,utf8_decode($atracao['integrantes']));

        $pdf->SetX($x);
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(46, $l, utf8_decode("Classificação indicativa:"), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(25, $l, utf8_decode($atracao['classificacao_indicativa']), 0, 1, 'L');

        $pdf->SetX($x);
        $pdf->SetFont('Arial','B', 11);
        $pdf->Cell(18, $l, utf8_decode("Release:"), 0, 0, 'L');
        $pdf->SetFont('Arial','', 11);
        $pdf->MultiCell(163,$l,utf8_decode($atracao['release_comunicacao']));

        $pdf->SetX($x);
        $pdf->SetFont('Arial','B', 11);
        $pdf->Cell(13, $l, utf8_decode("Links:"), 0, 0, 'L');
        $pdf->SetFont('Arial','', 11);
        $pdf->MultiCell(163,$l,utf8_decode($atracao['links']));

        $pdf->SetX($x);
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(56, $l, utf8_decode("Quantidede de apresentação:"), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(15, $l, utf8_decode($atracao['quantidade_apresentacao']), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(31, $l, utf8_decode("Valor individual:"), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(15, $l, utf8_decode("R$ ".dinheiroParaBr($atracao['valor_individual'])), 0, 1, 'L');

        $pdf->Ln();

        $pdf->SetX($x);
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(19, $l, utf8_decode("Produtor:"), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(15, $l, utf8_decode($produtor['nome']), 0, 1, 'L');

        $pdf->SetX($x);
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(14, $l, utf8_decode("E-mail:"), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(75, $l, utf8_decode($produtor['email']), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(20, $l, utf8_decode("Telefones:"), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(15, $l, utf8_decode($produtor['telefone1']." | ".$produtor['telefone2']), 0, 1, 'L');

        $pdf->SetX($x);
        $pdf->SetFont('Arial','B', 11);
        $pdf->Cell(25, $l, utf8_decode("Observação:"), 0, 0, 'L');
        $pdf->SetFont('Arial','', 11);
        $pdf->MultiCell(160,$l,utf8_decode($produtor['observacao']));

        $pdf->Ln();

        $pdf->SetX($x);
        $pdf->SetFont('Arial','B', 11);
        $pdf->Cell(25, $l, utf8_decode("Ocorrências"), 0, 1, 'L');

        while ($ocorrencia = mysqli_fetch_array($sql_ocorrencia)){
            $local = recuperaDados('locais', 'id', $ocorrencia['local_id']);
            $retirada_ingresso = recuperaDados('retirada_ingressos', 'id', $ocorrencia['retirada_ingresso_id']) ?? NULL;
            $instituicao = recuperaDados('instituicoes', 'id', $ocorrencia['instituicao_id'])['sigla'] ?? NULL;
            $espaco = recuperaDados('espacos', 'id', $ocorrencia['espaco_id'])['espaco'] ?? NULL;

            $pdf->SetX($x);
            $pdf->SetFont('Arial','', 11);
            $pdf->Cell(10, $l, utf8_decode("Data:"), 0, 0, 'L');
            $pdf->SetFont('Arial','', 11);
            $pdf->Cell(21, $l, utf8_decode(exibirDataBr($ocorrencia['data_inicio'])), 0, 0, 'L');
            if ($ocorrencia['data_fim'] != "0000-00-00"){
                $pdf->Cell(21, $l, utf8_decode("até ".exibirDataBr($ocorrencia['data_fim'])), 0, 0, 'L');
            }
            $pdf->Ln();

            $pdf->SetX($x);
            $pdf->SetFont('Arial','', 11);
            $pdf->Cell(15, $l, utf8_decode("Horário:"), 0, 0, 'L');
            $pdf->SetFont('Arial','', 11);
            $pdf->Cell(21, $l, utf8_decode("das ".exibirHora($ocorrencia['horario_inicio'])." às ".exibirHora($ocorrencia['horario_fim'])), 0, 1, 'L');

            $pdf->SetX($x);
            $pdf->SetFont('Arial','', 11);
            $pdf->Cell(13, $l, utf8_decode("Local:"), 0, 0, 'L');
            $pdf->SetFont('Arial','', 11);
            $pdf->MultiCell(160,$l,utf8_decode("($instituicao) {$local['local']}"));

            //var_dump($ocorrencia);

        }

        $pdf->Ln(10);
    }
}
// fim atração

$pdf->Ln();

$pdf->SetX($x);
$pdf->SetFont('Arial','', 11);
$pdf->MultiCell(160,$l,utf8_decode("Em, ______ de _______________________ de ".date('Y')."."));

$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();

if($pedido['pessoa_tipo_id'] == 1) {
    $idPf = $pedido['pessoa_fisica_id'];
    $pf = $con->query("SELECT nome, rg, cpf, ccm FROM pessoa_fisicas WHERE id = '$idPf'")->fetch_array();

    $pdf->SetX($x);
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(14, $l, utf8_decode('Nome:'), 0, 0, 'L');
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(150, $l, utf8_decode($pf['nome']), 0, 1, 'L');

    $pdf->SetX($x);
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(9, $l, utf8_decode('RG:'), 0, 0, 'L');
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(150, $l, utf8_decode($pf['rg']), 0, 1, 'L');

    $pdf->SetX($x);
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(10, $l, utf8_decode('CPF:'), 0, 0, 'L');
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(150, $l, utf8_decode($pf['cpf']), 0, 1, 'L');

    $pdf->SetX($x);
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(12, $l, utf8_decode('CCM:'), 0, 0, 'L');
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(150, $l, utf8_decode($pf['ccm']), 0, 1, 'L');
}
else{
    $idPj = $pedido['pessoa_juridica_id'];
    $pj = $con->query("SELECT razao_social, cnpj, ccm, representante_legal1_id, representante_legal2_id FROM pessoa_juridicas WHERE id = '$idPj'")->fetch_array();
    $idRep1 = $pj['representante_legal1_id'];
    $idRep2 = $pj['representante_legal2_id'];
    $rep1 = $con->query("SELECT nome FROM representante_legais WHERE id = '$idRep1'")->fetch_array();
    $rep2 = $con->query("SELECT nome FROM representante_legais WHERE id = '$idRep2'")->fetch_array();

    $pdf->SetX($x);
    $pdf->SetFont('Arial','B', 11);
    $pdf->Cell(27,$l,utf8_decode('Razão Social:'),0,0,'L');
    $pdf->SetFont('Arial','', 11);
    $pdf->Cell(150,$l,utf8_decode($pj['razao_social']),0,1,'L');

    $pdf->SetX($x);
    $pdf->SetFont('Arial','B', 11);
    $pdf->Cell(14,$l,utf8_decode('CNPJ:'),0,0,'L');
    $pdf->SetFont('Arial','', 11);
    $pdf->Cell(150,$l,utf8_decode($pj['cnpj']),0,1,'L');

    $pdf->SetX($x);
    $pdf->SetFont('Arial','B', 11);
    $pdf->Cell(12,$l,utf8_decode('CCM:'),0,0,'L');
    $pdf->SetFont('Arial','', 11);
    $pdf->Cell(150,$l,utf8_decode($pj['ccm']),0,1,'L');

    $pdf->SetX($x);
    $pdf->SetFont('Arial','B', 11);
    $pdf->Cell(37,$l,utf8_decode('Responsável (eis):'),0,0,'L');
    $pdf->SetFont('Arial','', 11);
    $pdf->Cell(143,$l,utf8_decode($rep1['nome']),0,1,'L');

    $pdf->SetX($x);
    $pdf->SetFont('Arial','B', 11);
    $pdf->Cell(37,$l,'',0,0,'L');
    $pdf->SetFont('Arial','', 11);
    $pdf->Cell(143,$l,utf8_decode($rep2['nome']),0,1,'L');
}

$pdf->Output();