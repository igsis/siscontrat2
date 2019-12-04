<?php
setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');

include '../funcoes/funcoesConecta.php';
$con = bancoMysqli();
// Incluimos a classe PHPExcel
require_once("../include/phpexcel/Classes/PHPExcel.php");
require_once("../funcoes/funcoesConecta.php");
require_once("../funcoes/funcoesGerais.php");

$sql = $_POST['sqlConsulta'];
if (!$query = mysqli_query($con, $sql)) {
    echo $sql;
}

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

// Criamos as colunas
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue("A1", "Nome do Evento")
    ->setCellValue("B1", "Categoria(s)")
    ->setCellValue("C1", "Data de Inicio")
    ->setCellValue("D1", "Horario de Inicio")
    ->setCellValue("E1", "Valor")
    ->setCellValue("F1", "Descricao")
    ->setCellValue("G1", "Local");

// Definimos o estilo da fonte
$objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

//Colorir a primeira linha
$objPHPExcel->getActiveSheet()->getStyle('A1:G1')->applyFromArray
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

$cont = 2;
while($result = mysqli_fetch_array($query))
{
    $categorias = '';

    $SqlAcao_atracao = "SELECT acao_id FROM acao_atracao WHERE atracao_id = '" . $result['atracao_id'] . "'";
    $queryAcaoAtracao = mysqli_query($con, $SqlAcao_atracao);

    while ($acao_atracao = mysqli_fetch_array($queryAcaoAtracao)) {
        $sqlAcao = "SELECT acao FROM acoes WHERE id = '" . $acao_atracao['acao_id'] . "'";
        $queryAction = mysqli_query($con, $sqlAcao);
        while ($acoes = mysqli_fetch_array($queryAction)) {
            $categorias .= $acoes['acao'] . "; ";
        }
    }

    $a = "A".$cont;
    $b = "B".$cont;
    $c = "C".$cont;
    $d = "D".$cont;
    $e = "E".$cont;
    $f = "F".$cont;
    $g = "G".$cont;

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue($a, $result['nomeEvento'])
        ->setCellValue($b, $categorias)
        ->setCellValue($c, $result['data_inicio'])
        ->setCellValue($d, $result['horario_inicio'])
        ->setCellValue($e, $result['valor'])
        ->setCellValue($f, $result['descricao'])
        ->setCellValue($g, $result['sala']);

    $objPHPExcel->getActiveSheet()->getStyle($a . ":" . $g)->getAlignment()->setWrapText(true);
    $objPHPExcel->getActiveSheet()->getStyle($a . ":" . $g)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle($a . ":H" . $cont)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $cont++;

}
// Renomeia a guia
$objPHPExcel->getActiveSheet()->setTitle('Inscritos');

for ($col = 'A'; $col !== 'H'; $col++){
    $objPHPExcel->getActiveSheet()
        ->getColumnDimension($col)
        ->setAutoSize(true);
}

$objPHPExcel->setActiveSheetIndex(0);
ob_end_clean();
ob_start();

$nome_arquivo = date("YmdHis", strtotime("-3 hours")) . "_eventos.csv";


// Cabeçalho do arquivo para ele baixar(Excel2007)
header('Content-Type: text/html; charset=ISO-8859-1');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$nome_arquivo.'"');
header('Cache-Control: max-age=0');
// Se for o IE9, isso talvez seja necessário
header('Cache-Control: max-age=1');

// Acessamos o 'Writer' para poder salvar o arquivo
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

// Salva diretamente no output, poderíamos mudar arqui para um nome de arquivo em um diretório ,caso não quisessemos jogar na tela
$objWriter->save('php://output');

exit;
