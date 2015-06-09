<?php
/**
 * @package   com_ostoolbar
 * @contact   www.ostraining.com, support@ostraining.com
 * @copyright 2014 Open Source Training, LLC. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

defined('_JEXEC') or die();

$app = JFactory::getApplication();
if ($app->input->getCmd('tmpl', '') == 'component') {
    $target = 'target="_blank"';
} else {
    $target = '';
}
?>
<div class="ostoolbar">
    <?php echo OstoolbarHelper::getTrialBanner(); ?>
    <form action='index.php' method='get' name='adminForm'>
        <div class="filter-search fltlft">
            <?php echo JText::_('COM_OSTOOLBAR_CATEGORY'); ?>
            <?php echo $this->filters['category'] ?>
        </div>
        <div class="clr"></div>

        <table class="table table-striped adminlist">
            <tbody>
            <?php
            $params = JComponentHelper::getParams('com_ostoolbar');
            if ($params->get("videos")) {
                $selected = preg_split("/,/", $params->get("videos"));
            } else {
                $selected = array();
            }
            $index = 0;
            for ($i = 0; $i < count($this->rows); $i++) :
                $row   = $this->rows[$i];
                if (
                    !in_array($row->jversion, array("1.6_trial"))
                    && is_array($selected) && count($selected)
                    && !in_array("s_" . $row->id, $selected)
                ) {
                    continue;
                }
                $class = $index++ % 2 ? 'row0' : 'row1';
                ?>
                <tr class="<?php echo $class; ?>">
                    <td class="tutnumber"><?php echo $index; ?></td>
                    <td class="tut">
                        <?php echo JHtml::link($row->link, $row->title, $target); ?>
                    </td>
                    <td class="tutcat">
                        <?php echo $row->ostcat_name ?: '--'; ?>
                    </td>
                </tr>
            <?php endfor; ?>
            </tbody>
        </table>

        <input type='hidden' name='task' value=''/>
        <input type='hidden' name='option' value='<?php echo $this->option; ?>'/>
        <input type='hidden' name='view' value='tutorials'/>
        <input type='hidden' name='boxchecked' value='0'/>
        <input type="hidden" name="filter_order" value="<?php echo $this->model->getState('filter.order'); ?>"/>
        <input type="hidden" name="filter_order_Dir" value="<?php echo $this->model->getState('filter.order_dir'); ?>"/>
        <?php echo JHTML::_('form.token'); ?>
    </form>
</div>
