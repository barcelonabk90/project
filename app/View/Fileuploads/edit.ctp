<div class="fileuploads form">
<?php echo $this->Form->create('Fileupload');?>
	<fieldset>
		<legend><?php __('Edit Fileupload'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('url');
		echo $this->Form->input('type');
		echo $this->Form->input('size');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Fileupload.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('Fileupload.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Fileuploads', true), array('action' => 'index'));?></li>
	</ul>
</div>