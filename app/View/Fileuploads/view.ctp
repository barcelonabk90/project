<div class="fileuploads view">
<h2><?php  __('Fileupload');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $fileupload['Fileupload']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Url'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $fileupload['Fileupload']['url']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Type'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $fileupload['Fileupload']['type']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Size'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $fileupload['Fileupload']['size']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $fileupload['Fileupload']['created']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Fileupload', true), array('action' => 'edit', $fileupload['Fileupload']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete Fileupload', true), array('action' => 'delete', $fileupload['Fileupload']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $fileupload['Fileupload']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Fileuploads', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Fileupload', true), array('action' => 'add')); ?> </li>
	</ul>
</div>
