<?php
/**
 * @package   com_ostoolbar
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2014 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

defined('_JEXEC') or die();

class com_ostoolbarInstallerScript
{
    public function update()
    {
        $this->uninstall();
        $this->install();
    }

    public function install()
    {
        $cache = JFactory::getCache('com_ostoolbar', 'callback');
        $cache->clean();

        $cache = JFactory::getCache('com_ostoolbar_trial', 'callback');
        $cache->clean();

        $db        = JFactory::getDBO();
        $src       = dirname(__FILE__);
        $installer = new JInstaller;

        // Install the quickicon plugin
        $result = $installer->install($src . '/exts/plg_quickicon_ostoolbar');
        if ($result) {
            $query = $db->getQuery(true)
                ->update('#__extensions')
                ->set('enabled = 1')
                ->where(
                    array(
                        'type = ' . $db->quote('plugin'),
                        'element = ' . $db->quote('ostoolbar'),
                        'folder=' . $db->quote('quickicon')
                    )
                );
            $db->setQuery($query)->execute();
        }

        // Install the system plugin
        $result = $installer->install($src . '/exts/plg_system_ostoolbar');
        if ($result) {
            $query = $db->getQuery(true)
                ->update('#__extensions')
                ->set('enabled = 1')
                ->where(
                    array(
                        'type = ' . $db->quote('plugin'),
                        'element = ' . $db->quote('ostoolbar'),
                        'folder = ' . $db->quote('system')
                    )
                );
            $db->setQuery($query)->execute();
        }

    }

    public function uninstall()
    {
        $db        = JFactory::getDBO();
        $installer = new JInstaller;

        $query = $db->getQuery(true)
            ->select('extension_id')
            ->from('#__extensions')
            ->where(
                array(
                    'type = ' . $db->quote('plugin'),
                    'element = ' . $db->quote('ostoolbar'),
                    'folder=' . $db->quote('quickicon')
                )
            );

        $id = $db->setQuery($query)->loadResult();
        if ($id) {
            $installer->uninstall('plugin', $id, 1);
        }

        $query = $db->getQuery(true)
            ->select('extension_id')
            ->from('#__extensions')
            ->where(
                array(
                    'type = ' . $db->quote('plugin'),
                    'element = ' . $db->quote('ostoolbar'),
                    'folder=' . $db->quote('system')
                )
            );

        $id = $db->setQuery($query)->loadResult();
        if ($id) {
            $installer->uninstall('plugin', $id, 1);
        }
    }
}
