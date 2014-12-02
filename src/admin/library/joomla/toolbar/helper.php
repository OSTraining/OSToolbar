<?php
/**
 * @package   com_ostoolbar
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2014 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

defined('_JEXEC') or die();

abstract class OstoolbarToolbarHelper extends JToolbarHelper
{
    public static function link($name, $text, $url)
    {
        $bar = JToolbar::getInstance('toolbar');

        $img = JHtml::image(
            "administrator/components/com_ostoolbar/assets/images/icon-32-{$name}.png",
            null,
            null,
            false,
            true
        );
        if ($img) {
            $doc = JFactory::getDocument();
            $doc->addStyleDeclaration(".icon-32-{$name} { background-image: url({$img}); }");
        }

        $bar->appendButton('link', $name, $text, $url);
    }
}
