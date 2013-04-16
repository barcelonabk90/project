<h1>List all test </h1>
<table border="1">
    <tr>
        <th>Test Title</th>
        <th>Test Subtitle</th>
                <th>Created time</th>
             
    </tr>

<?php foreach ($Tests as $test): ?>
<tr>
	 <td>
	 	<?php  
	 $testTitle=$test['Test']['testTitle'];
	  echo $this->Html->link($testTitle,array('controller'=>'tests', 'action'=>'login',$test['Test']['testID'])); ?>
	</td>
	<td>
	 	<?php  
	 $testSubTitle=$test['Test']['testSubTitle'];
	  echo $this->Html->link($testSubTitle,array('controller'=>'tests', 'action'=>'login',$test['Test']['testID'])); ?>
	</td>
		<td>
	 	<?php  
	 $testTime=$test['Test']['startDateTime'];
	  echo $this->Html->link($testTime,array('controller'=>'tests', 'action'=>'login',$test['Test']['testID'])); ?>
	</td>
</tr>
<?php endforeach; ?>

</table> 
