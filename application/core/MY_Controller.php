<?php 
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
class MY_Controller extends CI_Controller{
	
	public $gstPer = ['0'=>"NILL",'0.10'=>'0.10 %','0.25'=>"0.25 %",'1'=>"1 %",'3'=>"3%",'5'=>"5 %","6"=>"6 %","7.50"=>"7.50 %",'12'=>"12 %",'18'=>"18 %",'28'=>"28 %"];	
	public $empRole = ["1"=>"Admin","2"=>"Devloper","3"=>"Tester","4"=>"HR","5"=>"Customer","6"=>"Employee",'']; 
    public $gender = ["M"=>"Male","F"=>"Female","O"=>"Other"];
	public $gstRegistrationTypes = [4=>'Un-Registerd',1=>'Registerd',2=>'Composition',3=>'Overseas'];
	public $itemTypes = [1 => "Finish Good", 2 => "Raw Material", 5 => "Machine Master"];

	// CRM Status
	public $appointmentMode = [1 => "Phone", 2 => "Email", 3 => "Visit", 4 => "Other",5 => "Whatsapp"];

	public $appointmentIcon = ["Phone" => "ti ti-phone-call", "Email" => "ti ti-mail-forward", "Visit" => "fas fa-door-open", "Other" => "ti ti-flag", "IndiaMART"=>"fa fa-cart-plus" , "Facebook"=>"ti-facebook" , "Instagram"=>"ti-instagram", "LinkedIn"=>"ti-linkedin", "Whatsapp"=>"fab fa-whatsapp"];

	public $logClass = ['','lead-title','followup-title','reminder-title','enquiry-title','quotation-title','order-title','lost-title','won-title','request-title','asign-title','reopen-title','inactive-title','active-title'];

	public $logTitle = ['','CONGRATULATIONS!','FOLLOW UP','REMINDER','ENQUIRY','QUOTATION','ORDER','Ohh..No ! We Lost..&#128542;','Won','QUOTATION REQUEST','EXECUTIVE ASSIGNED','REOPEN LEAD','INACTIVE','ACTIVE','STATUS CHANGE','STATUS CHANGE','STATUS CHANGE','STATUS CHANGE','STATUS CHANGE','STATUS CHANGE','STATUS CHANGE','STATUS CHANGE','STATUS CHANGE','STATUS CHANGE','STATUS CHANGE','STATUS CHANGE','Visit'];
	
	public $iconClass = ['','las la-check-circle bg-soft-success','fas fa-comment-dots bg-soft-info','far fa-bell bg-soft-danger','fas fa-question-circle bg-soft-primary','fas fa-file-alt bg-soft-info','mdi mdi-cart-plus bg-soft-success','fas fa-frown bg-soft-dark','fas fa-hand-peace bg-soft-success','far fa-registered bg-soft-success','fas fa-user-check bg-soft-success','fas fa-comment-dots bg-soft-info','fas fa-comment-dots bg-soft-info','fas fa-comment-dots bg-soft-info','fas fa-comment-dots bg-soft-info','fas fa-comment-dots bg-soft-info','fas fa-comment-dots bg-soft-info','fas fa-comment-dots bg-soft-info','fas fa-comment-dots bg-soft-info','fas fa-comment-dots bg-soft-info','fas fa-comment-dots bg-soft-info','fas fa-comment-dots bg-soft-info','fas fa-comment-dots bg-soft-info','fas fa-comment-dots bg-soft-info','fas fa-comment-dots bg-soft-info','fas fa-comment-dots bg-soft-info','far fa-handshake'];

    public $notes = ["1"=>"New lead generated.", "2"=>"Follow up", "3"=>"Reminders", "4"=>"Enquiry Received With Ref. No. : ", "5"=>"Quotation Sent With Ref. No. : ", "6"=>"Order Received With Ref. No. : ", "7"=>"Lost Lead", "8"=>"Won Lead","9"=>"Quotation Request Sent With Ref. No. : ","10"=>"Executive Assigned",26=>"Visited"];
	
