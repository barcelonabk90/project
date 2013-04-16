<?php
// app/Controller/UsersController.php


class AdminController extends AppController {

    public function beforeFilter() {
        parent::beforeFilter();
        //$this->Auth->allow('add');
        if (AuthComponent::user('userType')!=1)
        {
            $this->Session->setFlash(__('Access deny'));
            $this->redirect(array('controller' => 'users', 'action' => 'index'));
        }
    }


    public function index(){
    if (AuthComponent::user('userType')!=1)
        {
            $this->Session->setFlash(__('Access deny'));
            $this->redirect(array('controller' => 'users', 'action' => 'index'));
        }
    }

    public function addgroup()
    {
    	if (AuthComponent::user('userType')!=1)
        {
            $this->Session->setFlash(__('Access deny'));
            $this->redirect(array('controller' => 'users', 'action' => 'index'));
        }
        $this->loadModel('Region');
        $this->set('Regions',$this->Region->find('all'));
        if ($this->request->is('post')) {
            //print_r ($this->request->data);
            $this->loadModel("Group");
            $count = $this->Group->find('count', array('conditions' => array('groupID'=>$this->request->data['Group']['groupID'])));
            if($count>=1)
                {
                    $this->Session->setFlash(__('The group account is already exist. Please, try again.'));
                }
            else
                {
            $this->Group->create();
            if ($this->Group->save($this->request->data)) {
                $this->Session->setFlash(__('The group has been added'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The user could not be saved. Please, try again.'));
            }
                }
        }
        

    }

    public function addregion()
    {
        if (AuthComponent::user('userType')!=1)
        {
            $this->Session->setFlash(__('Access deny'));
            $this->redirect(array('controller' => 'users', 'action' => 'index'));
        }

        if ($this->request->is('post')) {
            //print_r ($this->request->data);
            $this->loadModel("Region");
            $count = $this->Region->find('count', array('conditions' => array('regionName'=>$this->request->data['Region']['regionName'])));
            if($count>=1)
                {
                    $this->Session->setFlash(__('The region data is already exist. Please, try again.'));
                }
            else
                {
            $this->Region->create();
            if ($this->Region->save($this->request->data)) {
                $this->Session->setFlash(__('The region has been added'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The user could not be saved. Please, try again.'));
            }
                }
        }
        
    }


    public function groupmanager()
    {
    	if (AuthComponent::user('userType')!=1)
        {
            $this->Session->setFlash(__('Access deny'));
            $this->redirect(array('controller' => 'users', 'action' => 'index'));
        }
            $this->loadModel("Group");
            $this->set('Groups',$this->Group->find('all'));        
 
    }

    public function deletegroup($id=null)
    {
    	if (AuthComponent::user('userType')!=1)
        {
            $this->Session->setFlash(__('Access deny'));
            $this->redirect(array('controller' => 'users', 'action' => 'index'));
        }
        $this->loadModel("Group");
            $this->Group->query("DELETE FROM groups WHERE groupID='".$id."';");
            $this->Session->setFlash(__('group have been deleted!!!'));
            $this->redirect(array('controller' => 'admin', 'action' => 'groupmanager'));
    }



//*****************************************

function connect()
{
$con=mysqli_connect("localhost","root","","itjp");
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
        mysqli_character_set_name($con);
        return $con;
    }

}
}

function getMonth($testID)
{
    $con=$this->connect();
    $result = mysqli_query($con,"SELECT EXTRACT(MONTH FROM startDateTime) as month FROM tests WHERE testID='$testID'");
    $row = mysqli_fetch_array($result);
   return $row['month'];
}
function numberOfTestees($testID)
{
    $conn=$this->connect();
    $result=mysqli_query($conn,"SELECT count(distinct testeeID) as number FROM testinfo WHERE testID='$testID'");
    $row=mysqli_fetch_array($result);
    return $row['number'];
}
function listGroup($regionID)
{
// the purpose of this function is listing all University of Organization in specific region
    $groupIDs=array();
    $conn=$this->connect();
    $result=mysqli_query($conn,"SELECT * FROM groups WHERE regionID='$regionID'");
    while($row=mysqli_fetch_array($result))
    {
        array_push($groupIDs,$row['groupID']);
    }
    return $groupIDs;
}
function getAdmin($regionID)
{
    $conn=$this->connect();
    $result=mysqli_query($conn,"SELECT * FROM users WHERE regionID='$regionID' and userType=1");
    $row=mysqli_fetch_array($result);
    return $row;
}
function getManager($groupID)
{
    $conn=$this->connect();
    $result=mysqli_query($conn,"SELECT * FROM users WHERE groupID='$groupID' and userType=2");
    $row=mysqli_fetch_array($result);
    return $row;
}
#$info=getManager('BK');
#print $info['address']."<br>";
#print $info['phone'];
function listTest($groupID)
{
    $testIDs=array();
    $conn=$this->connect();
    $time=getdate();
    $month=$time['mon']-1;
    $result=mysqli_query($conn,"SELECT * FROM tests WHERE groupID='$groupID'");
    while($row=mysqli_fetch_array($result))
    {
        if($this->getMonth($row['testID'])==$month)
        array_push($testIDs,$row['testID']);
    }
    return $testIDs;
}
#print_r(listTest('BK'));
function writecsv()
{

    $k=10000;
    
    $today=getdate();
    $year=$today['year'];
    $mon=$today['mon']-1;
    $filename=AuthComponent::user('username').$year.$mon.".csv";
 
    //$fp=fopen($filename,'a') or die("cant open");
    $line1[0]="TAS-T11";
    $line1[1]=$today['year'];
    $line1[2]=$today['mon']-1;
    $line1[3]=$today['year'];
    $line1[4]=$today['mon'];
    $line1[5]=$today['mday'];
    $line1[6]=$today['hours'];
    $line1[7]=$today['minutes'];
    $line1[8]=$today['seconds'];
    $line1[9]=AuthComponent::user('username');
    $line1[10]=AuthComponent::user('name');
    //fputcsv($fp,$line1);
    print_r($line1);
    $groupIDs=$this->listGroup(AuthComponent::user('regionID'));
    foreach($groupIDs as $groupID )
    {
        //each group
        $money=0;
        #print "group:".$groupID."<br>";
        $testIDs=$this->listTest($groupID);
        foreach($testIDs as $testID)
        {
            //each testID
           # print "   testID:".$testID;
            $money=$money+$this->numberOfTestees($testID)*$k;
        }
        // now I have got amount of money that each group or organization have to pay
        print "Group ".$groupID."have to pay ".$money."<br>";
        if($money>0)
        {
            // write info into CSV file
            $info=$this->getManager($groupID);
            $line2=array();
            $line2[0]=$info['username'];
            $line2[1]=$info['name'];
            $line2[2]=$money;
            $line2[3]=$info['address'];
            $line2[4]=$info['phone'];
            //fputcsv($fp,$line2);
            print_r($line2);

        }


    }
    $line3=array();
    $line3[0]="END__END__END";
    $line3[1]=$today['year'];
    $line3[2]=$today['mon']-1;
    //fputcsv($fp,$line3);
    print_r($line3);
}

}