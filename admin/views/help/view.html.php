<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class OSToolbarViewHelp extends OSToolbarView {
	
	public function display($tpl = null) {
		
		$this->generateToolbar();
		
		/*
		$params = JComponentHelper::getParams('com_ostoolbar');
		if (!$params->get('api_key')) {
			JFactory::getApplication()->enqueueMessage(JText::_('COM_OSTOOLBAR_API_KEY_ERROR'), 'error');
		}
		*/

		$row	= OSToolbarCacheHelper::callback($this, '_fetchData', array(), OSToolbarCacheHelper::HOUR, true);
		
		if ($row == false)
		{
			$this->setLayout('error');
			$this->assignRef('msg', JText::_('COM_OSTOOLBAR_NOT_FOUND'));
			parent::display($tpl);
			return;
		}
		
		$return		= 'index.php?option='.$this->option.'&view=tutorials';

		$this->assignRef('return', $return);
		$this->assignRef('row', $row);
				
		parent::display($tpl);
	}

	public function _fetchData() {
		$data	= array('resource' => 'helppage');
		
		$response = OSToolbarRequestHelper::makeRequest($data);
		if ($response->hasError()) :
		{
			JFactory::getApplication()->enqueueMessage(JText::_('COM_OSTOOLBAR_API_KEY_ERROR'), 'error');
			return false;
		}
		endif;
		
		$body 	= $response->getBody();
		
		if ($body != false) 
		{
			$body->introtext = OSToolbarRequestHelper::filter($body->introtext);
			$body->fulltext  = OSToolbarRequestHelper::filter($body->fulltext);
		}
		
		return $body;
	}
	
	private function generateToolbar() {
		OSToolbarHelper::setPageTitle(JText::_('COM_OSTOOLBAR_HELP'));
		OSToolbarHelper::customButton(JText::_('COM_OSTOOLBAR_TUTORIALS'), 'icon-32-tutorials','tutorials', 'index.php?option=com_ostoolbar&view=tutorials');
		JToolBarHelper::preferences('com_ostoolbar', 500, 500);
		/*OSToolbarHelper::customButton(JText::_('COM_OSTOOLBAR_HELP'), 'icon-32-help','help', 'index.php?option=com_ostoolbar&view=help');*/
	}
	
}
