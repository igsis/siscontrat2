<?php
include "includes/menu_interno.php";

unset($_SESSION['idEvento']);
unset($_SESSION['idPj']);
unset($_SESSION['idPf']);

$conn = bancoPDO();

if(isset($_POST['excluir'])){
    $evento = $_POST['idEvent'];
    $stmt = $conn->prepare('UPDATE eventos SET publicado = 0 WHERE id=:id');
    $stmt->execute(['id' => $evento]);
    $mensagem = mensagem("sucess" , "Evento excluido com sucesso");
}

$idEvento = $_POST['idEvent'];
$_SESSION['idEvento'] = $idEvento;


$idUser = $_SESSION['idUser'];
$evento = recuperaDados('eventos', 'id', $idEvento);

?>

<div class="content-wrapper">
    <section class="content">
        <div class="box box-widget widget-user">
            <h2 class="page-header">Produção - Evento</h2>
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-reader">
                            <h3 class="box-title"> Evento selecionado </h3><em class="pull-right"><?php if(isset($prazo)){echo $prazo;}; ?></em>


                            <div class="row align="center">
                            <?php
                                if(isset($mensagem)){
                                    echo $mensagem;
                                }?>
                            </div>
                        </div>

                        <div class="box-body">
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs pull-right">
                                    <?php if($evento['contratacao'] == 1){ ?>
                                        <li> <a href="#pedido" data-toggle="tab"> Pedido de Contratação </a></li>
                                    <?php } ?>
                                        <li> <a href="#ocorrencia" data-toggle="tab"> Ocorrência </a></li>
                                        <li>
                                            <a href="#atracao" data-toggle="tab">
                                                <?= $evento['tipo_evento_id' == 1] ? "Atração" : "Filme"?>
                                            </a>
                                        </li>
                                    <li class="active"><a href="#evento" data-toggle="tab"> Eventos </a> </li>
                                    <li class="pull-left header"> Confirmação dos Dados Inseridos </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="evento">
                                        
                                    </div>
                                </div>
                </div>
            </div>
         </section>
        </div>
    </div>
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

