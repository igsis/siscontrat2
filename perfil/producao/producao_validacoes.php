<?php
$con = bancoMysqli();

$pedidos = recuperaDados('pedidos', 'origem_id', $idEvento);
echo "VAI DA O CU" . $pedidos['pessoa_tipo_id'];

$sqlAtracoes = "SELECT * FROM atracoes WHERE evento_id = '$idEvento' AND publicado = 1";
$atracoes = mysqli_query($con, $sqlAtracoes);

if($pedidos != null){
    while ($atracao = mysqli_fetch_array($atracoes)){

        $tipoPessoa = $pedidos['pessoa_tipo_id'];

        if($tipoPessoa == 1){
            $idPessoa = $pedidos['pessoa_fisica_id'];
            $pf = recuperaDados("pessoa_fisicas", "id", $idPessoa);
            $sqlArqs = "SELECT FROM arquivos WHERE lista_documento_id = 2 or lista_documento_id = 3";
            $queryArqs = mysqli_query($con, $sqlArqs);
            if(mysqli_num_rows($queryArqs) < 2 AND mysqli_num_rows($queryArqs) != 0){
                $arqs = mysqli_fetch_array($queryArqs);
                $idDoc = $arqs['lista_documento_id'];
                if($idDoc == 2){
                    array_push($erroArqs, "Cópia do CPF não anexada na pessoa física. <b>" . $pf['nome'] . "</b>");
                }elseif ($idDoc == 3){
                    array_push($erroArqs, "Cópia do RG não anexada na pessoa física. <b>" . $pf['nome']. "</b>");
                }
            }elseif (mysqli_num_rows($queryArqs) == 0){
                array_push($erroArqs, "Cópia do CPF e RG não anexadas na pessoa física");
            }
        }else{
            $idPessoa = $pedidos['pessoa_juridica_id'];
            $pj = recuperaDados("pessoa_juridicac", "id", $idPessoa);
            $sqlArqs = "SELECT * FROM arquivos WHERE lista_documento_id = 22";
            $queryArqs = mysqli_query($con,$sqlArqs);
            if(mysqli_num_rows($queryArqs) == 0){
                array_push($erroArqs, "Cópia do CNPJ não anexada na pessoa juridica <b>" . $pf['razao_social'] . "</b>");
            }
        }
    }
}else{
    array_push($erroArqs, "Sem pedido você não poderá enviar seu evento!");
}

$erros = [];
$numAtracoes = $atracoes->num_rows;

if ($evento['tipo_evento_id'] == 1) {
    array_push($erros, "Não possui atrações cadastradas");
} else {
    foreach ($atracoes as $atracao) {
        if (($atracao['produtor_id'] == "") || ($atracao['produtor_id'] == null)) {
            $idAtracao = $atracao['id'];
            $acoes = recuperaDados('acao_atracao', 'atracao_id', $idAtracao);
            $idAcao = $acoes['acao_id'];
            $possui = true;
            switch ($idAcao) {
                case  11 : // teatro
                    $tabela = 'teatro';
                    break;
                case 7 : // música
                    $tabela = 'musica';
                    break;
                case 5 : // exposição (feira)
                    $tabela = 'exposicoes';
                    break;
                case 8 : // oficina
                    $tabela = 'oficinas';
                    break;
                default:
                    $possui = false;
            }

            if ($possui) {
                $numEspecialidades = $con->query("SELECT * FROM $tabela WHERE atracao_id = '$idAtracao'")->num_rows;
            }
            if ($numAtracoes == 0) {
                array_push($erros, "Não há especificidade cadastrada para a atração <b>" . $atracao['nome_atracao'] . "</b>");
            }
        }

        $ocorrencias = $con->query("SELECT * FROM ocorrencias WHERE origem_ocorrencia_id = '$idEvento' AND publicado = 1");
        $ocorrenciasAssocs = $ocorrencias->fetch_assoc();
        $numOcorrencias = $ocorrencias->num_rows;
        if ($numOcorrencias == 0) {
            array_push($erros, "Não há ocorrência cadastrada para a atração <b>" . $atracao['nome_atracao'] . "</b>");
        } else {
            $hoje = new DataTime(date("Y-m-d"));
            $dataInicio = new DateTime($ocorrencias['data_inicio']);
            $diff = $hoje->diff($dataInicio);

            if ($diff->d < 30) {
                $mensagem = "Hoje é dia" . $hoje->format('d/m/Y') . ". O seu evento se inicia em " . $dataInicio->format('d,m,Y') . ". <br>
                O prazo para contratos é de 30 dias.<br>";
                $prazo = "Você está <b  class='text-red'>fora</b>do prazo de contrato";
            } else {
                $mensagem = "Hoje é dia" . $hoje->format('d/m/Y' . ". O seu evento se inicia em " . $dataInicio->format('d/m/Y') . ". <br>
                O prazo para contratos é de 30 dias.<br>");
                $prazo = "Você está <b class='text-green'>dentro</b> do prazo de contrato";
            }
        }
    }
}
if($evento['contratacao'] == 1){
    $pedidos = $con->query("SELECT * FROM pedidos WHERE origem_tipo_id = '1' AND origem_id = '$idEvento' AND publicado = '1' ");
    $numPedidos = $pedidos->num_rows;
    $pedido = $pedidos->fetch_assoc();
    if($numPedidos == 0){
        array_push($erros, "Não há pedido inserido nesse evento");
    }else{
        if($pedido['pessoa_tipo_id'] == 2){
            $pj = recuperaDados('pessoa_juridicas', 'id', $pedido['pessoa_juridica_id']);
            if(($pj['representante_legal1_id'] == null) && ($pj['representante_legal2_id'] == null)){
                array_push($erros, "Não há Representante legal cadastrado no proponente <b>" . $pj['razao_social'] . "</b>");
            }
        }
    }
}


?>
