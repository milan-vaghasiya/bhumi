<?php $this->load->view('app/includes/header'); ?>
<?php $this->load->view('app/includes/topbar'); ?>
    
    <!-- Page Content -->
    <div class="page-content">
        <div class="content-inner pt-0">
			<div class="container bottom-content">
                
				<form>
					<div class="input-group cd-search" id="qs">
						<input type="text" class="form-control  qs quicksearch" id="cd-search" name="cd-search" placeholder="Search..">
						<span class="input-group-text"> 
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M23.7871 22.7761L17.9548 16.9437C19.5193 15.145 20.4665 12.7982 20.4665 10.2333C20.4665 4.58714 15.8741 0 10.2333 0C4.58714 0 0 4.59246 0 10.2333C0 15.8741 4.59246 20.4665 10.2333 20.4665C12.7982 20.4665 15.145 19.5193 16.9437 17.9548L22.7761 23.7871C22.9144 23.9255 23.1007 24 23.2816 24C23.4625 24 23.6488 23.9308 23.7871 23.7871C24.0639 23.5104 24.0639 23.0528 23.7871 22.7761ZM1.43149 10.2333C1.43149 5.38004 5.38004 1.43681 10.2279 1.43681C15.0812 1.43681 19.0244 5.38537 19.0244 10.2333C19.0244 15.0812 15.0812 19.035 10.2279 19.035C5.38004 19.035 1.43149 15.0865 1.43149 10.2333Z" fill="var(--primary)"/>
							</svg>
						</span>
					</div>
				</form>
                <div class="dz-tab style-4">
				<div class="tab-slide-effect">
					<ul class="nav nav-tabs"  role="tablist" >
						<li class="tab-active-indicator " style="width: 108.391px; transform: translateX(177.625px);"></li>
						<li class="nav-item   active" role="presentation">
							<button class="nav-link buttonFilter active" id="home-tab"  data-filter=".pending_route" data-bs-toggle="tab" data-bs-target="#pending_route" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="false" tabindex="-1">Pending</button>
						</li>
						<li class="nav-item " role="presentation">
							<button class="nav-link buttonFilter" id="profile-tab" data-bs-toggle="tab" data-filter=".accepted_route" data-bs-target="#accepted_route" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false" tabindex="-1">Accepted</button>
						</li>
					</ul>
				</div>  
				<div class="list-grid" id="myTabContent1"  data-isotope='{ "itemSelector": ".listItem" }'>
					<div class="dz-list message-list" >
					<?=$routeList['allRoute']?>
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

<script src="<?=base_url()?>assets/plugins/isotop/isotope.pkgd.min.js"></script>
<script>
var buttonFilter;
var qsRegex;
var isoOptions ={};
var $grid = '';
$(document).ready(function(){
	var $qs = $('.quicksearch').keyup( debounce( function() {qsRegex = new RegExp( $qs.val(), 'gi' );initISOTOP();}, 200 ) );
	buttonFilter = $( ".buttonFilter.active" ).attr('data-filter');
	initISOTOP();
	$(document).on( 'click', '.buttonFilter', function() {
		var filterValue = $( this ).attr('data-filter');
		buttonFilter = filterValue;
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

function changeRouteStatue(data){
	setPlaceHolder();
	var fd =  data.postData;
    var msg = data.message || "Are you sure want to save this change ?";
    var fnsave = data.fnsave || "save";
    var ajaxParam = {
        url: base_url + controller + '/' + fnsave,
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
		if (result.isConfirmed){
			$.ajax(ajaxParam).done(function(response){
				$('.list-grid').isotope('destroy');
               	$(".message-list").html(response.routeList.allRoute);
                initISOTOP();
			});
		}
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
}
</script>