<?php
defined('_JEXEC') or die;

class OSToolbarCacheHelper {
	
	const CACHE_GROUP = 'com_ostoolbar';
	const DAY			= 86400;
	const HALF_DAY		= 43200;
	const HOUR			= 3600;
	const MINUTE		= 60;
	
	function getCache($object, $method, $args, $cache)
	{
		$callback	= array(array($object, $method));
		$cache_args = array_merge($callback, $args);
		$data = call_user_func_array(array($cache, 'call'), $cache_args);

		if ($data === false) :
			// Copy current model for cache ID serialization
			$obj_copy = clone $object;
			
			// Remove errors to return model to original state at cache call
			if ($obj_copy->getError()) :
				$obj_copy->set('_errors', array());
			endif;
			
			$callback	= array($obj_copy, $method);
			// Workaround for getting cache ID and manually removing cached results
			// Need a better way to do this
			$id	= self::makeCacheId($callback, $args);
			$cache->remove($id, self::CACHE_GROUP);
			
			$data = array();
		endif;
		return $data;
	}
	
	public static function callback($object, $method, $args=array(), $cache_lifetime=null, $overrideConfig=false) {
		
		$conf 			= JFactory::getConfig();
		$cacheactive 	= $conf->getValue('config.caching');
		$cachetime		= $conf->getValue('config.cachetime');
		
		$cache= & JFactory::getCache(self::CACHE_GROUP,'callback');

		$key_error = false;
		$response = OSToolbarRequestHelper::makeRequest(array('resource' => 'checkapi'));
		if ($response->hasError() || $response->getBody() == 0)
		{
			//JFactory::getApplication()->enqueueMessage(JText::_('COM_OSTOOLBAR_API_KEY_ERROR'), 'error');
			$key_error = true;
			$cache= & JFactory::getCache(self::CACHE_GROUP."_trial",'callback');
			OSToolbarRequestHelper::isTrial();

		}

		if ($overrideConfig) :
			$cache->setCaching(1); //enable caching
		endif;

		if ($cache_lifetime) :
			$cache->setLifeTime($cache_lifetime);
		endif;
		$data = OSToolbarCacheHelper::getCache($object, $method,$args, $cache);

		if ($data !== false) :
			// In this case we have data in cache but just send minimum request to check update
			$response = OSToolbarRequestHelper::makeRequest(array('resource' => 'lastupdate'));
			if (!$response->hasError())
			{
				$last_update = strtotime($response->getBody());
				if (is_array($data))
				{
					if ((count($data) && strtotime($data[0]->last_update_date) < $last_update) || count($data) == 0)
					{
						$cache->clean();
						$data = OSToolbarCacheHelper::getCache($object, $method, $args, $cache);
					}
				}
			}
		endif;
		if (get_class($object) == "OSToolbarModelTutorial" && !empty($data))
		{
			$data->jversion = $key_error ? "1.6_trial": "1.6";
		}
		
		if ($overrideConfig) :
			$cache->setCaching($cacheactive);
		endif;
		
		if ($cache_lifetime) :
			$cache->setLifeTime($cachetime);
		endif;
		
		return $data;
	}
	
	// Copy of private function in JCacheControllerCallbac
	public function makeCacheId($callback, $args)
	{
		if (is_array($callback) && is_object($callback[0])) {
			$vars = get_object_vars($callback[0]);
			$vars[] = strtolower(get_class($callback[0]));
			$callback[0] = $vars;
		}

		return md5(serialize(array($callback, $args)));
	}
	
}