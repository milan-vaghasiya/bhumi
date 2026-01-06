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
					<h5 class="title mb-0 text-nowrap">Leave Approve</h5>
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

</script>