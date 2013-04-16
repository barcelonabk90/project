<?php 
//print_r ($Testees);
foreach($Testees as $testee)
	{
//	print_r ($testee['testees']);
//	echo '<BR>';
echo $this->Html->link($testee['testees']['testeeID']."=>".$testee['testees']['name'], array('controller'=>'teachers','action' => 'handscore',$testee['testees']['testeeID']."*".$testee['testees']['testID']));
echo '<BR>';
	
	}
	
?>