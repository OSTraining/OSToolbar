<?php
defined('JPATH_BASE') or die;

require_once JPATH_ADMINISTRATOR . '/components/com_ostoolbar/library/include.php';

class JFormFieldVideos extends JFormField
{
    protected $type = 'Videos';

    protected function getInput()
    {

        $model     = JModelLegacy::getInstance('Tutorials', 'OSToolbarModel');
        $available = $model->getList(true);

        if (OstoolbarRequest::$isTrial) {
            $document = JFactory::getDocument();
            $document->addStyleDeclaration("#jform_videos-lbl{display:none}");
            return JText::_('COM_OSTOOLBAR_API_KEY_ERROR');
        }

        JHtml::_('ost.jquery');
        JHtml::_('script', 'com_ostoolbar/jquery-ui.js', false, true);
        JHtml::_('stylesheet', 'com_ostoolbar/ui-lightness/jquery-ui.css', null, true);

        $selected = $this->value ? explode(',', $this->value) : array();
        $empty = !(bool)count($selected);

        for ($i = 0; $i < count($available); $i++) {
            $available[$i]->link = 'index.php?option=com_ostoolbar&view=tutorial&id=' . $available[$i]->id;
            if ($empty) {
                $selected[] = "s_" . $available[$i]->id;
            }
        }

        $this->loadCss();
        $this->loadJs();

        return $this->getHtml($available, $selected);
    }

    protected function loadCss()
    {
        $css = <<<CSS
#jform_videos-lbl {
    display: none
}

#sortable1,
#sortable2 {
width: 250px;
float: left;
list-style: none;
margin: 0;
padding: 3px;
border: 1px solid #dedede;
height: 300px;
overflow-y: scroll;
}

#sortable1 {
margin-right: 15px;
}

#sortable1 li, #sortable2 li {
    padding: 5px;
    margin-top: 1px;
    cursor: pointer;
}
CSS;
        JFactory::getDocument()->addStyleDeclaration($css);
    }

    protected function loadJs()
    {
        $id = $this->id;
        $js = <<<JS
(function($) {
    $(document).ready(function() {
        $('#sortable1, #sortable2').sortable({
            connectWith: '.connectedSortable'
        }).disableSelection();

        function updateSortableField() {
            var selected = $('#sortable2').sortable('toArray');
            var string	= selected.join(',');
            $('#{$id}_id').val(string);
        }

        $('#sortable2').bind('sortupdate', function(event, ui) {
            updateSortableField();
        });
        updateSortableField();
    });
})(jQuery);
JS;
        JFactory::getDocument()->addScriptDeclaration($js);
    }

    protected function getHtml(array $available, array $selected = array())
    {
        ob_start();
        ?>
        <div>
            <div style="float:left; width:270px"><?php echo(JText::_("COM_OSTOOLBAR_HIDDEN_VIDEOS")); ?></div>
            <div style="float:left; width:50px">&nbsp;</div>
            <div style="float:left; width:250px"><?php echo(JText::_("COM_OSTOOLBAR_SELECTED_VIDEOS")); ?></div>
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
                <?php
                endfor;
                ?>
            </ul>
            <div style="float:left; width:50px"><?php echo(JText::_("COM_OSTOOLBAR_DRAG_DROP_VIDEOS")); ?></div>
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
                <?php
                endfor;
                ?>
            </ul>
            <div class="clearfix"></div>
        </div>

        <input type="hidden" id="<?php echo($this->id); ?>_id" name="<?php echo($this->name); ?>"
               value="<?php echo($this->value); ?>"/>
    <?php
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }
}
