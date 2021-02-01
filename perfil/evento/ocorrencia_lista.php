<?php
include "includes/menu_interno.php";

$con = bancoMysqli();
$conn = bancoPDO();

$idEvento = $_SESSION['idEvento'];

if (isset($_POST['apagar'])) {

    $idOcorrencia = $_POST['idOcorrenciaApaga'];

    $sql = "UPDATE siscontrat.ocorrencias SET publicado = 0 WHERE id = '$idOcorrencia'";

    if (mysqli_query($con, $sql)) {
        $mensagem = mensagem("success", "Ocorrência apagada com sucesso");
    } else {
        $mensagem = mensagem("danger", "Erro ao apagar a ocorrência. Tente novamente!");
    }

}

if (isset($_POST['duplicar'])) {
    $idProjeto = $_POST['idProjeto'];
    $numeroDuplica = $_POST['numeroDuplica'];

    $sqlProjeto = "SELECT * FROM siscontrat.ocorrencias WHERE id = :id";

    $stmt = $conn->prepare($sqlProjeto);
    $stmt->bindValue(':id', $idProjeto);
    $stmt->execute();
    $cloneOcorrencia = $stmt->fetch();

    array_shift($cloneOcorrencia);

    foreach ($cloneOcorrencia as $key => $dado) {
        $cloneOcorrencia[$key] = addslashes($dado);
    }
    $inserir = "INSERT INTO siscontrat.ocorrencias 
            (" . implode(',', array_keys($cloneOcorrencia)) . ") VALUES 
            (" . sprintf("'%s'", implode("','", $cloneOcorrencia)) . ")";

    for ($i = 0; $i < $numeroDuplica; $i++) {
        if ($conn->exec($inserir)) {
            $sucesso = true;
        } else {
            $sucesso = false;
        }
    }

    if ($sucesso) {
        $mensagem = mensagem("success", "$numeroDuplica Ocorrência(s) replicada(s) com sucesso!");

    } else {
        $mensagem = mensagem("danger", "Erro ao gravar! Tente novamente.");
    }

}

$evento = recuperaDados('eventos', 'id', $idEvento);

$tipo_ocorrencia_id = $evento['tipo_evento_id'];

if (isset($_POST['carregar'])) {
    $idFilme = $_POST['idFilme'] ?? NULL;
    $idOrigem = $_POST['idOrigem'];
    unset($_SESSION['idOrigem']);
    $_SESSION['idOrigem'] = $idOrigem;
} else if (isset($_SESSION['idOrigem'])) {
    $idOrigem = $_SESSION['idOrigem'];
} else {
    $idOrigem = $_POST['idOrigem'] ?? $_POST['idOrigemModal'];
}

$sql = "SELECT o.id as idOco, l.local,
               o.atracao_id, o.instituicao_id,
               o.local_id, o.espaco_id,
               o.data_inicio, o.data_fim, 
               o.segunda, o.terca,
               o.quarta, o.quinta,
               o.sexta, o.sabado,
               o.domingo, o.horario_inicio,
               o.horario_fim, o.retirada_ingresso_id,
               o.valor_ingresso, o.observacao,
               o.periodo_id, o.subprefeitura_id,
               o.virada, o.libras, o.audiodescricao
         FROM ocorrencias as o
        INNER JOIN  locais as l ON o.local_id = l.id
        WHERE o.atracao_id = '$idOrigem' AND o.tipo_ocorrencia_id = '$tipo_ocorrencia_id' AND o.publicado = 1
        ORDER BY local, data_inicio, horario_inicio, horario_fim";

$query = mysqli_query($con, $sql);

$mensagem2 = mensagem("warning", "Há ocorrências duplicadas. Ocorrências destacadas com a mesma cor são idênticas!!")
?>

