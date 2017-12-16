<?php 
/** 
 * Created by /tools/create-bookstore.php 
 * Time:  2017-08-11 19:45:38
 */ 

namespace TwoQuakers\testing\db;

use \PDO; 
class SupplierRepository 
{
    public static function Get($id) { 
        $dbh = Database::getConnection();
        $sql = "SELECT * FROM bookstore_suppliers WHERE id = ?";
        /** 
         * @var PDOStatement 
         */ 
        $stmt = $dbh->prepare($sql); 
        $stmt->execute(array($id)); 
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'TwoQuakers\testing\model\Supplier'); 
        $result = $stmt->fetch(); 
        return $result; 
    } 
 
    public static function Update($dto, $userName = 'admin') { 
        $dbh = Database::getConnection(); 
        $sql = 
            "UPDATE bookstore_suppliers SET ".
            "id = :id, \n".
"name = :name, \n".
"address = :address, \n".
"city = :city, \n".
"state = :state, \n".
"postalcode = :postalcode, \n".
"changedby  = :changedby, \n".
"changedon  = :changedon".
 
        "WHERE id = :id"; 
        $today = new \DateTime();  
        $date = $today->format('Y-m-d H:i:s');  

        /** 
         * @var PDOStatement 
         */ 
        $stmt = $dbh->prepare($sql);  
        
$stmt->bindValue(':id', $dto->id, PDO::PARAM_INT);
$stmt->bindValue(':name', $dto->name, PDO::PARAM_STR);
$stmt->bindValue(':address', $dto->address, PDO::PARAM_STR);
$stmt->bindValue(':city', $dto->city, PDO::PARAM_STR);
$stmt->bindValue(':state', $dto->state, PDO::PARAM_STR);
$stmt->bindValue(':postalcode', $dto->postalcode, PDO::PARAM_STR);
$stmt->bindValue(':changedby', $userName ,PDO::PARAM_STR	);
$stmt->bindValue(':changedon', $date	  ,PDO::PARAM_STR	); 
        $count = $stmt->execute(); 
        $result = $dbh->lastInsertId(); 
        return $result;  
    } 
 
    public static function Create($dto,$userName = 'admin') { 
        $dbh = Database::getConnection(); 
        $sql = "INSERT INTO bookstore_suppliers (  id, name, address, city, state, postalcode, createdby, createdon, changedby, changedon) ". 
                "VALUES ( :id, :name, :address, :city, :state, :postalcode, :createdby, :createdon, :changedby, :changedon)"; 

        $today = new \DateTime(); 
        $date = $today->format('Y-m-d H:i:s'); 

        /** 
         * @var PDOStatement 
         */ 
        $stmt = $dbh->prepare($sql); 
        
$stmt->bindValue(':id', $dto->id, PDO::PARAM_INT);
$stmt->bindValue(':name', $dto->name, PDO::PARAM_STR);
$stmt->bindValue(':address', $dto->address, PDO::PARAM_STR);
$stmt->bindValue(':city', $dto->city, PDO::PARAM_STR);
$stmt->bindValue(':state', $dto->state, PDO::PARAM_STR);
$stmt->bindValue(':postalcode', $dto->postalcode, PDO::PARAM_STR);
$stmt->bindValue(':changedby', $userName ,PDO::PARAM_STR	);
$stmt->bindValue(':changedon', $date	  ,PDO::PARAM_STR	);  

        
$stmt->bindValue(':createdby', $userName ,PDO::PARAM_STR	);
$stmt->bindValue(':createdon', $date	  ,PDO::PARAM_STR	);  

        $count = $stmt->execute(); 
        $result = $dbh->lastInsertId(); 
        return $result; 
    } 

    public static function Delete($id) { 
        $dbh = Database::getConnection(); 
        $sql = "DELETE FROM bookstore_suppliers WHERE id = ?"; 
        /** 
         * @var PDOStatement 
         */ 
        $stmt = $dbh->prepare($sql); 
        $stmt->execute(array($id)); 
    } 

    public static function GetAll($where = '' ) { 
        $dbh = \Tops\db\TDatabase::getConnection(); 
        $sql = "SELECT * FROM bookstore_suppliers"; 
        if ($where) { 
            $sql .= " WHERE $where"; 
        } 

        /** 
         * @var PDOStatement 
         */ 
        $stmt = $dbh->prepare($sql); 
        $stmt->execute(); 

        $result = $stmt->fetchAll(PDO::FETCH_CLASS,'TwoQuakers\testing\model\Supplier'); 
        return $result; 
    } 
} 
