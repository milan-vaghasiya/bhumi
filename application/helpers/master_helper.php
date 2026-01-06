<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/* get Pagewise Table Header */
function getMasterDtHeader($page){

    /* Customer Header */
    $data['customer'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
	$data['customer'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"]; 
	$data['customer'][] = ["name"=>"Image"];
    $data['customer'][] = ["name"=>"Party Code"];
	$data['customer'][] = ["name"=>"Company Name"];
	$data['customer'][] = ["name"=>"Business Type"];
	$data['customer'][] = ["name"=>"Contact Person"];
    $data['customer'][] = ["name"=>"Contact No."];
    $data['customer'][] = ["name"=>"Sales Executive"];
    $data['customer'][] = ["name"=>"District"];
    $data['customer'][] = ["name"=>"Taluka"];

    /* Customer Complaint Data */
    $data['customerComplaint'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
    $data['customerComplaint'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"];
    $data['customerComplaint'][] = ["name"=>"Complaint No."]; 
    $data['customerComplaint'][] = ["name"=>"Complaint Date"]; 
    $data['customerComplaint'][] = ["name"=>"Return No."]; 
    $data['customerComplaint'][] = ["name"=>"Return Date"];
    $data['customerComplaint'][] = ["name"=>"Customer Name"];
    $data['customerComplaint'][] = ["name"=>"Product Name"];
    $data['customerComplaint'][] = ["name"=>"Notes"];

    /* Finish Goods Header */
    $data['finish_good'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
    $data['finish_good'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"];  
	$data['finish_good'][] = ["name"=>"Item Image","textAlign"=>"center"]; 
    $data['finish_good'][] = ["name"=>"Item Name"];
    $data['finish_good'][] = ["name"=>"Item Category"];
    $data['finish_good'][] = ["name"=>"Price"];
    $data['finish_good'][] = ["name"=>"Primary Pack"];
    $data['finish_good'][] = ["name"=>"Master Pack"];

    /* Item Category Header */
    $data['itemCategory'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
    $data['itemCategory'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"]; 
    $data['itemCategory'][] = ["name"=>"Category Name"];
    $data['itemCategory'][] = ["name"=>"Parent Category"];
    $data['itemCategory'][] = ["name"=>"Is Final ?"];
    $data['itemCategory'][] = ["name"=>"Is Returnable ?"];
    $data['itemCategory'][] = ["name"=>"Remark"];

    /* Service Category Header */
    $data['serviceCategory'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
    $data['serviceCategory'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"]; 
    $data['serviceCategory'][] = ["name"=>"Service"];

    /* Service Request Header */
    $data['serviceRequest'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
    $data['serviceRequest'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"]; 
    $data['serviceRequest'][] = ["name"=>"Request No."];
    $data['serviceRequest'][] = ["name"=>"Request Date"];
    $data['serviceRequest'][] = ["name"=>"Customer"];
    $data['serviceRequest'][] = ["name"=>"Product"];
    $data['serviceRequest'][] = ["name"=>"On Site"];
    $data['serviceRequest'][] = ["name"=>"Description"];

    /* Expense Header */
    // $masterQcCheckBox = '<input type="checkbox" id="masterApproveSelect" class="filled-in chk-col-success BulkApproveRequest permission-approve" value=""><label for="masterApproveSelect">ALL</label>';

	$data['expense'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
    $data['expense'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"]; 
    // $data['expense'][] = ["name"=>$masterQcCheckBox,"style"=>"width:10%;","textAlign"=>"center","orderable"=>"false","class"=>"permission-approve"];
    $data['expense'][] = ["name"=>"Exp Date"];    
    // $data['expense'][] = ["name"=>"Expense No."];
    $data['expense'][] = ["name"=>"Employee / Customer"];
    $data['expense'][] = ["name"=>"Demand Amount"];
    $data['expense'][] = ["name"=>"Approved Amount"];
    $data['expense'][] = ["name"=>"Status"];	

    /* Meeting Header */
    $data['meeting'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
    $data['meeting'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"]; 
    $data['meeting'][] = ["name"=>"Meeting Date"];
    $data['meeting'][] = ["name"=>"Meeting Type"];
    $data['meeting'][] = ["name"=>"Location"];
    $data['meeting'][] = ["name"=>"Agenda"];

    /* Event  Header */
    $data['eventIndex'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
    $data['eventIndex'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"]; 
    $data['eventIndex'][] = ["name"=>"Event Date"];
    $data['eventIndex'][] = ["name"=>"Event Type"];
    $data['eventIndex'][] = ["name"=>"Event Name"];
    $data['eventIndex'][] = ["name"=>"Duration"];
    $data['eventIndex'][] = ["name"=>"Location"];
    $data['eventIndex'][] = ["name"=>"Description"];

    // 27-09-25
    /* Vehicel Expense Header */
	$data['vehicleExpense'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
    $data['vehicleExpense'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"]; 
    $data['vehicleExpense'][] = ["name"=>"Date"];    
    $data['vehicleExpense'][] = ["name"=>"Emp Code"];
    $data['vehicleExpense'][] = ["name"=>"Emp Name"];
    $data['vehicleExpense'][] = ["name"=>"Vehicle Name"];
    $data['vehicleExpense'][] = ["name"=>"Total Km"];
    $data['vehicleExpense'][] = ["name"=>"Total Price"];

    /* Vehicel Expense Approve Header */

    $data['approveExpense'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
    $data['approveExpense'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"]; 
    $data['approveExpense'][] = ["name"=>"Exp Date"];    
    $data['approveExpense'][] = ["name"=>"Expense No."];
    $data['approveExpense'][] = ["name"=>"Employee"];
    $data['approveExpense'][] = ["name"=>"Vehicle Name"];
    $data['approveExpense'][] = ["name"=>"Demand Amount"];
    $data['approveExpense'][] = ["name"=>"Approved Amount"];
    
    return tableHeader($data[$page]);
}

/* Customer Table Data */
function getPartyData($data){
    $userBtn="";
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Customer'}";
    $editParam = "{'postData':{'id' : ".$data->id.",'party_type':'1'},'modal_id' : 'right_modal_lg', 'form_id' : 'editCustomer', 'title' : 'Update Customer'}";

    $editButton = '<a class="btn btn-success btn-edit" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';
    $deleteButton = '<a class="btn btn-danger btn-delete" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';

    $partyImgPath = (!empty($data->party_image) ? base_url('assets/uploads/party/'.$data->party_image) : base_url('assets/uploads/party/user_default.png') );
    $partyImg = '<a href="'.$partyImgPath.'" target="_blank"> <img src="'.$partyImgPath.'" width="25"></a>';
        
    
    $action = getActionButton($userBtn.$editButton.$deleteButton);

    return [$action,$data->sr_no,$partyImg,$data->party_code,$data->party_name,$data->business_type,$data->contact_person,$data->contact_phone,$data->emp_name,$data->state.', '.$data->district,$data->taluka];
}

/* Customer Complaint Table Data */
function getCustomerComplaintData($data){
    $editButton = $deleteButton = $completeButton = "";
    if(empty($data->status)):
        $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Customer Complaint'}";
        $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';

        $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'right_modal', 'form_id' : 'editCustomerComplaint', 'title' : 'Update Customer Complaint'}";
        $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="mdi mdi-square-edit-outline" ></i></a>';

        $completeParam = "{'postData':{'id':".$data->id.",'status': 1,'msg':'Completed'},'message':'Are you sure want to Complete this Complaint?','fnsave':'completeComplaint'}";
        $completeButton = '<a href="javascript:void(0)" class="btn btn-info permission-approve" onclick="confirmStore('.$completeParam.');" datatip="Complete" flow="down"><i class="fa fa-check"></i></a>';
    endif;
	
	$action = getActionButton($completeButton.$editButton.$deleteButton);
    return [$action,$data->sr_no,$data->trans_number,formatDate($data->trans_date),$data->so_number,formatDate($data->so_date),$data->party_name,'[ '.$data->item_Code.' ] '.$data->item_name,$data->notes];
}

/* Finish Goods Table Data */
function getFinishGoodsData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Finish Good'}";
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';

    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'right_modal', 'form_id' : 'editItem', 'title' : 'Update Finish Good'}";    
    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';

	$downloadButton = ''; $imgFile ='';
    if(!empty($data->img_file)):
        $downloadButton = '<a href="'.base_url('assets/uploads/finish_goods/'.$data->img_file).'" class="btn btn-sm btn-primary" target="_blank"><i class="mdi mdi-download"></i></a>';

        $imgFile = '<img src="'.base_url('assets/uploads/finish_goods/'.$data->img_file).'" width="60" height="60" style="border-radius:0%;border: 0px solid #ccc;padding:3px;">';
    else:
        $imgFile = '<img src="'.base_url('assets/images/icon.png').'" width="60" height="60" style="border-radius:0%;border: 0px solid #ccc;padding:3px;">';
    endif;

    $action = getActionButton($downloadButton.$editButton.$deleteButton);
   
    return [$action,$data->sr_no,$imgFile,htmlentities($data->item_name),$data->category_name,$data->price,floatval($data->primary_packing),floatval($data->master_packing)];
}

/* Item Category Table Data */
function getItemCategoryData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id.",'table_id':'commanTable'},'message' : 'Item Category'}";
    $editParam = "{'postData':{'id' : ".$data->id.",'table_id':'commanTable'},'modal_id' : 'right_modal', 'form_id' : 'editItemCategory', 'title' : 'Update Item Category',}";

    $editButton=''; $deleteButton='';
	if(!empty($data->ref_id)):
        $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';
        $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
    endif;

    $cat_code ='';
	if($data->ref_id ==6 || $data->ref_id == 7):
        $cat_code = (!empty($data->tool_type))?'['.str_pad($data->tool_type,3,'0',STR_PAD_LEFT).'] ':'';
    endif;

    if($data->final_category == 0):
        $data->category_name = $cat_code.'<a href="' . base_url("itemCategory/list/" . $data->id) . '">' . $data->category_name . '</a>';
    else:
        $data->category_name = $cat_code.$data->category_name;
    endif;

    $action = getActionButton($editButton.$deleteButton);

    return [$action,$data->sr_no,$data->category_name,$data->parent_category_name,$data->is_final_text,/* $data->stock_type_text, */$data->is_returnable_text,$data->remark];
}

/* Service Category Table Data */
function getServiceCategoryData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Service Category'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'right_modal', 'form_id' : 'editServiceCategory', 'title' : 'Update Service Category'}";

    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="mdi mdi-square-edit-outline" ></i></a>';
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
	
	$action = getActionButton($editButton.$deleteButton);
    return [$action,$data->sr_no,$data->service_name];
}

/* Service Request Table Data */
function getServiceRequestData($data){
    $editButton = $deleteButton = "";
    if(empty($data->status)):
        $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Service Request'}";
        $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'right_modal', 'form_id' : 'editServiceRequest', 'title' : 'Update Service Request'}";

        $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="mdi mdi-square-edit-outline" ></i></a>';
        $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
        
        $assignParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'right_modal', 'form_id' : 'assignEmployee', 'title' : 'Assign Employee', 'fnedit' : 'assignEmployee', 'fnsave' : 'saveAssignEmployee'}";
        $assignButton = '<a class="btn btn-primary permission-modify" href="javascript:void(0)" datatip="Assign" flow="down" onclick="edit('.$assignParam.');"><i class="fas fa-user-check"></i></a>';
    endif;
	
	$action = getActionButton($editButton.$deleteButton);
    return [$action,$data->sr_no,$data->req_prefix.sprintf("%03d",$data->req_no),formatDate($data->req_date),$data->party_name,"[".$data->item_code."] ".$data->item_name,$data->on_site,$data->description];
}

/* Expense Table Data */
function getExpenseData($data){
    $editButton = $deleteButton = $approveButton = $rejectButton = $selectBox = $downloadButton = $viewBtn = $reOpenButton =""; //27-09-25
    if(empty($data->status)):
        $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Expense'}";
        $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';

        $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'right_modal', 'form_id' : 'editExpense', 'title' : 'Update Expense'}";
        $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';

        $approveParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'right_modal_lg', 'form_id' : 'editExpense', 'title' : 'Approve Expense ','fnedit' : 'getApprovedData' , 'fnsave' : 'saveApprovedData'}";
        $approveButton = '<a class="btn btn-primary btn-edit permission-approve" href="javascript:void(0)" datatip="Approve" flow="down" onclick="edit('.$approveParam.');"><i class="mdi mdi-check"></i></a>';

        $rejectParam = "{'postData': {'id' : ".$data->id.", 'status' : 2,'exp_source':".$data->exp_source."}, 'fnsave' : 'rejectExpense', 'message' : 'Are you sure want to Reject Expense?'}";//27-09-25
        $rejectButton = '<a class="btn btn-warning btn-edit permission-approve" href="javascript:void(0)" datatip="Reject" flow="down" onclick="confirmStore('.$rejectParam.');"><i class="mdi mdi-close"></i></a>';
        
        $selectBox = '<input type="checkbox" name="ref_id[]" id="ref_id_'.$data->sr_no.'" class="filled-in chk-col-success BulkApproveRequest  permission-approve" value="'.$data->id.'"><label for="ref_id_'.$data->sr_no.'"></label>';
	endif;
	if(!empty($data->proof_file)):
		$downloadParam = "{'postData':{'id' : ".$data->id."},'button':'close','modal_id' : 'right_modal', 'fnedit' : 'downloadFiles', 'form_id' : 'downloadFile', 'title' : 'Download Files'}";
		$downloadButton = '<a class="btn btn-info btn-edit permission-modify" href="javascript:void(0)" datatip="Download" flow="down" onclick="edit('.$downloadParam.');"><i class="mdi mdi-download"></i></a>';
	endif;
    
    if($data->status == 1){
        $viewParam = "{'postData':{'id':".$data->id."},'modal_id':'modal-lg','form_id':'viewExpense','fnedit':'viewExpenseDetails','button':'close','title':'Expense Details'}";
        $viewBtn = '<a href="javascript:void(0)" class="btn btn-info btn-edit permission-modify" onclick="edit('.$viewParam.');" datatip="View Lead Details" flow="down"><i class="fas fa-eye"></i></a>';
    }
    //27-09-25
    if($data->status == 2){
        $reOpenParam = "{'postData': {'id' : ".$data->id.", 'status' : 0,'exp_source':".$data->exp_source."}, 'fnsave' : 'rejectExpense', 'message' : 'Are you sure want to Re-Open Expense?'}";
        $reOpenButton = '<a class="btn btn-warning btn-edit permission-approve" href="javascript:void(0)" datatip="Re-Open" flow="down" onclick="confirmStore('.$reOpenParam.');"><i class="mdi mdi-replay"></i></a>';
        $action = getActionButton($reOpenButton);
    }
    $printButton = '<a href="'.base_url('expense/printData/'.$data->id).'" class="btn btn-dribbble permission-modify" target="_blank" datatip="Print" flow="down"><i class="fas fa-print"></i></a>';
   

    $status = (!empty($data->approve_name)) ? $data->approve_name.'<br>'.formatDate($data->approved_at) : '';
	
    $action = getActionButton($approveButton.$viewBtn.$rejectButton.$reOpenButton.$downloadButton.$editButton.$deleteButton);

    return [$action,$data->sr_no,formatDate($data->exp_date),$data->exp_by_name,$data->demand_amount,$data->amount,$status];
}

/* Meeting Table Data */
function getMeetingData($data){
    $editButton = $deleteButton = $startButton =$cancelButton =$attendeeButton =$completeButton ="";
    $type = (($data->trans_type == 1) ? "Meeting": "Event") ;
    if($data->status == 0){
        $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'right_modal', 'form_id' : 'editMeeting', 'title' : 'Update ".$type."'}";
        $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="mdi mdi-square-edit-outline" ></i></a>';

        $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : '".$type."'}";
        $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';

        $startParam = "{'postData':{'id':".$data->id.",'status':1,'type':'".$type."','msg':'Start'},'message':'Are you sure want to Start this ".$type."?','fnsave':'changeMeetStatus'}";
        $startButton = '<a href="javascript:void(0)" class="btn btn-info permission-modify" onclick="confirmStore('.$startParam.');" datatip="Start" flow="down"><i class="fas fa-play"></i></a>';
    
        $cancelParam = "{'postData':{'id':".$data->id.",'status':3,'type':'".$type."','msg':'Cancel'},'message':'Are you sure want to Cancel this ".$type."?','fnsave':'changeMeetStatus'}";
        $cancelButton = '<a href="javascript:void(0)" class="btn btn-dark permission-modify" onclick="confirmStore('.$cancelParam.');" datatip="Cancel" flow="down"><i class="fa fa-close"></i></a>';    

    }
    elseif($data->status == 1){
        if(empty($data->emp_id)){
            $attendeeParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'right_modal', 'form_id' : 'attendee', 'title' : 'Attendee / Participate','fnedit' : 'addParticipate' ,'fnsave':'saveParticipate'}";
            $attendeeButton = '<a class="btn btn-info btn-edit permission-modify" href="javascript:void(0)" datatip="Add Participants" flow="down" onclick="edit('.$attendeeParam.');"><i class="fa fa-check" ></i></a>';
        }
        else{
            $completeParam = "{'postData':{'id':".$data->id.",'status':2,'type':'".$type."','msg':'Complete'},'message':'Are you sure want to Complete this ".$type."?','fnsave':'changeMeetStatus'}";
            $completeButton = '<a href="javascript:void(0)" class="btn btn-success permission-modify" onclick="confirmStore('.$completeParam.');" datatip="Complete " flow="down"><i class="fa fa-check"></i></a>';
        }
    }

	$action = getActionButton($startButton.$attendeeButton.$completeButton.$cancelButton.$editButton.$deleteButton);
    if($data->trans_type == 1){
        return [$action,$data->sr_no,formatDate($data->me_date),$data->me_type,$data->location,$data->description];
    }else{
        return [$action,$data->sr_no,formatDate($data->me_date),$data->me_type,$data->event_name,$data->event_duration,$data->location,$data->description];
    }
}

/* Vehicle Expense Table Data */ //27-09-25
function getVehicleExpenseData($data){ $approveButton ="";
    if(empty($data->approved_by)):
        $approveParam = "{'postData':{'emp_id' : ".$data->emp_id.",'vehicle_id' : ".$data->vehicle_id.",'log_time' : '".date('d-m-Y H:i:s' ,strtotime($data->log_time))."'},'modal_id' : 'bs_approval_modal', 'form_id' : 'editVehicleExpense', 'title' : 'Approve Vehicle Expense ','call_function' : 'getVehicleExpApprovedData' , 'fnsave' : 'saveVehicleExpApprovedData'}";
        $approveButton = '<a class="btn btn-primary btn-edit permission-approve" href="javascript:void(0)" datatip="Approve" flow="down" onclick="modalApproveAction('.$approveParam.');"><i class="mdi mdi-check"></i></a>';
    endif;
	
    $action = getActionButton($approveButton);
    return [$action,$data->sr_no,date('d-m-Y H:i',strtotime($data->log_time)),$data->emp_code,$data->emp_name,$data->vehicle_name,$data->total_km,$data->total_price];
}

/* Vehicle Expense Approve/Reject Table Data */
function getExpenseApproveData($data){
    $reOpenButton ="";
    if($data->status == 2){
        $reOpenParam = "{'postData': {'id' : ".$data->id.", 'status' : 0,'exp_source':".$data->exp_source."}, 'fnsave' : 'rejectExpense', 'message' : 'Are you sure want to Re-Open Expense?'}";
        $reOpenButton = '<a class="btn btn-warning btn-edit permission-approve" href="javascript:void(0)" datatip="Re-open" flow="down" onclick="confirmStore('.$reOpenParam.');"><i class="mdi mdi-replay"></i></a>';
    }
    $action = getActionButton($reOpenButton);
	
    return [$action,$data->sr_no,formatDate($data->exp_date),$data->exp_number,$data->exp_by_name,$data->vehicle_name,$data->demand_amount,$data->amount];
}

?>