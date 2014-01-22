<?php
defined('_JEXEC') or die('Restricted access');
$params		= JComponentHelper::getParams('com_ostoolbar');
if ($params->get("videos"))
	$selected = preg_split("/,/",$params->get("videos"));
else
	$selected = array();
?>
<h3><?php echo(JText::_("Tutorials"));?></h3>
<div class="ostoolbar">
	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="tablelist">
    	<thead>
            <tr>
                <th>#</th>
                <th>Title</th>
            </tr>
        </thead>
        <tbody>
			<?php foreach ($this->items as $i => $item):?>
                <?php
                    if (!in_array($item->jversion, array("1.6_trial")) && is_array($selected) && count($selected) && !in_array("s_".$item->id, $selected))
                    {
                        continue;
                    }
                ?>
                <tr class="row_<?php echo($i %2);?>">
                    <td><?php echo($i + 1);?></td>
                    <td><a href="<?php echo($item->link);?>"><?php echo($item->title);?></a></td>
                </tr>
            <?php endforeach;?>
        </tbody>
    </table>
</div>