<?php
class Expense extends MY_Controller
{
    private $expense_index = "app/expense_index";
    private $expense_form = "app/expense_form";

    public function __construct(){
        parent::__construct();
		$this->data['headData']->pageTitle = "Expense";
		$this->data['headData']->controller = "app/expense";    
		$this->data['headData']->pageUrl = "app/expense";   
    }

    public function index(){
	    $this->data['headData']->appMenu = "app/expense";
        $this->data['expList'] = $this->getExpenseData();
        $this->data['headData']->pageUrl = "app/expense";   

        $this->load->view($this->expense_index,$this->data);
    }

    public function getExpenseData($status=""){
        $postData = $this->input->post();
		$fnCall = "Ajax";
		if(empty($postData)){$fnCall = 'Outside'; $postData['status'] = $status;}
        $postData['group_by'] = "expense_manager.id";
        $expData = $this->expense->getExpenseData($postData);
		$expList['pendingExp'] = '';$expList['approvedExp'] = '';$expList['rejectedExp'] = '';

		if(!empty($expData))
		{
			foreach($expData as $row)
			{
				$expDetail=''; $editButton=''; $deleteButton=''; $approveButton=''; $rejectButton='';
				$userImg = base_url('assets/images/users/user_default.png');
                
                if($row->status == 0){
                    $editButton = '<a class="dropdown-item btn btn-success btn-edit permission-modify" href="'.base_url("app/expense/edit/".$row->id).'" style="justify-content: flex-start;" flow="down"><i class="mdi mdi-square-edit-outline"></i> Edit</a>';
                    
                    $deleteParam = "{'postData':{'id' : ".$row->id."},'message' : 'Expense'}";
                    $deleteButton = '<a class="dropdown-item btn btn-danger btn-delete permission-remove" href="javascript:void(0)" style="justify-content: flex-start;" onclick="trash('.$deleteParam.');" flow="down"><i class="mdi mdi-trash-can-outline"></i> Remove</a>';
                    
			        if(in_array($this->userRole,[1,-1]) || in_array($this->loginId,explode(',',$row->super_auth_id))){
                        $approveParam = "{'postData': {'ids' : ".$row->id."}, 'fnsave' : 'approveBulkRequest', 'message' : 'Are you sure want to Approve this Expense?', 'controller' : 'expense'}";
                        $approveButton = '<a class="dropdown-item btn" href="javascript:void(0)" style="justify-content: flex-start;" onclick="confirmStore('.$approveParam.');"><i class="mdi mdi-check"></i> Approve</a>';

                        $rejectParam = "{'postData': {'id' : ".$row->id.", 'status' : 2}, 'fnsave' : 'rejectExpense', 'message' : 'Are you sure want to Reject this Expense?', 'controller' : 'expense'}";
                        $rejectButton = '<a class="dropdown-item btn" href="javascript:void(0)" style="justify-content: flex-start;" onclick="confirmStore('.$rejectParam.');"><i class="mdi mdi-close"></i> Reject</a>';
                    }
                }

				$filterCls = '';
				if($row->status == 0){$filterCls = "pending_exp";}
				if($row->status == 1){$filterCls = "approved_exp";}
				if($row->status == 2){$filterCls = "rejected_exp";}
				
				$expDetail = '<li class=" grid_item listItem item transition position-static '.$filterCls.'" data-category="transition">
					<a href="javascript:void(0)">
						<div class="mb-2 me-2 btn btn-rounded btn-icon btn-primary"><i class="fa fa-user "></i></div>
						<div class="media-content">
							<div>
								<h6 class="name">'.(!empty($row->emp_name) ? $row->emp_name : $row->party_name).'</h6>
								<p class="my-1"> '.$row->exp_number.' | <i class="far fa-clock"></i> '.date("d, M Y", strtotime($row->exp_date)).'</p>
								<p class="my-1"> '.$row->expense_label.' | '.$row->amount.' </p>
								<p class="my-1"> '.$row->notes.' </p>
							</div>
						</div>
						<div class="left-content w-auto">';
							if(empty($row->status)):
								$expDetail .= '<a class="dropdown-toggle lead-action" data-bs-toggle="dropdown" href="#" role="button"><i class="mdi mdi-chevron-down fs-3"></i></a>
								<div class="dropdown-menu dropdown-menu-end text-left">'.$editButton.$deleteButton.$approveButton.$rejectButton.'</div>';
							endif;
						$expDetail .= '</div>
						
					</a>
				</li>';
				if($row->status == 0){$expList['pendingExp'] .= $expDetail;}
				if($row->status == 1){$expList['approvedExp'] .= $expDetail;}
				if($row->status == 2){$expList['rejectedExp'] .= $expDetail;}
			}
		}
		if($fnCall == 'Ajax'){$this->printJson(['expList'=>$expList]);}
		else{return $expList;}
    }
	
