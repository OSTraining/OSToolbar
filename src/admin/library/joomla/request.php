<?php
/**
 * @package   com_ostoolbar
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2014 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

defined('_JEXEC') or die();

abstract class OstoolbarRequest
{
    protected static $hostUrl = 'https://www.ostraining.com/';
    public static    $isTrial = false;

    public static function getHostUrl()
    {
        $version = version_compare(JVERSION, '3', 'ge') ? '3.0' : '1.6';
        $trial   = static::$isTrial ? '_trial' : '';

        $vars = array(
            'option' => 'com_api',
            'v'      => $version . $trial
        );

        return self::$hostUrl . 'index.php?' . http_build_query($vars);
    }

    public static function makeRequest($data)
    {
        $cparams = JComponentHelper::getParams('com_ostoolbar');

        $staticData = array(
            'format' => 'json',
            'key'    => $cparams->get('api_key')
        );

        if (!isset($data['app'])) {
            $data['app'] = 'tutorials';
        }
        $data = array_merge($data, $staticData);

        $response = OstoolbarRestRequest::send(
            self::getHostUrl(),
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
        $split  = explode('index.php', static::getHostUrl());
        $ostUrl = $split[0];

        $text = preg_replace('#(href|src)="([^:"]*)("|(?:(?:%20|\s|\+)[^"]*"))#', '$1="' . $ostUrl . '$2$3', $text);

        return $text;
    }
}
