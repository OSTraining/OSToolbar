<?php
defined('JPATH_BASE') or die;

class JFormFieldLogo extends JFormField
{
    protected $type = 'Logo';

    protected function getInput()
    {
        $response = OstoolbarRequest::makeRequest(array('resource' => 'checkapi'));

        if ($response->hasError() || $response->getBody() == 0) {
            ob_start();
            ?>
            <div style="float:left; position:relative">
                <div style="float:left; margin-right:10px;margin-top:8px"
                     id="img_<?php echo($this->id); ?>"><?php echo(JText::_('COM_OSTOOLBAR_API_KEY_ERROR')); ?></div>
                <div style="clear:both"></div>
            </div>
            <div style="clear:both"></div>
            <div style="height:20px;">&nbsp;</div>
            <?php
            $input = ob_get_contents();
            ob_end_clean();
            return $input;

        }

        header('Pragma: no-cache');

        JHtml::_('script', 'com_ostoolbar/fileuploader.js', false, true);
        JHtml::_('stylesheet', 'com_ostoolbar/fileuploader.css', null, true);

        switch ($this->name) {
            case "jform[panel_logo]":
                $imageFile = "ost-logo.png";
                $note = JText::_("PNG_FORMAT_LARGE");
                break;

            case "jform[menu_logo]":
                $imageFile = "ost_icon.png";
                $note = JText::_("PNG_FORMAT_SMALL");
                break;

            case "jform[tutorial_logo]":
                $imageFile = "icon-tutorials-small.png";
                $note = JText::_("PNG_FORMAT_MEDIUM");
                break;

            case "jform[plugin_logo]":
                $imageFile = "quickicon/ost_icon_24.png";
                $note = JText::_("PNG_FORMAT_MEDIUM");
                break;

            default:
                $imageFile = '';
                $note = '';
                break;
        }

        ob_start();
        ?>
        <div style="float:left; position:relative">
            <div style="margin-right:10px;" id="img_<?php echo($this->id); ?>">
                <?php echo JHtml::_('image', 'com_ostoolbar/' . $imageFile, $note, null, true); ?>
            </div>
            <div id="file-uploader-<?php echo($this->id); ?>">
                <noscript>
                    <p><?php echo("ENANLE_JS"); ?></p>
                </noscript>
            </div>
            <div style="clear:both"></div>
            <div><?php echo($note); ?></div>
        </div>
        <div style="clear:both"></div>
        <div style="height:20px;">&nbsp;</div>
        <script>
            //function createUploader<?php echo($this->id);?>(){
            var uploader<?php echo($this->id);?> = new qq.FileUploader({
                element   : document.getElementById('file-uploader-<?php echo($this->id);?>'),
                action    : '<?php echo JURI::root();?>administrator/index.php?option=com_ostoolbar&task=updatelogo&type=<?php echo($this->name);?>',
                multiple  : false,
                onComplete: function onUploadComplete<?php echo($this->id);?>() {
                    var obj = document.getElementById("img_<?php echo($this->id);?>");
                    var new_image = new Image();
                    new_image.src = obj.firstChild.src + "?" + new Date();
                    obj.removeChild(obj.firstChild);
                    obj.appendChild(new_image);
                },
                debug     : false
            });
            //}

            //window.onload = createUploader<?php echo($this->id);?>;
            /*
             */
        </script>

        <?php
        $input = ob_get_contents();
        ob_end_clean();
        return $input;
    }
}
