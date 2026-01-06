<?php $this->load->view('includes/header'); ?>
<div class="page-content-tab">
	<div class="container-fluid">
		<div class="row">
			<div class="page-title-box">	
				<ul class="nav nav-pills">
					<li class="nav-item"> 
						<button onclick="statusTab('vehicleExpTable',0,'getMasterDtHeader','vehicleExpense');" class="nav-tab btn waves-effect waves-light btn-outline-warning active" style="outline:0px" data-bs-toggle="tab" aria-expanded="false">Pending</button> 
					</li>
					<li class="nav-item"> 
						<button onclick="statusTab('vehicleExpTable',1,'getMasterDtHeader','approveExpense');" class="nav-tab btn waves-effect waves-light btn-outline-success" style="outline:0px" data-bs-toggle="tab" aria-expanded="false">Approved</button> 
					</li>
					<li class="nav-item"> 
						<button onclick="statusTab('vehicleExpTable',2,'getMasterDtHeader','approveExpense');" class="nav-tab btn waves-effect waves-light btn-outline-danger" style="outline:0px" data-bs-toggle="tab" aria-expanded="false">Rejected</button> 
					</li>
				</ul>
			</div>
		</div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id='vehicleExpTable' class="table table-bordered ssTable" data-url='/getVehicleExpDTRows'></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>
<div class="modal modal-right fade" id="bs_approval_modal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" >
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title m-0"></h6>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body"></div>
			<div class="modal-footer modal-footer-fixed">
				<button type="button" class="btn btn-secondary press-close-btn btn-close-modal save-form" data-bs-dismiss="modal"><i class="fa fa-times"></i> Close</button>

                <button type="button" class="btn btn-success approveBtn" onclick="approve()"><i class="fa fa-check"></i> Approve</button>

                <button type="button" class="btn btn-danger  rejectBtn" onclick="reject()"><i class="fa fa-close"></i> Reject</button>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('includes/footer'); ?>
<script>
    
    function modalApproveAction(data){
        var call_function = data.call_function;
        if(call_function == "" || call_function == null){call_function="edit";}

        var fnsave = data.fnsave;
        if(fnsave == "" || fnsave == null){fnsave="save";}

        var controllerName = data.controller;
        if(controllerName == "" || controllerName == null){controllerName=controller;}	

        $.ajax({ 
            type: "POST",   
            url: base_url + controllerName + '/' + call_function,   
            data: data.postData,
        }).done(function(response){
            initApprovalModal(data,response);
        });
    }

    function initApprovalModal(postData,response){
        var button = postData.button;if(button == "" || button == null){button="both";};
        var fnedit = postData.fnedit;if(fnedit == "" || fnedit == null){fnedit="edit";}
        var fnsave = postData.fnsave;if(fnsave == "" || fnsave == null){fnsave="save";}
        var controllerName = postData.controller;if(controllerName == "" || controllerName == null){controllerName=controller;}
        var savebtn_text = postData.savebtn_text;
        var savebtn_icon = postData.savebtn_icon || "";
        if(savebtn_text == "" || savebtn_text == null){savebtn_text='<i class="fa fa-check"></i> Save';}
        else{ savebtn_text = ((savebtn_icon != "")?'<i class="'+savebtn_icon+'"></i> ':'')+savebtn_text; }

        var resFunction = postData.res_function || "";
        var jsStoreFn = postData.js_store_fn || 'store';
        var txt_editor = postData.txt_editor || '';
        var form_close = postData.form_close || '';
        var message = postData.message || '';

        var fnJson = "{'formId':'"+postData.form_id+"','fnsave':'"+fnsave+"','controller':'"+controllerName+"','txt_editor':'"+txt_editor+"','form_close':'"+form_close+"','message':'"+message+"'}";

        $("#"+postData.modal_id).modal('show');
		$("#"+postData.modal_id).css({'z-index':1059,'overflow':'auto'});

        $("#"+postData.modal_id).addClass(postData.form_id+"Modal");
        $("#"+postData.modal_id+' .modal-title').html(postData.title);
        $("#"+postData.modal_id+' .modal-body').html('');
        $("#"+postData.modal_id+' .modal-body').html(response);
        $("#"+postData.modal_id+" .modal-body form").attr('id',postData.form_id);
        if(resFunction != ""){
            $("#"+postData.modal_id+" .modal-body form").attr('data-res_function',resFunction);
        }
        $("#"+postData.modal_id+" .modal-footer .btn-save").html(savebtn_text);
        $("#"+postData.modal_id+" .modal-footer .btn-save").attr('onclick',jsStoreFn+"("+fnJson+");");
        $("#"+postData.modal_id+" .btn-custom-save").attr('onclick',jsStoreFn+"("+fnJson+");");

        $("#"+postData.modal_id+" .modal-header .btn-close").attr('data-modal_id',postData.modal_id);
        $("#"+postData.modal_id+" .modal-header .btn-close").attr('data-modal_class',postData.form_id+"Modal");
        $("#"+postData.modal_id+" .modal-footer .btn-close-modal").attr('data-modal_id',postData.modal_id);
        $("#"+postData.modal_id+" .modal-footer .btn-close-modal").attr('data-modal_class',postData.form_id+"Modal");

        $("#"+postData.modal_id+" .modal-footer .approveBtn").attr('onclick',"approve("+fnJson+");");
        $("#"+postData.modal_id+" .modal-footer .rejectBtn").attr('onclick',"reject("+fnJson+");");
        if(button == "close"){
            $("#"+postData.modal_id+" .modal-footer .btn-close-modal").show();
            $("#"+postData.modal_id+" .modal-footer .btn-save").hide();
        }else if(button == "save"){
            $("#"+postData.modal_id+" .modal-footer .btn-close-modal").hide();
            $("#"+postData.modal_id+" .modal-footer .btn-save").show();
        }else{
            $("#"+postData.modal_id+" .modal-footer .btn-close-modal").show();
            $("#"+postData.modal_id+" .modal-footer .btn-save").show();
        }
        setTimeout(function(){ 
            initMultiSelect();setPlaceHolder();setMinMaxDate();initSelect2();		
        }, 5);
        setTimeout(function(){
            $('#'+postData.modal_id+'  :input:enabled:visible:first, select:first').focus();
        },500);
    }

    function approve(postData){
        var input = $("<input>").attr("type", "hidden").attr("name", "status").val(1);
        $('#'+postData.formId).append($(input));
        confirmStore(postData);
    }
    function reject(postData){
        var input = $("<input>").attr("type", "hidden").attr("name", "status").val(2);
        $('#'+postData.formId).append($(input));
        confirmStore(postData);
    }
  
</script>
