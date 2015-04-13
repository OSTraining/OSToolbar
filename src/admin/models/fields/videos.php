<?php
defined('JPATH_BASE') or die;

require_once JPATH_ADMINISTRATOR . '/components/com_ostoolbar/library/include.php';

class JFormFieldVideos extends JFormField
{
    protected $type = 'Videos';

    protected function getInput()
    {
        OstoolbarSystem::check();

        $model     = JModelLegacy::getInstance('Tutorials', 'OSToolbarModel');
        $available = $model->getList(true);
        if (OstoolbarRequest::$isTrial) {
            $document = JFactory::getDocument();
            $document->addStyleDeclaration("#jform_videos-lbl{display:none}");
            return JText::_('COM_OSTOOLBAR_API_KEY_ERROR');
        }

        if ($this->value) {
            $selected = preg_split("/,/", $this->value);
        } else {
            $selected = array();
        }

        $empty = count($selected) ? false : true;

        for ($i = 0; $i < count($available); $i++) :
            $available[$i]->link = 'index.php?option=com_ostoolbar&view=tutorial&id=' . $available[$i]->id;
            if ($empty) {
                $selected[] = "s_" . $available[$i]->id;
            }
        endfor;

        JHtml::_('script', 'http://code.jquery.com/ui/1.10.0/jquery-ui.js');
        JHtml::_('stylesheet', 'com_ostoolbar/ui-lightness/jquery-ui-1.8.6.custom.css', null, true);

        $document = JFactory::getDocument();
        $document->addStyleDeclaration(
            "#jform_videos-lbl{display:none}
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
			"
        );
        $document->addScriptDeclaration(
            "

			jQuery(function() {
				jQuery('#sortable1, #sortable2').sortable({
					connectWith: '.connectedSortable'
				}).disableSelection();

				function updateSortableField() {
					var selected = jQuery('#sortable2').sortable('toArray');
					var string	= selected.join(',');
					jQuery('#" . $this->id . "_id').val(string);
				}

				jQuery('#sortable2').bind('sortupdate', function(event, ui) {
					updateSortableField();
				});

				updateSortableField();
			});
		"
        );

        ob_start();
        ?>
        <div>
            <div style="float:left; width:270px"><?php echo(JText::_("ORGINAL_VIDEOS")); ?></div>
            <div style="float:left; width:50px">&nbsp;</div>
            <div style="float:left; width:250px"><?php echo(JText::_("COLLECTION")); ?></div>
            <div style="clear:both"></div>
        </div>
        <div class='sortable_holder'>
            <ul id="sortable1" class="connectedSortable">
                <?php
                $data = array();
                for ($i = 0; $i < count($available); $i++) :
                    $item = $available[$i];
                    $name = $item->title;
                    if (in_array("s_" . $item->id, $selected)) {
                        $data["s_" . $item->id] = $item;
                        continue;
                    }
                    ?>
                    <li class="ui-state-default" id="s_<?php echo $item->id; ?>"><?php echo $name; ?></li>
                <?php endfor; ?>
            </ul>
            <div style="float:left; width:50px"><?php echo(JText::_("DRAP_DROP_TO_COLLECT")); ?></div>
            <ul id="sortable2" class="connectedSortable">
                <?php
                for ($i = 0; $i < count($selected); $i++) :
                    if (!isset($data[$selected[$i]])) {
                        continue;
                    }
                    $item = $data[$selected[$i]];
                    $name = $item->title . "";
                    ?>
                    <li class="ui-state-highlight" id="s_<?php echo $item->id; ?>"><?php echo $name; ?></li>
                <?php endfor; ?>
            </ul>
            <div class="clearfix"></div>
        </div>


        <input type="hidden" id="<?php echo($this->id); ?>_id" name="<?php echo($this->name); ?>"
               value="<?php echo($this->value); ?>"/>
        <?php
        $input = ob_get_contents();
        ob_end_clean();
        return $input;
    }
}
