<?php
defined('JPATH_BASE') or die;

class plgSystemOsToolbar extends JPlugin
{
    function __construct(&$subject, $config)
    {
        parent::__construct($subject, $config);

    }

    function onAfterDispatch()
    {
        $app = JFactory::getApplication();

        if (!$app->isAdmin()) {
            return;
        }

        $option = JRequest::getVar("option");
        if ($option == "com_ostoolbar") {
            return;
        }

        $doc = JFactory::getDocument();
        $doc->addStyleDeclaration(
            ".icon-32-tutorial{background:url('" . JURI::root() . "administrator/components/com_ostoolbar/assets/images/icon-tutorials-small.png');width:46px !important;}
		div.toolbar-list span {height:34px !important}
		"
        );

        $bar = JToolBar::getInstance('toolbar');
        $bar->appendButton(
            'Popup',
            'tutorial',
            JText::_("Tutorials"),
            'index.php?option=com_ostoolbar&amp;view=tutorials&amp;tmpl=component',
            600,
            400,
            0,
            0,
            ''
        );
    }


}
