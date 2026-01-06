<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/* get Pagewise Table Header */
function getConfigDtHeader($page){

    /* Custom Field Header */
    $data['customField'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
    $data['customField'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"]; 
    $data['customField'][] = ["name"=>"Field"];
    $data['customField'][] = ["name"=>"Field Type"];

    /* Sales Zone Header */
    $data['salesZone'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
    $data['salesZone'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"]; 
    $data['salesZone'][] = ["name"=>"Type"];
    $data['salesZone'][] = ["name"=>"Zone Name"];
    $data['salesZone'][] = ["name"=>"Remark"];

    /* Bussiness Type Header */
    $data['businessType'][] = ["name"=>"Action","style"=>"width:5%;"];
    $data['businessType'][] = ["name"=>"#","style"=>"width:5%;"];
    $data['businessType'][] = ["name"=>"Type Name"];
    $data['businessType'][] = ["name"=>"Parent Type"];
    $data['businessType'][] = ["name"=>"Remark"];

    /* Source Master Header */
    $data['selectOption'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
    $data['selectOption'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"]; 
    $data['selectOption'][] = ["name"=>"Option"];
	$data['selectOption'][] = ["name"=>"Remark"];

    /* Discount Structure Header */
    $data['discountStructure'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
    $data['discountStructure'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"]; 
    $data['discountStructure'][] = ["name"=>"Structure "];

    /* Lead Stages Header */
    $data['leadStages'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
    $data['leadStages'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"]; 
    $data['leadStages'][] = ["name"=>"Stage Type"];

    /* Country Header */
    $data['country'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
    $data['country'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"]; 
    $data['country'][] = ["name"=>"Country"];

    /* State Header */
    $data['state'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
    $data['state'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"]; 
    $data['state'][] = ["name"=>"Country"];
    $data['state'][] = ["name"=>"State"];
    $data['state'][] = ["name"=>"State Code"];

    /* District Header */
    $data['district'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
    $data['district'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"]; 
    $data['district'][] = ["name"=>"Country"];
    $data['district'][] = ["name"=>"State"];
    $data['district'][] = ["name"=>"State Code"];
    $data['district'][] = ["name"=>"District"];

    /* Taluka Header */
    $data['taluka'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
    $data['taluka'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"]; 
    $data['taluka'][] = ["name"=>"Country"];
    $data['taluka'][] = ["name"=>"State"];
    $data['taluka'][] = ["name"=>"State Code"];
    $data['taluka'][] = ["name"=>"District"];
    $data['taluka'][] = ["name"=>"Taluka"];
	
	/* terms header */
    $data['terms'][] = ["name"=>"Action",'textAlign'=>'center',"sortable"=>"FALSE"];
    $data['terms'][] = ["name"=>"#",'textAlign'=>'center',"sortable"=>"FALSE"]; 
    $data['terms'][] = ["name"=>"Title"];
    $data['terms'][] = ["name"=>"Type"];
    $data['terms'][] = ["name"=>"Conditions"];

    /* Head Quarter Header */
    $data['headQuarter'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
    $data['headQuarter'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"]; 
    $data['headQuarter'][] = ["name"=>"Head Quarter"];
	$data['headQuarter'][] = ["name"=>"Lat-Long"];
	$data['headQuarter'][] = ["name"=>"Address"];
	$data['headQuarter'][] = ["name"=>"Remark"];
	
	/* Notice Board Header */
    $data['noticeBoard'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
    $data['noticeBoard'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"]; 
    $data['noticeBoard'][] = ["name"=>"Title"];
	$data['noticeBoard'][] = ["name"=>"Description"];
	$data['noticeBoard'][] = ["name"=>"From date"];
	$data['noticeBoard'][] = ["name"=>"To Date"];
	$data['noticeBoard'][] = ["name"=>"Reminder Days"];

    return tableHeader($data[$page]);
}

/* Custom Field Table Data */
function getCustomFieldData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Cusstom Field','fndelete':'deleteCustomField'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'right_modal', 'form_id' : 'editcustomField', 'title' : 'Update Field Option','fnsave':'saveCustomField','fnedit':'editCustomField'}";
    
    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="mdi mdi-square-edit-outline" ></i></a>';

    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';

	$fieldBtn = "";
    if($data->field_type == 'SELECT'){
        $fieldParam = "{'postData':{'type' : ".$data->id."},'modal_id' : 'right_modal', 'form_id' : 'addMasterOption','button' : 'close', 'title' : 'Add Master','fnsave':'save','fnedit':'addMasterOption'}";
        $fieldBtn = '<a class="btn btn-primary btn-edit permission-modify" href="javascript:void(0)" datatip="Master" flow="down" onclick="edit('.$fieldParam.');"><i class="fas fa-plus" ></i></a>';
    }    

	$action = getActionButton($fieldBtn.$editButton);
    return [$action,$data->sr_no,$data->field_name,$data->field_type];
}

/* Sales Zone Table Data */
function getSalesZoneData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Sales Zone'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'right_modal', 'form_id' : 'editSalesZone', 'title' : 'Update Sales Zone'}";
    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';

    $title ="";$statutoryBtn = "";
    if($data->type != 1){
        $modal_id = 'right_modal';$button="both";
        if($data->type == 3){  $title ="Add State"; $modal_id = 'right_modal';}
        elseif($data->type == 4){  $title ="Add District"; }
        elseif($data->type == 5){  $title ="Add Taluka"; $button="close";}
        
        $statutoryParam = "{'postData':{'id' : ".$data->id.",'type' : ".$data->type.",'statutory_id':'".$data->statutory_id."','state_id':'".$data->state_id."','zone_name':'".$data->zone_name."'},'modal_id' : '".$modal_id."', 'form_id' : 'editSalesZone', 'title' : '". $title."','fnedit':'addStatutoryDetail','fnsave':'saveStatutoryDetail','button':'".$button."'}";
        $statutoryBtn = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="'.$title.'" flow="down" onclick="edit('.$statutoryParam.');"><i class="fas fa-list"></i></a>';
    }
    $action = getActionButton($statutoryBtn.$editButton.$deleteButton);
    return [$action,$data->sr_no,$data->zone_type,$data->zone_name,$data->remark];   
}

/* Business Type Table Data */
function getBusinessTypeData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id.",'type_name':'".$data->type_name."'},'message' : 'Business Type'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'right_modal', 'form_id' : 'editBusinessType', 'title' : 'Update Business Type'}";

    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="mdi mdi-square-edit-outline" ></i></a>';
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
	
	$action = getActionButton($editButton.$deleteButton);
    return [$action,$data->sr_no,$data->type_name,$data->parentType,$data->remark];
}

/* Select Option Table Data */
function getSelectOptionData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Option'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'right_modal', 'form_id' : 'editOption', 'title' : 'Update Option'}";

    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="mdi mdi-square-edit-outline" ></i></a>';
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
	
	$action = getActionButton($editButton.$deleteButton);
    return [$action,$data->sr_no,$data->label,$data->remark];
}

/* Discount Structure Table Data */
function getDiscountStructureData($data){
       
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'right_modal_xl', 'form_id' : 'editDiscount'}";
    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="mdi mdi-square-edit-outline" ></i></a>';

    $action = getActionButton($editButton);
    return [$action,$data->sr_no,$data->structure_name];
}

/* Lead Stages Table Data */
function getLeadStagesData($data){   
    $editButton = '';$deleteButton="";
    if(empty($data->is_system)): 
        $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'right_modal', 'form_id' : 'editLeadStage'}";
        $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="mdi mdi-square-edit-outline" ></i></a>';

        $deleteParam = "{'postData':{'id' : ".$data->id."}}";
        $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
    endif;

    $action = getActionButton($editButton.$deleteButton);
    return [$action,$data->sr_no,$data->stage_type];
}

/* Country Table Data */
function getCountryData($data){   
    $editParam = "{'postData':{'id' : ".$data->id.", 'type' : ''},'modal_id' : 'right_modal', 'form_id' : 'editCountry'}";
    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="mdi mdi-square-edit-outline" ></i></a>';

    $deleteParam = "{'postData':{'id' : ".$data->id.", 'type' : ''}}";
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';

    $action = getActionButton($editButton.$deleteButton);
    return [$action,$data->sr_no,$data->name];
}

/* State Table Data */
function getStateData($data){   
    $editParam = "{'postData':{'id' : ".$data->id.", 'type' : 'State'},'modal_id' : 'right_modal', 'form_id' : 'editState'}";
    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="mdi mdi-square-edit-outline" ></i></a>';

    $deleteParam = "{'postData':{'id' : ".$data->id.", 'type' : 'State'}}";
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';

    $action = getActionButton($editButton.$deleteButton);
    return [$action,$data->sr_no,$data->name,$data->state,$data->state_code];
}

/* District Table Data */
function getDistrictData($data){   
    $editParam = "{'postData':{'id' : ".$data->id.", 'type' : 'District'},'modal_id' : 'right_modal', 'form_id' : 'editDistrict'}";
    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="mdi mdi-square-edit-outline" ></i></a>';

    $deleteParam = "{'postData':{'id' : ".$data->id.", 'type' : 'District'}}";
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';

    $action = getActionButton($editButton.$deleteButton);
    return [$action,$data->sr_no,$data->name,$data->state,$data->state_code,$data->district];
}

/* Taluka Table Data */
function getTalukaData($data){   
    $editParam = "{'postData':{'id' : ".$data->id.", 'type' : 'Taluka'},'modal_id' : 'right_modal', 'form_id' : 'editTaluka'}";
    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="mdi mdi-square-edit-outline" ></i></a>';

    $deleteParam = "{'postData':{'id' : ".$data->id.", 'type' : 'Taluka'}}";
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';

    $action = getActionButton($editButton.$deleteButton);
    return [$action,$data->sr_no,$data->name,$data->state,$data->state_code,$data->district,$data->taluka];
}

/* Terms Table Data */
function getTermsData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id.", 'conditions' : '".$data->conditions."'},'message' : 'Terms'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'right_modal', 'form_id' : 'editTerms', 'title' : 'Update Terms','txt_editor' : 'conditions'}";

    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="mdi mdi-square-edit-outline" ></i></a>';

    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
	
	$action = getActionButton($editButton.$deleteButton);
    return [$action,$data->sr_no,$data->title,str_replace(',',', ',$data->type),$data->conditions];
}

/* Head Quarter Table Data */
function getHeadQuarterData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Head Quarter'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'right_modal', 'form_id' : 'editHeadQuarter', 'title' : 'Update Head Quarter'}";

    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="mdi mdi-square-edit-outline" ></i></a>';
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
	
	$action = getActionButton($editButton.$deleteButton);
    return [$action,$data->sr_no,$data->name,$data->hq_lat_lng,$data->hq_add,$data->remark];
}

/* Notice Board Table Data */
function getNoticeBoardData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Notice Board'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'right_modal', 'form_id' : 'editNoticeBoard', 'title' : 'Update Notice Board'}";

    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="mdi mdi-square-edit-outline" ></i></a>';

    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
	
	$action = getActionButton($editButton.$deleteButton);
    return [$action,$data->sr_no,$data->title,$data->description,formatDate($data->from_date),formatDate($data->to_date),$data->reminder_days];
}

?>