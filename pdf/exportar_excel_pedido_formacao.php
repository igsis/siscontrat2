<?php

setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');

include '../funcoes/funcoesConecta.php';
$con = bancoMysqli();
// Incluimos a classe PHPExcel
require_once("../include/phpexcel/Classes/PHPExcel.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");
session_start();

$idPedido = $_SESSION['idFCExport'];

$sql = "SELECT    fc.id,
                  fc.pessoa_fisica_id AS 'idp', 
	               pf.nome AS 'nome',
	               p.programa AS 'programa',
                   c.cargo AS 'funcao',
                   l.linguagem AS 'linguagem',
                   pf.email AS 'email',
                   st.status AS 'status'
            FROM formacao_contratacoes AS fc
            INNER JOIN pessoa_fisicas AS pf ON pf.id = fc.pessoa_fisica_id
            INNER JOIN programas AS p ON p.id = fc.programa_id
	        INNER JOIN formacao_cargos AS c ON c.id = fc.form_cargo_id
            INNER JOIN linguagens AS l ON l.id = fc.linguagem_id
            INNER JOIN formacao_status AS st ON st.id = fc.form_status_id 
            WHERE fc.id = '$idPedido' AND fc.publicado = 1";

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

//Eventos Comum
$proxLinha = 1;

$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue("A" . $proxLinha)
    ->setCellValue("B" . $proxLinha)
    ->setCellValue("C" . $proxLinha)
    ->setCellValue("D" . $proxLinha)
    ->setCellValue("E" . $proxLinha, "Pedido de Contratação")
    ->setCellValue("F" . $proxLinha)
    ->setCellValue("G" . $proxLinha)
    ->setCellValue("H" . $proxLinha)
    ->setCellValue("I" . $proxLinha);

$objPHPExcel->getActiveSheet()->getStyle('A' . $proxLinha . ':I' . $proxLinha)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A' . $proxLinha . ':I' . $proxLinha)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getRowDimension($proxLinha)->setRowHeight(40);
$objPHPExcel->getActiveSheet()->getStyle('A' . $proxLinha . ':I' . $proxLinha)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);


$proxCabecalho = $proxLinha + 1;

// Criamos as colunas
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A' . $proxCabecalho, 'Nome Completo')
    ->setCellValue("B" . $proxCabecalho, "Programa")
    ->setCellValue("C" . $proxCabecalho, "Função")
    ->setCellValue("D" . $proxCabecalho, "Linguagem")
    ->setCellValue("E" . $proxCabecalho, "E-mail")
    ->setCellValue("F" . $proxCabecalho, "Telefone 1")
    ->setCellValue("G" . $proxCabecalho, "Telefone 2")
    ->setCellValue("H" . $proxCabecalho, "Telefone 3")
    ->setCellValue("I" . $proxCabecalho, "Estado do Pedido");

// Definimos o estilo da fonte
$objPHPExcel->getActiveSheet()->getStyle('A' . $proxCabecalho . ':I' . $proxCabecalho)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A' . $proxCabecalho . ':I' . $proxCabecalho)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getRowDimension($proxCabecalho)->setRowHeight(40);
$objPHPExcel->getActiveSheet()->getStyle('A' . $proxCabecalho . ':I' . $proxCabecalho)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

//Colorir a primeira linha
$objPHPExcel->getActiveSheet()->getStyle('A' . $proxLinha . ':I' . $proxLinha)->applyFromArray
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
$objPHPExcel->getActiveSheet()->getStyle('A' . $proxCabecalho . ':I' . $proxCabecalho)->applyFromArray
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
$count = 0;
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

    $idp = $linha['idp'];
    $sqltel = "SELECT telefone FROM pf_telefones WHERE pessoa_fisica_id = '$idp' AND publicado = 1";
    $tel = $con->query($sqltel)->fetch_all();

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue($a, $linha['nome'])
        ->setCellValue($b, $linha['programa'])
        ->setCellValue($c, $linha['funcao'])
        ->setCellValue($d, $linha['linguagem'])
        ->setCellValue($e, $linha['email'])
        ->setCellValue($f, $tel[0] [0])
        ->setCellValue($g, $tel[1] [0])
        ->setCellValue($h, $tel[2] [0])
        ->setCellValue($i, $linha['status']);

    $objPHPExcel->getActiveSheet()->getStyle($a . ":" . $i)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($a . ":" . $i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle($a . ":" . $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->getActiveSheet()->getRowDimension($cont)->setRowHeight(20);

    $count++;

}


// Renomeia a guia
$objPHPExcel->getActiveSheet()->setTitle('Inscritos');

for ($col = 'A'; $col !== 'I'; $col++) {
    $objPHPExcel->getActiveSheet()
        ->getColumnDimension($col)
        ->setAutoSize(true);
}

$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);

$objPHPExcel->setActiveSheetIndex(0);
ob_end_clean();
ob_start();

$nome_arquivo = date("YmdHis") . "_pedidos_formacao.xls";


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

