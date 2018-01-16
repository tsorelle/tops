<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 1/15/2018
 * Time: 10:50 AM
 */

namespace Tops\services;


use Tops\sys\TConfiguration;
use Tops\sys\TObjectContainer;

class DownloadServiceFactory extends TAbstractServiceFactory
{

    private static $instance;

    public static function Execute()
    {
        if (!isset(self::$instance)) {
            self::$instance = new ServiceFactory();
        }
        return self::$instance->executeService();
    }

    /**
     * @throws \Exception
     */
    public static function PrintOutput() {
        $response = self::Execute();
        if (empty($response) || empty($response->Value) || (!isset($response->value->data))) {
            throw new \Exception('Error invalid download response.');
        }
        $fileName = empty($response->Value->filename ? 'download' : $response->Value->filename);
        if ($response->Result === ResultType::Errors) {
            $errors = [];
            $errors[] = '"Message","Cannot download. Errors occurred';
            foreach ($response->Messages as $message) {
                switch ($message->MessageType) {
                    case MessageType::Error :
                        $type = 'Error';
                        break;
                    case MessageType::Warning :
                        $type = 'Warning';
                        break;
                    default:
                        continue;
                }
                $errors[] = sprintf('"%s","%s"',$type,$message->Text);
                $data = join("\n",$errors);
            }
        }
        else {
            $data = empty($response->Value->data) ? 'No data returned' : $response->Value->data;
        }
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private",false);
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"$fileName.csv\";" );
        header("Content-Transfer-Encoding: binary");
        print $data;
    }
}