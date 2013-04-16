<h1>List tests </h1>
<table border="1">
    <tr>
        <th>Test Title</th>
        <th>Test Subtitle</th>
                <th>Created time</th>
             
    </tr>

<?php
        
        foreach ($tests as $test): ?>
<tr>
	 <td>
             <input type="hidden" name="testID" value="<?php echo $test['tests']['testID'] ; ?>" />
<?php  
         
	 $testTitle=$test['tests']['testTitle'];
	  echo $this->Html->link($testTitle,array('controller'=>'teachers', 'action'=>'checkTestType',$test['tests']['testID'])); ?>
	</td>
	<td>
	 	<?php  
	 $testSubTitle=$test['tests']['testSubTitle'];
	  echo $this->Html->link($testSubTitle,array('controller'=>'teachers', 'action'=>'checkTestType',$test['tests']['testID'])); ?>
	</td>
		<td>
	 	<?php  
	 $testTime=$test['tests']['startDateTime'];
	  echo $this->Html->link($testTime,array('controller'=>'teachers', 'action'=>'checkTestType',$test['tests']['testID'])); ?>
	</td>
</tr>
<?php endforeach; ?>

</table> 
