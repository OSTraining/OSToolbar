<?php
/**
 * @package   com_ostoolbar
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2014 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

defined('_JEXEC') or die();

OstoolbarLoader::setup();

abstract class OstoolbarLoader
{
    const PREFIX = 'Ostoolbar';

    public static function setup()
    {
        spl_autoload_register(array(__CLASS__, 'load'), true);
    }

    protected static function load($class)
    {
        if (!class_exists($class) && strpos($class, self::PREFIX) === 0) {
            $parts = preg_split('/(?<=[a-z])(?=[A-Z])/x', substr($class, strlen(self::PREFIX)));

            $file = strtolower(join('/', $parts));

            $libfile = __DIR__ . '/' . $file . '.php';

            if (file_exists($libfile)) {
                require_once $libfile;
            }
        }
    }
}
