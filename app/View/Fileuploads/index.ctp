<div class="fileuploads index">
	<h2><?php __('Fileuploads');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('url');?></th>
			<th><?php echo $this->Paginator->sort('type');?></th>
			<th><?php echo $this->Paginator->sort('size');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($fileuploads as $fileupload):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $fileupload['Fileupload']['id']; ?>&nbsp;</td>
		<td><?php echo $fileupload['Fileupload']['name']; ?>&nbsp;</td>
		<td><?php echo $fileupload['Fileupload']['type']; ?>&nbsp;</td>
		<td><?php echo $fileupload['Fileupload']['size']; ?>&nbsp;</td>
		<td><?php echo $fileupload['Fileupload']['created']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $fileupload['Fileupload']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $fileupload['Fileupload']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $fileupload['Fileupload']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $fileupload['Fileupload']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
	));
	?>	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Fileupload', true), array('action' => 'add')); ?></li>
	</ul>
</div>