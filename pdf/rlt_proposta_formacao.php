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
$idFC = $_SESSION['idFC'];
$pedido = recuperaDados('pedidos', 'id', $idPedido);
$idPf = $pedido['pessoa_fisica_id'];
$contratacao = recuperaDados('formacao_contratacoes', 'id', $idFC);
$pessoa = recuperaDados('pessoa_fisicas', 'id', $idPf);
$idPrograma = $contratacao['programa_id'];
$programa = recuperaDados('programas', 'id', $idPrograma);
$nacionalidade = recuperaDados('nacionalidades', 'id', $pessoa['nacionalidade_id']);

$sqlTelefone = "SELECT * FROM pf_telefones WHERE pessoa_fisica_id = '$idPf'";
$tel = "";
$queryTelefone = mysqli_query($con, $sqlTelefone);

$idFc = $pedido['origem_id'];
$sqlLocal = "SELECT l.local FROM formacao_locais fl INNER JOIN locais l on fl.local_id = l.id WHERE form_pre_pedido_id = '$idFc'";
$local = "";
$queryLocal = mysqli_query($con, $sqlLocal);

$carga = $_SESSION['formacao_carga_horaria'];

$Observacao = "Todas as atividades dos programas da Supervisão de Formação são inteiramente gratuitas e é terminantemente proibido cobrar por elas sob pena de multa e rescisão de contrato.";
$penal = "DAS OBRIGAÇÕES
1. Difundir o Programa de acordo com suas diretrizes e em diálogo com os Equipamentos e a Supervisão de Formação Cultural; 
2. Atuar conforme as atribuições da função para qual for convocado (ANEXO II) e orientações da Supervisão de Formação Cultural; 
3. Realizar as ações sob sua responsabilidade a partir das diretrizes do Programa e do contrato a ser firmado. 
4. Organizar e encaminhar impreterivelmente todos os conteúdos relativos aos instrumentais de pesquisa, planejamento e avaliação de atividades sempre que solicitados (registro de ação, relatórios, atestados, listas de presença etc). 
5. Atuar também como agentes públicos da cultura participando direta e indiretamente na criação e na produção artística no âmbito do programa. 
6. Participar obrigatoriamente dos encontros artístico-pedagógicos regionais e semanais de equipe e nos encontros mensais do Programa que ocorrerão às segundas-feiras pela manhã, entre 9h e 14h, com duração de 4 horas (no Vocacional) e  às sextas-feiras pela manhã, entre 9h e 14h, com duração de 4 horas (no PIÁ) . 
VII. Os encontros artístico-pedagógicos semanais com os coordenadores, artistas articuladores regionais e de áreas, ocorrerão às terças-feiras das 09h às 13h para a s questões de implantação e atuação local (no Vocacional) e às quintas-feiras das 09h às 13h (no PIÁ) .   
Observação: O plano de ação dos Orientadores considera a execução de até 6 (seis horas)  semanais de orientação artística no Programa Vocacional e execução de até 14 (quatorze horas) semanais de orientação artística no PIÁ.

DAS PENALIDADES
1. Ao contratado que não cumprir com as obrigações assumidas ou com os preceitos legais, conforme o caso e, observadas as condições expostas no item 5, serão aplicadas as seguintes penalidades: I. Advertência; II. Multa; III. Rescisão do contrato.
2. A critério da administração, a título de alerta para a adoção das medidas necessárias a fim de evitar a aplicação de sanções mais severas, sempre que descumpridas obrigações contratuais, ou desatendidas as determinações da Supervisão de Formação Cultural, no exercício da fiscalização do contrato, será aplicada a penalidade Advertência. 
3. Na hipótese de inexecução dos serviços, o contratado estará sujeito às seguintes sanções: I. Pela inexecução parcial, interrupção do contrato sem aviso prévio: multa de 20% (vinte por cento) do valor da parcela não executada do contrato. II. Pela inexecução total: multa de 30% (trinta por cento) do valor total do contrato. 
4. Será considerada como 1 (uma) falta a ausência em período de 3h (três horas). 
5. Para cada falta injustificada: multa de 5% (cinco por cento) sobre o valor mensal – não cumulativo, além do desconto da hora/atividade não trabalhado. O limite é de 2 (duas) faltas injustificadas durante todo o período da contratação sob pena de rescisão contratual por inexecução parcial e incidência da multa prevista no item 3.
6. As faltas justificadas, que não sejam por motivo de força maior (doença, morte em família etc.), serão limitadas a 2 (duas) durante todo o período de contratação, sob pena de rescisão contratual por inexecução parcial e aplicação da multa prevista no item 3. 
7. As faltas justificadas, assim como as de motivo de força maior, não ensejam a aplicação de penalidade ao contratado, mas deverão ser repostas no mesmo mês da sua efetivação com o acordo dos responsáveis e do gestor do equipamento em que esteja alocado, para que não haja desconto dos valores correspondentes no cálculo do pagamento devido. 
8. Durante a vigência do contrato, o contratado estará sujeito à legislação vigente, em especial ao Estatuto da Criança e do Adolescente, Estatuto do Idoso e Código Penal. 
9. Aplicam-se a esse contrato, no que couber, as disposições dos artigos 54 a 56 do Decreto Municipal nº 44279/2003 e da Lei Municipal nº 14141/2006.

