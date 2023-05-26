<?php
class Database
{
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;
    private $charset="utf8mb4";
    private  $dbh;
    private $stmt;
    private $error;
    public function __construct()
    {
        //set DSN
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname. ';charset='.$this->charset;
        $options = array(
            PDO::ATTR_PERSISTENT => true, //CHECK IF THE CONNECTION IS ALREADY LIVE
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES=>false

        );
        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) { 
            
           
            $this->error = $e->getMessage().(int)$e->getCode();
            echo $this->error;// send to email report and echo something else
            exit;
        }
    }
    public function query($sql)
    {
        $this->stmt = $this->dbh->prepare($sql);
    }
    public function bind($pram, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;

                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($pram, $value, $type);
    }
    public function execute()
    {
        return $this->stmt->execute();
    }
    public function resultSet()
    {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function single()
    {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }
    public function rowCount()
    {
        return $this->stmt->rowCount();
    }
    public function begin()
    {
        $this->dbh->beginTransaction();
    }
    public function commit()
    {
        $this->dbh->commit();
    }
    public function rollback()
    {
        $this->dbh->rollback();
    }
}
