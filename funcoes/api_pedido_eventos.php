<?php
require_once 'funcoesConecta.php';
require_once 'funcoesGerais.php';
$con = bancoMysqli();

if (isset($_POST['_method'])) {
    session_start();
    switch ($_POST['_method']) {
        case "valorPorEquipamento":
            $valoresEquipamentos = $_POST['valorEquipamento'];
            $equipamentos = $_POST['equipamentos'];
            $idPedido = $_POST['idPedido'];

            $sql_delete = "DELETE FROM valor_equipamentos WHERE pedido_id = '$idPedido'";
            mysqli_query($con, $sql_delete);

            for ($i = 0; $i < count($valoresEquipamentos); $i++) {
                $valor = dinheiroDeBr($valoresEquipamentos[$i]);
                $idLocal = $equipamentos[$i];

                $sql_insert_valor = "INSERT INTO valor_equipamentos (local_id, pedido_id, valor) 
                             VALUES ('$idLocal', '$idPedido', '$valor')";

                if (mysqli_query($con, $sql_insert_valor)) {
                    $erro[] = false;
                } else {
                    $erro[] = true;
                }
            }
            echo in_array(true, $erro, true) ? false : true;
            break;

        case "parecerArtistico":
            $idPedido = $_POST['idPedido'];

            $topico1 = trim(addslashes($_POST['topico1']));
            $topico2 = trim(addslashes($_POST['topico2']));
            $topico3 = trim(addslashes($_POST['topico3']));
            $topico4 = trim(addslashes($_POST['topico4']));

            $sql_cadastra = "INSERT INTO parecer_artisticos (pedido_id, topico1, topico2, topico3, topico4) VALUES ('$idPedido','$topico1','$topico2','$topico3','$topico4')
                                 ON DUPLICATE KEY UPDATE topico1 = '$topico1', topico2 = '$topico2', topico3 = '$topico3', topico4 = '$topico4'";
            if (mysqli_query($con, $sql_cadastra)) {
                echo true;
            } else {
                echo false;
            }
            break;
        case "parcelas":

            $idVerba = $_POST["verba_id"];
            $valor_total = dinheiroDeBr($_POST["valor_total"]);
            $num_parcelas = $_POST["numero_parcelas"];
            $forma_pagamento = trim(addslashes($_POST["forma_pagamento"]));
            $justificativa = trim(addslashes($_POST["justificativa"]));
            $observacao = trim(addslashes($_POST["observacao"]));
            $idPedido = $_POST["idPedido"];
            $tipoPesso = $_POST["tipoPessoa"];
            $idProponent = $_POST["idProponente"];
            $data_kit_pagamento = $_POST["data_kit"];

            if ($num_parcelas == 1 || $num_parcelas == 13) {
                $data_kit_pagamento = date('Y-m-d', strtotime("+1 days", strtotime($data_kit_pagamento)));
            }else{
                $queryParcela = "SELECT data_pagamento FROM parcelas WHERE pedido_id = ".$idPedido." AND numero_parcelas = 1";
                $data_kit_pagamento = mysqli_fetch_row(mysqli_query($con,$queryParcela))[0];
            }
             $query = "UPDATE pedidos SET verba_id = '$idVerba', numero_parcelas = '$num_parcelas', valor_total = '$valor_total', forma_pagamento = '$forma_pagamento', data_kit_pagamento = '$data_kit_pagamento', justificativa = '$justificativa', observacao = '$observacao' WHERE id = '$idPedido'";
            if (mysqli_query($con,$query)){
                echo true;
            }else{
                echo false;
            }

            break;

        case "enviosArquivos":
            $idPedido = $_POST['idPedido'];
            $tipoPessoa = 3;
            $sql_arquivos = "SELECT * FROM lista_documentos WHERE tipo_documento_id = '$tipoPessoa' and publicado = 1";
            $query_arquivos = mysqli_query($con, $sql_arquivos);
            while ($arq = mysqli_fetch_array($query_arquivos)) {
                $y = $arq['id'];
                $x = $arq['sigla'];
                $nome_arquivo = isset($_FILES['arquivo']['name'][$x]) ? $_FILES['arquivo']['name'][$x] : null;
                $f_size = isset($_FILES['arquivo']['size'][$x]) ? $_FILES['arquivo']['size'][$x] : null;

                if ($f_size > 5242880) {
                    $mensagem = mensagem("danger", "<strong>Erro! Tamanho de arquivo excedido! Tamanho máximo permitido: 05 MB.</strong>");
                } else {
                    if ($nome_arquivo != "") {
                        $nome_temporario = $_FILES['arquivo']['tmp_name'][$x];
                        $new_name = date("YmdHis",strtotime("-3 hours")) . "_" . semAcento($nome_arquivo); //Definindo um novo nome para o arquivo
                        $hoje = date("Y-m-d H:i:s",strtotime("-3 hours"));
                        $dir = '../uploadsdocs/'; //Diretório para uploads
                        $allowedExts = array(".pdf", ".PDF"); //Extensões permitidas
                        $ext = strtolower(substr($nome_arquivo,-4));

                        if(in_array($ext, $allowedExts)) //Pergunta se a extensão do arquivo, está presente no array das extensões permitidas
                        {
                            if (move_uploaded_file($nome_temporario, $dir . $new_name)) {
                                $sql_insere_arquivo = "INSERT INTO `arquivos` (`origem_id`, `lista_documento_id`, `arquivo`, `data`, `publicado`) VALUES ('$idPedido', '$y', '$new_name', '$hoje', '1'); ";
                                $query = mysqli_query($con, $sql_insere_arquivo);

                                if ($query) {
                                    $mensagem = mensagem("success", "Arquivo recebido com sucesso");
                                    echo "<script>
                                swal('Clique nos arquivos após efetuar o upload e confira a exibição do documento!', '', 'warning');                             
                            </script>";
                                    gravarLog($sql_insere_arquivo);
                                } else {
                                    $mensagem = mensagem("danger", "Erro ao gravar no banco");
                                }
                            } else {
                                $mensagem = mensagem("danger", "Erro no upload");
                            }
                        }else {
                            echo "<script>
                            swal('Erro no upload!', 'Anexar documentos somente no formato PDF.', 'error');                             
                        </script>";
                        }
                    }
                }
            }
            break;

        case "apagaArquivo":
            $idArquivo = $_POST['idArquivo'];
            $sql_apagar_arquivo = "UPDATE arquivos SET publicado = 0 WHERE id = '$idArquivo'";
            if(mysqli_query($con,$sql_apagar_arquivo))
            {
                $arq = recuperaDados("arquivos",$idArquivo,"id");
                $mensagem = mensagem("success", "Arquivo ".$arq['arquivo']."apagado com sucesso!");
                gravarLog($sql_apagar_arquivo);
                echo true;
            }
            else
            {
                echo false;
            }
            break;

        default:
            echo false;
            break;
    }
} else {
    echo false;
}