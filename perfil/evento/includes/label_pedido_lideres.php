<?php
//if($pedido['pessoa_tipo_id'] == 2){
$sql_atracao = "SELECT a.id, a.nome_atracao, pf.nome, l.pessoa_fisica_id FROM atracoes AS a                                              
                                            LEFT JOIN lideres l on a.id = l.atracao_id
                                            left join pessoa_fisicas pf on l.pessoa_fisica_id = pf.id
                                            WHERE a.publicado = 1 AND a.evento_id = '" . $_SESSION['idEvento'] . "'";
$query_atracao = mysqli_query($con, $sql_atracao);
?>
<h3 class="hs">3. Líder</h3>
<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Atração</th>
                <th>Proponente</th>
                <th width="10%">Ação</th>
            </tr>
            </thead>
            <?php
            echo "<tbody>";
            while ($atracao = mysqli_fetch_array($query_atracao)) {
                //analisaArray($atracao);
                echo "<tr>";
                echo "<td>" . $atracao['nome_atracao'] . "</td>";
                if ($atracao['pessoa_fisica_id'] > 0) {
                    echo "<td>" . $atracao['nome'] . "</td>";
                    echo "<td>
                                            <form method=\"POST\" action=\"?perfil=evento&p=pesquisa_lider\" role=\"form\">
                                            <input type='hidden' name='oficina' value='" . $atracao['id'] . "'>
                                            <input type='hidden' name='lider' value='$idPedido'>
                                            <button type=\"submit\" name='pesquisar' class=\"btn btn-primary\"><i class='fa fa-refresh'></i> Trocar</button>
                                            </form>
                                        </td>";
                } else {
                    echo "<td>
                                            <form method=\"POST\" action=\"?perfil=evento&p=pesquisa_lider\" role=\"form\">
                                            <input type='hidden' name='oficina' value='" . $atracao['id'] . "'>
                                            <input type='hidden' name='lider' value='$idPedido'>
                                            <button type=\"submit\" name='pesquisar' class=\"btn btn-primary\"><i class='fa fa-plus'></i> Adicionar</button>
                                            </form>
                                        </td>";
                    echo "<td></td>";
                }
                echo "</tr>";
            }
            echo "</tbody>";
            ?>
        </table>
    </div>
</div>
<ul class="list-inline pull-right">
    <li>
        <a class="btn btn-default prev-step"><span
                aria-hidden="true">&larr;</span>
            Voltar</a>
    </li>
    <li>
        <a class="btn btn-primary next-step">Próxima etapa <span
                aria-hidden="true">&rarr;</span></a>
    </li>
</ul>