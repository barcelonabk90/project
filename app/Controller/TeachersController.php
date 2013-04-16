<?php
// app/Controller/UsersController.php
App::import('Core', 'ConnectionManager');

class TeachersController extends AppController {

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('upcsv','listtest','handscore','liststudents');
        header('Content-type: text/html; charset=utf-8');
        $this->loadModel("Test");
    }


    public function index(){
        $this->loadModel('Group');
        $this->loadModel('User');
        $username=AuthComponent::user('username');
        $arrayUser=$this->Group->query("SELECT * FROM users WHERE users.username= '$username'");
        
        $grID=$arrayUser['0']['users']['groupID'];
        
        $arrayGroup=$this->Group->query("SELECT * FROM groups WHERE groupID= '$grID'");
        
        $contractDate=$arrayGroup['0']['groups']['contractDate'];
        $contractTime=$arrayGroup['0']['groups']['contractTime'];
        
        $expire=  strtotime($contractDate)+$contractTime*30*24*3600;
        $timeNow=time()+7*3600;
        
           if($timeNow<= $expire){
            $this->set('flag','1');
        }
        else {
            $this->set('flag','0');
        }

    }

    
    public function unfixtest($id)
    {
        
        $this->loadModel("Question");
        $this->loadModel("Choice");
        $this->set("tests",$this->Test->find('first',array('conditions'=>array('Test.testID'=>$id))));
        $this->set("questions",$this->Question->find('all',array('conditions'=>array('Question.testID'=>$id))));
        $this->set("choices",$this->Choice->find('all',array('conditions'=>array('Choice.testID'=>$id))));                   
        
        
    }

     

    //------- Paging Normal
    function fixtest($id){

        $this->loadModel("Question");
        $this->loadModel("Choice");
        $this->set("tests",$this->Test->find('first',array('conditions'=>array('Test.testID'=>$id))));
        $this->set("questions",$this->Question->find('all',array('conditions'=>array('Question.testID'=>$id))));
        $this->set("choices",$this->Choice->find('all',array('conditions'=>array('Choice.testID'=>$id))));
        
    }      
    
    // Check test type
    function is_fix($testID)
    {

    $count = $this->Test->find('count', array('conditions' => array('testType'=>'Fix' ,'testID'=>$testID)));
    if($count>=1) return true;
    return false; 
    }
    
    public function checkTestType($id=null){
        print_r($_POST);
        $this->set('testID',$id);
        if($this->is_fix($id ))
            {   
                $this->Session->write('testType','fix');
                $this->redirect(array('controller' => 'teachers', 'action' => 'fixtest',$id ));                
           
            }
            else
            {   $this->Session->write('testType','unfix');
                $this->redirect(array('controller' => 'teachers', 'action' => 'unfixtest',$id ));
                
            }
        
    }
    
    public function testList()
    {
        //Get current userID
        
        $userID=AuthComponent::user('username');
        
        //load Test model
        $this->loadModel("Test");
        $this->set("tests",$this->Test->query("SELECT * FROM tests WHERE tests.username='$userID'"));
    }
    
    
    public function listtest()
    {
        $this->loadModel("Test");
        $qr=$this->Test->find('all');
        $this->set('Tests',$qr);

    }

    public function liststudents($id=null)
    {
        $this->loadModel("Test");
        $this->set('Testees',$this->Test->query("select *  from testees where testID='$id' ;"));
    }

    public function handscore($id =null)
    {
        $this->loadModel("Test");
        $dat=str_getcsv($id,"*");

        if ($this->request->is('post'))
        {   
            ////print_r($this->request);
            foreach($_POST as $i => $value) {
            if ($i!=0)
            {
                //echo $i.$value;
                $this->Test->query("update testinfo set grade=$value where testeeID='$dat[0]' and testID='$dat[1]' and questionID =$i;");
                $this->Session->setFlash(__('Grade have been updated'));
            }

        }
            
                $qr=$this->Test->query("select sum(grade) as score from testinfo where testeeID='$dat[0]' and testID='$dat[1]';");
                
                $i=(int) $qr[0][0]['score'];
                echo $i;
                $this->Test->query("update testees set grade='$i' where testeeID='$dat[0]' and testID='$dat[1]';");

                
        }
    
//echo $id;


//
//print_r($dat);

    $qrs=$this->Test->query("select DISTINCT testinfo.*  from testinfo,scores where testinfo.testeeID='$dat[0]' and testinfo.testID=$dat[1] and testinfo.testID=scores.testID and testinfo.questionID=scores.questionID and scores.score=-1;");
    $qr3=$this->Test->query("select DISTINCT testinfo.*  from testinfo,scores where testinfo.testeeID='$dat[0]' and testinfo.testID=$dat[1] and testinfo.testID=scores.testID and testinfo.questionID=scores.questionID and scores.score !=-1;");
    $qr2=$this->Test->query("select testinfo.*  from testinfo,scores where testeeID='$dat[0]' and testinfo.testID=$dat[1] ;");

   for($i=0;$i<count($qrs);$i++)
    {
        $q=$this->Test->query("select * from questions where testID=".$qrs[$i]['testinfo']['testID']." and questionID=".$qrs[$i]['testinfo']['questionID'].";");

        $qrs[$i]['testinfo']['question']=$q[0]['questions']['questionContent'];
    }
    
      for($i=0;$i<count($qr3);$i++)
    {
        $q=$this->Test->query("select * from questions where testID=".$qr3[$i]['testinfo']['testID']." and questionID=".$qr3[$i]['testinfo']['questionID'].";");
        
        $qr3[$i]['testinfo']['question']=$q[0]['questions']['questionContent'];
    }

    $this->set('check',$qr2);
    $this->set('TestInfos',$qrs);
    $this->set('Scoreds',$qr3);   
    }