<div class="content-wrapper">
    <section class="content">

        <h2 class="page-header">Ocorrências</h2>
        <div class="row">
            <div class="col-md-2">
                <form method="POST" action="?perfil=evento&p=ocorrencia_cadastro">
                    <input type="hidden" name="idOrigem" value="<?= $idOrigem ?>">
                    <?php
                    if(isset($idFilme)){
                        echo "<input type='hidden' name='idFilme' value='" . $idFilme . "'>";
                    }
                    ?>
                    <button type="submit" class="btn btn-block btn-info"><i class="fa fa-plus"></i> Adiciona</button>
                </form>
            </div>

        </div>
        <br/>

        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Listagem</h3>
                    </div>
                    <div class="row" align="center">
                        <?php if (isset($mensagem)) {
                            echo $mensagem;
                        }; ?>
                    </div>
                    <div class="row" align="center" id="duplicated-message">

                    </div>
                    <div class="box-body">
                        <table id="tblOcorrencia" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Data início</th>
                                <th>Data final</th>
                                <th>Horario início</th>
                                <th>Horario final</th>
                                <th>Local</th>
                                <th>Datas de Exceçoes</th>
                                <th style='display:none'>atracao_id</th>
                                <th style='display:none'>instituicao_id</th>
                                <th style='display:none'>local_id</th>
                                <th style='display:none'>espaco_id</th>
                                <th style='display:none'>segunda</th>
                                <th style='display:none'>terca</th>
                                <th style='display:none'>quarta</th>
                                <th style='display:none'>quinta</th>
                                <th style='display:none'>sexta</th>
                                <th style='display:none'>sabado</th>
                                <th style='display:none'>domingo</th>
                                <th style='display:none'>horario_incio</th>
                                <th style='display:none'>horario_fim</th>
                                <th style='display:none'>retirada_ingresso_id</th>
                                <th style='display:none'>valor_ingresso</th>
                                <th style='display:none'>observacao</th>
                                <th style='display:none'>periodo_id</th>
                                <th style='display:none'>subprefeitura_id</th>
                                <th style='display:none'>virada</th>
                                <th style='display:none'>libras</th>
                                <th style='display:none'>audiodescricao</th>
                                <th>Editar</th>
                                <th>Replicar</th>
                                <th>Apagar</th>
                            </tr>
                            </thead>

                            <?php

                            echo "<tbody>";
                            while ($ocorrencia = mysqli_fetch_array($query)) {
                                if ($ocorrencia['data_fim'] != "0000-00-00") {
                                    $sqlExcecoes = "SELECT GROUP_CONCAT(DATE_FORMAT(data_excecao, '%d/%m/%Y') SEPARATOR ', ') AS 'excecoes' FROM ocorrencia_excecoes WHERE atracao_id = '{$ocorrencia['idOco']}'";
                                    $excecoes = $con->query($sqlExcecoes)->fetch_assoc()['excecoes'];
                                    $excecoes = $excecoes == null ? "Sem datas cadastradas" : $excecoes;
                                    $dataFim = exibirDataBr($ocorrencia['data_fim']);
                                } else {
                                    $excecoes = "Não é Temporada";
                                    $dataFim = "Não é Temporada";
                                }

                                echo "<tr class='content'>";
                                echo "<td>" . exibirDataBr($ocorrencia['data_inicio']) . "</td>";
                                echo "<td>" . $dataFim . "</td>";
                                echo "<td>" . exibirHora($ocorrencia['horario_inicio']) . "</td>";
                                echo "<td>" . exibirHora($ocorrencia['horario_fim']) . "</td>";
                                echo "<td>" . $ocorrencia['local'] . "</td>";
                                echo "<td>" . $excecoes . "</td>";
                                echo "<td style='display:none'>" . $ocorrencia['atracao_id'] . "</td>";
                                echo "<td style='display:none'>" . $ocorrencia['instituicao_id'] . "</td>";
                                echo "<td style='display:none'>" . $ocorrencia['espaco_id'] . "</td>";
                                echo "<td style='display:none'>" . $ocorrencia['segunda'] . "</td>";
                                echo "<td style='display:none'>" . $ocorrencia['terca'] . "</td>";
                                echo "<td style='display:none'>" . $ocorrencia['quarta'] . "</td>";
                                echo "<td style='display:none'>" . $ocorrencia['sabado'] . "</td>";
                                echo "<td style='display:none'>" . $ocorrencia['domingo'] . "</td>";
                                echo "<td style='display:none'>" . $ocorrencia['horario_inicio'] . "</td>";
                                echo "<td style='display:none'>" . $ocorrencia['retirada_ingresso_id'] . "</td>";
                                echo "<td style='display:none'>" . $ocorrencia['valor_ingresso'] . "</td>";
                                echo "<td style='display:none'>" . $ocorrencia['observacao'] . "</td>";
                                echo "<td style='display:none'>" . $ocorrencia['periodo_id'] . "</td>";
                                echo "<td style='display:none'>" . $ocorrencia['subprefeitura_id'] . "</td>";
                                echo "<td style='display:none'>" . $ocorrencia['virada'] . "</td>";
                                echo "<td style='display:none'>" . $ocorrencia['libras'] . "</td>";
                                echo "<td style='display:none'>" . $ocorrencia['audiodescricao'] . "</td>";

                                if(isset($idFilme)){
                                    echo "<td>
                                            <form method=\"POST\" action=\"?perfil=evento&p=ocorrencia_edita\" role=\"form\">
                                                <input type='hidden' name='idOcorrencia' value='" . $ocorrencia['idOco'] . "'>
                                                <input type='hidden' name='idFilme' value='" . $idFilme . "'>
                                                <button type=\"submit\" name='carregar' class=\"btn btn-block btn-primary\"><span class='glyphicon glyphicon-eye-open'></span></button>
                                            </form>
                                        </td>";
                                }else{
                                    echo "<td>
                                            <form method=\"POST\" action=\"?perfil=evento&p=ocorrencia_edita\" role=\"form\">
                                                <input type='hidden' name='idOcorrencia' value='" . $ocorrencia['idOco'] . "'> 
                                                <button type=\"submit\" name='carregar' class=\"btn btn-block btn-primary\"><span class='glyphicon glyphicon-eye-open'></span></button>
                                            </form>
                                          </td>";
                                }

                                echo "<td>
                                    <input type='hidden' name='idOcorrencia'>
                                    <button class='btn btn-block btn-info' data-toggle='modal' data-target='#duplicar' data-ocorrencia-id='" . $ocorrencia['idOco'] . "' data-tittle='Replicando ocorrência' data-message='Digite o número de vezes que deseja replicar a ocorrência: '><span class='glyphicon glyphicon-retweet'></span></button>
                                </td>";

                                echo "<td>
                                    <button class='btn btn-block btn-danger' data-toggle='modal' data-target='#apagar' data-id='" . $ocorrencia['idOco'] . "' data-tittle='Apagar ocorrência' data-message='Deseja mesmo pagar está ocorrências' onClick='setarIdOcorrencia(" . $ocorrencia['idOco'] . ")'><span class='glyphicon glyphicon-trash'></span></button>
                                  </td>";
                                echo "</tr>";
                            }
                            echo "</tbody>";
                            ?>
                            <tfoot>
                            <tr>
                                <th>Data início</th>
                                <th>Data final</th>
                                <th>Horario início</th>
                                <th>Horario final</th>
                                <th>Local</th>
                                <th>Datas de Exceçoes</th>
                                <th style='display:none'>atracao_id</th>
                                <th style='display:none'>instituicao_id</th>
                                <th style='display:none'>local_id</th>
                                <th style='display:none'>espaco_id</th>
                                <th style='display:none'>segunda</th>
                                <th style='display:none'>terca</th>
                                <th style='display:none'>quarta</th>
                                <th style='display:none'>quinta</th>
                                <th style='display:none'>sexta</th>
                                <th style='display:none'>sabado</th>
                                <th style='display:none'>domingo</th>
                                <th style='display:none'>horario_incio</th>
                                <th style='display:none'>horario_fim</th>
                                <th style='display:none'>retirada_ingresso_id</th>
                                <th style='display:none'>valor_ingresso</th>
                                <th style='display:none'>observacao</th>
                                <th style='display:none'>periodo_id</th>
                                <th style='display:none'>subprefeitura_id</th>
                                <th style='display:none'>virada</th>
                                <th style='display:none'>libras</th>
                                <th style='display:none'>audiodescricao</th>
                                <th colspan="3" width="15%"></th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="box-footer">
                        <button type="button" class="btn btn-default" id="voltar" name="voltar" onclick="window.history.back();">Voltar</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Duplicando ocorrencias -->
