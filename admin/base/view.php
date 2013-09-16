<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class OSToolbarView extends JViewLegacy {

	protected $option	= null;
	protected $view		= null;

	function __construct() {
		$this->set('option', JRequest::getCmd('option'));
		parent::__construct();
	}
	
	private function generateSubmenu() {
		$views = $this->getMainViews();
	
		foreach($views as $item) :
			$link = 'index.php?option='.$this->get('option').'&view='.$item['view'];
			JSubMenuHelper::addEntry($item['name'], $link, ($this->get('view') == $item['view']));
		endforeach;
	
	}
	
	protected function getMainViews() {
		$views = array(
					array('name' => JText::_('COM_OSTOOLBAR_TUTORIALS'), 'view' => 'tutorials', 'icon' => 'icon-tutorials.png'),
					array(
						'name' => JText::_('COM_OSTOOLBAR_PARAMETERS'), 
						'link' => 'index.php?option=com_config&amp;view=component&amp;component=com_ostoolbar&amp;path=&amp;tmpl=component', 
						'rel' => '{handler: \'iframe\', size: {x: 570, y: 400}}', 
						'class' => 'modal',
						'icon' => 'icon-parameters.png'
					),
					array('name' => JText::_('COM_OSTOOLBAR_HELP'), 'view' => 'help', 'icon' => 'icon-help.png')
				);
		return $views;
	}
	
	protected function routeLayout($tpl) {
		$layout = ucwords(strtolower($this->getLayout()));
		
		if ($layout == 'Default') :
			return false;
		endif;
		
		$method_name = 'display'.$layout;
		if (method_exists($this, $method_name) && is_callable(array($this, $method_name))) :
			$this->$method_name($tpl);
			return true;
		else :
			$this->setLayout('default');
			return false;
		endif;
		
	}
}
