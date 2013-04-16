<div id="Unfix_test">
<?php //echo $this->Form->create('Test'); 
header('Content-type: text/html; charset=utf-8');
        echo $this->Form->create('Test',array('action'=>'process'));
?>
    <h2 align="center">
            Group name : <?php echo $groupName; ?><br/>
            Testee     : <?php echo $testeeID; ?><br/>
        </h2>
    <fieldset>
        
        <?php
       
       foreach ($tests as $test){
           
                      
           ?>
        <legend> 
            <input type="hidden" name="testID" value="<?php echo $test['testID'] ?>" />
            <input type="hidden" name="type" value="<?php echo $test['testType'] ?>" />
            <p align="center">
            Start Time : <?php echo $test['startDateTime']."<br/>"; ?> 
            End Time : <?php echo $test['endDateTime']; ?>
            </p>
            <?php
           echo __("Test".".".$test['testID'].".".$test['testTitle'].".".$test['testSubTitle']);?></legend><?php
           foreach ($questions as $question){
            echo "<br/>";
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
            }
        }
       }
       
    ?>
    </fieldset>
<?php echo "<input type='submit' name='submit' value = 'submit' ";

?>
    
</div>

