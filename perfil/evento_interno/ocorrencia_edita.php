<?php
$con = bancoMysqli();

$evento = recuperaDados('eventos', 'id', $_SESSION['idEvento']);
$url = 'http://'.$_SERVER['HTTP_HOST'].'/siscontrat2/funcoes/api_locais_espacos.php';

include "includes/menu_interno.php";

if (isset($_POST['cadastra']) || isset($_POST['edita'])) {

    $tipo_evento_id = $evento['tipo_evento_id'];
    $origem_ocorrencia_id = $_POST['idOrigem'] ?? NULL;
    $instituicao_id = $_POST['instituicao'];
    $local_id = $_POST['local'];
    $espaco_id = $_POST['espaco'] ?? NULL;
    $data_inicio = $_POST['data_inicio'];
    $data_fim   = $_POST['data_fim'] ?? NULL;
    $segunda    = $_POST['segunda'] ?? 0;
    $terca      = $_POST['terca']   ?? 0;
    $quarta     = $_POST['quarta']  ?? 0;
    $quinta     = $_POST['quinta']  ?? 0;
    $sexta      = $_POST['sexta']   ?? 0;
    $sabado     = $_POST['sabado']  ?? 0;
    $domingo    = $_POST['domingo'] ?? 0;
    $horario_inicio = $_POST['horaInicio'];
    $horario_fim = $_POST['horaFim'];
    $retirada_ingresso_id = $_POST['retiradaIngresso'];
    $valor_ingresso = dinheiroDeBr($_POST['valor_ingresso']);
    $observacao = addslashes($_POST['observacao']) ?? NULL;
    $idOcorrencia =  $_POST['idOcorrencia'] ?? NULL;

}

if (isset($_POST['cadastra'])) {

    $sql = "INSERT INTO ocorrencias (tipo_ocorrencia_id,
                                 origem_ocorrencia_id,
                                 instituicao_id, 
                                 local_id,
                                 espaco_id,
                                 data_inicio, 
                                 data_fim, 
                                 segunda,
                                 terca,
                                 quarta,
                                 quinta,
                                 sexta,
                                 sabado,
                                 domingo,
                                 horario_inicio,
                                 horario_fim,
                                 retirada_ingresso_id,
                                 valor_ingresso,
                                 observacao)
                          VALUES ('$tipo_evento_id',
                                  '$origem_ocorrencia_id',
                                  '$instituicao_id',
                                  '$local_id',
                                  '$espaco_id',
                                  '$data_inicio',
                                  '$data_fim',
                                  '$segunda',
                                  '$terca',
                                  '$quarta',
                                  '$quinta',
                                  '$sexta',
                                  '$sabado',
                                  '$domingo',
                                  '$horario_inicio',
                                  '$horario_fim',
                                  '$retirada_ingresso_id',
                                  '$valor_ingresso',
                                  '$observacao')";

    if (mysqli_query($con, $sql)) 
    {
        $idOcorrencia = recuperaUltimo('ocorrencias');
        $mensagem = mensagem("success", "Cadastrado com sucesso!");
        //gravarLog($sql);
    } else {
        $mensagem = mensagem("danger", "Erro ao gravar! Tente novamente.");
        //gravarLog($sql);
    }

}

if (isset($_POST['edita'])) {

    $sql = "UPDATE ocorrencias SET
                            instituicao_id = '$instituicao_id',
                            local_id = '$local_id',
                            espaco_id = '$espaco_id',
                            data_inicio = '$data_inicio',
                            data_fim = '$data_fim',
                            segunda = '$segunda',
                            terca = '$terca',
                            quarta = '$quarta',
                            quinta = '$quinta',
                            sexta = '$sexta',
                            sabado = '$sabado',
                            domingo = '$domingo',
                            horario_inicio = '$horario_inicio',
                            horario_fim = '$horario_fim',
                            retirada_ingresso_id = '$retirada_ingresso_id',
                            valor_ingresso = '$valor_ingresso',
                            observacao = '$observacao'
                            WHERE id = '$idOcorrencia'";

    If (mysqli_query($con, $sql)) {
        $mensagem = mensagem("success", "Gravado com sucesso!");
        //gravarLog($sql);
    } else {
        $mensagem = mensagem("danger", "Erro ao gravar! Tente novamente.");
        //gravarLog($sql);
    }
}

if (isset($_POST['carregar'])) {
    $idOcorrencia = $_POST['idOcorrencia'];
}

$ocorrencia = recuperaDados('ocorrencias', 'id', $idOcorrencia);

?>

