<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 8/30/2017
 * Time: 6:33 AM
 */

namespace Tops\mail;


use Tops\sys\TLanguage;
use Tops\sys\TObjectContainer;

class TEmailValidator
{
    private $warnings=array();
    private $error;
    private $result = 0;
    private $emailAddress = false;
    private $dnsFailed = false;

    private static $instance;
    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new TEmailValidator();
        }
        return self::$instance;
    }

    public static function Create()
    {
        return new TEmailValidator();
    }

    /**
     * @var IEmailValidator
     */
    private static $externalValidator;
    public static function getExternalValidator() {
        if (!isset(self::$externalValidator)) {
            if ( TObjectContainer::HasDefinition('tops.mailvalidator')) {
                /**
                 * @var IEmailValidator
                 */
                self::$externalValidator = TObjectContainer::Get('tops.mailvalidator');
            }
            else {
                self::$externalValidator = null;
            }
        }
        return self::$externalValidator;
    }

    public static function Validate($emailAddress,$doubleCheck = false) {
        $result = new \stdClass();
        $validator = self::getInstance();
        $valid = $validator->isValid($emailAddress);
        $result->usedExternal = false;
        if ($doubleCheck && $valid) {
            $external = self::getExternalValidator();
            if ($external !== null) {
                $response = $external->validate($emailAddress);
                $valid = ($response === true);
                if ((!$valid) && !empty($response->suggestion)) {
                    $result->suggestion = $response->suggestion;
                }
                $result->usedExternal = true;
            }
        }
        $result->isValid = $valid;
        $result->error = $validator->getError();
        $result->warnings = $validator->getWarnings();
        return $result;
    }

    public static function Invalid($emailAddress,$doubleCheck = false) {
        $validation = self::Validate($emailAddress,$doubleCheck);
        return !$validation->isValid;
    }

    private function getEmailAddress() {
        return $this->emailAddress;
    }

    private  function clear()
    {
        $this->warnings=array();
        $this->error;
        $this->result = 0;
        $this->name = '';
        $this->address = '';
    }

    private function parseEmailAddress($emailAddress) {
        $result = new \stdClass();
        $result->name = '';
        $parts = explode('<',$emailAddress);
        if (sizeof($parts) == 2) {
            $result->name = trim($parts[0]);
            $parts = explode('>',$parts[1]);
            $result->address = trim($parts[0]);
        }
        else {
            $result->name = '';
            $result->address = trim($emailAddress);
        }

        return $result;
    }

    private function translateResult($resultCode,$failDns,$strict,$emailAddress)
    {
        $this->result = $resultCode;
        if ($this->result < TSayersEmailValidator::ISEMAIL_VALID_CATEGORY) {
            return true;
        }
        if ($this->result < TSayersEmailValidator::ISEMAIL_DNSWARN) {
            $this->dnsFailed = true;
            $message = 'Address is valid but a DNS check was not successful';
            if ($failDns) {
                $this->error = $message;
                return false;
            }
            else {
                $this->warnings[] = $message;
            }
            return true;
        }
        if ($this->result < TSayersEmailValidator::ISEMAIL_RFC5321) {
            $message = TLanguage::text('smtp-odd-address');
            if ($strict) {
                $this->error = $message;
                return false;
            }
            else {
                $this->warnings[] = $message;
            }
            return true;
        }
        if ($this->result < TSayersEmailValidator::ISEMAIL_CFWS) {
            $message = TLanguage::text('smtp-warning-2');
            if ($strict) {
                $this->error = $message;
                return false;
            }
            else {
                $this->warnings[] = $message;
            }
            return true;
        }
        if ($this->result < TSayersEmailValidator::ISEMAIL_DEPREC) {
            $message = TLanguage::text('smtp-warning-3');
            if ($strict) {
                $this->error = $message;
                return false;
            }
            else {
                $this->warnings[] = $message;
            }
            return true;
        }
        if ($this->result < TSayersEmailValidator::ISEMAIL_RFC5322) {
            $message = TLanguage::text('smtp-warning-4');
            if ($strict) {
                $this->error = $message;
                return false;
            }
            else {
                $this->warnings[] = $message;
            }
            return true;
        }

        $errMessage = TLanguage::text('validation-invalid-email');
        $this->error = sprintf($errMessage,$emailAddress);
        return false;
    }

    public function isValid($emailAddress,$dnsCheck=false,$failDns= false, $strict = false)
    {
        $this->result = 0;
        $this->error = '';
        $this->warnings = array();
        $this->dnsFailed = false;
        $parsed = $this->parseEmailAddress($emailAddress);
        $resultCode = TSayersEmailValidator::is_email($parsed->address,$dnsCheck, true);
        $isValid = $this->translateResult($resultCode,$failDns,$strict,$emailAddress);
        if ($isValid) {
            $this->emailAddress = $parsed;
        }
        return $isValid;

    }

    public function checkDns($emailAddress) {
        $isvalid =  $this->isValid($emailAddress,true,true);
        if ($isvalid) {
            return $this->getDnsResult();
        }
        return false;
    }

    public function getDnsResult() {
        return !$this->dnsFailed;
    }

    public function getResultCode() {
        return $this->result;
    }
    public function hasWarnings()
    {
        return (!empty($this->warnings));
    }

    public function getWarnings()
    {
        return $this->warnings;
    }

    public function getError()
    {
        return $this->error;
    }
}