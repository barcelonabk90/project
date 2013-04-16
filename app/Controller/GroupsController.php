<?php
// app/Controller/UsersController.php


class GroupsController extends AppController {

    public function beforeFilter() {
        parent::beforeFilter();
        //$this->Auth->allow('add');
    }


    public function index()
    {

    }

    public function teachermanager()
    {

    	 if (AuthComponent::user('userType')!=2)
        {
            $this->Session->setFlash(__('Access deny'));
            $this->redirect(array('controller' => 'users', 'action' => 'index'));
        }
            $this->loadModel("User");
            $this->set('Users',$this->User->find('all', array('conditions' => array('groupID'=>AuthComponent::user('groupID') ))));  
            
    }

}