<?php $this->load->view('app/includes/header'); ?>
<!-- Header -->
<header class="header">
	<div class="main-bar bg-primary-2">
		<div class="container">
			<div class="header-content">
				<div class="left-content">
					<a href="javascript:void(0);" class="menu-toggler me-2">
                        <svg class="text-dark" xmlns="http://www.w3.org/2000/svg" height="30px" viewBox="0 0 24 24" width="30px" fill="#000000"><path d="M13 14v6c0 .55.45 1 1 1h6c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1h-6c-.55 0-1 .45-1 1zm-9 7h6c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1zM3 4v6c0 .55.45 1 1 1h6c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1zm12.95-1.6L11.7 6.64c-.39.39-.39 1.02 0 1.41l4.25 4.25c.39.39 1.02.39 1.41 0l4.25-4.25c.39-.39.39-1.02 0-1.41L17.37 2.4c-.39-.39-1.03-.39-1.42 0z"></path></svg>
    				</a>
					<h5 class="title mb-0 text-nowrap">Leave</h5>
				</div>
				<div class="mid-content"> </div>
				<div class="right-content headerSearch">
					<div class="jpsearch" id="qs1">
						<input type="text" class="input quicksearch qs1" placeholder="Search Here ..." />
						<button class="search-btn"><i class="fas fa-search"></i></button>
					</div>
				</div>
			</div>
		</div>
	</div>
</header>
<!-- Header -->
<!-- Page Content -->
<div class="page-content">
    <div class="content-inner pt-0">
        <div class="container bottom-content">
            <div class="tab-pane fade active show list-grid" role="tabpanel" aria-labelledby="home-tab" tabindex="0" >
                <ul class="dz-list message-list" data-isotope='{ "itemSelector": ".listItem" }'>
                    <?php
                     echo  $leaveHtml;
                    ?>
                </ul>
            </div>
           
            <div class="review-box" >
            <?php
                $addParam = "{'modal_id' : 'modalCenter', 'form_id' : 'addLeave', 'title' : 'Add Leave','fnedit': 'addLeave','fnsave':'save'}";

            ?>
            <a type="button" class="add-btn permission-write btn-remind dropdown-item" style="margin-bottom:80px" onclick="edit(<?=$addParam?>);"><i class="fa-solid fa-plus"></i></a>
            </div> 
        </div>    
    </div>
</div> 



<!-- Page Content End-->
<?php $this->load->view('app/includes/bottom_menu'); ?>
<?php $this->load->view('app/includes/footer'); ?>
<?php $this->load->view('app/includes/sidebar'); ?>

<script src="<?=base_url()?>assets/plugins/isotop/isotope.pkgd.min.js"></script>
<!-- <script src="https://maps.google.com/maps/api/js?key=AIzaSyAAzYbgqM1TKIa7psryIXXP07g6FTk_inY"></script> -->
<script>
var qsRegex;
var isoOptions ={};
var $grid = '';
$(document).ready(function(){
    $('.select2').each(function() { 
        $(this).select2({ dropdownParent: $(this).parent()});
    })
    initISOTOP();
	var $qs = $('.quicksearch').keyup( debounce( function() {qsRegex = new RegExp( $qs.val(), 'gi' );$grid.isotope();}, 200 ) );
    $(document).on( 'click', '.buttonFilter', function() {
		var status = $(this).data('status');
        $(".message-list").html("");	
        $.ajax({
            url: base_url  + 'app/leave/getLeaveData',
            data:{'visit_status':status},
            type: "POST",
            dataType:"json",
        }).done(function(response){
                $('.list-grid').isotope('destroy');
                $(".message-list").html(response.html);	
                initISOTOP();
                                
            });
    });
    setPlaceHolder();    

});


function searchItems(ele){
	console.log($(ele).val());
}

function debounce( fn, threshold ) {
  var timeout;
  threshold = threshold || 100;
  return function debounced() {
	clearTimeout( timeout );
	var args = arguments;
	var _this = this;
	
	function delayed() {fn.apply( _this, args );}
	timeout = setTimeout( delayed, threshold );
  };
}


function initISOTOP(){
    var isoOptions = {
		itemSelector: '.listItem',
		layoutMode: 'fitRows',
		filter: function() {return qsRegex ? $(this).text().match( qsRegex ) : true;}
	};
    $('.listItem').css('position', 'static');
	// init isotope
	$grid = $('.list-grid').isotope( isoOptions );
}

