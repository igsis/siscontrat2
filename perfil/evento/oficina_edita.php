<?php
$con = bancoMysqli();

if (isset($_POST['cadastra']) || isset($_POST['edita'])){
    $idAtracao = $_POST['idAtracao'];
    $certificado =  ($_POST['certificado']);
    $vagas = $_POST['vagas'];
    $venda =  ($_POST['venda']);
    $publico_alvo = addslashes ($_POST['publico_alvo']);
    $material = $_POST['material'];
    $inscricao = $_POST['inscricao'];
    $carga_horaria = $_POST['carga_horaria'];
    $valor_hora = $_POST['valor_hora'];
    $inicio = $_POST ['inicio_inscricao'];
    $encerramento = $_POST ['encerramento_inscricao'];
    $divulgacao = $_POST['data_divulgacao'];
    $descricao = $_POST ['descricao'];
}

if (isset($_POST['cadastra'])) {

    $sql = "INSERT INTO oficinas (atracao_id, 
                                  certificado,
                                  vagas,
                                  venda,
                                  publico_alvo,
                                  material,
                                  inscricao,
                                  carga_horaria,
                                  valor_hora,
                                  data_divulgacao) 
                          VALUES ('$idAtracao',
                                  '$certificado',
                                  '$vagas',
                                  '$venda',
                                  '$publico_alvo',
                                  '$material',
                                  '$inscricao',
                                  '$carga_horaria',
                                  '$valor_hora',
                                  '$divulgacao')";

    if(mysqli_query($con, $sql)) {

        $idOficina = recuperaUltimo("oficinas");

        $mensagem = mensagem("success","Cadastrado com sucesso!");
        //gravarLog($sql);
    }else{
        $mensagem = mensagem("danger","Erro ao gravar! Tente novamente.");
        //gravarLog($sql);
    }
}

if(isset($_POST['edita'])){
    $sql = "UPDATE oficinas SET
                            certificado = '$certificado',
                            vagas = '$vagas',
                            venda = '$venda',
                            publico_alvo = '$publico_alvo',
                            material = '$material',
                            inscricao = '$inscricao',
                            carga_horaria = '$carga_horaria',
                            valor_hora = '$valor_hora',
                            data_divulgacao = '$divulgacao'
                            WHERE id = '$idAtracao'";
    if(mysqli_query($con,$sql)){
        $mensagem = mensagem("success","Gravado com sucesso!");
        //gravarLog($sql);
    }else{
        $mensagem = mensagem("danger","Erro ao gravar! Tente novamente.");
        //gravarLog($sql);
    }
}
if(isset($_POST['carregar'])){
    $idOficina = $_POST['idOficina'];
}

$oficina = recuperaDados("oficinas","id",$idOficina);


include "includes/menu_interno.php";
?>
<script language="JavaScript" >
    function barraData(n){
        if(n.value.length==2)
            c.value += '/';
        if(n.value.length==5)
            c.value += '/';
    }
</script>

<div class="content-wrapper">
    <section class="content">

        <h2 class="page-header">Cadastro de Evento</h2>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Atração - Especificidades de Área</h3>
                    </div>
                    <form method="POST" action="?perfil=evento&p=oficina_edita" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="certificado">Certficado?</label><br/>
                                    <label><input type="radio" name="certificado" value="1" checked> Sim </label>
                                    <label><input type="radio" name="certificado" value="0"> Não </label>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="Vagas">Vagas</label> <br>
                                    <input type="number" name="vagas">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="venda">Venda de material?</label> <br>
                                    <label><input type="radio" name="venda" value="1" checked> Sim </label>
                                    <label><input type="radio" name="venda" value="0"> Não </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="publico_alvo">Público-alvo *</label>
                                    <textarea name="publico_alvo" id="publico_alvo" class="form-control" rows="5"><?= $oficina['publico_alvo']?></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="material">Material Requisitado: </label>
                                    <textarea name="material" id="material" class="form-control" rows="2"><?= $oficina['material']?></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="forma_inscricao">Forma de inscrição: </label><br>
                                    <select id="forma_inscricao" name="forma_inscricao">
                                       <?php  ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="carga_horaria">Carga Horária (em horas): </label><br>
                                    <input type="number" name="carga_horaria" value="<?= $oficina['carga_horaria']?>">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="valor_hora">Valor hora/aula: </label><br>
                                    <input type="number" name="valor_hora" value="<?= $oficina['valor_hora']?>">
                                </div>
                            </div>
                            <div class="row">
                            <div class="form-group col-md-4">
                                <label for="inicio_inscricao">Início de inscrição: </label> <br/>
                                <input type="date" name="inicio_inscricao" onkeyup="barraData(this);">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="encerramento_inscricao">Encerramento de inscrição: </label>
                                <input type="date" name="encerramento_inscricao" onkeyup="barraData(this);">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="divulgacao">Divulgação de inscrição: </label> <br/>
                                <input type="date" name="divulgacao" onkeyup="barraData(this);" value="<?= $oficina['data_divulgacao'] ?>">
                            </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="descricao">Descrição</label><br/>
                                    <i>Esse campo deve conter uma breve descrição do que será apresentado no evento.</i>
                                    <p align="justify"><span style="color: gray; "><strong><i>Texto de exemplo:</strong>Ana Cañas faz o show de lançamento do seu quarto disco, “Tô na Vida” (Som Livre/Guela Records). Produzido por Lúcio Maia (Nação Zumbi) em parceria com Ana e mixado por Mario Caldato Jr, é o primeiro disco totalmente autoral da carreira da cantora e traz parcerias com Arnaldo Antunes e Dadi entre outros.</span></i></p>
                                    <textarea name="descricao" id="descricao" class="form-control" rows="5"></textarea>
                                </div>
                            </div>
                            <div class="box-footer">
                                <button type="submit" class="btn btn-default">Cancelar</button>
                                <button type="submit" name="edita" class="btn btn-info pull-right">Salvar</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
