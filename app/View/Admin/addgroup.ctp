<div class="users form">
<?php echo $this->Form->create('Group'); ?>
    <fieldset>
        <legend><?php echo __('Add new group'); ?></legend>
        <?php echo $this->Form->input('groupID');
        
        echo $this->Form->input('groupName');
        
        echo $this->Form->input('contractTime');
               
        
    ?>
        <div class="input select required"><label for="regionID">
                Region ID</label><select name="data[Group][regionID]" id="regionID">
		<?php 
                
		foreach ($Regions as $region) 
                {
                    
                    echo ('<option value="'.$region['Region']['regionID'].'">'.$region['Region']['regionName'].'</option>');       	
                }
		?>
		</select></div>
    
   
    </fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>