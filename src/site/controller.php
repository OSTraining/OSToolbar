<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerLegacy');

class OSToolbarController extends JControllerLegacy
{
	protected $default_view = "tutorials";
	
 	function display($cachable = false, $urlparams = false) 
	{
		parent::display($cachable, $urlparams);
	}
}