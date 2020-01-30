<?php
$con = bancoMysqli();
if (isset($_POST['idEvento'])) {
    $idEvento = $_POST['idEvento'];
}
if (isset($_POST['idPedido'])) {
    $idPedido = $_POST['idPedido'];
}


$sql = "select u.nome_completo, c.justificativa, u.id, c.data,
es.status, e.id,e.nome_evento,c.titulo,ct.tipo,u.email
from chamados as c
inner join usuarios as u on c.usuario_id = u.id
inner join eventos as e on e.id = c.evento_id
inner join chamado_tipos as ct on ct.id = c.id
inner join evento_status as es on es.id = e.evento_status_id e.publicado WHERE e.publicado = 1 AND e.id = $idEvento";

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
                        <div class="col-md-offset-2 col-md-8">
                            <label>ID Chamado:</label>
                            <input type="text" readonly name="idChamado" class="form-control" id="idChamado"
                                   value="<?= $dados['id'] ?>">
                        </div>
                        <div class="col-md-offset-2 col-md-8">
                            <label>Nome do Evento:</label>
                            <input readonly name="nomeEvento" class="form-control" id="nomeEvento"
                                   value="<?= $dados['nome_evento'] ?>"/>
                        </div>
                        <div class="col-md-offset-2 col-md-8">
                            <label>Titulo chamado:</label>
                            <input readonly name="nomeEvento" class="form-control" id="nomeEvento"
                                   value="<?= $dados['titulo'] ?>"/>
                        </div>
                        <div class="col-md-offset-2 col-md-8">
                            <label>Data do chamado:</label>
                            <input readonly name="nomeEvento" class="form-control" id="nomeEvento"
                                   value="<?= $dados['data'] ?>"/>
                        </div>
                        <div class="col-md-offset-2 col-md-8">
                            <label>Tipo de chamado:</label>
                            <input readonly name="nomeEvento" class="form-control" id="nomeEvento"
                                   value="<?= $dados['tipo'] ?>"/>
                        </div>

                        <div class="col-md-offset-2 col-md-4">
                            <label>Criado por:</label>
                            <input readonly name="nomeCompleto" class="form-control" id="nomeCompleto"
                                   value="<?= $dados['nome_completo'] ?>"/>
                        </div>
                        <div class="col-md-offset-0 col-md-4">
                            <label>Email:</label>
                            <input readonly name="email" class="form-control" id="email"
                                   value="<?= $dados['email'] ?>"/>
                        </div>
                        <div class="col-md-offset-2 col-md-8">
                            <label>Descrição:</label>
                            <textarea readonly name="descricao" class="form-control"
                                      rows="10"><?php echo $dados['justificativa'] ?></textarea>
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
