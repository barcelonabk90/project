

<div id="Unfix_test">
<?php //echo $this->Form->create('Test'); 
header('Content-type: text/html; charset=utf-8');
        echo $this->Form->create('Test',array('action'=>'process','name'=>'testForm'));
?>
    

<h2 align="center">
            Group name : <?php echo $groupName; ?><br/>
            Testee     : <?php echo $testeeID; ?><br/>
</h2>
<fieldset>
        
<?php foreach ($tests as $test){ ?>
    
<legend> 
    <input type="hidden" name="testID" value="<?php echo $test['testID'] ?>" />
    <input type="hidden" name="type" value="<?php echo $test['testType'] ?>" />
    <p align="center">
            Start Time : <?php echo $test['startDateTime']."<br/>"; ?> 
            End Time : <?php echo $test['endDateTime']; ?>
    </p>
            <?php
           echo __("Test".".".$test['testID'].".".$test['testTitle'].".".$test['testSubTitle']);?></legend><?php
           echo '<label id="total" style="display:none">'.count($questions).'</label>';
           foreach ($questions as $question)
            {
           $i=$question['Question']['questionID'];    
               if($i==1){
            echo '<div id="content1">';
               }
               else { 
            echo '<div id="content'.$i.'" style="display:none">';
               }
            echo "<br/>";
            echo "<br/>";            
            echo "<br/>";
            if ($question['Question']['TM']!=(-1))                                     
            echo "Time for this question is ".$question['Question']['TM']."s";
        
            echo "<br/><br/>";
            $questionID=$question['Question']['questionID'];
            echo $question['Question']['questionID'].".&nbsp;&nbsp;".$question['Question']['questionContent'];
            echo "<br/>";
            foreach ($choices as $choice){
                if($question['Question']['questionID']==$choice['Choice']['questionID']){
                $questionID=$question['Question']['questionID'];
                $choiceID=$choice['Choice']['choiceID'];
                if($question['Question']['questionType']=="QS"){
                    echo "<br>"."<input type='checkbox' name='test[".$questionID."][".$choiceID."]' value='$choiceID' />
                        &nbsp;&nbsp;&nbsp;&nbsp;";
                    echo $choice['Choice']['choiceContent'];
                    echo "<br/>";
                }
                else if($question['Question']['questionType']=="QW"){
                    $param=$choice['Choice']['choiceParam'];
                    echo $param;
                    echo "<br/>"."<input type='text' maxLength='$param' name='test[".$questionID."][".$choiceID."]'>"."<br/>"; 
                    
                }
            }
            }            echo "<br/>";
            echo "<br/>";
            
        echo '</div>';
           }         
           
        
        echo $this->Form->button('previous',array(
            'type'=>'button',
            'id'=>'previous',
            'class'=>'button',
            'size'=>'30'
            ));
        
        echo $this->Form->button('next',array(
            'type'=>'button',
            'id'=>'next',
            'class'=>'button',
            'size'=>'30'
            
            ));
    
       }
       
    ?>
    </fieldset>
<?php echo '<input type="submit" name="submit" id="btaction" value = "submit" class="button" />';

?>
        

</div>
<script type="text/javascript">

index=1;
total = parseInt($('#total').text());
time=parseInt($('#questionTime').text());
$(document).ready(function(){
    $('#next').click(function(){
        if(index<total){
            var tmp='#content'+index;
            $(tmp).hide();
            index=index+1;
            var tmp='#content'+index;
            $(tmp).show();
        }
    });
    
    $('#previous').click(function(){
        if(index>1){
            var tmp='#content'+index;
            $(tmp).hide();
            index=index-1;
            var tmp='#content'+index;
            $(tmp).show();
        }
    }); 
        
});


</script>

