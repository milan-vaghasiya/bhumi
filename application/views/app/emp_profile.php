<?php $this->load->view('app/includes/header'); ?>
	
	<!-- Header -->
	<header class="header">
		<div class="main-bar">
			<div class="container">
				<div class="header-content">
					<div class="left-content">
						<a href="javascript:void(0);" class="menu-toggler me-2">
							<svg class="text-dark" xmlns="http://www.w3.org/2000/svg" height="30px" viewBox="0 0 24 24" width="30px" fill="#000000"><path d="M13 14v6c0 .55.45 1 1 1h6c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1h-6c-.55 0-1 .45-1 1zm-9 7h6c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1zM3 4v6c0 .55.45 1 1 1h6c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1zm12.95-1.6L11.7 6.64c-.39.39-.39 1.02 0 1.41l4.25 4.25c.39.39 1.02.39 1.41 0l4.25-4.25c.39-.39.39-1.02 0-1.41L17.37 2.4c-.39-.39-1.03-.39-1.42 0z"></path></svg>
						</a>
						<h5 class="title mb-0 text-nowrap">Profile</h5>
					</div>
					<div class="mid-content">
					</div>
					<div class="right-content">
						<a type="button" class="text-danger" data-form_title="Edit" datatip="Edit Profile" data-bs-toggle="offcanvas" data-bs-target="#profileModel" data-id = <?= $empData->id ?> aria-controls="offcanvasBottom" style="width:20px; padding-right:15px;"><i class="fa-solid fa-edit"></i></a>
						<?php if(empty($empData->quarter_id)) {?> 
							<a href="<?=base_url("app/hqChangeRequest/addHeadQuarter/".$empData->id)?>" class="add-btn text-danger permission-write" datatip="New Head Quarter" style="margin-bottom:80px"><i class="fa-solid fa-plus"></i></a>
						<?php }else{ ?>
							<a type="button" class="text-danger" data-form_title="Change Head Quarter" datatip="New Head Quarter" data-bs-toggle="offcanvas" data-bs-target="#headModel" aria-controls="offcanvasBottom" style="width:20px; padding-right:15px;"><i class="fas fa-exchange-alt fs-22"></i></a>
						<?php } ?>
					    <a type="button" class="text-danger" data-form_title="Change Password" datatip="Add change Password" data-bs-toggle="offcanvas" data-bs-target="#change-psw" aria-controls="offcanvasBottom"><i class="fa fa-key fs-22"></i></a>
					</div>
				</div>
			</div>
		</div>
	</header>
	<!-- Header -->
    <?php
    $profile_pic = 'male_user.png';
    if(!empty($empData->emp_profile)):
        $profile_pic = $empData->emp_profile;
    else:
        if(!empty($empData->emp_gender) and $empData->emp_gender=="Female"):
            $profile_pic = 'female_user.png';
        endif;
    endif;
    ?>
    <!-- Page Content -->
    <div class="page-content bottom-content">
        <div class="container">
			<div class="driver-profile">
				<div class="media media-100 mb-2">
					<img class="rounded-circle" src="<?= base_url('assets/uploads/emp_profile/'.$profile_pic) ?>" alt="driver-image">
				</div>
				<div class="profile-detail">
					<h6 class="name mb-0 font-18"><?=$empData->emp_code?></h6>
				</div>
			</div>
			
			<div class="dz-list">
				<ul>
					
					<li>
						<a href="javascript:void(0);" class="item-content">
							<div class="dz-icon">
								<i class="fa fa-user"></i>
							</div>
							<div class="dz-inner">
								<span class="title"><?=$empData->emp_name?> <br><small>Employee Name</small></span>
								
							</div>
						</a>
					</li>
					<li>
						<a href="javascript:void(0);" class="item-content">
							<div class="dz-icon">
								<i class="fa fa-at"></i>
							</div>
							<div class="dz-inner">
								<span class="title"><?=$empData->emp_email?> <br><small>E-mail</small></span>
							</div>
						</a>
					</li>
					<li>
						<a href="javascript:void(0);" class="item-content">
							<div class="dz-icon">
								<i class="fa-solid fa-phone"></i>
							</div>
							<div class="dz-inner">
								<span class="title"><?=$empData->emp_contact?> <br><small>Mobile No.</small></span>
							</div>
						</a>
					</li>
				
					<li>
						<a href="javascript:void(0);" class="item-content">
							<div class="dz-icon">
								<i class="fa fa-users"></i>
							</div>
							<div class="dz-inner">
								<span class="title"><?=$empData->department_name .' - '.$empData->designation_name?> <br><small>Department - Designation</small></span>
							</div>
						</a>
					</li>
					<li>
						<a href="javascript:void(0);" class="item-content">
							<div class="dz-icon">
								<i class="fa fa-id-card"></i>
							</div>
							<div class="dz-inner">
								<span class="title"><?=$empData->aadhar_no?> <br><small>Aadhar No</small></span>
							</div>
						</a>
					</li>
					<li>
						<a href="javascript:void(0);" class="item-content">
							<div class="dz-icon">
								<i class="fa fa-address-card"></i>
							</div>
							<div class="dz-inner">
								<span class="title"><?=$empData->pan_no?> <br><small>Pan No</small></span>
							</div>
						</a>
					</li>
					<li>
						<a href="javascript:void(0);" class="item-content">
							<div class="dz-icon">
								<i class="fa fa-address-card"></i>
							</div>
							<div class="dz-inner">
								<span class="title"><?=formatDate($empData->joining_date)?> <br><small>Joining Date</small></span>
							</div>
						</a>
					</li>
					<li>
						<a href="javascript:void(0);" class="item-content">
							<div class="dz-icon">
								<i class="fa fa-address-card"></i>
							</div>
							<div class="dz-inner">
								<span class="title"><?=$empData->hq_name?> <br><small>Head Quarter</small></span>
							</div>
						</a>
						<div class="text-right">
							
						</div>
					</li>
				</ul>
			</div>
		</div>
    </div>  
