<?php

if (isset($_POST['adicionar'])) {
    $documento = $_POST['documentacao'];
    $tipoDocumento = $_POST['tipoDocumento'];
}

if (isset($_POST['importar'])) {
    $conCapac = bancoCapacAntigo();
    $con = bancoMysqli();

    $documento = $_POST['cpf'];
    $tipoDocumento = 1;

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
                pf.telefone2,
                pf.telefone3,
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
                pf.codigoBanco,
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
    WHERE pf.cpf = '{$documento}'";

    $query = mysqli_query($conCapac, $sql);

    if (mysqli_num_rows($query) > 0) {
        $pf = mysqli_fetch_assoc($query);
        $pf['nacionalidade'] = $pf['nacionalidade'] != buscaId('nacionalidades', $pf['nacionalidade']) ?: '';
    } else {
        echo "<script>window.location = '?perfil=formacao&p=pessoa_fisica&sp=pesquisa_capac&erro=0';</script>";
    }

}

function buscaId($tabela, $valor)
{
    $con = bancoMysqli();
    $sql = "SELECT id FROM {$tabela} WHERE nacionalidade LIKE '%{$valor}%'";
    $query = mysqli_query($con, $sql);
    $resultado = mysqli_fetch_assoc($query);
    if ($resultado != null) {
        return $resultado['id'];
    }
    return '';

}

?>

<script>
    $(document).ready(function () {
        $("#cep").mask('00000-000', {reverse: true});
    });
</script>

<script language="JavaScript">
    function barraData(n) {
        if (n.value.length == 2)
            c.value += '/';
        if (n.value.length == 5)
            c.value += '/';
    }
</script>

