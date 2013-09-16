<?php
defined('_JEXEC') or die('Restricted access');
?>
<div class="ostoolbar">
<?php if (in_array($this->item->jversion, array("1.6_trial"))):?>
    <iframe src="http://www.ostraining.com/services/adv/adv1.html" width="734px" height="80px" style="padding-left:10px"></iframe>
<?php endif;?>

<fieldset class='adminform'>
    <legend><?php echo $this->item->title;?></legend>
    <form action="index.php" method="post" name='adminForm'>
        <table width='100%' cellpadding='5' cellspacing='0' class='admintable form-validate'>
            <tr>
                <td><?php echo $this->item->introtext.$this->item->fulltext;?></td>
            </tr>
        </table>
    </form>
</fieldset>
</div>