<script type="text/javascript">
    function desmarca() {
        $("#diasemana01").prop("checked", false);
        $("#diasemana02").prop("checked", false);
        $("#diasemana03").prop("checked", false);
        $("#diasemana04").prop("checked", false);
        $("#diasemana05").prop("checked", false);
        $("#diasemana06").prop("checked", false);
        $("#diasemana07").prop("checked", false);
    }

    function mudaData(valor) {
        $("#diasemana01").prop("disabled", valor);
        $("#diasemana02").prop("disabled", valor);
        $("#diasemana03").prop("disabled", valor);
        $("#diasemana04").prop("disabled", valor);
        $("#diasemana05").prop("disabled", valor);
        $("#diasemana06").prop("disabled", valor);
        $("#diasemana07").prop("disabled", valor);

        desmarca();
    }

    // $(document).ready(function () {
    //     validate();
    //     $('#datepicker11').change(validate);
    // });

    function validate() {
        comparaData();
        if ($('#datepicker11').val().length > 0) {
            mudaData(false);
        }
        else {
            mudaData(true);

            var data = document.querySelector('input[name="data_inicio"]').value;
            data = new Date(data);
            dayName = new Array("0", "1", "2", "3", "4", "5", "6", "0");
            let dia = dayName[data.getDay() + 1];

            if (dia == 0) {
                $("#diasemana07").prop("disabled", false);
                $("#diasemana07").prop("checked", true);
            } else if (dia == 1) {
                $("#diasemana01").prop("disabled", false);
                $("#diasemana01").prop("checked", true);
            } else if (dia == 2) {
                $("#diasemana02").prop("disabled", false);
                $("#diasemana02").prop("checked", true);
            } else if (dia == 3) {
                $("#diasemana03").prop("disabled", false);
                $("#diasemana03").prop("checked", true);
            } else if (dia == 4) {
                $("#diasemana04").prop("disabled", false);
                $("#diasemana04").prop("checked", true);
            } else if (dia == 5) {
                $("#diasemana05").prop("disabled", false);
                $("#diasemana05").prop("checked", true);
            } else if (dia == 6) {
                $("#diasemana06").prop("disabled", false);
                $("#diasemana06").prop("checked", true);
            }
        }
    }

    function comparaData(){
        var dataInicio = document.querySelector('#datepicker10').value;
        var dataFim = document.querySelector('#datepicker11').value;

        if((dataInicio != "") && (dataFim != "")){
            var dataInicio = parseInt(dataInicio.split("-")[0].toString() + dataInicio.split("-")[1].toString() + dataInicio.split("-")[2].toString());
            var dataFim = parseInt(dataFim.split("-")[0].toString() + dataFim.split("-")[1].toString() + dataFim.split("-")[2].toString());

            if(dataFim <= dataInicio){
                alert("Data final menor que a data inicial");
                $('#edita').attr("disabled", true);
            }else{
                $('#edita').attr("disabled", false);
            }
        }

        if(dataFim == ""){
            $('#edita').attr("disabled", false);
        }


    }
</script>

