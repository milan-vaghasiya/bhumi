<?php
class Reports extends MY_Controller{

	public function __construct(){
		parent::__construct();
		$this->isLoggedin();
		$this->data['headData']->pageTitle = "Reports";
		$this->data['headData']->controller = "app/reports";
		$this->data['headData']->appMenu = "app/reports";
	}
	
	public function index(){
        $this->data['empData'] = $this->usersModel->getEmployeeList();
        $this->load->view('app/reports',$this->data);
    }
}
?>