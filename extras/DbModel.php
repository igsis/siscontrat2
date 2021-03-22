<?php
require_once "../funcoes/funcoesConecta.php";
date_default_timezone_set('America/Fortaleza');

class DbModel
{
    public static $conn;

    protected function connection($capac = false) {
        if(!isset(self::$conn)) {
            if (!$capac) {
                self::$conn = new PDO(SGDB1, USER1, PASS1, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
            } else {
                self::$conn = new PDO(SGDB2, USER2, PASS2, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
            }
            self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$conn;
    }

    protected function killConn(){
        if (isset(self::$conn)) {
            self::$conn = null;
        }
    }

    /**
     * <p>Função para inserir um registro no banco de dados </p>
     * @param string $table
     * <p>Tabela do banco de dados</p>
     * @param array $data
     * <p>Dados a serem inseridos</p>
     * @param bool $capac
     * <p><strong>FALSE</strong> por padrão. Quando <strong>TRUE</strong>, faz a consulta no banco de dados do sistema CAPAC</p>
     * @return bool|PDOStatement
     */
    protected function insert($table, $data, $capac = false) {
        $pdo = self::connection($capac);
        $fields = implode(", ", array_keys($data));
        $values = ":".implode(", :", array_keys($data));
        $sql = "INSERT INTO $table ($fields) VALUES ($values)";
        $statement = $pdo->prepare($sql);
        foreach($data as $key => $value) {
            $statement->bindValue(":$key", $value, PDO::PARAM_STR);
        }
        $statement->execute();

        return $statement;
    }

    /**
     * <p>Função para inserir um registro no banco de dados caso seja válido </p>
     * @param string $table
     * <p>Tabela do banco de dados</p>
     * @param array $data
     * <p>Dados a serem inseridos</p>
     * @param bool $capac
     * <p><strong>FALSE</strong> por padrão. Quando <strong>TRUE</strong>, faz a consulta no banco de dados do sistema CAPAC</p>
     * @return bool|PDOStatement
     */
    protected function insertignore($table, $data, $capac = false) {
        $pdo = self::connection($capac);
        $fields = implode(", ", array_keys($data));
        $values = ":".implode(", :", array_keys($data));
        $sql = "INSERT IGNORE INTO $table ($fields) VALUES ($values)";
        $statement = $pdo->prepare($sql);
        foreach($data as $key => $value) {
            $statement->bindValue(":$key", $value, PDO::PARAM_STR);
        }
        $statement->execute();

        return $statement;
    }

    // Método para update

    /**
     * <p>Atualiza os dados do registro especificado</p>
     * @param string $table
     * <p>Tabela do banco de dados</p>
     * @param array $data
     * <p>Dados a serem inseridos</p>
     * @param int $id
     * <p>ID do registro a ser atualizado</p>
     * @param bool $capac
     * * <p><strong>FALSE</strong> por padrão. Quando <strong>TRUE</strong>, faz a consulta no banco de dados do sistema CAPAC</p>
     * @return bool|PDOStatement
     */
    protected function update($table, $data, $id, $capac = false){
        $pdo = self::connection($capac);
        $new_values = "";
        foreach($data as $key => $value) {
            $new_values .= "$key=:$key, ";
        }
        $new_values = substr($new_values, 0, -2);
        $sql = "UPDATE $table SET $new_values WHERE id = :id";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(":id", $id, PDO::PARAM_STR);
        foreach($data as $key => $value) {
            $statement->bindValue(":$key", $value, PDO::PARAM_STR);
        }
        $statement->execute();

        return $statement;
    }

    // Método para update especial
    protected function updateEspecial($table, $data, $campo, $campo_id, $capac = false){
        $pdo = self::connection($capac);
        $new_values = "";
        foreach($data as $key => $value) {
            $new_values .= "$key=:$key, ";
        }
        $new_values = substr($new_values, 0, -2);
        $sql = "UPDATE $table SET $new_values WHERE $campo = :$campo";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(":$campo", $campo_id, PDO::PARAM_STR);
        foreach($data as $key => $value) {
            $statement->bindValue(":$key", $value, PDO::PARAM_STR);
        }
        $statement->execute();

        return $statement;
    }

    // Método para update condicional
    protected function updateCondicional($table, $data, $where, $capac = false){
        $pdo = self::connection($capac);
        $new_values = "";
        foreach($data as $key => $value) {
            $new_values .= "$key=:$key, ";
        }
        $new_values = substr($new_values, 0, -2);
        $sql = "UPDATE $table SET $new_values WHERE $where";
        $statement = $pdo->prepare($sql);
        foreach($data as $key => $value) {
            $statement->bindValue(":$key", $value, PDO::PARAM_STR);
        }
        $statement->execute();

        return $statement;
    }

    /**
     * Método para apagar (despublicar)
     * @param string $table
     * @param int $id
     * @param bool $capac
     * @return bool|PDOStatement
     */
    protected function apaga($table, $id, $capac = false){
        $pdo = self::connection($capac);
        $sql = "UPDATE $table SET publicado = 0 WHERE id = :id";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(":id", $id);
        $statement->execute();

        return $statement;
    }

    protected function deleteEspecial($table, $campo, $campo_id, $capac = false){
        $pdo = self::connection($capac);
        $sql = "DELETE FROM $table WHERE $campo = :$campo";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(":$campo", $campo_id, PDO::PARAM_STR);
        $statement->execute();

        return $statement;
    }

    public function consultaSimples($consulta, $capac = false) {
        $pdo = self::connection($capac);
        $statement = $pdo->prepare($consulta);
        $statement->execute();
        self::$conn = null;

        return $statement;
    }

    // Método para pegar a informação

    /**
     * @param string $table
     * <p>tabela a ser consultada no banco</p>
     * @param int $id
     * <p>ID que deve ser procurado</p>
     * @param bool $capac [opcional]
     * <p><strong>FALSE</strong> por padrão. Quando <strong>TRUE</strong>, faz a consulta no banco de dados do sistema CAPAC</p>
     * @return bool|PDOStatement
     */
    protected function getInfo($table, $id, $capac = false){
        $pdo = self::connection($capac);
        $sql = "SELECT * FROM $table WHERE id = :id";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(":id", $id);
        $statement->execute();

        return $statement;
    }

    // Lista publicados
    protected function listaPublicado($table,$id = null, $capac = false) {
        if(!empty($id)){
            $filtro_id = "AND id = :id";
        }
        else{
            $filtro_id = "";
        }
        $pdo = self::connection($capac);
        $sql = "SELECT * FROM $table WHERE publicado = 1 $filtro_id ORDER BY 2";
        $statement = $pdo->query($sql);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_OBJ);
    }
}