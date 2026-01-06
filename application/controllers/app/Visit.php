<?php
class Visit extends MY_Controller
{	
    private $visit_list = "app/visit_list";

	public function __construct(){
		parent::__construct();
		$this->isLoggedin();
		$this->data['headData']->pageTitle = "Visit";
		$this->data['headData']->controller = "app/visit";
		$this->data['headData']->pageUrl = "app/visit";    
	}
	
	public function index(){
        $this->visit->confirmVisit();
		$this->data['headData']->appMenu = "app/visit";
        $this->data['partyData'] = $this->party->getPartyList();
        $this->data['leadData'] = $this->party->getLeadList();
		$this->data['visitHtml'] = $this->getVisitData(['visit_status'=>1]);
        $this->data['bTypeList'] = $this->configuration->getBusinessTypeList();
        $this->data['leadStages'] = $this->configuration->getLeadStagesList(['not_in'=>[1,2]]);
        $this->data['visitTypeList'] = $this->configuration->getSelectOptionList(['type'=>4]); 
        $this->load->view($this->visit_list,$this->data);
    }

    public function getVisitData($parameter = []){
        $postData = !empty($parameter)?$parameter :  $this->input->post();
        $visitData = $this->visit->getVisitList(['visit_status'=>$postData['visit_status']]);
        $html = '';
        if(!empty($visitData)):
            $endBtn='';
            foreach($visitData as $row): 
                $duration = 0;
                if(!empty($row->end_at)){
                    $d1 = new DateTime($row->start_at);
                    $d2 = new DateTime($row->end_at);
                    $interval = $d1->diff($d2);
                    $diffInSeconds = $interval->s;
                    $diffInMinutes = $interval->i; 
                    $diffInHours   = $interval->h;
                    $duration=($diffInHours*60)+$diffInMinutes+($diffInSeconds/60);
                }
                $html .='<li class="grid_item listItem item transition position-static" data-category="transition">
                    <a href="javascript:void(0)" class="position-relative">
                        <div class="mb-2 me-2 btn btn-rounded btn-icon btn-primary"><i class="fa fa-user"></i></div>
                        <div class="media-content">
                            <div>
                                <h6 class="name">'.(!empty($row->party_name)?$row->party_name:'').'</h6>
                                <p class="my-1">'.(!empty($row->contact_person)?$row->contact_person:'').'</p>
                                <p class="my-1">'.(!empty($row->purpose)?$row->purpose:'').'</p>
                                <p class="my-1">'.(!empty($row->start_at )?date('d M Y H:i:s',strtotime($row->start_at)) :'').'</p>
                            </div>
                        </div>
                        <div class="left-content">   
                            '.(empty($row->end_at)?'<a class="btn btn-sm light btn-danger add-btn permission-modify endVisitBtn" data-form_title="End Visit" data-bs-toggle="offcanvas" data-bs-target="#endModel" data-lead_id="0"  data-party_type="1" data-id="'.$row->id.'" aria-controls="offcanvasBottom">End</a>':'<a  class="text-muted font-12"> '. number_format($duration,2).' MIN</a >');

                            if((!empty($row->end_at)) && ($row->approve_by <= 0) && (in_array($this->userRole,[1,-1]) || in_array($this->loginId,explode(',',$row->super_auth_id)))){
                                $approveParam = "{'postData': {'id' : ".$row->id."}, 'fnsave' : 'approveVisit', 'message' : 'Are you sure want to Approve this Visit?'}";
                                $html .= '<a class="btn btn-sm light btn-primary" href="javascript:void(0)"  onclick="confirmStore('.$approveParam.');"> Approve</a>';
                            }
                        $html .='</div>
                    </a>
                </li>';
                
            endforeach;
        endif;
        if(!empty($parameter)){  return $html; }
        else{ $this->printJson(['status' => 1, 'html' =>$html]); }
    }
	
