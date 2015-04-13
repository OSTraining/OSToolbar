<?php
/**
 * @package   com_ostoolbar
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2014 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

defined('_JEXEC') or die();

class plgSystemOsToolbar extends JPlugin
{
    protected $imageDir = 'administrator/components/com_ostoolbar/assets/images';

    public function onAfterDispatch()
    {
        $app = JFactory::getApplication();

        if (!$app->isAdmin()) {
            return;
        }

        $option = $app->input->getCmd('option', '');
        if ($option == 'com_ostoolbar') {
            return;
        }

        $image = JHtml::_('image', "{$this->imageDir}/icon-32-tutorials.png", null, null, false, true);
        if ($image) {
            JFactory::getDocument()
                ->addStyleDeclaration(".icon-32-tutorial {background:url('{$image}');}");
        }

        $bar = JToolBar::getInstance('toolbar');
        $bar->appendButton(
            'Popup',
            'tutorial',
            JText::_("Tutorials"),
            'index.php?option=com_ostoolbar&amp;view=tutorials&amp;tmpl=component',
            600,
            400,
            0,
            0,
            ''
        );
    }
}
