<h1>Group Management</h1>

<table border="1">
    <tr>
        <th>GroupID</th>
        <th>GoupName</th>
                <th>Action</th>
    </tr>

<!-- Here's where we loop through our $posts array, printing out post info -->

<?php foreach ($Groups as $group): ?>
<tr>
<td><?php echo $group['Group']['groupID']?></td>
<td><?php echo $group['Group']['groupName']?></td>

<?php echo $this->Html->link('delete',array('controller'=>'admin', 'action'=>'deletegroup', $group['Group']['groupID'])
		, null, 'Are you sure?'
	); ?> 
      
</td>
</tr>
<?php endforeach; ?>

</table> 


<p><?php echo $this->Html->link("Create new group", array('controller'=>'admin','action' => 'add')); ?></p>