	public function saveVisit(){
        $data = $this->input->post();
        $errorMessage = array();
		if(empty($data['party_name'])){
            $errorMessage['party_name'] = "Party is required.";
        } 
        if(empty($data['contact_person'])){
            $errorMessage['contact_person'] = "Contact Person is required.";
        }      
        if(empty($data['purpose'])){
            $errorMessage['purpose'] = "Purpose is required.";
        }
		if(empty($data['visit_type'])){
            $errorMessage['visit_type'] = "Visit Type is required.";
        }
      
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            /*if(!empty($_FILES['img_file'])):
                if($_FILES['img_file']['name'] != null || !empty($_FILES['img_file']['name'])):
                    $this->load->library('upload');
                    $_FILES['userfile']['name']     = $_FILES['img_file']['name'];
                    $_FILES['userfile']['type']     = $_FILES['img_file']['type'];
                    $_FILES['userfile']['tmp_name'] = $_FILES['img_file']['tmp_name'];
                    $_FILES['userfile']['error']    = $_FILES['img_file']['error'];
                    $_FILES['userfile']['size']     = $_FILES['img_file']['size'];
                    
                    $imagePath = realpath(APPPATH . '../assets/uploads/visit_log/');
                    $config = ['file_name' => $this->loginId."_V".time(),'allowed_types' => '*','max_size' => 10240,'upload_path' => $imagePath];
                    
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload()):
                        $errorMessage['img_file'] = $this->upload->display_errors();
                        $this->printJson(["status"=>0,"message"=>$errorMessage]);
                    else:
                        $uploadData = $this->upload->data();
                        $data['img_file'] = $uploadData['file_name'];
                    endif;
                endif;
            endif;*/

            $data['start_at'] = date("Y-m-d H:i:s");
            $data['start_location'] = ((!empty($data['s_lat']) AND !empty($data['s_lon'])) ? $data['s_lat'].','.$data['s_lon'] : NULL);
            unset($data['s_lat'],$data['s_lon']);
            if(!empty($data['start_location']))
    		{
    		    $add = $this->callcUrl(['callURL'=>'https://maps.googleapis.com/maps/api/geocode/json?latlng='.$data['start_location'].'&key='.GMAK]);
    		    $add = (!empty($add) ? json_decode($add) : new StdClass);
    		    $data['s_add'] = (isset($add->results[0]->formatted_address) ? $add->results[0]->formatted_address : "");
    		}
            
            $this->printJson($this->visit->save($data));
        endif;
    }

    public function saveEndVisit(){
        $data = $this->input->post();
        $errorMessage = array();
        if(empty($data['discussion_points'])){
            $errorMessage['discussion_points'] = "Discussion Point is required.";
        }
        if($data['next_visit'] == 'Yes'){
            if(empty($data['reminder_date'])){$errorMessage['reminder_date'] = "Reminder Date is required.";}
            if(empty($data['reminder_time'])){$errorMessage['reminder_time'] = "Reminder Time is required.";}
            if(empty($data['reminder_note'])){$errorMessage['reminder_note'] = "Reminder Note is required.";}
        }
      
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $data['id'] = $data['main_id'];
            $data['end_at'] = date("Y-m-d H:i:s");
            $data['end_location'] = ((!empty($data['e_lat']) AND !empty($data['e_lon'])) ? $data['e_lat'].','.$data['e_lon'] : NULL);
            $data['updated_by'] = $this->loginId;
            $data['updated_at'] = date('Y-m-d H:i:s');
            
            unset($data['e_lat'],$data['e_lon'],$data['main_id']);
            $data['e_add']='';
            if(!empty($data['end_location'])){
    		    $add = $this->callcUrl(['callURL'=>'https://maps.googleapis.com/maps/api/geocode/json?latlng='.$data['end_location'].'&key='.GMAK]);
    		    $add = (!empty($add) ? json_decode($add) : new StdClass);
    		    $data['e_add'] = (isset($add->results[0]->formatted_address) ? $add->results[0]->formatted_address : "");
    		}

            $this->printJson($this->visit->saveEndVisit($data));
        endif;
    }
	
    public function approveVisit(){
        $data = $this->input->post();
        if(empty($data['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->visit->approveVisit($data));
        endif;
    }
}
?>