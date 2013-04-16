this's Admin pages
<BR>
<?php  echo $this->Html->link('account management',array('controller'=>'users', 'action'=>'account')); ?>
<BR>
<?php  echo $this->Html->link('group management',array('controller'=>'admin', 'action'=>'groupmanager')); ?>
<BR>
<?php  echo $this->Html->link('Create CSV file',array('controller'=>'admin', 'action'=>'writecsv')); ?>	
<BR>
<?php  echo $this->Html->link('add new regions',array('controller'=>'admin', 'action'=>'addregion')); ?>
