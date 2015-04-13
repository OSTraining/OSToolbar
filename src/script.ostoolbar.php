<?php
/**
 * @package   com_ostoolbar
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2014 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

use Alledia\Installer\AbstractScript;

defined('_JEXEC') or die();

$includePath = __DIR__ . '/admin/library';
if (!is_dir($includePath)) {
    $includePath = __DIR__ . '/library';
}

if (file_exists($includePath . '/Installer/include.php')) {
    require_once $includePath . '/Installer/include.php';
} else {
    throw new Exception('[OSToolbar] Alledia Installer not found');
}

class com_ostoolbarInstallerScript extends AbstractScript
{
    /**
     * @var array Related extensions required or useful with the component
     *            type => [ (folder) => [ (element) => [ (publish), (uninstall), (ordering) ] ] ]
     */
    protected $relatedExtensions = array(
        'plugin' => array(
            'quickicon' => array(
                'ostoolbar' => array(1, 1, null)
            ),
            'system'   => array(
                'ostoolbar' => array(1, 1, null)
            )
        )
    );

    /**
     * Install related extensions
     * Overriding the Alledia Install because we're specifying some additional things
     * @TODO: Remove when AllediaInstaller is updated with this enhancement
     *
     * @return void
     */
    protected function installRelated()
    {
        parent::installRelated();

        if ($this->relatedExtensions) {
            $source = $this->installer->getPath('source');

            foreach ($this->relatedExtensions as $type => $folders) {
                foreach ($folders as $folder => $extensions) {
                    foreach ($extensions as $element => $settings) {
                        $path = $source . '/' . $type;
                        if ($type == 'plugin') {
                            $path .= '/' . $folder;
                        }
                        $path .= '/' . $element;
                        if (is_dir($path)) {
                            $current = $this->findExtension($type, $element, $folder);
                            $isNew   = empty($current);

                            $typeName  = trim(($folder ?: '') . ' ' . $type);
                            $text      = 'LIB_ALLEDIAINSTALLER_RELATED_' . ($isNew ? 'INSTALL' : 'UPDATE');
                            $installer = new JInstaller();
                            if ($installer->install($path)) {
                                $this->setMessage(JText::sprintf($text, $typeName, $element));
                                if ($isNew) {
                                    $current = $this->findExtension($type, $element, $folder);
                                    if ($settings[0]) {
                                        $current->publish();
                                    }
                                    if ($settings[2] && ($type == 'plugin')) {
                                        $this->setPluginOrder($current, $settings[2]);
                                    }
                                }
                            } else {
                                $this->setMessage(JText::sprintf($text . '_FAIL', $typeName, $element), 'error');
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Uninstall the related extensions that are useless without the component
     */
    protected function uninstallRelated()
    {
        parent::uninstallRelated();

        if ($this->relatedExtensions) {
            $installer = new JInstaller();

            foreach ($this->relatedExtensions as $type => $folders) {
                foreach ($folders as $folder => $extensions) {
                    foreach ($extensions as $element => $settings) {
                        if ($settings[1]) {
                            if ($current = $this->findExtension($type, $element, $folder)) {
                                $msg     = 'LIB_ALLEDIAINSTALLER_RELATED_UNINSTALL';
                                $msgtype = 'message';
                                if (!$installer->uninstall($current->type, $current->extension_id)) {
                                    $msg .= '_FAIL';
                                    $msgtype = 'error';
                                }
                                $this->setMessage(JText::sprintf($msg, $type, $element), $msgtype);
                            }
                        }
                    }
                }
            }
        }
    }
}
