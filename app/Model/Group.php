<?php


class Group extends AppModel {
    //public $primaryKey = 'groupID';
    public $validate = array(
        'groupID' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'A groupID is required'
            )
        ),
        'groupName' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'A groupName is required'
            )
        )
    );
    
     public function beforeSave($options = array()) {
        //$this->data[$this->alias]['createdTime']='CURRENT_DATE';
    $this->data[$this->alias]['contractDate']=date('Y-m-d');
    return true;
    }
}


