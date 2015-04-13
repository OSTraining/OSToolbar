<?php
/**
 * @package   com_ostoolbar
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2014 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

defined('_JEXEC') or die();

jimport('joomla.model.model');

class OstoolbarController extends JControllerLegacy
{
    protected $option       = null;
    protected $saved_object = null;

    public function __construct($params = array())
    {
        parent::__construct($params);

        $input = JFactory::getApplication()->input;

        $this->set('option', $input->getCmd('option', ''));
        JModelLegacy::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . '/models');
        JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . '/tables');
    }

    public function display($cachable = false, $urlparams = false)
    {
        $input = JFactory::getApplication()->input;
        $view  = $input->getCmd('view', '');
        if (!$view) {
            $input->set('view', 'tutorials');
        }

        parent::display($cachable, $urlparams);
    }

    private function saveLanguage($data, $lang_file, $default_lang_file)
    {
        $translates = array();
        if (!JFile::exists($lang_file)) {
            JFile::copy($default_lang_file, $lang_file);
            $content = JFile::read($default_lang_file);
        } else {
            $content = JFile::read($lang_file);
        }
        $lines = preg_split("/\n/", $content);
        foreach ($lines as $line) {
            if (strlen(trim($line))) {
                list($key, $value) = preg_split("/=/", $line);
                if (is_array($value)) {
                    $value = implode("=", $value);
                }
                $value                  = substr($line, strlen($key) + 1);
                $translates[trim($key)] = trim(preg_replace("/\"/", "", $value));
            }
        }
        foreach ($data as $key => $value) {
            $translates[$key] = $value;
        }
        $lines = array();
        foreach ($translates as $key => $value) {
            $lines[] = "$key=\"$value\"";
        }
        JFile::write($lang_file, implode("\r\n", $lines));
    }

    public function updatelanguage()
    {
        // @TODO: No obvious equivalent to this in JInput
        $language_data = JRequest::getVar("language_data", '', 'post', 'string', JREQUEST_ALLOWRAW);
        $data          = json_decode($language_data, true);

        $db    = &JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('a.*');
        $query->from('`#__extensions` AS a');
        $query->where('a.client_id = 0 AND a.type = "language"');
        $db->setQuery($query);
        $languages = $db->loadObjectList();

        $default_lang      = $languages[0]->element;
        $file              = $default_lang . ".com_ostoolbar.ini";
        $com_path          = JPATH_SITE . "/administrator/components/com_ostoolbar";
        $default_lang_file = JPath::clean($com_path . "/language/" . $default_lang . "/" . $file);

        if (!JFile::exists($default_lang_file)) {
            // Find available lang file if default language doesn't have language file
            foreach ($languages as $language) {
                $default_lang_file = JPath::clean(
                    $com_path . "/language/" . $language->element . "/" . $language->element . ".com_ostoolbar.ini"
                );
                if (JFile::exists($default_lang_file)) {
                    break;
                }
            }
        }

        $default_lang          = "en-GB"; // Hard code
        $default_lang_file     = JPath::clean(
            $com_path . "/language/" . $default_lang . "/" . $default_lang . ".com_ostoolbar.ini"
        );
        $default_sys_lang_file = JPath::clean(
            $com_path . "/language/" . $default_lang . "/" . $default_lang . ".com_ostoolbar.sys.ini"
        );
        $default_plg_lang_file = JPath::clean(
            JPATH_ADMINISTRATOR . "/language/" . $default_lang . "/" . $default_lang . ".plg_quickicon_ostoolbar.ini"
        );

        foreach ($languages as $language) {
            $lang_file        = JPath::clean(
                $com_path . "/language/" . $language->element . "/" . $language->element . ".com_ostoolbar.ini"
            );
            $system_lang_file = JPath::clean(
                $com_path . "/language/" . $language->element . "/" . $language->element . ".com_ostoolbar.sys.ini"
            );
            $plg_lang_file    = JPath::clean(
                JPATH_ADMINISTRATOR . "/language/" . $language->element . "/" . $language->element . ".plg_quickicon_ostoolbar.ini"
            );

            $this->saveLanguage($data[$language->element]["com"], $lang_file, $default_lang_file);
            $this->saveLanguage($data[$language->element]["sys"], $system_lang_file, $default_sys_lang_file);
            $this->saveLanguage($data[$language->element]["plg"], $plg_lang_file, $default_plg_lang_file);

        }
        echo(1);
        exit();
    }

    public function updatelogo()
    {
        $type     = JFactory::getApplication()->input->get("type");
        $data     = file_get_contents('php://input');
        $com_path = JPATH_SITE . "/administrator/components/com_ostoolbar";
        switch ($type) {
            case "jform[panel_logo]":
                $path = $com_path . "/assets/images/ost-logo.png";
                break;
            case "jform[menu_logo]":
                $path = $com_path . "/assets/images/ost_icon.png";
                break;
            case "jform[tutorial_logo]":
                $path = $com_path . "/assets/images/icon-tutorials-small.png";
                break;
            case "jform[plugin_logo]":
                $path = JPATH_SITE . "/media/plg_quickicon_ostoolbar/images/ost_icon_24.png";
                break;

            default:
                $path = '';
                break;
        }

        echo($path);
        JFile::write($path, $data);
        echo("{success:true}");
        exit();
    }

    public function cancel()
    {
        $ret = JFactory::getApplication()->input->get('ret');
        $this->setRedirect($ret ?: 'index.php?option=' . $this->option);
    }
}
