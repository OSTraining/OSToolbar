<?php
/**
 * @package   Simplerenew
 * @contact   www.simplerenew.com, support@simplerenew.com
 * @copyright 2014-2015 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

defined('_JEXEC') or die();

abstract class JHtmlOst
{
    protected static $jqueryLoaded    = false;

    /**
     * Load jQuery core
     *
     * @param bool $noConflict
     * @param bool $debug
     */
    public static function jquery($noConflict = true, $debug = null)
    {
        // Only load once
        if (!static::$jqueryLoaded) {
            if (version_compare(JVERSION, '3.0', 'lt')) {
                // pre 3.0 manual loading

                // If no debugging value is set, use the configuration setting
                if ($debug === null) {
                    $config = JFactory::getConfig();
                    $debug  = (boolean)$config->get('debug');
                }

                JHtml::_('script', 'com_ostoolbar/jquery.js', false, true, false, false, $debug);

                // Check if we are loading in noConflict
                if ($noConflict) {
                    JHtml::_('script', 'com_ostoolbar/noconflict.js', false, true);
                }

            } else {
                JHtml::_('jquery.framework', $noConflict, $debug);
            }
            static::$jqueryLoaded = true;
        }
    }
}
