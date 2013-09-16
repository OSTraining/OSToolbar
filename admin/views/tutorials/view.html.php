<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class OSToolbarViewTutorials extends OSToolbarView {
	
	public function display($tpl = null) {
		
		$this->generateToolbar();
		
		$model		= JModel::getInstance('Tutorials', 'OSToolbarModel');
		
		if (JRequest::getVar('session', 0) && JRequest::getVar('tmpl', '') == 'component') :
			$session	= JFactory::getSession();
			$rows		= $session->get('helparticles', array(), 'OSToolbar');
			$this->setLayout('popup');
		else :
			$rows		= $model->getList();
			if ($errors = $model->getErrors()) :
				$rows	= array();
				OSToolbarHelper::renderErrors($errors);
			endif;
		endif;
		
		$filters	= $model->getFilters($rows);
		$rows		= $model->applyFilters($rows);

		$params = JComponentHelper::getParams('com_ostoolbar');
		if (OSToolbarRequestHelper::$isTrial)
		{
			if ($params->get('api_key'))
				JFactory::getApplication()->enqueueMessage(JText::_('COM_OSTOOLBAR_API_KEY_INVALIAD'), 'error');
			else
				JFactory::getApplication()->enqueueMessage(JText::_('COM_OSTOOLBAR_API_KEY_TRIAL'), 'error');
		}
		
		/*
		$params = JComponentHelper::getParams('com_ostoolbar');
		if (!$params->get('api_key')) {
			JFactory::getApplication()->enqueueMessage(JText::_('COM_OSTOOLBAR_API_KEY_ERROR'), 'error');
		}
		*/
		
		$this->assignRef('model', $model);
		$this->assignRef('rows', $rows);
		$this->assignRef('filters', $filters);
		
		parent::display($tpl);
	}
	
	private function generateToolbar() {
		OSToolbarHelper::setPageTitle(JText::_('COM_OSTOOLBAR_TUTORIALS'));
		OSToolbarHelper::customButton(JText::_('COM_OSTOOLBAR_TUTORIALS'), 'icon-32-tutorials','tutorials', 'index.php?option=com_ostoolbar&view=tutorials');
		JToolBarHelper::preferences('com_ostoolbar', 500, 700);
		/*OSToolbarHelper::customButton(JText::_('COM_OSTOOLBAR_HELP'), 'icon-32-help','help', 'index.php?option=com_ostoolbar&view=help');*/
	}
	
}
