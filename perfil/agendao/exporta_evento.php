<?php
include "includes/menu_principal.php";
$con = bancoMysqli();

$consulta = isset($_POST['filtrar']) ? 1 : 0;
$displayForm = 'block';
$displayBotoes = 'none';

if (isset($_POST['filtrar'])) {
    $datainicio = exibirDataMysql($_POST['inicio']);
    $datafim = $_POST['final'] ?? null;
    $local = $_POST['local'] ?? null;
    $usuario = $_POST['inserido'] ?? null;
    $projeto = $_POST['projetoEspecial'] ?? null;

    if ($datainicio != '') {
        if ($datafim != '') {
            $datafim = ($_POST['final']);
            $filtro_data = "O.data_inicio BETWEEN '$datainicio' AND '$datafim'";
        } else {
            $filtro_data = "O.data_inicio >= '$datainicio'";
        }
    } else {
        $mensagem = "Informe uma data para inicio da consulta";
        $consulta = 0;
    }

    if ($local != '') {
        $filtro_local = "AND O.local_id = '$local'";
    } else {
        $filtro_local = "";
    }

    if ($usuario != '') {
        $sql_user = "SELECT * FROM usuarios WHERE nome_completo like '%$usuario%'";
        $query_user = mysqli_query($con, $sql_user);
        if (mysqli_num_rows($query_user) > 0) {
            $user = mysqli_fetch_array($query_user);
            $idUsuario = $user['id'];
            $nomeUser = $user['nome_completo'];
            $filtro_usuario = "AND E.usuario_id = $idUsuario";
        } else {
            $mensagem = "Usuário não possuí nenhum evento enviado!";
            $consulta = 0;
            $filtro_usuario = "";
        }
    } else {
        $filtro_usuario = "";
    }

    if ($projeto != '') {
        $filtro_PE = "AND PE.id = $projeto";
    } else {
        $filtro_PE = "";
    }

    $sql = "SELECT
                E.id AS 'evento_id',
                E.tipo_evento_id AS 'tipo_evento',
                E.nome_evento AS 'nome',
                E.espaco_publico AS 'espaco_publico',
                A.quantidade_apresentacao AS 'apresentacoes',
                PE.projeto_especial AS 'projeto_especial',
                TE.tipo_evento AS 'categoria',
                O.id AS 'idOcorrencia',
                O.horario_inicio AS 'hora_inicio',
                O.data_inicio AS 'data_inicio',
                O.data_fim AS 'data_fim',
                O.horario_fim AS 'hora_fim',
                O.valor_ingresso AS 'valor_ingresso',
                O.segunda AS 'segunda',
                O.terca AS 'terca',
                O.quarta AS 'quarta',
                O.quinta AS 'quinta',
                O.sexta AS 'sexta',
                O.sabado AS 'sabado',
                O.domingo AS 'domingo',
                L.local AS 'nome_local',
                L.logradouro AS 'logradouro',
                L.numero AS 'numero',
                L.complemento AS 'complemento',
                L.bairro AS 'bairro',
                L.cidade AS 'cidade',
                L.uf AS 'estado',
                L.cep AS 'cep',
                E.sinopse AS 'artistas',
                CI.classificacao_indicativa AS 'classificacao',
                A.links AS 'divulgacao',
                E.sinopse AS 'sinopse',
                E.fomento AS 'fomento',
                P.nome AS 'produtor_nome',
                P.email AS 'produtor_email',
                P.telefone1 AS 'produtor_fone',
                U.nome_completo AS 'nomeCompleto',
                PE.projeto_especial,
                SUB_PRE.subprefeitura AS 'subprefeitura',
                DIA_PERI.periodo AS 'periodo',
                retirada.retirada_ingresso AS 'retirada',
                I.sigla AS 'instiSigla'
                FROM eventos AS E
                LEFT JOIN tipo_eventos AS TE ON E.tipo_evento_id = TE.id  
                LEFT JOIN ocorrencias AS O ON O.origem_ocorrencia_id = E.id          
                LEFT JOIN usuarios AS U ON E.usuario_id = U.id
                LEFT JOIN projeto_especiais AS PE ON E.projeto_especial_id = PE.id
                LEFT JOIN instituicoes AS I ON I.id = O.instituicao_id
                LEFT JOIN locais AS L ON O.local_id = L.id
                LEFT JOIN subprefeituras AS SUB_PRE ON O.subprefeitura_id = SUB_PRE.id
                LEFT JOIN periodos AS DIA_PERI ON O.periodo_id = DIA_PERI.id
                LEFT JOIN retirada_ingressos AS retirada ON O.retirada_ingresso_id = retirada.id
                LEFT JOIN atracoes AS A ON A.evento_id = E.id 
                LEFT JOIN classificacao_indicativas AS CI ON A.classificacao_indicativa_id = CI.id
                LEFT JOIN produtores AS P ON A.produtor_id = P.id
                WHERE
                $filtro_data
                $filtro_local
                $filtro_usuario
                $filtro_PE AND
                E.evento_status_id = 3 AND
                E.publicado = 1
                ORDER BY O.data_inicio";

    $sqlAgendao = "SELECT
                agendao.id AS 'evento_id',
                agendao.nome_evento AS 'nome',
                agendao.espaco_publico AS 'espaco_publico',
                agendao.quantidade_apresentacao AS 'apresentacoes',
                PE.projeto_especial AS 'projeto_especial',
                O.id AS 'idOcorrencia',
                O.horario_inicio AS 'hora_inicio',
                O.data_inicio AS 'data_inicio',
                O.data_fim AS 'data_fim',
                O.horario_fim AS 'hora_fim',
                O.valor_ingresso AS 'valor_ingresso',
                O.segunda AS 'segunda',
                O.terca AS 'terca',
                O.quarta AS 'quarta',
                O.quinta AS 'quinta',
                O.sexta AS 'sexta',
                O.sabado AS 'sabado',
                O.domingo AS 'domingo',
                L.local AS 'nome_local',
                L.logradouro AS 'logradouro',
                L.numero AS 'numero',
                L.complemento AS 'complemento',
                L.bairro AS 'bairro',
                L.cidade AS 'cidade',
                L.uf AS 'estado',
                L.cep AS 'cep',
                agendao.ficha_tecnica AS 'artistas',
                CI.classificacao_indicativa AS 'classificacao',
                agendao.links AS 'divulgacao',
                agendao.sinopse AS 'sinopse',
                agendao.fomento AS 'fomento',
                P.nome AS 'produtor_nome',
                P.email AS 'produtor_email',
                P.telefone1 AS 'produtor_fone',
                U.nome_completo AS 'nomeCompleto',
                PE.projeto_especial,
                SUB_PRE.subprefeitura AS 'subprefeitura',
                DIA_PERI.periodo AS 'periodo',
                retirada.retirada_ingresso AS 'retirada',
                I.sigla AS 'instiSigla'
                FROM agendoes AS agendao
                LEFT JOIN agendao_ocorrencias AS O ON agendao.id = O.origem_ocorrencia_id
                LEFT JOIN locais AS L ON O.local_id = L.id
                LEFT JOIN instituicoes AS I ON O.instituicao_id = I.id
                LEFT JOIN subprefeituras AS SUB_PRE ON O.subprefeitura_id = SUB_PRE.id
                LEFT JOIN usuarios AS U ON agendao.usuario_id = U.id
                LEFT JOIN periodos AS DIA_PERI ON O.periodo_id = DIA_PERI.id
                LEFT JOIN projeto_especiais AS PE ON agendao.projeto_especial_id = PE.id
                LEFT JOIN retirada_ingressos AS retirada ON O.retirada_ingresso_id = retirada.id
                LEFT JOIN classificacao_indicativas AS CI ON agendao.classificacao_indicativa_id = CI.id
                LEFT JOIN produtores AS P ON agendao.produtor_id = P.id
                WHERE
                $filtro_data
                $filtro_local
                $filtro_usuario
                $filtro_PE AND
                agendao.evento_status_id = 3 AND
                agendao.publicado = 1
                ORDER BY O.data_inicio";

    if (!$query = mysqli_query($con, $sql)) {
        echo "sql Eventos: " . $sql;
    }

    if ((!$queryAgendao = mysqli_query($con, $sqlAgendao))) {
        echo "sql Agendao: " . $sqlAgendao;
    }

    $numComuns = mysqli_num_rows($query);
    $numAgendao = mysqli_num_rows($queryAgendao);

    $num = $numComuns + $numAgendao;

    if ($num > 0) {
        $mensagem = "Foram encontrados $num resultados";
        $consulta = 1;
        $displayForm = 'none';
        $displayBotoes = 'block';

    } else {
        $consulta = 0;
        $mensagem2 = mensagem("warning", "Não foram encontrados resultados para esta pesquisa!");
    }
}
?>