/*
function connect()
{
    $con=mysqli_connect("localhost","root","123456","itjp");
// Check connection
    if (mysqli_connect_errno())
    {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    else
    {
    if (!mysqli_set_charset($con,"utf8")) {
        //printf("Error loading character set utf8: %s\n", mysqli_error($con));
    }
    else {
        //printf("Current character set: %s\n", mysqli_character_set_name($con));
        return $con;
    }

    }
}*/


function updateLM($LM,$testID,$questionID)
{
    $sql="UPDATE questions Set LM='$LM' where testID='$testID' and questionID='$questionID' ";
    $this->Test->query($sql);
    
}
function updateINS($INS,$testID,$questionID)
{
        $sql="UPDATE questions Set INS='$INS' where testID='$testID' and questionID='$questionID' ";
    $this->Test->query($sql);
    
}
function updateTM($TM,$testID,$questionID)
{

    $sql="UPDATE questions Set TM='$TM' where testID='$testID' and questionID='$questionID'";
   $this->Test->query($sql); 

}
function updateANC($ANC,$testID,$questionID)
{
 
        $sql="UPDATE questions Set ANC='$ANC' where testID='$testID' and questionID='$questionID'";
        $this->Test->query($sql);

    }

function addUser($userID,$name,$password,$userType)
{
    
    $sql="INSERT INTO users (userID,name,password,userType)
    VALUES('$userID','$name','$password','$userType')";
 $this->Test->query($sql);
}
// end add new user function

