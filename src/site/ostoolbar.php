<?php
// no direct access
defined('_JEXEC') or die;

$user = JFactory::getUser();

if (!$user->authorise('view.frontend', 'com_ostoolbar')) {
    throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'), 403);
}

require_once JPATH_ADMINISTRATOR . '/components/com_ostoolbar/library/include.php';

JHtml::_('stylesheet', 'com_ostoolbar/ostoolbar.css', null, true);

$controller = JControllerLegacy::getInstance('ostoolbar');
$controller->execute(JFactory::getApplication()->input->getCmd('task', ''));
$controller->redirect();
