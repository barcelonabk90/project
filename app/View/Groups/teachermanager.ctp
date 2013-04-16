<?php
print_r($Users);
?>


<h1>Teacher Management</h1>

<table border="1">
    <tr>
        
        <th>username</th>
                <th>Action</th>
    </tr>

<!-- Here's where we loop through our $posts array, printing out post info -->

<?php foreach ($Users as $user): ?>
<tr>

<td><?php echo $group['User']['username']?></td>
<td>
<?php echo $this->Html->link('delete',array('controller'=>'admin', 'action'=>'deletegroup', $group['Group']['groupID'])
		, null, 'Are you sure?'
	); ?> 
      
</td>
</tr>
<?php endforeach; ?>

</table> 


<p><?php echo $this->Html->link("Add new Group", array('controller'=>'admin','action' => 'addgroup')); ?></p>
