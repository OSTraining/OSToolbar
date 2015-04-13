<?php

abstract class OstoolbarSystem
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
        JHtml::_('stylesheet', 'com_ostoolbar/admin/ostoolbar.css', null, true);

        $toolbar = "<div class='header ost-logo'>\n"
            . "<span class='header_text'>" . JText::_('COM_OSTOOLBAR_ERROR') . "</span>\n"
            . "</div>\n";

        $app = JFactory::getApplication()->set('JComponentTitle', $toolbar);

        $html = "<h1>" . JText::_('COM_OSTOOLBAR_ERROR_PAGE_HEADING') . "</h1>"
            . "<p class='errormessage'>" . JText::_('COM_OSTOOLBAR_ERROR_PAGE_DESC') . "</p>";

        if ($errors['curl']) {
            $html .= "<div class='error_msg'>"
                . "<h3 class='error_title'>" . JText::_("COM_OSTOOLBAR_CURL_ERROR") . "</h3>"
                . "<p class='error_desc'>" . JText::_("COM_OSTOOLBAR_CURL_DESC") . "</p>"
                . "</div>";
        }

        if ($errors['php']) {
            $html .= "<div class='error_msg'>"
                . "<h3 class='error_title'>" . JText::_("COM_OSTOOLBAR_PHP_ERROR") . "</h3>"
                . "<p class='error_desc'>" . JText::_("COM_OSTOOLBAR_PHP_DESC") . "</p>"
                . "</div>";
        }

        echo $html;
    }

}
