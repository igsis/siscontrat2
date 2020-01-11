<?php
$sqlAtracoes = "SELECT id FROM atracoes WHERE evento_id = '$idEvento' AND publicado = '1'";
$atracoes = $con->query($sqlAtracoes)->fetch_all(MYSQLI_ASSOC);

$musica = false;
$oficina = false;
$teatro = false;
$edital = false;

foreach ($atracoes as $atracao) {
    $sqlAcao = "SELECT acao_id FROM acao_atracao WHERE atracao_id = '{$atracao['id']}'";
    $acoes = $con->query($sqlAcao)->fetch_all(MYSQLI_ASSOC);
    foreach ($acoes as $acao) {
        switch ($acao['acao_id']) {
            case 7:
                $musica = true;
                break;

            case 8;
                $oficina = true;
                break;

            case 11:
                $teatro = true;
                break;

            default:
                break;
        }
    }
}

if ($musica) { $whereAdicional[] = "musica = '1'"; }
if ($oficina) { $whereAdicional[] = "oficina = '1'"; }
if ($teatro) { $whereAdicional[] = "teatro = '1'"; }
if ($edital) { $whereAdicional[] = "edital = '1'"; }

// $campo = recuperaPessoa($_REQUEST['idPessoa'],$_REQUEST['tipoPessoa']);

?>

