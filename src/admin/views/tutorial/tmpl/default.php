<?php
/**
 * @package   com_ostoolbar
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2014 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

defined('_JEXEC') or die();

if ($showAd = !empty($this->row->jversion)) {
    $showAd = in_array($this->row->jversion, array("1.6_trial"));
}
?>
<div class="ostoolbar">
    <?php if ($showAd): ?>
        <iframe src="http://www.ostraining.com/services/adv/adv1.html" width="734px" height="80px"
                style="padding-left:10px"></iframe>
    <?php endif; ?>

    <fieldset class='adminform'>
        <legend><?php echo $this->row->title; ?></legend>
        <form action="index.php" method="post" name='adminForm'>
            <table width='100%' cellpadding='5' cellspacing='0' class='admintable form-validate'>
                <tr>
                    <td><?php echo $this->row->introtext . $this->row->fulltext; ?></td>
                </tr>
            </table>

            <input type='hidden' name='id' id='id' value='<?php echo $this->row->id; ?>'/>
            <input type="hidden" name="task" id="task" value=""/>
            <input type="hidden" name="c" id="c" value="tutorial"/>
            <input type="hidden" name="ret" id="ret" value="<?php echo $this->return; ?>"/>
            <input type="hidden" name="option" id="option" value="<?php echo $this->option; ?>"/>
            <?php echo JHTML::_('form.token'); ?>
        </form>
    </fieldset>
</div>
