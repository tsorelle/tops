<?php 
/** 
 * Created by /tools/create-bookstore.php
 * Time:  2017-08-16 22:52:43
 */ namespace TwoQuakers\testing\db;

use \PDO;
use PDOStatement;
use Tops\db\TDatabase;
use Tops\db\TEntityRepository;

class CustomerRepository extends TEntityRepository 
{
    protected function getClassName() {
        return 'TwoQuakers\testing\model\Customer';
    }

    protected function getTableName() {
        return 'bookstore_customers';
    }

    protected function getDatabaseId() {
        return 'tops-db';
    }

    protected function getFieldDefinitionList()
    {
        return array(
        'id'=>PDO::PARAM_INT,
        'customertypeid'=>PDO::PARAM_INT,
        'name'=>PDO::PARAM_STR,
        'address'=>PDO::PARAM_STR,
        'city'=>PDO::PARAM_STR,
        'state'=>PDO::PARAM_STR,
        'postalcode'=>PDO::PARAM_STR,
        'buyer'=>PDO::PARAM_STR,
        'createdby'=>PDO::PARAM_STR,
        'createdon'=>PDO::PARAM_STR,
        'changedby'=>PDO::PARAM_STR,
        'changedon'=>PDO::PARAM_STR,
        'active'=>PDO::PARAM_STR);
    }
}