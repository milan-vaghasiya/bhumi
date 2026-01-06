<?php
class Attendance extends MY_ApiController{	

	public function __construct(){
		parent::__construct();
        $this->data['headData']->pageTitle = "Attendance";
        $this->data['headData']->pageUrl = "api/attendance";
        $this->data['headData']->base_url = base_url();
	}
	
	public function getEmployeeDetail(){
        $this->data['employeeDetail'] = $this->usersModel->getEmployeeData();
        $this->printJson(['status'=>1,'message'=>'Data Found.','data'=>$this->data]);
    }

    public function getAttendanceList(){
        $data = $this->input->post();
        $logData = $this->usersModel->getAttendanceList($data);

        // Columns to remove
        $columnsToRemove = ['emp_id','attendance_date','start_at','approve_by','approve_at','notes','attendance_status','start_location','img_file','created_by','created_at','updated_at','updated_by','is_delete','cm_id'];
        
        // Remove columns from result
        $logData = array_map(function($row) use ($columnsToRemove) {
            $row->distance=2;
            if(!empty($row->hq_location) AND !empty($row->start_location)){
                $row->distance = getDistanceOpt($row->hq_location,$row->start_location);
            }
            $row->punch_date = date('d-m-Y h:i:s A',strtotime($row->punch_date));
            $row->lat_long = $row->start_location;
            $row->type = $row->in_out_flag;
            $row->img_file_path = base_url('/assets/uploads/attendance_log/'.((!empty($row->img_file))?$row->img_file:"user_default.png"));
            return array_diff_key((array) $row, array_flip($columnsToRemove));
        }, $logData);

        $this->printJson(['status'=>1,'data'=>['warn_msg'=>"You have reached Max. Allowable(3) Missed Punch!",'dataList'=>$logData]]);
    }