	public $holidayType = ['PH'=>"PH - Public Holiday", 'NH'=>"NH - National Holiday", 'SH'=>"SH - Special Holiday", 'WO'=>"WO - Week Off"];
	public $bloodGroups = ["A Positive", "A Negative", "B Positive", "B Negative", "O Positive", "O Negative", "AB Positive", "AB Negative"];
	public $recSource = ["Direct (Walking)", "Employee Referrals", "Job Fair", "Recruitment Agencies", "Campus Recruitment", "Company Website", "Social Media", "Former Employees", "Transfers"];
	public $interviewType = ['','New Joining','Application Received','Document Verification','Technical Round Clear','HR Round Clear','Offered','Rejected','Employee has Left'];
	public $rejType = ['','','Application Received','Document Verification','Technical Interview','HR Interview','Offered','Rejected','Employee has Left'];
	public $leaveType = ["Casual Leave","Sick Leave","Marriage Leave","Maternity Leave","Paternity Leave","Study Leave"];
	
	public $salesHierarchy = ["State Cordinator","State Manager","Area Sales Manager","District Cordinator","Sr. Officer","Field Officer"];
	
	public function __construct(){
		parent::__construct();
		//echo '<br><br><hr><h1 style="text-align:center;color:red;">We are sorry!<br>Your ERP is Updating New Features</h1><hr><h2 style="text-align:center;color:green;">Thanks For Co-operate</h1>';exit;
		$this->isLoggedin();
		$this->data['headData'] = new StdClass;
		$this->load->library('form_validation');
		
		$this->load->model('masterModel');
		$this->load->model('DashboardModel','dashboard');
		$this->load->model('PermissionModel','permission');

		/* Configration Models */
		$this->load->model('ConfigurationModel','configuration');

		/* User Models */
		$this->load->model('usersModel','usersModel');

		/* Party Master Models */
		$this->load->model('PartyModel','party');

		/* Item Master Models */
		$this->load->model('ItemModel','item');

		/* Sales Model */
		$this->load->model('SalesModel','sales');

		/* Service Model */
		$this->load->model('ServiceModel','service');

		/* Expense Manager Model */
		$this->load->model('ExpenseModel','expense');

		/* Meeting & Event Model */
		$this->load->model('meetingModel','meeting');

		/* Master Model */
		$this->load->model('TransactionMainModel','transMainModel');
		$this->load->model('LocationLogModel','locationLog');
		$this->load->model('VisitModel','visit'); 

		/* Report Model */
		$this->load->model('report/SalesReportModel','salesReportModel');
		
		/* Leave Model */
		$this->load->model('LeaveModel','leave');
		$this->load->model("ShiftModel","shiftModel");
		
		/*Recruitment Model*/
		$this->load->model('SkillMasterModel','skillMaster');
		$this->load->model("EmployeeCategoryModel","employeeCategory");

		$this->setSessionVariables(["masterModel","dashboard","permission","configuration","usersModel","party","item","sales","service","expense","meeting","transMainModel","locationLog","visit","salesReportModel","leave","skillMaster","employeeCategory","shiftModel"]);
	}

	public function setSessionVariables($modelNames){
		$this->data['dates'] = $this->dates = explode(' AND ',$this->session->userdata('financialYear'));
        $this->data['shortYear'] = $this->shortYear = date('y',strtotime($this->dates[0])).'-'.date('y',strtotime($this->dates[1]));
		$this->data['startYear'] = $this->startYear = date('Y',strtotime($this->dates[0]));
		$this->data['endYear'] = $this->endYear = date('Y',strtotime($this->dates[1]));
		$this->data['startYearDate'] = $this->startYearDate = date('Y-m-d',strtotime($this->dates[0]));
		$this->data['endYearDate'] = $this->endYearDate = date('Y-m-d',strtotime($this->dates[1]));
		$this->CMID = $this->data['CMID'] = $this->session->userdata('CMID');
		
		$this->loginId = $this->session->userdata('loginId');
		$this->userName = $this->session->userdata('user_name');
		$this->userRole = $this->session->userdata('role');
		$this->userRoleName = $this->session->userdata('roleName');
		$this->superAuth = $this->session->userdata('superAuth');
		$this->zoneId = $this->session->userdata('zoneId');
		$this->leadRights = $this->session->userdata('leadRights');
		$this->empPrefix = $this->data['empPrefix'] = $this->session->userdata('emp_prefix');

		$this->RTD_STORE = $this->session->userdata('RTD_STORE');
		
		$this->data['cyCode'] = $this->cyCode = n2y(date('Y')); // Current Year Code
		$this->data['cmCode'] = $this->cmCode = n2m(date('m')); // Current Month Code

		$models = $modelNames;
		foreach($models as $modelName):
			$modelName = trim($modelName);
			$this->{$modelName}->dates = $this->dates;
			$this->{$modelName}->shortYear = $this->shortYear;
			$this->{$modelName}->startYear = $this->startYear;
			$this->{$modelName}->endYear = $this->endYear;
			$this->{$modelName}->startYearDate = $this->startYearDate;
			$this->{$modelName}->endYearDate = $this->endYearDate;

			$this->{$modelName}->loginId = $this->loginId;
			$this->{$modelName}->userName = $this->userName;
			$this->{$modelName}->userRole = $this->userRole;
			$this->{$modelName}->userRoleName = $this->userRoleName;
    		$this->{$modelName}->superAuth = $this->superAuth;
    		$this->{$modelName}->zoneId = $this->zoneId;
    		$this->{$modelName}->leadRights = $this->leadRights;
			$this->{$modelName}->CMID = $this->CMID;
			
			$this->{$modelName}->empPrefix = $this->empPrefix;

			$this->{$modelName}->RTD_STORE = $this->RTD_STORE;
		
			$this->{$modelName}->cyCode = $this->cyCode;
			$this->{$modelName}->cmCode = $this->cmCode;
		endforeach;
		return true;
	}
	