<div class="modal fade" id="duplicar" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id='formDuplicar' action="?perfil=evento&p=ocorrencia_lista" class="form-horizontal"
                  role="form">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Replicando ocorrência</h4>
                </div>
                <div class="modal-body">
                    <p>Digite o número de vezes que deseja replicar a ocorrência: </p>
                    <input type="number" min="1" max="10" name="numeroDuplica" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <input type='hidden' name='idProjeto'> <!-- vem pelo js -->
                    <input type='hidden' name='idOrigemModal' value="<?= $idOrigem ?>">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type='submit' class='btn btn-info btn-sm' name="duplicar">Confirmar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--Apagar ocorrência-->
<div class="modal fade" id="apagar" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id='formApagar' action="?perfil=evento&p=ocorrencia_lista" class="form-horizontal"
                  role="form">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Apagar ocorrência</h4>
                </div>
                <div class="modal-body">
                    <p>Deseja mesmo apagar esta ocorrência?</p>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="idOcorrenciaApaga">
                    <input type='hidden' name='idOrigemModal' value="<?= $idOrigem ?>">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type='submit' class='btn btn-info btn-sm' name="apagar">Confirmar</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script defer src="../visual/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script defer src="../visual/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
    $(function () {
        $('#tblOcorrencia').DataTable({
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

<script type="text/javascript">

    $('#duplicar').on('show.bs.modal', (e) => {
        document.querySelector('#formDuplicar input[name="idProjeto"]').value = e.relatedTarget.dataset.ocorrenciaId

    });

    function setarIdOcorrencia(valor) {
        document.querySelector('#formApagar input[name="idOcorrenciaApaga"]').value = valor;
    }


</script>

<script type="text/javascript">

    const menssagem = `<?=$mensagem2?>`;

    function generateRandomInt(max, min = 125) {
        return Math.floor(Math.random() * (max - min + 1) + min);
    }

    function colorGenerator() {

        const
            generatedColors = new Set();

        return () => {
            let randomColor;

            do {
                randomColor = `rgb(${generateRandomInt(255)},${generateRandomInt(255)},${generateRandomInt(255)})`;
            } while (generatedColors.has(randomColor));

            generatedColors.add(randomColor);

            return randomColor;
        };
    }

    function highlightDoubles(table) {

        var cont;
        cont = 0;
        const

            contentCells = table.querySelectorAll('.content'),

            contentMap = new Map();

        Array.from(contentCells).forEach(cell => {
            const
                array = (contentMap.has(cell.textContent))
                    ? contentMap.get(cell.textContent)
                    : [];

            array.push(cell)

            contentMap.set(cell.textContent, array);
        });

        const
            randomColor = colorGenerator();


        contentMap.forEach(cells => {

            if (cells.length < 2) {
                return;
            } else {
                cont++;
            }

            const
                color = randomColor();

            cells.forEach(cell => {
                cell.style.backgroundColor = color;
            });
        });

        if (cont > 0) {
            $("#duplicated-message").html(menssagem)
            /*$.post("?perfil=evento&p=includes&sp=validacoes",
                    {
                        duplicado: true
                    }

            )
            .done(() => {
                console.log("FOI");
            })
            .fail(() => {
                console.log("nao");
            })*/
        }
    }

    highlightDoubles(document.getElementById('tblOcorrencia'));


</script>

