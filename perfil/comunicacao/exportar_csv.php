<?php
include "includes/menu.php";
$con = bancoMysqli();

$url = 'http://' . $_SERVER['HTTP_HOST'] . '/siscontrat2/funcoes/api_locais_espacos.php';

if (isset($_POST['filtrar'])) {
    $dataInicio = exibirDataMysql($_POST['data_inicio']);
    $data_encerramento = $_POST['data_encerramento'];
    $instituicaoID = $_POST['instituicao'];
    $localID = $_POST['SelectLocal'];

    if ($data_encerramento != '') {
        $data_encerramento = exibirDataMysql($data_encerramento);
        $filtroData = "AND O.data_inicio BETWEEN $dataInicio AND $data_encerramento";

    } else {
        $filtroData = "AND O.data_inicio > '$dataInicio'";

    }

    if ($instituicaoID != '') {
        $filtroInsti = "AND O.instituicao_id = '$instituicaoID'";
    } else {
        $filtroInsti = '';
    }

    if ($localID != '') {
        $filtroLocal = "AND O.local_id = '$localID'";
    } else {
        $filtroLocal = '';
    }

    $sqlConsulta = "SELECT 
                           E.nome_evento AS 'nomeEvento',                    
                           A.id AS 'atracao_id',
                           DATE_FORMAT(O.data_inicio, '%d/%m/%Y') AS 'data_inicio',
                           DATE_FORMAT(O.horario_inicio, '%H:%i') AS 'horario_inicio',
                           O.valor_ingresso AS 'valor',
                           E.sinopse AS 'descricao',
                           espacos.espaco AS 'sala'                          
                           
                     FROM 
                         eventos AS E
                         INNER JOIN atracoes AS A ON A.evento_id = E.id
                         INNER JOIN ocorrencias AS O ON O.atracao_id = A.id
                         INNER JOIN locais AS L ON L.id = O.local_id
                         INNER JOIN espacos ON espacos.id = O.espaco_id
                     
                     WHERE 
                        E.publicado = 1 AND 
                        E.evento_status_id = 3
                        $filtroData
                        $filtroInsti
                        $filtroLocal
                        ORDER BY data_inicio";

    $queryConsulta = mysqli_query($con, $sqlConsulta);
    $num = mysqli_num_rows($queryConsulta);

    if ($num > 0) {
        $consulta = 1;
        $mensagem = "<b> Foram encontrados $num resultados.</b>";
    } else {
        $mensagem = "<b>Não foram encontrados resultados para esta pesquisa!</b>";
    }

    //echo $sqlConsulta;
}


