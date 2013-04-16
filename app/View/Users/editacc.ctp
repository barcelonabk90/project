<div class="users form">
<?php echo $this->Form->create('User'); ?>
    <fieldset>
        <legend><?php echo __('Change password '); ?></legend>
        <?php 
        echo $this->Form->input('password');
        echo $this->Form->input('newpass',array('type' => 'password'));
        echo $this->Form->input('renewpass',array('type' => 'password'));

        
        
        echo '<input type="hidden" name="editusername" value= "'.$edituser['username'].'" />';
    	echo '<input type="hidden" name="editgroupID" value= "'.$edituser['groupID'].'" />';
    ?>
    </fieldset>
    


<?php echo $this->Form->end(__('Submit')); ?>
</div>