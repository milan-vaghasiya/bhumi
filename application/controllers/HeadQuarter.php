<?php
class HeadQuarter extends MY_Controller{
    private $index = "head_quarter/index";
    private $form = "head_quarter/form";

    public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Head Quarter";
		$this->data['headData']->controller = "headQuarter";
        $this->data['headData']->pageUrl = "headQuarter";
	}
	
	public function index(){
        $this->data['tableHeader'] = getConfigDtHeader($this->data['headData']->controller);
        $this->load->view($this->index,$this->data);
    }
	
    public function getDTRows(){
        $data = $this->input->post();
        $result = $this->configuration->getHeadQuarterDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):          
            $row->sr_no = $i++;         
            $sendData[] = getHeadQuarterData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addHeadQuarter(){
		$data = $this->input->post();
        $this->load->view($this->form, $this->data);
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
			$data['hq_lat_lng'] = trim(((!empty($data['hq_lat']) AND !empty($data['hq_long'])) ? $data['hq_lat'].','.$data['hq_long'] : ""));
			$data['hq_add']='';
			if(!empty($data['hq_lat_lng'])):
				$add = $this->callcUrl(['callURL'=>'https://maps.googleapis.com/maps/api/geocode/json?latlng='.$data['hq_lat_lng'].'&key='.GMAK]);
				$add = (!empty($add) ? json_decode($add) : new StdClass);
				
				$data['hq_add'] = (isset($add->results[0]->formatted_address) ? $add->results[0]->formatted_address : "");
			endif;
			
			$this->printJson($this->configuration->saveHeadQuarter($data));
        endif;
    }

    public function edit(){     
        $data = $this->input->post();
        $this->data['dataRow'] = $this->configuration->getHeadQuarter($data);
        $this->load->view($this->form, $this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->configuration->trash('head_quarter',['id'=>$id]));
        endif;
    }
}
?>