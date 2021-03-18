<?php
$con = bancoMysqli();

$link_api_locais_instituicoes = 'http://' . $_SERVER['HTTP_HOST'] . '/siscontrat2/funcoes/api_listar_locais_instituicoes.php';

if (isset($_POST['busca'])) {
    $protocolo = $_POST['protocolo'] ?? NULL;
    $numProcesso = $_POST['numProcesso'] ?? NULL;
    $nomeEvento = $_POST['nomeEvento'] ?? NULL;
    $usuario = $_POST['usuario'] ?? NULL;
    $projeto = $_POST['projeto'] ?? NULL;
    $valorInicial = dinheiroDeBr($_POST['valor_inicial']) ?? NULL;
    $valorFinal = dinheiroDeBr($_POST['valor_final']) ?? NULL;

    $sqlProtocolo = '';
    $sqlNomeEvento = '';
    $sqlProjeto = '';
    $sqlUsuario = '';
    $sqlValorInicial = '';
    $sqlValorFinal = '';
    $sqlValor = '';
    $sqlProcesso = '';

    if ($protocolo != null)
        $sqlProtocolo = " AND e.protocolo LIKE '%$protocolo%'";
    if ($nomeEvento != null)
        $sqlNomeEvento = " AND e.nome_evento LIKE '%$nomeEvento%'";
    if ($projeto != null && $projeto != 0)
        $sqlProjeto = " AND e.projeto_especial_id = '$projeto'";
    if ($usuario != null && $usuario != 0)
        $sqlUsuario = " AND fiscal_id = '$usuario' OR suplente_id = '$usuario' OR usuario_id = '$usuario'";
    if ($valorInicial != NULL && $valorInicial != 0) {
        if ($valorFinal != NULL && $valorFinal != 0){
            $sqlValor = " AND p.valor_total between '$valorInicial' AND '$valorFinal'";
        } else {
            $sqlValor = " AND p.valor_total >= '$valorInicial'";
        }
    } elseif ($valorFinal != NULL && $valorFinal != 0){
        $sqlValor = " AND p.valor_total <= '$valorFinal'";
    }else {
        $sqlValor = "";
    }
    if ($numProcesso != null)
        $sqlProcesso = "AND p.numero_processo LIKE '%$numProcesso%'";

    $sql = "SELECT e.id, e.protocolo, 
                   p.numero_processo, p.pessoa_tipo_id,
                   p.pessoa_fisica_id, p.pessoa_juridica_id,
                   e.nome_evento, p.valor_total, es.status
    FROM eventos e 
    INNER JOIN pedidos p on e.id = p.origem_id 
    INNER JOIN evento_status AS es ON e.evento_status_id = es.id
    WHERE e.publicado = 1 
    AND p.publicado = 1 
    AND p.status_pedido_id NOT IN (1,3,20,21)
    AND p.status_pedido_id != 1
    AND p.status_pedido_id != 3
    AND e.evento_status_id != 1
    $sqlProjeto $sqlUsuario $sqlValor
    $sqlProtocolo $sqlNomeEvento $sqlProcesso
    GROUP BY e.id";

    $resultado = $con->query($sql);
}

$http = "http://" . $_SERVER['SERVER_NAME'] . "/siscontrat2/pdf/resumo_evento.php?id=";
?>
<div class="content-wrapper">
    <section class="content">
        <h3 class="box-title">Resultado de busca</h3>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Listagem</h3>
                    </div>
                    <div class="box-body">
                        <table id="tblResultado" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Protocolo</th>
                                <th>Processo</th>
                                <th>Proponente</th>
                                <th>Tipo</th>
                                <th>Nome do evento</th>
                                <th>Local</th>
                                <th>Período</th>
                                <th>Valor</th>
                                <th>Status</th>
                                <th>Chamados</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            while ($evento = mysqli_fetch_array($resultado)) {
                                if ($evento['pessoa_tipo_id'] == 1) {
                                    $tipo = "Física";
                                    $pessoa = recuperaDados('pessoa_fisicas', 'id', $evento['pessoa_fisica_id'])['nome'];
                                } else {
                                    $tipo = "Jurídica";
                                    $pessoa = recuperaDados('pessoa_juridicas', 'id', $evento['pessoa_juridica_id'])['razao_social'];
                                }
                                //?perfil=pesquisa&p=resumo
                                ?>
                                <tr>
                                    <td>
                                        <form action="?perfil=pesquisa&p=resumo" method="POST">
                                            <input type="hidden" name="idEvento" value="<?= $evento['id'] ?>">
                                            <button type="submit" name="carregar" id="carregar"
                                                    class="btn btn-link"><?= $evento['protocolo'] ?></button>
                                        </form>
                                    </td>
                                    <td><?= checaCampo($evento['numero_processo']) ?></td>
                                    <td><?= $pessoa ?></td>
                                    <td><?= $tipo ?></td>
                                    <td><?= $evento['nome_evento'] ?></td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-block" id="exibirLocais"
                                                data-toggle="modal" data-target="#modalLocais_Inst" data-name="local"
                                                onClick="exibirLocal_Instituicao('<?=$link_api_locais_instituicoes?>', '#modalLocais_Inst', '#modalTitulo')"
                                                data-id="<?= $evento['id'] ?>"
                                                name="exibirLocais">
                                            Ver locais
                                        </button>
                                    </td>
                                    <td><?= retornaPeriodoNovo($evento['id'], 'ocorrencias') ?></td>
                                    <td><?= "R$" . dinheiroParaBr($evento['valor_total']) ?></td>
                                    <td><?= $evento['status'] ?></td>
                                    <?= retornaChamadosTD($evento['id']) ?>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Protocolo</th>
                                <th>Processo</th>
                                <th>Proponente</th>
                                <th>Tipo</th>
                                <th>Nome do evento</th>
                                <th>Local</th>
                                <th>Período</th>
                                <th>Valor</th>
                                <th>Status</th>
                                <th>Chamados</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script defer src="../visual/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script defer src="../visual/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
    $(function () {
        $('#tblResultado').DataTable({
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