?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <!-- START ACCORDION-->
        <div class="row" align="center">
            <?php if (isset($mensagem2)) {
                echo $mensagem2;
            }; ?>
        </div>
        <h2 class="page-header">Comunicação - Gerar CSV</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-solid">
                    <div class="box-body">
                        <div class="box-group" id="gerarCSV">
                            <form action="#" class="form-group" method="post">
                                <div class="row text-center">
                                    <div class="col-md-offset-3 col-md-3">
                                        <label for="data_inicio"> Data início *</label><br>
                                        <input type="text" name="data_inicio" id="data_inicio"
                                               class="form-control datepicker" onchange="btnfiltrar()" required
                                               autocomplete="off">
                                        <i class="text-danger" style="display: none;" id="msgSemInicio">Informe uma data
                                            de início!</i>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="data_encerramento"> Data encerramento</label>
                                        <input type="text" name="data_encerramento" class="form-control datepicker"  id="encerramento">
                                    </div>
                                </div>
                                <br>
                                <div class="row text-center">
                                    <div class="col-md-5" style="margin-left: 29%">
                                        <label for="instituicao">Instituição</label><br>
                                        <select name="instituicao" id="instituicao" class="form-control"
                                                onchange="insti_local()">
                                            <option value="">Selecione...</option>
                                            <?php geraOpcao('instituicoes', '') ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row text-center">
                                    <div class="col-md-5" id="local" style="margin-left: 29%; display: none">
                                        <br>
                                        <label for="SelectLocal">Local</label>
                                        <select name="SelectLocal" id="SelectLocal" class="form-control">
                                            <option value="">Selecione...</option>
                                            <?php geraOpcao('locais', '') ?>
                                        </select>
                                    </div>
                                </div>
                                <br>
                                <div class="row text-center">
                                    <div class="col-md-12" id="btnFiltrar">
                                        <button type="submit" class="btn btn-info" name="filtrar" id="filtrar">Filtrar
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="row" align="center">
                            <?php if (isset($mensagem)) {
                                echo $mensagem;
                            }; ?>
                        </div>

                        <div class="row text-center" id="novaPesquisa" style="display: none">
                            <br>
                            <div class="col-md-12">
                                <button type="button" class="btn btn-info" id="btnNovaPesquisa">Nova pesquisa</button>
                            </div>
                        </div>
                        <?php
                        if (isset($consulta)):
                            ?>
                            <div id="resultadoConsulta">
                            <form method="post" action="../pdf/exportar_csv.php">
                                <div class="form-group">
                                    <div class="col-md-offset-2 col-md-8">
                                        <br/>
                                        <input type="hidden" name="sqlConsulta" value="<?= $sqlConsulta ?>">
                                        <input type="submit" class="btn btn-primary btn-block" name="exportar"
                                               value="Baixar Arquivo .csv">
                                        <br>
                                    </div>
                                </div>
                            </form>
                            <br>
                            <div class="row">
                            <div class="col-md-12 text-center">
                            <div class="table-responsive list_info">
                            <table class="table table-condensed table-striped table-hover">
                            <thead class="text-bold text-center" style="background: #00c0ef; border-color: #00acd6">
                            <tr>
                                <td>Nome do Evento</td>
                                <td>Categoria(s)</td>
                                <td>Data/Hora Início</td>
                                <td>Valor</td>
                                <td>Nome da Sala</td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            while ($result = mysqli_fetch_array($queryConsulta)) {
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
                                ?>

                                <tr class="text-center">
                                    <td class="list_description"><?= $result['nomeEvento'] ?></td>
                                    <td class="list_description"><?= $categorias ?></td>
                                    <td class="list_description"><?= $result['data_inicio'] . " " . $result['horario_inicio'] ?></td>
                                    <td class="list_description"><?= $result['valor'] == 0 ? 'Gratuito' : $result['valor'] ?></td>
                                    <td class="list_description"><?= $result['sala'] ?></td>
                                </tr>
                                </tbody>
                                </table>
                                </div>
                                </div>

                                </div>
                                </div>

                                <?php
                            }
                        endif; ?>

                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <!-- END ACCORDION & CAROUSEL-->

    </section>
    <!-- /.content -->
</div>

<script>
    $(function () {
        $(".datepicker").datepicker();



        $('#filtrar').mouseover(function () {
            if ($('#data_inicio').val() == '') {
                $('#msgSemInicio').show();
                $('#filtrar').prop('disabled', true);
            }
        })

        let consulta = "<?= isset($consulta) ? 1 : 0 ?>";

        if (consulta == 1) {
            $('#gerarCSV').hide();
            $('#novaPesquisa').show();
        }

    });

    $('#btnNovaPesquisa').on('click', function () {
        $('#gerarCSV').fadeIn();
        $('#novaPesquisa').hide();
        $('#resultadoConsulta').fadeOut();
    })

    function insti_local() {
        const urlModal = `<?=$url?>`;

        var idInstituicaoModal = $('#instituicao').val();
        $('#local').show();

        $.post(urlModal, {
            instituicao_id: idInstituicaoModal,
        })
            .done(function (data) {
                $('#SelectLocal option').remove();
                $('#SelectLocal').append('<option value="">Selecione uma opção...</option>');

                for (let local of data) {
                    $('#SelectLocal').append(`<option value='${local.id}'>${local.local}</option>`).focus();
                }
            })
            .fail(function () {
                swal("danger", "Erro ao gravar");
            });

    }

    function btnfiltrar() {
        if ($('#data_inicio').val() == '') {
            $('#filtrar').prop('disabled', true);
            $('#msgSemInicio').show();
            //$('#filtrar').on('mouseover', mostrarMsg);

        } else {
            $('#filtrar').prop('disabled', false);
            $('#msgSemInicio').hide();
        }

    }


</script>