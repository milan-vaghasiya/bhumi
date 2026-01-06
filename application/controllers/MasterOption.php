<?php
class MasterOption extends MY_Controller{
    private $indexPage = "master_option/index";
    private $form = "master_option/form";
    private $custFieldIndex = "master_option/cust_field_index";
    private $custFieldForm = "master_option/cust_field_form";

	public function __construct(){
		parent::__construct();
		$this->isLoggedin();
		$this->data['headData']->pageTitle = "Master Option";
		$this->data['headData']->controller = "masterOption";
		$this->data['headData']->pageUrl = "masterOption";
	}
    
    /*********************************************************************************** */
    /** Custom Field */
    public function customField(){
		$this->data['headData']->pageUrl = "masterOption/customField";
        $this->data['tableHeader'] = getConfigDtHeader('customField');
        $this->load->view($this->custFieldIndex,$this->data);
    }

    public function getCustomFieldDTRows($type=1){
        $data = $this->input->post(); $data['type']=$type;
        $result = $this->configuration->getCustomFieldDTRows($data);
        $sendData = array();$i=($data['start'] + 1);
        foreach($result['data'] as $row):
            $row->sr_no = $i++;
            $sendData[] = getCustomFieldData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addCustomField(){
        $data = $this->input->post();
        $this->data['type'] = $data['type'];
        $this->data['nextIndex'] = $this->configuration->getNextFieldIndex(['type'=>$data['type']]);
        $this->load->view($this->custFieldForm,$this->data);
    }

    public function editCustomField(){
        $data = $this->input->post(); 
        $this->data['dataRow'] = $this->configuration->getCustomFieldDetail($data); 
        $this->load->view($this->custFieldForm,$this->data);
    }
    
	public function saveCustomField(){
        $postData = $this->input->post();
        $errorMessage = array();
        if(empty($postData['field_name'])){ $errorMessage['field_name'] = "Field is required."; }
        if(empty($postData['field_type'])){ $errorMessage['field_type'] = "Field type is required."; }

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->configuration->saveCustomField($postData));
        endif;

    }

    public function deleteCustomField(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->configuration->trash('udf',['id'=>$id]));
        endif;
    }  
	
	/*********************************************************************************** */
    /** Master Option */
    public function addMasterOption(){
		$postData = $this->input->post();
        $this->data['type'] = $postData['type'];       
		$this->data['optionRows'] = $this->getMasterListRows(['type'=>$postData['type']]);
        $this->load->view($this->form,$this->data);
    }

    public function save(){
        $postData = $this->input->post();
        $errorMessage = array();
       
        if(empty($postData['title'])){ $errorMessage['title'] = "Title is required.";}
        if(empty($postData['type'])){ $errorMessage['type'] = "Type is required.";}

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
			$result = $this->configuration->saveMasterOption($postData);
			$result['optionRows'] = $this->getMasterListRows(['type'=>$postData['type']]);
            $this->printJson($result);
        endif;
    }

    public function delete(){
		$postData = $this->input->post();
        $id = $postData['id'];
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
			$result = $this->configuration->trash('master_detail',['id'=>$id]);
			$result['optionRows'] = $this->getMasterListRows(['type'=>$postData['type']]);
            $this->printJson($result);
        endif;
    }
	
	public function getMasterListRows($param = []){
        $optionList = $this->configuration->getMasterList(['type'=>$param['type']]);
		$optionRows = '';$i=1;
		if(!empty($optionList))
		{
			foreach($optionList as $row)
			{
				$deleteParam = "{'postData':{'id' : ".$row->id.",'type' : ".$row->type."},'message' : 'Master Option'}";
				$deleteButton = '<button type="button" onclick="removeOptions('.$deleteParam.');" class="btn btn-sm btn-outline-danger waves-effect waves-light"><i class="mdi mdi-trash-can-outline"></i></button>';
				$optionRows .= '<tr>';
					$optionRows .= '<td>'.$i++.'</td>';
					$optionRows .= '<td>'.$row->title.'</td>';
					$optionRows .= '<td>'.$deleteButton.'</td>';
				$optionRows .= '<tr>';
			}
		}
        return $optionRows;
    }	
}
?>