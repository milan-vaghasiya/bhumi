<?php
class CustomerComplaint extends MY_Controller{
    private $indexPage = "customer_complaint/index";
    private $form = "customer_complaint/form";

	public function __construct(){
		parent::__construct();
		$this->isLoggedin();
		$this->data['headData']->pageTitle = "Customer Complaint";
		$this->data['headData']->controller = "customerComplaint";
		$this->data['headData']->pageUrl = "customerComplaint";
	}
	
	public function index(){
        $this->data['tableHeader'] = getMasterDtHeader($this->data['headData']->controller);
        $this->load->view($this->indexPage,$this->data);
    }

    public function getDTRows($status=0){
        $data = $this->input->post(); $data['status'] = $status;
        $result = $this->party->getComplaintDTRows($data);
        $sendData = array();$i=($data['start'] + 1);
        foreach($result['data'] as $row):
            $row->sr_no = $i++;
            $sendData[] = getCustomerComplaintData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addCustComplaint(){
        $this->data['trans_prefix'] = 'CMP';
        $this->data['trans_no'] = $this->party->getNextComplaintNo();
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>1]);
        $this->data['bTypeList'] = $this->configuration->getBusinessTypeList();
        $this->load->view($this->form,$this->data);
    }

    public function getReturnOrderList($param = []){
        $data = !empty($param)?$param:$this->input->post();
        $orderList = $this->sales->getSalesOrderItems(['entry_type'=>2, 'party_id'=>$data['party_id']]);
        $options = '<option value="">Select Order</option>';
        $options = '<option value="-1" '.((!empty($data['order_id']) && $data['order_id'] == -1) ? 'selected' : '').'>NA</option>';
        if(!empty($orderList)):
            foreach($orderList as $row):
                $selected = (!empty($data['order_id']) && $data['order_id'] == $row->id) ? 'selected' : '';
                $options .= '<option value="'.$row->id.'" '.$selected.'>'.$row->trans_number.'</option>';
            endforeach;
        endif;
        if(!empty($param)){
            return $options;
        }else{
            $this->printJson(['status'=>1, 'options'=>$options]);
        }
        
    }

    public function save(){
        $data = $this->input->post();
        $errorMessage = array();

        if(empty($data['trans_number'])){
            $errorMessage['trans_number'] = "Complaint No. is required.";
        }
        if(empty($data['trans_date'])){
            $errorMessage['trans_date'] = "Complaint Date is required.";
        }
        if(empty($data['party_id'])){
            $errorMessage['party_id'] = "Customer is required.";
        }
        if(empty($data['item_id'])){
            $errorMessage['item_id'] = "Product is required.";
        }
        if(empty($data['order_id'])){
            $errorMessage['order_id'] = "Return Order is required.";
        }

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            if(empty($data['id'])){
                $data['created_by'] = $this->loginId;
                $data['created_at'] = date('Y-m-d H:i:s');
            }else{
                $data['updated_by'] = $this->loginId;
                $data['updated_at'] = date('Y-m-d H:i:s');
            }
            $this->printJson($this->party->saveComplaint($data));
        endif;
    }

    public function edit(){
        $data = $this->input->post(); 
        $this->data['dataRow'] = $dataRow = $this->party->getCustComplaint($data); 
        $this->data['partyList'] = $this->party->getPartyList(['party_type'=>1,'business_type'=> $dataRow->business_type]);
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>1]);
        $this->data['options'] = $this->getReturnOrderList(['entry_type'=>2, 'party_id'=>$dataRow->party_id,'order_id'=>$dataRow->order_id]);
        $this->data['bTypeList'] = $this->configuration->getBusinessTypeList();
        $this->load->view($this->form,$this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->party->trash('customer_complaint',['id'=>$id]));
        endif;
    }    

    public function completeComplaint(){
		$data = $this->input->post();		
		if(empty($data['id'])):
			$this->printJson(['status'=>0,'message'=>'Something went wrong...Please try again.']);
		else:
			$this->printJson($this->party->completeComplaint($data));
		endif;
	}
}
?>