<?php
class BusinessType extends MY_Controller{
    private $index = "business_type/index";
    private $form = "business_type/form";

    public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Business Type";
		$this->data['headData']->controller = "businessType";
        $this->data['headData']->pageUrl = "businessType";
	}
	
	public function index(){
        $this->data['tableHeader'] = getConfigDtHeader($this->data['headData']->controller);
        $this->load->view($this->index,$this->data);
    }
	
    public function getDTRows(){
        $data = $this->input->post();
        $result = $this->configuration->getBusinessTypeDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):          
            $row->sr_no = $i++;         
            $sendData[] = getBusinessTypeData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addBusinessType(){
        $this->data['bTypeList'] = $this->configuration->getBusinessTypeList();
        $this->load->view($this->form, $this->data);
    }

    public function save(){
        $data = $this->input->post();
		$errorMessage = array();

        if(empty($data['type_name'])){
			$errorMessage['type_name'] = "Type Name is required.";
        }

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            
            $this->printJson($this->configuration->saveBusinessType($data));
        endif;
    }

    public function edit(){     
        $data = $this->input->post();
        $this->data['dataRow'] = $this->configuration->getBusinessType($data);
        $this->data['bTypeList'] = $this->configuration->getBusinessTypeList();
        $this->load->view($this->form, $this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        $type_name = $this->input->post('type_name');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $checkData['columnName'] = ['business_type','parent_id'];
            $checkData['value'] = $type_name;
            $checkUsed = $this->configuration->checkUsage($checkData);

            if($checkUsed == true):
                return ['status'=>0,'message'=>'The Party is currently in use. you cannot delete it.'];
            endif;

            $this->printJson($this->configuration->trash('business_type',['id'=>$id]));
        endif;
    }
}
?>