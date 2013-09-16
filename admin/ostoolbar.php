<?php 
defined('_JEXEC') or die();

if (!JFactory::getUser()->authorise('core.manage', 'com_ostoolbar')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
} 

JLoader::register('OSToolbarSystem', JPATH_COMPONENT_ADMINISTRATOR.'/helpers/system.php');
$check = OSToolbarSystem::check();
if ($check['pass'] == false)
{
	OSToolbarSystem::displayErrors($check['errors']);
	return;
}

jimport('joomla.application.component.controller');

// Include base files
JLoader::register('OSToolbarController', JPATH_COMPONENT_ADMINISTRATOR.'/base/controller.php');
JLoader::register('OSToolbarModel', JPATH_COMPONENT_ADMINISTRATOR.'/base/model.php');
JLoader::register('OSToolbarView', JPATH_COMPONENT_ADMINISTRATOR.'/base/view.php');

// Include helpers
JLoader::register('OSToolbarHelper', JPATH_COMPONENT_ADMINISTRATOR.'/helpers/helper.php');
JLoader::register('OSToolbarRequestHelper', JPATH_COMPONENT_ADMINISTRATOR.'/helpers/request.php');
JLoader::register('OSToolbarCacheHelper', JPATH_COMPONENT_ADMINISTRATOR.'/helpers/cache.php');
JLoader::register('JRestRequest', JPATH_COMPONENT_ADMINISTRATOR.'/rest/request.php');

$command = JRequest::getCmd('task', 'display');

// Initialize CSS and Javascript
OSToolbarHelper::adminInit();

$class = 'OSToolbarController';

$controller = new $class();
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();
