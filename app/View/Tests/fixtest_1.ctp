<script language=JavaScript>
var txt=" ..:: WelCome To Website | Online Test System ------ By Team 11 - K53 - HEDSPI";
var expert=200;
// speed of roll
var refresh=null;
function marquee_title(){
document.title=txt;
txt=txt.substring(1,txt.lenghth)+txt.charAt(0);
refresh=setTimeout("marquee_title()",expert);
}
marquee_title();
</script>
 

<div id="fix_test">
<?php //echo $this->Form->create('Test'); 
header('Content-type: text/html; charset=utf-8');
        echo $this->Form->create('Test',array('action'=>'fixprocess','name'=>'testForm'));
?>
    

<h2 align="center">
            Group name : <?php echo $groupName; ?><br/>
            Testee     : <?php echo $testeeID; ?><br/>
</h2>
<fieldset>
        
<?php foreach ($tests as $test){ ?>
    <div class="login-date">
        <p align ="center"><b> TOTAL TIME: <span class="min">
        <?php
            $test_time=$test['testTime']/60;
            $next = 50000;
            echo $test_time;
            //echo $test_data['test']['TestTime']/3600;
            echo '</span> : <span class="sec">00</span>';        
        ?>
        </b></p>
    <!-- </span> : 
                <span class="sec">00</span>     -->
    </div>
<legend> 
    <input type="hidden" name="testID" value="<?php echo $test['testID'] ?>" />
    <input type="hidden" name="type" value="<?php echo $test['testType'] ?>" />
    <p align="center">
            Test Time : <?php echo $test['testTime']/60; ?> minutes 
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
            if($question['Question']['TM']!='-1')
            {
            echo '<p> 問題の時間 <input width="50" name="Time['.$questionID.']"  value = "0" id = "testtime'.$questionID.'"> senconds </p>';
        
            }          
            else {
            echo '<p style="display:none"> 問題の時間 <input name="Time['.$questionID.']" value = "00" id = "testtime'.$questionID.'" > senconds </p>';
}
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
            echo "<br/>";
            echo "<br/>";
            
        echo '</div>';
           }         
           
        echo $this->Form->button('next',array(
            'type'=>'button',
            'id'=>'next',
            'class'=>'button'
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
     //  var a=<?php echo $next ?>;
     //  int next = parseInt (a);
     //  setTimeout(function() {
     //  $('#next').trigger('click');
     //   }, next);
       

});
function countup() {
    var t=parseInt($('#testtime'+index).val());
    $('#testtime'+index).val(t+1);
}

function countdown() {
    var m = $('.min');
    var s = $('.sec');
   
    if(m.length == 0 && parseInt(s.html()) <= 0) {
        $('.login-date').html('Timer Complete.');
         //document.testForm.submit();return false;         
         $('#btaction').trigger('click');
    }

    //s++;
    if(parseInt(s.html()) <= 0) {
        m.html(parseInt(m.html())-1);   
        s.html(59);
    }

    if(parseInt(m.html()) <= 0) {
        $('.login-date').html('TIME: <span class="sec">59</span> seconds.'); 
    }

    s.html(parseInt(s.html()-1));
}
setInterval('countdown()',1000);
setInterval('countup()',1000);
        
//function get_textBox() {
//        var str1 = document.getElementById('<%= TextBox1.ClientID %>').value;
        
 //           }

</script>

