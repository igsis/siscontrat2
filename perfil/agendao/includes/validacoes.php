<?php
$con = bancoMysqli();

$idEvento = $_SESSION['idEvento'];

$evento = recuperaDados('agendoes', 'id', $idEvento);

/**
 * <p>Recebe um array multidimensional para ser utilizado na função in_array()</p>
 *
 * @param string|int $needle <p>
 * Valor a ser procurado </p>
 * @param array $haystack <p>
 * Array multidimensional onde deve procurar
 * @return array <p>
 * Retorna um array contendo os indices: <br>
 * 'bool' - false ou true <br>
 * 'especificidade' - indice do array multidimencional onde foi encontrado o valor </p>
 */
/*
function in_array_key($needle, $haystack) {
    $return = [
        'bool' => false,
        'especificidade' => null
    ];

    foreach ($haystack as $key => $array) {
        if (in_array($needle, $array)) {
            $return = [
                'bool' => true,
                'especificidade' => $key
            ];
            return $return;
        }
    }
    return $return;
}
*/
$erros = [];

if (($evento['produtor_id'] == "") || ($evento['produtor_id'] == null)) {
    array_push($erros,"Produtor não cadastrado!");
}

$ocorrencias = $con->query("SELECT * FROM ocorrencias WHERE origem_ocorrencia_id = '$idEvento' AND tipo_ocorrencia_id = 3 AND publicado = '1'");
$numOcorrencias = $ocorrencias->num_rows;
if ($numOcorrencias == 0) {
    array_push($erros, "Não há ocorrência cadastrada!");
}