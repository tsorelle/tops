<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 12/15/2017
 * Time: 12:50 PM
 */

namespace Tops\services;


use Tops\db\model\entity\Process;
use Tops\db\model\entity\ProcessLogEntry;
use Tops\db\model\repository\ProcessesRepository;
use Tops\db\model\repository\ProcessLogRepository;
use Tops\sys\TDates;

class TProcessManager
{
    /**
     * @var ProcessesRepository
     */
    private static $processRepository;
    /**
     * @var ProcessLogRepository
     */
    private static $logRepository;

    private static $managers = [];
    
    private static function getProcessRepository()
    {
        if (!isset(self::$processRepository)) {
            self::$processRepository = new ProcessesRepository();
        }
        return self::$processRepository;
    }

    private static function getlogRepository()
    {
        if (!isset(self::$logRepository)) {
            self::$logRepository = new ProcessesRepository();
        }
        return self::$logRepository;
    }

    private $code;

    public function __construct($code)
    {
        $this->code = $code;
    }
    
    public static function CreateProcess($code,$name,$description) {
        $repository = self::getProcessRepository();
        /**
         * @var $process Process
         */
        $process = $repository->getEntity($code);
        if (empty($process)) {
            $process = new Process();
            $process->code = $code;
            $process->name = $name;
            $process->description = $description;
            $process->paused = null;
            $repository->insert($process);
        }
        return self::Start($code);
    }

    public static function Start($code) {
        $processManager = new TProcessManager($code);
        $started = $processManager->startProcess();
        if ($started) {
            self::$managers[$code] = $processManager;
            return $processManager;
        }
        return false;
    }

    public static function Get($code) {
        if (array_key_exists($code,self::$managers)) {
            return self::$managers[$code];
        }
        return self::Start($code);
    }

    private function getProcess() {
        $repository = self::getProcessRepository();
        /**
         * @var $process Process
         */
        $process = $repository->getEntity($this->code);
        if (empty($process)) {
            $this->log('start','error:process not defined.');
            return false;
        }
        return $process;
    }

    public function pauseProcess($reason='process paused',$interval='1 hour') {
        $process = $this->getProcess();
        if ($process === false) {
            return false;
        }
        $expiry =  new \DateTime();
        $expiry->add(TDates::StringToInterval($interval));
        $process->paused = $expiry->format(TDates::MySqlDateTimeFormat);
        self::getProcessRepository()->update($process);
        $this->log('pause',$reason);
        return true;
    }

    public function isPaused() {
        $process = $this->getProcess();
        if ($process === false || $process->paused === null) {
            return false;
        }
        $diff = TDates::CompareWithNow($process->paused);
        return $diff == TDates::Before;
    }

    public function startProcess() {
        /**
         * @var $process Process
         */
        $process = $this->getProcess();
        if ($process === false) {
            return false;
        }
        if ($process->paused !== null) {
            $process->paused = null;
            self::getProcessRepository()->update($process);
        }
        return true;
    }

    public function log($event,$message='',$messageType=MessageType::Info,$detail=null) {

        $entry = new ProcessLogEntry();
        $entry->processCode = $this->code;
        $entry->event = $event;
        $entry->message = $message;
        $entry->messageType = $messageType;
        $entry->detail = $detail;
        self::getlogRepository()->insert($entry);
    }

    public function logError($event,$message='',$detail=null) {
        $this->log($event,$message,MessageType::Error,$detail);
    }

    public function logWarning($event,$message='',$detail=null) {
        $this->log($event,$message,MessageType::Warning,$detail);
    }

    public function logException(\Exception $ex,$event=null) {
        if ($event==null) {
            $event = 'Exception';
        }
        $message =  sprintf('%s. $s Line:d',$ex->getMessage(),$ex->getFile(),$ex->getLine());
        $detail = $ex->getTraceAsString();
        $this->logError($event,$message,$detail);
    }
}