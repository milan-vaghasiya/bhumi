<?php
class Dashboard extends MY_Controller{

	public function __construct(){
		parent::__construct();
		$this->isLoggedin();
		$this->data['headData']->pageTitle = "Dashboard";
		$this->data['headData']->controller = "app/dashboard";
	}
	
	public function index(){
		$this->data['headData']->appMenu = "app/dashboard";
		/*$this->data['logCount'] = $this->sales->getSalesLogCount(['from_date'=>date("Y-m-1"),'to_date'=>date("Y-m-d"),'created_by'=>(!in_array($this->userRole,[1,-1])?$this->loginId:'')]);
		$this->data['tagetData'] = $this->sales->getTargetData(['emp_id'=>$this->loginId,'target_month'=>date("y-m-1"),'single_row'=>1]);
	    $this->data['logClass'] = $this->logClass;
	    $this->data['logTitle'] = $this->logTitle;*/
        $this->load->view('app/dashboard',$this->data);
    }

	public function reminderList(){
		$this->load->view('app/pending_response',$this->data);
	}
	
	public function getReminderData(){
		$reminderList = $this->sales->getReminders(['status'=>1,'executive_id'=>(!in_array($this->userRole,[1,-1])?$this->loginId:'')]);
		$html="";
		if(!empty($reminderList)){
			foreach($reminderList as $row){
				$link = '<p class="text-muted fs-11 text-right">'.date("d M Y H:i A",strtotime($row->ref_date." ".$row->reminder_time)).'</p>';
				$responseParam = "{'postData':{'id' : ".$row->id.",'lead_id' : ".$row->lead_id."},'modal_id' : 'modalCenter', 'form_id' : 'response', 'title' : 'Reminder Response', 'fnedit' : 'reminderResponse', 'fnsave' : 'saveSalesLog','controller':'lead','res_function':'reminderReponse'}";
				$btn = '<a href="javascript:void(0)" class="btn btn-sm btn-primary float-end" datatip="Edit" flow="down" onclick="edit('.$responseParam.');">Response</a>';
				$html .= '<li class="listItem item transition "  data-category="transition">
							<div class="card order-box">
								<div class="card-body">
									<div class="order-content">
										<div class="right-content">
											<div>
												<h6 class="order-number">'.$row->party_name.'</h6>
												<p class="order-name">'.$row->notes.$link.'</p>
												<div class="divider border-light mb-0 mt-0"></div>
												<h6 class="order-time">'.date("d M Y",strtotime($row->created_at)).'</h6>
											</div>
										</div>
										<div class="left-content w-auto">
											'.$btn.'
										</div>
									</div>
								</div>
							</div>
						</li>';
				
			}
		}
		$this->printJson(['status'=>1,'html'=>$html]);
	}
}
?>