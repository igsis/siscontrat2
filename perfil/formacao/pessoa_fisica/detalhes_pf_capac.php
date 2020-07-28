<?php
$con = bancoCapacAntigo();

//dados proponente
$sql = "SELECT 	pf.id,
                pf.nome,
                pf.nomeArtistico,
                pf.rg,
                pf.cpf,
                pf.ccm,
                ec.estadoCivil,
                pf.dataNascimento,
                pf.nacionalidade,
                pf.logradouro,
                pf.bairro,
                pf.cidade,
                pf.estado,
                pf.numero,
                pf.complemento,
                pf.cep,
                pr.prefeituraRegional,
                pf.telefone1,
                pf.email,
                pf.drt,
                r.regiao,
                pf.tipo_formacao_id,
                pf.pis,
                et.etnia,
                gt.grau_instrucao,
                fl.linguagem,
                tf.descricao AS programa,
                ff.funcao,
                ba.banco,
                pf.agencia,
                pf.conta
    FROM pessoa_fisica AS pf
    LEFT JOIN estado_civil AS ec ON pf.idEstadoCivil = ec.id
    LEFT JOIN prefeitura_regionais AS pr ON pf.prefeituraRegional_id = pr.prefeituraRegional
    LEFT JOIN grau_instrucoes AS gi ON pf.grau_instrucao_id = gi.grau_instrucao
    LEFT JOIN regioes as r on pf.formacao_regiao_preferencial = r.id
    LEFT JOIN etnias AS et ON pf.etnia_id = et.id
    LEFT JOIN grau_instrucoes AS gt ON pf.grau_instrucao_id = gt.id
    LEFT JOIN formacao_linguagem AS fl ON pf.formacao_linguagem_id = fl.linguagem
    LEFT JOIN tipo_formacao AS tf ON pf.tipo_formacao_id = tf.id
    LEFT JOIN formacao_funcoes AS ff ON pf.formacao_funcao_id = ff.funcao
    LEFT JOIN banco AS ba ON ba.id = pf.codigoBanco
    WHERE pf.id = {$_GET['id_capac']}";
$query = mysqli_query($con, $sql);
$pf = mysqli_fetch_assoc($query);

// arquivos do proponente
$sql = "SELECT *
				FROM upload_lista_documento as list
				INNER JOIN upload_arquivo as arq ON arq.idUploadListaDocumento = list.id
				WHERE arq.idPessoa = '{$_GET['id_capac']}'
				AND arq.idTipoPessoa = '1'
				AND arq.publicado = '1'
				ORDER BY documento";

$query = mysqli_query($con, $sql);
$linhas = mysqli_num_rows($query);
//$formacao = recuperaDadosCapac('tipo_formacao', 'id', $pf['tipo_formacao_id'])['descricao'];

?>

<div class="content-wrapper">

    <section class="content">
        <h2 class="page-header"></h2>
        <div class="tab-content">
            <div class="tab-pane active" id="evento">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Dados Pessoa Física CAPAC</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="box box-success">
                                    <div class="box-header">
                                        <h3 class="box-tittle">Informações Pessoais</h3>
                                    </div>
                                    <div class="box-body">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <tbody>
                                                <tr>
                                                    <th>Nome:</th>
                                                    <td colspan="3"><?= $pf['nome'] ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Nome artistico:</th>
                                                    <td colspan="3"><?= $pf['nomeArtistico'] ?></td>
                                                </tr>
                                                <tr>
                                                    <th>RG:</th>
                                                    <td><?= $pf['rg'] ?></td>
                                                    <th>CPF:</th>
                                                    <td><?= $pf['cpf'] ?></td>
                                                </tr>
                                                <tr>
                                                    <th>CCM:</th>
                                                    <td colspan="3"><?= $pf['ccm'] ?></td>
                                                </tr>
                                                <tr>
                                                    <th>E-mail:</th>
                                                    <td colspan="3"><?= $pf['email'] ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Telefone:</th>
                                                    <td colspan="3"><?= $pf['telefone1'] ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Estado Civil:</th>
                                                    <td><?= $pf['estadoCivil'] ?></td>
                                                    <th>Nacionalidade:</th>
                                                    <td><?= $pf['nacionalidade'] ?></td>
                                                </tr>
                                                <tr>
                                                    <th>PIS:</th>
                                                    <td colspan="3"><?= $pf['pis'] ?></td>
                                                </tr>
                                                <tr>
                                                    <th colspan="2">Programa Selecionado:</th>
                                                    <td colspan="2"><?= $pf['programa'] ?></td>
                                                </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="box box-info">
                                    <div class="box-header">
                                        <h3 class="box-tittle">Endereço</h3>
                                    </div>
                                    <div class="box-body">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <tbody>
                                                <tr>
                                                    <th>Logradouro:</th>
                                                    <td><?= $pf['logradouro'] ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Número:</th>
                                                    <td><?= $pf['numero'] ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Complemento:</th>
                                                    <td><?= $pf['complemento'] ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Bairro:</th>
                                                    <td><?= $pf['bairro'] ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Cidade:</th>
                                                    <td><?= $pf['cidade'] ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Telefone:</th>
                                                    <td><?= $pf['telefone1'] ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Estado:</th>
                                                    <td><?= $pf['estado'] ?></td>
                                                </tr>
                                                <tr>
                                                    <th>CEP:</th>
                                                    <td><?= $pf['cep'] ?></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="box box-warning">
                                    <div class="box-header">
                                        <h3 class="box-tittle">Informações Complementares</h3>
                                    </div>
                                    <div class="box-body">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <tbody>
                                                <tr>
                                                    <th>DRT:</th>
                                                    <td colspan="3"><?= $pf['drt'] ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Etnia:</th>
                                                    <td colspan="3"><?= $pf['etnia'] ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Grau de Instrução:</th>
                                                    <td colspan="3"><?= $pf['grau_instrucao'] ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Linguagem:</th>
                                                    <td><?= $pf['linguagem'] ?></td>
                                                    <th>Função:</th>
                                                    <td><?= $pf['funcao'] ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Região Preferencial:</th>
                                                    <td><?= $pf['regiao'] ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Banco:</th>
                                                    <td colspan="3"><?= $pf['banco'] ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Agência:</th>
                                                    <td><?= $pf['agencia'] ?></td>
                                                    <th>Conta:</th>
                                                    <td><?= $pf['conta'] ?></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="box box-danger">
                                    <div class="box-header">
                                        <h3 class="box-tittle">Arquivo(s) de Pessoa Física</h3>
                                    </div>
                                    <div class="box-body">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <tbody>
                                                <?php
                                                if ($linhas) {
                                                    while ($arquivo = mysqli_fetch_array($query)) {
                                                        ?>
                                                        <tr>
                                                            <td><?= $arquivo['documento'] ?></td>
                                                            <td><a class="btn btn-warning" href='../../igsiscapac/uploadsdocs/<?= $arquivo['arquivo'] ?>' target='_blank'>Abrir</td>
                                                        </tr>
                                                        <?php
                                                    }
                                                } else{ ?>
                                                    <tr>
                                                        <td>
                                                            <span style="text-align: center">Nenhum arquivo encontrado</span>
                                                        </td>
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
</section>