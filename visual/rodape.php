    <footer class="main-footer">
        <div class="pull-right hidden-xs" align="right">
            <strong>2018 &copy; SISCONTRAT</strong><br/>
            STI - Sistemas de Informação - <b>Version</b> 2.0
        </div>
        <img src="images/logo_cultura.png" />
        <div class="box-body">
            <div class="box-group" id="accordion">
                <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                <div class="panel box box-primary">
                    <div class="box-header with-border">
                        <h4 class="box-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseRodape">
                                Configurações
                            </a>
                        </h4>
                    </div>
                    <div id="collapseRodape" class="panel-collapse collapse">
                        <div class="box-body">
                            <?php
                            echo "<strong>SESSION</strong><pre>", var_dump($_SESSION), "</pre>";
                            echo "<strong>POST</strong><pre>", var_dump($_POST), "</pre>";
                            echo "<strong>GET</strong><pre>", var_dump($_GET), "</pre>";
                            echo "<strong>SERVER</strong><pre>", var_dump($_SERVER), "</pre>";
                            echo "<strong>FILES</strong><pre>", var_dump($_FILES), "</pre>";
                            echo ini_get('session.gc_maxlifetime')/60; // em minutos
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>

<!--  Mask-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- DataTables -->
<!-- <script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script> -->
<!-- <script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script> -->
<!-- API Consulta CEP -->
<script src="./dist/js/cep_api.js"></script>
<script src="./dist/js/scripts.js"></script>

<script type="text/javascript" src="js/autocomplete.js"></script>
<script src="js/jquery-ui.js"></script>


<!-- page script -->
<script>
    // $(function () {
    //     $('#example1').DataTable()
    //     $('#example2').DataTable({
    //         'paging'      : true,
    //         'lengthChange': false,
    //         'searching'   : false,
    //         'ordering'    : true,
    //         'info'        : true,
    //         'autoWidth'   : false
    //     })
    // })
</script>
</body>
</html>