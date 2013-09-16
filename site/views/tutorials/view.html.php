<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

class OSToolbarViewTutorials extends JView
{
	var $items;
	function display($tpl = null)
	{
		$this->items = $this->get("List");
		parent::display($tpl);
	}
}
?>