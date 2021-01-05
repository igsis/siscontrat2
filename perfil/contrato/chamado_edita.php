<?php
$con = bancoMysqli();
if (isset($_POST['idEvento'])) {
    $idEvento = $_POST['idEvento'];
}
if (isset($_POST['idPedido'])) {
    $idPedido = $_POST['idPedido'];
}
if(isset($_POST['idChamado'])){
    $idChamado = $_POST['idChamado'];
}

$sql = "SELECT c.id, e.nome_evento, c.titulo, c.data, ct.tipo, u.nome_completo, u.email, c.justificativa
FROM chamados AS c
INNER JOIN usuarios AS u ON c.usuario_id = u.id
INNER JOIN eventos AS e ON e.id = c.evento_id
INNER JOIN chamado_tipos AS ct ON ct.id = c.id
INNER JOIN evento_status AS es ON es.id = e.evento_status_id WHERE c.id = $idChamado";

$query = mysqli_query($con, $sql);
$dados = mysqli_fetch_array($query);

?>

<div class="content-wrapper">
    <section class="content">
        <div class="page-header">
            <h2 class="page-title">Resumo do Chamado</h2>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Detalhes do chamado selecionado</h3>
            </div>
            <div class="box-body">
                <div class=form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <label>ID do Chamado:</label>
                            <input type="text" readonly name="idChamado" class="form-control" id="idChamado"
                                   value="<?= $dados['id'] ?>">
                        </div>

                        <div class="col-md-6">
                            <label>Nome do Evento:</label>
                            <input readonly name="nomeEvento" class="form-control" id="nomeEvento"
                                   value="<?= $dados['nome_evento'] ?>"/>
                        </div>
                    </div>

                    <br>

                    <div class="row">
                        <div class="col-md-6">
                            <label>Titulo do Chamado:</label>
                            <input readonly name="nomeEvento" class="form-control" id="nomeEvento"
                                   value="<?= $dados['titulo'] ?>"/>
                        </div>

                        <div class="col-md-6">
                            <label>Data do Chamado:</label>
                            <input readonly name="nomeEvento" class="form-control" id="nomeEvento"
                                   value="<?= date("d/m/Y (H:m", strtotime($dados['data'])) . "h)" ?>"/>
                        </div>
                    </div>

                    <br>

                    <div class="row">
                        <div class="col-md-4">
                            <label>Tipo de chamado:</label>
                            <input readonly name="nomeEvento" class="form-control" id="nomeEvento"
                                   value="<?= $dados['tipo'] ?>"/>
                        </div>

                        <div class="col-md-4">
                            <label>Criado por:</label>
                            <input readonly name="nomeCompleto" class="form-control" id="nomeCompleto"
                                   value="<?= $dados['nome_completo'] ?>"/>
                        </div>
                        <div class="col-md-4">
                            <label>Email:</label>
                            <input readonly name="email" class="form-control" id="email"
                                   value="<?= $dados['email'] ?>"/>
                        </div>
                    </div>

                    <br>

                    <div class="row">
                        <div class="col-md-12">
                            <label>Descrição:</label>
                            <textarea readonly name="descricao" class="form-control"
                                      rows="4"><?php echo $dados['justificativa'] ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <form action="?perfil=contrato&p=chamados_contrato" method="post" role="form">
                    <input type="hidden" value="<?= $idPedido ?>" name="idPedido">
                    <input type="hidden" value="<?= $idEvento ?>" name="idEvento">
                    <button type="submit" name="Voltar" class="btn btn-default pull-left">Voltar</button>
                </form>
            </div>
        </div>
</div>
