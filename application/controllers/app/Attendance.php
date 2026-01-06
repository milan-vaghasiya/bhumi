<?php
class Attendance extends MY_Controller
{	
    private $attendance_view = "app/attendance_view";
    //private $distanceApi = 'https://maps.googleapis.com/maps/api/distancematrix/json?destinations=New%20York%20City%2C%20NY&origins=Washington%2C%20DC%7CBoston&units=imperial&key=YOUR_API_KEY';

	public function __construct(){
		parent::__construct();
		$this->isLoggedin();
		$this->data['headData']->pageTitle = "Attendance";
		$this->data['headData']->controller = "app/attendance";
		$this->data['headData']->pageUrl = "app/attendance";
	}
	
	public function index(){
        //$this->usersModel->confirmAttendance();
		$this->data['headData']->appMenu = "app/attendance";
        $this->data['empData'] = $this->usersModel->getEmployeeData();
        $this->data['logData'] = $this->usersModel->getEmpLogData();
		$this->data['todayPunch'] = $this->usersModel->getTodayPunchData(['count'=>1]);
        $this->data['hqLocation'] = $this->usersModel->getEmployee(['id'=>$this->loginId]);
        $this->load->view($this->attendance_view,$this->data);
    }
	
	public function saveAttendance(){
        $data = $this->input->post();

        if(!empty($_FILES['img_file'])):
            if($_FILES['img_file']['name'] != null || !empty($_FILES['img_file']['name'])):
                $this->load->library('upload');
                $_FILES['userfile']['name']     = $_FILES['img_file']['name'];
                $_FILES['userfile']['type']     = $_FILES['img_file']['type'];
                $_FILES['userfile']['tmp_name'] = $_FILES['img_file']['tmp_name'];
                $_FILES['userfile']['error']    = $_FILES['img_file']['error'];
                $_FILES['userfile']['size']     = $_FILES['img_file']['size'];
                
                $imagePath = realpath(APPPATH . '../assets/uploads/attendance_log/');
                $config = ['file_name' => $this->loginId."_".$data['type']."_".$_FILES['userfile']['name'],'allowed_types' => '*','max_size' => 10240,'overwrite' => FALSE, 'upload_path' => $imagePath];

                $this->upload->initialize($config);
                if (!$this->upload->do_upload()):
                    $errorMessage['img_file'] = $this->upload->display_errors();
                    $this->printJson(["status"=>0,"message"=>$errorMessage]);
                else:
                    $uploadData = $this->upload->data();
                    $data['img_file'] = $uploadData['file_name'];
                endif;
            endif;
        endif;

        $data['emp_id'] = $this->loginId;
        $data['punch_date'] = date("Y-m-d H:i:s");
        $data['start_at'] = date("Y-m-d H:i:s");
        $data['start_location'] = ((!empty($data['s_lat']) AND !empty($data['s_lon'])) ? $data['s_lat'].','.$data['s_lon'] : NULL);
        unset($data['s_lat'],$data['s_lon']);
        $data['loc_add']='';
        if(!empty($data['start_location']))
		{
		    $add = $this->callcUrl(['callURL'=>'https://maps.googleapis.com/maps/api/geocode/json?latlng='.$data['start_location'].'&key='.GMAK]);
		    $add = (!empty($add) ? json_decode($add) : new StdClass);
		    $data['loc_add'] = (isset($add->results[0]->formatted_address) ? $add->results[0]->formatted_address : "");
		}

		$hqLocation = $this->usersModel->getEmployee(['id'=>$this->loginId]);
        $distance=2;
        if(!empty($hqLocation->hq_location) && !empty($data['start_location'])){
            $distance = getDistanceOpt($hqLocation->hq_location,$data['start_location']);
        }
        if($distance <= 1){
            $data['approve_by'] = $this->loginId;
            $data['approve_at'] = date('Y-m-d H:i:s');
        }
        $this->printJson($this->usersModel->saveAttendance($data));
    }
}
?>