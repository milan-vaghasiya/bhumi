<?php 
	$this->load->view('app/includes/header'); 
	$monthArr = ['Apr-'.$startYear=>'04-'.$startYear,'May-'.$startYear=>'05-'.$startYear,'Jun-'.$startYear=>'06-'.$startYear,'Jul-'.$startYear=>'07-'.$startYear,'Aug-'.$startYear=>'08-'.$startYear,'Sep-'.$startYear=>'09-'.$startYear,'Oct-'.$startYear=>'10-'.$startYear,'Nov-'.$startYear=>'11-'.$startYear,'Dec-'.$startYear=>'12-'.$startYear,'Jan-'.$endYear=>'01-'.$endYear,'Feb-'.$endYear=>'02-'.$endYear,'Mar-'.$endYear=>'03-'.$endYear];
?>
<header class="header">
		<div class="main-bar bg-primary-2">
			<div class="container">
				<div class="header-content">
					<div class="left-content">
						<a href="javascript:void(0);" class="menu-toggler me-2">
							<svg class="text-dark" xmlns="http://www.w3.org/2000/svg" height="30px" viewBox="0 0 24 24" width="30px" fill="#000000"><path d="M13 14v6c0 .55.45 1 1 1h6c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1h-6c-.55 0-1 .45-1 1zm-9 7h6c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1zM3 4v6c0 .55.45 1 1 1h6c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1zm12.95-1.6L11.7 6.64c-.39.39-.39 1.02 0 1.41l4.25 4.25c.39.39 1.02.39 1.41 0l4.25-4.25c.39-.39.39-1.02 0-1.41L17.37 2.4c-.39-.39-1.03-.39-1.42 0z"></path></svg>
						</a>
						<h5 class="title mb-0 text-nowrap">Head Quarter</h5>
					</div>
					<div class="mid-content"> </div>
					<div class="right-content headerSearch">
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
    				<div class="tab-content px-0 list-grid" id="myTabContent1"  data-isotope='{ "itemSelector": ".listItem" }'>
						<div class="tab-pane fade active show" role="tabpanel" aria-labelledby="home-tab" tabindex="0" >
                            <ul class="dz-list message-list">
                                <?=$headList?>
                            </ul>
    					</div>
                        <div class="review-box" >
                            <a href="<?=base_url("app/headQuarter/addHeadQuarter")?>" class="add-btn  permission-write" style="margin-bottom:80px">
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
			window.location = base_url + 'app/headQuarter';
		}
	});
	
}
</script>