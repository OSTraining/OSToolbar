<?php
defined('JPATH_BASE') or die;

class JFormFieldVideos extends JFormField
{
	protected $type = 'Videos';

	protected function getInput()
	{
		JLoader::register('OSToolbarSystem', JPATH_SITE.'/administrator/components/com_ostoolbar/helpers/system.php');
		$check = OSToolbarSystem::check();

		JLoader::register('OSToolbarHelper', JPATH_SITE.'/administrator/components/com_ostoolbar//helpers/helper.php');
		JLoader::register('OSToolbarRequestHelper', JPATH_SITE.'/administrator/components/com_ostoolbar//helpers/request.php');
		JLoader::register('OSToolbarCacheHelper', JPATH_SITE.'/administrator/components/com_ostoolbar//helpers/cache.php');
		JLoader::register('JRestRequest', JPATH_SITE.'/administrator/components/com_ostoolbar//rest/request.php');
		JLoader::register('OSToolbarModel', JPATH_SITE.'/administrator/components/com_ostoolbar/base/model.php');

		JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR."/components/com_ostoolbar/models");

		$model		= JModelLegacy::getInstance('Tutorials', 'OSToolbarModel');
		$available = $model->getList(true);
		if (OSToolbarRequestHelper::$isTrial)
		{
			$document = JFactory::getDocument();
			$document->addStyleDeclaration("#jform_videos-lbl{display:none}");
			return JText::_('COM_OSTOOLBAR_API_KEY_ERROR');
		}

		/*
		$data	= array('resource' => 'articles');
		$response = OSToolbarRequestHelper::makeRequest($data);

		$available	= $response->getBody();
		if ($response->hasError())
		{
			$document = JFactory::getDocument();
			$document->addStyleDeclaration("#jform_videos-lbl{display:none}");
			return JText::_('COM_OSTOOLBAR_API_KEY_ERROR');
		}
		*/
		
		if ($this->value)
			$selected = preg_split("/,/",$this->value);
		else $selected = array();
		
		$empty = count($selected) ? false : true;

		for($i=0; $i<count($available); $i++) :
			$available[$i]->link = 'index.php?option=com_ostoolbar&view=tutorial&id='.$available[$i]->id;
			if ($empty)
			{
				$selected[] = "s_".$available[$i]->id;
			}
		endfor;
		
		$document = JFactory::getDocument();
		$document->addScript('http://code.jquery.com/ui/1.10.0/jquery-ui.js');
		$document->addStyleSheet(JURI::root().'administrator/components/com_ostoolbar/assets/css/ui-lightness/jquery-ui-1.8.6.custom.css'); 
		$document->addStyleDeclaration("#jform_videos-lbl{display:none}
				#sortable1, #sortable2 {
					width:250px;
					float:left;
					list-style:none;
					padding:0;
					margin:0;
					padding:3px;
					border:1px solid #dedede;
					height:300px;
					overflow-y:scroll;
				}
				
				#sortable1 {
					margin-right:15px;
				}
				
				#sortable1 li, #sortable2 li {
					padding:5px;
					margin-top:1px;
					cursor:pointer;
				}
			");
		$document->addScriptDeclaration("
		
			jQuery(function() {
				jQuery('#sortable1, #sortable2').sortable({
					connectWith: '.connectedSortable'
				}).disableSelection();
				
				function updateSortableField() {
					var selected = jQuery('#sortable2').sortable('toArray');
					var string	= selected.join(',');
					jQuery('#".$this->id."_id').val(string);
				}
				
				jQuery('#sortable2').bind('sortupdate', function(event, ui) {
					updateSortableField();
				});
				
				updateSortableField();
			});
		");
		
		ob_start();
		?>
        	<div>
            	<div style="float:left; width:270px"><?php echo(JText::_("ORGINAL_VIDEOS"));?></div>
            	<div style="float:left; width:50px">&nbsp;</div>
            	<div style="float:left; width:250px"><?php echo(JText::_("COLLECTION"));?></div>
                <div style="clear:both"></div>
            </div>
            <div class='sortable_holder'>
                <ul id="sortable1" class="connectedSortable">
                    <?php 
					$data = array();
					for($i=0; $i<count($available); $i++) :
                        $item	= $available[$i];
                        $name	= $item->title;
						if (in_array("s_".$item->id, $selected))
						{
							$data["s_".$item->id] = $item;
							continue;
						}
                    ?>
                        <li class="ui-state-default" id="s_<?php echo $item->id;?>"><?php echo $name; ?></li>
                    <?php endfor; ?>
                </ul>
            	<div style="float:left; width:50px"><?php echo(JText::_("DRAP_DROP_TO_COLLECT"));?></div>
                <ul id="sortable2" class="connectedSortable">
                    <?php 
					for($i=0; $i<count($selected); $i++) :
						if (!isset($data[$selected[$i]]))
							continue;
                        $item	= $data[$selected[$i]];
                        $name	= $item->title."";
                    ?>
                        <li class="ui-state-highlight" id="s_<?php echo $item->id;?>"><?php echo $name; ?></li>
                    <?php endfor; ?>
                </ul>
                <div class="clearfix"></div>
            </div>
        
        
        	<input type="hidden" id="<?php echo($this->id);?>_id" name="<?php echo($this->name);?>" value="<?php echo($this->value);?>" />
        <?php
		$input = ob_get_contents();
		ob_end_clean();
		return $input;
	}
} 