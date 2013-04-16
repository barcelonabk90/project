<h1>Teacher Management</h1>

<table border="1">
    <tr>
        <th>GroupID</th>
        <th>Teacher</th>
                <th>Action</th>
    </tr>

<!-- Here's where we loop through our $posts array, printing out post info -->

<?php foreach ($Users as $user): ?>
<tr>
<td><?php echo $user['User']['groupID']?></td>
<td><?php echo $user['User']['username']?></td>
<td>
"action"      
</td>
</tr>
<?php endforeach; ?>

</table> 



