<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class OSToolbarController extends JController
{
	protected $default_view = "tutorials";
	
 	function display($cachable = false, $urlparams = false) 
	{
		parent::display($cachable, $urlparams);
	}
}