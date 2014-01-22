<?php
defined('_JEXEC') or die('Restricted access');
?>
<div class="ostoolbar">
<form action='index.php' method='post' name='adminForm'>
<table cellpadding='4' cellspacing='0' border='0' width='100%' class='adminlist'>
	<thead>
		<tr>
			<th width="20">#</th>
			<th>Name</th>
		</tr>
	</thead>
	<tbody>
	<?php for($i=0; $i<count($this->rows); $i++) :
		$row = $this->rows[$i];
		$class = $i%2 ? 'row0' : 'row1';
		?>
		<tr class="<?php echo $class;?>">
			<td><?php echo $this->pagination->getRowOffset($i);?></td>
			<td>
				<a href="<?php echo $row->link;?>">
					<?php echo $row->name; ?>
				</a>
			</td>
		</tr>
	<?php endfor; ?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan='13'><?php echo $this->pagination->getListFooter();?></td>
		</tr>
	</tfoot>
</table>

<input type='hidden' name='task' value='' />
<input type='hidden' name='c' value='transaction' />
<input type='hidden' name='option' value='<?php echo $this->option;?>' />
<input type='hidden' name='view' value='transactions' />
<input type='hidden' name='boxchecked' value='0' />
<?php echo JHTML::_('form.token'); ?>
</form>
</div>