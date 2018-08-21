<?php

namespace Inteldev\ReCaptcha;

use Bitrix\Main\Page\Asset;

class Main 
{
    public $moduleId = 'inteldev.recaptcha';
    private $siteKey;
    private $secretKey;
    private $tagAttribute = array();

    public function __construct()
    {
        $this->siteKey = \COption::GetOptionString($this->moduleId, 'key');
        $this->secretKey = \COption::GetOptionString($this->moduleId, 'secret');
    }

    public function getSiteKey()
    {
        return $this->siteKey;
    }

    public function validate()
    {
        if (!empty($_REQUEST['g-recaptcha-response'])) {
            $recaptchaResult = $this->getRecaptchaResult($_REQUEST['g-recaptcha-response']);
            return $recaptchaResult->success;
        } else {
            return false;
        }
    }

    public function render($type = 'block', $renderParams = array())
    {
        $this->fillParams($renderParams);

        array_walk($renderParams, array($this, 'setTagAttribute'));

        $params = implode(' ', $this->tagAttribute);

        if ($type == 'block') {
            return '<div ' . $params . ' style="width: 304px; height: 78px;"></div>';
        } elseif ($type == 'param') {
            return $params;
        }

        return false;
    }

    public function assetJs($render = null, $callback = null)
    {
        $paramsJs = array();
        $apiJs = 'https://www.google.com/recaptcha/api.js';

        if ($callback) {
            $paramsJs['onload'] = $callback;
        }

        if ($render) {
            $paramsJs['render'] = $render;
        }

        if (!empty($paramsJs)) {
            $apiJs .= '?' . http_build_query($paramsJs);
        }

        Asset::getInstance()->addJs($apiJs);
    }

    private function setTagAttribute($value, $parameter)
    {
        if ($parameter != 'class') {
            $parameter = 'data-' . $parameter;
        }

        $this->tagAttribute[] = $parameter . '="' . $value . '"';
    }

    private function fillParams(&$renderParams)
    {
        $defaultParams = array(
            'class' => 'g-recaptcha',
            'sitekey' => $this->siteKey,
            'size' => 'invisible',
        );

        $renderParams = array_merge($defaultParams, $renderParams);
    }

    private function getRecaptchaResult($token)
    {
        $recaptchaUrl = 'https://www.google.com/recaptcha/api/siteverify';
        $recaptchaData = array(
            'secret' => $this->secretKey,
            'response' => $token,
            'remoteip' => $_SERVER['REMOTE_ADDR'],
        );
        $recaptchaQuery = http_build_query($recaptchaData);

        $options = array(
            CURLOPT_URL => $recaptchaUrl,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $recaptchaQuery,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded; charset=utf-8',
                'Content-Length: ' . strlen($recaptchaQuery)
            ),
        );

        $ch = curl_init();
        curl_setopt_array($ch, $options);

        $recaptchaResult = json_decode(curl_exec($ch));
        $error = curl_error($ch);
        $info = curl_getinfo($ch);

        curl_close($ch);

        return $recaptchaResult;
    }
}
