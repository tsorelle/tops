<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 5/5/2017
 * Time: 7:29 AM
 */
$root = realpath(__DIR__.'/../');
$dbPath = $root.'/bookstore/src/db/';
include($root.'/bookstore/src/db/Database.php');
include($root.'/bookstore/src/db/model/TimeStampedEntity.php');
// include($root.'/bookstore/src/db/model/Example.php');
$dbh = Bookstore\db\Database::getConnection();
$date = new DateTime('2000-01-01');

function buildSource($dbh, $tableName,$dbPath)
{
    $modelsPath = $dbPath.'model/';
    $date = new DateTime();

    print "\nBuilding $tableName...\n";

    $q = $dbh->prepare("DESCRIBE $tableName");
    $q->execute();
    $fields = $q->fetchAll(PDO::FETCH_OBJ);

    $className = strtoupper(substr($tableName, 0, 1)) . substr($tableName, 1);

    $plural = substr($className, strlen($className) - 1);
    if ($plural == 's') {
        $className = substr($className, 0, strlen($className) - 1);
    }
    $superclass = empty($fields["createdon"]) ? '' : ' extends TimeStampedEntity';
    $entity =
        "<?php \n" .
        "/** \n" .
        " * Created by /tools/create-model.php \n" .
        " * Time:  " . $date->format('Y-m-d H:i:s') . "\n" .
        " */ \n\n" .
        "namespace Bookstore\\db\\model;" . "\n\n" .
        "class $className $superclass \n" .
        "{ \n";

    $updateBindings = "";
    $insertBindings = "";
    $updateFields = array();
    $insertNames = array();
    $insertValues = array();


    foreach ($fields as $field) {
        switch ($field->Field) {
            case 'createdby' :
                $insertBindings .= "\n\$stmt->bindValue(':createdby', \$userName ,PDO::PARAM_STR	);";
                break;
            case 'createdon' :
                $insertBindings .= "\n\$stmt->bindValue(':createdon', \$date	  ,PDO::PARAM_STR	);";
                break;
            case 'changedby' :
                $updateBindings .= "\n\$stmt->bindValue(':changedby', \$userName ,PDO::PARAM_STR	);";
                $updateFields[] = "\"changedby  = :changedby";
                break;
            case 'changedon' :
                $updateBindings .= "\n\$stmt->bindValue(':changedon', \$date	  ,PDO::PARAM_STR	);";
                $updateFields[] = "\"changedon  = :changedon";
                break;
            default:
                $entity .= '    public $' . $field->Field . ";\n";
                $type = explode('(', $field->Type)[0];
                $type = $type == 'int' ? 'INT' : 'STR';
                $updateBindings .= "\n\$stmt->bindValue(':$field->Field', \$dto->$field->Field, PDO::PARAM_$type);";
                $updateFields[] =  "\"$field->Field = :$field->Field";
                break;
        }

        $insertNames[] = ' '.$field->Field;
        $insertValues[] = " :$field->Field";

    }

    $updateFields = join(", \\n\".\n",$updateFields)."\".\n";
    $insertNames = join(",",$insertNames);
    $insertValues = join(",",$insertValues);


    $entity .= "} \n";


    $fullClassName = 'Bookstore\\db\\model\\' . $className;


    print "\nRepository for $tableName\n\n";

    $repos =
        "<?php \n".
        "/** \n" .
        " * Created by /tools/create-model.php \n" .
        " * Time:  " . $date->format('Y-m-d H:i:s') . "\n" .
        " */ \n\n" .
        'namespace Bookstore\db; ' . "\n" .
        'use \PDO; ' . "\n" .
        "class $className" . "Repository \n" .
        "{\n" .
        "    public static function Get(\$id) { \n" .
        "        \$dbh = Database::getConnection();\n" .
        "        \$sql = \"SELECT * FROM $tableName WHERE id = ?\";\n" .
        "        /** \n" .
        "         * @var PDOStatement \n" .
        "         */ \n" .
        "        \$stmt = \$dbh->prepare(\$sql); \n" .
        "        \$stmt->execute(array(\$id)); \n" .
        "        \$stmt->setFetchMode(PDO::FETCH_CLASS, '$fullClassName'); \n" .
        "        \$result = \$stmt->fetch(); \n" .
        "        return \$result; \n" .
        "    } \n" .
        " \n" .
        "    public static function Update(\$dto, \$userName = 'admin') { \n" .
        "        \$dbh = Database::getConnection(); \n" .
        "        \$sql = \n" .
        "            \"UPDATE $tableName SET \".\n" .
        "            $updateFields \n" .
        "        \"WHERE id = :id\"; \n" .
        "        \$today = new \\DateTime();  \n" .
        "        \$date = \$today->format('Y-m-d H:i:s');  \n\n" .
        "        /** \n" .
        "         * @var PDOStatement \n" .
        "         */ \n" .
        "        \$stmt = \$dbh->prepare(\$sql);  \n" .
        "        $updateBindings \n" .
        "        \$count = \$stmt->execute(); \n" .
        "        \$result = \$dbh->lastInsertId(); \n" .
        "        return \$result;  \n" .
        "    } \n" .
        " \n" .
        "    public static function Create(\$dto,\$userName = 'admin') { \n" .
        "        \$dbh = Database::getConnection(); \n" .
        "        \$sql = \"INSERT INTO $tableName ( $insertNames) \". \n" .
        "                \"VALUES ($insertValues)\"; \n\n" .
        "        \$today = new \\DateTime(); \n" .
        "        \$date = \$today->format('Y-m-d H:i:s'); \n\n" .
        "        /** \n" .
        "         * @var PDOStatement \n" .
        "         */ \n" .
        "        \$stmt = \$dbh->prepare(\$sql); \n" .
        "        $updateBindings  \n\n" .
        "        $insertBindings  \n\n" .
        "        \$count = \$stmt->execute(); \n" .
        "        \$result = \$dbh->lastInsertId(); \n" .
        "        return \$result; \n" .
        "    } \n\n" .
        "    public static function Delete(\$id) { \n" .
        "        \$dbh = Database::getConnection(); \n" .
        "        \$sql = \"DELETE FROM $tableName WHERE id = ?\"; \n" .
        "        /** \n" .
        "         * @var PDOStatement \n" .
        "         */ \n" .
        "        \$stmt = \$dbh->prepare(\$sql); \n" .
        "        \$stmt->execute(array(\$id)); \n" .
        "    } \n\n" .
        "    public static function GetAll(\$where = '' ) { \n" .
        "        \$dbh = Database::getConnection(); \n" .
        "        \$sql = \"SELECT * FROM $tableName\"; \n" .
        "        if (\$where) { \n" .
        "            \$sql .= \" WHERE \$where\"; \n" .
        "        } \n\n" .
        "        /** \n" .
        "         * @var PDOStatement \n" .
        "         */ \n" .
        "        \$stmt = \$dbh->prepare(\$sql); \n" .
        "        \$stmt->execute(); \n\n" .
        "        \$result = \$stmt->fetchAll(PDO::FETCH_CLASS,'$fullClassName'); \n" .
        "        return \$result; \n" .
        "    } \n" .
        "} \n";


    file_put_contents($modelsPath.$className.'.php',$entity);
    file_put_contents($dbPath.$className.'Repository.php',$repos);
    print $entity;
    print $repos;
}


$q = $dbh->prepare("SHOW TABLES");
$q->execute();
$tables = $q->fetchAll(PDO::FETCH_COLUMN);

foreach ($tables as $table) {
    if ($table != 'examples') {
        buildSource($dbh, $table, $dbPath);
    }
}


// var_dump($tables);

print("\nDone.\n");