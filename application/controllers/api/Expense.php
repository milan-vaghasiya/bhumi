<?php
class Expense extends MY_ApiController{
    public function __construct(){
		parent::__construct();
        $this->data['headData']->pageTitle = "Expense";
        $this->data['headData']->pageUrl = "api/expense";
        $this->data['headData']->base_url = base_url();
	}

    public function getExpenseList(){
        $data = $this->input->post();
        $expenseList = $this->expense->getExpenseData($data);

        // Columns to keep
        $columnsToKeep = ['id', 'emp_name', 'exp_number', 'exp_date', 'expense_label', 'demand_amount', 'amount', 'notes', 'status', 'super_auth_id'];

        // Retain only specific columns in the result
        $expenseList = array_map(function($row) use ($columnsToKeep) {
            return array_intersect_key((array) $row, array_flip($columnsToKeep));
        }, $expenseList);

        $this->printJson(['status'=>1,'data'=>['dataList'=>$expenseList]]);
    }

    public function addExpense(){
        //$this->data['exp_prefix'] = $exp_prefix = "EXP".n2y(date('Y')).n2m(date('m'));  
        //$this->data['exp_no'] = $exp_no = $this->expense->getNextExpNo();
        //$this->data['exp_number'] = $exp_prefix.sprintf("%03d",$exp_no);
        //$this->data['travelBy'] = ['Bike','Car','Bus','Train','Flight','Other'];
        $this->data['travelBy'] = [];//$this->configuration->getSelectOptionList(['type'=>5]);
        $this->data['expTypeList'] = $this->configuration->getSelectOptionList(['type'=>3,'calc_type'=>'MANUAL']);
		$this->printJson(['status'=>1,'message'=>'Data Found.','data'=>$this->data]);
    }

    public function save(){
        $data = $this->input->post();

        if(!empty($data['expTrans']) && gettype($data['expTrans']) == "string"): $data['expTrans'] = json_decode($data['expTrans'],true); endif;
        $errorMessage = array();
		$edValdMsg = ['ABSENT'=>'Employee is Absent', 'LEAVE'=>'Employee is on Leave'];

		$data['exp_by_id'] = $this->loginId;
		 
        if(empty($data['exp_date']))
		{
            $errorMessage['exp_date'] = "Expense date is required.";
        }
		else
		{
			$ed_validation = $this->expense->hasValidExpDate($data);
			if(!empty($ed_validation) AND $ed_validation->expense_status != "OK"){$errorMessage['exp_date'] = $edValdMsg[$ed_validation->expense_status];}
		}
        if(empty($data['exp_by_id']))
            $errorMessage['exp_by_id'] = "Employee is required.";
		if($data['exp_date'] < date('Y-m-d', strtotime('-7 days')))
			$errorMessage['exp_date'] = "You have entered the expiration date.";
        if(empty(array_sum(array_column($data['expTrans'],'amount'))))
            $errorMessage['exp_type_data'] = "Expense type is required.";

        if(!empty($_FILES['proof_file']['name'][0])):
            foreach ($_FILES['proof_file']['name'] as $key => $value):
                if($value != null || !empty($value)):
                    $this->load->library('upload');
                    $_FILES['userfile']['name']     = $value;
                    $_FILES['userfile']['type']     = $_FILES['proof_file']['type'][$key];
                    $_FILES['userfile']['tmp_name'] = $_FILES['proof_file']['tmp_name'][$key];
                    $_FILES['userfile']['error']    = $_FILES['proof_file']['error'][$key];
                    $_FILES['userfile']['size']     = $_FILES['proof_file']['size'][$key];
                    
                    $imagePath = realpath(APPPATH . '../assets/uploads/expense/');
                    $config = ['file_name' => time()."_EXP_PROOF_FILE_".$_FILES['userfile']['name'][$key]."_".$key,'allowed_types' => '*','max_size' => 10240,'overwrite' => FALSE, 'upload_path'	=>$imagePath];
    
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload()):
                        $errorMessage['proof_file'] = $this->upload->display_errors();
                    else:
                        $uploadData = $this->upload->data();
                        $data['proof_file'][] = $uploadData['file_name'];
                    endif;
                endif;
            endforeach;
            $data['proof_file'] = implode(',', $data['proof_file']);
        endif;
      
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->expense->saveExpense($data));
        endif;
    }

    public function edit(){
        $data = $this->input->post();
        $dataRow = $this->expense->getExpense(['id'=>$data['id']]);
		if(!empty($dataRow->proof_file))
		{
			$pfiles = explode(',',$dataRow->proof_file);
			$file_paths = Array();
			foreach($pfiles as $file_name){$file_paths[] = base_url('assets/uploads/expense/'.$file_name);}
			$dataRow->proof_file = $file_paths;
		}
        $dataRow->expTrans = $this->expense->getExpenseTransData(['exp_id' => $dataRow->id]);
		
        $this->data['dataRow'] = $dataRow;
        $this->data['expTypeList'] = $this->configuration->getSelectOptionList(['type'=>3]);
		$this->printJson(['status'=>1,'message'=>'Data Found.','data'=>$this->data]);
    }

    public function delete(){
        $data = $this->input->post();
        if (empty($data['id'])) :
            $this->printJson(['status' => 0, 'message' => 'Somthing went wrong...Please try again.']);
        else :
            $this->expense->trash('expense_trans',['exp_id'=>$data['id']]);
            $result = $this->expense->trash('expense_manager',['id'=>$data['id']]);
            $this->printJson($result);
        endif;
    }

    public function saveApprovedData(){
        $data = $this->input->post();
        $errorMessage = array();
       
        if($data['status'] == 1):
            if(empty($data['amount'])):
                $errorMessage['amount'] = "Amount is required.";
            endif;
        else:
            if(empty($data['rej_reason'])):
                $errorMessage['rej_reason'] = "Reason is required.";
            endif;
        endif;
      
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $data['approved_by'] = $this->loginId;
            $data['approved_at'] = date('Y-m-d H:i:s');
            $this->printJson($this->expense->changeExpenseStatus($data));
        endif;
    }
}
?>