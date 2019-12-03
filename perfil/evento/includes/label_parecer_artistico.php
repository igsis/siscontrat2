<?php

$con = bancoMysqli();

$idPedido = $_POST['idPedido'];
$pedido = recuperaDados("pedidos","id",$idPedido);


if(isset($_POST['cadastra_parecer']) || isset($_POST['edita_parecer'])){
    $topico1 = addslashes($_POST['topico1']);
    $topico2 = addslashes($_POST['topico2']);
    $topico3 = addslashes($_POST['topico3']);
    $topico4 = addslashes($_POST['topico4']);
}

if(isset($_POST['cadastra_parecer'])){
    $sql_cadastra = "INSERT INTO parecer_artisticos (pedido_id, topico1, topico2, topico3, topico4) VALUES ('$idPedido','$topico1','$topico2','$topico3','$topico4')";
    if(mysqli_query($con,$sql_cadastra)){
        $mensagem = mensagem("success","Cadastrado com sucesso!");
    }
    else{
        $mensagem = mensagem("danger","Erro ao gravar! Tente novamente.");
    }
}

if(isset($_POST['edita_parecer'])){
    $sql_edita = "UPDATE parecer_artisticos SET topico1='$topico1', topico2='$topico2', topico3='$topico3', topico4='$topico4' WHERE pedido_id = '$idPedido'";
    if(mysqli_query($con,$sql_edita)){
        $mensagem = mensagem("success","Atualizado com sucesso!");
    }
    else{
        $mensagem = mensagem("danger","Erro ao atualizar! Tente novamente.");
    }
}


$parecer = recuperaDados("parecer_artisticos","pedido_id",$idPedido);

if ($pedido['pessoa_tipo_id'] == 2)
{
    $pj = recuperaDados("pessoa_juridicas","id",$pedido['pessoa_juridica_id']);
    $t1 = "Esta comissão ratifica o pedido de contratação de XXXXXXX LÍDERES XXXXXXXXX por intermédio da ".$pj['razao_social'].", para apresentação artística no evento “".retornaObjeto($idPedido)."”, que ocorrerá ".retornaPeriodo($_SESSION['idEvento'])." no valor de R$ ".$pedido['valor_total']." (".valorPorExtenso($pedido['valor_total']).").";
}
else
{
    $pf = recuperaDados("pessoa_fisicas","id",$pedido['pessoa_fisica_id']);
    $t1 = "Esta comissão ratifica o pedido de contratação de ".$pf['nome'].", para apresentação artística no evento “".retornaObjeto($idPedido)."”, que ocorrerá ".retornaPeriodo($_SESSION['idEvento'])." no valor de R$ ".dinheiroParaBr($pedido['valor_total'])." (".valorPorExtenso($pedido['valor_total'])." ).";
}
?>
<script>
    function mostrarResultado(box,num_max,campospan){
        var contagem_carac = box.length;
        if (contagem_carac != 0){
            document.getElementById(campospan).innerHTML = contagem_carac + " caracteres digitados";
            if (contagem_carac == 1){
                document.getElementById(campospan).innerHTML = contagem_carac + " caracter digitado";
            }
            if (contagem_carac < num_max){
                document.getElementById(campospan).innerHTML = "<font color='red'>Você não inseriu a quantidade mínima de caracteres!</font>";
            }
        }else{
            document.getElementById(campospan).innerHTML = "Ainda não temos nada digitado...";
        }
    }
    function contarCaracteres(box,valor,campospan){
        var conta = valor - box.length;
        document.getElementById(campospan).innerHTML = "Faltam " + conta + " caracteres";
        if(box.length >= valor){
            document.getElementById(campospan).innerHTML = "Quantidade mínima de caracteres atingida!";
        }
    }
    function mostrarResultado3(box,num_max,campospan){
        var contagem_carac = box.length;
        if (contagem_carac != 0){
            document.getElementById(campospan).innerHTML = contagem_carac + " caracteres digitados";
            if (contagem_carac == 1){
                document.getElementById(campospan).innerHTML = contagem_carac + " caracter digitado";
            }
            if (contagem_carac < num_max){
                document.getElementById(campospan).innerHTML = "<font color='red'>Você não inseriu a quantidade mínima de caracteres!</font>";
            }
        }else{
            document.getElementById(campospan).innerHTML = "Ainda não temos nada digitado...";
        }
    }
    function contarCaracteres3(box,valor,campospan){
        var conta = valor - box.length;
        document.getElementById(campospan).innerHTML = "Faltam " + conta + " caracteres";
        if(box.length >= valor){
            document.getElementById(campospan).innerHTML = "Quantidade mínima de caracteres atingida!";
        }
    }
</script>