function confirmStore(data){
	setPlaceHolder();

	var formId = data.formId || "";
	var fnsave = data.fnsave || "save";
	var controllerName = data.controller || controller;

	if(formId != ""){
		var form = $('#'+formId)[0];
		var fd = new FormData(form);
		var resFunctionName = $("#"+formId).data('res_function') || "";
		var msg = "Are you sure want to save this record ?";
		var ajaxParam = {
			url: base_url + 'hr/leave/'  + fnsave,
			data:fd,
			type: "POST",
			processData:false,
			contentType:false,
			dataType:"json"
		};
	}else{
	
        var fd = data.postData;
        var msg = data.message || "Are you sure want to save this change ?";
        var ajaxParam = {
            url: base_url  + 'hr/leave/' + fnsave,
            data:fd,
            type: "POST",
            dataType:"json"
        };
    }
	Swal.fire({
		title: 'Are you sure?',
		text: msg,
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Yes, Do it!',
	}).then(function(result) {
		if (result.isConfirmed)
		{
			$.ajax(ajaxParam).done(function(response){
				if(response.status==1){										
					Swal.fire( 'Success', response.message, 'success' ).then((result) => { window.location = base_url + 'app/leave'; });
				}else{
					if(typeof response.message === "object"){
						$(".error").html("");
						$.each( response.message, function( key, value ) {$("."+key).html(value);});
					}else{
						Swal.fire( 'Sorry...!', response.message, 'error' );
					}			
				}			
			});
		}
	});
}

function trash(data){
	var controllerName = data.controller || controller;
	var fnName = data.fndelete || "delete";
	var msg = data.message || "Record";
	var send_data = data.postData;
	
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
				if(response.status==0){
					Swal.fire( 'Sorry...!', response.message, 'error' );
				}else{
					initTable();
					Swal.fire( 'Deleted!', response.message, 'success' );					
				}	
			});
			Swal.fire( 'Deleted!', 'Your file has been deleted.', 'success' );
			window.location = base_url + 'app/expense';
		}
	});
	
}

function edit(data){
	var button = data.button;if(button == "" || button == null){button="both";};
	var fnedit = data.fnedit;if(fnedit == "" || fnedit == null){fnedit="edit";}
	var fnsave = data.fnsave;if(fnsave == "" || fnsave == null){fnsave="save";}
	var controllerName = data.controller;if(controllerName == "" || controllerName == null){controllerName=controller;}
	var savebtn_text = data.savebtn_text;
	var txt_editor = data.txt_editor || '';
	if(savebtn_text == "" || savebtn_text == null){savebtn_text='<i class="fa fa-check"></i> Save';}

	var resFunction = data.res_function || "";
	var jsStoreFn = data.js_store_fn || 'confirmStore';

	var fnJson = "{'formId':'"+data.form_id+"','fnsave':'"+fnsave+"','txt_editor':'"+txt_editor+"','controller':'"+controllerName+"'}";

	$.ajax({ 
		type: "POST",   
		url: base_url + controllerName + '/' + fnedit,   
		data: data.postData,
	}).done(function(response){
		$("#"+data.modal_id).modal('show');
		$("#"+data.modal_id).css({'z-index':1059,'overflow':'auto'});
		$("#"+data.modal_id).addClass(data.form_id+"Modal");
		$("#"+data.modal_id+' .modal-title').html(data.title);
		$("#"+data.modal_id+' .modal-body').html('');
		$("#"+data.modal_id+' .modal-body').html(response);
		$("#"+data.modal_id+" .modal-body form").attr('id',data.form_id);
		if(resFunction != ""){
			$("#"+data.modal_id+" .modal-body form").attr('data-res_function',resFunction);
		}
		$("#"+data.modal_id+" .modal-footer .btn-save").html(savebtn_text);
		$("#"+data.modal_id+" .modal-footer .btn-save").attr('onclick',"confirmStore("+fnJson+");");

		$("#"+data.modal_id+" .modal-header .close").attr('data-modal_id',data.modal_id);
		$("#"+data.modal_id+" .modal-header .close").attr('data-modal_class',data.form_id+"Modal");
		$("#"+data.modal_id+" .modal-footer .btn-close").attr('data-modal_id',data.modal_id);
		$("#"+data.modal_id+" .modal-footer .btn-close").attr('data-modal_class',data.form_id+"Modal");

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

		setPlaceHolder();
	});
}
</script>