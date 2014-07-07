<?php
defined('_JEXEC') or die;

abstract class OSToolbarRequestHelper
{
    public static $host_url = 'https://www.ostraining.com/index.php?option=com_api&v=3.0';
    public static $isTrial = false;

    public static function isTrial()
    {
        self::$host_url = "https://www.ostraining.com/index.php?option=com_api&v=3.0_trial";
        self::$isTrial  = true;
    }

    public static function makeRequest($data)
    {
        $cparams     = JComponentHelper::getParams('com_ostoolbar');
        $static_data = array(
            'format' => 'json',
            'key'    => $cparams->get('api_key')
        );

        if (!isset($data['app'])) {
            $data['app'] = 'tutorials';
        }

        $data = array_merge($data, $static_data);

        $response = JRestRequest::send(
            self::$host_url,
            $data,
            'POST',
            array(
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_MAXREDIRS      => 1,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false
            )
        );
        if ($body = $response->getBody()) {
            $response->setBody(json_decode($body));
        }

        if ($response->hasError()) {
            $body = $response->getBody();
            if (isset($body->code)) {
                $response->setErrorCode($body->code);
            }
            if (isset($body->message)) {
                $response->setErrorMsg($body->message);
            }
        }

        return $response;
    }

    public static function filter($text)
    {
        $split   = explode('index.php', self::$host_url);
        $ost_url = $split[0];

        $text = preg_replace('#(href|src)="([^:"]*)("|(?:(?:%20|\s|\+)[^"]*"))#', '$1="' . $ost_url . '$2$3', $text);

        return $text;
    }
}
