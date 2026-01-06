<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/* get Pagewise Table Header */
function getHrDtHeader($page){
    /* Designation Header */
    $data['designation'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
	$data['designation'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"];
    $data['designation'][] = ["name"=>"Designation Name"];
    // $data['designation'][] = ["name"=>"Remark"];
	
	/* Department Header */
    $data['departments'][] = ["name"=>"Action","style"=>"width:10%;","sortable"=>"FALSE","textAlign"=>"center"];
	$data['departments'][] = ["name"=>"#","style"=>"width:10%;","sortable"=>"FALSE","textAlign"=>"center"];
    $data['departments'][] = ["name"=>"Department Name"];
    $data['departments'][] = ["name"=>"Remark"];

    /* Employee Header */
    $data['employees'][] = ["name"=>"Action"];
	$data['employees'][] = ["name"=>"#","textAlign"=>'center']; 
    $data['employees'][] = ["name"=>"Employee Name"];
    $data['employees'][] = ["name"=>"Employee Code","textAlign"=>'center'];
    $data['employees'][] = ["name"=>"Department"];
	$data['employees'][] = ["name"=>"Designation"];
    $data['employees'][] = ["name"=>"Contact No.","textAlign"=>'center'];
	$data['employees'][] = ["name"=>"Head Quarter"];
	
    /* Pending/Self Approved Attendance Header */
    $data['attendance'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
	$data['attendance'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"];
    $data['attendance'][] = ["name"=>"Employee Code"];
    $data['attendance'][] = ["name"=>"Employee Name"];
    $data['attendance'][] = ["name"=>"Punch Type"];
    $data['attendance'][] = ["name"=>"Punch Time"];
    $data['attendance'][] = ["name"=>"Location"];
    $data['attendance'][] = ["name"=>"HQ Location"];
    $data['attendance'][] = ["name"=>"Approved"];
	
	/* Leave Header */
    $data['leave'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
    $data['leave'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"];
    $data['leave'][] = ["name"=>"Employee Name"];
    $data['leave'][] = ["name"=>"From Date"];
    $data['leave'][] = ["name"=>"To Date"];
    $data['leave'][] = ["name"=>"Leave Days"];
    $data['leave'][] = ["name"=>"Status"];
    $data['leave'][] = ["name"=>"Reasons"];
	
	/* Employee Header */
    $data['recruit'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
	$data['recruit'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE];
    $data['recruit'][] = ["name"=>"Employee Name"];
    $data['recruit'][] = ["name"=>"Contact No.","textAlign"=>'center'];
	$data['recruit'][] = ["name"=>"Department"];
    $data['recruit'][] = ["name"=>"Designation"];
    $data['recruit'][] = ["name"=>"Source"];
    $data['recruit'][] = ["name"=>"Reference"];
	$data['recruit'][] = ["name"=>"Joining Date"];
	
	/* Employee Category Header */
    $data['employeeCategory'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
	$data['employeeCategory'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE];
    $data['employeeCategory'][] = ["name"=>"Category Name"];
    $data['employeeCategory'][] = ["name"=>"Over Time"];
	
	/* Skill Master Header */
	$data['skillMaster'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
	$data['skillMaster'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE];
	$data['skillMaster'][] = ["name"=>"Skill Name"];

    /* Skill Set Header */
	$data['skillSet'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
	$data['skillSet'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE];
	$data['skillSet'][] = ["name"=>"Set Name"];
	$data['skillSet'][] = ["name"=>"No Of Skill"];
	$data['skillSet'][] = ["name"=>"Department"];
	$data['skillSet'][] = ["name"=>"Designation"];
	
	/* Employee Header */
    $data['recruitRej'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
	$data['recruitRej'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE];
    $data['recruitRej'][] = ["name"=>"Employee Name"];
    $data['recruitRej'][] = ["name"=>"Contact No.","textAlign"=>'center'];
	$data['recruitRej'][] = ["name"=>"Department"];
    $data['recruitRej'][] = ["name"=>"Designation"];
    $data['recruitRej'][] = ["name"=>"Source"];
    $data['recruitRej'][] = ["name"=>"Reference"];
    $data['recruitRej'][] = ["name"=>"Reject Stage"];
    $data['recruitRej'][] = ["name"=>"Reject By"];
	
	/* Vacancy Header */
	$data['vacancy'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
	$data['vacancy'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE];
	$data['vacancy'][] = ["name"=>"Set Name"];
	$data['vacancy'][] = ["name"=>"Vacancy No"];
	$data['vacancy'][] = ["name"=>"Notes"];
	$data['vacancy'][] = ["name"=>"Publish To"];
	
	/* Shift Header */
	$data['shift'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
	$data['shift'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE];
	$data['shift'][] = ["name"=>"Shift Name"];
	$data['shift'][] = ["name"=>"Start Time"];
	$data['shift'][] = ["name"=>"End Time"];
	$data['shift'][] = ["name"=>"Shift Hour"];
	
    return tableHeader($data[$page]);
}

/* Designation Table Data */
function getDesignationData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Designation'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'right_modal', 'form_id' : 'editDesignation', 'title' : 'Update Designation'}";

    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="mdi mdi-square-edit-outline" ></i></a>';
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
	
	$action = getActionButton($editButton.$deleteButton);
    return [$action,$data->sr_no,$data->title];
}

/* Department Table Data */
function getDepartmentData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Department'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'right_modal', 'form_id' : 'editDepartment', 'title' : 'Update Department'}";

    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="mdi mdi-square-edit-outline" ></i></a>';
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
	
	$action = getActionButton($editButton.$deleteButton);
    return [$action,$data->sr_no,$data->name,$data->description];
}

/* Employee Table Data */
function getEmployeeData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Employee'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'right_modal_lg', 'form_id' : 'editEmployee', 'title' : 'Update Employee'}";
    
    $leaveButton = '';$addInDevice = '';$activeButton = '';$empRelieveBtn = '';$editButton = '';$deleteButton = '';    
    if($data->is_active == 1):
        $activeParam = "{'postData':{'id' : ".$data->id.", 'is_active' : 0},'fnsave':'activeInactive','message':'Are you sure want to De-Active this Employee?'}";
        $activeButton = '<a class="btn btn-youtube permission-modify" href="javascript:void(0)" datatip="De-Active" flow="down" onclick="confirmStore('.$activeParam.');"><i class="fa fa-ban"></i></a>';  

        $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="mdi mdi-square-edit-outline" ></i></a>';
        $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
    else:
        $activeParam = "{'postData':{'id' : ".$data->id.", 'is_active' : 1},'fnsave':'activeInactive','message':'Are you sure want to Active this Employee?'}";
        $activeButton = '<a class="btn btn-success permission-remove" href="javascript:void(0)" datatip="Active" flow="down" onclick="confirmStore('.$activeParam.');"><i class="fa fa-check"></i></a>';            
    endif;
	
	$logParam = "{'postData':{'id' : ".$data->id.", 'status' : 2},'modal_id' : 'modal-lg', 'form_id' : 'empLogs', 'title' : '".$data->emp_name." (".$data->dept_name." - ".$data->emp_designation.")','fnedit':'printLogs', 'button' : 'close'}";
	$logButton = '<a class="btn btn-info btn-edit permission-modify" href="javascript:void(0)" datatip="Employee Details" flow="down" onclick="edit('.$logParam.');"><i class="fa fa-info"></i></a>';
    
    $CI = & get_instance();
    $userRole = $CI->session->userdata('role');

    $resetPsw='';
    if(in_array($userRole,[-1,1])):
        $resetParam = "{'postData':{'id' : ".$data->id."},'fnsave':'resetPassword','message':'Are you sure want to Change ".$data->emp_name." Password?'}";
        $resetPsw='<a class="btn btn-danger" href="javascript:void(0)" onclick="confirmStore('.$resetParam.');" datatip="Reset Password" flow="down"><i class="fa fa-key"></i></a>';
    endif;
    
    $action = getActionButton($resetPsw.$leaveButton.$addInDevice.$activeButton.$empRelieveBtn.$editButton.$deleteButton.$logButton);

    return [$action,$data->sr_no,$data->emp_name,$data->emp_code,$data->dept_name,$data->emp_designation,$data->emp_contact,$data->quarter_name];
}

/* Pending/Self Approved Attendance Table Data */
function getAttendanceData($data){
    $approveButton = '';$approved_by = '';$rejectButton = '';
    if($data->status == 3){
        $CI = & get_instance();
        $userRole = $CI->session->userdata('role');
        if($userRole == 4):
            $approveParam = "{'postData':{'id':".$data->id.",'attendance_status' : 3},'message':'Are you sure want to Approve this Attendance?','fnsave':'approveAttendance'}";
            $approveButton = '<a href="javascript:void(0)" class="btn btn-success permission-approve" onclick="confirmStore('.$approveParam.');" datatip="Approve" flow="down"><i class="fa fa-check"></i></a>';

            $rejectParam = "{'postData':{'id':".$data->id." ,'attendance_status' : 2},'message':'Are you sure want to Rejct this Attendance?','fnsave':'approveAttendance'}";
            $rejectButton = '<a href="javascript:void(0)" class="btn btn-dark permission-approve" onclick="confirmStore('.$rejectParam.');" datatip="Reject" flow="down"><i class="fa fa-close"></i></a>';
        endif;
    }else{
        if(empty($data->approve_by)):
            $approveParam = "{'postData':{'id':".$data->id." ,'attendance_status' : 1},'message':'Are you sure want to Approve this Attendance?','fnsave':'approveAttendance'}";
            $approveButton = '<a href="javascript:void(0)" class="btn btn-success permission-approve" onclick="confirmStore('.$approveParam.');" datatip="Approve" flow="down"><i class="fa fa-check"></i></a>';

            $rejectParam = "{'postData':{'id':".$data->id." ,'attendance_status' : 2},'message':'Are you sure want to Rejct this Attendance?','fnsave':'approveAttendance'}";
            $rejectButton = '<a href="javascript:void(0)" class="btn btn-dark permission-approve" onclick="confirmStore('.$rejectParam.');" datatip="Reject" flow="down"><i class="fa fa-close"></i></a>';
    
        else:
            if($data->emp_id != $data->approve_by):
                $approved_by = (!empty($data->approve_name) ? $data->approve_name : ' - ');
            endif;
        endif;
    }
    
	$action = getActionButton($approveButton.$rejectButton);
	$add = '<p style="max-width: 300px;white-space: break-spaces;font-size: 0.8rem;line-height: inherit;margin-bottom: 0px;'.(($data->distance >1) ? 'color:#FF0000;' : '').'">'.$data->loc_add.'</p>';
	$add .= '<small>[Distance : '.$data->distance.' Km.]</small>';
	$hq_add = '<p style="max-width: 300px;white-space: break-spaces;font-size: 0.8rem;line-height: inherit;margin-bottom: 0px;">'.$data->hq_add.'</p>';
    return [$action,$data->sr_no,$data->emp_code,$data->emp_name,$data->type,date('d-m-Y H:i:s',strtotime($data->punch_date)),$add,$hq_add,$approved_by];
}

/* Leave Table Data */
function getLeaveData($data){
    $approveButton = $rejButton = $editButton = $deleteButton ='';
    
    if($data->status == 1):
        $approveParam = "{'postData':{'id':".$data->id.", 'status':2},'message':'Are you sure want to Approve this Leave?','fnsave':'approveLeave'}";
        $approveButton = '<a href="javascript:void(0)" class="btn btn-success permission-approve" onclick="confirmStore('.$approveParam.');" datatip="Approve" flow="down"><i class="fa fa-check"></i></a>';
		
        $rejParam = "{'postData':{'id':".$data->id.", 'status':3},'message':'Are you sure want to Reject this Leave?','fnsave':'approveLeave'}";
        $rejButton = '<a href="javascript:void(0)" class="btn btn-dark permission-approve" onclick="confirmStore('.$rejParam.');" datatip="Reject" flow="down"><i class="fa fa-times"></i></a>';

        $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Leave'}";
        $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'right_modal', 'form_id' : 'editLeave', 'title' : 'Update Leave'}";
        
        $editButton = '<a class="btn btn-info btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="mdi mdi-square-edit-outline" ></i></a>';
        $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
	elseif($data->status == 3):
		$approveParam = "{'postData':{'id':".$data->id.", 'status':2},'message':'Are you sure want to Approve this Leave?','fnsave':'approveLeave'}";
        $approveButton = '<a href="javascript:void(0)" class="btn btn-success permission-approve" onclick="confirmStore('.$approveParam.');" datatip="Approve" flow="down"><i class="fa fa-check"></i></a>';
	elseif($data->status == 2):
	    $rejParam = "{'postData':{'id':".$data->id.", 'status':3},'message':'Are you sure want to Reject this Leave?','fnsave':'approveLeave'}";
        $rejButton = '<a href="javascript:void(0)" class="btn btn-dark permission-approve" onclick="confirmStore('.$rejParam.');" datatip="Reject" flow="down"><i class="fa fa-times"></i></a>';
    endif;
	
	if(strtotime($data->end_date) < strtotime(date('Y-m-d'))):
		$editButton = $deleteButton = '';
	endif;
	
    $emp_name = (!empty($data->emp_code)) ? '['.$data->emp_code.'] '.$data->emp_name : $data->emp_name;
	
    $status = (!empty($data->auth_by)) ? $data->auth_by.'<br>'.formatDate($data->auth_at) : '';
    
	$action = getActionButton($approveButton.$rejButton.$editButton.$deleteButton);
    return [$action,$data->sr_no,$emp_name,formatDate($data->start_date),formatDate($data->end_date),$data->total_days,$status,$data->leave_reason];
}

/* Recruitment Table Data */
function getRecruitmentData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Employee'}";
    $editParam = "{'postData':{'id' : ".$data->id.", 'status' : 2},'modal_id' : 'right_modal_lg', 'form_id' : 'editEmployee', 'title' : 'Update Employee','fnedit':'edit'}";
    
    $editButton = ''; $approveButton = ''; $rejectButton = ''; $approveDocButton = ''; $skillButton = ''; $activeButton = ''; $logButton = ''; $printBtn = '';
		
	if($data->status == 2){
		$approveParam = "{'postData':{'id' : ".$data->id.", 'status' : 3},'modal_id' : 'modal-md', 'form_id' : 'approveEmployee', 'title' : 'Approve Employee','fnedit':'approveEmployee', 'fnsave' : 'changeAppStatus'}";
		$editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';
    }
    
	if($data->status == 3){
		$approveParam = "{'postData':{'id' : ".$data->id.", 'status' : 4},'modal_id' : 'modal-md', 'form_id' : 'approveEmployee', 'title' : 'Approve Employee','fnedit':'approveEmployee', 'fnsave' : 'changeAppStatus'}";
		$approveDocParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'modal-lg', 'form_id' : 'editEmpDocs', 'title' : 'Add Document Verification','fnedit':'uploadDocument', 'button' : 'close'}";
		$approveDocButton = '<a class="btn btn-primary btn-edit permission-modify" href="javascript:void(0)" datatip="Document Upload" flow="down" onclick="edit('.$approveDocParam.');"><i class="fa fa-plus"></i></a>';
	}
	
	if($data->status == 4){
		$approveParam = "{'postData':{'id' : ".$data->id.", 'status' : 5},'modal_id' : 'modal-md', 'form_id' : 'approveEmployee', 'title' : 'Approve Employee','fnedit':'approveEmployee', 'fnsave' : 'changeAppStatus'}";
		$skillParam = "{'postData':{'id' : ".$data->id.", 'type' : 1},'modal_id' : 'right_modal_lg', 'form_id' : 'addSkill', 'title' : 'Add Staff Skill','fnedit':'addStaffSkill','fnsave' : 'saveStaffSkill'}";
		$skillButton = '<a class="btn btn-primary btn-edit permission-modify" href="javascript:void(0)" datatip="Staff Skill" flow="down" onclick="edit('.$skillParam.');"><i class="fa fa-plus"></i></a>';
	}
	
	if($data->status == 5){
		$approveParam = "{'postData':{'id' : ".$data->id.", 'status' : 6},'modal_id' : 'modal-md', 'form_id' : 'approveEmployee', 'title' : 'Approve Employee','fnedit':'approveEmployee', 'fnsave' : 'changeAppStatus'}";
		$skillParam = "{'postData':{'id' : ".$data->id.", 'type' : 2},'modal_id' : 'right_modal_lg', 'form_id' : 'addSkill', 'title' : 'Add Staff Skill','fnedit':'addStaffSkill','fnsave' : 'saveStaffSkill'}";
		$skillButton = '<a class="btn btn-primary btn-edit permission-modify" href="javascript:void(0)" datatip="Staff Skill" flow="down" onclick="edit('.$skillParam.');"><i class="fa fa-plus"></i></a>';
	}
	
	if($data->status == 6){
		$activeParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'modal-md', 'form_id' : 'activeEmp', 'title' : 'Active Employee','fnedit':'appointedForm','fnsave' : 'saveAppointedForm'}";
		$activeButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Staff Skill" flow="down" onclick="edit('.$activeParam.');"><i class="fa fa-check"></i></a>';
		
		if(!empty($data->emp_joining_date)){
			$printBtn = '<a class="btn btn-primary btn-edit permission-modify" href="'.base_url('hr/employees/printOfferLetter/'.$data->id).'" target="_blank" datatip="Offer Letter" flow="down"><i class="mdi mdi-file-pdf" ></i></a>';
		}
	}
	
	if($data->status != 7){
		$rejectParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'modal-md', 'form_id' : 'rejectEmployee', 'title' : 'Reject Employee','fnedit':'rejectEmployee', 'fnsave' : 'saveRejectEmployee'}";
		$rejectButton = '<a class="btn btn-danger btn-edit permission-modify" href="javascript:void(0)" datatip="Reject" flow="down" onclick="edit('.$rejectParam.');"><i class="fa fa-close"></i></a>';
		
		if(!in_array($data->status,[1,6,7])){
			if($data->status == 3):				
				if($data->total_docs != 0):
					$approveButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Approve" flow="down" onclick="edit('.$approveParam.');"><i class="fa fa-check"></i></a>';
				endif;
			else:
				$approveButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Approve" flow="down" onclick="edit('.$approveParam.');"><i class="fa fa-check"></i></a>';
			endif;
		}
	}
	
	$logParam = "{'postData':{'id' : ".$data->id.", 'status' : 2},'modal_id' : 'modal-lg', 'form_id' : 'empLogs', 'title' : '".$data->emp_name." (".$data->dept_name." - ".$data->emp_designation.")','fnedit':'printLogs', 'button' : 'close'}";
	$logButton = '<a class="btn btn-info btn-edit permission-modify" href="javascript:void(0)" datatip="Log Details" flow="down" onclick="edit('.$logParam.');"><i class="fa fa-info"></i></a>';
	
	$action = getActionButton($editButton.$logButton.$activeButton.$skillButton.$approveDocButton.$approveButton.$rejectButton.$printBtn);
	if(!empty($data->is_approve)){ $action = getActionButton(''); }
	
	if($data->status != 7){
		return [$action,$data->sr_no,$data->emp_name,$data->emp_contact,$data->dept_name,$data->emp_designation,$data->rec_source,$data->ref_by,formatDate($data->emp_joining_date)];
	}else{
		return [$action,$data->sr_no,$data->emp_name,$data->emp_contact,$data->dept_name,$data->emp_designation,$data->rec_source,$data->ref_by,$data->from_stage,$data->reject_name.'<br>'.formatDate($data->reject_at,'d-m-Y H:i')];
	}
}

/* Employee Category Table Data */
function getEmployeeCategoryData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Employee Category'}";
	
	$editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'right_modal', 'form_id' : 'editEmployeeCategory', 'title' : 'Update Employee Category'}";

    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';
    $deleteButton = '<a class="btn btn-danger btn-delete" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';

	$action = getActionButton($editButton.$deleteButton);
    return [$action,$data->sr_no,$data->category,$data->overtime];
}

/* get Skill Master Data */
function getSkillData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Skill'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'modal-md', 'form_id' : 'editSkill', 'title' : 'Update Skill'}";

    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="mdi mdi-square-edit-outline" ></i></a>';
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';

	$action = getActionButton($editButton.$deleteButton);

    return [$action,$data->sr_no,$data->skill_name];
}

/* get Skill Set Master Data */
function getSkillSetData($data){
    $deleteParam = "{'postData':{'set_name' : '".$data->set_name."'},'message' : 'Skill Set','fndelete':'deleteSet'}";
    $editParam = "{'postData':{'set_name' : '".$data->set_name."'},'modal_id' : 'right_modal_lg', 'form_id' : 'addSkillSet', 'title' : 'Update Skill Set' , 'fnedit':'editSkillSet', 'fnsave':'saveSkillSet'}";

    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="mdi mdi-square-edit-outline" ></i></a>';
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';

    $print = '<a class="btn btn-success btn-info" href="'.base_url('hr/skillMaster/printSkillSet/'.$data->set_name).'" target="_blank" datatip=" Print" flow="down"><i class="fas fa-print" ></i></a>';

	$action = getActionButton($print.$editButton.$deleteButton);

    return [$action,$data->sr_no,$data->set_name,$data->skill_count,$data->dept_name,$data->dsg_title];
}

/* get Vacancy Data */
function getVacancyData($data){
    $deleteParam = "{'postData':{'id' : '".$data->id."'},'message' : 'Vacancy','fndelete':'deleteVacancy'}";

    $editParam = "{'postData':{'id' : '".$data->id."'},'modal_id' : 'right_modal', 'form_id' : 'addVacancy', 'title' : 'Update Vacancy' , 'fnedit':'editVacancy', 'fnsave':'saveVacancy'}";

    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="mdi mdi-square-edit-outline" ></i></a>';
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';

	$action = getActionButton($editButton.$deleteButton);

    return [$action,$data->sr_no,$data->set_name,$data->vacancy_no,$data->notes,$data->publish_to];
}

/* get Shift Data */
function getShiftData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Shift'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'right_modal', 'form_id' : 'editShift', 'title' : 'Update Shift'}";

    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="mdi mdi-square-edit-outline" ></i></a>';
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';

	$action = getActionButton($editButton.$deleteButton);

    return [$action,$data->sr_no,$data->shift_name,$data->shift_start,$data->shift_end,$data->total_shift_time];
}
?>