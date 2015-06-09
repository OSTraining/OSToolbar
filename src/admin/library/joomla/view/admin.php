<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

abstract class OstoolbarViewAdmin extends JViewLegacy
{
    /**
     * @var JObject
     */
    protected $state = null;

    /**
     * @var string
     */
    protected $option = 'com_ostoolbar';

    /**
     * @var string
     */
    protected $view = null;

    public function display($tpl = null)
    {
        if (version_compare(JVERSION, '3.0', 'ge')) {
            $hide    = JFactory::getApplication()->input->getBool('hidemainmenu', false);
            $sidebar = count(JHtmlSidebar::getEntries()) + count(JHtmlSidebar::getFilters());
            if (!$hide && $sidebar > 0) {
                $start = array(
                    '<div id="j-sidebar-container" class="span2">',
                    JHtmlSidebar::render(),
                    '</div>',
                    '<div id="j-main-container" class="span10">'
                );
            } else {
                $start = array(
                    '<div id="j-main-container">'
                );
            }

            echo join("\n", $start) . "\n";
            parent::display($tpl);
            echo "\n</div>";
        } else {
            parent::display($tpl);
        }
        $this->displayFooter();
    }

    /**
     * Default admin screen title
     *
     * @param string $sub
     * @param string $icon
     *
     * @return void
     */
    protected function setTitle($sub = null, $icon = 'ostoolbar')
    {
        $img = JHtml::image("com_ostoolbar/icon-48-{$icon}.png", null, null, true, true);
        if ($img) {
            $doc = JFactory::getDocument();
            $doc->addStyleDeclaration(".icon-48-{$icon} { background-image: url({$img}); }");
        }

        $title = JText::_('COM_OSTOOLBAR');
        if ($sub) {
            $title .= ': ' . JText::_($sub);
        }

        JToolbarHelper::title($title, $icon);
    }

    /**
     * Render the admin screen toolbar buttons
     *
     * @param bool $addDivider
     *
     * @return void
     */
    protected function setToolBar($addDivider = true)
    {
        $user = JFactory::getUser();
        if ($user->authorise('core.admin', 'com_ostoolbar')) {
            if ($addDivider) {
                JToolBarHelper::divider();
            }
            JToolBarHelper::preferences('com_ostoolbar');
        }
    }

    /**
     * Display a standard footer on all admin pages
     *
     * @return void
     */
    protected function displayFooter()
    {
        // Don't show footer on these views
    }
}
