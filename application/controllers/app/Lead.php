<?php
class Lead extends MY_Controller{
    private $crm_desk = "app/crm_desk";
    private $party_detail = "app/party_detail";
    private $sales_enq_form = "app/sales_enq_form";
    private $sales_quot_form = "app/sales_quot_form";
    private $sales_ord_form = "app/sales_ord_form";
    private $lead_form = "app/lead_form";
    private $lead_list = "app/lead_list";
    private $order_list = "app/order_list";
    private $order_preview = "app/order_preview";
    private $lead_status = "app/lead_status";
    private $customer_desk = "app/customer_desk";

    private $index = "lead/index";
	private $followupForm = "lead/followup_form";
	private $enquiryForm = "lead/enquiry_form";
	private $view_followup = "lead/view_followup";

    public function __construct(){
        parent::__construct();
		$this->data['headData']->pageTitle = "Leads";
		$this->data['headData']->controller = "app/lead";    
		$this->data['headData']->pageUrl = "app/lead/crmDesk";
    }

    /** CRM DESK */
    public function crmDesk(){
		$this->data['headData']->appMenu = "app/lead/crmDesk";    
        $this->data['rec_per_page'] = 10; // Records Per Page
        $this->data['stageList'] = $this->configuration->getLeadStagesList();
        $this->load->view($this->crm_desk,$this->data);
    }
	
	public function getLeadData($party_type="",$fnCall = "Ajax"){
        $postData = $this->input->post();
        $leadStages = $this->data['leadStages'] = $this->configuration->getLeadStagesList(['not_in'=>[1,2,3]]);
		if(empty($postData)){$fnCall = 'Outside';$postData['party_type']=$party_type;}
        $next_page = 0;
		
		$leadData = Array();
		if(isset($postData['page']) AND isset($postData['start']) AND isset($postData['length']))
        {
            $leadData = $this->party->getLeadList($postData);
            $next_page = intval($postData['page']) + 1;
            
        }
        else{ $leadData = $this->party->getLeadList($postData); }
		
		$this->data['leadData'] = $leadData;
		$leadDetail ='';
		$leadDetail = $this->load->view('app/list_view',$this->data,true);
		
        if($fnCall == 'Ajax'){$this->printJson(['leadDetail'=>$leadDetail,'next_page'=>$next_page]);}
		else{return $leadDetail;}
    }
    
	public function partyContactList(){
        $data = $this->input->post();
        if($data['party_type'] == 1){
            $this->data['dataRow'] = $this->party->getParty(['id'=>$data['id']]); 
        }else{
            $this->data['dataRow'] = $this->party->getLead(['id'=>$data['id']]);
        }
        $this->load->view('app/party_contact',$this->data);
    }
	
    /* CUSTOMER DESK */
    public function customerDesk(){
		$this->data['headData']->controller = "app/lead";    
		$this->data['headData']->pageUrl = "app/lead/customerDesk";
		$this->data['headData']->appMenu = "app/lead/customerDesk";    
        $this->data['rec_per_page'] = 10; // Records Per Page
        $this->data['bTypeList'] = $this->configuration->getBusinessTypeList();
        $this->load->view($this->customer_desk,$this->data);
    }

    public function getPartyData($party_type="",$fnCall = "Ajax"){
        $postData = $this->input->post();
        $leadStages = $this->data['leadStages'] = $this->configuration->getLeadStagesList(['not_in'=>[1,2,3]]);
		if(empty($postData)){$fnCall = 'Outside';$postData['party_type']=$party_type;}
        $next_page = 0;
		
		$leadData = Array();
		if(isset($postData['page']) AND isset($postData['start']) AND isset($postData['length']))
        {
            $leadData = $this->party->getPartyList($postData);
            $next_page = intval($postData['page']) + 1;
            
        }
        else{ $leadData = $this->party->getPartyList($postData); }
		
		$this->data['leadData'] = $leadData;
		$leadDetail ='';
		$leadDetail = $this->load->view('app/list_view',$this->data,true);
		
        if($fnCall == 'Ajax'){$this->printJson(['leadDetail'=>$leadDetail,'next_page'=>$next_page]);}
		else{return $leadDetail;}
    }
    
