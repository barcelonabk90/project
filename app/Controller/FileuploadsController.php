<?php
CakePlugin::load('FileUpload');
class FileuploadsController extends AppController {

	var $name = 'Fileuploads';
	var $components = array('FileUpload.FileUpload');
	function beforeFilter(){
     $this->FileUpload->allowedTypes(array(
         'jpg' => array('image/jpeg', 'image/pjpeg'),
         'jpeg' => array('image/jpeg', 'image/pjpeg'),
         'gif' => array('image/gif'),
         'png' => array('image/png','image/x-png'),
         'zip' => array('application/octet-stream')
        )
     );
     $this->FileUpload->fields(array('name'=> 'name', 'type' => 'type', 'size' => 'size')); //các field tuong ung trong csdl
     $this->FileUpload->uploadDir('files'); //folder chua file upload, folder duoc tao san
     $this->FileUpload->fileModel('Fileupload');  //model cua table
     $this->FileUpload->fileVar('file'); //name
	}
	function index() {
		$this->Fileupload->recursive = 0;
		$this->set('fileuploads', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid fileupload', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('fileupload', $this->Fileupload->read(null, $id));
	}
// function upload dung cho thiet lap voi component
	function add() {
		if(!empty($this->data)){
		  if($this->FileUpload->success){
			$this->Session->setFlash(__('Upload successfully', true));
		  }else{
			$this->Session->setFlash($this->FileUpload->showErrors());
		  }
		}
	}
	
/*	function upload dung cho thiet lap voi behaviors
	function add() {
		if(!empty($this->data)){
		  if ($this->Fileupload->save($this->data)) {
				$this->Session->setFlash(__('Upload successfully', true));
				//$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Fileupload could not be uploaded. Please, try again.', true));
			}
		}
	}
*/
	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid fileupload', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Fileupload->save($this->data)) {
				$this->Session->setFlash(__('The fileupload has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The fileupload could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Fileupload->read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for fileupload', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Fileupload->delete($id)) {
			$this->Session->setFlash(__('Fileupload deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Fileupload was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
