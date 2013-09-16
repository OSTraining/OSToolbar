<?php
defined('_JEXEC') or die;

class OSToolbarRequestHelper {
	
	static  $host_url		= 'http://www.ostraining.com/index.php?option=com_api&v=1.6';
	static $isTrial = false;
	
	public function isTrial()
	{
		self::$host_url = "http://www.ostraining.com/index.php?option=com_api&v=1.6_trial";
		self::$isTrial = true;
	}
	
	public function makeRequest($data) {
		$cparams		= JComponentHelper::getParams('com_ostoolbar');
		$static_data	= array(
							'format' 	=> 'json',
							'key'		=> $cparams->get('api_key')
						);
						
		if (!isset($data['app'])) :
			$data['app'] = 'tutorials';
		endif;
						
		$data = array_merge($data, $static_data);
		
		$response	= JRestRequest::send(self::$host_url, $data);
		if ($body = $response->getBody()) :
			$response->setBody(json_decode($body));
		endif;
		
		
		if ($response->hasError()) :
			$body 	= $response->getBody();
			if (isset($body->code)) :
				$response->setErrorCode($body->code);
			endif;
			if (isset($body->message)) :
				$response->setErrorMsg($body->message);
			endif;
		endif;
		
		return $response;
	}
	
	public static function filter($text) {
		$split	 = explode('index.php', self::$host_url);
		$ost_url = $split[0];
		
		$text = preg_replace('#(href|src)="([^:"]*)("|(?:(?:%20|\s|\+)[^"]*"))#','$1="'.$ost_url.'$2$3',$text);
		
		return $text;
	}
	
}