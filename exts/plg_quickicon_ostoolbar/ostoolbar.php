<?php
defined('_JEXEC') or die;

class plgQuickiconOSToolbar extends JPlugin
{
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	public function onGetIcons($context)
	{
		if ($context != $this->params->get('context', 'mod_quickicon') || !JFactory::getUser()->authorise('core.manage', 'com_installer')) {
			return;
		}


		return array(array(
			'link' => 'index.php?option=com_ostoolbar',
			'image' => 'plg_quickicon_ostoolbar/ost_icon_24.png',
			'text' => JText::_('PLG_QUICKICON_OSTOOLBAR'),
			'id' => 'plg_quickicon_ostoolbar'
		));
	}
}
