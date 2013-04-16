<?php
App::import('Core', 'ConnectionManager');

class TestsController extends AppController{
    var $helpers = array('Paginator','Html','Form');
    var $components = array('Session');
    var $paginate = array();

        public function beforeFilter() {
        parent::beforeFilter();   
        {$this->Auth->allow('unfixtest','listall','login','process','score','fixtest','paging','fixprocess','waiting',
                'notice','checkTestType');}
        $this->loadModel("Test");
    }

    
    public function index(){
        $this->redirect(array('controller'=>'teachers','action'=>'index'));
    }

    public function unfixtest($id)
    {
   
        if($this->Session->read('testID')!=$id)
            {
                //echo 'you can not access this test';
                $this->Session->setFlash(__('you can not access this test, Please, try login again'));
                //$this->redirect(array('controller' => 'admin', 'action' => 'index'));
                $this->Session->delete('testID');
                $this->Session->delete('testeeID');
                $this->Session->delete('testType');  
                $this->redirect(array('controller' => 'tests', 'action' => 'login',$id));
            }
        else{
        
        if($this->Session->read('testType')=='fix')
            {
            $this->redirect(array('controller' => 'tests', 'action' => 'fixtest',$id));                
            }
        $this->loadModel("Question");
        $this->loadModel("Choice");
        $this->set("tests",$this->Test->find('first',array('conditions'=>array('Test.testID'=>$id))));
        $this->set("questions",$this->Question->find('all',array('conditions'=>array('Question.testID'=>$id))));
        $this->set("choices",$this->Choice->find('all',array('conditions'=>array('Choice.testID'=>$id))));
        //new
        $this->loadModel("Group");
        $groupID=$this->Test->query('SELECT tests.groupID FROM tests WHERE tests.testID='.$id);
        //print_r($groupID['0']['tests']['groupID']);
        $GrID=$groupID['0']['tests']['groupID'];
        $groupName=$this->Group->query("SELECT groups.groupName FROM groups WHERE groups.groupID = '$GrID'");
        //print_r($groupName);
        $this->set('groupName',$groupName['0']['groups']['groupName']);
        $testeeID= $this->Session->read('testeeID');
        $this->set('testeeID',$testeeID);
        }
    }

     

    //------- Paging Normal
    function fixtest($id){

        if($this->Session->read('testID')!=$id)
            {
                //echo 'you can not access this test';
                $this->Session->setFlash(__('you can not access this test, Please, try login again'));
                //$this->redirect(array('controller' => 'admin', 'action' => 'index'));
                $this->Session->delete('testID');
                $this->Session->delete('testeeID');
                $this->Session->delete('testType');  
                $this->redirect(array('controller' => 'tests', 'action' => 'login',$id));
            }
        else{
        
        if($this->Session->read('testType')=='unfix')
            {
            $this->redirect(array('controller' => 'tests', 'action' => 'unfixtest',$id));                
            }

        $this->loadModel("Question");
        $this->loadModel("Choice");
        $this->set("tests",$this->Test->find('first',array('conditions'=>array('Test.testID'=>$id))));
        $this->set("questions",$this->Question->find('all',array('conditions'=>array('Question.testID'=>$id))));
        $this->set("choices",$this->Choice->find('all',array('conditions'=>array('Choice.testID'=>$id))));
        //new
        $this->loadModel("Group");
        $groupID=$this->Test->query('SELECT tests.groupID FROM tests WHERE tests.testID='.$id);
        //print_r($groupID['0']['tests']['groupID']);
        $GrID=$groupID['0']['tests']['groupID'];
        $groupName=$this->Group->query("SELECT groups.groupName FROM groups WHERE groups.groupID = '$GrID'");
        //print_r($groupName);
        $this->set('groupName',$groupName['0']['groups']['groupName']);
        $testeeID= $this->Session->read('testeeID');
        $this->set('testeeID',$testeeID);
        }
    }      

