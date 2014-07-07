<?php

abstract class OSToolbarSystem
{
    public static function check()
    {
        $pass   = true;
        $errors = array('curl' => false, 'php' => false);

        if (!function_exists('curl_init')) {
            $pass           = false;
            $errors['curl'] = true;
        }

        $version       = explode(".", phpversion());
        $major_version = $version[0];

        if ($major_version < 5) {
            $pass          = false;
            $errors['php'] = true;
        }

        return array('pass' => $pass, 'errors' => $errors);
    }

    public static function displayErrors($errors)
    {
        $document = JFactory::getDocument();
        $document->addStyleSheet(JURI::root() . 'administrator/components/com_ostoolbar/assets/css/ostoolbar.css');

        $toolbar = "<div class='header ost-logo'>\n";
        $toolbar .= "<span class='header_text'>" . JText::_('COM_OSTOOLBAR_ERROR') . "</span>\n";
        $toolbar .= "</div>\n";

        $app = JFactory::getApplication()->set('JComponentTitle', $toolbar);

        $html = "<h1>" . JText::_('COM_OSTOOLBAR_ERROR_PAGE_HEADING') . "</h1>";
        $html .= "<p class='errormessage'>" . JText::_('COM_OSTOOLBAR_ERROR_PAGE_DESC') . "</p>";

        if ($errors['curl']) {
            $html .= "<div class='error_msg'>";
            $html .= "<h3 class='error_title'>" . JText::_("COM_OSTOOLBAR_CURL_ERROR") . "</h3>";
            $html .= "<p class='error_desc'>" . JText::_("COM_OSTOOLBAR_CURL_DESC") . "</p>";
            $html .= "</div>";
        }

        if ($errors['php']) {
            $html .= "<div class='error_msg'>";
            $html .= "<h3 class='error_title'>" . JText::_("COM_OSTOOLBAR_PHP_ERROR") . "</h3>";
            $html .= "<p class='error_desc'>" . JText::_("COM_OSTOOLBAR_PHP_DESC") . "</p>";
            $html .= "</div>";
        }

        echo $html;
    }

}