	public function isLoggedin(){
		$URL = base_url(uri_string());
		$BASEURL = base_url();
		if (strpos($URL, '/app/') !== false) { 
			$BASEURL = base_url('app');
		}
		if(!$this->session->userdata("loginId")):
			echo '<script>window.location.href="'.$BASEURL.'";</script>';
		endif;
		return true;
	}
	
	public function printJson($data){
		print json_encode($data);exit;
	}
	
	public function checkGrants($url){
		$empPer = $this->session->userdata('emp_permission');
		if(!array_key_exists($url,$empPer)):
			redirect(base_url('error_403'));
		endif;
		return true;
	}
	
	/**** Generate QR Code ****/
	public function getQRCode($qrData,$dir,$file_name){
		if(isset($qrData) AND isset($file_name)):
			$file_name .= '.png';
			/* Load QR Code Library */
			$this->load->library('ciqrcode');
			
			if (!file_exists($dir)) {mkdir($dir, 0775, true);}

			/* QR Configuration  */
			$config['cacheable']    = true;
			$config['imagedir']     = $dir;
			$config['quality']      = true;
			$config['size']         = '1024';
			$config['black']        = array(255,255,255);
			$config['white']        = array(255,255,255);
			$this->ciqrcode->initialize($config);
	  
			/* QR Data  */
			$params['data']     = $qrData;
			$params['level']    = 'L';
			$params['size']     = 10;
			$params['savename'] = FCPATH.$config['imagedir']. $file_name;
			
			$this->ciqrcode->generate($params);

			return $dir. $file_name;
		endif;

		return false;
	}

	public function getTableHeader(){
		$data = $this->input->post();

		$response = call_user_func_array($data['hp_fn_name'],[$data['page']]);
		
		$result['theads'] = (isset($response[0])) ? $response[0] : '';
		$result['textAlign'] = (isset($response[1])) ? $response[1] : '';
		$result['srnoPosition'] = (isset($response[2])) ? $response[2] : 1;
		$result['sortable'] = (isset($response[3])) ? $response[3] : '';

		$this->printJson(['status'=>1,'data'=>$result]);
	}

	public function getMonthListFY(){
		$monthList = array();
		$start    = (new DateTime($this->startYearDate))->modify('first day of this month');
        $end      = (new DateTime($this->endYearDate))->modify('first day of next month');
        $interval = DateInterval::createFromDateString('1 month');
        $period   = new DatePeriod($start, $interval, $end);
        $i=0;
        foreach ($period as $dt) {
            $monthList[$i]['val'] = $dt->format("Y-m-d");
            $monthList[$i++]['label'] = $dt->format("F-Y");
        }
		return $monthList;
	}

	public function callcURL($param = []){
	    $response = new StdClass;
	    if(isset($param['callURL']) AND (!empty($param['callURL'])))
	    {
    	    $curl = curl_init();
    
            curl_setopt_array($curl, array(
              CURLOPT_URL => $param['callURL'],
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'GET',
            ));
            
            $response = curl_exec($curl);
            
            curl_close($curl);
	    }
        return $response;
	}
}
?>