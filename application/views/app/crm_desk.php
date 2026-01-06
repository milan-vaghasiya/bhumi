<?php $this->load->view('app/includes/header'); ?>
<style>
.dropdown-toggle::after{display:none;}
</style>
    <!-- Header -->
	<header class="header">
		<div class="main-bar bg-primary-2">
			<div class="container">
				<div class="header-content">
					<div class="left-content">
						<a href="javascript:void(0);" class="menu-toggler me-2">
    						<svg class="text-dark" xmlns="http://www.w3.org/2000/svg" height="30px" viewBox="0 0 24 24" width="30px" fill="#000000"><path d="M13 14v6c0 .55.45 1 1 1h6c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1h-6c-.55 0-1 .45-1 1zm-9 7h6c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1zM3 4v6c0 .55.45 1 1 1h6c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1zm12.95-1.6L11.7 6.64c-.39.39-.39 1.02 0 1.41l4.25 4.25c.39.39 1.02.39 1.41 0l4.25-4.25c.39-.39.39-1.02 0-1.41L17.37 2.4c-.39-.39-1.03-.39-1.42 0z"></path></svg>
    					</a>
						<h5 class="title mb-0 text-nowrap">CRM</h5>
					</div>
					<div class="mid-content" id="desk_title">
						
						New
						
					</div>
					<div class="right-content ">
						<!-- <div class="headerSearch" style="margin-right:50px">
							<div class="jpsearch" id="qs1">
								
								<button class="search-btn"><i class="fas fa-search"></i></button>
							</div>
						</div> -->
						<div class="basic-dropdown">
							<div class="dropdown">
								<a type="button" class=" dropdown-toggle show font-20" data-bs-toggle="dropdown" aria-expanded="true">
									<i class="fas fa-ellipsis-v"></i>
								</a>
								<div class="dropdown-menu" data-popper-placement="bottom-start" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(0px, 48px);">
									<?php
									if(!empty($stageList)){
										foreach($stageList as $row) {
											if($row->sequence != 1){
												?>
												<a type="button" class="text-danger stageFilter  dropdown-item <?=($row->stage_type == 'New'?'active':'')?>"  data-party_type="<?=$row->id?>"  data-postdata='{"party_type":"<?=$row->id?>","stage":"<?=$row->stage_type?>"}'><?=$row->stage_type?></a>
												<?php
											}
											
										}
									}
									?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</header>
	<!-- Header -->
    
    <!-- Page Content -->
    <div class="page-content"  id="leadBoard" style="overflow:scroll !important;height:80vh;">
	
        <div class="content-inner pt-0" >
			<div class="container">
				<input type="text" class="form-control quicksearch qs1" placeholder="Search Here ..." />
                <div class="dz-tab style-4">
					<div class="list-grid">
						<ul id="leadContainer" class="dz-list message-list leadData accordion style-2"></ul>
						
						<div class="review-box" >
							<a href="<?=base_url("app/lead/addParty")?>" class="add-btn  permission-write" style="margin-bottom:80px">
								<i class="fa-solid fa-plus"></i>
							</a>
						</div>
					</div>
				</div> 
			</div>    
		</div>
    </div>    
    <!-- Page Content End-->
</div> 
<input type="hidden" id="next_page" value="0" />
<a href="#" class="next_page" type="button" data-next_page="0" ></a> 
<input type="hidden" id="party_type" value="2">
<?php $this->load->view('app/includes/bottom_menu'); ?>
<?php $this->load->view('app/includes/footer'); ?>
<?php $this->load->view('app/includes/sidebar'); ?>

<script>
$(document).ready(function(){
	
	var rec_per_page = "<?=$rec_per_page?>";
	setTimeout(function(){ $(".quicksearch").trigger("keyup"); }, 50);	
	$(document).on('click','.stageFilter',function(){
        var postdata = $(this).data('postdata') || {party_type:2,stage:'New'};
		$("#desk_title").html(postdata.stage);
		$("#party_type").val(postdata.party_type);
		var np = parseFloat($('#next_page').val()) || 0;
		postdata.start = 0;
		postdata.length = parseFloat(rec_per_page);
		postdata.page = 0;
		// alert(postdata.length+'#'+postdata.party_type);
		loadHtmlData({'fnget':'getLeadData','rescls':'leadData','postdata':postdata});
	});

	$('.quicksearch').keyup(delay(function (e) {
			e.preventDefault();
			$('#next_page').val('0');
			var postdata = {};
			postdata.party_type = $("#party_type").val();
			// alert(postdata.length+'#'+postdata.party_type);

			delete postdata.page;delete postdata.start;delete postdata.length;
			postdata.limit = parseFloat(rec_per_page);
			postdata.skey = $(this).val();
			loadHtmlData({'fnget':'getLeadData','rescls':'leadData','postdata':postdata});
	}));

	
	const scrollEle = $('#leadBoard');
	var ScrollDebounce = true;
	$(scrollEle).scroll(function() {
		
		if($(this).scrollTop() + $(this).innerHeight() >= ($(this)[0].scrollHeight - 10)) {
			if(ScrollDebounce){
				ScrollDebounce = false;
				var party_type = $("#party_type").val();
				var postdata = {party_type:party_type} || {};
				var np = parseFloat($('#next_page').val()) || 0;
				postdata.start = np * parseFloat(rec_per_page);
				postdata.length = rec_per_page;
				postdata.page = np;
				console.log(postdata);
				loadHtmlData({'fnget':'getLeadData','rescls':'leadData','postdata':postdata,'scroll_type':1});
				setTimeout(function () { ScrollDebounce = true; }, 500);		
			}
		}
	});
	
	$(document).on("click", "ul.leadData li span.delete", function () {alert("delete");});
	$(document).on("click", "ul.leadData li span.flag", function () {alert("flag");});
	$(document).on("click", "ul.leadData li span.more", function () {alert("nothing");});

	/*$(document).on("swipeleft", "ul.leadData li a.swipe-container", function (e) {
		$(this).prevAll("span").addClass("show");
		$(this).off("click").blur();
		$(this).css({
			transform: "translateX(-210px)"
		}).one("transitionend webkitTransitionEnd oTransitionEnd otransitionend MSTransitionEnd", function () {
			$(this).one("swiperight", function () {
				$(this).prevAll("span").removeClass("show");
				$(this).css({transform: "translateX(0)" }).blur();
			});
		});
	});*/
	
});

