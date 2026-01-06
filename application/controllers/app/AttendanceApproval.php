<?php
class AttendanceApproval extends MY_Controller
{
    private $attendance_index = "app/attendance_index";

    public function __construct(){
        parent::__construct();
		$this->data['headData']->pageTitle = "Attendance Approval";
		$this->data['headData']->controller = "app/attendanceApproval";    
		$this->data['headData']->pageUrl = "app/attendanceApproval";   
    }

    public function index(){
	    $this->data['headData']->appMenu = "app/attendanceApproval";
        $this->data['attendanceList'] = $this->getAttendanceData();
        $this->data['headData']->pageUrl = "app/attendanceApproval";   
		
        $this->load->view($this->attendance_index,$this->data);
    }

    public function getAttendanceData($status=""){
        $postData = $this->input->post();
		$fnCall = "Ajax";
		if(empty($postData)){$fnCall = 'Outside'; $postData['status'] = $status;}
        $approveData = $this->usersModel->getAttendanceData($postData);
		$approveList['pendingAttendance'] = '';$approveList['approvedAttendance'] = '';
		if(!empty($approveData))
		{
			foreach($approveData as $row)
			{
				$expDetail=''; $approveButton='';
				$userImg = base_url('assets/images/users/user_default.png');
                
                if($row->approve_by == 0){
                    $approveParam = "{'postData':{'id' : ".$row->id.",'message' : 'Approve','fndelete' : 'approve'}}";
                    $approveButton = '<a class="dropdown-item btn btn-danger btn-delete" href="javascript:void(0)" style="justify-content: flex-start;" onclick="trash('.$approveParam.');" flow="down"><i class="fa fa-check"></i>&nbsp;Approve</a>';
                }

				$filterCls = '';
				if($row->approve_by == 0){$filterCls = "pending_attendance";}
				if($row->approve_by > 0){$filterCls = "approved_attendance";}
				
				$emp_name = (!empty($row->emp_code)) ? '['.$row->emp_code.'] '.$row->emp_name : $row->emp_name;
				$expDetail = '<li class=" grid_item listItem item transition position-static '.$filterCls.'" data-category="transition">
                                    <a href="javascript:void(0)">
										<div class="mb-2 me-2 btn btn-rounded btn-icon btn-primary"><i class="fa fa-user "></i></div>
                                        <div class="media-content">
                                            <div>
                                                <h6 class="name">'.$emp_name.'</h6>
                                                <p class="my-1"> '.$row->type.' | <i class="far fa-clock"></i> '.date("d, M Y h:i A", strtotime($row->punch_date)).'</p>
                                                <p class="my-1"> '.$row->loc_add.' </p>
                                            </div>
                                        </div>
										<div class="left-content w-auto">';
                                            if($row->approve_by == 0):
                                                $expDetail .= '<a class="dropdown-toggle lead-action" data-bs-toggle="dropdown" href="#" role="button"><i class="mdi mdi-chevron-down fs-3"></i></a>
											    <div class="dropdown-menu dropdown-menu-end text-left">'.$approveButton.'</div>';
                                            endif;
                                        $expDetail .= '</div>
                                        
                                    </a>
                                </li>';
				if($row->approve_by == 0){$approveList['pendingAttendance'] .= $expDetail;}
				if($row->approve_by > 0){$approveList['approvedAttendance'] .= $expDetail;}
			}
		}
		if($fnCall == 'Ajax'){$this->printJson(['approveList'=>$approveList]);}
		else{return $approveList;}
    }

    public function approve(){
        $data = $this->input->post();
        if (empty($data['id'])) :
            $this->printJson(['status' => 0, 'message' => 'Somthing went wrong...Please try again.']);
        else :
            $this->usersModel->approveAttendance($data);
            $this->printJson($result);
        endif;
    }
}
?>