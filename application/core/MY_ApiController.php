<?php 
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );

header('Content-Type:application/json');
if (isset($_SERVER['HTTP_ORIGIN'])):
    header("Access-Control-Allow-Origin:*");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
endif;

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS'):
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE,OPTIONS");
    
	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers:{$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    exit(0);
endif;

class MY_ApiController extends CI_Controller{
    
	public $gstPer = ['0'=>"NILL",'0.10'=>'0.10 %','0.25'=>"0.25 %",'1'=>"1 %",'3'=>"3%",'5'=>"5 %","6"=>"6 %","7.50"=>"7.50 %",'12'=>"12 %",'18'=>"18 %",'28'=>"28 %"];
	public $empRole = ["1"=>"Admin","2"=>"Production Manager","3"=>"Accountant","4"=>"Sales Manager","5"=>"Purchase Manager","6"=>"Employee"];
    public $gender = ["M"=>"Male","F"=>"Female","O"=>"Other"];
	public $gstRegistrationTypes = [1=>'Registerd',2=>'Composition',3=>'Overseas',4=>'Un-Registerd'];
    public $itemTypes = [1 => "Finish Good", 2 => "Raw Material", 5 => "Machine Master"];

	// CRM Status
	public $appointmentMode = [1 => "Phone", 2 => "Email", 3 => "Visit", 4 => "Other",5 => "Whatsapp"];

	public $appointmentIcon = ["Phone" => "ti ti-phone-call", "Email" => "ti ti-mail-forward", "Visit" => "fas fa-door-open", "Other" => "ti ti-flag", "IndiaMART"=>"fa fa-cart-plus" , "Facebook"=>"ti-facebook" , "Instagram"=>"ti-instagram", "LinkedIn"=>"ti-linkedin", "Whatsapp"=>"fab fa-whatsapp"];

	public $logClass = ['','lead-title','followup-title','reminder-title','enquiry-title','quotation-title','order-title','lost-title','won-title','request-title','asign-title','reopen-title','inactive-title','active-title'];

	public $logTitle = ['','CONGRATULATIONS!','FOLLOW UP','REMINDER','ENQUIRY','QUOTATION','ORDER','Ohh..No ! We Lost..&#128542;','Won','QUOTATION REQUEST','EXECUTIVE ASSIGNED','REOPEN LEAD','INACTIVE','ACTIVE','STATUS CHANGE','STATUS CHANGE','STATUS CHANGE','STATUS CHANGE','STATUS CHANGE','STATUS CHANGE','STATUS CHANGE','STATUS CHANGE','STATUS CHANGE','STATUS CHANGE','STATUS CHANGE','STATUS CHANGE','Visit'];
	
	public $iconClass = ['','las la-check-circle bg-soft-success','fas fa-comment-dots bg-soft-info','far fa-bell bg-soft-danger','fas fa-question-circle bg-soft-primary','fas fa-file-alt bg-soft-info','mdi mdi-cart-plus bg-soft-success','fas fa-frown bg-soft-dark','fas fa-hand-peace bg-soft-success','far fa-registered bg-soft-success','fas fa-user-check bg-soft-success','fas fa-comment-dots bg-soft-info','fas fa-comment-dots bg-soft-info','fas fa-comment-dots bg-soft-info','fas fa-comment-dots bg-soft-info','fas fa-comment-dots bg-soft-info','fas fa-comment-dots bg-soft-info','fas fa-comment-dots bg-soft-info','fas fa-comment-dots bg-soft-info','fas fa-comment-dots bg-soft-info','fas fa-comment-dots bg-soft-info','fas fa-comment-dots bg-soft-info','fas fa-comment-dots bg-soft-info','fas fa-comment-dots bg-soft-info','fas fa-comment-dots bg-soft-info','fas fa-comment-dots bg-soft-info','far fa-handshake'];

    public $notes = ["1"=>"New lead generated.", "2"=>"Follow up", "3"=>"Reminders", "4"=>"Enquiry Received With Ref. No. : ", "5"=>"Quotation Sent With Ref. No. : ", "6"=>"Order Received With Ref. No. : ", "7"=>"Lost Lead", "8"=>"Won Lead","9"=>"Quotation Request Sent With Ref. No. : ","10"=>"Executive Assigned",26=>"Visited"];
    
	public $leaveType = ["Casual Leave","Sick Leave","Marriage Leave","Maternity Leave","Paternity Leave","Study Leave"];

    public function __construct(){
        parent::__construct();
        $this->checkAuth();

        $this->data['headData'] = new StdClass;

        //Load Defualt Library
        $this->load->library('form_validation');

        //Load Models
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

		$this->setSessionVariables(["masterModel","dashboard","permission","configuration","usersModel","party","item","sales","service","expense","meeting","transMainModel","locationLog","visit","salesReportModel","leave"]);
    }

    public function setSessionVariables($modelNames){
        $headData = json_decode(base64_decode($this->input->get_request_header('sign')));

		$this->dates = $fyDate = explode(' AND ',$headData->financialYear);
        $this->shortYear = formatDate($fyDate[0],'y').'-'.formatDate($fyDate[1],'y');
        $this->startYear = formatDate($fyDate[0],'Y');
		$this->endYear = formatDate($fyDate[1],'Y');
		$this->startYearDate = $fyDate[0];
		$this->endYearDate = $fyDate[1];

        $this->CMID = $headData->CMID;
        $this->empPrefix = $headData->emp_prefix;

		$this->loginId = $headData->loginId;
		$this->userName = $headData->user_name;
		$this->userRole = $headData->role;
		$this->userRoleName = $headData->roleName;
		$this->superAuth = $headData->superAuth;
		$this->zoneId = $headData->zoneId;
		$this->leadRights = $headData->leadRights;

        $this->cyCode = n2y(date('Y')); // Current Year Code
		$this->cmCode = n2m(date('m')); // Current Month Code

		$models = $modelNames;
		foreach($models as $modelName):
			$modelName = trim($modelName);
			$this->{$modelName}->dates = $this->dates;
			$this->{$modelName}->shortYear = $this->shortYear;
			$this->{$modelName}->startYear = $this->startYear;
			$this->{$modelName}->endYear = $this->endYear;
			$this->{$modelName}->startYearDate = $this->startYearDate;
			$this->{$modelName}->endYearDate = $this->endYearDate;

            $this->{$modelName}->CMID = $this->CMID;			
			$this->{$modelName}->empPrefix = $this->empPrefix;

			$this->{$modelName}->loginId = $this->loginId;
			$this->{$modelName}->userName = $this->userName;
			$this->{$modelName}->userRole = $this->userRole;
			$this->{$modelName}->userRoleName = $this->userRoleName;
    		$this->{$modelName}->superAuth = $this->superAuth;
    		$this->{$modelName}->zoneId = $this->zoneId;
    		$this->{$modelName}->leadRights = $this->leadRights;
		
			$this->{$modelName}->cyCode = $this->cyCode;
			$this->{$modelName}->cmCode = $this->cmCode;
		endforeach;

		return true;
	}

    public function checkAuth(){
        if($token = $this->input->get_request_header('authToken')):
            $this->load->model('LoginModel','loginModel');
            $result = $this->loginModel->checkToken($token);

            if($result == 0):
                $this->printJson(['status'=>0,'message'=>"Unauthorized",'data'=>null],401);
            endif;

            if(!$this->input->get_request_header('sign')):
                $this->printJson(['status'=>0,'message'=>"Sign not found.",'data'=>null],401);
            endif;

            return true;  
        else:
            $this->printJson(['status'=>0,'message'=>"Unauthorized",'data'=>null],401);
        endif;
    }

    public function printJson($response,$headerStatus=200){
        $this->output->set_status_header($headerStatus)->set_content_type('application/json', 'utf-8')->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))->_display();
        exit;
	}

    public function callcURL($param = []){
	    $response = new StdClass;
	    if(isset($param['callURL']) AND (!empty($param['callURL']))):
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
	    endif;
        return $response;
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

	public function trashFiles(){
        /** define the directory **/
        $dirs = [
            realpath(APPPATH . '../assets/uploads/temp_files/')
        ];

        foreach($dirs as $dir):
            $files = array();
            $files = scandir($dir);
            unset($files[0],$files[1]);

            /*** cycle through all files in the directory ***/
            foreach($files as $file):
                /*** if file is 24 hours (86400 seconds) old then delete it ***/
                if(time() - filectime($dir.'/'.$file) > 86400):
                    unlink($dir.'/'.$file);
                    //print_r(filectime($dir.'/'.$file)); print_r("<hr>");
                endif;
            endforeach;
        endforeach;

        return true;
    }
}
?>