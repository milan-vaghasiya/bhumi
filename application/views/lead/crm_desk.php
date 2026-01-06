<?php 
    $this->load->view('includes/header');
    $assignExt = "{'postData':{'id':''},'fnsave':'saveBulkExecutive','modal_id':'right_modal','form_id':'addBulkExecutive','fnedit':'addBulkExecutive'}";
	$excelParam = "{'postData':{'id':''},'fnsave':'saveApproachExcel','modal_id':'bottom_modal','form_id':'addApproachExcel','fnedit':'addApproachExcel', 'title' : 'Add Approaches Excel'}";
?>
<link href="<?=base_url()?>assets/plugins/tobii/tobii.min.css" rel="stylesheet" type="text/css" />
<style>
    .nav-pills .select2-container--default .select2-selection--single .select2-selection__rendered{line-height:32px!important;}.nav-pills .select2-container .select2-selection--single{height:32px!important;border-color: #557EF8!important;}
</style>
<div class="page-content-tab">
	<div class="container-fluid">
		<div class="row mb-1">
			<div class="col-sm-12">
				<div class="float-end">
					<button type="button" class="btn btn-info btn-sm float-right addNew permission-write press-add-btn" data-button="both" data-modal_id="right_modal_lg" data-function="addParty" data-controller="parties" data-postdata='{"party_type":"2"}' data-form_title="Add Approaches" data-js_store_fn="saveLead"><i class="fa fa-plus"></i> Add Approaches</button>
					<a href="javascript:void(0)" class="btn btn-dribbble btn-sm float-right permission-modify mr-2" onclick="leadEdit(<?=$assignExt?>);" data-msg="Bulk Executive" flow="down"><i class="fa fa-plus"></i> Bulk Asign</a>
					<a href="javascript:void(0)" class="btn btn-success btn-sm float-right permission-modify mr-2" onclick="leadEdit(<?=$excelParam?>);" datatip="Add Approaches Excel" flow="down"><i class="fas fa-file-excel"></i> Excel</a>
				</div>
				<ul class="nav nav-pills" id="buttonFilter" style="width:60%;">
					<li class="nav-item"> <button data-filter=".pending_response" class="buttonFilter stageFilter btn btn-outline-primary active activeStage" data-postdata='{"party_type":""}' data-party_type="" data-bs-toggle="tab">Pending Response</button> </li>
					<?php
						if(!empty($stageList)){
							foreach($stageList as $row) {
								if($row->sequence != 1){
									?>
									<li class="nav-item"> <button data-filter=".<?=$row->id?>_lead" class="buttonFilter stageFilter mr-1 btn btn-outline-primary" data-party_type="<?=$row->id?>" data-postdata='{"party_type":"<?=$row->id?>"}' data-bs-toggle="tab"><?=$row->stage_type?></button> </li>
									<?php
								}
							}
						}
					?>
					<li style="width:150px;">
					    <select name="lead_source" id="lead_source" class="form-control select2">
    			            <option value="All">All source</option>
                            <?php
                                foreach($sourceList as $row):
                                    echo '<option value="'.$row->label.'" >'.$row->label.'</option>';
                                endforeach;
                            ?>
                        </select>
					</li>
				</ul>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-9">
                <div class="crm-desk-left" id="leadBoard">
					<div class="cd-search mb-1">
						<div class="form-group"> 
							<div class="jpsearch" id="qs">                                                
							   <input type="text" id="quick_search" class="form-control qs quicksearch" placeholder="Search Here...">
							</div>                                                    
						</div>
					</div>
					<div class="cd-body-left" data-simplebar>
						<div class="cd-list jpPanel-widget">
							<!--<div class="loading_wrapper">
								<?php
									/*for($i=0;$i<=6;$i++)
									{
										echo '<div class="wrapper-cell"><div class="image"></div><div class="text"><div class="text-line"></div><div class="text-line"></div><div class="text-line"></div><div class="text-line"></div></div></div>';
									}*/
								?>
							</div>-->
							<div class="card-body row grid leadData"></div>
						</div>
					</div>
                </div>
			</div>
			<div class="col-lg-3">
			    <div class="crm-desk-right" id="partyData">
                    <div class="cd-header">
                        <a class="media">
                            <!-- <div class="media-left">
                                <img src="<?=base_url()?>assets/images/icon.png" alt="user" class="rounded-circle thumb-sm">
                            </div> -->
                            <div class="media-body">
                                <div class="row">
                                    <h6 class="m-0 partyName">NATIVEBIT TECHNOLOGIES LLP</h6>
                                    <p class="mb-0 lastSeen">Welcomes You</p>
                                </div>
                            </div>
                        </a>
                        <div class="cd-features visually-hidden">
							<div class="dropdown d-inline-block">
								<a class="dropdown-toggle salesOption" id="dLabel1" data-bs-toggle="dropdown" href="javascript:void(0)" role="button" aria-haspopup="false" aria-expanded="false">
									<i class="las la-ellipsis-v font-24 text-muted"></i>
								</a>
								<div class="dropdown-menu dropdown-menu-end" aria-labelledby="dLabel1" style="">
									<a type="button" class="text-primary dropdown-item addCrmForm permission-write " data-button="both" data-modal_id="right_modal_lg" data-function="addSalesData" data-fnsave="saveSalesData" data-module_type="1" data-form_title="Add Enquiry" datatip="Add Enquiry"><i class="far fa-question-circle align-text-bottom"></i> Sales Enquiry</a>

									<div class="dropdown-divider mb-0"></div>

									<a type="button" class="text-info dropdown-item addCrmForm permission-write " data-button="both" data-modal_id="right_modal_lg" data-function="addSalesData" data-fnsave="saveSalesData" data-module_type="2" data-form_title="Add Quotation" datatip="Add Quotation"><i class="far fa-file-alt align-text-bottom"></i> Sales Quotation</a>

									<div class="dropdown-divider mb-0"></div>

									<a type="button" class="text-success dropdown-item addCrmForm permission-write " data-button="both" data-modal_id="right_modal_lg"  data-function="addSalesData" data-fnsave="saveSalesData" data-module_type="3"  data-form_title="Add Order" datatip="Add Order"><i class="mdi mdi-cart-plus align-text-bottom"></i> Sales Order</a>
								</div>
							</div>
                            <div class="d-none d-sm-inline-block">

								<a type="button" class="text-danger permission-write btn-remind dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside"   data-form_title="Add Reminder" datatip="Add Reminder" data-bind="enable: !noResults()"><i class="far fa-bell fs-22"></i></a>

								
								
								<div class="dropdown-menu dropdown-lg" id="remind_dd">
									<form id="reminderForm">
										<div class="col-md-12">
											<div class="row">
												<input type="hidden" name="id" id="id" value="" />
						                        <input type="hidden" name="party_id" id="party_id" value="" />
						                        <input type="hidden" name="lead_id" id="lead_id" value="" />
						                        <input type="hidden" name="log_type" id="log_type" value="3" />
												<div class="col-md-6 form-group">
													<label for="ref_date">Date</label>
													<input type="date" name="ref_date" id="ref_date" class="form-control req" value="<?=(!empty($dataRow->ref_date ))?$dataRow->ref_date :date("Y-m-d")?>" min="<?=date("Y-m-d")?>" />
												</div>
												<div class="col-md-6 form-group">
													<label for="reminder_time">Time</label>
													<input type="time" name="reminder_time" id="reminder_time" class="form-control req" value="<?=(!empty($dataRow->reminder_time))?date("h:i:s",strtotime($dataRow->reminder_time)):date("h:i:s")?>" min="<?=date("h:i:s")?>" />
												</div>
												<!-- 13-03-2024 -->
												<div class="col-md-12 form-group">
													<label for="mode">Mode</label>
													<select name="mode" id="mode" class="form-control req select2">
														<?php
															foreach($this->appointmentMode as $key=>$mode):
																$selected = (!empty($dataRow->mode) and $dataRow->mode == $mode)?"selected":"";
																echo '<option value="'.$mode.'" '.$selected .'>'.$mode.'</option>';
															endforeach;
														?>
													</select>
												</div>
												<div class="col-md-12 form-group">
													<label for="notes">Notes</label>
													<textarea name="notes" class="form-control" rows="3"><?=(!empty($dataRow->notes))?$dataRow->notes:""?></textarea>
												</div>
												<div class="col-md-12">
													<button type="button" class="btn btn-success btn-round btn-outline-dashed btn-block saveReminder" >Save Reminder</button>
												</div>
											</div>        
										</div>
									</form>
								</div>
							</div>
                        </div>
                    </div>
                    <div class="cd-body cd_body" id="cd_body" data-simplebar style="overflow1:scroll;" >
                        <div class="cd-detail salesLog1 slimscroll activity-scroll" id="salesLog" >
							<div class="activity salesLog">
								<img src="<?=base_url('assets/images/background/crm_desk_bg.png')?>" style="width:90%;position:absolute;bottom:28px;left:5%;">
							</div>
						</div>                                              
                    </div>
					<div class="cd-footer visually-hidden">
                        <textarea type="text" rows="1" name="msg_content" id="msg_content" class="form-control" placeholder="Type a Message..."></textarea>
						<a type="button" class="text-secondary saveFollowups" ><i class="la la-send"></i></a>
                    </div>
                </div>
			
			</div>
		</div>
    </div>
</div>
<input type="hidden" id="next_page" value="0" />
<a href="#" class="next_page" type="button" data-next_page="0" ></a>
<?php $this->load->view('includes/footer'); ?>
<script src="<?=base_url()?>assets/plugins/isotop/isotope.pkgd.min.js"></script>
<script>;
$(document).ready(function(){
	setTimeout(function(){ $(".stageFilter.active").trigger("click"); }, 50);	
	
	initSelect2();setPlaceHolder();
	var rec_per_page = "<?=$rec_per_page?>";
	$("#msg_content").keypress(function (e) {
		if(e.which === 13 && !e.shiftKey) {
			e.preventDefault();
			$(".saveFollowups").trigger("click");
		}
	});

    $(document).on('change','#lead_source',function(){ $(".stageFilter.active").trigger("click"); });

    $(document).on('click','.stageFilter',function(){
        var lead_source = $("#lead_source").val() || "";
        var postdata = $(this).data('postdata') || {};
		var np = parseFloat($('#next_page').val()) || 0;
		postdata.start = 0;
		postdata.length = parseFloat(rec_per_page);
		postdata.page = 0;
		postdata.lead_source = lead_source;
		
		loadHtmlData({'fnget':'getLeadData','rescls':'leadData','postdata':postdata});
	});
	
	$('.quicksearch').keyup(delay(function (e) {
		//if(e.which === 13 && !e.shiftKey) {
			e.preventDefault();
			var lead_source = $("#lead_source").val() || "";
			$('#next_page').val('0');
			var postdata = $('.stageFilter.active').data('postdata') || {};
			delete postdata.page;delete postdata.start;delete postdata.length;
			postdata.limit = parseFloat(rec_per_page);
		    postdata.lead_source = lead_source;
			postdata.skey = $(this).val();
			loadHtmlData({'fnget':'getLeadData','rescls':'leadData','postdata':postdata});
		//}
	}));
	
	const scrollEle = $('#leadBoard .simplebar-content-wrapper');
	var loading = true;
	$(scrollEle).scroll(function() {
		if($(this).scrollTop() + $(this).innerHeight() >= ($(this)[0].scrollHeight - 10)) {
			loading = true;
    		var lead_source = $("#lead_source").val() || "";
			var postdata = $('.stageFilter.active').data('postdata') || {};
			var np = parseFloat($('#next_page').val()) || 0;
			postdata.start = np * parseFloat(rec_per_page);
			postdata.length = rec_per_page;
			postdata.page = np;
    		postdata.lead_source = lead_source;
			loadHtmlData({'fnget':'getLeadData','rescls':'leadData','postdata':postdata,'scroll_type':1});
			loading = false;
		}
	});
	
    $(document).on('click','.partyData',function(){
        var party_id = $(this).data('party_id');
        var lead_id = $(this).data('lead_id');
        $("#party_id").val(party_id);
        $("#lead_id").val(lead_id);

        $.ajax({
            url: base_url + controller + '/getLeadDetails', // 28-03-2024
            data: { party_id:party_id, lead_id:lead_id },
            global:false,
            type: "POST",
            dataType:"json",
        }).done(function(data){
            $(".partyName").html(data.partyData.party_name);
            $(".salesLog").html(data.salesLog);
            if(data.partyData.executive_id == 0){
                $(".salesOption").hide();
            }else{
                $(".salesOption").show();
            }
            $(".cd-features").removeClass("visually-hidden");
            $(".cd-footer").removeClass("visually-hidden");
			scrollBottom();
        });
		
		
	});

	$(document).on('click','.saveFollowups',function(){
        var party_id = $("#party_id").val();
        var lead_id = $("#lead_id").val();
        var notes = $("#msg_content").val();

		if(notes != ''){
			$.ajax({
				url: base_url + controller + '/saveSalesLog',
				data: {party_id:party_id, lead_id:lead_id, notes:notes,log_type:2,id:''},
				type: "POST",
				global:false,
				dataType:"json",
			}).done(function(response){
				if(response.status==1){$("#msg_content").val('');$(".salesLog").html(response.salesLog);}
				scrollBottom();
			});
		}
        
	});
	
    $(document).on('click','.saveReminder',function(){
        var formId = "reminderForm";
		var form = $('#'+formId)[0];
		var fd = new FormData(form);

		$.ajax({
			url: base_url + controller + '/saveSalesLog',
			data:fd,
			type: "POST",
			global:false,
			processData:false,
			contentType:false,
			dataType:"json",
		}).done(function(response){
			if(response.status==1)
			{
				$(".salesLog").html(response.salesLog);
				$("#reminderForm")[0].reset();
				$('#remind_dd').toggleClass('show');
				// getLeadData();
			}
			else{$(".error").html("");$.each( response.message, function( key, value ) {$("."+key).html(value);});}
			scrollBottom();
		});
	});

    $(document).on('click',".addCrmForm",function(){
        var functionName = $(this).data("function");
        var modalId = $(this).data('modal_id');
        var button = $(this).data('button');
		var title = $(this).data('form_title');
		var formId = $(this).data('form_id') || functionName.split('/')[0];
		var controllerName = $(this).data('controller') || controller;
		var party_id = $("#party_id").val() || {};
		var lead_id = $("#lead_id").val() || {};
		var fnsave = $(this).data("fnsave") || "save";
		var ref_id= $(this).data("ref_id") || "";
		var entry_type= $(this).data("entry_type") || "";
		var module_type= $(this).data("module_type") || "";
		var jsStoreFn = 'storeCrm';
		var fnJson = "{'formId':'"+formId+"','controller':'"+controllerName+"','fnsave':'"+fnsave+"'}";

        $.ajax({ 
            type: "post",   
            url: base_url + controllerName + '/' + functionName,   
            data: {party_id:party_id,lead_id:lead_id,ref_id:ref_id,entry_type:entry_type,module_type:module_type}
        }).done(function(response){
            $("#"+modalId).modal('show');
            $("#"+modalId).css({'z-index':9999,'overflow':'auto'});
			$("#"+modalId+'').addClass(formId+"Modal");
			$("#"+modalId+' .modal-title').html(title);
			$("#"+modalId+' .modal-body').html("");
            $("#"+modalId+' .modal-body').html(response);
            $("#"+modalId+" .modal-body form").attr('id',formId);
			// $("#"+modalId+" .modal-footer .btn-save").attr('onclick',"store("+fnJson+");");
		    $("#"+modalId+" .modal-footer .btn-save").attr('onclick',jsStoreFn+"("+fnJson+");");
			$("#"+modalId+" .modal-header .close").attr('data-modal_id',modalId);
			$("#"+modalId+" .modal-header .close").attr('data-modal_class',formId+"Modal");
			$("#"+modalId+" .modal-footer .btn-close").attr('data-modal_id',modalId);
			$("#"+modalId+" .modal-footer .btn-close").attr('data-modal_class',formId+"Modal");

            $("#"+modalId+" .modal-footer .btn-close").show();
            $("#"+modalId+" .modal-footer .btn-save").show();
			
			setTimeout(function(){ setPlaceHolder(); initSelect2(modalId); }, 5);
        });
    });	

});

function scrollBottom(){
	$("#cd_body .simplebar-content-wrapper").animate({ scrollTop: $('#cd_body .simplebar-content-wrapper').prop("scrollHeight")}, 500);
}

function saveLead(postData){
	setPlaceHolder();
	$(".btn-save").attr("disabled", true);	
	var formId = postData.formId;
	var fnsave = postData.fnsave || "save";
	var controllerName = postData.controller || controller;

	var form = $('#'+formId)[0];
	var fd = new FormData(form);
	$.ajax({
		url: base_url + controllerName + '/' + fnsave,
		data:fd,
		type: "POST",
		processData:false,
		contentType:false,
		dataType:"json",
	}).done(function(data){
		$(".btn-save").removeAttr("disabled");
	    if(data.status==1){
			initTable(); $('#'+formId)[0].reset(); colseModal(formId);
			Swal.fire({ icon: 'success', title: data.message});
			// getLeadData();
		}else{
			if(typeof data.message === "object"){
				$(".error").html("");
				$.each( data.message, function( key, value ) {$("."+key).html(value);});
			}else{
				Swal.fire({ icon: 'error', title: data.message });
			}			
		}	
        
	});
}

function leadEdit(data){
	var button = data.button;if(button == "" || button == null){button="both";};
	var fnedit = data.fnedit;if(fnedit == "" || fnedit == null){fnedit="edit";}
	var fnsave = data.fnsave;if(fnsave == "" || fnsave == null){fnsave="save";}
	var form_id = data.form_id;if(form_id == "" || form_id == null){form_id="saveLead";}
	var title = data.title;if(title == "" || title == null){title="";}
	var is_confirm = data.confirm || '';
	if(is_confirm == 1){
		Swal.fire({
			title: 'Are you sure?',
			text: data.message,
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, Change it!',
		}).then(function(result) {
			if (result.isConfirmed)
			{
				$.ajax({
					url: base_url + controller + '/' + fnsave,
					data: data.postData,
					type: "POST",
					dataType:"json",
				}).done(function(response){
					if(response.status==1){
						Swal.fire({ icon: 'success', title: response.message});
						// getLeadData();
					}else{
						Swal.fire({ icon: 'error', title: response.message });			
					}	
				});
			}
		});
		
	}else{
		$.ajax({ 
			type: "POST",   
			url: base_url + controller + '/'+fnedit,   
			data: data.postData,
		}).done(function(response){
			$("#"+data.modal_id).css({'z-index':1059});
			$('#'+data.modal_id+' .modal-title').html(title);
			$('#'+data.modal_id+' .modal-body').html(response);
			$("#"+data.modal_id+" .modal-body form").attr('id',form_id);
			$("#"+data.modal_id).addClass(form_id+"Modal");
			$("#"+data.modal_id+" .modal-footer .btn-save").attr('onclick',"leadStore('"+form_id+"','"+fnsave+"');");
			$('#'+data.modal_id).modal('show');
			if(button == "close"){
				$("#"+data.modal_id+" .modal-footer .btn-close").show();
				$("#"+data.modal_id+" .modal-footer .btn-save").hide();
			}else if(button == "save"){
				$("#"+data.modal_id+" .modal-footer .btn-close").hide();
				$("#"+data.modal_id+" .modal-footer .btn-save").show();
			}else{
				$("#"+data.modal_id+" .modal-footer .btn-close").show();
				$("#"+data.modal_id+" .modal-footer .btn-save").show();
			}
			$(".select2").select2();
			setPlaceHolder();
		});
	}
	
}

function leadStore(formId,fnsave){
	$(".btn-save").attr("disabled", true);
	setPlaceHolder();
	var form = $('#'+formId)[0];
	var fd = new FormData(form);
	$.ajax({
		url: base_url + controller + '/' + fnsave,
		data:fd,
		type: "POST",
		processData:false,
		contentType:false,
		dataType:"json",
	}).done(function(response){
		$(".btn-save").removeAttr("disabled");
		if(response.status==1){
			colseModal(formId);
			Swal.fire({ icon: 'success', title: response.message});
			// getLeadData();
		}else{
			if(typeof response.message === "object"){
				$(".error").html("");
				$.each( response.message, function( key, value ) {$("."+key).html(value);});
			}else{
				Swal.fire({ icon: 'error', title: response.message });
			}			
		}				
	});
}

function storeCrm(postData){
	setPlaceHolder();
		
	var formId = postData.formId;
	var fnsave = postData.fnsave || "save";
	var controllerName = postData.controller || controller;

	var form = $('#'+formId)[0];
	var fd = new FormData(form);
	$(".btn-save").attr("disabled", true);	
	$.ajax({
		url: base_url + controllerName + '/' + fnsave,
		data:fd,
		type: "POST",
		processData:false,
		contentType:false,
		dataType:"json",
	}).done(function(data){
		$(".btn-save").removeAttr("disabled");
		if(data.status==1){
			colseModal(formId);
			$(".salesLog").html(data.salesLog);
			Swal.fire({ icon: 'success', title: data.message});
			// getLeadData();
		}else{
			if(typeof data.message === "object"){
				$(".error").html("");
				$.each( data.message, function( key, value ) {$("."+key).html(value);});
			}else{
				Swal.fire({ icon: 'error', title: data.message });
			}			
		}	
	});
}

function getLeadData(postData = []){
	$.ajax({
		url: base_url  + 'lead/getLeadData',
		data:{party_type : ''},
		type: "POST",
		dataType:"json",
	}).done(function(res){
		$('.grid').isotope('destroy');
	    $(".grid").html(res.leadDetail);	
		initISOTOP();
        //setTimeout(function(){ $(".buttonFilter.active").trigger("click"); }, 250);

	});
}

</script>