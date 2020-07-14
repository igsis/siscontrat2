<?php

setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');

include '../funcoes/funcoesConecta.php';
$con = bancoMysqli();
// Incluimos a classe PHPExcel
require_once("../include/phpexcel/Classes/PHPExcel.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");
session_start(['name' => 'sis']);

$idPedido = $_POST['idPedido'];

$sql = "SELECT    pc.id,
                  fc.protocolo,
                  pc.origem_id,
                  fc.pessoa_fisica_id AS 'idp', 
	              pf.nome AS 'nome',
	              p.programa AS 'programa',
                  c.cargo AS 'funcao',
                  l.linguagem AS 'linguagem',
                  pf.email AS 'email',
                  st.status AS 'status'
            FROM pedidos AS pc
            INNER JOIN formacao_contratacoes fc ON fc.id = pc.origem_id
            INNER JOIN pessoa_fisicas AS pf ON pf.id = fc.pessoa_fisica_id
            INNER JOIN programas AS p ON p.id = fc.programa_id
	        INNER JOIN formacao_cargos AS c ON c.id = fc.form_cargo_id
            INNER JOIN linguagens AS l ON l.id = fc.linguagem_id
            INNER JOIN formacao_status AS st ON st.id = fc.form_status_id 
            WHERE pc.id = $idPedido AND pc.origem_tipo_id = 2 AND pc.publicado = 1";

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

$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue("A1")
    ->setCellValue("B1")
    ->setCellValue("C1")
    ->setCellValue("D1")
    ->setCellValue("E1", "PEDIDO DE CONTRATACAO")
    ->setCellValue("F1")
    ->setCellValue("G1")
    ->setCellValue("H1")
    ->setCellValue("I1");

//Colorir o header
$objPHPExcel->getActiveSheet()->getStyle("A1:I1")->applyFromArray
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


// Criamos as colunas

$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue("A2", "Nome Completo")
    ->setCellValue("B2", "Programa")
    ->setCellValue("C2", "Função")
    ->setCellValue("D2", "Linguagem")
    ->setCellValue("E2", "E-mail")
    ->setCellValue("F2", "Telefones do Proponente")
    ->setCellValue("G2", "Status do Pedido");

// Definimos o estilo da fonte
$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getFont()->setBold(true);

//Colorir a primeira linha
$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->applyFromArray
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


    $a = "A" . $cont;
    $b = "B" . $cont;
    $c = "C" . $cont;
    $d = "D" . $cont;
    $e = "E" . $cont;
    $f = "F" . $cont;
    $g = "G" . $cont;
    $h = "H" . $cont;
    $i = "I" . $cont;

    $idp = $linha['idp'];

    $sqlTelefone = "SELECT * FROM pf_telefones WHERE pessoa_fisica_id = '$idp' AND publicado = 1";
    $tel = "";
    $queryTelefone = mysqli_query($con, $sqlTelefone);

    while ($linhaTel = mysqli_fetch_array($queryTelefone)) {
        $tel = $tel . $linhaTel['telefone'] . ' | ';
    }

    $tel = substr($tel, 0, -3);

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue($a, $linha['nome'])
        ->setCellValue($b, $linha['programa'])
        ->setCellValue($c, $linha['funcao'])
        ->setCellValue($d, $linha['linguagem'])
        ->setCellValue($e, $linha['email'])
        ->setCellValue($f, $tel)
        ->setCellValue($g, $linha['status']);

    $cont++;
}


// Renomeia a guia
$objPHPExcel->getActiveSheet()->setTitle('Inscritos');

for ($col = 'A'; $col !== 'G'; $col++) {
    $objPHPExcel->getActiveSheet()
        ->getColumnDimension($col)
        ->setAutoSize(true);
}

//Consertando a coluna referente ao status
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(false);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(50);

$objPHPExcel->setActiveSheetIndex(0);
ob_end_clean();
ob_start();

$nome_arquivo = "pedido_formacao_" . date("Y") . ".xls";

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

