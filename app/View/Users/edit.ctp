<div class="users form">
<?php echo $this->Form->create('User'); ?>
    <fieldset>
        <legend><?php echo __('Change password'); ?></legend>
        <?php 
        
        echo $this->Form->input('Old password',array('name'=>'password'));
        echo $this->Form->input('newpass',array('type' => 'password'));
        echo $this->Form->input('renewpass',array('type' => 'password'));
        
        

    
    ?>
    </fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>