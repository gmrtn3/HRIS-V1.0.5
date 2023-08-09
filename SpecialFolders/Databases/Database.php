<?php
// namespace Db;
// use PDO;
// use PDOException;
class DatabaseShit{
    private $envFile = __DIR__ . '/.env';

    private $host;
    private $database;
    private $username;
    private $password;
    protected $conn;

    public function __construct()
    {
        if(file_exists($this->envFile)){
            $envVars = parse_ini_file($this->envFile);
            foreach($envVars as $key => $value){
                putenv("$key=$value");
            }
        }

        $this->host = getenv('DB_HOST');
        $this->database = getenv('DATABASE');
        $this->username = getenv('DB_USERNAME');
        $this->password = getenv('DB_PASSWORD');

        try{
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->database;", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $e){
            die("Connection error: " . $e->getMessage());
        }
    }
    public function getConnection(){
        return $this->conn;
    }
}
