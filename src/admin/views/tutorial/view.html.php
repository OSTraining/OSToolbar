<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class OSToolbarViewTutorial extends OSToolbarView {
	
	public function display($tpl = null) {
		
		$this->generateToolbar();
		
		if (JRequest::getVar('tmpl') == 'component') :
			$this->setLayout('popup');
		endif;
		
		$model		= $this->getModel();
		$row		= $model->getData();
		
		$return		= 'index.php?option='.$this->option.'&view=tutorials';
		$this->assignRef('return', $return);
		$this->assignRef('model', $model);
		$this->assignRef('row', $row);
				
		parent::display($tpl);
	}
	
	private function generateToolbar() {
		OSToolbarHelper::setPageTitle(JText::_('COM_OSTOOLBAR_TUTORIALS'));
		OSToolbarHelper::customButton(JText::_('COM_OSTOOLBAR_TUTORIALS'), 'icon-32-tutorials','tutorials', 'index.php?option=com_ostoolbar&view=tutorials');
		JToolBarHelper::preferences('com_ostoolbar', 500, 700);
		/*OSToolbarHelper::customButton(JText::_('COM_OSTOOLBAR_HELP'), 'icon-32-help','help', 'index.php?option=com_ostoolbar&view=help');*/
	}
	
}
