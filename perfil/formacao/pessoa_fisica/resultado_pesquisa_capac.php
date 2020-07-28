<?php
    $con = bancoCapacAntigo();

    if (isset($_POST['busca'])){
        $codigo = $_POST['cod_capac'] ?? null;
        $ano = $_POST['inscricao'] ?? null;
        $proponente = $_POST['proponente'] ?? null;
        $programacao = $_POST['programa'] ?? null;
        $funcao = $_POST['funcao'] ?? null;
        $linguagem = $_POST['linguagem'] ?? null;
        $regiao = $_POST['regiao'] ?? null;

        $condicoes = [];

        if (!empty($codigo))
        {
            $condicoes[] = "pf.id = '$codigo'";
        }
        if(!(empty($proponente)))
        {
            $condicoes[] = "pf.nome LIKE '%$proponente%'";
        }
        if(!(empty($programa)))
        {
            $condicoes[] = "pf.tipo_formacao_id = '$programa'";
        }
        if(!(empty($ano)))
        {
            $condicoes[] = "pf.formacao_ano = '$ano'";
        }

        if(!(empty($funcao)))
        {
            $condicoes[] = "pf.formacao_funcao_id = '$funcao'";
        }

        if(!(empty($linguagem)))
        {
            $condicoes[] = "pf.formacao_linguagem_id = '$linguagem'";
        }

        if(!(empty($regiao)))
        {
            $condicoes[] = "pf.formacao_regiao_preferencial = '$regiao'";
        }

        if ($ano > 2019) {
            $innerRegiao['regiao'] = ",r.regiao";
            $innerRegiao['innerJoin'] = "LEFT JOIN regioes as r on pf.formacao_regiao_preferencial = r.id";
        } else {
            $innerRegiao['regiao'] = "";
            $innerRegiao['innerJoin'] = "";
        }

        $query = "SELECT
                  pf.id,
                  pf.nome,
                  pf.nomeArtistico,
                  pf.dataNascimento,
                  pf.tipo_formacao_id,
                  pf.formacao_funcao_id,
                  pf.formacao_linguagem_id,
                  pf.formacao_regiao_preferencial
                  {$innerRegiao['regiao']}
                FROM pessoa_fisica AS pf
                       INNER JOIN tipo_formacao as tf ON pf.tipo_formacao_id = tf.id
                       INNER JOIN formacao_linguagem as fl ON pf.formacao_linguagem_id = fl.id
                       INNER JOIN formacao_funcoes as ff ON pf.formacao_funcao_id = ff.id
                       {$innerRegiao['innerJoin']}
                       INNER JOIN (SELECT pessoa_fisica_id FROM formacao_validacao WHERE validado = 1) AS fv ON fv.pessoa_fisica_id = pf.id";

        $sql = $query;

        if (count($condicoes) > 0){
            $sql .= " WHERE " . implode(' AND ', $condicoes) . " AND pf.formacao_funcao_id IS NOT NULL AND pf.publicado = '1'";
        }else{
            $sql .= " WHERE pf.formacao_funcao_id IS NOT NULL AND pf.publicado = '1'";
        }

        $queryLista = mysqli_query($con,$sql);
    }
    else{
        echo "<script>window.location = '?perfil=formacao&p=pessoa_fisica&sp=pesquisa_capac&erro=1';</script>";
    }
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <!-- START FORM-->
        <h2 class="page-header">Formação</h2>

        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Pesquisa pessoa fisica capac</h3>
                    </div>

                    <div class="row" align="center">
                        <?php if (isset($mensagem)) {
                            echo $mensagem;
                        }; ?>
                    </div>

                    <!-- /.box-header -->
                    <div class="box-body">
                        <?= var_dump($_POST) ?>
                        <table id="tblEvento" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Nome</th>
                                    <th>Nome Artístico</th>
                                    <th>Data de Nascimento</th>
                                    <th>Programa</th>
                                    <th>Função</th>
                                    <th>Linguagem</th>
                                    <th>Região Preferencial</th>
                                    <th>Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            while ($linha = mysqli_fetch_assoc($queryLista))
                            {
                                $formacao = recuperaDadosCapac('tipo_formacao', 'id', $linha['tipo_formacao_id']);
                                $funcao = recuperaDadosCapac('formacao_funcoes', 'id', $linha['formacao_funcao_id']);
                                $linguagem = recuperaDadosCapac('formacao_linguagem', 'id', $linha['formacao_linguagem_id']);
                                ?>
                                <tr>
                                    <td><?= $linha['id'] ?></td>
                                    <td><?= $linha['nome'] ?></td>
                                    <td><?= $linha['nomeArtistico'] ?></td>
                                    <td><?= exibirDataBr($linha['dataNascimento']) ?></td>
                                    <td><?= $formacao != null ? $formacao['descricao'] : '' ?></td>
                                    <td><?= $funcao != null ? $funcao['funcao'] : '' ?></td>
                                    <td><?=  $linguagem != null ? $linguagem['linguagem'] : '' ?></td>
                                    <td><?= !isset($linha['regiao']) || $linha['regiao'] == null ? "Não Cadastrado" : $linha['regiao'] ?></td>
                                    <td>
                                        <a class='btn btn-primary' href='?perfil=formacao&p=pessoa_fisica&sp=detalhes_pf_capac&id_capac=<?=$linha['id']?>'>Carregar</a>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Código</th>
                                    <th>Nome</th>
                                    <th>Nome Artístico</th>
                                    <th>Data de Nascimento</th>
                                    <th>Programa</th>
                                    <th>Função</th>
                                    <th>Linguagem</th>
                                    <th>Região Preferencial</th>
                                    <th>Ação</th>
                                </tr>
                            </tfoot>
                        </table>
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

<script defer src="../visual/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script defer src="../visual/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
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

<!--<script>-->
<!--    $('#exibirMotivo').click(function () {-->
<!--        $('#exibicao').modal('show');-->
<!--        let nome = $(this).attr('data-name');-->
<!---->
<!--        console.log(nome);-->
<!--    })-->
<!--</script>-->

<script>
    const url = `<?=$url?>`;

    $('#exibicao').on('show.bs.modal', function (e) {
        let nome = $(e.relatedTarget).attr('data-name');
        let id = $(e.relatedTarget).attr('data-id');
        $(this).find('p').html(`<strong>Nome do Evento:</strong> ${nome}`);

        $('#exibicao').find('#conteudoModal').empty();

        // @TODO: Melhorar esse código
        $.getJSON(url + "?idEvento=" + id, function (data) {
            $.each(data, function (key, value) {
                $.each(value, function (key, valor) {
                    $('#exibicao').find('#conteudoModal').append(`<td>${valor}</td>`);
                    console.log(key + ": " + valor);
                })
            })
        })

        //let operador = <?//=$chamado['nome_completo']?>//;
        //let data = <?//=$chamado['data']?>//;

        // $(this).find('#conteudoModal').append(`<td>${motivo}</td>`);
    })
</script>

<script type="text/javascript">
    $('#exclusao').on('show.bs.modal', function (e) {
        let evento = $(e.relatedTarget).attr('data-name');
        let id = $(e.relatedTarget).attr('data-id');

        $(this).find('p').text(`Tem certeza que deseja excluir o evento ${evento} ?`);
        $(this).find('#idEvent').attr('value', `${id}`);
    })
</script>