<div class="card">
    <div class="card-body">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Arquivos anexados</h3>
            </div>
            <div class="box-body">
                <?php
                //lista arquivos de determinado pedido
                $sql = "SELECT * 
                                            FROM lista_documentos as list
                                            INNER JOIN arquivos as arq ON arq.lista_documento_id = list.id
                                            WHERE arq.origem_id = '$idPedido' AND list.tipo_documento_id = 3
                                            AND arq.publicado = '1' ORDER BY arq.id";
                $query = mysqli_query($con, $sql);
                $linhas = mysqli_num_rows($query);

                if ($linhas > 0) {
                    ?>
                    <table class='table text-center table-striped table-bordered table-condensed'>
                        <thead>
                        <tr class='bg-info text-bold'>
                            <td>Tipo de arquivo</td>
                            <td>Nome do documento</td>
                            <td>Data de envio</td>
                            <td width='15%'></td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        while ($arquivo = mysqli_fetch_array($query)) {
                            ?>
                            <tr>
                                <td class='list_description'><?= $arquivo['documento'] ?></td>
                                <td class='list_description'><a href='../uploadsdocs/<?= $arquivo['arquivo'] ?>'
                                                                target='_blank'>
                                        <?= mb_strimwidth($arquivo['arquivo'], 15, 25, "...") ?></a>
                                </td>
                                <td class='list_description'>(<?= exibirDataBr($arquivo['data']) ?>)</td>
                                <td class='list_description'>
                                    <form id='formExcliuir' method='POST'>
                                        <button class='btn btn-danger glyphicon glyphicon-trash' type='button'
                                                data-toggle='modal' data-target='#exclusao'
                                                data-nome='<?= $arquivo['arquivo'] ?>'
                                                data-id='<?= $arquivo['id'] ?>'>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                    <?php
                } else {
                    ?>
                    <p>Não há listas disponíveis no momento.</p><br/>
                    <?php
                }
                ?>
            </div>
        </div>
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Enviar Arquivos</h3>
            </div>
            <div class="box-body">
                <h4 class="text-center">Nesta página, você envia documentos digitalizados. O tamanho máximo do arquivo deve ser 5MB.</h4>

                <form  method="POST" enctype="multipart/form-data" action="?perfil=evento&p=pedido_edita&label=anexos" role="form" data-etapa="Anexos do Pedido">
                    <table class="table text-center table-striped">
                        <tbody>
                            <?php
                            $evento = recuperaDados('eventos', 'id', $idEvento);
                            if($evento['tipo_evento_id'] == 1) {
                                if ($musica || $oficina || $teatro) {
                                    $sqlAdicional = "AND (".implode(" OR ", $whereAdicional).")";
                                } else
                                    $sqlAdicional = "";
                                $sql_arquivos = "SELECT * FROM lista_documentos WHERE tipo_documento_id = '3' and publicado = 1 $sqlAdicional";
                            } else {
                                $sql_arquivos = "SELECT * FROM lista_documentos WHERE tipo_documento_id = '3' and publicado = 1 AND (musica = 1 AND teatro = 1 AND oficina = 1 AND documento NOT LIKE '%Pessoa Jurídica%')";
                            }
                            $query_arquivos = mysqli_query($con,$sql_arquivos);
                            while($arq = mysqli_fetch_array($query_arquivos))
                            {
                                $idDoc = $arq['id'];
                                $sqlExistentes = "SELECT * FROM arquivos WHERE lista_documento_id = '$idDoc' AND origem_id = '$idPedido' AND publicado = 1";
                                $queryExistentes = mysqli_query($con, $sqlExistentes);

                                if (mysqli_num_rows($queryExistentes) == 0) {
                                    ?>
                                    <tr>
                                        <td>
                                            <label><?php echo $arq['documento'] ?></label>
                                        </td>
                                        <td>
                                            <input type='file' name='arquivo[<?php echo $arq['sigla']; ?>]'>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                    <br>
                    <?php
                    $num_lista = mysqli_num_rows($query_arquivos);
                    $num_arquivos = $con->query("SELECT * FROM arquivos WHERE lista_documento_id IN (SELECT id FROM lista_documentos WHERE tipo_documento_id = '3' and publicado = 1) AND origem_id = '$idPedido' AND publicado = 1")->num_rows;
                    $num_total = $num_lista - $num_arquivos;
                    if($num_total != 0) {
                        ?>
                        <input type='hidden' name='idPedido' value='<?= $idPedido ?>'/>
                        <input type="hidden" name="tipoPessoa"
                               value="3"/>
                        <input type="submit" class="btn btn-primary btn-lg btn-block"
                               name="enviarArquivo" value='Enviar'>
                        <?php
                    }
                    ?>
                </form>
            </div>
        </div>
    </div>
</div>
<ul class="list-inline pull-right">
    <li>
        <a class="btn btn-default prev-step"><span
                    aria-hidden="true">&larr;</span>
            Voltar</a>
    </li>
    <li>
        <a class="btn btn-primary next-step">Próxima etapa <span
                    aria-hidden="true">&rarr;</span></a>
    </li>
</ul>

<!--.modal-->
<div id="exclusao" class="modal modal-danger modal fade in" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Confirmação de Exclusão</h4>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir este arquivo?</p>
            </div>
            <div class="modal-footer">
                <form method="POST" action="?perfil=evento&p=pedido_edita&label=anexos" role="form" data-etapa="Envio de Arquivos">
                    <input type="hidden" name="idArquivo" id="idArquivo" value="">
                    <input type="hidden" name="tipoPessoa" id="tipoPessoa" value="">
                    <input type="hidden" name="idPedido" id="idPedido" value="<?=$idPedido?>">
                    <input type="hidden" name="apagarArquivo" id="apagar">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar
                    </button>
                    <input class="btn btn-danger btn-outline" type="submit" name="excluir" value="Apagar">
                </form>
            </div>
        </div>
    </div>
</div>
<!--  Fim Modal de Upload de arquivo  -->

<script type="text/javascript">
    $('#exclusao').on('show.bs.modal', function (e) {
        let nome = $(e.relatedTarget).attr('data-nome');
        let id = $(e.relatedTarget).attr('data-id');
        let pessoa = $(e.relatedTarget).attr('data-pessoa');

        $(this).find('p').text(`Tem certeza que deseja excluir o arquivo ${nome} ?`);
        $(this).find('#idArquivo').attr('value', `${id}`);
        $(this).find('#tipoPessoa').attr('value', `${pessoa}`);

    })
</script>