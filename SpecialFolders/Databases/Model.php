<?php
namespace Db;
use PDO;
use DatabaseShit;
require_once 'Database.php';
class Model{
    protected $table;
    private static $staticTb;
    protected $primaryKey;
    private static $conn;
    // protected $conn;
    private $rowCount;

    public function __construct()
    {
        $db = new DatabaseShit();
        self::$conn = $db->getConnection();
        // $this->conn = $db->getConnection();
    }

    public function create(array $data){
        $columns = implode(', ', array_keys($data));
        $values = ':'.implode(', :', array_keys($data));

        $sql = "INSERT INTO $this->table ($columns) VALUES ($values)";
        $stmt = self::$conn->prepare($sql);

        foreach($data as $key => $val){
            $stmt->bindValue(":$key", $val);
        }

        return $stmt->execute();
    }
    public function all(){
        $sql = "SELECT * FROM $this->table";
        $stmt = self::$conn->prepare($sql);

        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $rowsAffected = $stmt->rowCount();

        $data = array(
            'results' => $results,
            'rows' => $rowsAffected
        );

        return $data;
    }

    public function find($id){
        $sql = "SELECT * FROM $this->table WHERE `$this->primaryKey`= :id";
        $stmt = self::$conn->prepare($sql);
        // $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        // var_dump($result);
        $rowsAffected = $stmt->rowCount();
        $results = array(
            'result' => $result,
            'rowsAffected' => $rowsAffected
        );
        return $results;
    }

    public function findWith($col, $value){
        $sql = "SELECT * FROM $this->table WHERE $col = :val";
        $stmt = self::$conn->prepare($sql);
        // $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':val', $value);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $rowsAffected = $stmt->rowCount();
        $results = array(
            'result' => $result,
            'rowsAffected' => $rowsAffected
        );
        return $results;
    }

    public function update($id, array $data)
    {
        $updates = '';
        foreach ($data as $key => $value) {
            $updates .= "$key = :$key, ";
        }
        $updates = rtrim($updates, ', ');

        $sql = "UPDATE $this->table SET $updates WHERE $this->primaryKey = :id";
        $stmt = self::$conn->prepare($sql);
        $stmt->bindValue(':id', $id);

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        return $stmt->execute();
    }

    public function updateWhere($col, $val, array $data){
        $updates = '';
        foreach ($data as $key => $value) {
            $updates .= "$key = :$key, ";
        }
        $updates = rtrim($updates, ', ');

        $sql = "UPDATE $this->table SET $updates WHERE $col = :val";
        $stmt = self::$conn->prepare($sql);
        $stmt->bindValue(':val', $val);

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        return $stmt->execute();
    }

    public function updateMoreWhere(array $data, array $where){
        $updates = '';
        foreach ($data as $key => $value) {
            $updates .= "$key = :$key, ";
        }
        $updates = rtrim($updates, ', ');

        $wheres = '';
        foreach($where as $wkey => $wval){
            $wheres .= "$wkey = :$wval, ";
        }
        $wheres = rtrim($wheres, ', ');

        $sql = "UPDATE $this->table SET $updates WHERE $wheres";
        $stmt = self::$conn->prepare($sql);

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        foreach ($where as $wkey => $wval){
            $stmt->bindValue(":$wkey", $wval);
        }

        return $stmt->execute();
    }

    public function delete($id)
    {
        $sql = "DELETE FROM $this->table WHERE $this->primaryKey = :id";
        $stmt = self::$conn->prepare($sql);
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }

    public function JoinWith(Model $model, $thisKey){

        $sql = "SELECT * FROM $this->table INNER JOIN $model->table ON $this->table.$thisKey = $model->table.$model->primaryKey";

        $stmt = self::$conn->prepare($sql);

        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $rows = $stmt->rowCount();

        $data = array(
            'result' => $result,
            'rows' => $rows
        );

        return $data;
    }
    public function JoinMultipleTable(Model ...$models){

        $sql = "SELECT * FROM $this->table main_tb";
            foreach($models as $model){
                $i = 1;
                $sql .= " INNER JOIN $model->table tb_$i ON $model->primaryKey";
                $i += 1;
            }

        $stmt = self::$conn->prepare($sql);
        $stmt->execute();

        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $res;
    }

    public static function Join(Model $model_1, Model $model_2, $md1_key, $md2_key){
        $mdl_1_tb = $model_1->table;
        $mdl_2_tb = $model_2->table;

        $sql = "SELECT * FROM $mdl_1_tb INNER JOIN $mdl_2_tb ON $mdl_1_tb.$md1_key = $mdl_2_tb.$md2_key";
        $stmt = self::$conn->prepare($sql);

        $stmt->execute();
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $rows = $stmt->rowCount();

        $data = array(
            'result' => $res,
            'rows' => $rows
        );

        return $data;
    }

    public static function viewAll(Model $model){
        $tb = $model->table;
        echo $tb;
        $sql = 'SELECT * FROM '.$tb;

        $stmt = self::$conn->prepare($sql);

        $stmt->execute();
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $rows = $stmt->rowCount();

        $data = array(
            'result' => $res,
            'rows' => $rows
        );

        return $data;
    }
}
