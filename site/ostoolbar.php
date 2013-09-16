<?php
// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

$user = JFactory::getUser();


if (!$user->authorise('view.frontend', 'com_ostoolbar'))
{
	return JError::raiseWarning(403, JText::_('JERROR_ALERTNOAUTHOR'));
} 

JLoader::register('OSToolbarRequestHelper', JPATH_COMPONENT_ADMINISTRATOR.'/helpers/request.php');
JLoader::register('OSToolbarCacheHelper', JPATH_COMPONENT_ADMINISTRATOR.'/helpers/cache.php');
JLoader::register('JRestRequest', JPATH_COMPONENT_ADMINISTRATOR.'/rest/request.php');

$doc = JFactory::getDocument();
$doc->addStyleSheet(JURI::Root(). "components/com_ostoolbar/assets/css/ostoolbar.css");


jimport('joomla.application.component.controller');
$controller = JControllerLegacy::getInstance('ostoolbar');
$controller->execute( JRequest::getCmd( 'task') );
$controller->redirect();
?>