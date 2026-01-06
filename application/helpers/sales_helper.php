<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

function getSalesDtHeader($page){

    /* Sales Quotation Request Header */
    $data['salesQuotRequest'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
	$data['salesQuotRequest'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"]; 
	$data['salesQuotRequest'][] = ["name"=>"SE. No."];
	$data['salesQuotRequest'][] = ["name"=>"SE. Date"];
	$data['salesQuotRequest'][] = ["name"=>"Customer Name"];
	$data['salesQuotRequest'][] = ["name"=>"Item Name"];
    $data['salesQuotRequest'][] = ["name"=>"Qty"];

    /* Sales Quotation Header */
    $data['salesQuotation'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
	$data['salesQuotation'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"]; 
	$data['salesQuotation'][] = ["name"=>"SQ. No."];
	$data['salesQuotation'][] = ["name"=>"SQ. Date"];
	$data['salesQuotation'][] = ["name"=>"Customer Name"];
	$data['salesQuotation'][] = ["name"=>"Item Name"];
    $data['salesQuotation'][] = ["name"=>"Qty"];
    $data['salesQuotation'][] = ["name"=>"Price"];

    /* Sales Order Header */
    $data['salesOrder'][] = ["name"=>"Action"];
    $data['salesOrder'][] = ["name"=>"#","textAlign"=>"center"];
    $data['salesOrder'][] = ["name"=>"Attachment","textAlign"=>"center"];
    $data['salesOrder'][] = ["name"=>"SO. No.","textAlign"=>"center"];
    $data['salesOrder'][] = ["name"=>"SO. Date","style"=>"width:10%;","textAlign"=>"center"];
    $data['salesOrder'][] = ["name"=>"Customer Name"];
    $data['salesOrder'][] = ["name"=>"Amount"];
    $data['salesOrder'][] = ["name"=>"GST"];
    $data['salesOrder'][] = ["name"=>"Net Amount"];   

    /* Return Order Header */
    $data['returnOrder'][] = ["name"=>"Action"];
    $data['returnOrder'][] = ["name"=>"#","textAlign"=>"center"];
    $data['returnOrder'][] = ["name"=>"SO. No.","textAlign"=>"center"];
    $data['returnOrder'][] = ["name"=>"SO. Date","style"=>"width:10%;","textAlign"=>"center"];
    $data['returnOrder'][] = ["name"=>"Customer Name"];
    $data['returnOrder'][] = ["name"=>"Item Name"];
    $data['returnOrder'][] = ["name"=>"Qty."];
    
    return tableHeader($data[$page]);
}

/* Sales Quotation Request Table data */
function getSqRequestData($data){
    $quotationBtn="";
    if(empty($data->trans_status)):
        $quotationParam = "{'postData':{'ref_id' : ".$data->trans_main_id.", 'party_id' : '".$data->party_id."', 'lead_id' : '".$data->lead_id."', 'type' : '1','entry_type':'4','module_type':'2'}, 'modal_id' : 'right_modal_lg', 'form_id' : 'addResponse', 'title' : 'Create Quotation [ ".$data->party_name." ]', 'fnedit' : 'addSalesData', 'fnsave' : 'saveSalesData'}";
        $quotationBtn = '<a class="btn btn-primary permission-modify" href="javascript:void(0)" datatip="Create Quotation" flow="down" onclick="edit('.$quotationParam.');"><i class="fa fa-file-alt"></i></a>'; 
    endif;

    $action = getActionButton($quotationBtn);
    return [$action,$data->sr_no,$data->trans_number,formatDate($data->trans_date),$data->party_name,$data->item_name,floatVal($data->qty)];
}

/* Sales Quotation Table data */
function getSalesQuotationData($data){
    $editButton = $deleteButton = $approveButton = $cancelButton = $orderBtn = "";
    if(empty($data->trans_status))
    {    
        $editParam = "{'postData':{'id' : ".$data->trans_main_id."},'modal_id' : 'right_modal_lg', 'form_id' : 'editQuotation', 'title' : 'Update Sales Quotation [ ".$data->party_name." ]', 'fnedit' : 'editSalesQuotation', 'fnsave' : 'saveSalesData','txt_editor' : 'conditions'}";
        $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';

        $deleteParam = "{'postData':{'id' : ".$data->trans_main_id."},'message' : 'Sales Quotation','fndelete' : 'deleteSalesQuotation'}";
        $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';

        $approveParam = "{'postData':{'id':".$data->id.",'trans_status': 1,'msg':'Approved'},'message':'Are you sure want to Approve this Quotation?','fnsave':'changeQuotStatus'}";
        $approveButton = '<a href="javascript:void(0)" class="btn btn-info permission-approve" onclick="confirmStore('.$approveParam.');" datatip="Approve Quotation" flow="down"><i class="fa fa-check"></i></a>';

        $cancelParam = "{'postData':{'id':".$data->id.",'trans_status': 3,'msg':'Cancel'},'message':'Are you sure want to Cancel this Quotation ?','fnsave':'changeQuotStatus'}";
        $cancelButton = '<a href="javascript:void(0)" class="btn btn-dark permission-approve" onclick="confirmStore('.$cancelParam.');" datatip="Cancel Quotation" flow="down"><i class="fa fa-close"></i></a>';    
    }
    elseif($data->trans_status == 1)
    {
        $orderParam = "{'postData':{'ref_id' : ".$data->trans_main_id.",'party_id' : '".$data->party_id."','lead_id' : '".$data->lead_id."','entry_type' : '5','module_type':3},'modal_id' : 'right_modal_lg', 'form_id' : 'addSalesOrder', 'title' : 'Add Sales Order  [ ".$data->party_name." ] ', 'fnedit' : 'addSalesData', 'fnsave' : 'saveSalesData'}";
        $orderBtn = '<a class="btn btn-primary btn-edit permission-modify" href="javascript:void(0)" datatip="Create Order" flow="down" onclick="edit('.$orderParam.');"><i class="fa fa-file-alt"></i></a>';
    }

    $action = getActionButton($orderBtn.$approveButton.$cancelButton.$editButton.$deleteButton);

    return [$action,$data->sr_no,$data->trans_number,formatDate($data->trans_date),$data->party_name,$data->item_name,$data->qty,$data->price];
}

/* Sales Order Table data */
function getSalesOrderData($data){
    $editButton = $deleteButton = $approveButton = $cancelButton = $orderBtn = $completeButton = $dispatchButton = "";
    if(empty($data->trans_status))
    {    
        $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'right_modal_lg', 'form_id' : 'editOrder', 'title' : 'Update Sales Order [ ".$data->party_name." ]', 'fnedit' : 'editSalesOrder', 'fnsave' : 'saveSalesData','txt_editor' : 'conditions'}";
        $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';

        $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Sales Order','fndelete' : 'deleteSalesOrder'}";
        $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';

        $approveParam = "{'postData':{'id':".$data->id.",'trans_status': 1,'msg':'Approved'},'message':'Are you sure want to Approve this Order?','fnsave':'changeOrderStatus'}";
        $approveButton = '<a href="javascript:void(0)" class="btn btn-info permission-approve" onclick="confirmStore('.$approveParam.');" datatip="Approve Order" flow="down"><i class="fa fa-check"></i></a>';

        $cancelParam = "{'postData':{'id':".$data->id.",'trans_status': 3,'msg':'Cancel'},'message':'Are you sure want to Cancel this Order ?','fnsave':'changeOrderStatus'}";
        $cancelButton = '<a href="javascript:void(0)" class="btn btn-dark permission-approve" onclick="confirmStore('.$cancelParam.');" datatip="Cancel Order" flow="down"><i class="fa fa-close"></i></a>';    
    }
    elseif($data->trans_status == 1)
    {
        $dispatchParam = "{'postData':{'id' : ".$data->id.", 'trans_number' : '".$data->trans_number."'},'modal_id' : 'right_modal_lg', 'form_id' : 'dispatchPlan', 'title' : 'Add Dispatch', 'fnedit' : 'addDispatch', 'fnsave' : 'saveDispatch', 'button' : 'close'}";
        $dispatchButton = '<a class="btn btn-success permission-modify" href="javascript:void(0)" datatip="Dispatch" flow="down" onclick="edit('.$dispatchParam.');"><i class="fas fa-plus"></i></a>';
    }
    
    $printBtn = '<a href="'.base_url('lead/printOrder/'.$data->id).'" class="btn btn-dribbble permission-modify" target="_blank" datatip="Print" flow="down"><i class="fas fa-print"></i></a>';
    
    $action = getActionButton($dispatchButton.$completeButton.$printBtn.$approveButton.$cancelButton.$editButton.$deleteButton);
    $attachment ='';
    if(!empty($data->order_file)):
        $attachment = '<img src="'.base_url('assets/uploads/sales_order/'.$data->order_file).'" width="60" height="60" style="border-radius:0%;border:0px solid #ccc;padding:3px;">';
    else:
        $attachment = '<img src="'.base_url('assets/images/icon.png').'" width="60" height="60" style="border-radius:0%;border: 0px solid #ccc;padding:3px;">';
    endif;
    return [$action,$data->sr_no,$attachment,$data->trans_number,formatDate($data->trans_date),$data->party_name,$data->taxable_amount,$data->gst_amount,$data->net_amount];
}

/* Return Order Table data */
function getReturnOrderData($data){
    $editOrd = "";$deleteButton="";$acceptButton="";
    
    if(empty($data->trans_status)){
        $editOrdParam = "{'postData':{'id' : ".$data->trans_main_id."},'modal_id' : 'right_modal', 'form_id' : 'editOrder', 'title' : 'Update Return Order [ ".$data->party_name." ]', 'fnedit' : 'editReturnOrder', 'fnsave' : 'saveReturnOrder'}";
        $editOrd = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editOrdParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';

        $deleteParam = "{'postData':{'id' : ".$data->trans_main_id."},'message' : 'Return Order','fndelete' : 'deleteSalesOrder'}";
        $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
    
        $acceptParam = "{'postData':{'id':".$data->trans_main_id.",'trans_status': 4,'msg':'Accepted'},'message':'Are you sure want to Accept this Order?','fnsave':'changeOrderStatus'}";
        $acceptButton = '<a href="javascript:void(0)" class="btn btn-info permission-modify" onclick="confirmStore('.$acceptParam.');" datatip="Accept" flow="down"><i class="fa fa-check"></i></a>';
    }
    $action = getActionButton($acceptButton.$editOrd.$deleteButton);

    return [$action,$data->sr_no,$data->trans_number,formatDate($data->trans_date),$data->party_name,'[ '.$data->item_code.' ] '.$data->item_name,floatVal($data->qty)]; // 02-04-2024
}
?>