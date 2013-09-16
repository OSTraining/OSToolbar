<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

class OSToolbarViewTutorial extends JView
{
	var $item;
	function display($tpl = null)
	{
		$this->item		= $this->get("Data");

		parent::display($tpl);
	}
}
?>