<?php
$con = bancoMysqli();
$idPC = $_POST['idPC'];

$sql = "SELECT f.id AS 'id', 
               f.ano AS 'ano',
               f.chamado AS 'chamado',
               cla.classificacao_indicativa AS 'classificacao',
               t.territorio AS 'territorio',
               c.coordenadoria AS 'coordenadoria',
               s.subprefeitura AS 'subprefeitura',
               prog.programa AS 'programa',
               l.linguagem AS 'linguagem',
               proj.projeto AS 'projeto',
               fc.cargo AS 'cargo',
               fv.ano AS 'vigencia',
               f.observacao AS 'observacao',
               fiscal.nome_completo AS 'fiscal',
               suplente.nome_completo AS 'suplente',       
               f.num_processo_pagto AS 'numpgt',
               f.publicado AS 'publicado'
        FROM formacao_contratacoes AS f 
        INNER JOIN classificacao_indicativas AS cla ON f.classificacao = cla.id
        INNER JOIN territorios AS t ON f.territorio_id = t.id
        INNER JOIN coordenadorias AS c ON f.coordenadoria_id = c.id
        INNER JOIN subprefeituras AS s ON f.subprefeitura_id = s.id
        INNER JOIN programas AS prog ON f.programa_id = prog.id
        INNER JOIN linguagens AS l ON f.linguagem_id = l.id
        INNER JOIN projetos AS proj ON f.projeto_id = proj.id
        INNER JOIN formacao_cargos fc ON f.form_cargo_id = fc.id
        INNER JOIN formacao_vigencias fv ON f.form_vigencia_id = fv.id
        INNER JOIN usuarios AS fiscal ON f.fiscal_id = fiscal.id
        INNER JOIN usuarios AS suplente ON f.suplente_id = suplente.id 
        WHERE f.id = '$idPC'";
$fc = $con->query($sql)->fetch_assoc();

?>
<div class="content-wrapper">
    <section class="content">
        <div class="box">
            <div class="box-header">
                <h2 class="box-title">Contratação Selecionada</h2>
            </div>
        </div>
        <div class="box">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"> Dados da Contratação</h3>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th width="30%">Ano:</th>
                                <td><?= $fc['ano'] ?></td>
                            </tr>
                            <tr>
                                <th width="30%">Chamado:</th>
                                <td><?= $fc['chamado'] ?></td>
                            </tr>
                            <tr>
                                <th width="30%">Classificacao Indicativa:</th>
                                <td><?= $fc['classificacao'] ?></td>
                            </tr>
                            <tr>
                                <th width="30%">Território:</th>
                                <td><?= $fc['territorio'] ?></td>
                            </tr>
                            <tr>
                                <th width="30%">Coordenadoria:</th>
                                <td><?= $fc['coordenadoria'] ?></td>
                            </tr>
                            <tr>
                                <th width="30%">Subprefeitura:</th>
                                <td><?= $fc['subprefeitura'] ?></td>
                            </tr>
                            <tr>
                                <th width="30%">Programa:</th>
                                <td><?= $fc['programa'] ?></td>
                            </tr>
                            <tr>
                                <th width="30%">Linguagem:</th>
                                <td><?= $fc['linguagem'] ?></td>
                            </tr>
                            <tr>
                                <th width="30%">Projeto:</th>
                                <td><?= $fc['projeto'] ?></td>
                            </tr>
                            <tr>
                                <th width="30%">Cargo:</th>
                                <td><?= $fc['cargo'] ?></td>
                            </tr>
                            <tr>
                                <th width="30%">Vigência:</th>
                                <td><?= $fc['vigencia'] ?></td>
                            </tr>
                            <tr>
                                <th width="30%">Observação:</th>
                                <td><?= $fc['observacao'] ?></td>
                            </tr>
                            <tr>
                                <th width="30%">Fiscal:</th>
                                <td><?= $fc['fiscal'] ?></td>
                            </tr>
                            <tr>
                                <th width="30%">Suplente:</th>
                                <td><?= $fc['suplente'] ?></td>
                            </tr>
                            <tr>
                                <th width="30%">Número do Processo de Pagamento:</th>
                                <td><?= $fc['numpgt'] ?></td>
                            </tr>
                            <tr>
                                <th width="30%">Publicado?</th>
                                <td><?= $fc['publicado'] ? "Sim" : "Não" ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <form action="?perfil=formacao&p=dados_contratacao&sp=listagem" method="post">
                <a href="?perfil=formacao&p=dados_contratacao&sp=listagem">
                    <button type="button" class="btn btn-default">Voltar</button>
                </a>
                    <input type="hidden" name="idDados" id="idDados" value="<?= $fc['id'] ?>">
                    <button type="button" class="btn btn-danger" id="despublica"
                            data-toggle="modal" data-target="#despublicacao" name="despublica"
                            data-id="<?= $fc['id'] ?>">
                        Despublicar
                    </button>
                </form>
            </div>
        </div>
</div>
<div id="despublicacao" class="modal modal-danger modal fade in" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <form action="?perfil=formacao&p=dados_contratacao&sp=listagem" method="post">
                <h4 class="modal-title">Confirmação de Despublicação</h4>
            </div>
            <div class="modal-body">
                <label>Tem certeza que deseja despublicar?</label>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="idDados" id="idDados" value="<?= $fc['id']?>">
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