<?php
class Visit extends MY_ApiController{
    public function __construct(){
		parent::__construct();
        $this->data['headData']->pageTitle = "Visit";
        $this->data['headData']->pageUrl = "api/visit";
        $this->data['headData']->base_url = base_url();
	}

    public function getVisitList(){
        $postData = $this->input->post();
        $visitData = $this->visit->getVisitList($postData);

        $this->printJson(['status'=>1,'data'=>['dataList'=>$visitData]]);
    }

    public function addVisit(){
        $visitTypeList = $this->configuration->getSelectOptionList(['type'=>4]);

        // Columns to keep
        $columnsToKeep = ['id', 'label'];
        // Retain only specific columns in the result
        $visitTypeList = array_map(function($row) use ($columnsToKeep) {
            return array_intersect_key((array) $row, array_flip($columnsToKeep));
        }, $visitTypeList);

        $this->data['visitTypeList'] = $visitTypeList;
		
		$vehicalList = $this->configuration->getSelectOptionList(['type'=>5]);
		/*
        // Columns to keep
        $vehicolToKeep = ['id', 'label', 'price_km'];
        // Retain only specific columns in the result
        $vehicalList = array_map(function($row) use ($vehicolToKeep) {
            return array_intersect_key((array) $row, array_flip($vehicolToKeep));
        }, $vehicalList);
		*/

        $this->data['vehicalList'] = $vehicalList;
		
        $this->printJson(['status'=>1,'message'=>'Data Found.','data'=>$this->data]);
    }

    public function saveVisit(){
        $data = $this->input->post();
        $errorMessage = array();

		if(empty($data['party_name']))
            $errorMessage['party_name'] = "Party is required.";
        if(empty($data['contact_person']))
            $errorMessage['contact_person'] = "Contact Person is required.";
        if(empty($data['purpose']))
            $errorMessage['purpose'] = "Purpose is required.";
		if(empty($data['visit_type']))
            $errorMessage['visit_type'] = "Visit Type is required.";
		if(empty($data['vehicle_id']))
            $errorMessage['vehicle_id'] = "Vehical Type is required.";
      
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            
            $data['start_location'] = ((!empty($data['s_lat']) AND !empty($data['s_lon'])) ? $data['s_lat'].','.$data['s_lon'] : NULL);
            unset($data['s_lat'],$data['s_lon']);
            
            $data['s_add'] = "";
            if(!empty($data['start_location'])):
    		    $add = $this->callcUrl(['callURL'=>'https://maps.googleapis.com/maps/api/geocode/json?latlng='.$data['start_location'].'&key='.GMAK]);
    		    $add = (!empty($add) ? json_decode($add) : new StdClass);
    		    $data['s_add'] = (isset($add->results[0]->formatted_address) ? $add->results[0]->formatted_address : "");
    		endif;
			
			$vehicleArr = explode('~',$data['vehicle_id']);
			$data['vehicle_id'] = (!empty($vehicleArr[0]) ? $vehicleArr[0] : 0);
			$data['price_km'] = (!empty($vehicleArr[1]) ? $vehicleArr[1] : 0);
            $data['start_at'] = date('Y-m-d H:i:s');
            $this->printJson($this->visit->save($data));
        endif;
    }

    public function saveEndVisit(){
        $data = $this->input->post(); $data['next_visit'] = "No";$data['party_type'] = 0; $data['reminder_date'] = $data['reminder_time'] = $data['reminder_note'] = '';
        $errorMessage = array();
        if(empty($data['discussion_points']))
            $errorMessage['discussion_points'] = "Discussion Point is required.";

        if($data['next_visit'] == 'Yes'):
            if(empty($data['reminder_date'])): $errorMessage['reminder_date'] = "Reminder Date is required."; endif;
            if(empty($data['reminder_time'])): $errorMessage['reminder_time'] = "Reminder Time is required."; endif;
            if(empty($data['reminder_note'])): $errorMessage['reminder_note'] = "Reminder Note is required."; endif;
        endif;
      
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $data['created_at'] = date("Y-m-d H:i:s");
            $data['end_location'] = ((!empty($data['e_lat']) AND !empty($data['e_lon'])) ? $data['e_lat'].','.$data['e_lon'] : NULL);
            $data['updated_by'] = $this->loginId;
            $data['updated_at'] = date('Y-m-d H:i:s');            
            unset($data['e_lat'],$data['e_lon']);

            $data['e_add']='';
            if(!empty($data['end_location'])):
    		    $add = $this->callcUrl(['callURL'=>'https://maps.googleapis.com/maps/api/geocode/json?latlng='.$data['end_location'].'&key='.GMAK]);
    		    $add = (!empty($add) ? json_decode($add) : new StdClass);
    		    $data['e_add'] = (isset($add->results[0]->formatted_address) ? $add->results[0]->formatted_address : "");
    		endif;

            $this->printJson($this->visit->saveEndVisit($data));
        endif;
    }
}
?>