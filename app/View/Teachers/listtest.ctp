<?php 
//print_r ($Tests);
foreach ($Tests as $test)
	{
	echo	$this->Html->link($test['Test']['testTitle'], array('controller'=>'teachers','action' => 'liststudents', $test['Test']['testID']));
	echo '<BR>';
	}
?>