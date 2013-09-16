<?php
defined('_JEXEC') or die('Restricted access');
?>
<div class="ostoolbar">
<fieldset class='adminform'>
    <legend><?php echo JText::_('COM_OSTOOLBAR_HELP');?></legend>
		<h1><?php echo $this->row->title;?></h1>
		<div class="ost_help_body">
			<?php echo $this->row->introtext.$this->row->fulltext;?>
		</div>
		<input type='hidden' name='id' id='id' value='<?php echo $this->row->id;?>' />
		<input type="hidden" name="task" id="task" value="" />
		<input type="hidden" name="c" id="c" value="tutorial" />
		<input type="hidden" name="ret" id="ret" value="<?php echo $this->return;?>" />
		<input type="hidden" name="option" id="option" value="<?php echo $this->option;?>" />
		<?php echo JHTML::_('form.token'); ?>
		</form>
</fieldset>
</div>