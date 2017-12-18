<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 1/23/2015
 * Time: 2:56 PM
 */

namespace Tops\mail;

use Tops\sys\TObjectContainer;
// use Tops\mail\TContentType;

/**
 * Manages email operations
 * Class TPostOffice
 * @package Tops\sys
 */
class TPostOffice {
    // system mailbox names
    const AdminMailbox = 'admin';
    const BounceMailbox = 'bounce';
    const VendorMailbox = 'two-quakers-support';
    const ContactMailbox = 'contact-form';
    const SupportMailbox = 'support';
    const DefaultMailbox = 'admin';

    const SystemMailboxes = [
        self::AdminMailbox ,
        self::BounceMailbox,
        self::VendorMailbox,
        self::ContactMailbox,
        self::SupportMailbox
    ];


    public static $instance = null;
    public static $mailEnabled = true;

    /**
     * @return TPostOffice
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new TPostOffice();
        }
        return self::$instance;
    }

    public static function setInstance($instance)
    {
        self::$instance = $instance;
    }


    private $mailerClass;
    /**
     * @var IMailboxManager
     */
    private $mailboxes;

    /**
     * @return IMailer
     */
    private function getMailer()
    {
        if (isset($this->mailerClass)) {
            return new $this->mailerClass();
        }
        if (TObjectContainer::HasDefinition('tops.mailer')) {
            $mailer = TObjectContainer::Get('tops.mailer');
        }
        else {
            $mailer = new TNullMailer();
        }
        $this->mailerClass = get_class($mailer);
        $mailer->setSendEnabled(self::$mailEnabled);
        return $mailer;
    }

    public function __construct(IMailer $mailer = null, IMailboxManager $mailboxes = null) {
        if ($mailboxes == null) {
            if (TObjectContainer::HasDefinition('tops.mailboxes')) {
                $mailboxes = TObjectContainer::Get('tops.mailboxes');
            }
            else {
                $mailboxes = new TDbMailboxManager();
            }
        }
        $this->mailboxes = $mailboxes;
    }

    public static function CreateMessageToUs($addressId=self::SupportMailbox)
    {
        return self::getInstance()->_createMessageToUs($addressId);
    }

    private $bounceAddress;
    public function getBounceAddress() {
        if (!isset($this->bounceAddress)) {
            $this->bounceAddress = self::GetMailboxAddress(self::BounceMailbox);
        }
        return $this->bounceAddress;
    }

    private function _createMessageToUs($addressId=self::SupportMailbox)
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

    public static function CreateMessageFromUs($addressId=self::SupportMailbox,$subject=null,$body=null,$contentType=TContentType::Text) {
        return self::getInstance()->_createMessageFromUs($addressId,$subject,$body,$contentType);
    }

    private function _createMessageFromUs($addressId=self::SupportMailbox,$subject=null,$body=null,$contentType=TContentType::Text,$bounce=null)
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
            return $this->getMailer()->send($message);
        }
        catch(\Exception $ex) {
            return false;
        }
    }


    public static function SendMessage($to, $senderId, $senderAlias, $subject, $bodyText, $contentType=TContentType::Text, $replyTo=null,  $bounce = null)
    {
        //TTracer::Trace('SendMessage');
        return self::getInstance()->_sendMessage($to, $senderId, $senderAlias, $subject, $bodyText, $contentType, $replyTo, $bounce);
    }
    private function _sendMessage($to, $senderId, $senderAlias, $subject, $bodyText, $contentType=TContentType::Text, $replyTo=null, $bounce = null) {
        $message = new TEMailMessage();
        $message->setRecipient($to);
        if (empty($message->senderId)) {
            $senderId = self::DefaultMailbox;
        }
        $senderAddress = self::GetMailboxAddress($senderId);
        if (!empty($senderAlias)) {
            $senderAddress->setName($senderAlias);
        }
        $message->setFromAddress($senderAddress);
        $message->setSubject($subject);
        $message->setMessageBody($bodyText,$contentType);
        if ($replyTo) {
            $message->setReplyTo($replyTo);
        }
        if ($bounce) {
            $message->setReturnAddress($bounce);
        }
        return $this->getMailer()->send($message);
    }

    public static function SendMessageToUs($fromAddress, $subject, $bodyText, $senderId=self::DefaultMailbox,$addressId=self::DefaultMailbox)
    {
        return self::getInstance()->_sendMessageToUs($fromAddress, $subject, $bodyText,$senderId,$addressId);
    }

    private function _sendMessageToUs($fromAddress, $subject, $bodyText, $senderId=self::DefaultMailbox,$addressId=self::DefaultMailbox)
    {
        $message = $this->_createMessageToUs($addressId);
        $senderAddress = self::GetMailboxAddress($senderId);
        $message->setFromAddress($senderAddress);
        $message->setReplyTo($fromAddress);
        $message->setSubject($subject);
        $message->setMessageBody($bodyText);
        return $this->getMailer()->send($message);
    }

    public static function SendMessageFromUs($recipients, $subject, $bodyText, $addressId=self::DefaultMailbox, $contentType=TContentType::Html ) {
        return self::getInstance()->_sendMessageFromUs($recipients, $subject, $bodyText, $addressId, $contentType);
    }

    private function _sendMessageFromUs($recipient, $subject, $bodyText, $addressId=self::DefaultMailbox, $contentType=TContentType::Html,$bounce=null  ) {
        // TTracer::Trace('SendMessageFromUs');
        $message = $this->_createMessageFromUs($addressId, $subject, $bodyText, $contentType,$bounce);
        $message->setRecipient($recipient);
        return $this->getMailer()->send($message);
    }

    public static function SendHtmlMessageFromUs($recipients, $subject, $bodyText, $addressId=self::SupportMailbox,$bounce=null ) {
        return self::getInstance()->_sendHtmlMessageFromUs($recipients, $subject, $bodyText, $addressId,$bounce);
    }
    private function _sendHtmlMessageFromUs($recipients, $subject, $bodyText, $addressId=self::SupportMailbox,$bounce=null ) {
        $message = $this->_createMessageFromUs($addressId, $subject, $bodyText,TContentType::Html,$bounce);
        $message->setRecipient($recipients);
        return $this->getMailer()->send($message);
    }

    public static function SendMultiPartMessageFromUs($recipients, $subject, $bodyText, $textPart, $addressId=self::SupportMailbox, $bounce=null ) {
        return self::getInstance()->_sendMultiPartMessageFromUs($recipients, $subject, $bodyText, $textPart, $addressId,$bounce);
    }
    private function _sendMultiPartMessageFromUs($recipients, $subject, $bodyText, $textPart, $addressId=self::SupportMailbox,$bounce=null ) {
        $message = $this->_createMessageFromUs($addressId, $subject, $bodyText,TContentType::Html,$bounce);
        $message->setAlternateBodyText($textPart);
        $message->setRecipient($recipients);
        return $this->getMailer()->send($message);
    }

    public static function disableSend() {
        self::$mailEnabled = false;
    }

    public static function GetMailboxManager() {
        return self::getInstance()->mailboxes;
    }

}