<?php
/**
 * Created by PhpStorm.
 * User: Terry
 * Date: 8/21/2017
 * Time: 7:38 AM
 */

namespace Tops\sys;


class TTemplateManager
{
    private $templateLocation;
    private $tokenFormat = '[[%s]]';
    public function setTokenFormat($format) {
        $this->tokenPattern = $format;
    }
    public function __construct($templateLocation='')
    {
        $this->templateLocation = $templateLocation;
    }

    public function replaceTokens($content, array $tokens) {
        foreach ($tokens as $name=>$value) {
            $token = sprintf($this->tokenFormat,$name);
            $content = str_replace($token,$value,$content);
        }
        return $content;
    }

    public function getContent($templateName, $templateLocation='') {
        if (empty($templateLocation)) {
            if (empty($this->templateLocation)) {
                throw new \Exception('No template location found.');
            }
            $templateLocation = $this->templateLocation;
        }
        $templateFile = TPath::combine($templateLocation,$templateName,TPath::normalize_no_exception);
        if ($templateFile === false) {
            return false;
        }
        return file_get_contents($templateFile);
    }

    public function buildContent($templateName, array $tokens, $templateLocation='') {
        $content = $this->getContent($templateName, $templateLocation);
        return $this->replaceTokens($content,$tokens);
    }

}