DA RESCISÃO CONTRATUAL
1. O contrato poderá ser rescindido pela contratante a qualquer tempo, desde que justificada a rescisão e nos casos previstos no edital e na legislação em vigor. 
2. O contrato poderá ser rescindido por qualquer uma das partes, sem aplicação de penalidades, mediante a notificação à outra, por escrito, com 30 (trinta) dias de antecedência. 
3. A inexecução total ou parcial do contrato poderá ensejar a sua rescisão, desde que justificada a rescisão, com as conseqüências contratuais e as previstas em Lei ou regulamento.

DISPOSIÇÕES FINAIS
1. A inscrição do concorrente implica na prévia e integral concordância com as normas deste edital. 
2. O credenciado será responsável pelo desenvolvimento de sua atividade e pelas informações e conteúdos dos documentos apresentados, excluída qualquer responsabilidade civil ou penal das Secretarias Municipais de Cultura e/ou Educação nesse sentido, cabendo a estas a supervisão e fiscalização das atividades realizadas pelos contratados nos equipamentos sob sua administração nos termos deste edital. 
3. A supervisão de Formação poderá fazer o uso da imagem e os registros audiovisuais, bem como de toda produção decorrente das ações do Programa para fins estritamente institucional, para acompanhamento e divulgação do Programa nos canais de comunicação e redes sociais da Secretaria Municipal de Cultura 
4. O credenciamento realizado nos termos deste edital e as eventuais contratações dele derivadas não impedem a Administração de realizar outras contratações para atendimento das necessidades específicas das diretrizes e metas propostas pelas Secretarias Municipais de Cultura e/ou Educação. 
5. O credenciamento e/ou a contratação não geram vínculo trabalhista entre a Municipalidade e o contratado.

OBSERVAÇÕES 
Todas as atividades dos programas da Supervisão de Formação são inteiramente gratuitas e é terminantemente proibido cobrar por elas sob pena de multa e rescisão de contrato.
A supervisão de Formação poderá fazer o uso da imagem e os registros audiovisuais, bem como de toda produção decorrente das ações do Programa para fins estritamente institucional, para acompanhamento e divulgação do Programa nos canais de comunicação e redes sociais da Secretaria Municipal de Cultura.";

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
$pdf->SetFont('Arial', 'B', 10);
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
$pdf->Cell(5, $l, utf8_decode($pessoa['cpf']), 0, 0, 'L');

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
$pdf->MultiCell(160, $l, utf8_decode( $endereco['logradouro'] . ", " . $endereco['numero'] . " / - " .$endereco['bairro'] . " - " . $endereco['cidade'] . " / " . $endereco['uf']), 0, 'L', 0);

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
$pdf->Cell(10,10,utf8_decode($contratacao['protocolo']),0,1,'R');

$pdf->Ln(5);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(9, $l, 'Ano:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(50, $l, utf8_decode($contratacao['ano']), 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(25, $l, utf8_decode("Carga Horária:"), '0', '0', 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(168, $l, utf8_decode($carga), 0, 0, 'L');

$pdf->Ln(7);

while ($linhaLocal = mysqli_fetch_array($queryLocal)) {
    $local = $local . $linhaLocal['local'] . ' | ';
}

$local = substr($local, 0, -3);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(16, $l, 'Local(s):', '0', '0', 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(165, $l, utf8_decode($local), 0, 'L', 0);

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
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(155, $l, utf8_decode($Observacao),0, 'J', 0);

$pdf->SetX($x);
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(0, 4, utf8_decode($penal),0, 'J', 0);

$pdf->SetX($x);
$pdf->SetFont('Arial','', 10);
$pdf->Cell(180,$l,utf8_decode("Data: _________ / _________ / " . $contratacao['ano']) . ".",0,0,'L');

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

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 12);
$pdf->Cell(180,5,utf8_decode($programa['programa']),0,1,'C');

$pdf->Ln(5);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(12, $l, 'Nome:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(40, $l, utf8_decode($pessoa['nome']), 0, 'L', 0);

$idCargo = $contratacao['form_cargo_id'];
$cargo = recuperaDados('formacao_cargos', 'id', $idCargo);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(12, $l, 'Cargo:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(40, $l, utf8_decode($cargo['cargo']), 0, 'L', 0);


$idLinguagem = $contratacao['linguagem_id'];
$linguagem = recuperaDados('linguagens', 'id', $idLinguagem);

$pdf->SetX($x);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(21, $l, 'Linguagem:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(40, $l, utf8_decode($linguagem['linguagem']), 0, 'L', 0);

$pdf->Ln(5);

$idVigencia = $contratacao['form_vigencia_id'];

$sqlParcelas = "SELECT * FROM formacao_parcelas WHERE formacao_vigencia_id = '$idVigencia' ORDER BY data_inicio ASC";
$query = mysqli_query($con,$sqlParcelas);
while($parcela = mysqli_fetch_array($query))
{
    if($parcela['valor'] > 0)
    {
        $inicio = exibirDataBr($parcela['data_inicio']);
        $fim = exibirDataBr($parcela['data_fim']);
        $horas = $parcela['carga_horaria'];

        $pdf->SetX($x);
        $pdf->SetFont('Arial','', 10);
        $pdf->MultiCell(180,$l,utf8_decode("De $inicio a $fim - até $horas horas"));
    }
}

$pdf->SetX($x);
$pdf->SetFont('Arial','', 10);
$pdf->Cell(180,$l,utf8_decode("São Paulo, ______ de ____________________ de ".$contratacao['ano']).".",0,0,'L');

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

