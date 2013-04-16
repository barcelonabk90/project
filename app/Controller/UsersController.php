<?php
// app/Controller/UsersController.php

App::uses('DigestAuthenticate', 'Controller/Component/Auth/');

class UsersController extends AppController {

    public function beforeFilter() {
        parent::beforeFilter();   
        {$this->Auth->allow('index');}
    }

    public function account()
    {           

        if (AuthComponent::user('userType')!=1)
        {
            $this->Session->setFlash(__('Access deny'));
            $this->redirect(array('controller' => 'users', 'action' => 'index'));
        }
        
        $this->set('Users',$this->User->find('all'));

    }   
public function login() {

    $this->loadModel("Group");
    $this->set('Groups',$this->Group->find('all'));
    
    if ($this->request->is('post')) {
        
        $this->request->data['User']['password']=$this->request->data['User']['username'].$this->request->data['User']['groupID'].$this->request->data['User']['password'];
        
        if ($this->Auth->login()) {    

            $this->Session->write('username',$this->request->data['User']['username']);
            $this->Session->write('groupID',$this->request->data['User']['groupID']);
            if (AuthComponent::user('userType')==1)
                $this->redirect(array('controller' => 'admin', 'action' => 'index'));  
            if (AuthComponent::user('userType')==2)   
                $this->redirect(array('controller' => 'groups', 'action' => 'index'));
            if (AuthComponent::user('userType')==3)   
                $this->redirect(array('controller' => 'teachers', 'action' => 'index'));
        } else {
            //print_r($this->Auth);
            $this->Session->setFlash(__('Invalid username or password, try again'));
            $this->request->data['User']['password']=$_POST['data']['User']['password'];
        } 
    }
   
}

public function logout() {
    $this->redirect($this->Auth->logout());
}

    public function index() {
 
    }

    public function view() {
        if (AuthComponent::user('username')) {
        $this->set('user', $this->User->findByUsername(AuthComponent::user('username')));
            
        }
        else
            throw new NotFoundException(__('Invalid user'));
        
    }

