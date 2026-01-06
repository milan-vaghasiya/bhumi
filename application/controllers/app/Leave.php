<?php
class Leave extends MY_Controller
{	
    private $leave_list = "app/leave_list";
    private $leave_approve_list = "app/leave_approve_list";

	public function __construct(){
		parent::__construct();
		$this->isLoggedin();
		$this->data['headData']->pageTitle = "Leave";
		$this->data['headData']->controller = "app/leave";
		$this->data['headData']->pageUrl = "app/leave";    
	}
	
	public function index(){
		$this->data['headData']->appMenu = "app/leave";
		$this->data['leaveHtml'] = $this->getLeaveData(['status'=>1]);
        $this->load->view($this->leave_list,$this->data);
    }

    public function leaveApprove(){
		$this->data['headData']->appMenu = "app/leave/leaveApprove";
		$this->data['headData']->pageUrl = "app/leave/leaveApprove";    
		$this->data['leaveHtml'] = $this->getLeaveData(['status'=>2]);
        $this->load->view($this->leave_approve_list,$this->data);
    }

    public function getLeaveData($parameter = []){
        $postData = !empty($parameter)?$parameter :  $this->input->post();
        $leaveData = $this->leave->getLeaveList(['status'=>$postData['status']]);
        $html = '';
        if(!empty($leaveData)):
            foreach($leaveData as $row): 
                 $editButton=''; $deleteButton=''; $approveButton='';
                if($row->approve_by == 0){
                    $editParam = "{'postData':{'id' : ".$row->id."},'modal_id' : 'modalCenter', 'form_id' : 'editLeave', 'title' : 'Update Leave','fnsave':'save','fnedit':'editLeave'}";

                    $editButton = '<a class="dropdown-item btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" style="justify-content: flex-start;" onclick="edit('.$editParam.');"><i class="mdi mdi-square-edit-outline" ></i>Edit</a>';
                  
                    $deleteParam = "{'postData':{'id' : ".$row->id."},'message' : 'Leave'}";
                    $deleteButton = '<a class="dropdown-item btn btn-danger btn-delete permission-remove" href="javascript:void(0)" style="justify-content: flex-start;" onclick="trash('.$deleteParam.');" flow="down"><i class="mdi mdi-trash-can-outline"></i> Remove</a>';
                    
                    $approveParam = "{'postData': {'ids' : ".$row->id."}, 'fnsave' : 'approveLeave', 'message' : 'Are you sure want to Approve this Leave?', 'controller' : 'leave'}";
                    $approveButton = '<a class="dropdown-item btn" href="javascript:void(0)" style="justify-content: flex-start;" onclick="confirmStore('.$approveParam.');"><i class="mdi mdi-check"></i> Approve</a>';
                }
                $emp_name = (!empty($row->emp_code)) ? '['.$row->emp_code.'] '.$row->emp_name : $row->emp_name;

                $html .='<li class="grid_item listItem item transition position-static" data-category="transition">
                    <a href="javascript:void(0)" class="position-relative">
                        <div class="mb-2 me-2 btn btn-rounded btn-icon btn-primary"><i class="fa fa-user"></i></div>
                        <div class="media-content">
                            <div>
                                <h6 class="name">'.$emp_name.'</h6>
                                <p class="my-1">'. formatDate($row->start_date) . ' To ' . formatDate($row->end_date) . '</p>
                                <p class="my-1">'.$row->total_days.'</p>
                                <p class="my-1">'.$row->reason.'</p>
                            </div>
                        </div>
                        <div class="left-content w-auto">';
                        if(empty($row->approve_by)):
                            $html .= '<a class="dropdown-toggle lead-action" data-bs-toggle="dropdown" href="#" role="button"><i class="mdi mdi-chevron-down fs-3"></i></a>
                            <div class="dropdown-menu dropdown-menu-end text-left">'.$editButton.$deleteButton.$approveButton.'</div>';
                        endif;
						$html .= '</div>
                    </a>
                </li>';
                
            endforeach;
        endif;
        if(!empty($parameter)){  return $html; }
        else{ $this->printJson(['status' => 1, 'html' =>$html]); }
    }

    public function addLeave(){
		$data = $this->input->post();
		$this->data['empList'] = $this->usersModel->getEmployeeList();
        $this->load->view("app/leave_form",$this->data);
	}

    public function editLeave(){
		$data = $this->input->post();
		$this->data['empList'] = $this->usersModel->getEmployeeList();
		$this->data['dataRow'] = $this->leave->getLeaveData($data);
        $this->load->view("app/leave_form",$this->data);
	}
	
	public function deleteLeave(){
        $data = $this->input->post();
        if(empty($data['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->leave->delete($data));
        endif;
    }

    public function approveLeave(){
        $data = $this->input->post();

        if(empty($data['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->leave->approveLeave($data));
        endif;
    }
}
?>