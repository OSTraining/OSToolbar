<?php
defined('_JEXEC') or die();

if (!JFactory::getUser()->authorise('core.manage', 'com_ostoolbar')) {
    throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'), 404);
}

require_once JPATH_ADMINISTRATOR . '/components/com_ostoolbar/library/include.php';
OstoolbarHelper::adminInit();

$check = OstoolbarSystem::check();
if ($check['pass'] == false) {
    OstoolbarSystem::displayErrors($check['errors']);
    return;
}

// Crank up the controller
$input   = JFactory::getApplication()->input;
$command = $input->getCmd('task', 'display');

jimport('joomla.application.component.controller');

$controller = JControllerLegacy::getInstance('ostoolbar');
$controller->execute($input->getCmd('task', ''));
$controller->redirect();
