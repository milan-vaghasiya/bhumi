<?php //$this->load->view('hr/employee/change_password');?>
<script>
	var base_url = '<?=base_url();?>'; 
	var page_url ='<?=(isset($headData->pageUrl)) ? $headData->pageUrl : ''?>'; 
	var controller = '<?=(isset($headData->controller)) ? $headData->controller : ''?>'; 
	var popupTitle = '<?=POPUP_TITLE;?>';
	var theads = '<?=(isset($tableHeader)) ? $tableHeader[0] : ''?>';
	var textAlign = '<?=(isset($tableHeader[1])) ? $tableHeader[1] : ''?>';
	var srnoPosition = '<?=(isset($tableHeader[2])) ? $tableHeader[2] : 1?>';
	var tableHeaders = {'theads':theads,'textAlign':textAlign,'srnoPosition':srnoPosition};
	var menu_id = '<?=(isset($headData->menu_id)) ? $headData->menu_id : 0?>';
</script>
<div class="chat-windows"></div>
<!-- Permission Checking -->
<?php
	$script= "";
	if($permission = $this->session->userdata('emp_app_permission')):
		if(!empty($headData->pageUrl)):
    		$empPermission = $permission[$headData->pageUrl];
			
    		$script .= '
    			<script>
    				var permissionRead = "'.$empPermission['is_read'].'";
    				var permissionWrite = "'.$empPermission['is_write'].'";
    				var permissionModify = "'.$empPermission['is_modify'].'";
    				var permissionRemove = "'.$empPermission['is_remove'].'";
    				var permissionApprove = "'.$empPermission['is_approve'].'";
    			</script>
    		';
    		echo $script;
		else:
			$script .= '
			<script>
				var permissionRead = "1";
				var permissionWrite = "1";
				var permissionModify = "1";
				var permissionRemove = "1";
				var permissionApprove = "1";
			</script>
		';
		echo $script;
		endif;
	else:
		$script .= '
			<script>
				var permissionRead = "";
				var permissionWrite = "";
				var permissionModify = "";
				var permissionRemove = "";
				var permissionApprove = "";
			</script>
		';
		echo $script;
	endif;
?>
<!-- * DialogIconedDanger -->
<!-- * toast top auto close in 2 seconds -->
<!-- ============================================================== -->
<!--**********************************
    Scripts
***********************************-->
<script src="<?=base_url('assets/app/js/jquery.js')?>"></script>
<script src="<?=base_url('assets/app/vendor/bootstrap/js/bootstrap.bundle.min.js')?>"></script>
<script src="<?=base_url('assets/app/vendor/swiper/swiper-bundle.min.js')?>"></script><!-- Swiper -->
<script src="<?=base_url('assets/app/vendor/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js')?>"></script><!-- Swiper -->
<script src="<?=base_url('assets/app/js/dz.carousel.js')?>"></script><!-- Swiper -->
<script src="<?=base_url()?>assets/app/js/settings.js?v=<?=time()?>"></script>
<script src="<?=base_url('assets/app/js/custom.js')?>"></script>
<!-- Select2 js -->
<script src="<?=base_url()?>assets/plugins/select2/js/select2.full.min.js"></script>
<script src="<?=base_url()?>assets/js/pages/multiselect/js/bootstrap-multiselect.js"></script>
<script src="<?=base_url()?>assets/plugins/sweet-alert2/sweetalert2.min.js"></script><!-- Select2 js -->
<script src="<?=base_url()?>assets/js/custom/typehead.js?v=<?=time()?>"></script>
<div class="ajaxModal"></div>
<div class="centerImg">
	<img src="<?=base_url()?>assets/images/logo.png" style="width:50%;height:auto;"><br>
	<img src="<?=base_url()?>assets/images/ajaxLoading.gif" style="margin-top:-25px;">
</div>
<script>
	$(document).ready(function(){
		$(document).ajaxStart(function(){
			$('.ajaxModal').show();$('.centerImg').show();$(".error").html("");
			$('.save-form').attr('disabled','disabled');
			$('.btn-save').attr('disabled','disabled');
		});
	
		$(document).ajaxComplete(function(){
			$('.ajaxModal').hide();$('.centerImg').hide();
			$('.save-form').removeAttr('disabled');
			$('.btn-save').removeAttr('disabled');
			checkPermission();
		});
	});
