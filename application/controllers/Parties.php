<?php
class Parties extends MY_Controller{
    private $index = "party/index";
    private $form = "party/form";
    private $other_party = "party/other_party";

    public function __construct(){
        parent::__construct();
		$this->data['headData']->pageTitle = "Party Master";
		$this->data['headData']->controller = "parties";    
        $this->data['headData']->pageUrl = "parties";    
    }

    public function index(){
        $this->data['tableHeader'] = getMasterDtHeader("customer");
        $this->load->view($this->index,$this->data);
    }

    public function getDTRows(){
        $data=$this->input->post(); 
        $result = $this->party->getPartyDTRows($data);
        $sendData = array();
        $i = ($data['start']+1);
        foreach ($result['data'] as $row) :
            $row->sr_no = $i++;
            $sendData[] = getPartyData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addParty(){
        $data = $this->input->post();
        $this->data['party_type'] = $party_type = (!empty($data['party_type'])?$data['party_type']:1);
        $this->data['countryData'] = $this->configuration->getCountries();
        $this->data['party_code'] = $this->getPartyCode($party_type);
        $this->data['salesExecutives'] = $this->usersModel->getEmployeeList();
        $this->data['salesZoneList'] = $this->configuration->getSalesZoneList();
        $this->data['bTypeList'] = $this->configuration->getBusinessTypeList();
        $this->data['sourceList'] = $this->configuration->getSelectOptionList(['type'=>1]);
        $this->data['discountList'] = $this->configuration->getDiscountData(['group_by'=>'structure_name']);
        $this->data['stateList'] = $this->configuration->getStatutoryDetail(['group_by'=>'state']);
        $this->data['areaList'] = [];
        $this->data['customFieldList'] = $this->configuration->getCustomFieldList(['type'=>2]);
        $this->data['masterDetailList'] = $this->configuration->getMasterList();
        $this->load->view($this->form, $this->data);
    }

    /* Auto Generate Party Code */
    public function getPartyCode($party_type=""){
        $partyType = (!empty($party_type))?$party_type:$this->input->post('party_type');
        $code = $this->party->getPartyCode($partyType);
        $prefix = "";
        if($partyType == 1):
            $prefix = "C";
            $code = $this->party->getPartyCode($partyType);
        elseif($partyType == 2):
            $prefix = "L";
            $code = $this->party->getLeadCode($partyType);
        endif;

        $party_code = $prefix.sprintf("%03d",$code);

        if(!empty($party_type)):
            return $party_code;
        else:
            $this->printJson(['status'=>1,'party_code'=>$party_code]);
        endif;
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

        if (empty($data['contact_phone']))
            $errorMessage['contact_phone'] = "Contact No. is required.";
        if (empty($data['gstin']) && in_array($data['registration_type'],[1,2]))
            $errorMessage['gstin'] = 'Gstin is required.';

        if (empty($data['sales_zone_id']))
            $errorMessage['sales_zone_id'] = 'Sales Zone is required.';

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
    				$config = ['file_name' => 'Party-'.time(),'allowed_types' => '*','max_size' => 10240,'overwrite' => FALSE, 'upload_path'	=>$imagePath];
    
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
            if(!empty($data['party_type']) && $data['party_type'] == 2){
                $this->printJson($this->party->saveLead($data));
            }else{
                $this->printJson($this->party->saveParty($data));
            }
        endif;
    }

    public function edit(){
        $data = $this->input->post();
        if(!empty($data['party_type']) && $data['party_type'] == 1){
            $result = $this->party->getParty($data);
            $bType = $this->configuration->getBusinessType(['type_name'=>$result->business_type]); 
            $this->data['parentOption']=$this->getParentType(['business_type'=>$bType->parentType,'sales_zone_id'=>$result->sales_zone_id,'parent_id'=>$result->parent_id]);
            $this->data['discountList'] = $this->configuration->getDiscountData(['group_by'=>'structure_name']); 
            $this->data['customFieldList'] = $this->configuration->getCustomFieldList(['type'=>2]); 
            $this->data['masterDetailList'] = $this->configuration->getMasterList(); 
        }else{
            $result = $this->party->getLead($data);
        }
        $this->data['dataRow'] = $result;
        $this->data['salesExecutives'] = $this->usersModel->getEmployeeList();
        $this->data['bTypeList'] = $this->configuration->getBusinessTypeList(); 
        $this->data['party_type'] = $result->party_type;
        $this->data['salesZoneList'] = $this->configuration->getSalesZoneList(); 
        $this->data['sourceList'] = $this->configuration->getSelectOptionList(['type'=>1]); 
        $this->data['stateList'] = $this->configuration->getStatutoryDetail(['group_by'=>'state']);
        $this->data['customData'] = $this->party->getPartyUdfData(['party_id'=>$data['id']]);
        
        $this->load->view($this->form, $this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if (empty($id)) :
            $this->printJson(['status' => 0, 'message' => 'Somthing went wrong...Please try again.']);
        else :
            $this->printJson($this->party->deleteParty($id));
        endif;
    }

    public function getPartyList(){
        $data = $this->input->post();
        $partyList = $this->party->getPartyList($data);
        $this->printJson(['status'=>1,'data'=>['partyList'=>$partyList]]);
    }

    public function getPartyDetails(){
        $data = $this->input->post();
        $partyDetail = $this->party->getParty($data);
        $this->printJson(['status'=>1,'data'=>['partyDetail'=>$partyDetail]]);
    }

    // Sales Executive
    public function getSeForSalesZoneList(){
        $data = $this->input->post();
        $zoneList = $this->configuration->getSalesZoneList($data); 
        $options = '<option value="">Sales Zone</option>';
        if(!empty($zoneList)){
            foreach($zoneList as $row){ 
                $options .= '<option value="'.$row->id.'">'.$row->zone_name.'</option>';
            }
        }
        $this->printJson(['status'=>1, 'options'=>$options]);
    }

    public function  getParentType($param = []){
        $data = $this->input->post();
        if(!empty($param)){ $data =  $param; }
        $partyList = $this->party->getPartyList(['business_type'=>$data['business_type'],'sales_zone_id'=>$data['sales_zone_id']]);
        $options = '<option value="">Select</option>';
        if(!empty($partyList)){
            foreach($partyList as $row){ 
                $selected = (!empty($data['parent_id']) && $data['parent_id'] == $row->id)?'selected':'';
                $options .= '<option value="'.$row->id.'" '.$selected.'>'.$row->party_name.'</option>';
            }
        }
        if(!empty($param)){
            return $options;
        }else{
            $this->printJson(['status'=>1, 'options'=>$options]);
        }
        
    }

    public function createUser(){
        $data = $this->input->post(); 
        if(empty($data['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->party->createUser($data));
        endif;
    }

    /** Statutory Drop Down Options  */
    public function getStatesOptions($postData=array()){
        $country_id = (!empty($postData['country_id']))?$postData['country_id']:$this->input->post('country_id');

        $result =  $this->configuration->getStatutoryDetail(['country_id'=>$country_id,'group_by'=>'state']);

        $html = '<option value="">Select State</option>';
        foreach ($result as $row) :
            $selected = (!empty($postData['state_id']) && $row->id == $postData['state_id']) ? "selected" : "";
            $html .= '<option value="' . $row->state . '" ' . $selected . '>' . $row->state . '</option>';
        endforeach;

        if(!empty($postData)):
            return $html;
        else:
            $this->printJson(['status'=>1,'result'=>$html]);
        endif;
    }

    public function getDistrictList($postData=array()){
        $state = (!empty($postData['state']))?$postData['state']:$this->input->post('state');
		
        $result =  $this->configuration->getStatutoryDetail(['state'=>$state,'group_by'=>'district']);
        
        $html = '<option value="">Select District</option>';
        foreach ($result as $row) :
            $selected = (!empty($postData['district']) && $row->district == $postData['district']) ? "selected" : "";
            $html .= '<option value="' . $row->district . '" '  . $selected . ' data-state="' . $row->state . '">' . $row->district . '</option>';
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
            $html .= '<option value="' . $row->id . '" ' . $selected . ' >' . $row->taluka . '</option>';
        endforeach;

        if(!empty($postData)):
            return $html;
        else:
            $this->printJson(['status'=>1,'talukaOption'=>$html]);
        endif;
    }
}
?>