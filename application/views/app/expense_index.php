<?php 
	$this->load->view('app/includes/header'); 
	$today = new DateTime();
	$today->modify('first day of this month');$first_day = date('Y-m-d');
	$today->modify('last day of this month');$last_day = date("t",strtotime($today->format('Y-m-d')));
	$monthArr = ['Apr-'.$startYear=>'01-04-'.$startYear,'May-'.$startYear=>'01-05-'.$startYear,'Jun-'.$startYear=>'01-06-'.$startYear,'Jul-'.$startYear=>'01-07-'.$startYear,'Aug-'.$startYear=>'01-08-'.$startYear,'Sep-'.$startYear=>'01-09-'.$startYear,'Oct-'.$startYear=>'01-10-'.$startYear,'Nov-'.$startYear=>'01-11-'.$startYear,'Dec-'.$startYear=>'01-12-'.$startYear,'Jan-'.$endYear=>'01-01-'.$endYear,'Feb-'.$endYear=>'01-02-'.$endYear,'Mar-'.$endYear=>'01-03-'.$endYear];
?>
<header class="header">
		<div class="main-bar bg-primary-2">
			<div class="container">
				<div class="header-content">
					<div class="left-content">
						<a href="javascript:void(0);" class="menu-toggler me-2">
							<svg class="text-dark" xmlns="http://www.w3.org/2000/svg" height="30px" viewBox="0 0 24 24" width="30px" fill="#000000"><path d="M13 14v6c0 .55.45 1 1 1h6c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1h-6c-.55 0-1 .45-1 1zm-9 7h6c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1zM3 4v6c0 .55.45 1 1 1h6c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1zm12.95-1.6L11.7 6.64c-.39.39-.39 1.02 0 1.41l4.25 4.25c.39.39 1.02.39 1.41 0l4.25-4.25c.39-.39.39-1.02 0-1.41L17.37 2.4c-.39-.39-1.03-.39-1.42 0z"></path></svg>
						</a>
						<h5 class="title mb-0 text-nowrap">Expense</h5>
					</div>
					<div class="mid-content"> </div>
					<div class="right-content headerSearch">
					    <!-- <div class="jpsearch" id="qs1">
							<input type="text" class="input quicksearch qs1" placeholder="Search Here ..." />
							<button class="search-btn"><i class="fas fa-search"></i></button>
						</div> -->
						<a href="javascript:void(0)" class="btn btn-sm light btn-danger" onclick="getMonthWiseExpenseData()"><i class="fas fa-download"></i></a> 
					</div>
				</div>
			</div>
		</div>
	</header>
	
    <!-- Page Content -->
    <div class="page-content">
        <div class="content-inner pt-0">
			<div class="container bottom-content">
                <div class="dz-tab style-4">
					<select name="month" id="month" class="form-control select2 mt-2 mb-2">
						<?php
							echo '<option value="">All Months</option>';
							foreach($monthArr as $key=>$value):
								$selected = (date('m') == $value)?"selected":"";
								echo '<option value="'.$value.'" '.$selected.'>'.$key.'</option>';
							endforeach;
						?>
					</select>
    				<div class="tab-slide-effect">
    					<ul class="nav nav-tabs"  role="tablist" >
    						<li class="tab-active-indicator " style="width: 108.391px; transform: translateX(177.625px);"></li>
    						<li class="nav-item   active" role="presentation">
    							<button class="nav-link buttonFilter active" id="home-tab"  data-filter=".pending_exp" data-bs-toggle="tab" data-bs-target="#pending_exp" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="false" tabindex="-1">Pending</button>
    						</li>
    						<li class="nav-item " role="presentation">
    							<button class="nav-link buttonFilter" id="profile-tab" data-bs-toggle="tab" data-filter=".approved_exp" data-bs-target="#approved_exp" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false" tabindex="-1">Approved</button>
    						</li>
                            <li class="nav-item " role="presentation">
    							<button class="nav-link buttonFilter" id="profile-tab" data-bs-toggle="tab" data-filter=".rejected_exp" data-bs-target="#rejected_exp" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false" tabindex="-1">Rejected</button>
    						</li>
    					</ul>
    				</div>    
    				<div class="tab-content px-0 list-grid" id="myTabContent1"  data-isotope='{ "itemSelector": ".listItem" }'>
						<div class="tab-pane fade active show" id="pending_exp" role="tabpanel" aria-labelledby="home-tab" tabindex="0" >
                            <ul class="dz-list message-list pendingList" >
                                <?=$expList['pendingExp']?>
                            </ul>
    					</div>
                        <div class="tab-pane fade " id="approved_exp" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                            <ul class="dz-list message-list approveList" >
                                <?=$expList['approvedExp']?>
                            </ul>
    					</div>
                        <div class="tab-pane fade " id="rejected_exp" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                            <ul class="dz-list message-list rejectList" >
                                <?=$expList['rejectedExp']?>
                            </ul>
    					</div>
                        <div class="review-box" >
                            <a href="<?=base_url("app/expense/addExpense")?>" class="add-btn  permission-write" style="margin-bottom:80px">
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
<?php $this->load->view('app/includes/bottom_menu'); ?>
<?php $this->load->view('app/includes/footer'); ?>
<?php $this->load->view('app/includes/sidebar'); ?>

<script src="<?=base_url()?>assets/plugins/isotop/isotope.pkgd.min.js"></script>
<script>
var qsRegex;
var isoOptions ={};
var $grid = '';
$(document).ready(function(){
	
	$('.select2').each(function() { 
        $(this).select2({ dropdownParent: $(this).parent()});
    });
	
	$(document).on("change","#month",function(){
		var date = $(this).val();
		$.post("<?= base_url("app/expense/getExpenseData");?>",{date:date}).done(function(response){
			var data = JSON.parse(response);
			$(".pendingList").html(data.expList.pendingExp);
			$(".approveList").html(data.expList.approvedExp);
			$(".rejectedExp").html(data.expList.rejectedExp);
		});
	});

    initISOTOP();
	var $qs = $('.quicksearch').keyup( debounce( function() {qsRegex = new RegExp( $qs.val(), 'gi' );$grid.isotope();}, 200 ) );

	$(document).on( 'click', '.buttonFilter', function() {
		initISOTOP();
	});
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
		itemSelector: 'tab-pane active .listItem',
		layoutMode: 'fitRows',
		filter: function() {return qsRegex ? $(this).text().match( qsRegex ) : true;}
	};
    // $('.listItem').css('position', 'static');
	// init isotope
	$grid = $('.list-grid').isotope( isoOptions );
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

function getMonthWiseExpenseData()
{
    var month = $("#month").val();
	var type = 1;
	var file_type = 'PDF';
	var postData= {month:month,type:type,file_type:file_type};
	var u = window.btoa(JSON.stringify(postData)).replace(/=+$/, "");
	var url = base_url +  'expense/getMonthWiseExpenseData/' + encodeURIComponent(u);
	window.open(url);
}

function confirmStore(data){
	setPlaceHolder();

	var fnsave = data.fnsave || "save";
	var controllerName = data.controller || controller;
	
	var fd = data.postData;
	var msg = data.message || "Are you sure want to save this change ?";
	var ajaxParam = {
		url: base_url + controllerName + '/' + fnsave,
		data:fd,
		type: "POST",
		dataType:"json"
	};
	
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
					Swal.fire( 'Success', response.message, 'success' ).then((result) => { window.location = base_url + 'app/expense'; });
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
</script>

