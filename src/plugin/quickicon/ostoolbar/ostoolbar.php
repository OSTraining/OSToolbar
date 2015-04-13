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
        if (
            $context != $this->params->get('context', 'mod_quickicon')
            || !JFactory::getUser()->authorise('core.manage', 'com_installer')
        ) {
            return array();
        }

        $image = JHtml::_('image', 'com_ostoolbar/quickicon/ost_icon_16.png', null, null, true, true);

        JFactory::getDocument()
            ->addStyleDeclaration(".icon-ostoolbar {background:url('{$image}')}");

        return array(
            array(
                'link'  => 'index.php?option=com_ostoolbar',
                'image' => 'ostoolbar',
                'text'  => JText::_('PLG_QUICKICON_OSTOOLBAR'),
                'id'    => 'plg_quickicon_ostoolbar'
            )
        );
    }
}