    public function process(){
          print_r($_POST);
      
 
        $this->loadModel("Testee");
        header('Content-Type: text/html; charset=UTF-8');
        iconv_set_encoding("internal_encoding", "UTF-8");
        iconv_set_encoding("output_encoding", "UTF-8");
        setlocale(LC_ALL, 'de_DE.utf8');

        $conn = $this->connect();
        
        //mysql_query("SET character SET 'utf-8'",$conn);
        //mysql_query("SET NAMES SET 'utf8'",$conn);    
       if ($this->request->is('post')){
           
       $testID=$_POST['testID'];
       $questions=$_POST['test'];
       //$testID=$_SESSION['user'];
       $testeeID= $this->Session->read('testeeID');

        $count = $this->Testee->find('count', array('conditions' => array('testeeID'=>$testeeID ,'testID'=> $testID,'grade'=>'-1')));

       if($count!=0)
       {
       $type=$_POST['type'];
       // Get Answer
       foreach ($questions as $questionID=>$question){
           $answer="";
           //$int i=0;
           foreach ($question as $choice){
            if($choice!="")
               {
                if (is_numeric($choice))
                {$answer = $answer."S(".$choice."),";}
                else 
                {$answer=$answer.$choice.",";}    
                }
           }
           echo $answer;
           
        $sql = "INSERT INTO testinfo(testID,testeeID, questionID, answer,type) VALUES ( $testID, '$testeeID', $questionID ,'".$answer."','$type')";

        echo $sql;
        
        $retval = mysqli_query($conn,$sql);
        if(! $retval )
        {
        die('Could not enter data: ' . mysqli_error());
        }
         echo "Entered data successfully\n";
       }
        mysqli_close($conn);

        $this->calculateGrade();
        $qr=$this->Testee->query("select sum(grade) as score from testinfo where testeeID='$testeeID' and testID='$testID';");
        $i=(int) $qr[0][0]['score'];
        $this->Testee->query("update testees set grade='$i' where testeeID='$testeeID' and testID='$testID';");
        $this->Session->setFlash(__('Test success!!!.'));
        }
        else
        {
            $this->Session->setFlash(__('Can not access this test!!!.'));
        }
        
        }
    }
    
    public function fixprocess(){
          echo '<pre>';
          print_r($this->request->data);
          print_r($_POST['Time']);
          echo '</pre>';
        
        header('Content-Type: text/html; charset=UTF-8');
        iconv_set_encoding("internal_encoding", "UTF-8");
        iconv_set_encoding("output_encoding", "UTF-8");
        setlocale(LC_ALL, 'de_DE.utf8');

        $conn = $this->connect();
        $this->loadModel('Testee');

       if ($this->request->is('post')){
           
       $testID=$_POST['testID'];
       $questions=$_POST['test'];

        $testeeID= $this->Session->read('testeeID');
   
       $type=$_POST['type'];
       
       // Get Answer
       $i=1;
       foreach ($questions as $questionID=>$question){
           $answer="";
           $testTime="";
           //$int i=0;
           foreach ($question as $choice){
            if($choice!="")
               {
                if (is_numeric($choice))
                {$answer = $answer."S(".$choice."),";}
                else 
                {$answer=$answer.$choice.",";}    
                }
           }
           echo $answer;
           $testTime=$_POST['Time'][$i];
           $i++;
        $sql = "INSERT INTO testinfo(testID,testeeID, questionID,time, answer,type) VALUES ( $testID, '$testeeID', $questionID ,$testTime,'$answer','$type')";

        echo $sql;
        
        $retval = mysqli_query($conn,$sql);
        if(! $retval )
        {
        die('Could not enter data: ' . mysqli_error());
        }
         echo "Entered data successfully\n";
       }
        mysqli_close($conn);

        $this->calculateGrade();
        $qr=$this->Testee->query("select sum(grade) as score from testinfo where testeeID='$testeeID' and testID='$testID';");
        $i=(int) $qr[0][0]['score'];
        $this->Testee->query("update testees set grade='$i' where testeeID='$testeeID' and testID='$testID';");
        $this->Session->setFlash(__('Test success!!!.'));

   //     $this->calculateGrade();
    }
    
    
    }

    
    public function listall()
    {
        $this->loadModel("Test");
        $this->set('Tests',$this->Test->find('all'));
    }
    
    public function checkTestType($id=null){
        $this->set('testID',$id);
        if($this->is_fix($_POST['testID'] ))
            {   
                $this->Session->write('testType','fix');
                if($this->is_validTestTime($id,'fix')==0){
                    $this->redirect(array('controller' => 'tests', 'action' => 'fixtest',$_POST['testID'] ));                
                    }
                else if($this->is_validTestTime($id,'fix')==-1){
                    $this->redirect(array('action'=>'waiting'));
                    }
                else if($this->is_validTestTime($id,'fix')==1){
                    $this->redirect(array('action'=>'notice'));
                }
            }
            else
            {   $this->Session->write('testType','unfix');
                if($this->is_validTestTime($id,'unfix')==0){
                    $this->redirect(array('controller' => 'tests', 'action' => 'unfixtest',$_POST['testID'] ));
                    }
                else if($this->is_validTestTime($id,'unfix')==-1){
                    $this->redirect(array('action'=>'waiting'));
                    }
                else if($this->is_validTestTime($id,'unfix')==1){
                    $this->redirect(array('action'=>'notice'));
                    }
            }
    }

