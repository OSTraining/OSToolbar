<?php
defined('JPATH_BASE') or die;

class JFormFieldLanguage extends JFormField
{
	protected $type = 'Language';

	function loadLanguage($lang_file, $editable)
	{
		$translates = null;
		if (JFile::exists($lang_file)) 
		{
			$translates = array();
			$content = JFile::read($lang_file);
			$lines = preg_split("/\n/", $content);
			
			foreach ($lines as $line)
			{
				if (strlen(trim($line)))
				{
					list($key, $value) = preg_split("/=/", $line);
					
					if (count($editable) && !in_array(trim($key), $editable))
						continue;
					
					$value = trim(substr($line, strlen($key) + 1));	
					$translates[trim($key)] = trim(preg_replace("/\"/", "", $value));
				}
			}
		}
		return $translates;
	}

	protected function getInput()
	{
		
		$response = OSToolbarRequestHelper::makeRequest(array('resource' => 'checkapi'));

		if ($response->hasError() || $response->getBody() == 0)
		{
			$document = JFactory::getDocument();
			$document->addStyleDeclaration("#jform_language-lbl{display:none}");
			ob_start();
			?>
            	<script>
				window.addEvent('domready', function() {
					$$('p.tab-description')[0].innerHTML = '<?php echo(JText::_('COM_OSTOOLBAR_API_KEY_ERROR'));?>';
					$('permissions-sliders').outerHTML = '';
					
				});
				</script>
                <?php echo(JText::_('COM_OSTOOLBAR_API_KEY_ERROR'));?>
            <?php
			$input = ob_get_contents();
			ob_end_clean();
			return $input;
		}
		
		jimport('joomla.filesystem.file'); 
		//JHTML::_("behavior.mootools");
		
		$document = JFactory::getDocument();
		$document->addStyleDeclaration("#jform_language-lbl{display:none}");
		
		$db = & JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('a.*');
		$query->from('`#__extensions` AS a');
		$query->where('a.client_id = 0 AND a.type = "language"');
		$db->setQuery($query);
		$languages = $db->loadObjectList();
		
		$default_lang 		= $languages[0]->element;
		$file 				= $default_lang . ".com_ostoolbar.ini";
		$com_path 			= JPATH_SITE.DS."administrator".DS."components".DS."com_ostoolbar";
		$default_lang_file	= JPath::clean($com_path.DS."language".DS.$default_lang.DS.$file);

		if (!JFile::exists($default_lang_file))
		{
			// Find available lang file if default language doesn't have language file
			foreach ($languages as $language)
			{
				$default_lang = $language->element;
				$lang_file = JPath::clean($com_path.DS."language".DS.$language->element.DS.$language->element.".com_ostoolbar.ini");
				if (JFile::exists($lang_file)) 
					break;
			}
		}
		
		$default_lang = "en-GB"; // Hard code
		
		$translates = array();
		foreach ($languages as $language)
		{
			$translates[$language->element] = array();
			$lang_file = JPath::clean($com_path.DS."language".DS.$language->element.DS.$language->element.".com_ostoolbar.ini");
			$system_lang_file = JPath::clean($com_path.DS."language".DS.$language->element.DS.$language->element.".com_ostoolbar.sys.ini");
			$plg_lang_file = JPath::clean(JPATH_ADMINISTRATOR.DS."language".DS.$language->element.DS.$language->element.".plg_quickicon_ostoolbar.ini");
			$plg_sys_lang_file = JPath::clean(JPATH_ADMINISTRATOR.DS."language".DS.$language->element.DS.$language->element.".plg_quickicon_ostoolbar.sys.ini");
			$sys_plg_lang_file = JPath::clean(JPATH_ADMINISTRATOR.DS."language".DS.$language->element.DS.$language->element.".plg_system_ostoolbar.ini");
			$sys_plg_sys_lang_file = JPath::clean(JPATH_ADMINISTRATOR.DS."language".DS.$language->element.DS.$language->element.".plg_system_ostoolbar.sys.ini");
			$editable = array("COM_OSTOOLBAR");
			$translates[$language->element]["sys"] = $this->loadLanguage($system_lang_file, $editable);
			$editable = array("PLG_QUICKICON_OSTOOLBAR");
			$translates[$language->element]["plg"] = $this->loadLanguage($plg_lang_file, $editable);
		}
		
		foreach ($languages as $language)
		{
			if (!($translates[$language->element]["sys"]))
				$translates[$language->element]["sys"] = $translates[$default_lang]["sys"];
			if (!($translates[$language->element]["plg"]))
				$translates[$language->element]["plg"] = $translates[$default_lang]["plg"];
		}
		
		
		ob_start();
		?>
        	<style>
			.tab_item label
			{
				width:160px;
			}
			.tab_item input
			{
				width:300px;
			}
			</style>
            <div style="clear:both"></div>
        	<div>
                <select name="language_code" id="language_code" onchange="ChangeLanguage()">
                    <?php foreach ($languages as $language):?>
                        <option value="<?php echo($language->element);?>" <?php if ($default_lang == $language->element) echo('selected="selected"');?> ><?php echo($language->name);?></option>
                    <?php endforeach;?>
                </select>
                <button onclick="SaveLanguage(); return false;" id="btn_apply_lang" style="float:left"><?php echo(JText::_("Update"));?></button>
                <div id="lbl_ajax_msg" style="float:left; display:block; padding-top:7px;"></div>
            </div>
            <div style="clear:both"></div>
            <i style="color:#F30"><?php echo(JText::_("LANGUAGE_NOTICE_MSG"));?></i>
            <div style="position:relative;">
            	<div class="tab_item"><label for="sys_COM_OSTOOLBAR"><?php echo(JText::_("CONFIG_COM_OSTOOLBAR"));?></label><input type="text" name="sys_language" id="COM_OSTOOLBAR" value="<?php echo($translates[$language->element]["sys"]["COM_OSTOOLBAR"]);?>" /></div>
            	<div class="tab_item"><label for="plg_PLG_QUICKICON_OSTOOLBAR"><?php echo(JText::_("CONFIG_PLG_QUICKICON_OSTOOLBAR"));?></label><input type="text" name="plg_language" id="PLG_QUICKICON_OSTOOLBAR" value="<?php echo($translates[$language->element]["plg"]["PLG_QUICKICON_OSTOOLBAR"]);?>" /></div>
            </div>
            <script>
				var defaultLang = '<?php echo($default_lang);?>';
				var currLang =defaultLang;
				var translates = eval('(<?php echo preg_replace("/'/", "\\'", json_encode($translates));?>)');
				function SaveLanguage()
				{
					var lang = document.getElementById("language_code").options[document.getElementById("language_code").selectedIndex].value;
					UpdateLang(lang);
				
					//var jsonstr = "{"+myJsonify( translates )+"}";	
					var jsonstr = JSON.stringify(translates);
					var ajax = new Request({
						method:"POST",
						data:"language_data="+jsonstr,
						onComplete: DisplaySummary,
						url:'<?php echo JURI::root();?>administrator/index.php?option=com_ostoolbar&task=updatelanguage'});
					ajax.send();
					document.getElementById("btn_apply_lang").disabled = true;
					document.getElementById("lbl_ajax_msg").innerHTML = "Please wait";
				}
				
				function DisplaySummary(result)
				{
					document.getElementById("lbl_ajax_msg").innerHTML = "Completed";
					document.getElementById("btn_apply_lang").disabled = false;
				}
				
				function UpdateLang(selectedLang)
				{
					var inputs = document.getElementsByName("sys_language");
					for (var i = 0; i < inputs.length; i++)
						translates[selectedLang]["sys"][inputs[i].id] = inputs[i].value;

					var inputs = document.getElementsByName("plg_language");
					for (var i = 0; i < inputs.length; i++)
						translates[selectedLang]["plg"][inputs[i].id] = inputs[i].value;

				}
				
				function ChangeLanguage()
				{
					UpdateLang(currLang);
					
					var lang = document.getElementById("language_code").options[document.getElementById("language_code").selectedIndex].value;

					var inputs = document.getElementsByName("sys_language");
					for (var i = 0; i < inputs.length; i++)
						if (typeof translates[lang]["sys"][inputs[i].id] != "undefined")
							inputs[i].value = translates[lang]["sys"][inputs[i].id];

					var inputs = document.getElementsByName("plg_language");
					for (var i = 0; i < inputs.length; i++)
						if (typeof translates[lang]["plg"][inputs[i].id] != "undefined")
							inputs[i].value = translates[lang]["plg"][inputs[i].id];

					currLang = lang;
				}
				
            </script>
        
        <?php
		$input = ob_get_contents();
		ob_end_clean();
		return $input;
	}
} 