    public function getLeadDetails($lead_id,$party_id){
		$this->data['headData']->pageUrl = "app/lead/crmDesk";    
        if(!empty($lead_id)){
		    $this->data['partyData'] = $this->party->getLead(['id'=>$lead_id]);
		}elseif(!empty($party_id)){
		    $this->data['partyData'] = $this->party->getParty(['id'=>$party_id]);
		}

        $this->data['salesLog'] = $this->getSalesLog(['lead_id'=>$lead_id,'party_id'=>$party_id]);
		$this->data['sourceList'] = $this->configuration->getSelectOptionList(['type'=>1]);
        $this->load->view($this->party_detail,$this->data);
    }

    public function getSalesLog($param = []){
        $postData = $this->input->post();
		$fnCall = "Ajax";
		if(!empty($param)){$fnCall = 'Outside';$postData = $param;}
        $slData = $this->sales->getSalesLog(['lead_id'=>$param['lead_id'],'party_id'=>$param['party_id']]);
		$salesLog = '';
		if(!empty($slData))
		{
			$salesLog = '<ul class="dz-timeline">';
			foreach($slData as $row)
			{
				$msgSide = ($row->created_by == $this->loginId) ? 'user' : '';
				$userImg = base_url('assets/images/users/user_default.png');
                $link = '';
                if($row->log_type == 3){
                    $link = '<p class="text-muted fs-11 text-right">'.date("d M Y H:i A",strtotime($row->ref_date." ".$row->reminder_time)).'</p>';
                }
                elseif($row->log_type == 4){
                    $link = '<a href="'.base_url('lead/printEnquiry/'.$row->ref_id).'" class="fw-bold text-primary" target="_blank">'.$row->ref_no.'</a>';
                }
                elseif($row->log_type == 5){
                    $link = '<a href="'.base_url('lead/printQuotation/'.$row->ref_id).'" class="fw-bold text-primary" target="_blank">'.$row->ref_no.'</a>';
                }
                elseif($row->log_type == 6){
                    $link = '<a href="'.base_url('lead/printOrder/'.$row->ref_id).'" class="fw-bold text-primary" target="_blank">'.$row->ref_no.'</a>';
                }
                elseif($row->log_type == 7){
                    $link = "Lost Lead";
                }
                elseif($row->log_type == 9){
                    $link = '<a href="'.base_url('lead/printEnquiry/'.$row->ref_id).'" class="fw-bold text-primary" target="_blank">'.$row->ref_no.'</a>';
                }
				
				$lostBtn='';$editButton='';$deleteButton = '';$orderBtn='';$quoteBtn="";
				
				if(!in_array($row->log_type,[5,6])){
                    $quoteLink = "{'postData':{'ref_id':".$row->ref_id.",'party_id':".$row->party_id.",'lead_id':".$row->lead_id.",'trans_number':'".$row->ref_no."','sales_executive':".$row->executive_id."}}";
	                $quoteBtn = '<a class="dropdown-item btn1 btn-danger permission-modify" href="javascript:void(0)" datatip="Quotation Request" flow="down" onclick="addQuoteRequest('.$quoteLink.');">Quotation Request</a>';
				}
				
				if(!in_array($row->log_type,[6])){
    				$orderLink = "{'postData':{'id':".$row->id.",'status':4},'message':'Are you sure want to Create Quotation?','fnsave':'createQuotation'}";
    				$orderBtn = '<a href="'.base_url("app/lead/addSalesOrder/".$row->lead_id.'/'.$row->party_id.'/'.$row->ref_id.'/'.$row->log_type).'" class="dropdown-item btn1 btn-danger permission-write "  data-button="both" data-modal_id="right_modal_lg" data-function="addSalesOrder" data-fnsave="saveSalesOrder" data-form_title="Add Order" datatip="Add Order" data-ref_id="'.$row->ref_id.'" data-party_id="'.$row->party_id.'" flow="down"><i class="mdi mdi-close-circle"></i> Create Order</a>';
				}
				
				$dropDown = "";
				if(!in_array($row->log_type,[1,2,3,6,7,8,9,10,11,12])){
				    $dropDown='<a class="dropdown-toggle lead-action float-end" data-bs-toggle="dropdown" href="#" role="button">
												<i class="mdi mdi-chevron-down fs-3"></i>
											</a>
											<div class="dropdown-menu">'.$quoteBtn.$orderBtn.'</div>';
				}
                $salesLog .='<li class="timeline-item">
								<h6 class="timeline-tilte"> 
                                 '.(!in_array($row->log_type,[2])?$this->logTitle[$row->log_type]:'').'
                                 '.$dropDown.'
                                 </h6>
								<p class="timeline-date">'.date("d M Y",strtotime($row->created_at)).'</p>
								<p class="timeline-content">
									'.(!empty($row->notes.$link)?$row->notes.$link:'').'
								</p>
							</li>';
				
			}
			$salesLog .= (!empty($salesLog)) ? '</ul>' : '';
		}
		if($fnCall == 'Ajax'){$this->printJson(['salesLog'=>$salesLog]);}
		else{return $salesLog;}
    }