    public function add() {
        $this->loadModel("Group");
        $this->set('Groups',$this->Group->find('all'));
        $this->loadModel("Region");
        $this->set('Regions',$this->Region->find('all'));
        //$this->set('')
        
        if (AuthComponent::user('userType')!=1)
        {
            $this->Session->setFlash(__('Access deny'));
            $this->redirect(array('controller' => 'users', 'action' => 'index'));
        }

        //$this->data['SomeModel']['your_datetime_field'] = DboSource::expression('NOW()');
        if ($this->request->is('post')) {
            $count = $this->User->find('count', array('conditions' => array('username'=>$this->request->data['User']['username'],'groupID'=>$this->request->data['User']['groupID'])));
            if($count>=1)
                {
                    $this->Session->setFlash(__('The user account is already exist. Please, try again.'));
                }
            else
                {
            
            //$this->data['SomeModel']['your_datetime_field'] = DboSource::expression('NOW()');
        //    $this->data['User']['createdTime']='CURRENT_DATE';
            $this->User->create();

            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('The user has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The user could not be saved. Please, try again.'));
            }
            
            }
        }

        
    }

    public function addteacher() {
        $this->loadModel("Group");
        $this->set('Groups',$this->Group->find('all'));
        
        if (AuthComponent::user('userType')!=2)
        {
            $this->Session->setFlash(__('Access deny'));
            $this->redirect(array('controller' => 'users', 'action' => 'index'));
        }

        $this->request->data['User']['userType']='3';
      //  $this->request->data['User']['createdTime']='2013-04-05';
        $this->request->data['User']['groupID']=AuthComponent::user('groupID');

            if ($this->request->is('post')) {

                $count = $this->User->find('count', array('conditions' => array('username'=>$this->request->data['User']['username'],'groupID'=>$this->request->data['User']['groupID'])));
                if($count>=1)
                {
                    $this->Session->setFlash(__('The user account is already exist. Please, try again.'));
                }
                else
                {
                    //$this->data['User']['createdTime']='CURRENT_DATE';
                $this->User->create();
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('The user has been saved'));
                $this->redirect(array('action' => 'index'));
                } else {
                $this->Session->setFlash(__('The user could not be saved. Please, try again.'));
               }
            }
        }
    }

    public function edit() {

        if ($this->request->is('post') || $this->request->is('put')) {

            $count = $this->User->find('count', array('conditions' => array('username'=>AuthComponent::user('username') ,'password'=> AuthComponent::password(AuthComponent::user('username').AuthComponent::user('groupID').$this->request->data['User']['password']))));

            
            if ($count>0)
            {
                if ($this->request->data['User']['newpass']==$this->request->data['User']['renewpass'])
                {       
                $this->User->query("UPDATE users SET password='".AuthComponent::password(AuthComponent::user('username').AuthComponent::user('groupID').$this->request->data['User']['newpass'])."' WHERE username='".AuthComponent::user('username')."';");
                $this->Session->setFlash(__('The user has been saved'));
                $this->redirect(array('controller' => 'users', 'action' => 'logout'));
                }
                else
                {
                    $this->Session->setFlash(__('New password confirm not match'));


                }
            }
            else
            {
                $this->Session->setFlash(__('Password not true'));
            }
        }
        
    }

    public function editacc($id=null)
    {
    

        if ($this->request->is('post'))
            {

            $editusername=$_POST['editusername'];
            $editgroupID=$_POST['editgroupID'];

            $count = $this->User->find('count', array('conditions' => array('username'=>AuthComponent::user('username') ,'password'=> AuthComponent::password(AuthComponent::user('username').AuthComponent::user('groupID').$this->request->data['User']['password']))));
            
            if ($count>0)
            {
                if ($this->request->data['User']['newpass']==$this->request->data['User']['renewpass'])
                {       
                $this->User->query("UPDATE users SET password='".AuthComponent::password($editusername.$editgroupID.$this->request->data['User']['newpass'])."' WHERE username='".$editusername."' and groupID='".$editgroupID."';");
                $this->Session->setFlash(__('user newpass has been changed'));
                $this->redirect(array('controller' => 'users', 'action' => 'logout'));
                }
                else
                    {
                    $this->Session->setFlash(__('New password confirm not match'));
                    }
            }
            else
            {
                $this->Session->setFlash(__('Admin password not true'));
            }

            
            }
        if($this->request->is('get'))
            {
             $qr=$this->User->query("SELECT * from users where ".$id.";");   
            //print_r ($qr[0]['users']);
            $this->set('edituser',$qr[0]['users']);
            }

    }

    public function delete($id = null) {
            
        if ((AuthComponent::user('userType')!=1))
        {
            $this->Session->setFlash(__('Access deny'));
            //$this->redirect(array('controller' => 'users', 'action' => 'index'));
        }
        else
        {
            $dat=str_getcsv($id,"*");
            $this->User->query("DELETE FROM users WHERE username='".$dat[0]."' and groupID='".$dat[1]."';");
            $this->Session->setFlash(__('user have been deleted!!!'));
            $this->redirect(array('controller' => 'users', 'action' => 'account'));
        }
    /*    if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->User->delete()) {
            $this->Session->setFlash(__('User deleted'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('User was not deleted'));
        $this->redirect(array('action' => 'index'));
        */
    }

    public function tcdelete($id = null) {
            
        if ((AuthComponent::user('userType')!=2))
        {
            $this->Session->setFlash(__('Access deny'));
            //$this->redirect(array('controller' => 'groups', 'action' => 'teachermanager'));
        }
        else
        {   $dat=str_getcsv($id,"*");
            /*if (AuthComponent::user('groupID')!=$dat[1]) {
            $this->Session->setFlash(__('Access deny'));
            }
            else
            { */
            echo "DELETE FROM users WHERE username='".$dat[0]."' and groupID='".$dat[1]."';";
            $this->User->query("DELETE FROM users WHERE username='".$dat[0]."' and groupID='".$dat[1]."';");
            $this->Session->setFlash(__('user have been deleted!!!'));
            $this->redirect(array('controller' => 'users', 'action' => 'account'));
           // }
        }
    /*    if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->User->delete()) {
            $this->Session->setFlash(__('User deleted'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('User was not deleted'));
        $this->redirect(array('action' => 'index'));
        */
    }
 
}