function addTest($testTitle,$testSubTitle,$startDateTime,$testTime,$endDateTime,$testType)
{
    $sql="INSERT INTO tests (testTitle,testSubTitle,startDateTime,testTime,endDateTime,testType)
VALUES('$testTitle','$testSubTitle','$startDateTime','$testTime','$endDateTime','$testType')";
   $this->Test->query($sql);
}
// end adding new test function
function addTestee($testeeID,$name,$password,$testID,$grade)
{
   
    $sql="INSERT INTO testees (testeeID,name,password,testID,grade)
VALUES('$testeeID','$name','$password','$testID','$grade')";
$this->Test->query($sql);
}
// end adđ testee function
function addQuestion($testID,$questionID,$questionType,$questionContent,$ANC,$LM,$TM,$INS)
{
  
    $sql="INSERT INTO questions (testID,questionID,questionType,questionContent,ANC,LM,TM,INS)
VALUES('$testID','$questionID','$questionType','$questionContent','$ANC','$LM','$TM','$INS')";
 $this->Test->query($sql);
}
// end add question function
function addChoice($testID,$questionID,$choiceID,$choiceContent,$choiceType,$choiceParam)
{
   
    $sql="INSERT INTO choices (testID,questionID,choiceID,choiceContent,choiceType,choiceParam)
VALUES('$testID','$questionID','$choiceID','$choiceContent','$choiceType','$choiceParam')";
$this->Test->query($sql);
}
// end choice add function
function addAnswer($testID,$questionID,$answerID,$answerType)
{
  
    $sql="INSERT INTO answers (testID,questionID,answerID,answerType)
VALUES('$testID','$questionID','$answerID','$answerType')";
$this->Test->query($sql);
}
function addAnswerContent($testID,$questionID,$answerID,$answerContent)
{

    $sql="INSERT INTO  answercontents(testID,questionID,answerID,answerContent)
VALUES('$testID','$questionID','$answerID','$answerContent')";
 $this->Test->query($sql);
}
function addScore($testID,$questionID,$answerID,$score,$scoreID)
{
   
    $sql="INSERT INTO scores (testID,questionID,answerID,score,scoreID)
VALUES('$testID','$questionID','$answerID','$score','$scoreID')";
$this->Test->query($sql);
}
// end add answer function
    function getTestID()
    {
 
    $result1 = $this->Test->query("SELECT testID FROM tests");
    $result = $this->Test->query("SELECT max(testID) FROM tests");
    echo($result[0][0]['max(testID)']);
   /* if( mysql_fetch_row($result1)==FALSE)
    {
        return 0;
    }
    $row = mysql_fetch_row($result);
    $id=$row[0];
    //mysqli_close($con);*/
    return $result[0][0]['max(testID)'];
    }

    function getQuestionType($testID,$questionID)
    {
        $con=connect();
        $result = mysqli_query($con,"SELECT * FROM questions WHERE questionID='$questionID' and testID='$testID'");
        $row = mysqli_fetch_array($result);
        return $row['questionType'];
    }
    function invalidFile($testID)
    {
        $con=connect();
        $result = mysqli_query($con,"SELECT * FROM choices WHERE testID='$testID'");
        while($row=mysqli_fetch_array($result))
        {
            if(strcmp($row['choiceType'],"S")==0)
            {
                $questionID=$row['questionID'];
                if(getQuestionType($testID,$questionID)!="QS")
                {
                    return 0;
                }
            }
            if(strcmp($row['choiceType'],"WR")==0)
            {
                $questionID=$row['questionID'];
                if(getQuestionType($testID,$questionID)!="QW")
                {
                    return 0;
                }
            }
        }
        $result = mysqli_query($con,"SELECT * FROM questions WHERE testID='$testID'");
        while($row=mysqli_fetch_array($result))
        {
            $questionID=$row['questionID'];
            $result1=mysqli_query($con,"SELECT count(scoreID) as numOfScore FROM scores WHERE testID='$testID' and questionID='$questionID'");
            $num=mysqli_fetch_array($result1);
            if($num['numOfScore']==0)
            {
                return 0;
            }
        }
        $result = mysqli_query($con,"SELECT count(testeeID) as number FROM testees WHERE testID='$testID'");
        $num=mysqli_fetch_array($result);
        if($num['number']==0)
        {
            return 0;
        }
        return 1;
    }

    function processCSVFile($filename)
    {

    header('Content-Type: text/html; charset=UTF-8');
    iconv_set_encoding("internal_encoding", "UTF-8");
    iconv_set_encoding("output_encoding", "UTF-8");
    //setlocale(LC_ALL, 'de_DE.utf8');
    $handle1 = fopen($filename, "r");
    $students=0;
    $questions=0;
    $testTitle="";
    $testSubTitle="";
    $testTime=0;
        $startDateTime="";
        $endDateTime="";
    $data1=fgetcsv($handle1,1000,",");

        if(strcmp($data1[0],"TestTitle")==0)
        {
            $testTitle=$data1[1];
        }
        $commentState=0;
        $preQuestion=0;
    while (($data1 = fgetcsv($handle1,1000,",")) !== FALSE) {
        $pattern="/^Es/";
        if(preg_match($pattern,$data1[0]))
        {
            if(strcmp($data1[0],"Estimate")!=0)
            {
                print "<strong>ERROR: Estimate</strong>";
                return 0;
            }
            if(strcmp($data1[1],"P")!=0)
            {
                print "<strong>ERROR: Estimate</strong>";
                return 0;
            }
        }
        $pattern="/^Aver/";
        if(preg_match($pattern,$data1[0]))
        {
            if(strcmp($data1[0],"Average")!=0)
            {
                print "<strong>ERROR: Average</strong>";
                return 0;
            }
        }
        $pattern="/ing$/";
        if(preg_match($pattern,$data1[0]))
        {
            if(strcmp($data1[0],"Ranking")!=0)
            {
                print "<strong>ERROR: Ranking</strong>";
                return 0;
            }
        }
        $pattern="/^Gr/";
        if(preg_match($pattern,$data1[0]))
        {
            if(strcmp($data1[0],"Graphical")!=0)
            {
                print "<strong>ERROR: Graphical</strong>";
                return 0;
            }
        }
        $pattern="/^Hi/";
        if(preg_match($pattern,$data1[0]))
        {
            if(strcmp($data1[0],"Histgram")!=0)
            {
                print "<strong>ERROR: Histgram</strong>";
                return 0;
            }
        }
        $pattern="/^T/";
        if(preg_match($pattern,$data1[0]))
        {
            if(strcmp($data1[0],"Trend")!=0)
            {
                print "<strong>ERROR: Trend</strong>";
                return 0;
            }
        }
        $pattern="/^#/";

        $n=count($data1);
        if(preg_match($pattern,$data1[0]))
        {

            $data1=array("");
            continue;
        }
        $pattern1="/^\/\*/";
        $pattern2="/\*\/$/";
        if(preg_match($pattern1,$data1[0]))
        {
            $commentState=1;
            $data1[0]="";
        }
        if(preg_match($pattern2,$data1[count($data1)-1])&& $commentState==1)
        {
            $commentState=0;
            $data1[0]="";
        }
        if(preg_match($pattern2,$data1[0])&& $commentState==1)
        {
            $commentState=0;
            $data1[0]="";
        }
        if($commentState==1)
        {
            $data1[0]="";
        }
        if(strcmp($data1[0],"TestTitle")==0)
        {
            $testTitle=$data1[1];
            //print $testTitle;
            //print "title :".$testTitle."<br>";
        }
        if(strcmp($data1[0],"TestSubTitle")==0)
        {
            $testSubTitle=$data1[1];
            //print "subtitle :".$testSubTitle."<br>";
        }

        if(strcmp($data1[0],"TestTime")==0)
        {
            $endDateTime="";
            $testTime=$data1[1];
            if(strcmp($data1[2],"s")==0)
            {
                $testTime=$data1[1];
            }
            if(strcmp($data1[2],"m")==0)
            {
                $testTime=$data1[1]*60;
            }
            if(strcmp($data1[2],"h")==0)
            {
                $testTime=$data1[1]*60*60;
            }
            else
            {
                print "ERROR: testTime";
                return 0;
                //$testTime=$data1[1];
            }
            //print "test Time :".$testTime;
            //print "<br>";
        }
        if(strcmp($data1[0],"StartDateTime")==0)
        {
            //DATETIME - format: YYYY-MM-DD HH:MM:SS
            $startDateTime=$data1[1]."-".$data1[2]."-".$data1[3]." ".$data1[4].":".$data1[5].":".$data1[6];
        }
        if(strcmp($data1[0],"EndDateTime")==0)
        {
            $endDateTime=$data1[1]."-".$data1[2]."-".$data1[3]." ".$data1[4].":".$data1[5].":".$data1[6];
        }

        //Start
        $pattern="/^Test[a-zA-Z]+pe$/";
        if(preg_match($pattern,$data1[0]))
        {
            if(strcmp($data1[0],"TestType")==0)
            {
                $testType=$data1[1];
                // fix or unfix
                print "test title:".$testTitle;
                if(strcmp($testTitle,"")==0)
                {
                    print "<strong>ERROR: test title</strong>";
                    return 0;
                }
                if(strlen($testSubTitle)==0)
                {
                    print "<strong>ERROR: test Subtitle</strong>";
                    return 0;
                }
                if(strlen($startDateTime)==0)
                {
                    print "<strong>ERROR: test Start Date Time</strong>";
                    return 0;
                }
                if($testType=="Unfix")
                {
                    if(strlen($endDateTime)==0)
                    {
                        print "<strong>ERROR: test End Date Time</strong>";
                        return 0;
                    }
                }
                if($testType=="Fix")
                {
                    if($testTime==0)
                    {
                        print "<strong>ERROR: Test Time</strong>";
                        return 0;
                    }
                }
                $this->addTest($testTitle,$testSubTitle,$startDateTime,$testTime,$endDateTime,$testType);
            }
            else
            {
                print "<strong>ERROR: test Type</strong>";
                return 0;
            }

        }
        //end

        $pattern="/^Test[a-z]+s$/";
        if(preg_match($pattern,$data1[0]))
        {
        if(strcmp($data1[0],"Testees")==0)
        {
            $students++;
            if(strcmp($data1[1],"Name:")!=0)
            {
                print "<strong>ERROR: name of testee</strong>";
                return 0;
            }
            $name=$data1[2];
            if(strlen($data1[2])==0)
            {
                print "<strong>ERROR: name is empty</strong>";
                return 0;
            }
            if(strcmp($data1[3],"ID:")!=0)
            {
                print "<strong>ERROR: ID of testee</strong>";
                return 0;
            }
            $ID=$data1[4];
            if(strlen($data1[4])==0)
            {
                print "<strong>ERROR: ID is empty</strong>";
                return 0;
            }
            if(strcmp($data1[5],"PW:")!=0)
            {
                print "<strong>ERROR: password of testee</strong>";
                return 0;
            }
            $PW=$data1[6];
            if(strlen($data1[6])==0)
            {
                print "<strong>ERROR: password is empty</strong>";
                return 0;
            }
            $testID=$this->getTestID();
            //print "testee: name :".$name."     Id :".$ID."     pw :".$PW."<br>";
            //print "test ID :".$testID;
            $grade=-1;
             $this->addTestee($ID,$name,$PW,$testID,$grade);
        }
        else
        {
            print "<strong>ERROR: testee</strong>";
            return 0;
        }
        }
        $pattern="/^Q\([0-9]+\)/";
        if(preg_match($pattern,$data1[0]))
        {
          if(strcmp($data1[1],"QS")==0||strcmp($data1[1],"QW")==0)
            {
                if(strlen($data1[2])==0)
                {
                    print "<strong>ERROR: empty content</strong>";
                    return 0;
                }
                //$questions++;
                $preAnswer=0;
                $preChoice=0;
                $preScore=0;
                $preAns=array("haibong99");
                $preChoices=array("haibong99");
                $questions++;

                // content of question
                if(strlen($data1[0])==4)
                {
                    $questionID=substr($data1[0],2,1);
                    if($questionID!=$preQuestion+1)
                    {
                        print "<strong>ERROR: question ID</strong>";
                        //print "<strong>questionID=".$questionID."</strong>";
                        //print "<br><strong>prequestionID=".$preQuestion."</strong>";
                        return 0;
                    }
                }
                if(strlen($data1[0])>4)
                {
                    $questionID=substr($data1[0],2,2);
                    if($questionID!=$preQuestion+1)
                    {
                        print "<strong>ERROR: question ID</strong>";
                        //print "<strong>questionID=".$questionID."</strong>";
                        //print "<br><strong>prequestionID=".$preQuestion."</strong>";
                        return 0;
                    }
                }

                $questionType=$data1[1];
                $questionContent=$data1[2];
                $ANC="";
                $LM="";
                $TM=-1;
                $INS="";
                //print "question ID :".$questionID;
                //print "question Type :".$questionType;
                //print "question Content :".$questionContent;
                //print "<br><br>";
                // next is to save it to data1base

                $this->addQuestion($this->getTestID(),$questionID,$questionType,$questionContent,$ANC,$LM,$TM,$INS);
                $preQuestion++;
            }
            if(strcmp($data1[1],"INS")==0)
            {
                // allowed to insert comment
                $INS=$data1[2];
                $this->updateINS($INS,$this->getTestID(),$questionID);

            }
            if(strcmp($data1[2],"TRAP")==0)
            {
                if(strcmp($data1[1],"LM")!=0)
                {
                    print "<strong>ERROR: LM</strong>";
                    return 0;
                }
            }
            if(strcmp($data1[1],"LM")==0)
            {
                if(strcmp($data1[2],"TRAP")==0)
                {
                    if(strcmp($data1[4],"m")!=0 && strcmp($data1[4],"s")!=0)
                    {
                        print "<strong>ERROR: TRAP parameter</strong>";
                        return 0;
                    }
                    if(strcmp($data1[6],"m")!=0 && strcmp($data1[6],"s")!=0)
                    {
                        print "<strong>ERROR: TRAP parameter</strong>";
                        return 0;
                    }
                    if(count($data1)!=7)
                    {
                        print "<strong>ERROR: TRAP parameter</strong>";
                        return 0;
                    }
                }
                for($i=2;$i<$n-1;$i++)
                {
                    $LM=$LM.$data1[$i].",";
                }
                $LM=$LM.$data1[$n-1];
                $this->updateLM($LM,$this->getTestID(),$questionID);
                //print "<br>LM:".$LM."<br>";
            }
            if(strcmp($data1[1],"ANC")==0)
            {
                $ANC="";
                for($i=2;$i<$n-1;$i++)
                {
                    $an=substr($data1[$i],3,1);
                    $ANC=$ANC.$an.",";
                }
                $ANC=$ANC.substr($data1[$n-1],3,1);
                //print "ANC:".$ANC;
                $this->updateANC($ANC,$this->getTestID(),$questionID);
            }
            if(strcmp($data1[1],"TM")==0)
            {
                $TM=$data1[2]*60;
                //print "<br>TM:".$TM;
                $this->updateTM($TM,$this->getTestID(),$questionID);
            }
            // this is question section
            if(preg_match("/^S\([0-9]+\)/",$data1[1]))
            {
                // content of choices
                if(strlen($data1[1])==4)
                {
                    $choiceID=substr($data1[1],2,1);
                    if($choiceID!=$preChoice+1)
                    {
                        print "<strong>ERROR: choice ID</strong>" ;
                        return 0;
                    }
                }
                if(strlen($data1[1])==5)
                {
                    $choiceID=substr($data1[1],2,2);
                    if($choiceID!=$preChoice+1)
                    {
                        print "<strong>ERROR: choice ID</strong>" ;
                        return 0;
                    }
                }
                $choiceContent=$data1[2];
                $choiceType=substr($data1[1],0,1);
                $choiceParam=-1;
                //print "choice ID:".$choiceID."  content:".$choiceContent." choice Type:".$choiceType;
                $this->addChoice($this->getTestID(),$questionID,$choiceID,$choiceContent,$choiceType,$choiceParam);
                $preChoice++;
            }
            if(preg_match("/^WR\([0-9]+\)/",$data1[1]))
            {
                // content of choices
                if(strlen($data1[1])==5)
                {
                    $choiceID=substr($data1[1],3,1);
                    if($choiceID!=$preChoice+1)
                    {
                        print "<strong>ERROR: choice ID</strong>" ;
                        return 0;
                    }
                }
                if(strlen($data1[1])==6)
                {
                    $choiceID=substr($data1[1],3,2);
                    if($choiceID!=$preChoice+1)
                    {
                        print "<strong>ERROR: choice ID</strong>" ;
                        return 0;
                    }
                }
                $choiceContent="";
                $choiceType=substr($data1[1],0,2);
                $choiceParam=$data1[2];
                //print "choice ID:".$choiceID."  content(space):".$choiceContent." choice Type:".$choiceType;
                $this->addChoice($this->getTestID(),$questionID,$choiceID,$choiceContent,$choiceType,$choiceParam);
                $preChoice++;
            }
            // add choice to data1base

            if(preg_match("/^AN\([0-9]+\)/",$data1[1]))
            {
                // content of answer
                for($i=0;$i<count($preChoices);$i++)
                {
                    if(strcmp($preChoices[$i],$data1[3])==0)
                    {
                        print "<strong>ERROR:choice concise</strong>";
                        return 0;
                    }
                }
                array_push($preChoices,$data1[3]);
                if(strlen($data1[1])==5)
                {
                    $answerID=substr($data1[1],3,1);
                    if($answerID!=$preAnswer+1)
                    {
                        print "<strong>ERROR: answer ID</strong>";
                        return 0;
                    }
                }
                if(strlen($data1[1])==6)
                {
                    $answerID=substr($data1[1],3,2);
                    if($answerID!=$preAnswer+1)
                    {
                        print "<strong>ERROR: answer ID</strong>";
                        return 0;
                    }
                }
                $answerType=$data1[2];
                $this->addAnswer($this->getTestID(),$questionID,$answerID,$answerType);
                $preAnswer++;
                for($i=3;$i<$n;$i++)
                {
                    $answerContent[$i-3]=$data1[$i];
                    $this->addAnswerContent($this->getTestID(),$questionID,$answerID,$data1[$i]);
                }// end for
                //print "<br> answer ID :".$answerID."  answer type :".$answerType;
                for($i=3;$i<$n;$i++)
                {
                    //print $answerContent[$i-3]."  ";
                }// end for
                // then, save it to data1base
            }
            if(preg_match("/^SC\([0-9]+\)/",$data1[1]))
            {
                for($i=0;$i<count($preAns);$i++)
                {
                    if(strcmp($preAns[$i],$data1[2])==0)
                    {
                        print "<strong>ERROR:choice concise</strong>";
                        return 0;
                    }
                }
                array_push($preAns,$data1[2]);
                if(strcmp($data1[2],"VINP")==0)
                {
                 $scoreID=substr($data1[1],3,1);
                 $score=-1;
                 $answerID=-1;
                    if($scoreID!=$preScore+1)
                    {
                        print "<strong>ERROR: score ID</strong>";
                        return 0;
                    }
                 $this->addScore($this->getTestID(),$questionID,$answerID,$score,$scoreID);
                 $preScore++;

                }
                else{
                // content of score
                if(strlen($data1[1])==5)
                {
                    $scoreID=substr($data1[1],3,1);
                    if($scoreID!=$preScore+1)
                    {
                        print "<strong>ERROR: score ID</strong>";
                        return 0;
                    }
                }
                if(strlen($data1[1])==6)
                {
                  $scoreID=substr($data1[1],3,2);
                    if($scoreID!=$preScore+1)
                    {
                        print "<strong>ERROR: score ID</strong>";
                        return 0;
                    }
                }
                if(strlen($data1[2])==5)
                {
                $answerID=substr($data1[2],3,1);
                }
                 if(strlen($data1[2])==6)
                 {
                 $answerID=substr($data1[2],3,2);
                 }
                $score=$data1[3];
                #//print "<br>score ID :".$scoreID." answer: ".$answerID." with grade :".$grade."<br>";
                $this->addScore($this->getTestID(),$questionID,$answerID,$score,$scoreID);
                    $preScore++;
                // then save it to data1base
                }
            }


        }


        #end
    }// end while
    // add information of the current test
    // finish test add
    fclose($handle1);
        return 1;
}    

