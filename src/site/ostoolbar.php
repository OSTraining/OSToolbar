<?php
// no direct access
defined('_JEXEC') or die;

$user = JFactory::getUser();

if (!$user->authorise('view.frontend', 'com_ostoolbar')) {
    throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'), 403);
}

require_once JPATH_ADMINISTRATOR . '/components/com_ostoolbar/library/include.php';

$doc = JFactory::getDocument();
$doc->addStyleSheet(JURI::Root() . "components/com_ostoolbar/assets/css/ostoolbar.css");

$controller = JControllerLegacy::getInstance('ostoolbar');
$controller->execute(JFactory::getApplication()->input->getCmd('task', ''));
$controller->redirect();
