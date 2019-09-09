<?php
$con = bancoMysqli();
$idEC = $_POST['idECView'];
$sql = "SELECT c.id, 
        c.protocolo AS 'protocolo',
		p.nome AS 'nome',
        c.ano  AS 'ano',
        s.status AS 'status',
        l.local AS 'local',
        ec.cargo AS 'cargo',
        ev.ano AS 'vigencia',
        c.cronograma AS 'cronograma',
        c.observacao AS 'obs',
        fiscal.nome_completo AS 'fiscal',
        suplente.nome_completo AS 'suplente',
        c.num_processo_pagto AS 'num_pgt',
		c.publicado AS 'publicado'
        FROM emia_contratacao as c
        INNER JOIN pessoa_fisicas AS p ON p.id = c.pessoa_fisica_id
        INNER JOIN emia_status as s ON s.id = c.emia_status_id
        INNER JOIN locais as l ON l.id = c.local_id
        INNER JOIN emia_cargos as ec ON ec.id = c.emia_cargo_id
        INNER JOIN emia_vigencias AS ev ON ev.id = c.emia_vigencia_id
        INNER JOIN usuarios AS fiscal ON fiscal.id = c.fiscal_id
        INNER JOIN usuarios AS suplente ON suplente.id = c.suplente_id
        WHERE c.id = '$idEC' AND c.publicado = '1'";
$ec = $con->query($sql)->fetch_assoc();
?>
<div class="content-wrapper">
    <section class="content">
        <div class="page-header">
            <h2 class="page-title">EMIA - Dados para contratação</h2>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Detalhes da contratação selecionada</h3>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th width="30%">Protocolo: </th>
                            <td><?php if($ec['protocolo'] == null){
                                    echo "Não possui";
                                }else{
                                    echo $ec['protocolo'];
                                }
                                ?></td>
                        </tr>

                        <tr>
                            <th width="30%">Pessoa Física: </th>
                            <td><?=$ec['nome']?></td>
                        </tr>

                        <tr>
                            <th width="30%">Status: </th>
                            <td><?=$ec['status']?></td>
                        </tr>

                        <tr>
                            <th width="30%">Local: </th>
                            <td><?=$ec['local']?></td>
                        </tr>

                        <tr>
                            <th width="30%">Cargo: </th>
                            <td><?=$ec['cargo']?></td>
                        </tr>

                        <tr>
                            <th width="30%">Ano: </th>
                            <td><?=$ec['ano']?></td>
                        </tr>

                        <tr>
                            <th width="30%">Cronograma: </th>
                            <td><?=$ec['cronograma']?></td>
                        </tr>

                        <tr>
                            <th width="30%">Observação: </th>
                            <td><?=$ec['obs']?></td>
                        </tr>

                        <tr>
                            <th width="30%">Fiscal: </th>
                            <td><?=$ec['fiscal']?></td>
                        </tr>

                        <tr>
                            <th width="30%">Suplente: </th>
                            <td><?=$ec['suplente']?></td>
                        </tr>

                        <tr>
                            <th width="30%">Numero do Processo de Pagamento: </th>
                            <td><?php if($ec['num_pgt'] == null){
                                    echo "Não Cadastrado";
                                }else{
                                    echo $ec['protocolo'];
                                }
                                ?></td>
                        </tr>

                        <tr>
                            <th width="30%">Publicado? </th>
                            <td><?=$ec['publicado'] ? "Sim" : "Não" ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="box-footer">
                <form action="?perfil=emia&p=dados_contratacao&sp=listagem" method="post">
                    <a href="?perfil=emia&p=dados_contratacao&sp=listagem">
                        <button type="button" class="btn btn-default">Voltar</button>
                    </a>
                    <input type="hidden" name="idDados" id="idDados" value="<?= $ec['id'] ?>">
                    <button type="button" class="btn btn-danger pull-right" id="despublica"
                            data-toggle="modal" data-target="#despublicacao" name="despublica"
                            data-id="<?= $ec['id'] ?>">
                        Despublicar
                    </button>
                </form>
            </div>
        </div>
<div id="despublicacao" class="modal modal-danger modal fade in" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <form action="?perfil=emia&p=dados_contratacao&sp=listagem" method="post">
                    <h4 class="modal-title">Confirmação de Despublicação</h4>
            </div>
            <div class="modal-body">
                <label>Tem certeza que deseja despublicar?</label>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="idDados" id="idDados" value="<?= $ec['id']?>">
                <input type="hidden" name="despublicar" id="despublicar">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar
                </button>
                <input type="submit" class="btn btn-danger btn-outline" name="despublica" value="Despublicar">
                </form>
            </div>
        </div>
    </div>
</div>
</section>
</div>