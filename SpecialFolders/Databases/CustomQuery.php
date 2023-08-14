<?php
// namespace Db;
// use PDO;
require_once 'Database.php';
class CustomQuery{

    private static $sql;
    private static $table;
    private static $joinedTable;
    private static $data;

    /**
     * SELECT * FROM $tableName
     * @param string $tableName
     */
    public static function SelectAll($tableName){

        self::$sql = "SELECT * FROM $tableName";
        self::$table = $tableName;
        return new self();
    }

    public static function SelectColumn($tableName, array $columns){
        $cols = implode(", ", $columns);
        self::$sql = "SELECT $cols FROM $tableName";

        return new self();
    }

    // public static function Insert($table, array $data){

    //     $cols = array_keys($data);
    //     // $values = array_values($data);

    //     $c = implode(", ", $cols);
    //     $v = ':'.implode(", :", $cols);

    //     self::$sql = "INSERT INTO $table ($c) VALUES($v)";

    //     self::$data = $data;

    //     $stmt = self::$conn->prepare(self::$sql);

    //     foreach($data as $key => $val){
    //         $stmt->bindValue(":$key", $val);
    //     }

    //     return $stmt->execute();

    //     // return new self();
    // }

    public function Where($column, $value){

        if (strpos(self::$sql, 'WHERE') === false) {
            self::$sql .= " WHERE $column = '$value'";
        } else {
            self::$sql .= " AND $column = '$value'";
        }

        return $this;
    }

    /**
     * INNER JOIN $table ON $table.$key = $baseTable.$BaseKey
     * @param string $table - Table you want to join in.
     * @param string $BaseKey - The base table's column you want to join with.
     * @param string $key - The joined Table column you want to join with.
     *
     */

    public function InnerJoin($table, $BaseKey, $key){
        $baseTable = self::$table;
        self::$joinedTable = $table;
        self::$sql .= " INNER JOIN $table ON $table.$key = $baseTable.$BaseKey";
        // self::$table = $table;
        return $this;
    }

    /**
     * Add additional 'AND' clause to Inner join statement
     * e.g. : " AND $table.$key = $baseTable.$baseKey"
     */

    public function JoinAnd($baseKey, $key){
        $baseTable = self::$table;
        $table = self::$joinedTable;
        self::$sql .= " AND $table.$key = $baseTable.$baseKey";

        return $this;
    }

    public function AddKeysFromTable($tb1, $tb1_key, $tb2, $tb2_key){
        self::$sql .= " AND $tb1.$tb1_key = $tb2.$tb2_key";

        return $this;
    }

    public function Execute(){

        $db = new DatabaseShit();
        // echo self::$sql;
        $q = $db->getConnection()->prepare(self::$sql);
        $q->execute();

        $results = [
            'data' => $q->fetchAll(PDO::FETCH_ASSOC),
            'rowCount' => $q->rowCount()
        ];
        return $results;
    }
}
