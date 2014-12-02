<?php
/**
 * @package   com_ostoolbar
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2014 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

defined('_JEXEC') or die();

class OstoolbarHelper
{
    public static function adminInit()
    {
        if (JRequest::getVar('format') == 'raw') :
            return false;
        endif;
        $document = JFactory::getDocument();
        $document->addStyleSheet(JURI::root() . 'administrator/components/com_ostoolbar/assets/css/ostoolbar.css');
        $document->addScript(JURI::root() . 'administrator/components/com_ostoolbar/assets/js/jquery-1.4.2.min.js');
        $document->addScript(
            JURI::root() . 'administrator/components/com_ostoolbar/assets/js/jquery-ui-1.8.6.custom.min.js'
        );
        $document->addStyleSheet(
            JURI::root() . 'administrator/components/com_ostoolbar/assets/css/ui-lightness/jquery-ui-1.8.6.custom.css'
        );
    }

    public static function splitList($string, $delimiter = ",")
    {
        $list = preg_split("/\s*" . $delimiter . "\s*/", $string);

        return $list;
    }

    public static function parseParams($string)
    {
        $params = array();

        $lines = preg_split("/\s*\\n\s*/", $string);
        for ($i = 0; $i < count($lines); $i++) :
            $line = $lines[$i];
            $split = preg_split("/\s*=\s*/", $line);
            $key = isset($split[0]) ? $split[0] : null;
            $value = isset($split[1]) ? $split[1] : null;
            if ($key !== null && $value !== null) :
                $params[$key] = $value;
            endif;
        endfor;

        return $params;
    }

    public static function renderErrors($errors)
    {
        $app = JFactory::getApplication();
        for ($i = 0; $i < count($errors); $i++) :
            $app->enqueueMessage($errors[$i], 'error');
        endfor;
    }

    public static function setPageTitle($text, $class = 'ost-logo')
    {
        $html = "<div class='header " . $class . "'>\n";
        $html .= "<span class='header_text'>" . $text . "</span>\n";
        $html .= "</div>\n";
        JToolbarHelper::title($html);
    }

    public static function customButton($text, $class, $id, $link)
    {
        $bar = JToolbar::getInstance('toolbar');

        $html = "<a href=\"$link\" class=\"toolbar\">\n";
        $html .= "<span class=\"$class\" title=\"$text\">\n";
        $html .= "</span>\n";
        $html .= "$text\n";
        $html .= "</a>\n";

        $bar->appendButton('Custom', $html, $id);
    }

}
