<div class="users form">
<?php echo $this->Form->create('User'); ?>
    <fieldset>
        <legend><?php echo __('Add User'); ?></legend>
        <?php echo $this->Form->input('username'); ?>
        <?php echo $this->Form->input('name'); ?>
        
        
        <div class="input select required"><label for="UserGroupID">Group I D</label><select name="data[User][groupID]" id="UserGroupID">
		<?php 
		foreach ($Groups as $gr) 
			echo ('<option value="'.$gr['Group']['groupID'].'">'.$gr['Group']['groupName'].'</option>');       	
		?>
		</select></div>

        
        
        <?php echo $this->Form->input('userType', array('options' => array( '1'=> 'admin', '2'=>'group' )));
        echo $this->Form->input('password');
        echo $this->Form->input('password_confirm',array('type' => 'password'));

    
    ?>
    </fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>