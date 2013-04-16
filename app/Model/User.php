<?php
App::uses('DigestAuthenticate', 'Controller/Component/Auth/');

class User extends AppModel {
    var $primaryKeyArray = array('username','groupID');
    public $validate = array(
        'username' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'A username is required'
            )
        ),
        'name' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'A name is required'
            )
        ),
        'password' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'A password is required'
            )
        ),

        'password_confirm'=>array(
            'required'=>'notEmpty',
            'match'=>array(
                'rule' => 'validatePasswdConfirm',
                'message' => 'Passwords do not match'
            )
        ),
        'groupID' => array(
            'valid' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please select a group',
                'allowEmpty' => false
            )
        ),
        'regionID' => array(
            'valid' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please select a region',
                'allowEmpty' => false
            )
        ),
        'userType' => array(
            'valid' => array(
                'rule' => array('inList', array('1','2','3')),
                'message' => 'Please enter a valid role',
                'allowEmpty' => false
            )
        ),
        'createdTime'=>'CURRENT_DATE'
    );

 
    function validatePasswdConfirm($data)
    {
    if ($this->data['User']['password'] !== $data['password_confirm'])
    {
      return false;
    }
    return true;
    }

    public function beforeSave($options = array()) {
        //$this->data[$this->alias]['createdTime']='CURRENT_DATE';
    if ((isset($this->data[$this->alias]['password']))&&(isset($this->data[$this->alias]['username']))&&(isset($this->data[$this->alias]['groupID']))) {
        $this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['username'].$this->data[$this->alias]['groupID'].$this->data[$this->alias]['password']);
        $this->data[$this->alias]['createdTime']=date('Y-m-d'); 
        //echo date('Y-m-d');
        }
    return true;
    }


}
