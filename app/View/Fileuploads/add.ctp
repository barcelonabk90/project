<div class="fileuploads form">
<?php echo $this->Form->create('Fileupload',array('type'=>'file'));?>
	<fieldset>
		<legend><?php __('Add Fileupload'); ?></legend>
	<?php
		//echo $form->input('Fileupload.name');
		//echo $form->input('Fileupload.file', array('type' => 'file'));
		echo $this->Form->input('file',array('type'=>'file'));
		//echo $this->Form->input('type');
		//echo $this->Form->input('size');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
<?php 
	
?>

</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Fileuploads', true), array('action' => 'index'));?></li>
	</ul>
</div>