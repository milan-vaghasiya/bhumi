<?php
class Employee extends MY_Controller{
	
	public function __construct(){
		parent::__construct();
		$this->isLoggedin();
		$this->data['headData']->pageTitle = "Profile";
		$this->data['headData']->controller = "app/employee";
		$this->data['headData']->pageUrl = "app/employee";
	}
	
	public function index(){
		$this->data['headData']->appMenu = "app/employee";
		$this->data['empData'] = $this->usersModel->getEmployee(['id'=>$this->loginId]);
        $this->data['quarterList'] = $this->configuration->getHeadQuarterList(); 
        $this->load->view('app/emp_profile',$this->data);
    }

	public function save(){
        $data = $this->input->post();
        $errorMessage = array();

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else: 
            $this->printJson($this->usersModel->saveEmployee($data));
        endif;
    }
}
?>