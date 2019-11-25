<?php
include "includes/menu_principal.php";
//$id = $_POST['idCapac'];
$id = 1;

$bdc = bancoCapac();

$sql_evento = "SELECT * FROM eventos WHERE id = '$id'";
$query_evento = mysqli_query($bdc,$sql_evento);
$evento = mysqli_fetch_array($query_evento);

$sql_publico = "SELECT * FROM evento_publico INNER JOIN publicos p on evento_publico.publico_id = p.id WHERE evento_id = '$id'";
$query_publico = mysqli_query($bdc,$sql_publico);

$sql_atracao = "SELECT * FROM atracoes AS at INNER JOIN classificacao_indicativas AS cl ON at.classificacao_indicativa_id = cl.id WHERE evento_id = '$id' AND publicado = 1";
$query_atracao = mysqli_query($bdc,$sql_atracao);

$sql_pedido = "SELECT * FROM pedidos WHERE origem_id = '$id' AND origem_tipo_id = 1 AND publicado = 1";
$query_pedido = mysqli_query($bdc,$sql_pedido);
$pedido = mysqli_fetch_array($query_pedido);
?>
<div class="content-wrapper">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- /.col-md-6 -->
                <div class="col-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="m-0"><b>Dados do Evento</b></h3>
                        </div>
                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-12"><b>Nome do evento:</b> <?= $evento['nome_evento'] ?></div>
                            </div>
                            <div class="row">
                                <div class="col-md-6"><b>Espaço em que será realizado o evento é público?</b> <?php if ($evento['espaco_publico'] == 0): echo "Sim"; else: echo "Não"; endif;  ?></div>
                                <div class="col-md-6"><b>É fomento/programa?</b>
                                    <?php
                                    if($evento['fomento'] == 0){
                                        echo "Não";
                                    } else{
                                        echo "Sim: ".$evento['fomento_nome'];
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12"><b>Público (Representatividade e Visibilidade Sócio-cultural):</b>
                                    <?php
                                    while ($publicos = mysqli_fetch_array($query_publico)){
                                        echo $publicos['publico']."; ";
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12"><b>Sinopse:</b> <?= $evento['sinopse'] ?></div>
                            </div>
                            <br>
                            <!-- ************** Atrações ************** -->
                            <hr>
                            <h3><b>Atrações</b></h3>
                            <?php
                            while ($atracao = mysqli_fetch_array($query_atracao)){
                                ?>
                                <div class="row">
                                    <div class="col-md-12"><b>Nome da atração:</b> <?= $atracao['nome_atracao'] ?></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12"><b>Ações (Expressões Artístico-culturais):</b>
                                        <?php
                                        $idAtracao = $atracao['id'];
                                        $sql_acao = "SELECT * FROM acoes AS ac INNER JOIN acao_atracao aa on ac.id = aa.acao_id WHERE atracao_id = '$idAtracao'";
                                        $query_acao = mysqli_query($bdc,$sql_acao);

                                        while ($acao = mysqli_fetch_array($query_acao)){
                                            echo $acao['acao']."; ";
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12"><b>Ficha técnica completa:</b> <?= $atracao['ficha_tecnica'] ?></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12"><b>Integrantes:</b> <?= $atracao['integrantes'] ?></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12"><b>Classificação indicativa:</b> <?= $atracao['classificacao_indicativa'] ?></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12"><b>Release:</b>  <?= $atracao['release_comunicacao'] ?></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12"><b>Links:</b>  <?= $atracao['links'] ?></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6"><b>Quantidade de Apresentação:</b>  <?= $atracao['quantidade_apresentacao'] ?></div>
                                    <div class="col-md-6"><b>Valor:</b> R$ <?= dinheiroParaBr($atracao['valor_individual']) ?></div>
                                </div>
                                <div class="row">
                                    <?php
                                    $idProdutor = $atracao['produtor_id'];
                                    $sql_produtor = "SELECT * FROM produtores WHERE id = '$idProdutor'";
                                    $query_produtor = mysqli_query($bdc,$sql_produtor);
                                    $produtor = mysqli_fetch_array($query_produtor);
                                    ?>
                                    <div class="col-md-5"><b>Produtor:</b>  <?= $produtor['nome'] ?></div>
                                    <div class="col-md-3"><b>Telefone:</b>  <?= $produtor['telefone1'] ?> / <?= $produtor['telefone2'] ?? NULL ?></div>
                                    <div class="col-md-4"><b>E-mail:</b>  <?= $produtor['email'] ?></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4"><b>Observação:</b>  <?= $atracao->produtor->observacao ?? NULL ?></div>
                                </div>
                                <br>
                            <?php
                                if ($pedido['pessoa_tipo_id'] == 2){
                                    $sql_lider = "SELECT * FROM lideres AS li INNER JOIN pessoa_fisicas AS pf ON li.pessoa_fisica_id = pf.id LEFT JOIN drts ON pf.id = drts.pessoa_fisica_id WHERE atracao_id = '$idAtracao'";
                                    $query_lider = mysqli_query($bdc,$sql_lider);
                                    $lider = mysqli_fetch_array($query_lider);
                                    ?>
                                    <h5><b>Líder do grupo ou artista solo</b></h5>
                                    <div class="row">
                                        <div class="col-md-6"><b> Nome:</b> <?= $lider['nome'] ?></div>
                                        <div class="col-md-6"><b>Nome Artístico:</b> <?= $lider['nome_artistico'] ?></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3"><b>RG:</b> <?= $lider['rg'] ?></div>
                                        <div class="col-md-3"><b>CPF:</b> <?= $lider['cpf'] ?></div>
                                        <div class="col-md-6"><b>E-mail:</b> <?= $lider['email'] ?></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <b>Telefones:</b>
                                            <?= isset($lider['telefones']) ? implode(" | ", $lider['telefones']) : "" ?>
                                        </div>
                                        <div class="col-md-6"><b>DRT:</b> <?= $lider['drt'] ?></div>
                                    </div>
                                    <br>
                                    <br>
                            <?php
                                }
                            }
                            ?>
                            <!-- ************** Proponente ************** -->
                            <hr>
                            <h3><b>Proponente</b></h3>
                            <?php
                            if ($pedido['pessoa_tipo_id'] == 1){
                                $sql_pf = "SELECT * FROM pessoa_fisicas AS pf
                                    LEFT JOIN pf_enderecos pe on pf.id = pe.pessoa_fisica_id
                                    LEFT JOIN pf_bancos pb on pf.id = pb.pessoa_fisica_id
                                    LEFT JOIN drts d on pf.id = d.pessoa_fisica_id
                                    LEFT JOIN nits n on pf.id = n.pessoa_fisica_id
                                    LEFT JOIN nacionalidades n2 on pf.nacionalidade_id = n2.id
                                    LEFT JOIN bancos b on pb.banco_id = b.id
                                    LEFT JOIN pf_detalhes pd on pf.id = pd.pessoa_fisica_id
                                    LEFT JOIN etnias e on pd.etnia_id = e.id
                                    LEFT JOIN regiaos r on pd.regiao_id = r.id
                                    LEFT JOIN grau_instrucoes gi on pd.grau_instrucao_id = gi.id
                                    WHERE pf.id = '$id'";
                                $query_pf = mysqli_query($bdc,$sql_pf);
                                $pf = mysqli_fetch_array($query_pf);
                            ?>
                                <div class="row">
                                    <div class="col-md-6"><b> Nome:</b> <?= $pf['nome'] ?></div>
                                    <div class="col-md-6"><b>Nome Artístico:</b> <?= $pf['nome_artistico'] ?></div>
                                </div>
                                <div class="row">
                                    <?php
                                    if(!empty($pf['cpf'])){
                                        ?>
                                        <div class="col-md-2"><b>RG:</b> <?= $pf['rg'] ?></div>
                                        <div class="col-md-2"><b>CPF:</b> <?= $pf['cpf'] ?></div>
                                        <div class="col-md-2"><b>CCM:</b> <?= $pf['ccm'] ?></div>
                                        <?php
                                    }
                                    else{
                                        ?>
                                        <div class="col-md-6"><b>Passaporte:</b> <?= $pf['passaporte'] ?></div>
                                        <?php
                                    }
                                    ?>
                                    <div class="col-md-3"><b>Data de Nascimento:</b> <?= date("d/m/Y", strtotime($pf['data_nascimento'])) ?></div>
                                    <div class="col-md-3"><b>Naconalidade:</b> <?= $pf['nacionalidade'] ?></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4"><b>E-mail:</b> <?= $pf['email'] ?></div>
                                    <div class="col-md-6">
                                        <b>Telefones:</b>
                                        <?= isset($pf['telefones']) ? implode(" | ", $pf['telefones']) : "" ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6"><b>NIT:</b> <?= $pf['nit'] ?></div>
                                    <div class="col-md-6"><b>DRT:</b> <?= $pf['drt'] ?></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <b>Endereço:</b> <?= $pf['logradouro'] . ", " . $pf['numero'] . " " . $pf['complemento'] . " " . $pf['bairro'] . " - " . $pf['cidade'] . "-" . $pf['uf'] . " CEP: " . $pf['cep'] ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4"><b>Banco:</b> <?= $pf['banco'] ?></div>
                                    <div class="col-md-4"><b>Agência:</b> <?= $pf['agencia'] ?></div>
                                    <div class="col-md-4"><b>Conta:</b> <?= $pf['conta'] ?></div>
                                </div>
                            <?php
                            }
                            else{
                                $sql_pj = "SELECT * FROM pessoa_juridicas AS pj
                                    LEFT JOIN pj_enderecos pe on pj.id = pe.pessoa_juridica_id
                                    LEFT JOIN pj_bancos pb on pj.id = pb.pessoa_juridica_id
                                    LEFT JOIN bancos bc on pb.banco_id = bc.id
                                    WHERE pj.id = '$id'";
                                $query_pj = mysqli_query($bdc,$sql_pj);
                                $pj = mysqli_fetch_array($query_pj);
                                $idRep1 = $pj['representante_legal1_id'];
                                $sql_rep1 = "SELECT * FROM representante_legais WHERE id = '$idRep1'";
                                $query_rep1 = mysqli_query($bdc,$sql_rep1);
                                $rep1 = mysqli_fetch_array($query_rep1);

                                $sql_tel = "SELECT * FROM pj_telefones WHERE pessoa_juridica_id = '$id'";
                                $query_tel = mysqli_query($bdc,$sql_tel);
                                ?>
                                <div class="row">
                                    <div class="col-md-7"><b>Razão Social:</b> <?= $pj['razao_social'] ?></div>
                                    <div class="col-md-3"><b>CNPJ:</b> <?= $pj['cnpj'] ?></div>
                                    <div class="col-md-2"><b>CCM:</b> <?= $pj['ccm'] ?></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6"><b>E-mail:</b> <?= $pj['email'] ?></div>
                                    <div class="col-md-6"><b>Telefones:</b>
                                        <?php
                                        while ($telefones = mysqli_fetch_array($query_tel)){
                                            echo $telefones['telefone']." | ";
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <b>Endereço:</b> <?= $pj['logradouro'] . ", " . $pj['numero'] . " " . $pj['complemento'] . " " . $pj['bairro'] . " - " . $pj['cidade'] . "-" . $pj['uf'] . " CEP: " . $pj['cep'] ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4"><b>Banco:</b> <?= $pj['banco'] ?></div>
                                    <div class="col-md-4"><b>Agência:</b> <?= $pj['agencia'] ?></div>
                                    <div class="col-md-4"><b>Conta:</b> <?= $pj['conta'] ?></div>
                                </div>
                                <br/>
                                <h5><b>Representante Legal</b></h5>
                                <div class="row">
                                    <div class="col-md-6"><b>Nome:</b> <?= $rep1['nome'] ?></div>
                                    <div class="col-md-3"><b>RG:</b> <?= $rep1['rg'] ?></div>
                                    <div class="col-md-3"><b>CFP:</b> <?= $rep1['cpf'] ?></div>
                                </div>
                                <br>
                                <?php
                                if ($pj['representante_legal2_id']){
                                    $idRep2 = $pj['representante_legal2_id'];
                                    $sql_rep2 = "SELECT * FROM representante_legais WHERE id = '$idRep2'";
                                    $query_rep2 = mysqli_query($bdc,$sql_rep2);
                                    $rep2 = mysqli_fetch_array($query_rep2);
                                    ?>
                                    <div class="row">
                                        <div class="col-md-6"><b>Nome:</b> <?= $rep2['nome'] ?></div>
                                        <div class="col-md-3"><b>RG:</b> <?= $rep2['rg'] ?></div>
                                        <div class="col-md-3"><b>CFP:</b> <?= $rep2['cpf'] ?></div>
                                    </div>
                                    <br>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <div class="table-responsive list_info"><br>
                                    <?php
                                    //lista arquivos de determinado pedido
                                    $sql = "SELECT * FROM arquivos as arq
                                    INNER JOIN lista_documentos ld on arq.lista_documento_id = ld.id
                                    WHERE arq.origem_id = '$id' 
                                    AND arq.publicado = '1' ORDER BY arq.lista_documento_id, arq.id";
                                    $query = mysqli_query($bdc, $sql);
                                    $linhas = mysqli_num_rows($query);

                                    if ($linhas > 0) {
                                        echo "
                                    <table class='table text-center table-striped table-bordered table-condensed'>
                                        <thead>
                                            <tr class='bg-info text-bold'>
                                                <td>Tipo de documento</td>
                                                <td>Nome do documento</td>
                                                <td>Data de envio</td>
                                            </tr>
                                        </thead>
                                        <tbody>";
                                        while ($arquivo = mysqli_fetch_array($query)) {
                                            echo "<tr>";
                                            echo "<td>".$arquivo['documento']."</td>";
                                            echo "<td class='list_description'><a href='../uploadsdocs/" . $arquivo['arquivo'] . "' target='_blank'>" . mb_strimwidth($arquivo['arquivo'], 15, 50, "...") . "</a></td>";
                                            echo "<td class='list_description'>(" . exibirDataBr($arquivo['data']) . ")</td>";
                                            echo "</tr>";
                                        }
                                        echo "
                                        </tbody>
                                        </table>";
                                    } else {
                                        echo "<p>Não há listas disponíveis no momento.<p/><br/>";
                                    }

                                    ?>
                                </div>
                                <hr/>
                            </div>
                            <!--.modal-->
                            <div id="exclusao" class="modal modal-danger modal fade in" role="dialog">
                                <div class="modal-dialog">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;
                                            </button>
                                            <h4 class="modal-title">Confirmação de Exclusão</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p>Tem certeza que deseja excluir este arquivo?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <form action="?perfil=evento&p=arqs_com_prod"
                                                  method="post">
                                                <input type="hidden" name="idArquivo" id="idArquivo"
                                                       value="">
                                                <input type="hidden" name="apagar" id="apagar">
                                                <button type="button" class="btn btn-default pull-left"
                                                        data-dismiss="modal">Cancelar
                                                </button>
                                                <input class="btn btn-danger btn-outline" type="submit"
                                                       name="excluir" value="Apagar">
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--  Fim Modal de Upload de arquivo  -->
                        </div>
                        <div class="row">
                            <div class="col-md-offset-3 col-md-6">
                                <form class="form-horizontal" method="POST" action="?perfil=evento&p=importar_capac" role="form">
                                    <input type="hidden" name="idCapac" value="<?= $id ?>">
                                    <button type="submit" class="btn btn-success btn-block float-right" >Importar</button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- /.col-md-6 -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
