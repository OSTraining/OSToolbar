<?php

abstract class OstoolbarSystem
{
    protected static $phpMinimumVersion = '5.3';

    /**
     * Check system requirements
     *
     * @return array
     */
    public static function check()
    {
        $pass   = true;
        $errors = array('curl' => false, 'php' => false);

        if (!function_exists('curl_init')) {
            $pass           = false;
            $errors['curl'] = true;
        }

        if (version_compare(phpversion(), static::$phpMinimumVersion, 'lt')) {
            $pass          = false;
            $errors['php'] = true;
        }

        return array('pass' => $pass, 'errors' => $errors);
    }

    public static function displayErrors($errors)
    {
        $toolbar = array(
            '<div class="header ost-logo">',
            '<span class="header_text">' . JText::_('COM_OSTOOLBAR_ERROR') . '</span>',
            '</div>'
        );
        JFactory::getApplication()->set('JComponentTitle', join("\n", $toolbar));

        $html = array(
            '<h1>' . JText::_('COM_OSTOOLBAR_ERROR_PAGE_HEADING') . '</h1>',
            '<p class="errormessage">' . JText::_('COM_OSTOOLBAR_ERROR_PAGE_DESC') . '</p>'
        );

        if ($errors['curl']) {
            $html = array_merge(
                $html,
                array(
                    '<div class="error_msg">',
                    '<h3 class="error_title">' . JText::_('COM_OSTOOLBAR_CURL_ERROR') . '</h3>',
                    '<p class="error_desc">' . JText::_('COM_OSTOOLBAR_CURL_DESC') . '</p>',
                    '</div>'
                )
            );
        }

        if ($errors['php']) {
            $html = array_merge(
                $html,
                array(
                    '<div class="error_msg">'
                    . '<h3 class="error_title">' . JText::_('COM_OSTOOLBAR_PHP_ERROR') . '</h3>'
                    . '<p class="error_desc">'
                    . JText::sprintf('COM_OSTOOLBAR_PHP_DESC', static::$phpMinimumVersion)
                    . '</p>'
                    . '</div>'
                )
            );
        }

        echo join("\n", $html);
    }

}
