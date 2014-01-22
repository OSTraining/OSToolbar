<?php
defined('_JEXEC') or die;

class OSToolbarFormHelper {
	
	function label($text, $for, $id=null) {
		$id = $id ? $id : $for;
		
		return "<label for='".$id."' name='".$id."_label' id='".$id."_label'>".$text."</label>";
	}
	
	function inputbox($name, $default=null, $multi=false, $class='inputbox', $size='35', $id=null) {
		if ($multi)
			$name .= '[]';
		
		if (!$id) :
			$id	= $name;
		endif;
		
		return "<input type='text' name='".$name."' size='".$size."' class='".$class."' id='".$id."' value='".$default."' />";
	}
	
	function textarea($name, $default=null, $multi=false, $class='inputbox', $cols='55', $rows='8') {
		if ($multi)
			$name .= '[]';
		
		return "<textarea name='".$name."' rows='".$rows."' cols='".$cols."' class='".$class."'>".$default."</textarea>";
	}
	
	function statusSelect($name, $default, $multi=false, $onChange = null, $nullOption = false) {
		if ($multi)
			$name .= "[]";
		
		$options 	= array();
		if ($nullOption) :
			$options[]	= JHTML::_('select.option', '', '---');
		endif;
		$options[]	= JHTML::_('select.option', 0, 'Pending');
		$options[]	= JHTML::_('select.option', 1, 'Approved');
		$options[]	= JHTML::_('select.option', -1, 'Rejected');
		
		$attributes = "class='inputbox'";
		if ($onChange) :
			$attributes .= " onchange='".$onChange."'";
		endif;
		
		return JHTML::_('select.genericlist', $options, $name, $attributes, 'value', 'text', $default);
	}
	
	function transactionSelect($name, $default, $onChange = false, $nullOption = false) {
		$params	= JComponentHelper::getParams('com_referrals');
		$types	= $params->get('transaction_types');
		
		$lines	= OSToolbarHelper::splitList($types, "\n");
		
		$sanitized	= array();
		if (!empty($lines)) :
			foreach($lines as $line) :
				$split					= OSToolbarHelper::splitList($line, "=");
				$trimmed				= array_map("trim", $split);
				$sanitized[$trimmed[0]]	= isset($trimmed[1]) ? $trimmed[1] : null;
			endforeach;
		endif;
		
		$options	= array();
		
		if ($nullOption) :
			$options[]	= JHTML::_('select.option', '', '---');
		endif;
		
		if (!empty($sanitized)) :
			foreach($sanitized as $key => $value) :
				$options[]	= JHTML::_('select.option', $key, $key);
			endforeach;
		endif;
		
		$attributes = "class='inputbox'";
		
		if ($onChange) :
			$document	= JFactory::getDocument();
			$document->addScriptDeclaration('
				function prefillValue(key) {
					var transaction_types = '.json_encode($sanitized).';
					if (transaction_types[key] && $("value")) {
						$("value").setProperty("value", transaction_types[key]);
					}
				}
			
				window.addEvent("domready", function() {
					$("'.$name.'").addEvent("change", function() {
						prefillValue(this.getProperty("value"));
					})
				});
			');
		endif;
		
		return JHTML::_('select.genericlist', $options, $name, $attributes, 'value', 'text', $default);
	}
	
}