public function upcsv()
    {
        if($this->request->is('post'))
        {

    $allowedExts = array("csv");
    
    if (($_FILES["file"]["type"] == "text/csv") && ($_FILES["file"]["size"] < 20000))
    {
    if ($_FILES["file"]["error"] > 0)
        {
        echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
        }
    else
        {
        echo "Upload: " . $_FILES["file"]["name"] . "<br>";
        echo "Type: " . $_FILES["file"]["type"] . "<br>";
        echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
        echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";
        $folder="../upload";
        
            $t=time();
            $this->Session->setFlash(__(AuthComponent::user('username').' file '.$_FILES["file"]["name"]." store as name :".$t.$_FILES["file"]["name"]));
    
            move_uploaded_file($_FILES["file"]["tmp_name"],
                $folder."/" .$t.$_FILES["file"]["name"]);
            echo "Stored in: " .$t. $folder."/" . $_FILES["file"]["name"];
        
            $this->processCSVFile($folder."/".$t.$_FILES["file"]["name"]);

            $this->loadModel("User");
            $this->User->query("update tests set username= '".AuthComponent::user('username')."',groupID= '".AuthComponent::user('groupID')."'where testID=".$this->getTestID().";");
     
        
        }
    }
    else
    {
        echo "Invalid file";
    }
    
    }
}


}