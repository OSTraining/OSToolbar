<?php
/**
 * @package   com_ostoolbar
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2014 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

defined('_JEXEC') or die();

class plgQuickiconOSToolbar extends JPlugin
{
    public function __construct(&$subject, $config)
    {
        parent::__construct($subject, $config);
        $this->loadLanguage();
    }

    public function onGetIcons($context)
    {
        if ($context != $this->params->get('context', 'mod_quickicon')
            || !JFactory::getUser()->authorise('core.manage', 'com_ostoolbar')
        ) {
            return array();
        }

        return array(
            array(
                'link'  => 'index.php?option=com_ostoolbar',
                'image' => 'com_ostoolbar/icon-48-ostoolbar.png',
                'text'  => JText::_('PLG_QUICKICON_OSTOOLBAR'),
                'id'    => 'plg_quickicon_ostoolbar'
            )
        );
    }
}
