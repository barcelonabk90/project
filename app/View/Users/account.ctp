<h1>Account Management</h1>
<p><?php echo $this->Html->link("Create new acc", array('controller'=>'users','action' => 'add')); ?></p>
<table border="1">
    <tr>
        <th>Group</th>
        <th>Username</th>
                <th>Action</th>
    </tr>

<!-- Here's where we loop through our $posts array, printing out post info -->

<?php foreach ($Users as $User): ?>
<tr>
<td><?php echo $User['User']['groupID']?></td>
<td><?php echo $User['User']['username']?></td>
<td><?php echo $this->Html->link('edit',array('controller'=>'users', 'action'=>'editacc', "username = '".$User['User']['username']."' and groupID = '".$User['User']['groupID']."'")); ?> , 
	<?php echo $this->Html->link('delete',array('controller'=>'users', 'action'=>'delete', $User['User']['username']."*".$User['User']['groupID'])
		, null, 'Are you sure?'
	); ?> 
      
</td>
</tr>
<?php endforeach; ?>

<?php  echo $this->Html->link('Create new group account',array('controller'=>'users', 'action'=>'add')); ?>
</table> 
