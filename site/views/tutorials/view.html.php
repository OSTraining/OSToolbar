<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.viewlegacy');

class OSToolbarViewTutorials extends JViewLegacy
{
	var $items;
	function display($tpl = null)
	{
		$this->items = $this->get("List");
		parent::display($tpl);
	}
}
?>