function changeLeadStatus(data){
	var button = data.button;if(button == "" || button == null){button="both";};
	var fnedit = data.fnedit;if(fnedit == "" || fnedit == null){fnedit="edit";}
	var fnsave = data.fnsave;if(fnsave == "" || fnsave == null){fnsave="save";}
	var formId = data.formId;if(formId == "" || formId == null){formId="changeStatus";}
	var title = data.title;if(title == "" || title == null){title="Change Status";}
	var is_confirm = data.confirm || '';
	if(is_confirm == 1){
		Swal.fire({
			title: 'Are you sure?',
			text: data.message,
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes',
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
						$('.list-grid').isotope('destroy');
						$(".message-list").html(response.leadList.allLead);	
						initISOTOP();
					}else{
						Swal.fire({ icon: 'error', title: response.message });			
					}	
				});
			}
		});
		
	}else{
		$.ajax({ 
			type: "POST",   
			url: base_url + controller + '/changeLeadStatus',   
			data: data.postData,
		}).done(function(response){
			$("#"+data.modal_id).css({'z-index':1051});
			$('#'+data.modal_id+' .modal-title').html(title);
			$('#'+data.modal_id+' .modal-body').html(response);
			$("#"+data.modal_id+" .modal-body form").attr('id',formId);
			$("#"+data.modal_id+" .modal-footer .btn-save").attr('onclick',"saveLeadStatus('"+formId+"','"+fnsave+"','"+data.modal_id+"');");
			$('#'+data.modal_id).modal('show');
			$(".select2").select2();
			setPlaceHolder();
		});
	}
}
function saveLeadStatus(formId,fnsave,modal_id){
	setPlaceHolder
	
	var form = $('#'+formId)[0];
	var fd = new FormData(form);
	$(".btn-save").attr("disabled", true);
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
			$('#'+formId)[0].reset(); 
			$("#"+modal_id+' .modal-body').html("");
			$("#"+modal_id).modal('hide');	
			$(".modal").css({'overflow':'auto'});
			Swal.fire({ icon: 'success', title: response.message});
			$('.list-grid').isotope('destroy');

			$(".message-list").html(response.leadList.allLead);	
			//$("#won_lead .won").html(response.leadList.wonLead);	
			//$("#lost_lead .lost").html(response.leadList.lostLead);
			
			initISOTOP();
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
function trash(data){
	var controllerName = data.controller || controller;
	var fnName = data.fndelete || "delete";
	var msg = data.message || "Record";
	var send_data = data.postData;
	var resFunctionName = data.res_function || "";
	
	Swal.fire({
		title: 'Are you sure?',
		text: "You won't be able to revert this!",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Yes, delete it!',
	}).then(function(result) {
		if (result.isConfirmed)
		{
			$.ajax({
				url: base_url + controllerName + '/' + fnName,
				data: send_data,
				type: "POST",
				dataType:"json",
			}).done(function(response){
				if(resFunctionName != ""){
					window[resFunctionName](response);
				}else{
					if(response.status==0){
						Swal.fire( 'Sorry...!', response.message, 'error' );
					}else{
						initTable();
						Swal.fire( 'Deleted!', response.message, 'success' );
					}	
				}
			});
			Swal.fire( 'Deleted!', 'Your file has been deleted.', 'success' );
		}
	});
	
}

function leadAction(data){
	var button = data.button;if(button == "" || button == null){button="both";};
	var fnedit = data.fnedit;if(fnedit == "" || fnedit == null){fnedit="edit";}
	var fnsave = data.fnsave;if(fnsave == "" || fnsave == null){fnsave="save";}
	var formId = data.formId;if(formId == "" || formId == null){formId="changeStatus";}
	var title = data.title;if(title == "" || title == null){title="Change Status";}
	var is_confirm = data.confirm || '';
	$.ajax({ 
		type: "POST",   
		url: base_url + controller + '/'+fnedit,   
		data: data.postData,
	}).done(function(response){
		$("#"+data.modal_id).css({'z-index':1051});
		$('#'+data.modal_id+' .modal-title').html(title);
		$('#'+data.modal_id+' .modal-body').html(response);
		$("#"+data.modal_id+" .modal-body form").attr('id',formId);
		if(button == 'no'){
			$("#"+data.modal_id+" .modal-footer").hide();
		}
		
		$('#'+data.modal_id).modal('show');
		$(".select2").select2();
		setPlaceHolder();
	});
}
</script>