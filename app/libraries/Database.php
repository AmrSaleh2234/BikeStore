<?php

/*
 * PDO DATABASE CLASS
 * connect to database
 * create prepared statements
 * Bind Values
 * return rows and results
 */
namespace MVCPHP\libraries;
class Database
{
     private $host=DB_HOST;
     private $user=DB_USER;
     private $pass=DB_PASS;
     private $dbname=DB_NAME;
     private  $dbh;
     private $stmt;
     private $error;
     public function __construct()
     {
         $dsn ='mysql:host='.$this->host.';dbname='.$this->dbname;
         $options =array(
             \PDO::ATTR_PERSISTENT=>true,
             \PDO::ATTR_ERRMODE=> \PDO::ERRMODE_EXCEPTION
         );
         //create PDO instance
         try {
             $this->dbh =new \PDO($dsn,$this->user,$this->pass,$options);

         }catch (\PDOException $e)
         {
            $this->error=  $e->getMessage();
            echo $this->error;
         }
     }
     //prepare statement with query
    public function query($sql)
    {
        $this->stmt =$this->dbh->prepare($sql);
    }

    //Bnd values
    public function bind($param,$value ,$type =null)
    {
        if(is_null($type))
        {
            switch (true)
            {
                case  is_int($value):
                    $type=\PDO::PARAM_INT;
                    break;
                case  is_bool($value):
                    $type=\PDO::PARAM_BOOL;
                    break;
                case  is_null($value):
                    $type=\PDO::PARAM_NULL;
                    break;
                default :
                    $type=\PDO::PARAM_STR;

            }
        }
        $this->stmt->bindValue($param,$value,$type);
    }
    //execute prepare statement
    public function execute()
    {
        return $this->stmt->execute();
    }
     //get result set of array
    public function resultSet()
    {
        $this->execute();
        return $this->stmt->fetchAll(\PDO::FETCH_OBJ);
    }
    //get single record
    public function single()
    {
        $this->execute();
        return $this->stmt->fetch(\PDO::FETCH_OBJ);

    }
    public function rowCount()
    {
         return $this->stmt->rowCount();
    }


    /************************/
    public function select($table,$row="*",$where=null,$order=null){
        $query='SELECT '.$row.' FROM '.$table;
        if($where!=null){
            $query.=' WHERE '.$where;
        }
        if($order!=null){
            $query.=' ORDER BY '.$order;
        }
        $this->query($query);
            echo $query;

       return $this->resultSet();

    }
    public function insert($table,$value,$row=null){
        $insert= " INSERT INTO ".$table;
        if($row!=null){
            $insert.=" (". $row." ) ";
        }
        for($i=0; $i<count($value); $i++){
            if(is_string($value[$i])){
                $value[$i]= '"'. $value[$i] . '"';
            }
        }
        $value=implode(',',$value);
        $insert.=' VALUES ('.$value.')';
        echo $insert.'<br>';


       return $this->dbh->exec($insert);


    }
    public function delete($table,$where=null){
        if($where == null)
        {
            $delete = "DELETE ".$table;
        }
        else
        {
            $delete = "DELETE  FROM ".$table." WHERE ".$where;
        }
      //  echo $delete;
       return $this->dbh->exec($delete);
    }
    public function update($table,$rows,$where){
        // Parse the where values
        // even values (including 0) contain the where rows
        // odd values contain the clauses for the row
        for($i = 0; $i < count($where); $i++)
        {
            if($i%2 != 0)
            {
                if(is_string($where[$i]))
                {
                    if(($i+1) != null)
                        $where[$i] = '"'.$where[$i].'" AND ';
                    else
                        $where[$i] = '"'.$where[$i].'"';
                }
            }
        }
        $where = implode(" ",$where);


        $update = 'UPDATE '.$table.' SET ';
        $keys = array_keys($rows);
        for($i = 0; $i < count($rows); $i++)
        {
            if(is_string($rows[$keys[$i]]))
            {
                $update .= $keys[$i].'="'.$rows[$keys[$i]].'"';
            }
            else
            {
                $update .= $keys[$i].'='.$rows[$keys[$i]];
            }

            // Parse to add commas
            if($i != count($rows)-1)
            {
                $update .= ',';
            }
        }
        $update .= ' WHERE '.$where;
        echo $update;
        return $this->dbh->exec($update);

    }

}