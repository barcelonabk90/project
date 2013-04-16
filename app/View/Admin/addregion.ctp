<div class="users form">
<?php echo $this->Form->create('Region'); ?>
    <fieldset>
        <legend><?php echo __('Add new region'); ?></legend>
        <?php echo $this->Form->input('regionName'); ?>
    </fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>