<!-- 31-07-2024-->	
<div class="offcanvas offcanvas-bottom m-3 rounded" tabindex="-1" id="profileModel" aria-modal="true" role="dialog">
    <div class="offcanvas-body small">
        <form id="empProfile">
            <div class="card">
                <div class="card-body">
                    <input type="hidden" name="id" id="id" value="<?=(!empty($empData->id) ? $empData->id : "")?>" />
					<div class="mb-3">
                        <label for="emp_email" class="form-label">Employee Email</label>
                        <input type="text" name="emp_email" id="emp_email" class="form-control req" value="<?=(!empty($empData->emp_email) ? $empData->emp_email : "")?>" />
                    </div>
                    <div class="mb-3">
                        <label for="emp_contact" class="form-label">Contact Person</label>
                        <input type="text" name="emp_contact" id="emp_contact" class="form-control req" value="<?=(!empty($empData->emp_contact) ? $empData->emp_contact : "")?>" />
                    </div>
					<div class="mb-3">
                        <label for="aadhar_no" class="form-label">Aadhar No.</label>
                        <input type="text" name="aadhar_no" id="aadhar_no" class="form-control req" value="<?=(!empty($empData->aadhar_no) ? $empData->aadhar_no : "")?>" />
							</div>
					<div class="mb-3">
                        <label for="pan_no" class="form-label">Pan No.</label>
                        <input type="text" name="pan_no" id="pan_no" class="form-control req" value="<?=(!empty($empData->pan_no) ? $empData->pan_no : "")?>" />
					</div>
					<div class="">
						<?php
							$param = "{'formId':'empProfile','fnsave':'save','controller':'app/employee/'}";
						?>
						<a href="javascript:void(0)" class="btn btn-success btn-block btn-round btn-outline-dashed btn-save" onclick="store(<?=$param?>)">Save</a>
					
					</div>
                </div>        
            </div>
        </form>
    </div>
