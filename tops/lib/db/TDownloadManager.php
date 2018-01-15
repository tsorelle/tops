<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 1/15/2018
 * Time: 8:43 AM
 */

namespace Tops\db;


use PDO;

class TDownloadManager extends TPdoQueryManager
{

    public function __construct($databaseId='')
    {
        $this->databaseId = $databaseId;
    }

    private $databaseId = '';
    public function setDatabaseId($databaseId) {
        $this->databaseId = $databaseId;
    }

    public function getCsvData($sql,array $params=[],array $types =[],$includeHeader=true ) {
        $stmt = $this->executeStatement($sql,$params);
        $records = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $this->objectsToCsv($records,$types,$includeHeader);
    }

    public function objectsToCsv(array $records, array $types= [], $includeHeader=true) {
        $result = [];
        if (sizeof($records) == 0) {
            return $result;
        }
        if ($includeHeader) {
            $header = '';
            foreach ($records[0] as $fieldName => $value) {
                if (!empty($header)) {
                    $header .= ',';
                }
                $header .= '"'.$fieldName.'"';
            }
            $result[] = $header;
        }

        foreach ($records as $record) {
            $line = '';
            if (!empty($line)) {
                $line .= ',';
            }
            foreach ($record as $fieldName => $value) {
                if (!empty($line)) {
                    $line .= ',';
                }
                $type = array_key_exists($fieldName,$types) ? $types[$fieldName] : 'string';
                $line .= $this->fieldValue($value,$type);
            }
            $result[] = $line;
        }
        return $result;
    }

    private function fieldValue($value,$type = 'string') {
        $result = $value === null ? '' : $value;
        if ($type == 'string') {
            $result = '"'.str_replace('""','"',$result).'"';
        }
        return $result;
    }

    protected function getDatabaseId()
    {
        return $this->databaseId;
    }
}