<div class="content-wrapper">
    <section class="content">

        <h2 class="page-header">Edição de Ocorrência</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <?php echo $evento['nome_evento'] ?>
                        </h3>
                    </div>
                    <div class="row" align="center">
                        <?php if (isset($mensagem)) {
                            echo $mensagem;
                        }; ?>
                    </div>
                    <form method="POST" action="?perfil=evento_interno&p=ocorrencia_edita" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="data_inicio">Data Início*</label> <br>
                                    <input type="date" name="data_inicio" class="form-control" id="datepicker10"
                                           placeholder="DD/MM/AAAA" required value="<?= $ocorrencia['data_inicio'] ?>" onblur="arrumaData()">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="data_fim">Data Encerramento (apenas se for temporada)</label> <br>
                                    <input type="date" name="data_fim" class="form-control" id="datepicker11"
                                           value="<?= isset($ocorrencia['data_fim']) ? $ocorrencia['data_fim'] : NULL ?>"
                                           placeholder="DD/MM/AAAA" onblur="validate()">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label>
                                        <input type="checkbox" name="domingo" id="diasemana07"
                                               value="1" <?php checarOcorrencia($ocorrencia['domingo']) ?> > Domingo
                                        &nbsp;
                                        <input type="checkbox" name="segunda" id="diasemana01"
                                               value="1" <?php checarOcorrencia($ocorrencia['segunda']) ?> > Segunda
                                        &nbsp;
                                        <input type="checkbox" name="terca" id="diasemana02"
                                               value="1" <?php checarOcorrencia($ocorrencia['terca']) ?> > Terça
                                        &nbsp;
                                        <input type="checkbox" name="quarta" id="diasemana03"
                                               value="1" <?php checarOcorrencia($ocorrencia['quarta']) ?> > Quarta
                                        &nbsp;
                                        <input type="checkbox" name="quinta" id="diasemana04"
                                               value="1" <?php checarOcorrencia($ocorrencia['quinta']) ?>> Quinta
                                        &nbsp;
                                        <input type="checkbox" name="sexta" id="diasemana05"
                                               value="1" <?php checarOcorrencia($ocorrencia['sexta']) ?> > Sexta
                                        &nbsp;
                                        <input type="checkbox" name="sabado" id="diasemana06"
                                               value="1" <?php checarOcorrencia($ocorrencia['sabado']) ?> > Sábado
                                        &nbsp;
                                    </label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="horaInicio">Hora de Início*</label> <br>
                                    <input type="time" name="horaInicio" class="form-control" id="horaInicio"
                                           value="<?= $ocorrencia['horario_inicio'] ?>" required placeholder="hh:mm"/>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="horaFim">Hora Fim*</label> <br>
                                    <input type="time" name="horaFim" class="form-control" id="horaFim" required
                                           value="<?= $ocorrencia['horario_fim'] ?>" placeholder="hh:mm"/>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="retiradaIngresso">Retirada de Ingresso</label>
                                    <select name="retiradaIngresso" id="retiradaIngresso" class="form-control">
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcao("retirada_ingressos", $ocorrencia['retirada_ingresso_id']);
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="valor_ingresso">Valor Ingresso*</label> <br>
                                    <input type="text" name="valor_ingresso" class="form-control"
                                           value="<?= dinheiroParaBr($ocorrencia['valor_ingresso']) ?>" required id="valor_ingresso"
                                           placeholder="Em reais" onkeypress="return(moeda(this, '.', ',', event))"/>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="instituicao">Instituição</label>
                                    <select class="form-control" name="instituicao" id="instituicao">
                                        <option value="">Selecione uma opção...</option>

                                        <?php
                                        geraOpcao("instituicoes", $ocorrencia['instituicao_id']);
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="local">Local</label>
                                    <select class="form-control" id="local" name="local">
                                        <!-- Populando pelo js -->
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="espaco">Espaço</label>
                                    <select class="form-control" id="espaco" name="espaco">
                                        <!-- Populando pelo js -->
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="observacao">Observação</label><br/>
                                <textarea name="observacao" id="observacao" class="form-control"
                                          rows="5"><?= isset($ocorrencia['observacao']) ? $ocorrencia['observacao'] : NULL ?></textarea>
                            </div>

                        </div>

                        <div class="box-footer">
                            <a href="?perfil=evento_interno&p=ocorrencia_lista"><button type="button" class="btn btn-default" name="voltar">Voltar</button></a>
                            <input type="hidden" name="idOcorrencia" value="<?= $idOcorrencia ?>">
                            <button type="submit" name="edita" id="edita" class="btn btn-info pull-right">Gravar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>


<script>

    let data_fim = document.querySelector("input[name='data_fim']");
    
    if(data_fim.value != '')
    {
        let dias = document.querySelectorAll("input[type='checkbox']");
    
        for(dia of dias)
        {
            dia.disabled = false;
        }

    }

</script>
<script>
   const url = `<?=$url?>`;

   let instituicao = document.querySelector('#instituicao');
   let local_id = <?=$ocorrencia['local_id']?>;

    if(instituicao.value != ''){
        getLocais(instituicao.value, local_id)
    }

    instituicao.addEventListener('change', async e => {
        let idInstituicao = $('#instituicao option:checked').val();
        getLocais(idInstituicao, '')
        getEspacos('','') // Se alterar o primeiro ele limpa o local e o espaço 

    })

    function getLocais(idInstituicao, selectedId){
        fetch(`${url}?instituicao_id=${idInstituicao}`)
            .then(response => response.json())
            .then(locais => {                
                $('#local option').remove();
                $('#local').append('<option value="">Selecione uma opção...</option>');

                for (const local of locais) {
                    if(selectedId == local.id){
                        $('#local').append(`<option value='${local.id}' selected>${local.local}</option>`).focus();;
                    }else{
                        $('#local').append(`<option value='${local.id}'>${local.local}</option>`).focus();;
                    }
                    
                }                
            })
    }
    
    let local = document.querySelector('#local');
    let idEspaco = <?=$ocorrencia['espaco_id']?>;

    if(local_id != ''){
       
        console.log(`local ${local_id} Espaco ${idEspaco}`);
        getEspacos(local_id, idEspaco)
    }

    local.addEventListener('change', async e => {        
        let idLocal = $('#local option:checked').val();

        getEspacos(idLocal, '')
    })
    
    function getEspacos(idLocal, selectedId){
        fetch(`${url}?espaco_id=${idLocal}`)
            .then(response => response.json() )
            .then(espacos => {
                $('#espaco option').remove();
                if(espacos.length < 1){
                    $('#espaco').append('<option value="">Não há espaço para esse local</option>')
                    .attr('required',false)
                    .focus();
                }else{
                    $('#espaco').append('<option value="">Selecione uma opção...</option>')
                    .attr('required',true)
                    .focus();
                }
                
                for (const espaco of espacos) {
                    if(selectedId == espaco.id){
                        $('#espaco').append(`<option value='${espaco.id}' selected>${espaco.espaco}</option>`)
                    }else{
                        $('#espaco').append(`<option value='${espaco.id}'>${espaco.espaco}</option>`)
                    }
                }
             
            })
    }
  

</script>