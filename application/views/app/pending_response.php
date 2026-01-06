<?php $this->load->view('app/includes/header'); ?>
    <!-- Header -->
	<header class="header">
		<div class="main-bar bg-primary-2">
			<div class="container">
				<div class="header-content">
					<div class="left-content">
						<a href="javascript:void(0);" class="back-btn">
							<svg height="512" viewBox="0 0 486.65 486.65" width="512"><path d="m202.114 444.648c-8.01-.114-15.65-3.388-21.257-9.11l-171.875-171.572c-11.907-11.81-11.986-31.037-.176-42.945.058-.059.117-.118.176-.176l171.876-171.571c12.738-10.909 31.908-9.426 42.817 3.313 9.736 11.369 9.736 28.136 0 39.504l-150.315 150.315 151.833 150.315c11.774 11.844 11.774 30.973 0 42.817-6.045 6.184-14.439 9.498-23.079 9.11z"></path><path d="m456.283 272.773h-425.133c-16.771 0-30.367-13.596-30.367-30.367s13.596-30.367 30.367-30.367h425.133c16.771 0 30.367 13.596 30.367 30.367s-13.596 30.367-30.367 30.367z"></path>
							</svg>
						</a>
						<h5 class="title mb-0 text-nowrap">Appointments</h5>
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
	<!-- Page Content -->
    <div class="page-content bottom-content">
        <div class="container">
			<ul class="list-grid" id="reminderList" data-isotope='{ "itemSelector": ".listItem" }'>

			</ul>
		</div>
    </div>    
    <!-- Page Content End-->

<?php $this->load->view('app/includes/bottom_menu'); ?>
<?php $this->load->view('app/includes/footer'); ?>
<script src="<?=base_url()?>assets/plugins/isotop/isotope.pkgd.min.js"></script>
<script>
	var buttonFilter;
	var qsRegex;
	var isoOptions ={};
	var $grid = '';
	$(document).ready(function(){
		var $qs = $('.quicksearch').keyup( debounce(function() {qsRegex = new RegExp( $qs.val(), 'gi' );initISOTOP();}, 200 ) );
		initISOTOP();
		
		reminderList();
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
	function edit(data){
		var button = data.button;if(button == "" || button == null){button="both";};
		var fnedit = data.fnedit;if(fnedit == "" || fnedit == null){fnedit="edit";}
		var fnsave = data.fnsave;if(fnsave == "" || fnsave == null){fnsave="save";}
		var controllerName = data.controller;if(controllerName == "" || controllerName == null){controllerName=controller;}
		var storeController = data.storeController;if(storeController == "" || storeController == null){storeController=controllerName;}
		var savebtn_text = data.savebtn_text;
		if(savebtn_text == "" || savebtn_text == null){savebtn_text='<i class="fa fa-check"></i> Save';}

		var resFunction = data.res_function || "";
		var jsStoreFn = data.js_store_fn || 'store';

		var fnJson = "{'formId':'"+data.form_id+"','fnsave':'"+fnsave+"','controller':'"+storeController+"','res_function' : '"+resFunction+"'}";

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
			$("#"+data.modal_id+" .modal-footer .btn-save").attr('onclick',jsStoreFn+"("+fnJson+");");

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

	function store(postData){
		var formId = postData.formId;
		var fnsave = postData.fnsave || "save";
		var controllerName = postData.controller || controller;
		var resFunctionName = postData.res_function || "";
		var form = $('#'+formId)[0];
		var fd = new FormData(form);

		$.ajax({
			url: base_url + controllerName+'/'+fnsave,
			data:fd,
			type: "POST",
			processData:false,
			contentType:false,
			dataType:"json",
		}).done(function(data){
			if(resFunctionName != ""){
				window[resFunctionName](data,formId);
			}else{
				if(data.status==1){
					$('#'+formId)[0].reset(); colseModal(formId);
					Swal.fire({ icon: 'success', title: data.message});
				}else{
					if(typeof data.message === "object"){
						$(".error").html("");
						$.each( data.message, function( key, value ) {$("."+key).html(value);});
					}else{
						
						Swal.fire({ icon: 'error', title: data.message });
					}			
				}	
			}				
		});
	}

	function colseModal(formId){
		var modal_id = $("."+formId+"Modal").attr('id');
		$("#"+modal_id).removeClass(formId+"Modal");
		$("#"+modal_id+' .modal-body').html("");
		$("#"+modal_id).modal('hide');	
		$(".modal").css({'overflow':'auto'});

		$("#"+modal_id+" .modal-header .close").attr('data-modal_id',"");
		$("#"+modal_id+" .modal-header .close").attr('data-modal_class',"");
		$("#"+modal_id+" .modal-footer .btn-close").attr('data-modal_id',"");
		$("#"+modal_id+" .modal-footer .btn-close").attr('data-modal_class',"");
	}

	function reminderReponse(data,formId){
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
				colseModal(formId);
				reminderList();
			});
		}else{
			if(typeof data.message === "object"){
				$(".error").html("");
				$.each( data.message, function( key, value ) {$("."+key).html(value);});
			}else{
				Swal.fire({ icon: 'error', title: data.message });
			}			
		}	
	}
	function reminderList(){
		$.ajax({
            url: base_url  + 'app/dashboard/getReminderData',
            data:{'visit_status':status},
            type: "POST",
            dataType:"json",
        }).done(function(response){
			$('.list-grid').isotope('destroy');
			$("#reminderList").html(response.html);			
			initISOTOP();
		});
	}

	function initISOTOP(){
		isoOptions = {
			itemSelector: '.listItem',
			percentPosition: true,
			layoutMode: 'fitRows',
			filter: function() {
				var $this = $(this);
				var searchResult = qsRegex ? $this.text().match( qsRegex ) : true;
				var buttonResult = buttonFilter ? $this.is( buttonFilter ) : true;
				return searchResult && buttonResult;
			}
		};
		// init isotope
		var $grid = $('.list-grid').isotope( isoOptions );
		$('.listItem').css('position', 'static');

	}

</script>

