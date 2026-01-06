<?php
class HqChangeRequest extends MY_Controller{
	
	public function __construct(){
		parent::__construct();
		$this->isLoggedin();
		$this->data['headData']->pageTitle = "Head Quater";
		$this->data['headData']->controller = "app/hqChangeRequest";
		//$this->data['headData']->pageUrl = "app/hqChangeRequest";
	}
	
	public function index(){
		$this->data['headData']->appMenu = "app/hqChangeRequest";
        $this->data['hqRequestList'] = $this->getHQRequestData();
        $this->load->view('app/hq_change_req',$this->data);
    }

    public function getHQRequestData($status=""){
        $postData = $this->input->post();
		$fnCall = "Ajax";
		if(empty($postData)){$fnCall = 'Outside'; $postData['status'] = $status;}
        $hqReqData = $this->configuration->getNewHeadQuarterList($postData);
		$hqReqList['pendingHq'] = '';$hqReqList['approvedHq'] = '';$hqReqList['rejectedHq'] = '';
		if(!empty($hqReqData))
		{
			foreach($hqReqData as $row)
			{
				$hqDetail=''; $rejectButton=''; $approveButton='';
				$userImg = base_url('assets/images/users/user_default.png');
                
                if($row->status == 0){
					$approveParam = "{'postData':{'id' : ".$row->id.",'emp_id' : ".$row->emp_id.",'new_hq_id' : ".$row->new_hq_id.",'status':'1'},'message' : 'Approve','fnsave' : 'changeHqRequest','controller':'app/hqChangeRequest'}";
                    $approveButton = '<a class="dropdown-item btn btn-danger " href="javascript:void(0)" style="justify-content: flex-start;" onclick="approveHq('.$approveParam.');" flow="down"><i class="fa fa-check"></i>&nbsp;Approve</a>';
                }
                if($row->status == 1){
                    $rejectParam = "{'postData':{'id' : ".$row->id.",'status':'2'},'message' : 'Reject','fnsave' : 'changeHqRequest','controller':'app/hqChangeRequest'}";
                    $approveButton = '<a class="dropdown-item btn btn-danger " href="javascript:void(0)" style="justify-content: flex-start;" onclick="approveHq('.$rejectParam.');" flow="down"><i class="fa fa-close"></i>&nbsp;Reject</a>';
                }


				$filterCls = '';
				if($row->status == 0){$filterCls = "pending_hq";}
				if($row->status == 1){$filterCls = "approved_hq";}
				if($row->status == 2){$filterCls = "rejected_hq";}
                $emp_name = (!empty($row->emp_code)) ? '['.$row->emp_code.'] '.$row->emp_name : $row->emp_name;

				$hqDetail = '<li class=" grid_item listItem item transition position-static '.$filterCls.'" data-category="transition">
                                    <a href="javascript:void(0)">
										<div class="mb-2 me-2 btn btn-rounded btn-icon btn-primary"><i class="fa fa-user "></i></div>
                                        <div class="media-content">
                                            <div>
                                                <h6 class="name">'.$emp_name.'</h6>
                                                <p class="my-1"> '.$row->department_name.'  -  '.$row->designation_name.'</p>
                                                <p class="my-1"> '.$row->hq_name.' </p>
                                                <p class="my-1"> '.$row->new_hq_name.' </p>
                                            </div>
                                        </div>
										<div class="left-content w-auto">';
                                            if($row->status != 2):
                                                $hqDetail .= '<a class="dropdown-toggle lead-action" data-bs-toggle="dropdown" href="#" role="button"><i class="mdi mdi-chevron-down fs-3"></i></a>
											    <div class="dropdown-menu dropdown-menu-end text-left">'.$approveButton.'</div>';
                                            endif;
                                        $hqDetail .= '</div>
                                        
                                    </a>
                                </li>';
				if($row->status == 0){$hqReqList['pendingHq'] .= $hqDetail;}
				if($row->status == 1){$hqReqList['approvedHq'] .= $hqDetail;}
				if($row->status == 2){$hqReqList['rejectedHq'] .= $hqDetail;}
			}
		}
		if($fnCall == 'Ajax'){$this->printJson(['hqReqList'=>$hqReqList]);}
		else{return $hqReqList;}
    }

    public function changeHqRequest(){
        $data = $this->input->post(); 
        if (empty($data['id'])) :
            $this->printJson(['status' => 0, 'message' => 'Somthing went wrong...Please try again.']);
        else :
            $this->configuration->changeHqRequest($data);
            $this->printJson($result);
        endif;
    }

    public function saveNewHeadQuarter(){
        $data = $this->input->post();
        $errorMessage = array();

        if(empty($data['new_hq_id']))
            $errorMessage['new_hq_id'] = "New Head Quarter is required.";
       
      
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else: 
            $this->printJson($this->configuration->saveNewHeadQuarter($data));
        endif;
    }

    /*Add Head Quarter */
    //03-08-2024
    public function addHeadQuarter($id){
        $this->data['emp_id'] = $id;
        $this->load->view('app/head_quarter_form', $this->data);
    }

    public function save(){
        $data = $this->input->post();
		$errorMessage = array();

        if(empty($data['name'])){
            $errorMessage['name'] = "Head Quarter Name is required.";
        }
        if(empty($data['hq_lat'])){
            $errorMessage['hq_lat'] = "Latitude is required.";
        }
        if(empty($data['hq_long'])){
            $errorMessage['hq_long'] = "Longitude is required.";
        }

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:    
            $this->printJson($this->configuration->saveHeadQuarter($data));
        endif;
    }
}
?>