    public function login($id=null)
    {
        $this->set('testID',$id);
        $this->loadModel("Testee");
        
        if ($this->request->is('post')) {
        
            //print_r($_POST);
      
        $count = $this->Testee->find('count', array('conditions' => array('testeeID'=>$_POST['data']['Testee']['username'] ,'password'=>$_POST['data']['Testee']['password'],'testID'=>$_POST['testID'])));
        
        if ($count>0) {
            $qr = $this->Testee->find('all', array('conditions' => array('testeeID'=>$_POST['data']['Testee']['username'] ,'password'=>$_POST['data']['Testee']['password'],'testID'=>$_POST['testID'])));

            $this->Session->write('testeeID', $qr[0]['Testee']['testeeID']);
            $this->Session->write('testID', $qr[0]['Testee']['testID']);
            
            $this->checkTestType($id);
            
        } else {

            $this->Session->setFlash(__('Invalid username, password or testID, try again'));
        }
        

        }
    }
    
    function waiting(){
        $this->Session->setFlash(__('Chua den gio lam dau, ngoi ma doi di con !!!'));
    }
    
    function notice(){
        $this->Session->setFlash(__('Qua me thoi gian lam bai roi !!!'));
    }

    
    function is_fix($testID)
    {

    $count = $this->Test->find('count', array('conditions' => array('testType'=>'Fix' ,'testID'=>$testID)));
    if($count>=1) return true;
    return false; 
    }

    //Function check test time is valid 
    function is_validTestTime($testID,$testType){
        $this->loadModel('Test');
        $test=$this->Test->query('select * from tests where tests.testID='.$testID);
                
        $startDateTime=  strtotime($test['0']['tests']['startDateTime']);
        $endDateTime=  strtotime($test['0']['tests']['endDateTime']);
        $timeNow= time()+3600*7;
        $testTime=$test['0']['tests']['testTime'];
        if($testType=='fix'){
            if(($timeNow >$startDateTime)&($timeNow<($startDateTime+$testTime))) return 0;
            else if($timeNow<$startDateTime) return -1;
            else if($timeNow>$endDateTime) return 1;
        }
        else{
           
            if(($startDateTime<$timeNow) &&($timeNow < $endDateTime))
        { 
            return 0;
        }
            else if($timeNow<$startDateTime) return -1;
            else if($timeNow>$endDateTime)
        {
            return 1;
        }
        
        
        }
    }




    /***************************************************Hai ******************************************************************/


            

