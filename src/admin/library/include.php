<?php
/**
 * @package   com_ostoolbar
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2014 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

defined('_JEXEC') or die();

if (!defined('OSTOOLBAR_LOADED')) {
    define('OSTOOLBAR_LOADED', 1);
    define('OSTOOLBAR_ADMIN', JPATH_ADMINISTRATOR . '/components/com_ostoolbar');
    define('OSTOOLBAR_SITE', JPATH_SITE . '/components/com_ostoolbar');

    // Initiate auto-loader
    require_once OSTOOLBAR_ADMIN . '/library/joomla/loader.php';

    $app = JFactory::getApplication();
    if ($app->input->getCmd('option', '') != 'com_ostoolbar') {
        switch ($app->getName()) {
            case 'administrator':
                JModelLegacy::addIncludePath(OSTOOLBAR_ADMIN . '/models');
                break;

            case 'site':
                JModelLegacy::addIncludePath(OSTOOLBAR_SITE . '/models');
                JHtml::_('stylesheet', 'com_ostoolbar/ostoolbar.css', null, true);
                break;
        }
    }
}
