<?php
defined('_JEXEC') or die('Restricted access');
?>
<div class="ostoolbar">
<form action='index.php' method='get' name='adminForm'>
<fieldset>
	<legend><?php echo JText::_('COM_OSTOOLBAR_FILTER');?></legend>
	<table>
		<tr>
			<td><?php echo JText::_('COM_OSTOOLBAR_CATEGORY');?>:</td>
			<td><?php echo $this->filters['category']?></td>
		</tr>
	</table>
</fieldset>
<table cellpadding='4' cellspacing='0' border='0' width='100%' class='adminlist'>
	<tbody>
	<?php 
	$params		= JComponentHelper::getParams('com_ostoolbar');
	if ($params->get("videos"))
		$selected = preg_split("/,/",$params->get("videos"));
	else
		$selected = array();
	$index = 0;
	for($i=0; $i<count($this->rows); $i++) :
		$row = $this->rows[$i];
		if (!in_array($row->jversion, array("1.6_trial")) && is_array($selected) && count($selected) && !in_array("s_".$row->id, $selected))
		{
			continue;
		}
		$class = $index++%2 ? 'row0' : 'row1';
		?>
		<tr class="<?php echo $class;?>">
			<td class="tutnumber"><?php echo $index;?></td>
			<td class="tut">
				<a href="<?php echo $row->link;?>" <?php if (JRequest::getVar("tmpl")=="component") echo('target="_blank"');?> >
					<?php echo $row->title; ?>
				</a>
			</td>
			<td class="tutcat"><?php echo $row->ostcat_name ? $row->ostcat_name : '--'; ?></td>
		</tr>
	<?php endfor; ?>
	</tbody>
</table>

<input type='hidden' name='task' value='' />
<input type='hidden' name='option' value='<?php echo $this->option;?>' />
<input type='hidden' name='view' value='tutorials' />
<input type='hidden' name='boxchecked' value='0' />
<input type="hidden" name="filter_order" value="<?php echo $this->model->getState('filter.order');?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->model->getState('filter.order_dir');?>" />
<?php echo JHTML::_('form.token'); ?>
</form>
</div>
