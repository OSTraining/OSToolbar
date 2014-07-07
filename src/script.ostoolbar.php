<?php
defined('_JEXEC') or die();

class Com_OSToolbarInstallerScript
{
    public function update()
    {
        Com_OSToolbarInstallerScript::uninstall();
        Com_OSToolbarInstallerScript::install();
    }

    public function install()
    {
        $cache = JFactory::getCache("com_ostoolbar", 'callback');
        $cache->clean();

        $cache = JFactory::getCache("com_ostoolbar_trial", 'callback');
        $cache->clean();

        $db        = JFactory::getDBO();
        $src       = dirname(__FILE__);
        $installer = new JInstaller;
        $result    = $installer->install($src . '/exts/plg_quickicon_ostoolbar');
        if ($result) {
            $db->setQuery(
                "UPDATE #__extensions SET enabled = 1 WHERE  type = 'plugin' AND element = 'ostoolbar' AND folder='quickicon'"
            );
            $db->query();
        }

        $result = $installer->install($src . '/exts/plg_system_ostoolbar');
        if ($result) {
            $db->setQuery(
                "UPDATE #__extensions SET enabled = 1 WHERE  type = 'plugin' AND element = 'ostoolbar' AND folder='system'"
            );
            $db->query();
        }

    }

    public function uninstall()
    {
        $db        = JFactory::getDBO();
        $src       = dirname(__FILE__);
        $installer = new JInstaller;

        $db->setQuery(
            "SELECT extension_id FROM #__extensions WHERE  type = 'plugin' AND element = 'ostoolbar' AND folder='quickicon'"
        );
        $id = $db->loadResult();
        if ($id) {
            $installer->uninstall('plugin', $id, 1);
        }

        $db->setQuery(
            "SELECT extension_id FROM #__extensions WHERE  type = 'plugin' AND element = 'ostoolbar' AND folder='system'"
        );
        $id = $db->loadResult();
        if ($id) {
            $installer->uninstall('plugin', $id, 1);
        }
    }
}

