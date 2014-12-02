<?php
defined('_JEXEC') or die();

if (!JFactory::getUser()->authorise('core.manage', 'com_ostoolbar')) {
    throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'), 404);
}

// Initiate auto-loader
require_once JPATH_ADMINISTRATOR . '/components/com_ostoolbar/library/joomla/loader.php';

JLoader::register('OSToolbarSystem', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/system.php');
$check = OSToolbarSystem::check();
if ($check['pass'] == false) {
    OSToolbarSystem::displayErrors($check['errors']);
    return;
}

// Include helpers
JLoader::register('OstoolbarHelper', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/helper.php');
JLoader::register('OSToolbarRequestHelper', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/request.php');
JLoader::register('OSToolbarCacheHelper', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/cache.php');
JLoader::register('JRestRequest', JPATH_COMPONENT_ADMINISTRATOR . '/rest/request.php');

// Initialize CSS and Javascript
OstoolbarHelper::adminInit();

// Crank up the controller
$input   = JFactory::getApplication()->input;
$command = $input->getCmd('task', 'display');

jimport('joomla.application.component.controller');

$controller = new OstoolbarController();
$controller->execute($input->getCmd('task', ''));
$controller->redirect();
