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
		
		if(!empty($data['from_date']) AND !empty($data['to_date']) AND (strtotime($data['from_date']) > strtotime($data['to_date'])) ){
			$errorMessage['end_date'] = "End Date must be greater than or equal to Start Date";
			$this->printJson(['status'=>0, 'data'=>[], 'message'=>$errorMessage]);
		}
		else{
			if(!empty($data['from_date']) AND !empty($data['to_date'])){
				$data['from_date'] = formatDate($data['from_date'],"Y-m-d");
				$data['to_date'] = formatDate($data['to_date'],"Y-m-d");
			}
			$this->data['leaveList'] = $this->leave->getLeaveList($data);
			$this->printJson(['status'=>1,'data'=>$this->data['leaveList']]);
		}
    }
	
    public function getLeaveCount(){
        $data = $this->input->post();
		
		if(!empty($data['from_date']) AND !empty($data['to_date']) AND (strtotime($data['from_date']) > strtotime($data['to_date'])) ){
			$errorMessage['end_date'] = "End Date must be greater than or equal to Start Date";
			$this->printJson(['status'=>0, 'data'=>[], 'message'=>$errorMessage]);
		}
		else{
			if(!empty($data['from_date']) AND !empty($data['to_date'])){
				$data['from_date'] = formatDate($data['from_date'],"Y-m-d");
				$data['to_date'] = formatDate($data['to_date'],"Y-m-d");
			}
			$this->data['leaveCount'] = $this->leave->getLeaveCount($data);
			$this->printJson(['status'=>1,'data'=>$this->data['leaveCount']]);
		}
    }	
	
    public function addLeave(){
        $this->data['leaveType'] = $this->leaveType;
        $this->printJson(['status'=>1,'messge'=>'Data Found','data'=>$this->data]);
    }

	public function save(){
        $data = $this->input->post();		
		
		$data['emp_id'] = (!empty($data['emp_id']) ? $data['emp_id'] : $this->loginId);
		$data['total_days'] = 0;
		if($data['start_section'] == "D"){$data['start_section'] = "H";}
		if($data['end_section'] == "D"){$data['end_section'] = "H";}
        $errorMessage = array();
		
        //if(empty($data['leave_type']))
            //$errorMessage['leave_type'] = "Leave Type is required.";

		if(empty(strtotime($data['start_date']))):
            $errorMessage['start_date'] = "Start Date is required.";
		else:
			$data['start_date'] = formatDate($data['start_date'],"Y-m-d");
		endif;
		if(strtotime($data['start_date']) < strtotime(date('Y-m-d')))
            $errorMessage['start_date'] = "Invalid Start Date";
		if(empty($data['start_section']))
            $errorMessage['start_section'] = "Start Section is required.";
		
		if(empty(strtotime($data['end_date']))):
            $errorMessage['end_date'] = "End Date is required.";
		else:
			$data['end_date'] = formatDate($data['end_date'],"Y-m-d");
		endif;
		if(empty($data['end_section']))
            $errorMessage['end_section'] = "End Section is required.";
		if(empty($data['leave_reason']))
            $errorMessage['leave_reason'] = "Reason is required.";
		
		if(strtotime($data['start_date']) > strtotime($data['end_date']))
			$errorMessage['end_date'] = "End Date must be greater than or equal to Start Date";
		
		if(date("m-Y",strtotime($data['start_date'])) != date("m-Y",strtotime($data['end_date'])))
			$errorMessage['end_date'] = "Start Date & End Date must be within the same month as the Start Date.";
			
		if(!empty(strtotime($data['start_date'])) AND !empty(strtotime($data['end_date'])))
		{
			if(strtotime($data['start_date']) == strtotime($data['end_date']))
			{
				if($data['start_section'] == "F"){$data['total_days'] = 1;}
				if($data['start_section'] == "H"){$data['total_days'] = 0.5;}
				$data['end_section'] = $data['start_section'];
			}
			else
			{
				if($data['start_section'] == "F"){$data['total_days'] += 1;}else{$data['total_days'] += 0.5;}
				if($data['end_section'] == "F"){$data['total_days'] += 1;}else{$data['total_days'] += 0.5;}
				
				$sd = new DateTime($data['start_date']);
				$ed = new DateTime($data['end_date']);
				$diff = $sd->diff($ed);
				
				$data['total_days'] += $diff->days - 1;				
			}
		}
		
		if(empty($data['total_days']))
            $errorMessage['end_date'] = "You have to apply atleast 1 Day Leave";

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
			//print_r($data);exit;
            if(isset($_FILES['proof_file']['name'])):
                if($_FILES['proof_file']['name'] != null || !empty($_FILES['proof_file']['name'])):
                    $this->load->library('upload');
                    $_FILES['userfile']['name']     = $_FILES['proof_file']['name'];
                    $_FILES['userfile']['type']     = $_FILES['proof_file']['type'];
                    $_FILES['userfile']['tmp_name'] = $_FILES['proof_file']['tmp_name'];
                    $_FILES['userfile']['error']    = $_FILES['proof_file']['error'];
                    $_FILES['userfile']['size']     = $_FILES['proof_file']['size'];
                    
                    $imagePath = realpath(APPPATH . '../assets/uploads/leave/');
                    $config = ['file_name' => 'expense-'.time(),'allowed_types' => '*','max_size' => 10240,'overwrite' => FALSE, 'upload_path'	=>$imagePath];

                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload()):
                        $errorMessage['proof_file'] = $this->upload->display_errors();
                        $this->printJson(["status"=>0,"message"=>$errorMessage]);
                    else:
                        $uploadData = $this->upload->data();
                        $data['proof_file'] = $uploadData['file_name'];
                    endif;
                endif;
            endif;
			$data['created_at'] = date('Y-m-d H:i:s');
			$data['created_by'] = $this->loginId;
            $this->printJson($this->leave->save($data));
        endif;
    }
	
    public function approveLeave(){
        $data = $this->input->post();
        
        $errorMessage = array();
        if(empty($data['id']))
            $errorMessage['id'] = "Leave is not defined.";
		if(empty($data['status']) OR $data['status'] < 2)
            $errorMessage['status'] = "Invalid Status";
		if(($data['status'] == 3) AND empty($data['auth_notes']))
            $errorMessage['auth_notes'] = "Rejection Reason is required.";
			
		if(!empty($errorMessage)):
				$this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
			$data['auth_by'] = $this->loginId;
			$data['auth_at'] = date('Y-m-d H:i:s');
			$data['updated_at'] = date('Y-m-d H:i:s');
			$data['updated_by'] = $this->loginId;
			
			$this->printJson($this->leave->save($data));
        endif;
    }
	
    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->leave->delete($id));
        endif;
    }

}
?>