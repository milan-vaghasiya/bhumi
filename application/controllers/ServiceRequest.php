<?php
class ServiceRequest extends MY_Controller
{
    private $indexPage = "service_request/index";
    private $form = "service_request/form";
    private $assign_form = "service_request/assign_form";
    private $solution_form = "service_request/solution_form";

	public function __construct(){
		parent::__construct();
		$this->isLoggedin();
		$this->data['headData']->pageTitle = "Service Request";
		$this->data['headData']->controller = "serviceRequest";
		$this->data['headData']->pageUrl = "serviceRequest";
	}
	
	public function index(){
        $this->data['tableHeader'] = getMasterDtHeader($this->data['headData']->controller);
        $this->load->view($this->indexPage,$this->data);
    }

    public function getDTRows($status=0){
        $data = $this->input->post(); $data['status']=$status;
        $result = $this->service->getserviceRequestDTRows($data);
        $sendData = array();$i=($data['start'] + 1);
        foreach($result['data'] as $row):
            $row->sr_no = $i++;
            $sendData[] = getServiceRequestData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addServiceRequest(){  
        $this->data['req_prefix'] = 'REQ';      
        $this->data['nextReqNo'] = $this->service->getserviceRequestNextNo();
        $this->data['bTypeList'] = $this->configuration->getBusinessTypeList();
        $this->load->view($this->form,$this->data);
    }

    public function save(){
        $data = $this->input->post();
        $errorMessage = array();

        if(empty($data['req_no'])){
            $errorMessage['req_no'] = "Request No. is required.";
        }
        if(empty($data['party_id'])){
            $errorMessage['party_id'] = "Customer is required.";
        }
        if(empty($data['item_id'])){
            $errorMessage['item_id'] = "Product is required.";
        }

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->service->saveserviceRequest($data));
        endif;
    }

    public function edit(){
        $data = $this->input->post(); 
        $this->data['dataRow']  = $dataRow = $this->service->getServiceRequest($data); 
        $this->data['partyList'] = $this->party->getPartyList(['party_type'=>1,'business_type'=> $dataRow->business_type]);
        $this->data['bTypeList'] = $this->configuration->getBusinessTypeList();

        $this->data['options'] = $this->getItemOptions(['party_id'=>$dataRow->party_id,'item_id'=>$dataRow->item_id]);
        $this->load->view($this->form,$this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->service->trash('service_request',['id'=>$id]));
        endif;
    } 
    
    public function getItemOptions($param = []){
        $data = !empty($param)?$param:$this->input->post();
        $itemList = $this->sales->getSalesOrderItems(['party_id'=>$data['party_id'],'entry_type'=>1,'group_by'=>'item_id']);
        $options = 'Select Product';
        if(!empty($itemList)){
            foreach($itemList as $row){
                $selected = (!empty($data['item_id']) && $data['item_id'] == $row->item_id) ? "selected" : "";
                $options .= '<option value="'.$row->item_id.'" '.$selected.'>['.$row->item_code.'] '.$row->item_name.'</option>';
            }
        }
        if(!empty($param)){
            return $options;
        }else{
            $this->printJson(['status'=>1, 'options'=>$options]);
        }
        
    }

    public function assignEmployee(){
        $data = $this->input->post();
        $this->data['id'] = $data['id'];
        $this->data['empData'] = $this->usersModel->getEmployeeList();
        $this->load->view($this->assign_form,$this->data);        
    }

    public function saveAssignEmployee(){
        $data = $this->input->post();
        $errorMessage = array();

        if(empty($data['assign_to'])){
            $errorMessage['assign_to'] = "Employee is required.";
        }

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->service->saveserviceRequest($data));
        endif;
    }

    public function solutionForm(){
        $data = $this->input->post();
        $this->data['id'] = $data['id'];
        $this->data['empData'] = $this->usersModel->getEmployeeList();
        $this->load->view($this->solution_form,$this->data);        
    }

    public function saveSolutionForm(){
        $data = $this->input->post();
        $errorMessage = array();

        if(empty($data['solution_by'])){
            $errorMessage['solution_by'] = "Employee is required.";
        }

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->service->saveSolutionForm($data));
        endif;
    }
}
?>