	public function addExpense(){
        $this->data['emp_id'] = $this->loginId;
        $this->data['expTypeList'] = $this->configuration->getSelectOptionList(['type'=>3]); // 08-04-2024
        $this->data['exp_prefix'] = "EXP".n2y(date('Y')).n2m(date('m'));  
        $this->data['exp_no'] = $this->expense->getNextExpNo();
		$this->load->view($this->expense_form, $this->data);
    }

	public function save(){
        $data = $this->input->post();
        $errorMessage = array();

        if(empty($data['exp_number'])){
            $errorMessage['exp_number'] = "Expense Number is required.";
        }
        if(empty($data['exp_date'])){
            $errorMessage['exp_date'] = "Expense date is required.";
        }
        if(empty($data['exp_by_id'])){
            $errorMessage['exp_by_id'] = "Employee is required.";
        }
		$lastWeekDate = date('Y-m-d', strtotime('-7 days'));
		if($data['exp_date'] < $lastWeekDate){
			$errorMessage['exp_date'] = "You have entered the expiration date.";
		}

        $is_valid = 0;
        foreach ($data['exp_trans_amt'] as $row) {
            if(!empty($row)){ $is_valid = 1; }
        }
        if(empty($is_valid)){
            $errorMessage['exp_type_data'] = "Expense type is required.";
        }
      
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
			if(!empty($_FILES['proof_file']['name'][0])):
                foreach ($_FILES['proof_file']['name'] as $key => $value) {
                    if($value != null || !empty($value)):
                        $this->load->library('upload');
        				$_FILES['userfile']['name']     = $value;
        				$_FILES['userfile']['type']     = $_FILES['proof_file']['type'][$key];
        				$_FILES['userfile']['tmp_name'] = $_FILES['proof_file']['tmp_name'][$key];
        				$_FILES['userfile']['error']    = $_FILES['proof_file']['error'][$key];
        				$_FILES['userfile']['size']     = $_FILES['proof_file']['size'][$key];
        				
        				$imagePath = realpath(APPPATH . '../assets/uploads/expense/');
        				$config = ['file_name' => time()."_order_item_".$_FILES['userfile']['name'][$key]."_".$key,'allowed_types' => '*','max_size' => 10240,'overwrite' => FALSE, 'upload_path'	=>$imagePath];
        
        				$this->upload->initialize($config);
        				if (!$this->upload->do_upload()):
        					$errorMessage['proof_file'] = $this->upload->display_errors();
        					$this->printJson(["status"=>0,"message"=>$errorMessage]);
        				else:
        					$uploadData = $this->upload->data();
        					$data['proof_file'][] = $uploadData['file_name'];
        				endif;
        			endif;
                }
                $data['proof_file'] = implode(',', $data['proof_file']);
            endif;

            $this->printJson($this->expense->saveExpense($data));
        endif;
    }
	
    public function edit($id){
        $this->data['dataRow'] = $dataRow = $this->expense->getExpense(['id'=>$id]);
        $this->data['expTransData'] = $this->expense->getExpenseTransData(['exp_id' => $dataRow->id]);
        $this->data['expTypeList'] = $this->configuration->getSelectOptionList(['type'=>3]); // 08-04-2024
		$this->load->view($this->expense_form, $this->data);
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
}
?>