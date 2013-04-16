<div class="users form">
<?php echo $this->Session->flash('auth'); ?>

<?php echo $this->Form->create('User'); ?>
    <fieldset>
        <legend><?php echo __('Please enter your username and password'); ?></legend>
        <?php echo $this->Form->input('username');?>

       <div class="input select required"><label for="UserGroupID">Group I D</label><select name="data[User][groupID]" id="UserGroupID">
		<?php 
		foreach ($Groups as $gr) 
			echo ('<option value="'.$gr['Group']['groupID'].'">'.$gr['Group']['groupName'].'</option>');       	
		?>
		</select></div>
		<?php echo $this->Form->input('password');?>

    </fieldset>
<?php echo $this->Form->end(__('Login')); ?>
</div>