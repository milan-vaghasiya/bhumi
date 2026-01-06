<?php
class Meeting extends MY_Controller{
    private $index = "meeting/index";
    private $form = "meeting/form";
    private $event_index = "meeting/event_index";
    private $event_form = "meeting/event_form";
    private $participate_form = "meeting/participate_form";

    public function __construct(){
		parent::__construct();
		$this->data['headData']->controller = "meeting";
        $this->data['headData']->pageUrl = "meeting";
	}
	
	/*********************************************************************************** */
    /** Meeting */
	public function index(){
		$this->data['headData']->pageTitle = "Meeting";
        $this->data['tableHeader'] = getMasterDtHeader($this->data['headData']->controller);
        $this->data['type'] = 1;
        $this->load->view($this->index,$this->data);        
    }
	
    public function getDTRows($type= 0,$status = 0){
        $data = $this->input->post(); 
        $data['status'] = $status;
        $data['type'] = $type;
        $result = $this->meeting->getDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):          
            $row->sr_no = $i++;         
            $sendData[] = getMeetingData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addMeeting($type = 0){
		$data = $this->input->post();
        if($type == 1){
            $this->load->view($this->form, $this->data);
        }else{
            $this->load->view($this->event_form, $this->data);
        }
    }

    public function save(){
        $data = $this->input->post();
		$errorMessage = array();

        if(empty($data['me_type']))
			$errorMessage['me_type'] = "Type is required.";
        if($data['trans_type'] == 2 && empty($data['event_name'])){
            $errorMessage['event_name'] = "Event Name is required.";
        }
			
        if($data['me_date'] < (date('Y-m-d')))
			$errorMessage['me_date'] = "Invalid Date";

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            
            $this->printJson($this->meeting->save($data));
        endif;
    }

    public function edit(){     
        $data = $this->input->post();
        $this->data['dataRow'] = $this->meeting->getMeeting($data);
        if($this->data['dataRow']->trans_type == 1){
            $this->load->view($this->form, $this->data);
        }else{
            $this->load->view($this->event_form, $this->data);
        }
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->meeting->trash('meeting_event',['id'=>$id]));
        endif;
    }

	/*********************************************************************************** */
    /** Event */
    public function eventIndex(){
		$this->data['headData']->pageTitle = "Event";
        $this->data['tableHeader'] = getMasterDtHeader('eventIndex');
        $this->data['type'] = 2;
        $this->load->view($this->event_index,$this->data);
    }

    public function addParticipate(){
        $data = $this->input->post();
        $this->data['id'] = $data['id'];
        $this->data['empData'] = $this->usersModel->getEmployeeList();
        $this->load->view($this->participate_form,$this->data);
    }

    public function saveParticipate(){
        $data = $this->input->post();
		$errorMessage = array();

        if(empty($data['emp_id']))
			$errorMessage['emp_id'] = "Employee is required.";

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:      
            $data['emp_id'] = implode(',',$data['emp_id']);
            $this->printJson($this->meeting->save($data));
        endif;
    }

    public function changeMeetStatus(){
		$data = $this->input->post();
		
		if(empty($data['id'])):
			$this->printJson(['status'=>0,'message'=>'Something went wrong...Please try again.']);
		else:
			$this->printJson($this->meeting->changeMeetStatus($data));
		endif;
	}
}
?>