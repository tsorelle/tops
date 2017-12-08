<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 1/23/2015
 * Time: 2:56 PM
 */

namespace Tops\mail;

use Tops\sys\TObjectContainer;

/**
 * Manages email operations
 * Class TPostOffice
 * @package Tops\sys
 */
class TPostOffice {
    private static $instance;

    /**
     * @return TPostOffice
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new TPostOffice();
        }
        return self::$instance;
    }

    public static function setInstance($instance)
    {
        self::$instance = $instance;
    }


    /**
     * @var IMailer
     */
    private $mailer;
    /**
     * @var IMailboxManager
     */
    private $mailboxes;

    public function __construct(IMailer $mailer = null, IMailboxManager $mailboxes = null) {
        if ($mailer == null) {
            if (TObjectContainer::HasDefinition('tops.mailer')) {
                $mailer = TObjectContainer::Get('tops.mailer');
            }
            else {
                $mailer = new TNullMailer(); // todo: maybe use generic php mailer
            }
        }
        if ($mailboxes == null) {
            if (TObjectContainer::HasDefinition('tops.mailboxes')) {
                $mailer = TObjectContainer::Get('tops.mailboxes');
            }
            else {
                $mailboxes = new TDbMailboxManager();
            }
        }
        $this->mailboxes = $mailboxes;
        $this->mailer = $mailer;
    }

    public static function CreateMessageToUs($addressId='support')
    {
        return self::getInstance()->_createMessageToUs($addressId);
    }

    private $bounceAddress;
    public function getBounceAddress() {
        if (!isset($this->bounceAddress)) {
            $this->bounceAddress = self::GetMailboxAddress(IMailboxManager::BounceBox);
        }
        return $this->bounceAddress;
    }

    private function _createMessageToUs($addressId='support')
    {
        $result = new TEMailMessage();

        $recipients = explode(',',$addressId);
        $count = 0;
        foreach ($recipients as $addressId) {
            $mailbox = self::GetMailboxAddress($addressId);
            if ($mailbox != null) {
                $result->addRecipient($mailbox->getAddress(),$mailbox->getName());
                $count++;
            }
        }
        if ($count == 0) {
            throw new \Exception('No mailboxes found.');
        }

        $result->setReturnAddress($this->getBounceAddress());

        return $result;
    }


    public static function GetMailboxAddress($addressId)
    {
        $mailbox = self::GetMailbox($addressId);
        if ($mailbox === false) {
            return false;
        }
        return new TEmailAddress($mailbox->getEmail(),$mailbox->getName());
    }

    public static function GetMailbox($code) {
        $repository = self::getInstance()->mailboxes;
        $mailbox = $repository->findByCode($code);
        if (empty($mailbox)) {
            return false;
        }
        return $mailbox;

    }

    public static function CreateMessageFromUs($addressId='support',$subject=null,$body=null,$contentType='text') {
        return self::getInstance()->_createMessageFromUs($addressId,$subject,$body,$contentType);
    }

    private function _createMessageFromUs($addressId='support',$subject=null,$body=null,$contentType='text',$bounce=null)
    {
        // TTracer::Trace("CreateMessageFromUs($addressId) address: $address; name: $identity");
        $result = new TEMailMessage();

        $mailbox = self::GetMailboxAddress($addressId);
        if (empty($mailbox)) {
            return false;
        }
        $result->setFromAddress($mailbox);
        if ($bounce===null) {
            $result->setReturnAddress($this->getBounceAddress());
        }
        else {
            $result->setReturnAddress(self::GetMailboxAddress($bounce));
        }
        if (!empty($subject))
            $result->setSubject($subject);
        if (!empty($body)) {
            $result->setMessageBody($body,$contentType);
        }
        return $result;
    }  //  newEmailMessageFromUs


    public static function Send($message) {
        // TTracer::ShowArray($message);
        // TTracer::Trace('Send to: '.htmlentities($message->getRecipients()));
        return self::getInstance()->_send($message);
    }
    private function _send($message) {
        try {
            return $this->mailer->send($message);
        }
        catch(\Exception $ex) {
            return false;
        }
    }


    public static function SendMessage($to, $from, $subject, $bodyText, $contentType='text', $bounce = null)
    {
        //TTracer::Trace('SendMessage');
        return self::getInstance()->_sendMessage($to, $from, $subject, $bodyText, $contentType, $bounce);
    }
    private function _sendMessage($to, $from, $subject, $bodyText, $contentType='text', $bounce = null) {

        $message = new TEMailMessage();
        $message->setRecipient($to);
        $message->setFromAddress($from);
        $message->setSubject($subject);
        $message->setMessageBody($bodyText,$contentType);
        if ($bounce) {
            $message->setReturnAddress($bounce);
        }
        return $this->mailer->send($message);
    }

    public static function SendMessageToUs($fromAddress, $subject, $bodyText, $senderId='admin',$addressId='admin')
    {
        return self::getInstance()->_sendMessageToUs($fromAddress, $subject, $bodyText,$senderId,$addressId);
    }

    private function _sendMessageToUs($fromAddress, $subject, $bodyText, $senderId='admin',$addressId='admin')
    {
        $message = $this->_createMessageToUs($addressId);
        $senderAddress = self::GetMailboxAddress($senderId);
        $message->setFromAddress($senderAddress);
        $message->setReplyTo($fromAddress);
        $message->setSubject($subject);
        $message->setMessageBody($bodyText);
        return $this->mailer->send($message);
    }

    public static function SendMessageFromUs($recipients, $subject, $bodyText, $addressId='admin', $contentType='html' ) {
        return self::getInstance()->_sendMessageFromUs($recipients, $subject, $bodyText, $addressId, $contentType);
    }

    private function _sendMessageFromUs($recipient, $subject, $bodyText, $addressId='admin', $contentType='html',$bounce=null  ) {
        // TTracer::Trace('SendMessageFromUs');
        $message = $this->_createMessageFromUs($addressId, $subject, $bodyText, $contentType,$bounce);
        $message->setRecipient($recipient);
        return $this->mailer->send($message);
    }

    public static function SendHtmlMessageFromUs($recipients, $subject, $bodyText, $addressId='support',$bounce=null ) {
        return self::getInstance()->_sendHtmlMessageFromUs($recipients, $subject, $bodyText, $addressId,$bounce);
    }
    private function _sendHtmlMessageFromUs($recipients, $subject, $bodyText, $addressId='support',$bounce=null ) {
        $message = $this->_createMessageFromUs($addressId, $subject, $bodyText, 'html',$bounce);
        $message->setRecipient($recipients);
        return $this->mailer->send($message);
    }

    public static function SendMultiPartMessageFromUs($recipients, $subject, $bodyText, $textPart, $addressId='support', $bounce=null ) {
        return self::getInstance()->_sendMultiPartMessageFromUs($recipients, $subject, $bodyText, $textPart, $addressId,$bounce);
    }
    private function _sendMultiPartMessageFromUs($recipients, $subject, $bodyText, $textPart, $addressId='support',$bounce=null ) {
        $message = $this->_createMessageFromUs($addressId, $subject, $bodyText, 'html',$bounce);
        $message->setAlternateBodyText($textPart);
        $message->setRecipient($recipients);
        return $this->mailer->send($message);
    }

    public static function disableSend() {
        self::getInstance()->mailer->setSendEnabled(false);
    }

    public static function GetMailboxManager() {
        return self::getInstance()->mailboxes;
    }

}