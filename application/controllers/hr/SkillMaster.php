<?php
class SkillMaster extends MY_Controller{
    private $indexPage = "hr/skill_master/index";
    private $formPage = "hr/skill_master/form";
    private $skillSetIndex = "hr/skill_master/skill_set_index";
    private $skillSetForm = "hr/skill_master/skill_set_form";

    public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Skill Master";
		$this->data['headData']->controller = "hr/skillMaster";
	}

    public function index(){
        $this->data['status'] = 0;
		$this->data['tableHeader'] = getHrDtHeader('skillMaster');
		$this->load->view($this->indexPage,$this->data);
    }

    public function getDTRows(){
        $data = $this->input->post();
        $result = $this->skillMaster->getDTRows($data);	
        $sendData = array();$i=($data['start'] + 1);
        foreach($result['data'] as $row):
            $row->sr_no = $i++;         
            $sendData[] = getSkillData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addSkill(){
        $this->load->view($this->formPage,$this->data);
    }

    public function save(){
        $data = $this->input->post();
		$errorMessage = array();		
        if(empty($data['skill_name']))
			$errorMessage['skill_name'] = "Skill Name is required.";

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->skillMaster->save($data));
        endif;
    }

    public function edit(){     
        $data = $this->input->post();
        $this->data['dataRow'] = $this->skillMaster->getSkill($data);
        $this->load->view($this->formPage,$this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->skillMaster->delete($id));
        endif;
    }

    /* Start Skill Set */
    public function skillSetIndex(){
        $this->data['status'] = 1;
        $this->data['tableHeader'] = getHrDtHeader('skillSet');
        $this->load->view($this->skillSetIndex,$this->data);
    }

    public function getSkillSetDTRows(){
        $data = $this->input->post();
        $result = $this->skillMaster->getSkillSetDTRows($data);	
        $sendData = array();$i=($data['start'] + 1);
        foreach($result['data'] as $row):
            $row->sr_no = $i++;         
            $sendData[] = getSkillSetData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addSkillSet(){
        $this->data['skillList'] = $this->skillMaster->getSkillList();
		$this->data['departmentList'] = $this->usersModel->getDepartmentList();
		$this->data['designationList'] = $this->usersModel->getDesignations();
        $this->load->view($this->skillSetForm,$this->data);
    }

    public function saveSkillSet(){
        $data = $this->input->post();
		$errorMessage = array();

        if(empty($data['set_name']))
            $errorMessage['set_name'] = "Set Name is required.";		
        if(empty($data['skill_id']))
			$errorMessage['skill_id'] = "Skill Name is required.";
		if(empty($data['emp_dept_id']))
			$errorMessage['emp_dept_id'] = "Department is required.";
		if(empty($data['emp_designation']))
			$errorMessage['emp_designation'] = "Designation is required.";
        if(empty($data['skill_per'])){
			$errorMessage['skill_per'] = "Skill Per is required.";
        }else{
            if($data['skill_per'] <= 0 OR $data['skill_per'] >100)
			    $errorMessage['skill_per'] = "Skill Per Not valid.";
        }
        if($this->skillMaster->checkSkillSetDuplicate(['set_name'=>$data['set_name'],'skill_id'=>$data['skill_id'],'id'=>$data['id']]) > 0){
            $errorMessage['skill_id'] =  "Skill Name is duplicate."; 
        }
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $resultData = $this->skillMaster->saveSkillSet($data);
           
            $htmlData = $this->getSkillSetHtml(['set_name'=>$data['set_name']]);
            $this->printJson(['status'=>1,'message'=>$resultData['message'],"tbodyData"=>$htmlData]);
        endif;
    }

    public function getSkillSetHtml($data){
        $data = $this->input->post();
        $skillSetData = $this->skillMaster->getSkillSet($data);
		$i=1; $tbody='';
		if(!empty($skillSetData)):
			foreach($skillSetData as $row):
                $deleteParam = "{'postData':{'id' : ".$row->id.",'set_name':'".$row->set_name."'},'message' : 'Skill Set','fndelete':'deleteSkillSet'}";
				$tbody.= '<tr>
						<td>'.$i++.'</td>
						<td>'.$row->set_name.'</td>
						<td>'.$row->dept_name.'</td>
						<td>'.$row->dsg_title.'</td>
						<td>'.$row->skill_name.'</td>
						<td>'.$row->skill_per.'</td>
						<td class="text-center">
							<button type="button" onclick="trashSkillSet('.$deleteParam.');" class="btn btn-sm btn-outline-danger waves-effect waves-light permission-remove"><i class="mdi mdi-trash-can-outline"></i></button>
						</td>
					</tr>';
			endforeach;
        else:
            $tbody = '<tr><td colspan="5" class="text-center">No data found.</td></tr>';
		endif;
        return $tbody;
	}
	
    public function editSkillSet(){     
        $data = $this->input->post();
        $this->data['set_name'] = $data['set_name'];
        $this->data['dataRow'] = $this->skillMaster->getSkillSet($data);
		$this->data['departmentList'] = $this->usersModel->getDepartmentList();
		$this->data['designationList'] = $this->usersModel->getDesignations();
        $this->data['skillList'] = $this->skillMaster->getSkillList($data);
        $this->load->view($this->skillSetForm,$this->data);
    }

    public function deleteSet(){
        $data = $this->input->post();
        if(empty($data['set_name'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->skillMaster->deleteSet($data));
        endif;
    }

    public function deleteSkillSet(){
        $data = $this->input->post();
        if(empty($data['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $result = $this->skillMaster->deleteSkillSet($data);
          
            $result['tbodyData']=$this->getSkillSetHtml(['set_name'=>$data['set_name']]);
            $this->printJson($result);
        endif;
    }

    function printSkillSet($set_name){
		$skillSetData = $this->data['skillSetData'] = $this->skillMaster->getSkillSet(['set_name'=>$set_name]);
		
		$this->data['letter_head']=base_url('assets/images/letterhead_top.png');
        $pdfData = $this->load->view('hr/skill_master/print_skill_set', $this->data, true);

        $printedBy = $this->employee->getEmployee(['id'=>$this->loginId]);
		$htmlHeader = '<img src="'.$this->data['letter_head'].'" class="img">';
        $htmlFooter = '
			<table class="table top-table" style="margin-top:10px;border-top:1px solid #000000;">
				<tr>
					<td style="width:50%;">
					    Created By & Date : <br>
					    Printed By & Date : '.$printedBy->emp_name.' ('.formatDate(date('Y-m-d H:s:i'), 'd-m-Y H:s:i').')
					</td>
					<td style="width:50%;text-align:right;">Page No. {PAGENO}/{nbpg}</td>
				</tr>
			</table>';

        $mpdf = new \Mpdf\Mpdf();
        $pdfFileName = 'SS-' . $set_name . '.pdf';
        $stylesheet = file_get_contents(base_url('assets/css/pdf_style.css'));
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->SetProtection(array('print'));
		$mpdf->SetHTMLHeader($htmlHeader);
		$mpdf->SetHTMLFooter($htmlFooter);
		$mpdf->useSubstitutions = false;
		$mpdf->simpleTables = true;

        $mpdf->AddPage('P', '', '', '', '', 5, 5, 38, 20, 5, 5, '', '', '', '', '', '', '', '', '', 'A4-P');
        $mpdf->WriteHTML($pdfData);
        $mpdf->Output($pdfFileName, 'I');
    }
    /* End Skill Set */
}
?>