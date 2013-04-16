<?php 
class PagesController extends AppController {

 public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('home');
    }

	public function home()
	{}	

}