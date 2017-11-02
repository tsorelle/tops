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

// from




class TEmailValidator
{
    private $warnings=array();
    private $error;
    private $result = 0;
    private $emailAddress = false;

    private static $instance;

    public static function Create()
    {
        return new TEmailValidator();
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

    private function translateResult($resultCode,$failDns,$strict)
    {
        $this->result = $resultCode;
        if ($this->result < TSayersEmailValidator::ISEMAIL_VALID_CATEGORY) {
            return true;
        }
        if ($this->result < TSayersEmailValidator::ISEMAIL_DNSWARN) {
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
            $message = TLanguage::text('Address is valid for SMTP but has unusual elements');
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
            $message = TLanguage::text('Address is valid within the message but cannot be used unmodified for the envelope');
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
            $message = TLanguage::text('Address contains deprecated elements but may still be valid in restricted contexts');
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
            $message = TLanguage::text('The address is only valid according to the broad definition of RFC 5322. It is otherwise invalid.');
            if ($strict) {
                $this->error = $message;
                return false;
            }
            else {
                $this->warnings[] = $message;
            }
            return true;
		}
		
        $this->error = TLanguage::text('Invalid email');
        return false;
    }

    public function isValid($emailAddress,$dnsCheck=false,$failDns= false, $strict = false)
    {
        $this->result = 0;
        $this->error = '';
        $this->warnings = array();
        $parsed = $this->parseEmailAddress($emailAddress);
        $resultCode = TSayersEmailValidator::is_email($parsed->address,$dnsCheck, true);
        $isValid = $this->translateResult($resultCode,$failDns,$strict);
        if ($isValid) {
            $this->emailAddress = $parsed;
        }
        return $isValid;

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