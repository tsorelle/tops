<?php 
/** 
 * Created by /tools/create-bookstore.php
 * Time:  2017-08-11 19:45:38
 */ 

namespace TwoQuakers\testing\db;

use \PDO; 
class InvoiceRepository 
{
    public static function Get($id) { 
        $dbh = Database::getConnection();
        $sql = "SELECT * FROM bookstore_invoices WHERE id = ?";
        /** 
         * @var PDOStatement 
         */ 
        $stmt = $dbh->prepare($sql); 
        $stmt->execute(array($id)); 
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'TwoQuakers\testing\model\Invoice'); 
        $result = $stmt->fetch(); 
        return $result; 
    } 
 
    public static function Update($dto, $userName = 'admin') { 
        $dbh = Database::getConnection(); 
        $sql = 
            "UPDATE bookstore_invoices SET ".
            "id = :id, \n".
"customerid = :customerid, \n".
"status = :status, \n".
"invoicedate = :invoicedate, \n".
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
$stmt->bindValue(':customerid', $dto->customerid, PDO::PARAM_INT);
$stmt->bindValue(':status', $dto->status, PDO::PARAM_INT);
$stmt->bindValue(':invoicedate', $dto->invoicedate, PDO::PARAM_STR);
$stmt->bindValue(':changedby', $userName ,PDO::PARAM_STR	);
$stmt->bindValue(':changedon', $date	  ,PDO::PARAM_STR	); 
        $count = $stmt->execute(); 
        $result = $dbh->lastInsertId(); 
        return $result;  
    } 
 
    public static function Create($dto,$userName = 'admin') { 
        $dbh = Database::getConnection(); 
        $sql = "INSERT INTO bookstore_invoices (  id, customerid, status, invoicedate, createdby, createdon, changedby, changedon) ". 
                "VALUES ( :id, :customerid, :status, :invoicedate, :createdby, :createdon, :changedby, :changedon)"; 

        $today = new \DateTime(); 
        $date = $today->format('Y-m-d H:i:s'); 

        /** 
         * @var PDOStatement 
         */ 
        $stmt = $dbh->prepare($sql); 
        
$stmt->bindValue(':id', $dto->id, PDO::PARAM_INT);
$stmt->bindValue(':customerid', $dto->customerid, PDO::PARAM_INT);
$stmt->bindValue(':status', $dto->status, PDO::PARAM_INT);
$stmt->bindValue(':invoicedate', $dto->invoicedate, PDO::PARAM_STR);
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
        $sql = "DELETE FROM bookstore_invoices WHERE id = ?"; 
        /** 
         * @var PDOStatement 
         */ 
        $stmt = $dbh->prepare($sql); 
        $stmt->execute(array($id)); 
    } 

    public static function GetAll($where = '' ) { 
        $dbh = \Tops\db\TDatabase::getConnection(); 
        $sql = "SELECT * FROM bookstore_invoices"; 
        if ($where) { 
            $sql .= " WHERE $where"; 
        } 

        /** 
         * @var PDOStatement 
         */ 
        $stmt = $dbh->prepare($sql); 
        $stmt->execute(); 

        $result = $stmt->fetchAll(PDO::FETCH_CLASS,'TwoQuakers\testing\model\Invoice'); 
        return $result; 
    } 
} 
