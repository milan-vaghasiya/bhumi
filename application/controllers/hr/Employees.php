<?php
class Employees extends MY_Controller{
    private $indexPage = "hr/employee/index";
    private $employeeForm = "hr/employee/form";
	private $recruitmentIndex = "hr/employee/recruitment_index";
    private $docVerifyForm = "hr/employee/document_form";
    private $skillForm = "hr/employee/skill_form";
    private $appointedForm = "hr/employee/appointed_form";
	private $reasonForm = "hr/employee/reason_form";
	private $logDetails = "hr/employee/log_details";

    public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Users";
		$this->data['headData']->controller = "hr/employees";   
        $this->data['headData']->pageUrl = "hr/employees";
	}

    public function index(){        
        $this->data['tableHeader'] = getHrDtHeader('employees');
        $this->load->view($this->indexPage,$this->data);
    }

    public function getDTRows($status=0){
        $data = $this->input->post(); $data['status']=$status;
        $result = $this->usersModel->getEmployeeDTRows($data);
		
        $sendData = array();$i=($data['start']+1);
		foreach($result['data'] as $row):
			$row->sr_no = $i++; 
			$row->emp_role = $this->empRole[$row->emp_role];
			$sendData[] = getEmployeeData($row);
		endforeach;
		
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addEmployee(){
		$this->data['status'] = 1;
        $this->data['roleList'] = $this->empRole;
        $this->data['genderList'] = $this->gender;
        $this->data['designationList'] = $this->usersModel->getDesignations();
		$this->data['departmentList'] = $this->usersModel->getDepartmentList();
        $this->data['zoneList'] = $this->configuration->getSalesZoneList();
        $this->data['authList'] = $this->usersModel->getEmployeeList();
        $this->data['quarterList'] = $this->configuration->getHeadQuarterList(); 
		$this->data['empCategoryList'] = $this->employeeCategory->getEmployeeCategoryList();
        $this->data['shiftList'] = $this->shiftModel->getShiftList(); 
        $this->load->view($this->employeeForm,$this->data);
    }

    public function save(){
        $data = $this->input->post();
        $errorMessage = array();
        if(empty($data['emp_name']))
            $errorMessage['emp_name'] = "Employee name is required.";
        if(empty($data['emp_code']))
            $errorMessage['emp_code'] = "Emp. Code is required.";
        if(empty($data['emp_role']))
            $errorMessage['emp_role'] = "Role is required.";
        if(empty($data['emp_contact']))
            $errorMessage['emp_contact'] = "Contact No. is required.";
        if(empty($data['quarter_id']))
            $errorMessage['quarter_id'] = "Head Quarter is required.";
		if(empty($data['emp_dept_id']))
            $errorMessage['emp_dept_id'] = "Department is required.";
		if(empty($data['emp_designation']))
            $errorMessage['emp_designation'] = "Designation is required.";
		if(empty($data['shift_id']))
            $errorMessage['shift_id'] = "Shift is required.";
        
        if(empty($data['id'])):
            $data['emp_password'] = "123456";
        endif;
		unset($data[0]);
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $data['zone_id'] = (!empty($data['zone_id']) ? implode(',',$data['zone_id']) : "");
            $data['auth_id'] = (!empty($data['auth_id']) ? implode(',',$data['auth_id']) : "");
            $data['emp_name'] = ucwords($data['emp_name']);      
            $this->printJson($this->usersModel->saveEmployee($data));
        endif;
    }

    public function edit(){
        $data = $this->input->post();
        $this->data['roleList'] = $this->empRole;
        $this->data['genderList'] = $this->gender;
		$this->data['departmentList'] = $this->usersModel->getDepartmentList();
        $this->data['designationList'] = $this->usersModel->getDesignations();
        $this->data['dataRow'] = $this->usersModel->getEmployee($data);
        $this->data['zoneList'] = $this->configuration->getSalesZoneList();
        $this->data['authList'] = $this->usersModel->getEmployeeList();
        $this->data['quarterList'] = $this->configuration->getHeadQuarterList(); 
		$this->data['empCategoryList'] = $this->employeeCategory->getEmployeeCategoryList();
        $this->data['shiftList'] = $this->shiftModel->getShiftList();
        $this->load->view($this->employeeForm,$this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $checkData['columnName'] = ['created_by','updated_by'];
            $checkData['value'] = $id;
            $checkUsed = $this->usersModel->checkUsage($checkData);

            if($checkUsed == true):
                return ['status'=>0,'message'=>'The Shift is currently in use. you cannot delete it.'];
            endif;
            $this->printJson($this->usersModel->trash('employee_master',['id'=>$id]));
        endif;
    }

    public function activeInactive(){
        $postData = $this->input->post();
        if(empty($postData['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->usersModel->activeInactive($postData));
        endif;
    }
    
    public function changePassword(){
        $data = $this->input->post();
        $errorMessage = array();

        if(empty($data['old_password']))
            $errorMessage['old_password'] = "Old Password is required.";
        if(empty($data['new_password']))
            $errorMessage['new_password'] = "New Password is required.";
        if(empty($data['cpassword']))
            $errorMessage['cpassword'] = "Confirm Password is required.";
        if(!empty($data['new_password']) && !empty($data['cpassword'])):
            if($data['new_password'] != $data['cpassword'])
                $errorMessage['cpassword'] = "Confirm Password and New Password is Not match!.";
        endif;

        if(!empty($errorMessage)):
			$this->printJson(['status'=>0,'message'=>$errorMessage]);
		else:
            $data['id'] = $this->loginId;
			$result =  $this->usersModel->changePassword($data);
			$this->printJson($result);
		endif;
    }

    public function resetPassword(){
        $data = $this->input->post();
        if(empty($data['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->usersModel->resetPassword($data['id']));
        endif;
    }

	/*created By @Raj 27-12-2024*/
	public function newApplication(){
        $this->data['tableHeader'] = getHrDtHeader('recruit');
		$this->data['is_status'] = 2;
		$this->data['is_approve'] = 3;
		$this->data['heading'] = "New Application";
        $this->load->view($this->recruitmentIndex,$this->data);
    }
	
	public function docVerify(){
		$this->data['tableHeader'] = getHrDtHeader('recruit');
		$this->data['is_status'] = 3;
		$this->data['is_approve'] = 4;
		$this->data['heading'] = "Document Verification";
        $this->load->view($this->recruitmentIndex,$this->data);
	}
	
	public function techInterview(){
        $this->data['tableHeader'] = getHrDtHeader('recruit');
		$this->data['is_status'] = 4;
		$this->data['is_approve'] = 5;
		$this->data['heading'] = "Technical Interview";
        $this->load->view($this->recruitmentIndex,$this->data);
	}
	
	public function hrInterview(){
        $this->data['tableHeader'] = getHrDtHeader('recruit');
		$this->data['is_status'] = 5;
		$this->data['is_approve'] = 6;
		$this->data['heading'] = "HR Interview";
        $this->load->view($this->recruitmentIndex,$this->data);
	}
	
	public function appointed(){
        $this->data['tableHeader'] = getHrDtHeader('recruit');
		$this->data['is_status'] = 6;
		$this->data['is_approve'] = 1;
		$this->data['heading'] = "Appointed";
        $this->load->view($this->recruitmentIndex,$this->data);
	}
	
	public function rejApplication(){
        $this->data['tableHeader'] = getHrDtHeader('recruitRej');
		$this->data['is_status'] = 7;
		$this->data['is_approve'] = 8;
		$this->data['heading'] = "Rejected";
        $this->load->view($this->recruitmentIndex,$this->data);
	}

    public function getRecDTRows($status=2,$is_approve=0){
        $data = $this->input->post(); $data['status']=$status;
        $result = $this->usersModel->getEmployeeDTRows($data);
        $sendData = array();$i=($data['start']+1);
		
		foreach($result['data'] as $row):
			$row->sr_no = $i++;
			$row->is_approve = $is_approve;
			
			if($status == 7){
				$row->from_stage = $this->rejType[$row->from_stage]; 
			}
			
			$sendData[] = getRecruitmentData($row);
		endforeach;
		
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addApplication(){
		$data = $this->input->post();
		$this->data['status'] = $data['status']; 
        $this->data['departmentList'] = $this->usersModel->getDepartmentList();
        $this->data['genderList'] = $this->gender;
        $this->data['bloodGroups'] = $this->bloodGroups;
        $this->data['designationList'] = $this->usersModel->getDesignations();
        $this->data['empCategoryList'] = $this->employeeCategory->getEmployeeCategoryList();
        $this->data['empList'] = $this->usersModel->getEmployeeList();
		$this->data['zoneList'] = $this->configuration->getSalesZoneList();
        $this->data['authList'] = $this->usersModel->getEmployeeList();
        $this->data['quarterList'] = $this->configuration->getHeadQuarterList();
        $this->load->view($this->employeeForm,$this->data); 
    }

    public function changeAppStatus(){
        $data = $this->input->post();
		
		if(empty($data['reason']))
            $errorMessage['reason'] = "Reason is required.";
		
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->usersModel->changeAppStatus($data));
        endif;
    }
	
	public function uploadDocument(){
		$data = $this->input->post();
        $this->data['dataRow'] = $this->usersModel->getEmployee($data);
		$this->data['empDocs'] = $this->usersModel->getEmpDocuments(['emp_id'=>$data['id']]);
        $this->load->view($this->docVerifyForm,$this->data);
	}
	
	public function saveDocForm(){
		$data = $this->input->post();
		if($data['form_type'] == "empDocs"):
			if(empty($data['doc_name']))
                $errorMessage['doc_name'] = "Document Name is required.";
            if(empty($data['doc_no']))
                $errorMessage['doc_no'] = "Document No is required.";

            if($_FILES['doc_file']['name'] != null || !empty($_FILES['doc_file']['name'])):
                $this->load->library('upload');
                $_FILES['userfile']['name']     = $_FILES['doc_file']['name'];
                $_FILES['userfile']['type']     = $_FILES['doc_file']['type'];
                $_FILES['userfile']['tmp_name'] = $_FILES['doc_file']['tmp_name'];
                $_FILES['userfile']['error']    = $_FILES['doc_file']['error'];
                $_FILES['userfile']['size']     = $_FILES['doc_file']['size'];
                
                $imagePath = realpath(APPPATH . '../assets/uploads/emp_documents/');
                $config = ['file_name' => time()."_doc_file_".$data['emp_id']."_".$_FILES['userfile']['name'],'allowed_types' => '*','max_size' => 10240,'overwrite' => FALSE, 'upload_path'	=>$imagePath];
    
                $this->upload->initialize($config);
                if (!$this->upload->do_upload()):
                    $errorMessage['doc_file'] = $this->upload->display_errors();
                    $this->printJson(["status"=>0,"message"=>$errorMessage]);
                else:
                    $uploadData = $this->upload->data();
                    $data['doc_file'] = $uploadData['file_name'];
                endif;
            else:
                unset($data['doc_file']);
            endif;
		endif;
		
		if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $docData = $this->usersModel->editProfile($data);
			$docsHtml = array('htmlData'=>$this->getEmpDocsData(['emp_id'=>$data['emp_id']]));
			$result = array_merge($docData,$docsHtml);
            $this->printJson($result);
        endif;
	}
	
	public function getEmpDocsData($data = array()){
		$htmlData = "";
		$getDocs = $this->usersModel->getEmpDocuments(['emp_id'=>$data['emp_id']]);
		if(!empty($getDocs)):
			$i=1;
			foreach($getDocs as $row):
				$deleteParam = "{'postData':{'id' : ".$row->id.",'emp_id' : ".$row->emp_id.",'form_type' : 'empDocs'}, 'fndelete' : 'deleteEmpForm','message' : 'Employee'}";
				$htmlData .= '<tr>
					<td class="text-center">'.$i++.'</td>
					<td class="text-center">'.$row->doc_name.'</td>
					<td class="text-center">'.$row->doc_no.'</td>
					<td class="text-center">'.((!empty($row->doc_file))?'<a href="'.base_url('assets/uploads/emp_documents/'.$row->doc_file).'" target="_blank"><i class="fa fa-download"></i></a>':"") .'</td>
					<td class="text-center">
						<button type="button" onclick="trashEmpProfile('.$deleteParam.');" class="btn btn-sm btn-outline-danger waves-effect waves-light btn-delete permission-remove"><i class="mdi mdi-trash-can-outline"></i></button>
					</td>
				</tr>';
			endforeach;
		else:
			$htmlData .= '<tr><td colspan="5" style="text-align:center;">No Data Found</td></tr>';
		endif;
		
		return $htmlData;
	}
	
	public function addStaffSkill(){
		$data = $this->input->post();
		$this->data['type'] = (!empty($data['type']) ? $data['type'] : 1);
		$this->data['dataRow'] = $this->usersModel->getEmployee($data);
		$empData = $this->usersModel->getEmployee(['id'=>$data['id']]);
		$this->data['skillList'] = $this->skillMaster->getSkillSetList(['id'=>$data['id'], 'type'=>$data['type'], 'emp_dept_id'=>$empData->emp_dept_id, 'emp_designation'=>$empData->emp_designation]);
		$this->data['staffSkill'] = $this->skillMaster->getStaffSkillData(['emp_id' => $data['id'], 'type' => $data['type']]);
        $this->load->view($this->skillForm,$this->data);
	}
	
	public function saveStaffSkill(){
		$data = $this->input->post();
		
		if(empty($data['set_id']))
			$errorMessage['general'] = "Skill Set is required.";
		
		$flag = false;
		if(!empty($data['act_per'])){
			$i=0;
			foreach($data['act_per'] as $row){
				if($row > 100 || $row < 0){
					$errorMessage['act_per'.$i] = "Invalid Percentage.";
				}
				if(empty($row)){
					$flag = true;
				}else{
					$flag = false; break;
				}
				$i++;
			}
		}
		
		if($flag)
			$errorMessage['general'] = "Skill Percentage is required.";
		
		if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->usersModel->saveStaffSkill($data));
        endif;
	}	
	
	public function appointedForm(){
		$data = $this->input->post();
        $this->data['dataRow'] = $this->usersModel->getEmployee($data);
        $this->load->view($this->appointedForm,$this->data);
	}
	
	public function saveAppointedForm(){
		$data = $this->input->post();
		if(empty($data['emp_joining_date']))
			$errorMessage['emp_joining_date'] = "Joining Date is required.";
		
		if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->usersModel->saveAppointedForm($data));
        endif;
	}

	public function rejectEmployee(){
		$data = $this->input->post();
		$this->data['dataRow'] = $this->usersModel->getEmployee($data);
		$this->data['status'] = 7;
        $this->load->view($this->reasonForm,$this->data);
	}
	
	public function saveRejectEmployee(){
		$data = $this->input->post();
		
		if(empty($data['reason']))
			$errorMessage['reason'] = "Reason is required.";
		
		if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->usersModel->saveRejectEmployee($data));
        endif;
	}
	
	public function approveEmployee(){
		$data = $this->input->post();
		$this->data['dataRow'] = $this->usersModel->getEmployee($data);
		$this->data['status'] = $data['status'];
        $this->load->view($this->reasonForm,$this->data);
	}
	
	public function printLogs(){
		$data = $this->input->post();
		$this->data['getEmpLog'] = $this->usersModel->getEmpLogs(['emp_id'=>$data['id']]);
		$this->data['empDocuments'] = $this->usersModel->getEmpDocuments(['emp_id'=>$data['id']]);
		$this->data['techStaffSkill'] = $this->skillMaster->getStaffSkillData(['emp_id' => $data['id'], 'type' => 1]);
		$this->data['hrStaffSkill'] = $this->skillMaster->getStaffSkillData(['emp_id' => $data['id'], 'type' => 2]);
		$this->data['skillList'] = $this->skillMaster->getSkillSetList($data);
		$this->load->view($this->logDetails,$this->data);
	}

	public function printOfferLetter($id){
		$this->data['empData'] = $empData = $this->usersModel->getEmployee(['id'=>$id]);
		$this->data['companyData'] = $this->usersModel->getCompanyInfo();
		
		$logo=base_url('assets/images/logo.png');
		$this->data['letter_head']=base_url('assets/images/letterhead_top.png');
		$pdfData = $this->load->view('hr/employee/print_offer_letter',$this->data,true);
		
		$htmlHeader = '<img src="'.$this->data['letter_head'].'" class="img">';
		$htmlFooter = '<table class="table top-table" style="margin-top:10px;border-top:1px solid #545454;border-bottom:1px solid #000000;">
						<tr>
							<td style="width:50%;" rowspan="3"></td>
							<th colspan="2">For, '.$this->data['companyData']->company_name.'</th>
						</tr>
						<tr>
							<td style="width:25%;" class="text-center"></td>
							<td style="width:25%;" class="text-center">Authorised By</td>
						</tr>
					</table>';
		
		$mpdf = new \Mpdf\Mpdf();
		$pdfFileName= ''.$empData->emp_name.'_Employment_Offer.pdf';
		$stylesheet = file_get_contents(base_url('assets/css/pdf_style.css'));
		$mpdf->WriteHTML($stylesheet,1);
		$mpdf->SetDisplayMode('fullpage');
		$mpdf->SetWatermarkImage($logo,0.03,array(120,60));
		$mpdf->showWatermarkImage = true;
		$mpdf->SetProtection(array('print'));
		$mpdf->SetTitle(''.$empData->emp_name.'_Employment_Offer');
		
		$mpdf->SetHTMLHeader($htmlHeader);
		$mpdf->AddPage('P','','','','',5,5,40,10,3,3,'','','','','','','','','','A4-P');
		$mpdf->WriteHTML($pdfData);
		$mpdf->Output($pdfFileName,'I');
	}
	
	public function deleteEmpForm(){
        $data = $this->input->post();
        if(empty($data['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
			$msg = $this->usersModel->removeProfileDetails($data);
			if($data['form_type'] == "empDocs"):
				$tbodyData = $this->getEmpDocsData(['emp_id' => $data['emp_id']]);
			endif;
			
			$this->printJson(['status'=>1,'message'=>$msg['message'],"tbodyData"=>$tbodyData,"form_type"=>$data['form_type']]);
        endif;
    }

	/* Start Vacancy */
    public function vacancy(){
        $this->data['tableHeader'] = getHrDtHeader('vacancy');
        $this->load->view('hr/employee/vacancy_index',$this->data);
    }

    public function getVacancyDTRows(){
        $data = $this->input->post(); 
        $result = $this->usersModel->getVacancyDTRows($data);	
        $sendData = array();$i=($data['start'] + 1);
        foreach($result['data'] as $row):
            $row->sr_no = $i++;         
            $sendData[] = getVacancyData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addVacancy(){
        $this->data['skillSetList'] = $this->skillMaster->getSkillSet(['group_by'=>'set_name']);
        $this->load->view('hr/employee/vacancy_form',$this->data);
    }

    public function saveVacancy(){
        $data = $this->input->post();
		$errorMessage = array();		
        if(empty($data['set_name']))
			$errorMessage['set_name'] = "Set Name is required.";
        if(empty($data['vacancy_no']))
			$errorMessage['vacancy_no'] = "Vacancy No is required.";

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->usersModel->saveVacancy($data));
        endif;
    }

    public function editVacancy(){     
        $data = $this->input->post();
        $this->data['dataRow'] = $this->usersModel->getVacancyData($data);
        $this->data['skillSetList'] = $this->skillMaster->getSkillSet(['group_by'=>'set_name']);
        $this->load->view('hr/employee/vacancy_form',$this->data);
    }

    public function deleteVacancy(){
        $data = $this->input->post();
        if(empty($data['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->usersModel->deleteVacancy($data));
        endif;
    }
    /* End Vacancy */
}
?>