    /* Save Remider Form */
    public function saveReminder(){
        $postData = $this->input->post();
		$errorMessage = array();
  
        if(empty($postData['ref_date']))
            $errorMessage['ref_date'] = "Date is required.";
        if(empty($postData['reminder_time']))
            $errorMessage['reminder_time'] = "Time is required.";
        if(empty($postData['notes']))
            $errorMessage['notes'] = "Notes is required.";
        
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $postData['id'] = '';
            $postData['log_type'] = 3;
			$result = $this->sales->saveSalesLogs($postData);
			$result['salesLog'] = $this->getSalesLog($postData);
			
			$this->printJson($result);
		endif;
    }

    /* Save Follow ups */
    public function saveFollowups(){
        $postData = $this->input->post(); 
        $postData['log_type'] = 2;
        $postData['id'] = '';
		$result = $this->sales->saveSalesLogs($postData);
		$result['salesLog'] = $this->getSalesLog($postData);
		
        $this->printJson($result);
    }

    /* Sales Enquiry Form */
    public function addSalesEnquiry($lead_id = "",$party_id = ""){
        $this->data['party_id'] = (!empty($party_id) ? $party_id : 0);
        $this->data['lead_id'] = (!empty($lead_id) ? $lead_id : 0);
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>1]);
        $this->data['sizeList'] = $this->configuration->getMasterList(['type'=>1]);
        $this->data['categoryData'] = $this->item->getCategoryList(['final_category'=>1]);
        $this->load->view($this->sales_enq_form,$this->data);
    }

    public function saveSalesEnquiry(){
        $data = $this->input->post();
        $errorMessage = array();

        if(empty(array_sum($data['qty'])))
            $errorMessage['itemData'] = "Item Details is required.";
        
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            
           
            $data['trans_prefix'] = 'SE/'.$this->shortYear.'/';
            $data['trans_no'] = $this->sales->getNextTransNumber(['tableName'=>'se_master']);
            $data['trans_number'] = $data['trans_prefix'].$data['trans_no'];
            $data['id'] = "";
            $data['trans_date'] = date("Y-m-d");
            $data['sales_executive'] =$this->loginId;
            $itemData = [];
            foreach($data['item_id'] as $key=>$item_id){
                if($data['qty'][$key] > 0){
                    $itemData[] = [
                        'id'=>'',
                        'item_id'=>$item_id,
                        'qty'=>$data['total_qty'][$key],
                        'order_unit'=>$data['order_unit'][$key],
                    ];
                }
            }
            $data['itemData'] = $itemData;
            unset($data['item_id'],$data['qty'],$data['item_name'],$data['price'],$data['order_unit'],$data['total_qty'],$data['gst_per'],$data['gst_amount'],$data['net_price'],$data['net_amt']);
            $data['module_type']=1;
			$result = $this->sales->saveSalesData($data);
			$this->printJson($result);
        endif;
    }

    /* Sales Order Form */
    public function addSalesOrder($lead_id="",$party_id="",$ref_id="",$log_type=""){
        $this->data['party_id'] = $party_id;
        $this->data['lead_id'] = $lead_id;
        
		if(!empty($ref_id)){
            $from_entry_type=($log_type == 4)?1:2;
            $this->data['from_entry_type'] = $from_entry_type;
            $this->data['from_ref_id'] = $ref_id;
            $this->data['fromRefItemList'] = ($from_entry_type == 1)?$this->sales->getSalesEnquiryItems(['id'=>$ref_id]):$this->sales->getSalesQuotationItems(['id'=>$ref_id]);;
        }
    
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>1]);
        $this->data['categoryData'] = $this->item->getCategoryList(['final_category'=>1]);
        $this->data['partyList'] = $this->party->getPartyList(['party_type'=>1]);
        $this->load->view($this->sales_ord_form,$this->data);
    }

    public function saveSalesOrder(){
        $data = $this->input->post(); 
        $errorMessage = array();
        if(empty($data['party_id']) && empty($data['lead_id'])){ $errorMessage['party_id'] = "Party Name is required.";}
        $itemData = [];
        if(empty(array_sum($data['qty']))){
            $errorMessage['itemData'] = "Item Details is required.";
        }else{
            foreach($data['item_id'] as $key=>$item_id){
                if($data['qty'][$key] > 0){
                    if(!empty($data['price'][$key])){
                        $itemData[] = [
                            'id'=>'',
                            'item_id'=>$item_id,
                            'from_entry_type'=>$data['from_entry_type'],
                            'qty'=>$data['total_qty'][$key],
                            'packing_qty'=>$data['qty'][$key],
                            'ref_id'=>$data['ref_id'][$key],
                            'price'=>$data['price'][$key],
                            'gst_per'=>$data['gst_per'][$key],
                            'disc_per'=>$data['regular_disc'][$key],
                        ];
                    }else{
                        $errorMessage['price'.$item_id] = "Price is required.";
                    }
                    
                }
            }
        }
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            if(isset($_FILES['order_file']['name']) && $_FILES['order_file']['name'] != null || !empty($_FILES['order_file']['name'])):
                $this->load->library('upload');
                $_FILES['userfile']['name']     = $_FILES['order_file']['name'];
                $_FILES['userfile']['type']     = $_FILES['order_file']['type'];
                $_FILES['userfile']['tmp_name'] = $_FILES['order_file']['tmp_name'];
                $_FILES['userfile']['error']    = $_FILES['order_file']['error'];
                $_FILES['userfile']['size']     = $_FILES['order_file']['size'];
                
                $imagePath = realpath(APPPATH . '../assets/uploads/sales_order/');
                $config = ['file_name' => time()."_order_item_".$_FILES['userfile']['name'],'allowed_types' => '*','max_size' => 10240,'overwrite' => FALSE, 'upload_path'	=>$imagePath];

                $this->upload->initialize($config);
                if (!$this->upload->do_upload()):
                    $errorMessage['order_file'] = $this->upload->display_errors();
                    $this->printJson(["status"=>0,"message"=>$errorMessage]);
                else:
                    $uploadData = $this->upload->data();
                    $data['order_file'] = $uploadData['file_name'];
                endif;
            endif;
          
            $data['trans_no'] =$this->sales->getNextTransNumber(['tableName'=>'so_master']);;
            $data['trans_prefix'] = 'SO/'.$this->shortYear.'/';
            $data['trans_number'] = $data['trans_prefix'].$data['trans_no'];
            $data['trans_date'] = date("Y-m-d");
            $data['itemData'] = $itemData;
            unset($data['item_id'],$data['qty'],$data['price'],$data['item_name'],$data['gst_per'],$data['gst_amount'],$data['wt_pcs'],$data['regular_disc'],$data['kg_disc'],$data['disc_price'],$data['net_price'],$data['net_amt'],$data['kg_qty'],$data['disc_kg'],$data['kg_price'],$data['order_unit'],$data['disc_kg'],$data['ref_id'],$data['total_qty']);
            $data['module_type']=3;
            $result = $this->sales->saveSalesData($data);
            
            $this->printJson($result);
        endif;
    }

    public function addParty(){
        $this->data['business_status'] = (isset($data['business_status']))?$data['business_status']:1;
		$this->data['currencyData'] = $this->configuration->getCurrencyList();
		$this->data['countryData'] = $this->configuration->getCountries();
		$this->data['party_code'] = $this->getPartyCode(2);
		$this->data['salesExecutives'] = $this->usersModel->getEmployeeList();
		$this->data['sourceList'] = $this->configuration->getSelectOptionList(['type'=>1]);
        $this->data['bTypeList'] = $this->configuration->getBusinessTypeList();
        $this->data['customerList'] = $this->party->getPartyList(['party_type'=>1,'business_status'=>'1,2','executive_required'=>1]);
        $this->data['party_type'] = 2;
        $this->data['salesZoneList'] = $this->configuration->getSalesZoneList(['executive_id'=>(!in_array($this->userRole,[1,-1]) ? $this->loginId : '')]);
        $this->data['stateList'] = $this->configuration->getStatutoryDetail(['group_by'=>'state']);
		$this->load->view($this->lead_form, $this->data);
    }

    public function save(){
        $data = $this->input->post();
        $errorMessage = array();
        if (empty($data['party_name']))
            $errorMessage['party_name'] = "Company name is required.";

        if (empty($data['statutory_id']))
            $errorMessage['statutory_id'] = 'Required.';

        if (empty($data['party_address']))
            $errorMessage['party_address'] = "Address is required.";               

        if (!empty($errorMessage)) :
            $this->printJson(['status' => 0, 'message' => $errorMessage]);
        else :
            if(!empty($_FILES['party_image'])):
                if($_FILES['party_image']['name'] != null || !empty($_FILES['party_image']['name'])):
                    $this->load->library('upload');
    				$_FILES['userfile']['name']     = $_FILES['party_image']['name'];
    				$_FILES['userfile']['type']     = $_FILES['party_image']['type'];
    				$_FILES['userfile']['tmp_name'] = $_FILES['party_image']['tmp_name'];
    				$_FILES['userfile']['error']    = $_FILES['party_image']['error'];
    				$_FILES['userfile']['size']     = $_FILES['party_image']['size'];
    				
    				$imagePath = realpath(APPPATH . '../assets/uploads/party/');
    				$config = ['file_name' => time()."_order_item_".$_FILES['userfile']['name'],'allowed_types' => '*','max_size' => 10240,'overwrite' => FALSE, 'upload_path'	=>$imagePath];
    
    				$this->upload->initialize($config);
    				if (!$this->upload->do_upload()):
    					$errorMessage['party_image'] = $this->upload->display_errors();
    					$this->printJson(["status"=>0,"message"=>$errorMessage]);
    				else:
    					$uploadData = $this->upload->data();
    					$data['party_image'] = $uploadData['file_name'];
    				endif;
    			endif;
            endif;

            $data['party_name'] = ucwords($data['party_name']);
            $data['gstin'] = (!empty($data['gstin']))?strtoupper($data['gstin']):"";
            $this->printJson($this->party->saveLead($data));
        endif;
    }

    public function sendQuotationRequest(){
        $postData = $this->input->post(); 
		$result = $this->sales->sendQuotationRequest($postData);
		$result['salesLog'] = $this->getSalesLog($postData);
		
        $this->printJson($result);
    }

    public function edit($id){
        $result = $this->party->getLead(['id'=>$id]);
        $this->data['dataRow'] = $result;
		$this->data['currencyData'] = $this->configuration->getCurrencyList();
		$this->data['countryData'] = $this->configuration->getCountries();
		$this->data['salesExecutives'] = $this->usersModel->getEmployeeList();
		$this->data['sourceList'] = $this->configuration->getSelectOptionList(['type'=>1]);
        $this->data['bTypeList'] = $this->configuration->getBusinessTypeList();
        $this->data['customerList'] = $this->party->getPartyList(['party_type'=>1,'business_status'=>'1,2','executive_required'=>1]);
        $this->data['party_type'] = 2;
        $this->data['salesZoneList'] = $this->configuration->getSalesZoneList(['executive_id'=>(!in_array($this->userRole,[1,-1]) ? $this->loginId : '')]);
        $this->data['stateList'] = $this->configuration->getStatutoryDetail(['group_by'=>'state']);
        $this->data['districtList'] = $this->getDistrictList(['state'=>$result->state,'district'=>$result->district]);
        $this->data['talukaList'] = $this->getTalukaList(['state'=>$result->state,'district'=>$result->district,'statutory_id'=>$result->statutory_id]);
        
		$this->load->view($this->lead_form, $this->data);
    }

    public function order(){
		$this->data['headData']->pageTitle = "Order List";
		$this->data['headData']->appMenu = "app/lead/order";
        $this->data['orderList'] = $this->sales->getSalesOrderItems(['entry_type'=>1]);
        $this->data['bTypeList'] = $this->configuration->getBusinessTypeList();
        $this->load->view($this->order_list, $this->data);
    }
    
    /* Auto Generate Party Code */
    public function getPartyCode($party_type=""){
        $partyType = (!empty($party_type))?$party_type:$this->input->post('party_type');
        $code = $this->party->getLeadCode($partyType);
        $prefix = "";
        if($partyType == 1):
            $prefix = "C";
        elseif($partyType == 2):
            $prefix = "L";
        endif;

        $party_code = $prefix.sprintf("%03d",$code);

        if(!empty($party_type)):
            return $party_code;
        else:
            $this->printJson(['status'=>1,'party_code'=>$party_code]);
        endif;
    }
   
    public function delete(){
        $data = $this->input->post();
        if (empty($data['id'])) :
            $this->printJson(['status' => 0, 'message' => 'Somthing went wrong...Please try again.']);
        else :
            $result = $this->party->deleteLead($data['id']);
            $this->printJson($result);
        endif;
    }
	
    /*** Lead Lost */
    public function changeLeadStatus(){
        $data = $this->input->post();
        $this->data['data'] = $data;
        if($data['party_type'] == 3){
             $this->data['reasonList'] = $this->configuration->getSelectOptionList(['type'=>2]);
        }
       
        $this->load->view($this->lead_status,$this->data);
    }
    
    /* Save Lost Lead */
    public function saveLeadStatus(){
        $data = $this->input->post();
        if (empty($data['id'])){ $errorMessage['id'] = "Lead is required.";}
        if ($data['party_type'] == 3 && empty($data['notes'])){ $errorMessage['notes'] = "Required.";}
        if ($data['party_type'] == 3 && $data['notes'] == 'OTHER' && empty($data['remark']) ){ $errorMessage['remark'] = "Remark is required.";}

        if (!empty($errorMessage)) :
            $this->printJson(['status' => 0, 'message' => $errorMessage]);
        else :
           
            $result = $this->party->changeLeadStatus($data);
			$result['leadList'] = $this->getLeadData('','Return');
			$this->printJson($result);
        endif;
    }
    
    public function confirmOrder($lead_id="",$party_id="",$from_entry_type="",$from_ref_id="",$jsonData=""){
		$this->data['headData']->menu_id = 0;
        $data = (Array) decodeURL($jsonData);
		$itemData = [];$ids = [];
		foreach($data as $row){
			if($row->qty > 0){
				$itemData[$row->item_id]['qty'] = $row->qty;
				$itemData[$row->item_id]['order_unit'] = $row->order_unit;
				$itemData[$row->item_id]['ref_id'] = $row->ref_id;
				$ids[]=$row->item_id;
			}
		}
		if(empty($itemData))
			$errorMessage['general_error'] = "Item Details is required.";
		
		if(!empty($errorMessage)):
			$this->printJson(['status'=>0,'message'=>$errorMessage]);
		else:
            $this->data['itemList'] = $this->item->getItemList(['item_type'=>1,'ids'=>$ids]);
            $this->data['sizeList'] = $this->configuration->getMasterList(['type'=>1]);
			$this->data['itemData'] = $itemData; 
            $this->data['party_id'] = $party_id;
            $this->data['lead_id'] = $lead_id;
            $this->data['from_entry_type'] = $from_entry_type;
            $this->data['from_ref_id'] = $from_ref_id;
            $this->load->view($this->order_preview, $this->data);
		endif;
	}

    public function getDistrictList($postData=array()){
        $state = (!empty($postData['state']))?$postData['state']:$this->input->post('state');
		
        $result =  $this->configuration->getStatutoryDetail(['state'=>$state,'group_by'=>'district']);
        
        $html = '<option value="">Select District</option>';
        foreach ($result as $row) :
            $selected = (!empty($postData['district']) && $row->district == $postData['district']) ? "selected" : "";
            $html .= '<option value="' . $row->district . '" ' . $selected . '>' . $row->district . '</option>';
        endforeach;

        if(!empty($postData)):
            return $html;
        else:
            $this->printJson(['status'=>1,'districtOption'=>$html]);
        endif;
    }

    public function getTalukaList($postData=array()){
        $data = (!empty($postData))?$postData:$this->input->post();
        $result =  $this->configuration->getStatutoryDetail(['district'=>$data['district'],'state'=>$data['state']]);
        $html = '<option value="">Select Taluka</option>';
        foreach ($result as $row) :
            $selected = (!empty($postData['statutory_id']) && $row->id == $postData['statutory_id']) ? "selected" : "";
            $html .= '<option value="' . $row->id . '" ' . $selected . '>' . $row->taluka . '</option>';
        endforeach;

        if(!empty($postData)):
            return $html;
        else:
            $this->printJson(['status'=>1,'talukaOption'=>$html]);
        endif;
    }

}
?>