<?php 
/** 
 * Created by /tools/create-bookstore.php 
 * Time:  2017-08-11 19:45:38
 */ 

namespace TwoQuakers\testing\db;

use \PDO; 
class SupplierTitleRepository 
{
    public static function Get($id) { 
        $dbh = Database::getConnection();
        $sql = "SELECT * FROM bookstore_supplier_titles WHERE id = ?";
        /** 
         * @var PDOStatement 
         */ 
        $stmt = $dbh->prepare($sql); 
        $stmt->execute(array($id)); 
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'TwoQuakers\testing\model\SupplierTitle'); 
        $result = $stmt->fetch(); 
        return $result; 
    } 
 
    public static function Update($dto, $userName = 'admin') { 
        $dbh = Database::getConnection(); 
        $sql = 
            "UPDATE bookstore_supplier_titles SET ".
            "id = :id, \n".
"supplierid = :supplierid, \n".
"titleid = :titleid, \n".
"discountquantity = :discountquantity, \n".
"unitprice = :unitprice, \n".
"effectivedate = :effectivedate, \n".
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
$stmt->bindValue(':supplierid', $dto->supplierid, PDO::PARAM_INT);
$stmt->bindValue(':titleid', $dto->titleid, PDO::PARAM_INT);
$stmt->bindValue(':discountquantity', $dto->discountquantity, PDO::PARAM_INT);
$stmt->bindValue(':unitprice', $dto->unitprice, PDO::PARAM_STR);
$stmt->bindValue(':effectivedate', $dto->effectivedate, PDO::PARAM_STR);
$stmt->bindValue(':changedby', $userName ,PDO::PARAM_STR	);
$stmt->bindValue(':changedon', $date	  ,PDO::PARAM_STR	); 
        $count = $stmt->execute(); 
        $result = $dbh->lastInsertId(); 
        return $result;  
    } 
 
    public static function Create($dto,$userName = 'admin') { 
        $dbh = Database::getConnection(); 
        $sql = "INSERT INTO bookstore_supplier_titles (  id, supplierid, titleid, discountquantity, unitprice, effectivedate, createdby, createdon, changedby, changedon) ". 
                "VALUES ( :id, :supplierid, :titleid, :discountquantity, :unitprice, :effectivedate, :createdby, :createdon, :changedby, :changedon)"; 

        $today = new \DateTime(); 
        $date = $today->format('Y-m-d H:i:s'); 

        /** 
         * @var PDOStatement 
         */ 
        $stmt = $dbh->prepare($sql); 
        
$stmt->bindValue(':id', $dto->id, PDO::PARAM_INT);
$stmt->bindValue(':supplierid', $dto->supplierid, PDO::PARAM_INT);
$stmt->bindValue(':titleid', $dto->titleid, PDO::PARAM_INT);
$stmt->bindValue(':discountquantity', $dto->discountquantity, PDO::PARAM_INT);
$stmt->bindValue(':unitprice', $dto->unitprice, PDO::PARAM_STR);
$stmt->bindValue(':effectivedate', $dto->effectivedate, PDO::PARAM_STR);
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
        $sql = "DELETE FROM bookstore_supplier_titles WHERE id = ?"; 
        /** 
         * @var PDOStatement 
         */ 
        $stmt = $dbh->prepare($sql); 
        $stmt->execute(array($id)); 
    } 

    public static function GetAll($where = '' ) { 
        $dbh = \Tops\db\TDatabase::getConnection(); 
        $sql = "SELECT * FROM bookstore_supplier_titles"; 
        if ($where) { 
            $sql .= " WHERE $where"; 
        } 

        /** 
         * @var PDOStatement 
         */ 
        $stmt = $dbh->prepare($sql); 
        $stmt->execute(); 

        $result = $stmt->fetchAll(PDO::FETCH_CLASS,'TwoQuakers\testing\model\SupplierTitle'); 
        return $result; 
    } 
} 
