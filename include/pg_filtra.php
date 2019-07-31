<?php
//include "includes/menu_principal.php";
$con = bancoMysqli();

$consulta = isset($_POST['filtrar']) ? 1 : 0;
$displayForm = 'block';
$displayBotoes = 'none';

if (isset($_POST['filtrar'])) {
    $datainicio = ($_POST['inicio']);
    $datafim = $_POST['final'] ?? null;
    $local = $_POST['local'] ?? null;
    $usuario = $_POST['inserido'] ?? null;
    $projeto = $_POST['projetoEspecial'] ?? null;

    if ($datainicio != '') {
        if ($datafim != '') {
            $datafim = ($_POST['final']);
            $filtro_data = "O.data_inicio BETWEEN '$datainicio' AND '$datafim'";
        } else {
            $filtro_data = "O.data_inicio > '$datainicio'";
        }
    } else {
        $mensagem = "Informe uma data para inicio da consulta";
        $consulta = 0;
    }

    if ($local != '') {
        $filtro_local = "AND O.local_id = '$local'";
    } else {
        $filtro_local = "";
        /* $mensagem = "Selecione um local para consulta";
        $consulta = 0;*/
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
        $filtro_PE = "AND E.projeto_especial_id = $projeto";
    } else {
        $filtro_PE = "";
    }


    $sql = "SELECT
        E.id AS 'evento_id',
        E.nome_evento AS 'nome',
        E.espaco_publico AS 'espaco_publico',
        E.projeto_especial_id AS 'projeto_especial_id',
        AT.quantidade_apresentacao AS 'apresentacoes',
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
        E.sinopse AS 'artista',
        CI.classificacao_indicativa AS 'classificacao',
        AT.links AS 'divulgacao',
        E.sinopse AS 'sinopse',
        E.fomento AS 'fomento',
        P.nome AS 'produtor_nome',
        P.email AS 'produtor_email',
        P.telefone1 AS 'produtor_fone',
        U.nome_completo AS 'nomeCompleto',
        PE.projeto_especial,
        SUB_PRE.subprefeitura AS 'subprefeitura',
        DIA_PERI.periodo AS 'periodo',
        retirada.retirada_ingresso AS 'retirada'
        FROM
        eventos AS E
        INNER JOIN tipo_eventos AS TE ON E.tipo_evento_id = TE.id
        INNER JOIN atracoes AS AT ON E.id = AT.evento_id
        INNER JOIN classificacao_indicativas AS CI ON AT.classificacao_indicativa_id = CI.id
        LEFT JOIN produtores AS P ON AT.produtor_id = P.id
        INNER JOIN usuarios AS U ON E.usuario_id = U.id
        LEFT JOIN projeto_especiais AS PE ON E.projeto_especial_id = PE.id
        INNER JOIN ocorrencias AS O ON E.id = O.origem_ocorrencia_id
        INNER JOIN locais AS L ON O.local_id = L.id
        LEFT JOIN subprefeituras AS SUB_PRE ON O.subprefeitura_id = SUB_PRE.id
        LEFT JOIN periodos AS DIA_PERI ON O.periodo_id = DIA_PERI.id
        INNER JOIN retirada_ingressos AS retirada ON O.retirada_ingresso_id = retirada.id
        
        WHERE
        $filtro_data
        $filtro_local
        $filtro_usuario
        $filtro_PE AND
        E.evento_status_id = 3 AND
        E.publicado = 1
        ORDER BY O.data_inicio";

    $query = mysqli_query($con, $sql);
    $num = mysqli_num_rows($query);

    if ($num > 0) {
        $mensagem = "Foram encontrados $num resultados";
        $consulta = 1;
        $displayForm = 'none';
        $displayBotoes = 'block';

    } else {
        $consulta = 0;
        $mensagem = "Não foram encontrados resultados para esta pesquisa!";
    }
}
?>
<div class="content-wrapper">
    <section class="content-header">
        <h3 class="box-title">Eventos - Gerar Excel - Filtrar</h3>
        <h6><?php if (isset($mensagem)) {
                echo $mensagem;
            } ?></h6>
        <div class="box box-primary">
            <form method="POST" action="?perfil=agendao&p=exporta_evento">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-offset-4 col-md-4" align="center">
                            <label for="inserido">Inserido por (usuário)</label>
                            <input type="text" class="form-control" name="inserido" id="inserido" placeholder="">
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
                            <input type="date" name="inicio" class="form-control" id="inicio"
                                   onchange="desabilitaFiltrar()" placeholder="">
                        </div>
                        <div class="col-md-3">
                            <label>Data encerramento</label>
                            <input type="date" name="final" class="form-control" id="final"
                                   placeholder="">
                            <br>
                        </div>

                        <input type="submit" class="btn btn-theme btn-block" name="filtrar" id="filtrar" value="Filtrar"
                               disabled>
                    </div>
                </div>
            </form>
        </div>

        <div class="container" id="resultado">
            <?php
            if ($consulta == 1) {
                ?>
                <form method="post" action="../../pdf/exportar_excel_agendao.php">
                    <div class="form-group">
                        <br/>
                        <input type="hidden" name="sql" value="<?= $sql ?>">
                        <input type="submit" class="btn btn-theme btn-block" name="exportar"
                               value="Baixar Arquivo Excel">
                        <br>
                    </div>
                </form>
                <div class="table-responsive list_info" id="tabelaEventos">
                    <table class='table table-condensed'>
                        <thead>
                        <tr class='list_menu'>
                            <td>Espaço Público?</td>
                            <td>Local do Evento</td>
                            <td>Logradouro</td>
                            <td>Número</td>
                            <td>Complemento</td>
                            <td>Bairro</td>
                            <td>Cidade</td>
                            <td>Estado</td>
                            <td>CEP</td>
                            <td>SubPrefeitura</td>
                            <td>Data Início</td>
                            <td>Data Fim</td>
                            <td>Dias da semana</td>
                            <td>Horário de início</td>
                            <td>Período</td>
                            <td>Horário do fim</td>
                            <td>Nº de atividades</td>
                            <td>Cobrança de ingresso</td>
                            <td>Valor do ingresso</td>
                            <td>Nome do Evento</td>
                            <td>Projeto Especial?</td>
                            <td>Artistas</td>
                            <td>Ação</td>
                            <td>Público</td>
                            <td>É Fomento/Programa?</td>
                            <td>Classificação indicativa</td>
                            <td>Link de Divulgação</td>
                            <td>Sinopse</td>
                            <td>Produtor do Evento</td>
                            <td>E-mail de contato</td>
                            <td>Telefone de contato</td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        while ($linha = mysqli_fetch_array($query)) {

                            $totalDias = '';
                            $dias = "";
                            $linha['segunda'] == 1 ? $dias .= "Segunda, " : '';
                            $linha['terca'] == 1 ? $dias .= "Terça, " : '';
                            $linha['quarta'] == 1 ? $dias .= "Quarta, " : '';
                            $linha['quinta'] == 1 ? $dias .= "Quinta, " : '';
                            $linha['sexta'] == 1 ? $dias .= "Sexta, " : '';
                            $linha['sabado'] == 1 ? $dias .= "Sabádo, " : '';
                            $linha['domingo'] == 1 ? $dias .= "Domingo. " : '';

                            if ($dias != "") {
                                //echo "dias diferente de vazio " . $respectiva . $dias;
                                $totalDias .= substr($dias, 0, -2) . ".<br>";
                            } else {
                                $totalDias .= "Dias não especificados. <br>";
                            }

                            //Ações
                            $sqlAcao = "SELECT * FROM acao_evento WHERE evento_id = '" . $linha['evento_id'] . "'";
                            $queryAcao = mysqli_query($con, $sqlAcao);
                            $acoes = [];
                            $i = 0;

                            while ($arrayAcoes = mysqli_fetch_array($queryAcao)) {
                                $idAcao = $arrayAcoes['acao_id'];
                                $sqlLinguagens = "SELECT * FROM acoes WHERE id = '$idAcao'";
                                $linguagens = $con->query($sqlLinguagens)->fetch_assoc();
                                $acoes[$i] = $linguagens['acao'];
                                $i++;
                            }

                            if (count($acoes) != 0) {
                                $stringAcoes = implode(", ", $acoes);
                            }

                            //Público
                            $sqlPublico = "SELECT * FROM evento_publico WHERE evento_id = '" . $linha['evento_id'] . "'";
                            $queryPublico = mysqli_query($con, $sqlPublico);
                            $representatividade = [];
                            $i = 0;

                            while ($arrayPublico = mysqli_fetch_array($queryPublico)) {
                                $idRepresentatividade = $arrayPublico['publico_id'];
                                $sqlRepresen = "SELECT * FROM publicos WHERE id = '$idRepresentatividade'";
                                $publicos = $con->query($sqlRepresen)->fetch_assoc();
                                $representatividade[$i] = $publicos['publico'];
                                $i++;
                            }

                            if (count($acoes) != 0) {
                                $stringPublico = implode(", ", $representatividade);
                            }

                            if ($linha['fomento'] != 0) {
                                $sqlFomento = "SELECT * FROM fomentos WHERE id = '" . $linha['fomento'] . "'";
                                $fomento = $con->query($sqlFomento)->fetch_assoc();
                            }
                            ?>
                            <tr>
                                <td class="list_description"><?= $linha['espaco_publico'] == 1 ? "SIM" : "NÃO" ?></td>
                                <td class="list_description"><?= $linha['nome_local'] ?></td>
                                <td class="list_description"><?= $linha['logradouro'] ?></td>
                                <td class="list_description"><?= $linha['numero'] ?></td>
                                <td class="list_description"><?= $linha['complemento'] ?></td>
                                <td class="list_description"><?= $linha['bairro'] ?></td>
                                <td class="list_description"><?= $linha['cidade'] ?></td>
                                <td class="list_description"><?= $linha['estado'] ?></td>
                                <td class="list_description"><?= $linha['cep'] ?></td>
                                <td class="list_description"><?= $linha['subprefeitura'] ?></td>
                                <td class="list_description"><?= exibirDataBr($linha['data_inicio']) ?></td>
                                <td class="list_description"><?= ($linha['data_fim'] == "0000-00-00") ? "Não é Temporada" : exibirDataBr($linha['data_fim']) ?></td>
                                <td class="list_description"><?= $totalDias ?></td>
                                <td class="list_description"><?= exibirHora($linha['hora_inicio']) ?></td>
                                <td class="list_description"><?= $linha['periodo'] ?></td>
                                <td class="list_description"><?= exibirHora($linha['hora_fim']) ?></td>
                                <td class="list_description"><?= $linha['apresentacoes'] ?></td>
                                <td class="list_description"><?= $linha['retirada'] ?></td>
                                <td class="list_description"><?= ($linha['valor_ingresso'] != '0.00') ? dinheiroParaBr($linha['valor_ingresso']) . " reais." : "Gratuito" ?></td>
                                <td class="list_description"><?= $linha['nome'] ?></td>
                                <td class="list_description"><?= $linha['projeto_especial'] ?></td>
                                <td class="list_description"><?= mb_strimwidth($linha['artista'], 0, 50, '...') ?></td>
                                <td class="list_description"><?= $stringAcoes ?? "Não há ações." ?></td>
                                <td class="list_description"><?= $stringPublico ?? "Não foi selecionado público." ?></td>
                                <td class="list_description"><?= isset($fomento['fomento']) ? $fomento['fomento'] : "Não" ?></td>
                                <td class="list_description"><?= $linha['classificacao'] ?></td>
                                <td class="list_description"><?= isset($linha['divulgacao']) ? $linha['divulgacao'] : "Sem link de divulgação." ?></td>
                                <td class="list_description"><?= mb_strimwidth($linha['sinopse'], 0, 50, '...') ?></td>
                                <td class="list_description"><?= $linha['produtor_nome'] ?></td>
                                <td class="list_description"><?= $linha['produtor_email'] ?></td>
                                <td class="list_description"><?= $linha['produtor_fone'] ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <?php
            }
            ?>
        </div>
    </section>
</div>

<script>
    function mostraDiv() {
        let form = document.querySelector('#testeTana');
        form.style.display = 'block';

        let botoes = document.querySelector('#botoes');
        botoes.style.display = 'none';

        let resultado = document.querySelector('#resultado');
        resultado.style.display = 'none';
    }

    function desabilitaFiltrar() {

        var inicio = document.querySelector("#inicio");
        var filtrar = document.querySelector("#filtrar");

        if (inicio.value.length != 0) {
            filtrar.disabled = false;
        } else {
            filtrar.disabled = true;
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