<div class="content-wrapper">
    <section class="content">
        <h3 class="box-title">Eventos - Gerar Excel - Filtrar</h3>
        <h6><?php if (isset($mensagem)) {
                echo $mensagem;
            } ?></h6>
        <div class="row" align="center">
            <?php if (isset($mensagem2)) {
                echo $mensagem2;
            }; ?>
        </div>
        <div class="box box-primary" id="filtro">
            <form method="POST" action="?perfil=agendao&p=exporta_evento">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-offset-4 col-md-4" align="center">
                            <label for="inserido">Inserido por (usuário)</label>
                            <input type="text" class="form-control" id="inserido" name="inserido" placeholder="">
                            <br>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-offset-4 col-md-4" align="center">
                            <label for="projetoEspecial">Projeto Especial</label> <br>
                            <select class="form-control" name="projetoEspecial" id="projetoEspecial">
                                <option value="">Selecione uma opção...</option>
                                <?php
                                geraOpcaoPublicado("projeto_especiais", "");
                                ?>
                            </select>
                            <br>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-offset-4 col-md-4" align="center">
                            <label for="local">Local</label>
                            <select name="local" class="form-control" id="local">
                                <option value="">Seleciona uma Opção...</option>
                                <?php
                                geraOpcao("locais", ""); ?>
                            </select>
                            <br>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-offset-3 col-md-3">
                            <label>Data início *</label>
                            <input type="date" name="inicio" class="form-control" id="data_inicio"
                                   onchange="btnfiltrar()">
                        </div>
                        <div class="col-md-3">
                            <label>Data encerramento *</label>
                            <input type="date" name="final" class="form-control" id="final">
                            <br>
                        </div>
                    </div>
                    <span id="spanFiltrar" title="Informe uma data de início!">
                        <input type="submit" class="btn btn-primary btn-theme center-block" name="filtrar" id="filtrar"
                               value="Filtrar">
                    </span>
                </div>
            </form>
        </div>
    </section>