<div class="content-wrapper">
    <section class="content">
        <h2 class="page-header">Cadastro de pessoa física</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Pessoa física</h3>
                    </div>
                    <div class="box-body">
                        <form action="?perfil=formacao&p=pessoa_fisica&sp=edita" method="post">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="nome">Nome: *</label>
                                    <input type="text" class="form-control" name="nome" placeholder="Digite o nome"
                                           maxlength="70" required value="<?= isset($pf) ? $pf['nome'] : '' ?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="nomeArtistico">Nome Artistico:</label>
                                    <input type="text" class="form-control" name="nomeArtistico"
                                           placeholder="Digite o nome artistico" maxlength="70"
                                           value="<?= isset($pf) ? $pf['nomeArtistico'] : '' ?>">
                                </div>
                            </div>

                            <div class="row">
                                <?php
                                if ($tipoDocumento == 1) {
                                    ?>
                                    <div class="form-group col-md-2">
                                        <label for="rg">RG: *</label>
                                        <input type="text" class="form-control" name="rg" placeholder="Digite o RG"
                                               maxlength="20" required value="<?= isset($pf) ? $pf['rg'] : '' ?>">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="cpf">CPF: </label>
                                        <input type="text" name="cpf" class="form-control" id="cpf"
                                               value="<?= $documento ?>" readonly>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="ccm">CCM:</label>
                                        <input type="text" name="ccm" class="form-control" placeholder="Digite o CCM"
                                               maxlength="11" value="<?= isset($pf) ? $pf['ccm'] : '' ?>">
                                    </div>
                                    <?php
                                } else {
                                    ?>
                                    <div class="form-group col-md-6">
                                        <label for="passaporte" id="documento">Passaporte: </label>
                                        <input type="text" id="passaporte" name="passaporte" class="form-control"
                                               value="<?= $documento ?>" readonly>
                                    </div>
                                    <?php
                                }
                                ?>
                                <div class="form-group col-md-3">
                                    <label for="dataNascimento">Data de Nascimento: *</label>
                                    <input type="date" class="form-control" id="dataNascimento" name="dtNascimento"
                                           onkeyup="barraData(this);" required
                                           value="<?= isset($pf) ? $pf['dataNascimento'] : '' ?>"/>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="nacionalidade">Nacionalidade: *</label>
                                    <select class="form-control" id="nacionalidade" name="nacionalidade" required>
                                        <option value="">Selecione uma opção...</option>
                                        <?php
                                        geraOpcao("nacionalidades", isset($pf) ? $pf['nacionalidade'] : "");
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <hr/>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="cep">CEP: *</label>
                                    <input type="text" class="form-control" name="cep" id="cep" maxlength="9"
                                           placeholder="Digite o CEP" required data-mask="00000-000"
                                           value="<?= isset($pf) ? $pf['cep'] : '' ?>">
                                </div>
                                <div class="form-group col-md-2">
                                    <label>&nbsp;</label><br>
                                    <input type="button" class="btn btn-primary" value="Carregar">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="rua">Rua: *</label>
                                    <input type="text" class="form-control" name="rua" id="rua"
                                           placeholder="Digite a rua" maxlength="200" readonly
                                           value="<?= isset($pf) ? $pf['logradouro'] : '' ?>">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="numero">Número: *</label>
                                    <input type="number" name="numero" class="form-control" min="0"
                                           placeholder="Ex.: 10"
                                           required value="<?= isset($pf) ? $pf['numero'] : '' ?>">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="complemento">Complemento:</label>
                                    <input type="text" name="complemento" class="form-control" maxlength="20"
                                           placeholder="Digite o complemento"
                                           value="<?= isset($pf) ? $pf['complemento'] : '' ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="bairro">Bairro: *</label>
                                    <input type="text" class="form-control" name="bairro" id="bairro"
                                           placeholder="Digite o Bairro" maxlength="80" readonly
                                           value="<?= isset($pf) ? $pf['bairro'] : '' ?>">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="cidade">Cidade: *</label>
                                    <input type="text" class="form-control" name="cidade" id="cidade"
                                           placeholder="Digite a cidade" maxlength="50" readonly
                                           value="<?= isset($pf) ? $pf['cidade'] : '' ?>">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="estado">Estado: *</label>
                                    <input type="text" class="form-control" name="estado" id="estado" maxlength="2"
                                           placeholder="Ex.: SP" readonly
                                           value="<?= isset($pf) ? $pf['estado'] : '' ?>">
                                </div>
                            </div>
                            <hr/>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="email">E-mail: *</label>
                                    <input type="email" name="email" class="form-control" maxlength="60"
                                           placeholder="Digite o E-mail" required
                                           value="<?= isset($pf) ? $pf['email'] : '' ?>">
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Telefone #1: *</label>
                                    <input type="text" id="telefone" name="telefone[0]"
                                           onkeyup="mascara( this, mtel );" class="form-control"
                                           placeholder="Digite o Telefone principal" required
                                           maxlength="15" pattern=".{14,15}" title="14 a 15 caracteres"
                                           data-mask="(00) 00000-0000"
                                           value="<?= isset($pf) ? $pf['telefone1'] : '' ?>">
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Telefone #2:</label>
                                    <input type="text" id="telefone1" name="telefone[1]"
                                           onkeyup="mascara( this, mtel );" class="form-control"
                                           placeholder="Digite o telefone" maxlength="15"
                                           pattern=".{14,15}" title="14 a 15 caracteres"
                                           data-mask="(00) 00000-0000"
                                           value="<?= isset($pf) ? $pf['telefone2'] : '' ?>">
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Telefone #3:</label>
                                    <input type="text" id="telefone2" name="telefone[2]"
                                           onkeyup="mascara( this, mtel );" class="form-control"
                                           placeholder="Digite o telefone" maxlength="15"
                                           pattern=".{14,15}" title="14 a 15 caracteres"
                                           data-mask="(00) 00000-0000"
                                           value="<?= isset($pf) ? $pf['telefone3'] : '' ?>">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="nit">NIT: </label>
                                    <input type="text" name="nit" class="form-control" maxlength="45"
                                           placeholder="Digite o NIT">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="observacao">Observação: </label>
                                    <textarea id="observacao" name="observacao" rows="3"
                                              class="form-control"></textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="banco">Banco</label>
                                    <select name="banco" id="banco" class="form-control">
                                        <option value="">Selecione um banco...</option>
                                        <?php
                                        geraOpcao('bancos', $pf['codigoBanco']);
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="agencia">Agência</label>
                                    <input type="text" id="agencia" name="agencia" class="form-control"
                                           value="<?= isset($pf) ? $pf['agencia'] : '' ?>">
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="conta">Conta</label>
                                    <input type="text" id="conta" name="conta" class="form-control"
                                           value="<?= isset($pf) ? $pf['conta'] : '' ?>">
                                </div>
                            </div>

                            <div class="box-footer">
                                <button type='submit' name='cadastra' class='btn btn-info pull-right'>Cadastrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>