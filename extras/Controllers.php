<?php
require_once "MainModel.php";

class Controllers extends MainModel
{
    public function recuperaEvento($idEvento)
    {
        return DbModel::consultaSimples("
            SELECT  e.protocolo, e.tipo_evento_id, e.nome_evento, e.espaco_publico, e.fomento, f.fomento AS fomento_nome, rj.relacao_juridica, pe.projeto_especial, e.sinopse, uf.nome_completo AS fiscal_nome, uf.rf_rg AS fiscal_rf, us.nome_completo AS suplente_nome, us.rf_rg AS suplente_rf, uf.nome_completo AS user_nome, e.nome_responsavel, e.tel_responsavel
            FROM eventos AS e
            LEFT JOIN evento_fomento ef on e.id = ef.evento_id
            LEFT JOIN fomentos f on ef.fomento_id = f.id
            INNER JOIN relacao_juridicas rj on e.relacao_juridica_id = rj.id
            INNER JOIN projeto_especiais pe on e.projeto_especial_id = pe.id
            INNER JOIN usuarios uf on e.fiscal_id = uf.id
            INNER JOIN usuarios us on e.suplente_id = us.id
            INNER JOIN usuarios ur on e.usuario_id = ur.id
            WHERE e.id = '$idEvento'
        ")->fetchObject();
    }

    public function recuperaPublico($idEvento):string
    {
        $publicos = DbModel::consultaSimples("SELECT p.publico FROM evento_publico AS ep INNER JOIN publicos p on ep.publico_id = p.id WHERE evento_id='$idEvento'")->fetchAll(PDO::FETCH_OBJ);
        $lista = "";
        foreach ($publicos as $publico) {
            $lista .= $publico->publico . ", ";
        }
        return substr($lista,0,-2);
    }

    public function recuperaAtracao($idEvento)
    {
        return DbModel::consultaSimples("
            SELECT * FROM atracoes a 
                INNER JOIN classificacao_indicativas ci on a.classificacao_indicativa_id = ci.id 
                LEFT JOIN produtores p on a.produtor_id = p.id
            WHERE evento_id = '$idEvento' AND publicado = 1")->fetchAll(PDO::FETCH_OBJ);
    }

    public function recuperaAcaoAtracao($idAtracao):string
    {
        $acoes = DbModel::consultaSimples("SELECT a.acao FROM acao_atracao at INNER JOIN acoes a on at.acao_id = a.id WHERE atracao_id = '$idAtracao'")->fetchAll(PDO::FETCH_OBJ);
        $lista = "";
        foreach ($acoes as $acao) {
            $lista .= $acao->acao . ", ";
        }
        return substr($lista,0,-2);
    }

    public function recuperaFilme($idEvento){
        return DbModel::consultaSimples("SELECT * FROM filme_eventos fe INNER JOIN filmes f on fe.filme_id = f.id WHERE evento_id = '$idEvento'")->fetchAll(PDO::FETCH_OBJ);
    }

    public function recuperaOcorrencia($idEvento)
    {
        return DbModel::consultaSimples("
            SELECT o.*, i.sigla,l.local,e.espaco,s.subprefeitura,ri.retirada_ingresso 
            FROM ocorrencias o
            LEFT JOIN instituicoes i on o.instituicao_id = i.id
            LEFT JOIN locais l on o.local_id = l.id
            LEFT JOIN espacos e on o.espaco_id = e.id
            LEFT JOIN subprefeituras s on o.subprefeitura_id = s.id
            LEFT JOIN retirada_ingressos ri on o.retirada_ingresso_id = ri.id
            WHERE origem_ocorrencia_id = '$idEvento' AND o.publicado = 1")->fetchAll(PDO::FETCH_OBJ);
    }

    public function recuperaOcorrenciaOrigem($tipo,$origem)
    {
        if ($tipo == 1){ // atração
            $origem = DbModel::consultaSimples("SELECT nome_atracao FROM atracoes WHERE id = '$origem'")->fetchColumn();
        } elseif ($tipo == 2){ // filme
            $origem = DbModel::consultaSimples("SELECT titulo FROM filmes WHERE id = '$origem'")->fetchColumn();
        }
        return $origem;
    }

    public function recuperaOcorrenciaExcecao($idAtracao):string
    {
        $datas = DbModel::consultaSimples("SELECT data_excecao FROM ocorrencia_excecoes WHERE atracao_id = '$idAtracao'")->fetchAll(PDO::FETCH_OBJ);
        $lista = "";
        foreach ($datas as $data) {
            $lista .= date('d/m/Y', strtotime($data->data_excecao)) . ", ";
        }
        return substr($lista,0,-2);
    }

    public function diadasemanaocorrencia($idOcorrencia){
        $array = [];
        $ocorrencia = DbModel::consultaSimples("SELECT segunda,terca,quarta,quinta,sexta,sabado,domingo FROM ocorrencias WHERE id = '$idOcorrencia'")->fetch(PDO::FETCH_ASSOC);

        if($ocorrencia['domingo'] == 1){
            array_push($array, "domingo");
        }
        if($ocorrencia['segunda'] == 1){
            array_push($array,"segunda");
        }
        if($ocorrencia['terca'] == 1){
            array_push($array, "terça");
        }
        if($ocorrencia['quarta'] == 1){
            array_push($array, "quarta");
        }
        if($ocorrencia['quinta'] == 1){
            array_push($array,"quinta");
        }
        if($ocorrencia['sexta'] == 1){
            array_push($array, "sexta");
        }
        if($ocorrencia['sabado'] == 1){
            array_push($array, "sábado");
        }
        return implode(", ",$array);
    }

    public function recuperaArquivoComProd($idEvento)
    {
        return DbModel::consultaSimples("SELECT * FROM arquivos as arq INNER JOIN lista_documentos AS ld ON arq.lista_documento_id = ld.id  WHERE arq.origem_id = '$idEvento' AND arq.publicado = '1' ORDER BY arq.id")->fetchAll(PDO::FETCH_OBJ);
    }

    public function recuperaPedido($idEvento)
    {
        return DbModel::consultaSimples("
            SELECT p.id, p.pessoa_tipo_id, p.pessoa_fisica_id, p.pessoa_juridica_id, p.numero_processo, l.extrato_liquidacao, l.retencoes_inss, l.retencoes_iss, l.retencoes_irrf, pf.*, pj.* 
            FROM pedidos AS p 
                INNER JOIN eventos AS e ON p.origem_id = e.id 
                LEFT JOIN liquidacao l on p.id = l.pedido_id 
                LEFT JOIN pessoa_fisicas pf on p.pessoa_fisica_id = pf.id
                LEFT JOIN pessoa_juridicas pj on p.pessoa_juridica_id = pj.id 
            WHERE e.publicado = 1 AND p.publicado = 1 AND p.origem_id = '$idEvento'")->fetchObject();
    }

}