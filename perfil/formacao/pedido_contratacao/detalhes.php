<?php
$con = bancoMysqli();
$idPC = $_POST['idPC'];

$sql = "SELECT f.ano AS 'ano',
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
               suplente.nome_completo AS 'suplente'             
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
                <h2 class="box-title">Pedido Selecionado</h2>
            </div>
        </div>
        <div class="box">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"> Dados do Pedido</h3>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th width="30%">Ano: </th>
                                <td><?=$fc['ano']?></td>
                            </tr>
                            <tr>
                                <th width="30%">Chamado?</th>
                                <td><?=$fc['chamado'] ? "Sim" : "Não"?></td>
                            </tr>
                            <tr>
                                <th width="30%">Classificacao Indicativa: </th>
                                <td><?=$fc['classificacao']?></td>
                            </tr>
                            <tr>
                                <th width="30%">Território: </th>
                                <td><?=$fc['territorio']?></td>
                            </tr>
                            <tr>
                                <th width="30%">Coordenadoria: </th>
                                <td><?=$fc['coordenadoria']?></td>
                            </tr>
                            <tr>
                                <th width="30%">Subprefeitura: </th>
                                <td><?=$fc['subprefeitura']?></td>
                            </tr>
                            <tr>
                                <th width="30%">Programa: </th>
                                <td><?=$fc['programa']?></td>
                            </tr>
                            <tr>
                                <th width="30%">Linguagem: </th>
                                <td><?=$fc['linguagem']?></td>
                            </tr>
                            <tr>
                                <th width="30%">Projeto: </th>
                                <td><?=$fc['projeto']?></td>
                            </tr>
                            <tr>
                                <th width="30%">Cargo: </th>
                                <td><?=$fc['cargo']?></td>
                            </tr>
                            <tr>
                                <th width="30%">Vigência: </th>
                                <td><?=$fc['vigencia']?></td>
                            </tr>
                            <tr>
                                <th width="30%">Observação: </th>
                                <td><?=$fc['observacao']?></td>
                            </tr>
                            <tr>
                                <th width="30%">Fiscal: </th>
                                <td><?=$fc['fiscal']?></td>
                            </tr>
                            <tr>
                                <th width="30%">Suplente: </th>
                                <td><?=$fc['suplente']?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <a href="?perfil=formacao&p=pedido_contratacao&sp=listagem">
                    <button type="button" class="btn btn-default">Voltar</button>
                </a>
                <a href="#">
                    <button type="button" class="btn btn-primary pull-right">Editar Parcelas</button>
                </a>
            </div>
        </div>
    </section>
</div>