    function connect()
    {   
        $dataSource = ConnectionManager::getDataSource('default');
            
        $dbhost = 'localhost';
        $dbuser = $dataSource->config['login'];
        $dbpass = $dataSource->config['password'];
        $dbdatabase=$dataSource->config['database'];

        $con = mysqli_connect($dbhost, $dbuser, $dbpass,$dbdatabase);
    
    //$con = mysqli_connect("localhost", "root", "123456","itjp");
// Check connection
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    else
        {
    if (!mysqli_set_charset($con,"utf8")) {
        printf("Error loading character set utf8: %s\n", mysqli_error($con));
    }
    else {
        printf("Current character set: %s\n", mysqli_character_set_name($con));
        return $con;
    }

    }
    }




function beContained($answer,$answers)
{
    for($i=0;$i<count($answers);$i++)
    {
        if($answer==$answers[$i])
            return 1;

    }
    return 0;
}
function isFix($testID)
{
    $con=$this->connect();
    $result = mysqli_query($con,"SELECT testType FROM tests WHERE testID='$testID'");
    $row=mysqli_fetch_array($result);
    $answer=$row['testType'];
    
    mysqli_close($con);

    if($answer=="Fix")
        return 1;
    return 0;
}
function match($answers,$correctAnswers,$answerType)
{
    if($answerType=="KS"||$answerType=="KWP"||$answerType=="KW")
    {
        if($answers[0]==$correctAnswers[0])
            return 1;
        return 0;
    }
    if($answerType=="KSO"||$answerType=="KWPO"||$answerType=="KWO")
    {
        for($i=0;$i<count($correctAnswers);$i++)
        {
            if($answers[0]==$correctAnswers[$i])
                return 1;
        }
        return 0;
    }
    if($answerType=="KSA"||$answerType=="KWPA"||$answerType=="KWA")
    {
        if(count($answers)!=count($correctAnswers))
            return 0;
        for($i=0;$i<count($answers);$i++)
        {
            if(strcmp($answers[$i],$correctAnswers[$i])!=0)
                return 0;
        }
        return 1;
    }
}
function matchANC($answers,$correctAnswers,$answerType)
{

    if($answerType=="KS"||$answerType=="KWP"||$answerType=="KW")
    {
        if($answers[0]==$correctAnswers[0])
            return 1;
        return 0;
    }
    if($answerType=="KSO"||$answerType=="KWPO"||$answerType=="KWO")
    {
        for($i=0;$i<count($correctAnswers);$i++)
        {
            if($answers[0]==$correctAnswers[$i])
                return 1;
        }
        return 0;
    }
    if($answerType=="KWPA")
    {
    for($i=0;$i<count($answers);$i++)
    {
        if(beContained($answers[$i],$correctAnswers)==0)
            return 0;
    }
    for($i=0;$i<count($correctAnswers);$i++)
    {
        if(beContained($correctAnswers[$i],$answers)==0)
            return 0;
    }
    if(count($answers)!=count($correctAnswers))
        return 0;
    return 1;
    }
}
function getAnswer($testID,$questionID,$answerID)
{
    //to compare
    $con=$this->connect();
    $results=array();
    //echo ("SELECT answerContent FROM answerContents WHERE testID='$testID' and questionID='$questionID' and answerID='$answerID'");
    //echo ("SELECT answerContent FROM answerContents WHERE testID='".$testID."' and questionID='".$questionID."' and answerID='".$answerID."';");
    $result = mysqli_query($con,"SELECT answerContent FROM answercontents WHERE testID='".$testID."' and questionID='".$questionID."' and answerID='".$answerID."';");
    while($row=mysqli_fetch_array($result))
    {
        array_push($results,$row['answerContent']);
    }
    mysqli_close($con);

    return $results;
}
function getScore($testID,$questionID,$answerID)
{
    $con=$this->connect();
    $result = mysqli_query($con,"SELECT score FROM scores WHERE testID='$testID' and questionID='$questionID' and answerID='$answerID'");
    $row=mysqli_fetch_array($result);
     $score=$row['score'];
    
    mysqli_close($con);
    return $score;
}
function getTestType($testID)
{
    $con=$this->connect();
    $result = mysqli_query($con,"SELECT testType FROM tests WHERE testID='$testID'");
    $row=mysqli_fetch_array($result);
    $testType=$row['testType'];
    mysqli_close($con);
    return $testType;
}
function getANC($testID,$questionID)
{
    $con=$this->connect();
    $result = mysqli_query($con,"SELECT ANC FROM questions WHERE testID='$testID' and questionID='$questionID'");
    $row=mysqli_fetch_array($result);
    $string=$row['ANC'];
    if(strlen($string)==0)
        return null;
    $data=str_getcsv($string,",");
    $answers=array();
    for($i=0;$i<count($data);$i++)
    {
        if($data[$i]!="")
        {
            array_push($answers,$data[$i]);
        }
    }

    mysqli_close($con);

    return $answers;
}
function getGradeUnfix($testID,$questionID,$answers,$startTime,$type,$time)
{
    $con=$this->connect();
    $maxScore=0;
    //print "<br>"."Answer:";
    //print_r($answers);
    $ANC=$this->getANC($testID,$questionID);
    $result = mysqli_query($con,"SELECT answerID,answerType FROM answers WHERE testID='$testID' and questionID='$questionID'");
  while($row=mysqli_fetch_array($result))
  {
        // now I have row['answerID'] and row['answerType']
      $answerID=$row['answerID'];
      $answerType=$row['answerType'];
      
      $correctAnswers=$this->getAnswer($testID,$questionID,$answerID);
      
      if($ANC!=null)
      {
          //print $answerType;
        if($this->matchANC($answers,$correctAnswers,$answerType)==1)
         {
      
          $score=$this->getScore($testID,$questionID,$answerID);
          //print $score."<br>";
             if($score>$maxScore)
             {
                  $maxScore=$score;
              }
         }
          else {//print "1.not match";
      }
      }
      else
          if($this->match($answers,$correctAnswers,$answerType)==1)
      {
      
          $score=$this->getScore($testID,$questionID,$answerID);
          //print $score."<br>";
          if($score>$maxScore)
          {
              $maxScore=$score;
          }
      }
      else {
  }
  }

  mysqli_close($con);

    return $maxScore;
}
function getGradeFix($testID,$questionID,$answers,$startTime,$type,$time)
{
    $con=$this->connect();
    $maxScore=0;
    //print "<br>"."Answer:";
    //print_r($answers);
    $ANC=$this->getANC($testID,$questionID);
    $result = mysqli_query($con,"SELECT answerID,answerType FROM answers WHERE testID='$testID' and questionID='$questionID'");
    while($row=mysqli_fetch_array($result))
    {
        // now I have row['answerID'] and row['answerType']
        $answerID=$row['answerID'];
        $answerType=$row['answerType'];
        $correctAnswers=$this->getAnswer($testID,$questionID,$answerID);
        
        if($ANC!=null)
        {
            //print $answerType;
            if($this->matchANC($answers,$correctAnswers,$answerType)==1)
            {
        
                $score=$this->getScore($testID,$questionID,$answerID);
                //print $score."<br>";
                if($score>$maxScore)
                {
                    $maxScore=$score;
                }
            }
            else {//print "not match";
            }   
        }
        else
        {
            if(match($answers,$correctAnswers,$answerType)==1)
            {
        
                $score=$this->getScore($testID,$questionID,$answerID);
                //print $score."<br>";
                if($score>$maxScore)
                {
                    $maxScore=$score;
                }
            }
            else {
        }
        }
    }
    $score=$this->calculateScore($time,$maxScore,$this->getLM($testID,$questionID));
  
    mysqli_close($con);

    return $score;
}
function getLM($testID,$questionID)
{
    $con=$this->connect();
    $result = mysqli_query($con,"SELECT LM FROM questions WHERE testID='$testID' and questionID='$questionID'");
    $row=mysqli_fetch_array($result);
    $LM=$row['LM'];
    //print "<br>LM=".$LM;
    mysqli_close($con);
    return $LM;
}
function calculateScore($time,$maxScore,$LM)
{
    $score=$maxScore;
    $params=str_getcsv($LM,",");
    if($params[0]=="TRI")
    {
        $maxTime=$params[1]*60;
        //print "max time:".$maxTime;
        if($time>$maxTime)
            return 0;
        $score=$maxScore*$time/$maxTime;
    }
    if($params[0]=="REC")
    {
        //print "rec type";
        $maxTime=$params[1];
        if($params[2]=="m"){
            $maxTime=$params[1]*60;
        }
        if($time>$maxTime)
            $score=0;
        else $score=$maxScore;
    }
    if($params[0]=="TRAP")
    {
        //print "TRAP type";
        $a=$params[1];
        if($params[2]=="m")
        {
            $a=$params[1]*60;
        }
        //print "a=".$a;
        $b=$params[3];
        if($params[4]=="m")
        {
            $b=$params[3]*60;
        }
        //print "<br>b=".$b;
        if($time<=$a)
        {
            $score=$maxScore;
        }
        if($time>$a and $time<=$a+$b)
        {
            $score=$maxScore*($a+$b-$time)/$b;
        }
        if($time>$a+$b)
            $score=0;
    }
    return $score;
}
 function updateGrade($grade,$testeeID,$testID,$questionID)
    {
        $con=$this->connect();
        $sql="UPDATE testinfo Set grade='$grade' where testeeID='$testeeID' and testID='$testID' and questionID='$questionID' ";
        if (!mysqli_query($con,$sql))
        {
            die('Error: ' . mysqli_error());
        }
        //echo "1 record updated";
        mysqli_close($con);
    }
function getAnswerFromTestInfo($testeeID,$testID,$questionID)
{
    $con=$this->connect();
    $result = mysqli_query($con,"SELECT answer FROM testinfo WHERE testeeID='$testeeID' and testID='$testID' and questionID='$questionID'");
    $row=mysqli_fetch_array($result);
    $answer=$row['answer'];
    mysqli_close($con);
    return $answer;
}
function calculateGradeByHand($testeeID,$testID,$questionID)
{
    $answer=$this->getAnswerFromTestInfo($testeeID,$testID,$questionID);
  
}
#calculateGradeByHand(12,111,1);
public function calculateGrade()
{
    $con=$this->connect();
    $result = mysqli_query($con,"SELECT * FROM testinfo");
   while($row=mysqli_fetch_array($result))
   {
    $testID=$row['testID'];
    $testeeID=$row['testeeID'];
    $type=$row['type'];
    $time=$row['time'];
    $questionID=$row['questionID'];
    $startTime=$row['startTime'];
    $answers=str_getcsv($row['answer'],",");
         //question of choice type
         if($this->isFix($testID))
         {
             $grade=$this->getGradeFix($testID,$questionID,$answers,$startTime,$type,$time);
             $this->updateGrade($grade,$testeeID,$testID,$questionID);
         }
         else{
             $grade=$this->getGradeUnfix($testID,$questionID,$answers,$startTime,$type,$time);
             $this->updateGrade($grade,$testeeID,$testID,$questionID);
         }

   }

   mysqli_close($con);
}

  public function score ()
    {

        
        
    }

}