	public function saveAttendance(){
        $data = $this->input->post();
		
        $data['start_location'] = ((!empty($data['s_lat']) AND !empty($data['s_lon'])) ? $data['s_lat'].','.$data['s_lon'] : "");
        unset($data['s_lat'],$data['s_lon']);
        $data['loc_add']='';

        if(!empty($data['start_location'])):
		    $add = $this->callcUrl(['callURL'=>'https://maps.googleapis.com/maps/api/geocode/json?latlng='.$data['start_location'].'&key='.GMAK]);
		    $add = (!empty($add) ? json_decode($add) : new StdClass);
		    $data['loc_add'] = (isset($add->results[0]->formatted_address) ? $add->results[0]->formatted_address : "");
		endif;

        if(!empty($_FILES['img_file'])):
            if($_FILES['img_file']['name'] != null || !empty($_FILES['img_file']['name'])):
                $this->load->library('upload');
                $_FILES['userfile']['name']     = $_FILES['img_file']['name'];
                $_FILES['userfile']['type']     = $_FILES['img_file']['type'];
                $_FILES['userfile']['tmp_name'] = $_FILES['img_file']['tmp_name'];
                $_FILES['userfile']['error']    = $_FILES['img_file']['error'];
                $_FILES['userfile']['size']     = $_FILES['img_file']['size'];
                
                $imagePath = realpath(APPPATH . '../assets/uploads/attendance_log/');
                $config = ['file_name' => $this->loginId."_".$data['type']."_".time(),'allowed_types' => '*','max_size' => 10240,'overwrite' => FALSE, 'upload_path' => $imagePath];

                $this->upload->initialize($config);
                if (!$this->upload->do_upload()):
                    $errorMessage['img_file'] = $this->upload->display_errors();
                    $this->printJson(["status"=>0,"message"=>$errorMessage]);
                else:
                    $uploadData = $this->upload->data();
					
					$image_path = realpath(APPPATH . '../assets/uploads/attendance_log/'.$uploadData['file_name']);
					
					//$caption_text = $data['loc_add'];
					if(!empty($data['loc_add'])){ $this->add_caption_to_image($image_path, $data['loc_add'].' | Time : '.date("j M Y H:i A")); }
					
                    $data['img_file'] = $uploadData['file_name'];
                endif;
            endif;
        endif;

        $data['emp_id'] = $this->loginId;
        $data['punch_type'] = 4;
        $data['attendance_date'] = date("Y-m-d");
        $data['punch_date'] = date("Y-m-d H:i:s");
		
		$hqLocation = $this->usersModel->getEmpHq(['id'=>$this->loginId,'emp_id'=>$this->loginId]);
		$distance=2; $data['quarter_id'] = 0;
		
		$data['shift_id'] = (!empty($hqLocation->shift_id)) ? $hqLocation->shift_id : 1;
		
		if(!empty($hqLocation->hq_location)){
			$data['hq_lat_lng'] = (!empty($hqLocation->hq_location) ? $hqLocation->hq_location : '');
			$data['hq_add'] = (!empty($hqLocation->hq_add) ? $hqLocation->hq_add : '');
			$data['quarter_id'] = $hqLocation->quarter_id;
		}
		
		/*
        if(!empty($hqLocation->hq_location) && !empty($data['start_location'])){
            $distance = getDistanceOpt($hqLocation->hq_location,$data['start_location']);
        }
		
        if($distance <= 1){
            $data['approve_by'] = $this->loginId;
            $data['approve_at'] = date('Y-m-d H:i:s');
            $data['attendance_status'] = 1;
        }
		*/
		
		$data['type'] = "IN";
		$punches = $this->usersModel->countEmpPunches(['emp_id'=>$this->loginId,'from_date'=>date("Y-m-d"),'to_date'=>date("Y-m-d")]);
		if(!empty($punches->total_punch))
		{
			if(($punches->total_punch % 2) == 0){ $data['type'] = "IN";}else{$data['type'] = "OUT";}
		}
		
		if(empty($data['emp_id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
			$this->printJson($this->usersModel->saveAttendance($data));
        endif;
    }

	public function saveAutoPunchReason(){
        $data = $this->input->post();
		
		if(empty($data['id'])):
            $this->printJson(['status'=>0,'message'=>'PUNCH ID NOT FOUND']);
        else:
			$this->printJson($this->usersModel->saveAutoPunchReason($data));
        endif;
    }

    public function changeAttendanceStatus(){
        $data = $this->input->post();
        if (empty($data['id'])) :
            $this->printJson(['status' => 0, 'message' => 'Somthing went wrong...Please try again.']);
        else :
            $this->printJson($this->usersModel->approveAttendance($data));
        endif;
    }
	
	public function add_caption_to_image($file_path, $caption, $align = 'center') {
        $font_path = realpath(APPPATH . '../assets/css/verdana.ttf'); // Ensure this path is correct
        $font_size = 70;
		$margin = 27;
		$line_spacing = 32;
		//$white = imagecolorallocate($img, 255, 255, 255);
		
		$img = imagecreatefromjpeg($file_path);
		//$img = imagerotate($img, -90, $white);
		$width = imagesx($img);
		$height = imagesy($img);

		$text_color = imagecolorallocate($img, 255, 255, 255);
		$bg_color = imagecolorallocatealpha($img, 0, 0, 0, 50); // semi-transparent

		$wrapped_text = $this->wrap_text($caption, $font_size, $font_path, $width - 2 * $margin);
		$lines = explode("\n", $wrapped_text);
		$text_height = count($lines) * ($font_size + $line_spacing) + $margin;

		// Draw background
		imagefilledrectangle($img, 0, $height - $text_height, $width, $height, $bg_color);

		// Draw text line by line with alignment
		$y = $height - $text_height + $font_size + 27;
		foreach ($lines as $line) {
			$bbox = imagettfbbox($font_size, 0, $font_path, $line);
			$text_width = $bbox[2] - $bbox[0];

			// Calculate x-position based on alignment
			switch ($align) {
				case 'left':
					$x = $margin;
					break;
				case 'right':
					$x = $width - $text_width - $margin;
					break;
				case 'center':
				default:
					$x = ($width - $text_width) / 2;
					break;
			}

			imagettftext($img, $font_size, 0, $x, $y, $text_color, $font_path, $line);
			$y += $font_size + $line_spacing;
		}
		
		//$img = imagerotate($img, 90, $white);
		imagejpeg($img, $file_path);
		imagedestroy($img);
		//imagedestroy($rotatedImg);
    }

    public function wrap_text($text, $font_size, $font_path, $max_width) {
        $words = explode(' ', $text);
        $line = '';
        $wrapped_text = '';

        foreach ($words as $word) {
            $test_line = $line . ' ' . $word;
            $box = imagettfbbox($font_size, 0, $font_path, $test_line);
            $line_width = $box[2] - $box[0];

            if ($line_width > $max_width && $line !== '') {
                $wrapped_text .= trim($line) . "\n";
                $line = $word;
            } else {
                $line = $test_line;
            }
        }

        $wrapped_text .= trim($line);
        return $wrapped_text;
    }
	
	
}
?>