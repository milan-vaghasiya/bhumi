<?php
class Service extends MY_Controller{
	
	public function __construct(){
		parent::__construct();
		$this->isLoggedin();
		$this->data['headData']->pageTitle = "Service";
		$this->data['headData']->controller = "app/service";
	}
	
	public function index(){
		$this->data['headData']->appMenu = "app/service";
        $this->data['reqList'] = $this->getRequestData();
        $this->data['categoryData'] = $this->service->getServiceCategoryList();
        $this->load->view('app/service',$this->data);
    }

    public function getRequestData($status=""){
        $postData = $this->input->post();
		$fnCall = "Ajax";
		if(empty($postData)){$fnCall = 'Outside';$postData['status']=$status;}
        $reqData = $this->service->getServiceRequestList($postData);
		$reqList['pendingReq']=''; $reqList['completeReq']='';
		if(!empty($reqData))
		{
			foreach($reqData as $row)
			{
				$reqDetail =''; $filterCls = '';
				if($row->status == 0){$filterCls = "pending_req";}
				if($row->status == 1){$filterCls = "complete_req";}
				
				$reqDetail = '
                <li class="listItem item transition '.$filterCls.'" data-category="transition">
                    <div class="card order-box">
                        <div class="card-body">
                            <div class="order-content">
                                <div class="right-content">
                                    <div>
                                        <h6 class="order-number">'.$row->req_prefix.sprintf("%03d",$row->req_no).'</h6>
                                        <p class="order-name">'.$row->item_name.'</p>
                                        <p class="order-name">'.$row->party_name.'</p>
                                        <div class="divider border-light mb-1 mt-1"></div>
                                        <h6 class="order-time">'.date("d, M Y", strtotime($row->req_date)).'</h6>
                                    </div>
                                </div>
                                <div class="left-content w-auto">';
                                    if(empty($row->status)){
                                        $reqDetail .= '
                                        <a class="btn btn-sm light btn-danger add-btn permission-write closeBtn" data-form_title="Close Request" data-bs-toggle="offcanvas" data-bs-target="#closeModel" data-id="'.$row->id.'" aria-controls="offcanvasBottom">Close</a>';
                                    }
                                    $reqDetail .= '
                                </div>
                            </div>
                        </div>
                    </div>
                </li>';
				if($row->status == 0){$reqList['pendingReq'] .= $reqDetail;}
				if($row->status == 1){$reqList['completeReq'] .= $reqDetail;}
			}
		}
		if($fnCall == 'Ajax'){$this->printJson(['reqList'=>$reqList]);}
		else{return $reqList;}
    }

	public function saveCloseReq(){
        $postData = $this->input->post();
        $errorMessage = array();
        if(empty($postData['category_id'])){
            $errorMessage['category_id'] = "Category is required.";
        }
      
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $postData['status'] = 1;
            $postData['solution_by'] = $this->loginId;
            $postData['solution_at'] = date("Y-m-d H:i:s");
            $postData['updated_by'] = $this->loginId;
            $postData['updated_at'] = date('Y-m-d H:i:s');    

            $this->printJson($this->service->saveserviceRequest($postData));
        endif;
    }
}
?>