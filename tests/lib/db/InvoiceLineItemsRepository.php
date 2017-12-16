<?php 
/** 
 * Created by /tools/create-bookstore.php 
 * Time:  2017-08-11 19:18:51
 */ 

namespace TwoQuakers\testing\db;

use \PDO; 
class InvoiceLineItemsRepository 
{
    public static function Get($id) { 
        $dbh = Database::getConnection();
        $sql = "SELECT * FROM bookstore_invoicelineitems WHERE id = ?";
        /** 
         * @var PDOStatement 
         */ 
        $stmt = $dbh->prepare($sql); 
        $stmt->execute(array($id)); 
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'TwoQuakers\testing\model\InvoiceLineItems'); 
        $result = $stmt->fetch(); 
        return $result; 
    } 
 
    public static function Update($dto, $userName = 'admin') { 
        $dbh = Database::getConnection(); 
        $sql = 
            "UPDATE bookstore_invoicelineitems SET ".
            "id = :id, \n".
"invoiceid = :invoiceid, \n".
"titleid = :titleid, \n".
"supplierid = :supplierid, \n".
"quantity = :quantity, \n".
"cost = :cost, \n".
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
$stmt->bindValue(':invoiceid', $dto->invoiceid, PDO::PARAM_INT);
$stmt->bindValue(':titleid', $dto->titleid, PDO::PARAM_INT);
$stmt->bindValue(':supplierid', $dto->supplierid, PDO::PARAM_INT);
$stmt->bindValue(':quantity', $dto->quantity, PDO::PARAM_INT);
$stmt->bindValue(':cost', $dto->cost, PDO::PARAM_STR);
$stmt->bindValue(':changedby', $userName ,PDO::PARAM_STR	);
$stmt->bindValue(':changedon', $date	  ,PDO::PARAM_STR	); 
        $count = $stmt->execute(); 
        $result = $dbh->lastInsertId(); 
        return $result;  
    } 
 
    public static function Create($dto,$userName = 'admin') { 
        $dbh = Database::getConnection(); 
        $sql = "INSERT INTO bookstore_invoicelineitems (  id, invoiceid, titleid, supplierid, quantity, cost, createdby, createdon, changedby, changedon) ". 
                "VALUES ( :id, :invoiceid, :titleid, :supplierid, :quantity, :cost, :createdby, :createdon, :changedby, :changedon)"; 

        $today = new \DateTime(); 
        $date = $today->format('Y-m-d H:i:s'); 

        /** 
         * @var PDOStatement 
         */ 
        $stmt = $dbh->prepare($sql); 
        
$stmt->bindValue(':id', $dto->id, PDO::PARAM_INT);
$stmt->bindValue(':invoiceid', $dto->invoiceid, PDO::PARAM_INT);
$stmt->bindValue(':titleid', $dto->titleid, PDO::PARAM_INT);
$stmt->bindValue(':supplierid', $dto->supplierid, PDO::PARAM_INT);
$stmt->bindValue(':quantity', $dto->quantity, PDO::PARAM_INT);
$stmt->bindValue(':cost', $dto->cost, PDO::PARAM_STR);
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
        $sql = "DELETE FROM bookstore_invoicelineitems WHERE id = ?"; 
        /** 
         * @var PDOStatement 
         */ 
        $stmt = $dbh->prepare($sql); 
        $stmt->execute(array($id)); 
    } 

    public static function GetAll($where = '' ) { 
        $dbh = \Tops\db\TDatabase::getConnection(); 
        $sql = "SELECT * FROM bookstore_invoicelineitems"; 
        if ($where) { 
            $sql .= " WHERE $where"; 
        } 

        /** 
         * @var PDOStatement 
         */ 
        $stmt = $dbh->prepare($sql); 
        $stmt->execute(); 

        $result = $stmt->fetchAll(PDO::FETCH_CLASS,'TwoQuakers\testing\model\InvoiceLineItems'); 
        return $result; 
    } 
} 