<?php
if ($consulta == 1) {
    ?>
        <section class="content" style="margin-top: -20%">
            <div class="row text-center" id="novaPesquisa" style="display: none">
                <br>
                <div class="col-md-12">
                    <button type="button" class="btn btn-info" id="btnNovaPesquisa">Nova pesquisa</button>
                </div>
            </div>
            <br>
            <div id="resultadoPesquisa"  style="margin-top: 10px;">
                <div class="box-header">
                    <form method="post" action="../pdf/exportar_excel_agendao.php">
                        <div class="form-group">
                            <br>
                            <input type="hidden" name="sql" value="<?= $sql ?>">
                            <input type="hidden" name="sqlAgendao" value="<?= $sqlAgendao ?>">
                            <input type="submit" class="btn btn-success btn-theme btn-block" name="exportar"
                                   value="Baixar Arquivo Excel">
                        </div>
                    </form>
                </div>

                <h3 class="box-title">Resultado da pesquisa
                    <button class='btn btn-default' type='button' data-toggle='modal'
                            data-target='#modal' style="border-radius: 30px;">
                        <i class="fa fa-question-circle"></i></button>
                </h3>
                <div class="box box-success">
                    <div class="box-body">
                        <h3 class="box-title">Resumo da pesquisa eventos Agendão</h3>
                        <table id="tblAgendao" class="table table-bordered table-striped table-responsive">
                            <thead>
                            <tr>
                                <th>Nome do Evento</th>
                                <th>Local do Evento</th>
                                <th>Classificação indicativa</th>
                                <th>SubPrefeitura</th>
                                <th>Valor do ingresso</th>
                                <th>Nº de atividades</th>
                                <th>Artistas</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php
                            while ($linha = mysqli_fetch_array($queryAgendao)) {
                                ?>
                                <tr>
                                    <td><?= $linha['nome'] ?></td>
                                    <td><?= $linha['nome_local'] ?></td>
                                    <td><?= $linha['classificacao'] ?></td>
                                    <td><?= $linha['subprefeitura'] ?></td>
                                    <td><?= $linha['valor_ingresso'] == '0.00' ? 'Grátis' : 'R$ ' . dinheiroParaBr($linha['valor_ingresso']) ?></td>
                                    <td><?= $linha['apresentacoes'] ?></td>
                                    <td><?= $linha['artistas'] ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>

                            <tfoot>
                            <tr>
                                <th>Nome do Evento</th>
                                <th>Local do Evento</th>
                                <th>Classificação indicativa</th>
                                <th>SubPrefeitura</th>
                                <th>Valor do ingresso</th>
                                <th>Nº de atividades</th>
                                <th>Artistas</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="box box-success">
                    <div class="box-body">
                        <h3 class="box-title">Resumo da pesquisa eventos comuns</h3>
                        <table id="tblEvento" class="table table-bordered table-striped table-responsive">
                            <thead>
                            <tr>
                                <th>Nome do Evento</th>
                                <th>Local do Evento</th>
                                <th>Classificação indicativa</th>
                                <th>SubPrefeitura</th>
                                <th>Valor do ingresso</th>
                                <th>Nº de atividades</th>
                                <th>Artistas</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php
                            while ($linha = mysqli_fetch_array($query)) {
                                if ($linha['tipo_evento'] == 2) {
                                    $filme = recuperaDados("filmes", "id", $linha['evento_id']);
                                    $classificao = recuperaDados("classificacao_indicativas", "id", $filme['classificacao_indicativa_id']);

                                    $linha ['classificacao'] = $classificao['classificacao_indicativa'];
                                }

                                ?>
                                <tr>
                                    <td><?= $linha['nome'] ?></td>
                                    <td><?= $linha['nome_local'] ?></td>
                                    <td><?= $linha['classificacao'] ?></td>
                                    <td><?= $linha['subprefeitura'] ?></td>
                                    <td><?= $linha['valor_ingresso'] == '0.00' ? 'Grátis' : 'R$ ' . dinheiroParaBr($linha['valor_ingresso']) ?></td>
                                    <td><?= $linha['apresentacoes'] == '' ? 'Este evento é filme!' : $linha['apresentacoes'] ?></td>
                                    <td><?= $linha['artistas'] ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>

                            <tfoot>
                            <tr>
                                <th>Nome do Evento</th>
                                <th>Local do Evento</th>
                                <th>Classificação indicativa</th>
                                <th>SubPrefeitura</th>
                                <th>Valor do ingresso</th>
                                <th>Nº de atividades</th>
                                <th>Artistas</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </section>

<?php } ?>

</div>

<div class="modal fade" id="modal" role="dialog" aria-labelledby="lblModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Informações que serão exportadas sobre o evento</h4>
            </div>
            <div class="modal-body" style="text-align: left;">
                <ul class="list-group">
                    <li class="list-group-item">Nome do Evento</li>
                    <li class="list-group-item">Local do Evento</li>
                    <li class="list-group-item">Endereço Completo</li>
                    <li class="list-group-item">SubPrefeitura</li>
                    <li class="list-group-item">Artistas</li>
                    <li class="list-group-item">Data Início</li>
                    <li class="list-group-item">Data Fim</li>
                    <li class="list-group-item">Horário de início</li>
                    <li class="list-group-item">Horário do fim</li>
                    <li class="list-group-item">Nº de Apresentações</li>
                    <li class="list-group-item">Período</li>
                    <li class="list-group-item">Ação / Expressão Artística Principal</li>
                    <li class="list-group-item">Público / Representatividade Social Principal</li>
                    <li class="list-group-item">Espaço Público</li>
                    <li class="list-group-item">Entrada</li>
                    <li class="list-group-item">Valor do Ingresso (no caso de cobrança)</li>
                    <li class="list-group-item">Classificação indicativa</li>
                    <li class="list-group-item">Link de Divulgação</li>
                    <li class="list-group-item">Sinopse</li>
                    <li class="list-group-item">Projeto Especial</li>
                    <li class="list-group-item">Fomento / Programa</li>
                    <li class="list-group-item">Produtor do Evento</li>
                    <li class="list-group-item">E-mail de contato</li>
                    <li class="list-group-item">Telefone de contato</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-theme" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>

    $(function () {
        $(".datepicker").datepicker();

        $('#filtrar').mouseover(function () {
            if ($('#data_inicio').val() == '') {
                $('#filtrar').prop('disabled', true);
            }
        });

        let consulta = "<?= $consulta ?>";

        if (consulta == 1) {
            $('#filtro').hide();
            $('#novaPesquisa').show();
        }

    });

    $('#btnNovaPesquisa').on('click', function () {
        $('#filtro').fadeIn();
        $('#novaPesquisa').hide();
        $('#resultadoPesquisa').fadeOut();
    });

    function btnfiltrar() {
        if ($('#data_inicio').val() == '') {
            $('#filtrar').prop('disabled', true);
            $('#spanFiltrar').attr('title', 'Informe uma data de início!');

        } else {
            $('#filtrar').prop('disabled', false);
            $('#spanFiltrar').attr('title', '');
        }
    }


</script>

<script>
    $(function () {
        var usuarios = [];
        $.getJSON("ajax_usuario.php", function (result) {
            $.each(result, function (i, field) {
                usuarios.push(field.nome_completo);
            });
        });

        $("#inserido").autocomplete({
            source: usuarios
        });
    });
</script>

<script defer src="../visual/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script defer src="../visual/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
    $(function () {
        $('#tblAgendao').DataTable({
            "language": {
                "url": 'bower_components/datatables.net/Portuguese-Brasil.json'
            },
            "responsive": true,
            "dom": "<'row'<'col-sm-6'l><'col-sm-6 text-right'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7 text-right'p>>",
        });
    });

    $(function () {
        $('#tblEvento').DataTable({
            "language": {
                "url": 'bower_components/datatables.net/Portuguese-Brasil.json'
            },
            "responsive": true,
            "dom": "<'row'<'col-sm-6'l><'col-sm-6 text-right'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7 text-right'p>>",
        });
    });
</script>
