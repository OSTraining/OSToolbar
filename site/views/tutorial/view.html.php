<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.viewlegacy');

class OSToolbarViewTutorial extends JViewLegacy
{
	var $item;
	function display($tpl = null)
	{
		$this->item		= $this->get("Data");

		parent::display($tpl);
	}
}
?>