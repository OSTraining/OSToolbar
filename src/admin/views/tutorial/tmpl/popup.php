<?php
defined('_JEXEC') or die('Restricted access');
?>
<div class="ostoolbar">
    <?php
    if ($this->row) :
    ?>
    <h1 class="popup_tutorial_title"><?php echo $this->row->title; ?></h1>

    <div class="popup_tutorial_text"><?php echo $this->row->introtext . $this->row->fulltext; ?></div>
    <?php
    else :
    ?>
        <p><?php echo JText::_('COM_OSTOOLBAR_ERROR_NO_VIDEO'); ?></p>
    <?php
    endif;
    ?>
</div>
