<?php $this->load->view('app/includes/header'); ?>
<?php $this->load->view('app/includes/topbar'); ?>
    
    <!-- Page Content -->
    <div class="page-content">
        <div class="content-inner pt-0">
			<div class="container bottom-content">
                <form>
					<div class="input-group cd-search" id="qs">
						<input type="text" class="form-control qs quicksearch" id="cd-search" name="cd-search" placeholder="Search..">
						<span class="input-group-text"> 
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M23.7871 22.7761L17.9548 16.9437C19.5193 15.145 20.4665 12.7982 20.4665 10.2333C20.4665 4.58714 15.8741 0 10.2333 0C4.58714 0 0 4.59246 0 10.2333C0 15.8741 4.59246 20.4665 10.2333 20.4665C12.7982 20.4665 15.145 19.5193 16.9437 17.9548L22.7761 23.7871C22.9144 23.9255 23.1007 24 23.2816 24C23.4625 24 23.6488 23.9308 23.7871 23.7871C24.0639 23.5104 24.0639 23.0528 23.7871 22.7761ZM1.43149 10.2333C1.43149 5.38004 5.38004 1.43681 10.2279 1.43681C15.0812 1.43681 19.0244 5.38537 19.0244 10.2333C19.0244 15.0812 15.0812 19.035 10.2279 19.035C5.38004 19.035 1.43149 15.0865 1.43149 10.2333Z" fill="var(--primary)"/>
							</svg>
						</span>
					</div>
				</form>
                <div class="dz-tab style-4">
					<div class="tab-slide-effect">
						<ul class="nav nav-tabs text-center" role="tablist" >
							<li class="tab-active-indicator " style="width: 108.391px; transform: translateX(177.625px);"></li>
							<li class="nav-item   <?=($party_type ==2)?'active':''?>" role="presentation">
								<a href="<?=base_url('app/lead/leadList/2')?>" class="nav-link buttonFilter"  aria-controls="home-tab-pane" aria-selected="false" tabindex="-1"  type="button" role="tab">Pending</a>
							</li>
							<li class="nav-item <?=($party_type == 1)?'active':''?>" role="presentation">
								<a href="<?=base_url('app/lead/leadList/1')?>" class="nav-link buttonFilter  "  aria-controls="profile-tab-pane" aria-selected="false" tabindex="-1" type="button" role="tab">Won</a>
							</li>
							<li class="nav-item <?=($party_type == 3)?'active':''?>" role="presentation">
								<a  href="<?=base_url('app/lead/leadList/3')?>" class="nav-link buttonFilter " aria-controls="profile-tab-pane" aria-selected="false" tabindex="-1"   type="button" role="tab">Lost</a>
							</li>
						</ul>
					</div>    
					<div class="tab-content px-0  " id="myTabContent1" >
						<!-- <div class="tab-pane fade active show" id="pending_lead" role="tabpanel" aria-labelledby="home-tab" tabindex="0" > -->
							<!-- <div class=" " > -->
								<ul class="list-grid" data-isotope='{ "itemSelector": ".listItem" }'>
								<?php
									if(!empty($leadData)){
										foreach($leadData as $row){
											?>
											<li class="listItem item transition" data-category="transition">
												<div class="card order-box  "  >
													<div class="card-body">
														<a href="javascript:void(0)">
															<div class="order-content">
																<div class="right-content">
																	<h6 class="order-number"><?=$row->party_name?></h6>
																	<ul>
																		<li>
																			<p class="order-name"><?=$row->executive?></p>
																			<!-- <span class="order-quantity">x9</span> -->
																		</li>
																		<li>
																			<h6 class="order-time"><?=date('d, M ,Y H:i', strtotime($row->created_at))?></h6>
																		</li>
																	</ul>
																</div>
															</div>
															<!-- <div class="badge badge-md badge-primary float-end rounded-sm">ONGOING</div> -->
														</a>
													</div>
												</div>
											</li>
											
											<?php
										}
									}
								?>
								</ul>
								
							<!-- </div> -->
						<!-- </div> -->
						
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
$(document).ready(function(){
	var qsRegex;
	var isoOptions = {
		itemSelector: '.listItem',
		percentPosition: true,
		layoutMode: 'fitRows',
		filter: function() {return qsRegex ? $(this).text().match( qsRegex ) : true;}
	};
	$('.listItem').css('position', 'static');
	// init isotope
	var $grid = $('.list-grid').isotope( isoOptions );
	var $qs = $('.quicksearch').keyup( debounce( function() {qsRegex = new RegExp( $qs.val(), 'gi' );$grid.isotope();}, 200 ) );
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