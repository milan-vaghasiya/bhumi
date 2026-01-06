<?php
class Attendance extends MY_Controller{
    private $indexPage = "hr/attendance/index";
    private $aReport = "hr/attendance/attendance_report";
    private $attend_index = "hr/attendance/attend_index"; 
    private $monthlyAttendance = "hr/attendance/month_attendance";  
    private $manualAttendance = "hr/attendance/manual_attendance";
	
	public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Attendance";
		$this->data['headData']->controller = "hr/attendance";
		//$this->data['headData']->pageUrl = "hr/attendance";		
	}
	
	public function index(){
		$this->data['empList'] = $this->usersModel->getEmployeeList();
        $this->load->view($this->indexPage,$this->data);
    }
	
	public function attendanceReport(){
		$this->data['empList'] = $this->usersModel->getEmployeeList();
		$this->data['zoneList'] = $this->configuration->getSalesZoneList();
        $this->load->view($this->aReport,$this->data);
    }
	
    public function getAttendanceReport1(){
        $data = $this->input->post();
		$report_date = '';
		if(!empty($data))
		{
			$empAttendanceLog = $this->usersModel->getEmpPunchesByDate($data);
			if(!empty($empAttendanceLog))
			{
				$dateWisePunches = array_column($empAttendanceLog, 'punch_date');
				if(!empty($dateWisePunches[0]))
				{
					$empPunches = explode(',',$dateWisePunches[0]);
					//print_r('<pre>');
					print_r($empPunches);
				}
			}
			exit;
			$empTable = "";
			if(!empty($mpData))
			{
				foreach($mpData as $row):
					$empPunches = $row->punch_date; $allPunches = "";			
					if(!empty($empPunches))
					{
						$empPunches = explode(',',$empPunches);						
						$ap = Array();
						foreach($empPunches as $p){$ap[] = date("d-m-Y H:i:s",strtotime($p));}
						$allPunches = implode(', ',$ap);
					}
					 $imgFile = '';
            	    if(!empty($row->img_file)):
            	        $imgPath = base_url('assets/uploads/attendance_log/'.$row->img_file);
						$imgFile='<div class="picture-item" >
                            <a href="'.$imgPath.'" class="lightbox" >
                                <img src="'.$imgPath.'" alt="" class="img-fluid"  width="20" height="20"   style="border-radius:0%;border: 0px solid #ccc;padding:3px;"/>
                            </a> 
                            </div> ';
            		endif;
					$empTable .= '<tr>
                                    <td>'.$row->emp_code.'</td>
                                    <td>'.$row->emp_name.'</td>
                                    <td>'.$row->type.'</td>
                                    <td>'.$allPunches.'</td>
                                    <td class="text-wrap text-left">'.$row->loc_add.'</td>
                                    <td>'.$imgFile.'</td>
                                </tr>';
				endforeach;
				$this->printJson(['status'=>1,'tbody'=>$empTable]);
			}else{
				$this->printJson(['status'=>1,'tbody'=>""]);
			}
		}
	}
    
	public function getAttendanceReport($jsonData=''){
        if(!empty($jsonData)){$postData = (Array) decodeURL($jsonData);}
        else{$postData = $this->input->post();}
		
		$postData['attendance_status'] = 1;
		$postData['is_active'] = 1;
		$postData['is_zone'] = 1;
		$postData['from_date'] = (!empty($postData['from_date']) ? formatDate($postData['from_date'],'Y-m-d') : date('Y-m-d'));
		$postData['to_date'] = (!empty($postData['to_date']) ? formatDate($postData['to_date'],'Y-m-d') : date('Y-m-d'));
		
        $empData = $this->usersModel->getEmployeeList($postData);
		
        $lastDay = intVal(date('d',strtotime($postData['to_date'])));
        $thead='<tr style="background:#dddddd;">';
			$thead.='<th style="width:50px;">Code</th>';
			$thead.='<th style="width:220px;">Emp Name</th>';
			$thead.='<th>Department</th>';
			$thead.='<th>Designation</th>';
			$thead.='<th>Sales Zone</th>';
			$thead.='<th class="text-center">Date</th>';
			$thead.='<th class="text-center">Status</th>';
			$thead.='<th class="text-center">In Time</th>';
			$thead.='<th class="text-center">Out Time</th>';
			$thead.='<th class="text-center">WH</th>';
        $thead.='</tr>';  
       
        $empArray = array_reduce($empData, function($emp, $employee) {
            $emp[$employee->emp_name][] = $employee;
            return $emp;
        }, []);
		
		$begin = new DateTime($postData['from_date']);
		$end = new DateTime($postData['to_date']);
		$end = $end->modify( '+1 day' ); 
		
		$interval = new DateInterval('P1D');
		$dateRange = new DatePeriod($begin, $interval ,$end);
		
        $tbody='';$i=0;
        foreach($empData as $emp):
            $i++;
            
			$minLimitPerDay = 3600; // 1 Hour
			$hdLimit = 14400; // 4 Hour
            
            foreach($dateRange as $date):
				
				$currentDate =  date("Y-m-d",strtotime($date->format("Y-m-d")));
				$currentDay =  date("D",strtotime($date->format("Y-m-d")));
			
				$tbody.='<tr>';
				$tbody.='<td class="text-center" style="vertical-align:middle;">'.$emp->emp_code.'</td>';
				$tbody.='<td style="vertical-align:middle;" >'.$emp->emp_name.'</td>';
				$tbody.='<td style="vertical-align:middle;" >'.$emp->dept_name.'</td>';
				$tbody.='<td style="vertical-align:middle;" >'.$emp->emp_designation.'</td>';
				$tbody.='<td style="vertical-align:middle;" >'.$emp->zone_name.'</td>';
                
				$status="A"; $class="text-danger";$punchDates = array();$whRow='';
				$empAttendanceLog = $this->usersModel->getPunchByDate(['emp_id' => $emp->id,'from_date' => $currentDate,'to_date' => $currentDate]);
				$empPunches = array_column($empAttendanceLog, 'punch_date');
				$empPunches = sortDates($empPunches,'ASC');
				
				$t=1;$wph = Array();$idx=0;$stay_time=0;$twh = 0;$wh=0;$ot=0;$present_status = 'P';$punches = Array();
				foreach($empPunches as $punch)
				{
					$punches[]= date("H:i:s", strtotime($punch));
					$wph[$idx][]=strtotime($punch);
					if($t%2 == 0){$stay_time += floatVal($wph[$idx][1]) - floatVal($wph[$idx][0]);$idx++;}
					$t++;
				}
				$wh = $stay_time;
				if(!empty($punches[0]) AND $currentDate == date('Y-m-d')): 
					$status = "P";
					$class = "text-success";
                    if(empty($punches[1])): 
                        $status = "M";
					    $class = "text-success";
                    endif;
				else:
					if($wh >= $hdLimit):
						$status = "P";
						$class = "text-success";
					elseif($wh > 0 AND $wh < $minLimitPerDay):
						$status = "A";
						$class="text-danger";
					elseif($wh >= $minLimitPerDay AND $wh < $hdLimit):
						$status = "HD";
						$class = "text-info";
					endif;
				endif;
				
                if($currentDay == "Sun"){
					if($status == "A"){$status = "W";$class = "bg-light text-dark";}
                    if($status == "P"){$status = "WP";$class = "text-success";}
                    if($status == "HD"){$status = "W-HD";$class = "text-success";}
                    if($status == "L"){$status = "WL";}
                }
				
				if($wh > 0){ $whRow = '<td class="text-center">'.s2hi($wh).'</td>'; }
				else{ $whRow = '<td class="text-center"> - </td>'; }
				
				$tbody .= '<td class="text-center" style="">'.formatDate($currentDate,'d-m-Y').'</td>';
				$tbody .= '<td class="text-center '.$class.'" style="">'.$status.'</td>';
				$tbody .= '<td class="text-center" style="vertical-align:middle;" >'.(!empty($punches[0]) ? $punches[0] : '').'</td>';
				$tbody .= '<td class="text-center" style="vertical-align:middle;" >'.(!empty($punches[1]) ? $punches[1] : '').'</td>';
				$tbody .= $whRow;
				$tbody .= '</tr>';
            endforeach;
			
        endforeach;
        
        $reportTitle = 'Attendance Report';
        $report_date = $postData['from_date'].' to '.formatDate($postData['to_date'],'d-m-Y');
		$logo = base_url('assets/images/logo.png');

        $pdfData = '<table id="attendanceTable" class="table table-bordered itemList" repeat_header="1">
                        <thead class="thead-info" id="theadData">'.$thead.'</thead>
                        <tbody id="tbodyData">'.$tbody.'</tbody>
                    </table>';

                
        $htmlHeader = '<table class="table" style="border-bottom:1px solid #036aae;">
                        <tr>
                            <td class="text-uppercase text-left"><img src="'.$logo.'" class="img" style="height:30px;"></td>
                            <td class="org_title text-uppercase text-center" style="font-size:1.3rem;width:40%">'.$reportTitle.'</td>
                            <td class="text-uppercase text-right" style="font-size:0.8rem;width:30%">Date : '.$report_date.'</td>
                        </tr>
                    </table>';
        $htmlFooter = '<table class="table top-table" style="margin-top:10px;border-top:1px solid #545454;">
                    <tr>
                        <td style="width:50%;font-size:12px;">Printed On ' . date('d-m-Y') . '</td>
                        <td style="width:50%;text-align:right;font-size:12px;">Page No. {PAGENO}/{nbpg}</td>
                    </tr>
                </table>';

        if(!empty($data['file_type']) && $data['file_type'] == 'PDF')
        {
            $mpdf = new \Mpdf\Mpdf();
            $pdfFileName = 'AttendanceReport_'.str_replace(["/","-"],"_",date('d-m-Y')).'.pdf';          
            $stylesheet = file_get_contents(base_url('assets/css/pdf_style.css'));
            $mpdf->WriteHTML($stylesheet, 1);
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->SetTitle($reportTitle);
            $mpdf->SetHTMLHeader($htmlHeader);
            $mpdf->SetHTMLFooter($htmlFooter);
            $mpdf->AddPage('L','','','','',5,5,15,10,3,3,'','','','','','','','','','A4-L');
            $mpdf->WriteHTML($pdfData);	
            ob_clean();	
            $mpdf->Output($pdfFileName, 'D');
        } else { 
            $this->printJson(['status'=>1,'thead'=>$thead, 'tbody'=>$tbody]); 
        }
	}

    public function getDailyAttendance(){
        $data = $this->input->post();
		$report_date = '';
		if(!empty($data))
		{
			$mpData = $this->usersModel->getPunchByDate($data);
			$empTable = "";
			if(!empty($mpData))
			{
				foreach($mpData as $row):
					$empPunches = $row->punch_date; $allPunches = "";			
					if(!empty($empPunches))
					{
						$empPunches = explode(',',$empPunches);						
						$ap = Array();
						foreach($empPunches as $p){$ap[] = date("d-m-Y H:i:s",strtotime($p));}
						$allPunches = implode(', ',$ap);
					}
					 $imgFile = '';
            	    if(!empty($row->img_file)):
            	        $imgPath = base_url('assets/uploads/attendance_log/'.$row->img_file);
						$imgFile='<div class="picture-item" >
                            <a href="'.$imgPath.'" class="lightbox" >
                                <img src="'.$imgPath.'" alt="" class="img-fluid"  width="20" height="20"   style="border-radius:0%;border: 0px solid #ccc;padding:3px;"/>
                            </a> 
                            </div> ';
            		endif;
					$empTable .= '<tr>
                                    <td>'.$row->emp_code.'</td>
                                    <td>'.$row->emp_name.'</td>
                                    <td>'.$row->type.'</td>
                                    <td>'.$allPunches.'</td>
                                    <td class="text-wrap text-left">'.$row->loc_add.'</td>
                                    <td>'.$imgFile.'</td>
                                </tr>';
				endforeach;
				$this->printJson(['status'=>1,'tbody'=>$empTable]);
			}else{
				$this->printJson(['status'=>1,'tbody'=>""]);
			}
		}
	}

	public function attendanceIndex(){
		//$this->migrateHQ();exit;
        $this->data['tableHeader'] = getHrDtHeader('attendance');
        $this->load->view($this->attend_index,$this->data);
    }
	
	public function getDTRows($status=0){
        $data = $this->input->post(); $data['status']=$status;
        $result = $this->usersModel->getAttendanceDTRows($data);
		
        $sendData = array();$i=($data['start']+1);
		foreach($result['data'] as $row):
			$row->sr_no = $i++;
			//print_r('<pre>');
			//print_r($row->start_location.'~'.$row->hq_lat_lng);
			//print_r(' || ');
			$row->distance = (!empty($row->start_location) AND !empty($row->hq_lat_lng)) ? getDistanceOpt($row->start_location,$row->hq_lat_lng) : 'ERROR';
			//$row->hq_add = '';
			/*
			if(!empty($row->hq_lat_lng)):
				$add = $this->callcUrl(['callURL'=>'https://maps.googleapis.com/maps/api/geocode/json?latlng='.$row->hq_lat_lng.'&key='.GMAK]);
				$add = (!empty($add) ? json_decode($add) : new StdClass);
				$row->hq_add = (isset($add->results[0]->formatted_address) ? $add->results[0]->formatted_address : "");
			endif;
			*/
			$row->status = $data['status'];
			$sendData[] = getAttendanceData($row);
		endforeach;
		//exit;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addManualAttendence(){
		//$this->usersModel->insertAutoPunch();
		
        $this->data['empList'] = $this->usersModel->getEmployeeList();
        $this->load->view($this->manualAttendance,$this->data);
    }

    public function save(){
        $data = $this->input->post();
		$errorMessage = array();
        if(empty($data['emp_id'])){
			$errorMessage['emp_id'] = "Employee is required.";
        }
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $data['start_at'] = $data['punch_date'];
            $data['loc_add'] = 'MANUAL';
			$data['punch_type'] = 2;
            $data['attendance_date'] = formatDate($data['punch_date'],'Y-m-d');
            $data['approve_by'] = $this->loginId;
            $data['approve_at'] = date('Y-m-d H:i:s');
			$data['attendance_status'] = 1;
		
			$data['type'] = "IN";
			$punches = $this->usersModel->countEmpPunches(['emp_id'=>$data['emp_id'],'from_date'=>date("Y-m-d"),'to_date'=>date("Y-m-d")]);
			if(!empty($punches->total_punch))
			{
				if(($punches->total_punch % 2) == 0){ $data['type'] = "IN";}else{$data['type'] = "OUT";}
			}
			
            $this->printJson($this->usersModel->saveAttendance($data));
        endif;
    }

    public function approveAttendance(){
		$data = $this->input->post();
		if(empty($data['id'])):
			$this->printJson(['status'=>0,'message'=>'Something went wrong...Please try again.']);
		else:
			$this->printJson($this->usersModel->approveAttendance($data));
		endif;
	}

	/*
	public function confirmAttendance(){
		$data = $this->input->post();
		$this->printJson($this->usersModel->confirmAttendance($data));
	}
	*/

	public function confirmVisit(){
		$data = $this->input->post();
		$this->printJson($this->visit->confirmVisit($data));
	}

	public function getShiftListForEmployee(){
        $data = $this->input->post();
        $shiftList = $this->shiftModel->getShiftList();
        $options = '';
        foreach($shiftList as $row):
            $options .= '<option value="'.$row->id.'">'.$row->shift_name.'</option>';
        endforeach;

        $this->printJson(['status'=>1,'options'=>$options]);
    }

 	/* Monthly Attendance Report */
    public function monthlyAttendance(){
		$this->data['zoneList'] = $this->configuration->getSalesZoneList();
		$this->load->view($this->monthlyAttendance, $this->data);
	}
    
    public function getMonthlyReport1($jsonData=''){
        if(!empty($jsonData)){$data = (Array) decodeURL($jsonData);}
        else{$data = $this->input->post();}
        $empData = $this->usersModel->getMonthlyAttendance($data);

        $lastDay = intVal(date('t',strtotime($data['month'])));
        $thead='<tr style="background:#dddddd;"><th style="width:50px;">Code</th><th style="width:220px;">Emp Name</th>';
        for($d=1;$d<=$lastDay;$d++):	
            $thead.='<th class="text-center">'.$d.'</th>'; 
        endfor;
        
        $thead.='<th class="text-center">WP/WO</th>';
        $thead.='<th class="text-center">Present <br> Days</th>';
        $thead.='<th class="text-center">Leave</th>';
        $thead.='<th class="text-center">Absent <br> Days</th>';    
        $thead.='<th class="text-center">Total <br> Days</th>';
        $thead.='</tr>';  
       
        $empArray = array_reduce($empData, function($emp, $employee) {
            $emp[$employee->emp_name][] = $employee;
            return $emp;
        }, []);

        $tbody='';$i=0;
        foreach($empArray as $emp=>$employee):
            $i++;
            $tbody.='<tr>';
            $tbody.='<td class="text-center">'.$employee[0]->emp_code.'</td>';
            $tbody.='<td>'.$emp.'</td>';
            
            $totalDays = date("t",strtotime($data['month'])); 
            $holiday = countDayInMonth("Sunday",$data['month']);
            $totalDays -= $holiday; 
            $presentDays = 0;$absentDays = 0;$weekOff = 0;$wp = 0;$l = 0;
            
            for($d=1;$d<=$lastDay;$d++):
                
                $day=0; $text="A"; $class="bg-danger text-white";
                
                if(date("D",strtotime(date($d."-m-Y",strtotime($data['month'])))) == "Sun"){
                    if($text == "A"){$text = "W";}
                    if($text == "P"){$text = "WP";$wp++;$class = "text-success";}
                    if($text == "L"){$text = "WL";$wp++;}
                    $class = "bg-light text-dark";
                    $weekOff ++;
                    $day = 0;
                }else{
                    $text = "";
                    $punch_array = array_column($employee,'punch_date');
                    $leave_array = array_column($employee,'leave_date');

                    $date = date("Y-m-".str_pad($d,2,0,STR_PAD_LEFT),strtotime($data['month']));

                    if(in_array($date,$punch_array)){
                        $text = "P"; $class="bg-success text-white"; $day = 1;
                    }else{
                        if(in_array($date,$leave_array)){
                            $text = "L"; $class="bg-info text-white";
                            $l++;
                        }else{
                            $text = "A"; $class="bg-danger text-white";
                        }
                    }
                }                
                $tbody .= '<td class="text-center '.$class.'">'.$text.'</td>';
                $presentDays += $day;
            endfor;     

            $absentDays = (($totalDays - $presentDays) > 0)?($totalDays - $presentDays):0;
            $tbody .= '<td class="text-center" style="width:45px;">'.$wp.'/'.$weekOff.'</td>';
            $tbody .= '<td class="text-center" style="width:45px;">'.$presentDays.'</td>';
            $tbody .= '<td class="text-center" style="width:45px;">'.$l.'</td>';
            $tbody .= '<td class="text-center" style="width:45px;">'.$absentDays.'</td>';
            $tbody .= '<td class="text-center" style="width:45px;">'.$totalDays.'</td>'; 
            $tbody .= '</tr>';
        endforeach;
        
        $reportTitle = 'Attendance Report';
        $report_date = $data['month'].' to '.date('t-m-Y',strtotime($data['month']));
		$logo = base_url('assets/images/logo.png');

        $pdfData = '<table id="attendanceTable" class="table table-bordered itemList" repeat_header="1">
                        <thead class="thead-info" id="theadData">'.$thead.'</thead>
                        <tbody id="tbodyData">'.$tbody.'</tbody>
                    </table>';

                
        $htmlHeader = '<table class="table" style="border-bottom:1px solid #036aae;">
                        <tr>
                            <td class="text-uppercase text-left"><img src="'.$logo.'" class="img" style="height:30px;"></td>
                            <td class="org_title text-uppercase text-center" style="font-size:1.3rem;width:40%">'.$reportTitle.'</td>
                            <td class="text-uppercase text-right" style="font-size:0.8rem;width:30%">Date : '.$report_date.'</td>
                        </tr>
                    </table>';
        $htmlFooter = '<table class="table top-table" style="margin-top:10px;border-top:1px solid #545454;">
                    <tr>
                        <td style="width:50%;font-size:12px;">Printed On ' . date('d-m-Y') . '</td>
                        <td style="width:50%;text-align:right;font-size:12px;">Page No. {PAGENO}/{nbpg}</td>
                    </tr>
                </table>';

        if(!empty($data['file_type']) && $data['file_type'] == 'PDF')
        {
            $mpdf = new \Mpdf\Mpdf();
            $pdfFileName = 'AttendanceReport_'.str_replace(["/","-"],"_",date('d-m-Y')).'.pdf';          
            $stylesheet = file_get_contents(base_url('assets/css/pdf_style.css'));
            $mpdf->WriteHTML($stylesheet, 1);
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->SetTitle($reportTitle);
            $mpdf->SetHTMLHeader($htmlHeader);
            $mpdf->SetHTMLFooter($htmlFooter);
            $mpdf->AddPage('L','','','','',5,5,15,10,3,3,'','','','','','','','','','A4-L');
            $mpdf->WriteHTML($pdfData);	
            ob_clean();	
            $mpdf->Output($pdfFileName, 'D');
        } else { 
            $this->printJson(['status'=>1,'thead'=>$thead, 'tbody'=>$tbody]); 
        }
	}
 
    public function getMonthlyReport($jsonData=''){
        if(!empty($jsonData)){$data = (Array) decodeURL($jsonData);}
        else{$data = $this->input->post();}
		
		$data['attendance_status'] = 1;
		$data['is_active'] = 1;
		$data['is_zone'] = 1;
		$data['month'] = $data['year'].'-'.$data['month'].'-01';
        $empData = $this->usersModel->getEmployeeList($data);
		
        $lastDay = intVal(date('t',strtotime($data['month'])));
        
		$thead='<tr style="background:#dddddd;"><th style="width:50px;">Code</th><th style="width:220px;">Emp Name</th><th>Dept./Designation</th><th>Sales Zone</th>';
        
		for($d=1;$d<=$lastDay;$d++):	
            $thead.='<th colspan="2" class="text-center">'.$d.'</th>'; 
        endfor;
        
        $thead.='<th class="text-center">WP/WO</th>';
        $thead.='<th class="text-center">Present <br> Days</th>';
        $thead.='<th class="text-center">Leave</th>';
        $thead.='<th class="text-center">Absent <br> Days</th>';    
        $thead.='<th class="text-center">Total <br> Days</th>';  
        $thead.='<th class="text-center">Total <br> Late In</th>';  
        $thead.='<th class="text-center">Total <br> Early Out</th>';  
        $thead.='<th class="text-center">Total <br> OT</th>';  
        $thead.='<th class="text-center">Total <br> Working</th>';
        $thead.='</tr>';  
       
		
        $empArray = array_reduce($empData, function($emp, $employee) {
            $emp[$employee->emp_name][] = $employee;
            return $emp;
        }, []);
		
		$attedLog = $this->usersModel->getPunchByDateNew(['from_date' => formatDate($data['month'],'Y-m-01'),'to_date' => formatDate($data['month'],'Y-m-t')]);
		
		$attedLog = array_reduce($attedLog, function($log, $row) {
            $log[$row->emp_id][formatDate($row->attendance_date,'dmY')] = $row;
            return $log;
        }, []);
		
        $tbody='';$i=0;
        foreach($empData as $emp):
            $i++;
            $tbody.='<tr>';
            $tbody.='<td class="text-center" style="vertical-align:middle;" rowspan="2" >'.$emp->emp_code.'</td>';
            $tbody.='<td style="vertical-align:middle;" rowspan="2" >'.$emp->emp_name.'</td>';
            $tbody.='<td style="vertical-align:middle;" rowspan="2" ><small>'.$emp->dept_name.'<br>'.$emp->emp_designation.'</small></td>';
			$tbody.='<td style="vertical-align:middle;" rowspan="2" ><small>'.$emp->zone_name.'</small></td>';
            
            $totalDays = date("t",strtotime($data['month'])); 
            $holiday = countDayInMonth("Sunday",$data['month']);
            $totalDays -= $holiday; 
            $presentDays = 0;$absentDays = 0;$weekOff = 0;$hd = 0;$wp = 0;$l = 0;$punchRow='';$whRow=''; 
			$totalWh=$totalOT=$totalEarlyoutTime=$totalLateTime=0;
			$minLimitPerDay = 3600; // 1 Hour
			$hdLimit = 14400; // 4 Hour
            
            for($d=1;$d<=$lastDay;$d++):
                
                $day=0; $text="A"; $class="bg-danger text-white";$punchDates = array();$statusText = '';
				$dt = str_pad($d, 2, '0', STR_PAD_LEFT);
				$currentDate = date('Y-m-'.$dt,strtotime($data['month']));				
				$dayName = date("D", strtotime($currentDate));
				$dateKey = formatDate($data['month'],$dt.'mY');
				
				$t=1;$wph = Array();$idx=0;$stay_time=0;$twh = 0;$wh=0;$ot=0;$present_status = 'P';$punches = Array();
				
				$attendanceData = (isset($attedLog[$emp->id][$dateKey]) ? $attedLog[$emp->id][$dateKey] : []);
				
				if(!empty($attendanceData)){
					$empPunches = explode(',',$attendanceData->punch_date);
					$empPunches = sortDates($empPunches,'ASC');
					
					foreach($empPunches as $punch)
					{
						$punches[]= date("H:i:s", strtotime($punch));
						$wph[$idx][]= strtotime($punch);
						if($t%2 == 0){$stay_time += floatVal($wph[$idx][1]) - floatVal($wph[$idx][0]);$idx++;}
						$t++;
					}
					$wh = $stay_time;
					$totalWh += $wh;
				}
				
				if($wh >= $hdLimit){
					$day = 1;
					$text = "P";
					$class = "text-success";
				}elseif($wh > 0 AND $wh < $minLimitPerDay){
					$day = 0;
					$text = "A";
					$class="bg-danger text-white";
				}elseif($wh >= $minLimitPerDay AND $wh < $hdLimit){
					$day = 0.5;
					$text = "HD";$hd++;
					$class = "bg-info text-white";
				}
				
				if($wh > 0)
				{	
					$shift_start = (!empty($attendanceData->shift_start) ? $attendanceData->shift_start : '09:00:00');
					$shift_end = (!empty($attendanceData->shift_end) ? $attendanceData->shift_end : '09:00:00');
					$let_min = $attendanceData->let_in_time;
					$out_min = $attendanceData->early_out_time;
					
					$totalShiftTime = hi2s($shift_end) - hi2s($shift_start);
					$ot = $wh-$totalShiftTime;
					if($ot > 0){ $totalOT += $ot; }
					
					$as_start = hi2s($shift_start) + ($let_min*60);
					$as_end = hi2s($shift_end) - ($out_min*60);
				
					$timestamps = array_map('strtotime', $empPunches);

					// Find min and max timestamps
					$firstPunch = $empPunches[array_search(min($timestamps), $timestamps)];
					$lastPunch = $empPunches[array_search(max($timestamps), $timestamps)];
					
					if(!empty($firstPunch)){
						if($as_start < hi2s(formatDate($firstPunch,"H:i"))){
							$day = 1; $text = "LI"; $class = "text-warning";
							
							$lateTime = hi2s(formatDate($firstPunch,"H:i")) - $as_start;
							if($lateTime > 0){ $totalLateTime += $lateTime; }
						}
					}
					if(!empty($lastPunch)){
						if($as_end > hi2s(formatDate($lastPunch,"H:i"))){
							$day = 1; $class = "text-warning";
							$earlyTime = $as_end - hi2s(formatDate($lastPunch,"H:i"));
							if($earlyTime > 0){ $totalEarlyoutTime += $earlyTime; }
							
							if($text=='LI'){ $text = "LI-EO"; }else{ $text = "EO"; }
						}
					}
				}
				
                if(date("D",strtotime(date($d."-m-Y",strtotime($data['month'])))) == "Sun"){
					if($text == "A"){$text = "W";$class = "bg-light text-dark";}
                    if($text == "P"){$text = "WP";$wp++;$class = "bg-light-green text-dark";}
                    if($text == "HD"){$text = "W-HD";$wp++;$class = "bg-success text-white";}
                    if($text == "LI"){$text = "W-LI";$wp++;}
                    if($text == "EO"){$text = "W-EO";$wp++;}
                    $weekOff ++;
                    $day = 0;
                }
				
				if($wh > 0)
				{
					$punchRow .= '<td colspan="2" class="text-center "><small>'.implode(' - ',$punches).'</small></td>';
					$whRow = '<td class="text-center '.$class.'" style="width:50%;"><small>'.s2hi($wh).'</small></td>';
					
					$tbody .= '<td class="text-center '.$class.'" style="width:50%;">'.$text.'</td>';
					$tbody .= $whRow;
				}
				else
				{
					$punchRow .= '<td colspan="2" class="text-center '.$class.'"> - </td>';
					
					$tbody .= '<td colspan="2" class="text-center '.$class.'" style="min-width:115px;">'.$text.'</td>';
				}
                $presentDays += $day;
            endfor;
			
            $absentDays = (($totalDays - $presentDays) > 0)?($totalDays - $presentDays):0;
            $tbody .= '<td class="text-center" style="width:45px;vertical-align:middle;" rowspan="2" >'.$wp.'/'.$weekOff.'</td>';
            $tbody .= '<td class="text-center" style="width:45px;vertical-align:middle;" rowspan="2" >'.$presentDays.'</td>';
            $tbody .= '<td class="text-center" style="width:45px;vertical-align:middle;" rowspan="2" >'.$l.'</td>';
            $tbody .= '<td class="text-center" style="width:45px;vertical-align:middle;" rowspan="2" >'.$absentDays.'</td>';
            $tbody .= '<td class="text-center" style="width:45px;vertical-align:middle;" rowspan="2" >'.$totalDays.'</td>'; 
            $tbody .= '<td class="text-center" style="width:45px;vertical-align:middle;" rowspan="2" >'.s2hi($totalLateTime).'</td>'; 
            $tbody .= '<td class="text-center" style="width:45px;vertical-align:middle;" rowspan="2" >'.s2hi($totalEarlyoutTime).'</td>'; 
            $tbody .= '<td class="text-center" style="width:45px;vertical-align:middle;" rowspan="2" >'.s2hi($totalOT).'</td>'; 
            $tbody .= '<td class="text-center" style="width:45px;vertical-align:middle;" rowspan="2" >'.s2hi($totalWh).'</td>'; 
            $tbody .= '</tr>';
			
			$tbody .= '<tr>'.$punchRow.'</tr>';
        endforeach;
        
        $reportTitle = 'Attendance Report';
        $report_date = $data['month'].' to '.date('t-m-Y',strtotime($data['month']));
		$logo = base_url('assets/images/logo.png');

        $pdfData = '<table id="attendanceTable" class="table table-bordered itemList" repeat_header="1">
                        <thead class="thead-info" id="theadData">'.$thead.'</thead>
                        <tbody id="tbodyData">'.$tbody.'</tbody>
                    </table>';

                
        $htmlHeader = '<table class="table" style="border-bottom:1px solid #036aae;">
                        <tr>
                            <td class="text-uppercase text-left"><img src="'.$logo.'" class="img" style="height:30px;"></td>
                            <td class="org_title text-uppercase text-center" style="font-size:1.3rem;width:40%">'.$reportTitle.'</td>
                            <td class="text-uppercase text-right" style="font-size:0.8rem;width:30%">Date : '.$report_date.'</td>
                        </tr>
                    </table>';
        $htmlFooter = '<table class="table top-table" style="margin-top:10px;border-top:1px solid #545454;">
                    <tr>
                        <td style="width:50%;font-size:12px;">Printed On ' . date('d-m-Y') . '</td>
                        <td style="width:50%;text-align:right;font-size:12px;">Page No. {PAGENO}/{nbpg}</td>
                    </tr>
                </table>';

        if(!empty($data['file_type']) && $data['file_type'] == 'PDF')
        {
            $mpdf = new \Mpdf\Mpdf();
            $pdfFileName = 'AttendanceReport_'.str_replace(["/","-"],"_",date('d-m-Y')).'.pdf';          
            $stylesheet = file_get_contents(base_url('assets/css/pdf_style.css'));
            $mpdf->WriteHTML($stylesheet, 1);
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->SetTitle($reportTitle);
            $mpdf->SetHTMLHeader($htmlHeader);
            $mpdf->SetHTMLFooter($htmlFooter);
            $mpdf->AddPage('L','','','','',5,5,15,10,3,3,'','','','','','','','','','A4-L');
            $mpdf->WriteHTML($pdfData);	
            ob_clean();	
            $mpdf->Output($pdfFileName, 'D');
        }elseif(!empty($data['file_type']) && $data['file_type'] == 'excel'){
            $pdfData = '<table id="attendanceTable" class="table table-bordered itemList" repeat_header="1" border="1">
                            <thead class="thead-info" id="theadData">'.$thead.'</thead>
                            <tbody id="tbodyData">'.$tbody.'</tbody>
                        </table>';
            $xls_filename='AttendanceReport_'.str_replace(["/","-"],"_",date('d-m-Y')).'.xls';        
										
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment; filename='.$xls_filename);
			header('Pragma: no-cache');
			header('Expires: 0');
	
			echo $pdfData; exit;
        } else { 
            $this->printJson(['status'=>1,'thead'=>$thead, 'tbody'=>$tbody]); 
        }
	}

}
?>