function setPlaceHolder(){
	var label="";
	$('input').each(function () {
		if(!$(this).hasClass('combo-input') && $(this).attr("type")!="hidden" )
		{
			label="";
			inputElement = $(this).parent();
			if($(this).parent().hasClass('input-group')){inputElement = $(this).parent().parent();}else{inputElement = $(this).parent();}
			label = inputElement.children("label").text();
			label = label.replace('*','');
			label = $.trim(label);
			if($(this).hasClass('req')){inputElement.children("label").html(label + ' <strong class="text-danger">*</strong>');}
			if(!$(this).attr("placeholder")){if(label){$(this).attr("placeholder", label);}}
			$(this).attr("autocomplete", 'off');
			var errorClass="";
			var nm = $(this).attr('name');
			if($(this).attr('id')){errorClass=$(this).attr('id');}else{errorClass=$(this).attr('name');if(errorClass){errorClass = errorClass.replace("[]", "");}}
			if(inputElement.find('.'+errorClass).length <= 0){inputElement.append('<div class="error '+ errorClass +'"></div>');}
		}
		else{$(this).attr("autocomplete", 'off');}
	});
	$('textarea').each(function () {
		label="";
		label = $(this).parent().children("label").text();
		label = label.replace('*','');
		label = $.trim(label);
		if($(this).hasClass('req')){$(this).parent().children("label").html(label + ' <strong class="text-danger">*</strong>');}
		if(label){$(this).attr("placeholder", label);}
		$(this).attr("autocomplete", 'off');
		var errorClass="";
		var nm = $(this).attr('name');
		if($(this).attr('name')){errorClass=$(this).attr('name');}else{errorClass=$(this).attr('id');}
		if($(this).parent().find('.'+errorClass).length <= 0){$(this).parent().append('<div class="error '+ errorClass +'"></div>');}
	});
	$('select').each(function () {
		let string =String($(this).attr('name'));
		if(string.indexOf('[]') === -1)
		{
			label="";
			var selectElement = $(this).parent();
			if($(this).hasClass('single-select')){selectElement = $(this).parent().parent();}
			label = selectElement.children("label").text();
			label = label.replace('*','');
			label = $.trim(label);
			if($(this).hasClass('req')){selectElement.children("label").html(label + ' <strong class="text-danger">*</strong>');}
			var errorClass="";
			var nm = $(this).attr('name');
			
			if($(this).attr('name')){errorClass=$(this).attr('name');}else{errorClass=$(this).attr('id');}
			if(selectElement.find('.'+errorClass).length <= 0){selectElement.append('<div class="error '+ errorClass +'"></div>');}
		}
	});
}
$(window).on('pageshow', function() {
	$('form').off();
	checkPermission();
	$(".menubar-nav .nav-link").removeClass("active");
	$("[data-page_url ='"+page_url+"']").addClass("active");
});

function checkPermission(){
	$('.permission-read').show();
	$('.permission-write').show();
	$('.permission-modify').show();
	$('.permission-remove').show();
	$('.permission-approve').show();

	//view permission
	if(permissionRead == "1"){ 
		$('.permission-read').prop('disabled', false);
		$('.permission-read').show(); 
	}else{ 
		$('.permission-read').prop('disabled', true);
		$('.permission-read').hide(); 
		//window.location.href = base_url + 'error_403';
	}

	//write permission
	if(permissionWrite == "1"){ 
		$('.permission-write').prop('disabled', false);
		$('.permission-write').show(); 
	}else{ 
		$('.permission-write').prop('disabled', true);
		$('.permission-write').hide(); 
	}

	//update permission
	if(permissionModify == "1"){ 
		$('.permission-modify').prop('disabled', false);
		$('.permission-modify').show(); 
	}else{ 
		$('.permission-modify').prop('disabled', true);
		$('.permission-modify').hide(); 
	}

	//delete permission
	if(permissionRemove == "1"){ 
		$('.permission-remove').prop('disabled', false);
		$('.permission-remove').show(); 
	}else{ 
		$('.permission-remove').prop('disabled', true);
		$('.permission-remove').hide(); 
	}

	//Approve permission
	if(permissionApprove == "1"){ 
		$('.permission-approve').prop('disabled', false);
		$('.permission-approve').show(); 
	}else{ 
		$('.permission-approve').prop('disabled', true);
		$('.permission-approve').hide(); 
	}
}
</script>