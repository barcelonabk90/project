<div class="users form">
<?php echo $this->Session->flash('auth'); ?>

<?php echo $this->Form->create('Testee'); ?>
    <fieldset>
        <legend><?php echo __('Please enter your username and password'); ?></legend>
        <?php echo $this->Form->input('username');?>
		<?php echo $this->Form->input('password');?>
		<?php echo '<input type="hidden" name="testID" value= "'.$testID.'" />'; ?>
    </fieldset>
<?php echo $this->Form->end(__('Login')); ?>
</div>