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
						<h5 class="title mb-0 text-nowrap">Sales Order</h5>
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
			<div class="container bottom-content ">
				<div class=" filters">
					<div class="ui-group">
						<select name="business_type" id="business_type" class="filter-select form-control select2" value-group="business_type">
							<option value="">Select Type</option>
							<?php
								foreach($bTypeList as $row):
									echo '<option value=".'.$row->type_name.'"  >'.$row->type_name.'</option>';
								endforeach;
							?>
						</select>
					</div>
				</div>
				<ul class="list-grid" data-isotope='{ "itemSelector": ".listItem" }'>
				<?php
					if(!empty($orderList)){
						foreach($orderList as $row){
							?>
							<li class="listItem item transition <?=$row->business_type ?>"  data-category="transition">
								<div class="card order-box " >
									<div class="card-body">
										<a href="javascript:void(0)">
											<div class="order-content mb-0">
												<div class="right-content">
													<div class="title mb-0"><?=$row->item_name?></div>
													<ul>
														<li>
															<p class="order-name">#<?=$row->trans_number?> | <i class="far fa-clock"></i> <?=date('d, M Y', strtotime($row->trans_date))?></p>
															<span class="order-quantity"><?=floatval($row->qty)?></span>
														</li>
														<li>
															<p class="order-name font-13"><?=$row->party_name?></p>
														</li>
													</ul>
												</div>
											</div>
											<a class="badge badge-md badge-primary float-end rounded-sm" href="<?=base_url('lead/printOrder/'.$row->trans_main_id)?>" target="_blank">Print</a>
										</a>
									</div>
								</div>
							</li>
							
							<?php
						}
					}
				?>
				</ul>
				<div class="review-box" >
					<a  href="<?=base_url("app/lead/addSalesOrder/")?>" class="add-btn  permission-write" style="margin-bottom:80px">
						<i class="fa-solid fa-plus"></i>
					</a>
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
$(document).ready(function(){
	$(".select2").select2();
	var buttonFilters = {};
        var buttonFilter;
	var qsRegex;
	var isoOptions = {
		itemSelector: '.listItem',
		layoutMode: 'fitRows',
		// filter: function() {return qsRegex ? $(this).text().match( qsRegex ) : true;}
		filter: function() {
                        var $this = $(this);
                        var searchResult = qsRegex ? $this.text().match( qsRegex ) : true;
                        var buttonResult = buttonFilter ? $this.is( buttonFilter ) : true;
                        return searchResult && buttonResult;
                    }
	};
	$('.listItem').css('position', 'static');
	// init isotope
	var $grid = $('.list-grid').isotope( isoOptions );
	var $qs = $('.quicksearch').keyup( debounce( function() {qsRegex = new RegExp( $qs.val(), 'gi' );$grid.isotope();}, 200 ) );
	$("listItem")
//$(document).on('keyup',".quicksearch",function(){console.log($(this).val());});

	// store filter for each group
	$('.filters').on( 'change', function( event ) {
		var $select = $( event.target );
		// get group key
		var filterGroup = $select.attr('value-group');
		// set filter for group
		buttonFilters[ filterGroup ] = event.target.value;
		// combine filters
		buttonFilter = concatValues( buttonFilters );
		console.log(buttonFilter);
		// set filter for Isotope
		$grid.isotope();
	});

	// flatten object by concatting values
	function concatValues( obj ) {
		var value = '';
		for ( var prop in obj ) {
			value += obj[ prop ];
		}
		return value;
	}
            
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
</script>