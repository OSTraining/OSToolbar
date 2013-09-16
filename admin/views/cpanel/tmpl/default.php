<?php
defined('_JEXEC') or die;
?>
<div class="ostoolbar">
	<div id="cpanel">
		<?php for ($i=0; $i<count($this->views); $i++) : 
				$view 	= $this->views[$i];
				$link 	= isset($view['link']) ? $view['link'] : 'index.php?option='.$this->option.'&view='.$view['view'];
				$class 	= isset($view['class']) ? 'class="'.$view['class'].'"' : null;
				$rel	= isset($view['rel']) ? 'rel="'.$view['rel'].'"' : null;
		?>
			<div style="float:left;">
				<div class="icon">
					<a id="link_<?php echo strtolower($view['name']);?>" href="<?php echo $link;?>" <?php echo $class." ".$rel;?>>
						<img src='components/com_ostoolbar/assets/images/<?php echo $view['icon'];?>' alt='<?php echo $view['name'];?>' />
						<span><?php echo $view['name'];?></span>
					</a>
				</div>
			</div>
		<?php endfor; ?>
	</div>
</div>