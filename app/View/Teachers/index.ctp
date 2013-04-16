this's teachers pages
<BR/>
<?php  echo $this->Html->link('Upload CSV file',array('controller'=>'teachers', 'action'=>'upcsv')); ?>
<br/>
<?php echo $this->Html->link('List Tests',array('action'=>'testList'))."<br/>";?>
<?php  if($flag=='1'){
        echo $this->Html->link('Score',array('controller'=>'teachers', 'action'=>'listtest')); 
}
?>
