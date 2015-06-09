<?php
/**
 * @package   com_ostoolbar
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2014 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

defined('_JEXEC') or die();

abstract class OstoolbarCache
{
    const CACHE_GROUP = 'com_ostoolbar';
    const DAY         = 86400;
    const HALF_DAY    = 43200;
    const HOUR        = 3600;
    const MINUTE      = 60;

    public static function getCache($object, $method, $args, $cache)
    {
        $callback   = array(array($object, $method));
        $cache_args = array_merge($callback, $args);
        $data       = call_user_func_array(array($cache, 'call'), $cache_args);

        if ($data === false) {
            // Copy current model for cache ID serialization
            $objCopy = clone $object;

            // Remove errors to return model to original state at cache call
            if ($objCopy->getError()) {
                $objCopy->set('_errors', array());
            }

            $callback = array($objCopy, $method);
            // Workaround for getting cache ID and manually removing cached results
            // Need a better way to do this
            $id = static::makeCacheId($callback, $args);
            $cache->remove($id, static::CACHE_GROUP);

            $data = array();
        }
        return $data;
    }

    public static function callback(
        $object,
        $method,
        $args = array(),
        $cache_lifetime = null,
        $overrideConfig = false
    ) {
        $conf        = JFactory::getConfig();
        $cacheactive = $conf->get('config.caching');
        $cachetime   = $conf->get('config.cachetime');

        $cache = JFactory::getCache(static::CACHE_GROUP, 'callback');
        if ($overrideConfig) {
            $cache->setCaching(1); //enable caching
        }

        if ($cache_lifetime) {
            $cache->setLifeTime($cache_lifetime);
        }
        $data = static::getCache($object, $method, $args, $cache);

        if ($data !== false) {
            // In this case we have data in cache but just send minimum request to check update
            $response = OstoolbarRequest::makeRequest(array('resource' => 'lastupdate'));
            if (!$response->hasError()) {
                $last_update = strtotime($response->getBody());
                if (is_array($data)) {
                    if ((count($data) && strtotime($data[0]->last_update_date) < $last_update) || count($data) == 0) {
                        $cache->clean();
                        $data = static::getCache($object, $method, $args, $cache);
                    }
                }
            }
        }
        if (get_class($object) == "OSToolbarModelTutorial" && !empty($data)) {
            $data->jversion =  OstoolbarRequest::isTrial() ? '1.6_trial' : '1.6';
        }

        if ($overrideConfig) {
            $cache->setCaching($cacheactive);
        }

        if ($cache_lifetime) {
            $cache->setLifeTime($cachetime);
        }

        return $data;
    }

    // Copy of private function in JCacheControllerCallback
    public function makeCacheId($callback, $args)
    {
        if (is_array($callback) && is_object($callback[0])) {
            $vars        = get_object_vars($callback[0]);
            $vars[]      = strtolower(get_class($callback[0]));
            $callback[0] = $vars;
        }

        return md5(serialize(array($callback, $args)));
    }
}
