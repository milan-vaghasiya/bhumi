<?php
class StatutoryDetail extends MY_Controller{
    private $index = "statutory_detail/index";
    private $state_index = "statutory_detail/state_index";
    private $district_index = "statutory_detail/district_index";
    private $taluka_index = "statutory_detail/taluka_index";
    private $form = "statutory_detail/form";

    public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Statutory Detail";
		$this->data['headData']->controller = "statutoryDetail";
        $this->data['headData']->pageUrl = "statutoryDetail";
	}
	
    /* Country List */
	public function index(){
        $this->data['tableHeader'] = getConfigDtHeader('country');
        $this->load->view($this->index,$this->data);
    }
	
    public function getDTRows(){
        $data = $this->input->post();
        $result = $this->configuration->getCountriesDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):          
            $row->sr_no = $i++;         
            $sendData[] = getCountryData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    /* State List */
	public function stateIndex(){
        $this->data['type'] = 'State';
        $this->data['tableHeader'] = getConfigDtHeader('state');
        $this->load->view($this->state_index,$this->data);
    }
	
    public function getStateDTRows($type=""){
        $data = $this->input->post(); $data['type'] = $type;
        $result = $this->configuration->getStatutoryDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):          
            $row->sr_no = $i++;         
            $sendData[] = getStateData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    /* District List */
	public function districtIndex(){
        $this->data['type'] = 'District';
        $this->data['tableHeader'] = getConfigDtHeader('district');
        $this->load->view($this->district_index,$this->data);
    }
	
    public function getDistrictDTRows($type=""){
        $data = $this->input->post(); $data['type'] = $type;
        $result = $this->configuration->getStatutoryDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):          
            $row->sr_no = $i++;         
            $sendData[] = getDistrictData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    /* Taluka List */
	public function talukaIndex(){
        $this->data['type'] = 'Taluka';
        $this->data['tableHeader'] = getConfigDtHeader('taluka');
        $this->load->view($this->taluka_index,$this->data);
    }
	
    public function getTalukaDTRows($type=""){
        $data = $this->input->post(); $data['type'] = $type;
        $result = $this->configuration->getStatutoryDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):          
            $row->sr_no = $i++;         
            $sendData[] = getTalukaData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }
    /* End Listing */

    public function addStatutoryDetail($type=""){
        $this->data['type'] = $type;
        $this->data['countryData'] = $this->configuration->getCountries();
        $this->data['stateData'] = $this->configuration->getStatutoryDetail(['group_by'=>'state']);
        $this->data['districtData'] = $this->configuration->getStatutoryDetail(['group_by'=>'district']);
        $this->load->view($this->form, $this->data);
    }

    public function save(){
        $data = $this->input->post();
		$errorMessage = array();

        if(!empty($data['type'])){
            if (empty($data['country_id'])){
                $errorMessage['country_id'] = 'Country is required.';
            }
            if (empty($data['state'])){
                $errorMessage['state'] = 'State is required.';
            }
            if (empty($data['state_code'])){
                $errorMessage['state_code'] = 'State Code is required.';
            }
            if($data['type'] == 'District'){
                if (empty($data['district'])){
                    $errorMessage['district'] = 'District is required.';
                }
            }
            if($data['type'] == 'Taluka'){
                if (empty($data['district'])){
                    $errorMessage['district'] = 'District is required.';
                }
                if (empty($data['taluka'])){
                    $errorMessage['taluka'] = 'Taluka is required.';
                }
            }
        }
        else{
            if (empty($data['name'])){
                $errorMessage['name'] = 'Country is required.';
            }
        }

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:         
            $this->printJson($this->configuration->saveStatutory($data));
        endif;
    }

    public function edit(){     
        $data = $this->input->post();
        $this->data['type'] = $data['type'];
        $this->data['countryData'] = $this->configuration->getCountries();
        $this->data['stateData'] = $this->configuration->getStatutoryDetail(['group_by'=>'state']);
        $this->data['districtData'] = $this->configuration->getStatutoryDetail(['group_by'=>'district']);
        if(!empty($data['type'])){
            $this->data['dataRow'] = $this->configuration->getStatutoryDetail(['id'=>$data['id'], 'single_row'=>1]);
        }
        else{
            $this->data['dataRow'] = $this->configuration->getCountry(['id'=>$data['id']]);
        }
        $this->load->view($this->form, $this->data);
    }

    public function delete(){
        $data = $this->input->post();
        if(empty($data['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $tableName = 'countries';
            if(!empty($data['type'])){
                $tableName = 'statutory_detail';
            }else{
                $tableName = 'countries';
            }
            $this->printJson($this->configuration->trash($tableName,['id'=>$data['id']]));
        endif;
    }
}
?>