<form method="POST" action="?perfil=evento&p=pedido_edita" role="form">
    <div class="form-group">
        <h4><strong>1º Tópico</strong></h4>
        <label for="topico1">Neste tópico deve conter o posicionamento da comissão e as informações gerais do evento (nome do artista, evento, datas, valor, tempo, etc).</label><br/>
        <span style="color: gray; "><i><b>Texto de exemplo:</b></i><br/>
            <i>Esta comissão ratifica o pedido de contratação de Nome do artista ou grupo (nome artístico) por intermédio da Nome da empresa representante, para apresentação artística no evento “Nome do evento ou atividade especial”, que ocorrerá no dia datas ou período quando for temporada no valor de R$ XXX (valor por extenso).</i></span>
        <?php
        if($parecer['topico1'] == NULL){
        ?>
            <textarea name="topico1" id="topico1" class="form-control" rows="3"><?php echo $t1; ?></textarea>
        <?php
        }
        else{
        ?>
            <textarea name="topico1" id="topico1" class="form-control" rows="3"><?= $parecer["topico1"]; ?></textarea>
        <?php
        }
        ?>
    </div>
    <br/>
    <div class="form-group">
        <h4><strong>2º Tópico (mínimo de 500 caracteres)</strong></h4>
        <label for="topico2">Neste tópico deve-se falar sobre o evento ou atividade especial da qual o artista/grupo irá participar.<br/>Se for programação geral do equipamento, sem estar vinculada a nenhum evento ou projeto específico, falar sobre o equipamento, histórico, tipo de atividades desenvolvidas, etc, demonstrando a importância desse tipo de programação dentro do equipamento.</label><br/>
        <span style="color: gray; "><i><b>Texto de exemplo:</b></i><br/>
            <i>Em sua nona edição, o projeto Virada Cultural, da Secretaria Municipal de Cultura, consolida a Cidade de São Paulo como o principal pólo gerador de arte e cultura do País proporcionando, não só aos munícipes como também aos visitantes de outros Estados e de outras nacionalidades, o acesso gratuito ao que há de melhor na produção cultural atual existente no Brasil e no exterior. A Virada Cultural da Cidade de São Paulo, através de apresentações artísticas em logradouros públicos e equipamentos oficiais dentre outros espaços culturais conquistou, nesses nove anos de existência, o reconhecimento da mídia e do público, solidificando-se como um dos eventos nacionais mais conhecidos e divulgados do Brasil, assim como no exterior.</i></span>
        <textarea id="topico2" name="topico2" class="form-control" rows="5" onkeyup="mostrarResultado(this.value,500,'spcontando');contarCaracteres(this.value,500,'sprestante')"><?php echo $parecer["topico2"]; ?></textarea>
        <span id="spcontando" style="font-family:Georgia;">Comece a digitar para ativar a contagem de caracteres.</span><br />
        <span id="sprestante" style="font-family:Georgia;"></span>
    </div>
    <br/>
    <div class="form-group">
        <h4><strong>3º Tópico (mínimo de 700 caracteres)</strong></h4>
        <label>Neste tópico deve-se falar sobre o currículo/biografia do artista ou grupo (na 3ª pessoa), escrever um breve release. Deve ficar claro que o artista contribuirá positivamente para a programação e porque essa é a melhor escolha de artista para o evento.</label>
        <textarea id="topico3" name="topico3" class="form-control" rows="5" onkeyup="mostrarResultado3(this.value,700,'spcontando3');contarCaracteres3(this.value,700,'sprestante3')"><?php echo $parecer["topico3"]; ?></textarea>
        <span id="spcontando3" style="font-family:Georgia;">Comece a digitar para ativar a contagem de caracteres.</span><br />
        <span id="sprestante3" style="font-family:Georgia;"></span>
    </div>
    <br/>
    <div class="form-group">
        <h4><strong>4º Tópico</strong></h4>
        <div class="row">
            <div class="box box-default collapsed-box">
                <div class="box-header with-border">
                    <h4 class="box-title">
                        Artista Local
                    </h4>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                        </button>
                    </div>
                    <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <label for="topico4">Neste tópico deve-se falar que o contratado tem o necessário para a contratação e que as exigências legais foram observadas, apresentando a comprovação documental (mínimo três comprovações diferentes) do valor proposto para o cachê. Encerrar com a manifestação favorável da comissão quanto à contratação.</label>
                    <span style="color: gray; "><i><b>Texto de exemplo:</b></i><br/>
                    <i>Os artistas reúnem as condições necessárias para integrar a programação Secretaria Municipal de Cultura, possuem consagração, reconhecimento e aceitação do público, conforme documentos juntados ao presente, SEI ( link do clipping, curriculo e release ). Ainda, avaliamos que o cachê proposto encontra-se compatível com os valores praticados no mercado e pagos por esta Secretaria, conforme pode ser comprovado pelos processos/notas fiscais  ( link de 3 números de processos SEI que constem notas fiscais ), em cumprimento ao Acórdão TC 2.393/15-37.<br/>Sendo os serviços indubitavelmente de natureza artística, manifestamo-nos favoravelmente à contratação, endossando a proposta inicial.</i></span>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
        <!-- /.box -->
        <div class="row">
            <div class="box box-default collapsed-box">
                <div class="box-header with-border">
                    <h4 class="box-title">
                        Artista Consagrado
                    </h4>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                        </button>
                    </div>
                    <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <label for="topico4">Neste tópico deve-se falar que o contratado tem o necessário para a contratação e que as exigências legais foram observadas, apresentando a comprovação documental (mínimo três notas fiscais de eventos que não foram contratados pela prefeitura) do valor proposto para o cachê. Encerrar com a manifestação favorável da comissão quanto à contratação.</label>
                    <span style="color: gray; "><i><b>Texto de exemplo:</b></i><br/>
                    <i>O espetáculo é composto por profissionais consagrados pelo público e pela crítica especializada, estando o cachê proposto de acordo com os valores praticados no mercado, conforme pode ser comprovado pelos documentos x, y e z, em cumprimento ao Acórdão TCM 2.393/15-37.<br/>Sendo os serviços indubitavelmente de natureza artística, manifestamo-nos favoravelmente à contratação, endossando a proposta inicial.</i></span>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
        <!-- /.box -->
        <textarea id="topico4" name="topico4" class="form-control" rows="5"><?=$parecer['topico4']?></textarea>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
        <input type="hidden" name="idPedido" value="<?= $idPedido ?>">
        <button type="submit" name="<?=($parecer == NULL) ? "cadastra_parecer" : "edita_parecer"?>" class="btn btn-info pull-right">Gravar</button>
    </div>
</form>