</div>  

<!-- 31-07-2024-->	
<div class="offcanvas offcanvas-bottom m-3 rounded" tabindex="-1" id="headModel" aria-modal="true" role="dialog">
    <div class="offcanvas-body small">
        <form id="headForm">
            <div class="card">
                <div class="card-body">
                    <input type="hidden" name="id" id="id" value="" />
                    <input type="hidden" name="emp_id" id="emp_id" value="<?=(!empty($empData->id) ? $empData->id : "")?>" />
                    <input type="hidden" name="hq_id" id="hq_id" value="<?=(!empty($empData->quarter_id) ? $empData->quarter_id : "")?>" />

					<div class="mb-3">
						<label for="new_hq_id">Change Head Quarter</label>
						<select name="new_hq_id" id="new_hq_id" class="form-control modal-select2 req">
							<option value="">Select Head Quarter</option>
							<?php
								foreach($quarterList as $row):
									if($empData->quarter_id != $row->id){
										echo '<option value="'.$row->id.'">'.$row->name.'</option>';
									}
								endforeach;
							?>
						</select>
					</div>
                   
					<div class="">
						<?php
							$param = "{'formId':'headForm','fnsave':'saveNewHeadQuarter','controller':'app/hqChangeRequest/'}";
						?>
						<a href="javascript:void(0)" class="btn btn-success btn-block btn-round btn-outline-dashed btn-save" onclick="store(<?=$param?>)">Save</a>
					</div>
                </div>        
            </div>
        </form>
    </div>
</div> 

<!-- Page Content End-->
<?php $this->load->view('app/includes/bottom_menu'); ?>
<?php $this->load->view('app/includes/footer'); ?>
<?php $this->load->view('app/includes/sidebar'); ?>
<?php $this->load->view('app/change_password'); ?>
<script>
function store(postData){
	setPlaceHolder();
	
	var formId = postData.formId;
	var fnsave = postData.fnsave || "save";
	var tableId = postData.table_id || "";
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
		if(data.status==1){
			$('#'+formId)[0].reset(); 
			Swal.fire({
				title: "Success",
				text: data.message,
				icon: "success",
				showCancelButton: false,
				confirmButtonColor: "#3085d6",
				cancelButtonColor: "#d33",
				confirmButtonText: "Ok!"
				}).then((result) => {
					window.location.reload();
				});
		}else{
			if(typeof data.message === "object"){
				$(".error").html("");
				$.each( data.message, function( key, value ) {$("."+key).html(value);});
			}else{
				Swal.fire( 'Sorry...!', data.message, 'error' );

			}			
		}				
	});
}
    
$(document).ready(function(){
	setPlaceHolder();
	$(document).on('click','.changePsw',function(){ 
		var formId = "changePSW";
		var form = $('#'+formId)[0];
		var fd = new FormData(form);

		$.ajax({
			url: base_url + 'hr/employees/changePassword',
			data:fd,
			type: "POST",
			global:false,
			processData:false,
			contentType:false,
			dataType:"json",
		}).done(function(response){
			if(response.status==1)
			{
				$("#changePSW")[0].reset();
				$('#change-psw').offcanvas('hide');
				Swal.fire({ icon: 'success', title: response.message});
			}
			else{$(".error").html("");$.each( response.message, function( key, value ) {$("."+key).html(value);});}
			window.scrollTo(0, document.body.scrollHeight);
		});
	});
	
	$(document).on('click','.pswHideShow',function(){
		var type = $('.pswType').attr('type');
		if(type == "password"){
			$(".pswType").attr('type','text');
			$(this).html('<i class="fa fa-eye-slash"></i>');
		}else{
			$(".pswType").attr('type','password');
			$(this).html('<i class="fa fa-eye"></i>');
		}
	});
});
</script>