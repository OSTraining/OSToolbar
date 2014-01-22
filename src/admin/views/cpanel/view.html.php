<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class OSToolbarViewCpanel extends OSToolbarView {
	
	public function display($tpl = null) {
		
		if ($this->routeLayout($tpl)) :
			return;
		endif;
		
		JHTML::_('behavior.modal', 'a.modal');
		
		
		JFactory::getDocument()->addScriptDeclaration("
			window.addEvent('domready', function() {
				if(window.location.hash == '#parameters') {
					$('link_parameters').fireEvent('click');
				}
				
				if ($('ost_launch_params')) {
					$('ost_launch_params').addEvent('click', function(e) {
						new Event(e).stop();
						$('link_parameters').fireEvent('click');
					});
				}
				
			});
		");
		
		$this->generateToolbar();
		
		$views 		= $this->getMainViews();
		
		/*
		$params = JComponentHelper::getParams('com_ostoolbar');
		if (!$params->get('api_key')) {
			JFactory::getApplication()->enqueueMessage(JText::_('COM_OSTOOLBAR_API_KEY_ERROR'), 'error');
		}
		*/

		$this->assignRef('views', $views);
		$this->assignRef('modified', $modified);
		
		parent::display($tpl);
	}
	
	private function generateToolbar() {
		OSToolbarHelper::setPageTitle(JText::_('COM_OSTOOLBAR_CONTROL_PANEL'));
	}
	
}
