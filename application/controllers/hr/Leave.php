<?php
class Leave extends MY_Controller{
    private $index = "hr/leave/index";
    private $form = "hr/leave/form";
	private $leave_approve_index = "hr/leave/leave_approve_index";

    public function __construct(){
        parent::__construct();
        $this->data['headData']->pageTitle = "Leave";
		$this->data['headData']->controller = "hr/leave";    
        $this->data['headData']->pageUrl = "hr/leave";    
    }

	public function index(){
        $this->data['headData']->pageTitle = "Leave";
        $this->data['tableHeader'] = getHrDtHeader('leave');
        $this->data['status'] = 1;
        $this->load->view($this->index,$this->data);
    }

    public function leaveApprove(){
        $this->data['headData']->pageTitle = "Leave Approve";
        $this->data['tableHeader'] = getHrDtHeader('leave');
        $this->data['status'] = 2;
        $this->load->view($this->leave_approve_index,$this->data);
    }

    public function getDTRows($status = 1){
        $data = $this->input->post(); $data['status'] = $status;
        $result = $this->leave->getDTRows($data);
        $sendData = array();$i=($data['start']+1);
		foreach($result['data'] as $row):
			$row->sr_no = $i++; 
			$sendData[] = getLeaveData($row);
		endforeach;
		
        $result['data'] = $sendData;
        $this->printJson($result);
    }

	public function addLeave(){
		$data = $this->input->post();
		$this->data['empList'] = $this->usersModel->getEmployeeList();
        $this->load->view($this->form,$this->data);
	}

	public function save(){
		$data = $this->input->post();
        $errorMessage = array();
		
		if(empty($data['emp_id']))
            $errorMessage['emp_id'] = "Employee is required.";
		if(empty($data['start_date']))
            $errorMessage['start_date'] = "Start Date is required.";
		if(empty($data['end_date']))
            $errorMessage['end_date'] = "End Date is required.";
		if(empty($data['reason']))
            $errorMessage['reason'] = "Reason is required.";    
		if(empty($data['total_days']))
            $errorMessage['total_days'] = "Total Days is required.";
			
		if(!empty($errorMessage)):
			$this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
			$this->printJson($this->leave->save($data));
        endif;
	}
	
	public function edit(){
		$data = $this->input->post();
		$this->data['empList'] = $this->usersModel->getEmployeeList();
		$this->data['dataRow'] = $this->leave->getLeaveData($data);
        $this->load->view($this->form,$this->data);
	}
	
	public function delete(){
        $data = $this->input->post();
        if(empty($data['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->leave->delete($data['id']));
        endif;
    }

    public function approveLeave(){
        $postData = $this->input->post();
        if(empty($postData['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
			$postData['auth_by']=$this->loginId;
			$postData['auth_at']= date('Y-m-d');
			
            $this->printJson($this->leave->save($postData));
        endif;
    }
}
?>