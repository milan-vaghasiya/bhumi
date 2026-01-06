<?php
class Leave extends MY_ApiController{
    public function __construct(){
		parent::__construct();
        $this->data['headData']->pageTitle = "Leave";
        $this->data['headData']->pageUrl = "api/leave";
        $this->data['headData']->base_url = base_url();
	}

    public function getLeaveList(){
        $data = $this->input->post();
        $result = $this->leave->getLeaveList($data);

        // Columns to remove
        $columnsToRemove = ['created_by','created_at','updated_at','updated_by','is_delete','cm_id'];

        // Remove columns from result
        $result = array_map(function($row) use ($columnsToRemove) {
            return array_diff_key((array) $row, array_flip($columnsToRemove));
        }, $result);

        $this->printJson(['status'=>1,'data'=>['dataList'=>$result]]);
    }

    public function addLeave(){
		$data = $this->input->post();
		$this->data['empList'] = (in_array($this->userRole,[1,-1]))?$this->usersModel->getEmployeeList(['emp_not_in'=>[-1,1],'selectBox'=>1]):[];
        $this->printJson(['status'=>1,'message'=>'Data Found.','data'=>$this->data]);
	}

    public function save(){
		$data = $this->input->post();
        $errorMessage = array();
		
        if(empty($data['leave_status'])):
            if(empty($data['emp_id'])):
                $errorMessage['emp_id'] = "Employee is required.";
            endif;
            if(empty($data['start_date'])):
                $errorMessage['start_date'] = "Start Date is required.";
            endif;
            if(empty($data['end_date'])):
                $errorMessage['end_date'] = "End Date is required.";
            endif;
            if(empty($data['reason'])):
                $errorMessage['reason'] = "Reason is required.";
            endif;
            if(empty($data['total_days'])):
                $errorMessage['total_days'] = "Total Days is required.";
            endif;
        else:
            if(empty($data['leave_status'])):
                $errorMessage['leave_status'] = "Status is required.";
            else:
                if($data['leave_status'] == 2 && empty($data['notes'])):
                    $errorMessage['notes'] = "Rejection Reason is required.";
                endif;
            endif;

            $data['approve_by'] = $this->loginId; 
            $data['approve_at'] = date('Y-m-d H:i:s');
        endif;
			
		if(!empty($errorMessage)):
			$this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
			$this->printJson($this->leave->save($data));
        endif;
	}

    public function edit(){
        $data = $this->input->post();
		$this->data['dataRow'] = $this->leave->getLeaveData($data);
        $this->data['empList'] = (in_array($this->userRole,[1,-1]))?$this->usersModel->getEmployeeList(['emp_not_in'=>[-1,1],'selectBox'=>1]):[];
        $this->printJson(['status'=>1,'message'=>'Data Found.','data'=>$this->data]);
    }

    public function delete(){
        $data = $this->input->post();
        if(empty($data['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->leave->delete($data));
        endif;
    }
}
?>