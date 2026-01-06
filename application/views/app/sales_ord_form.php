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
						<h5 class="title mb-0 text-nowrap">Create Order</h5>
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
        <div class="container bottom-content shop-cart-wrapper"> 
            <!-- <div class="item-list style-2"> -->
               
                <div class="row mb-3">
                    <div class="col-12 filters">
                        <div class="ui-group">
                            <select name="category" id="category" class="filter-select form-control select2" value-group="category">
                                <option value="">Category</option>
                                <?php
                                    foreach($categoryData as $row):
                                        echo '<option value=".'.(str_replace(" ",'_',$row->category_name)).'">'.$row->category_name.'</option>';
                                    endforeach;
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            <form id="orderForm">
                <input type="hidden" name="from_entry_type" id="from_entry_type" value="<?=(!empty($from_entry_type)?$from_entry_type:'')?>">
                <input type="hidden" name="from_ref_id" id="from_ref_id" value="<?=(!empty($from_ref_id)?$from_ref_id:'')?>">

                <input type="hidden" id="id" name="id" value="">
                <div class="error text-danger itemData"></div>
                <div class="row mb-3">
                    <div class="col-12 ">
                        <div class="ui-group">
                            <?php
                            if(empty($party_id)){ ?>
                                <select name="party_id" id="party_id" class="form-control select2" >
                                    <option value="">Customer</option>
                                    <?php
                                        foreach($partyList as $row):
                                            echo '<option value="'.$row->id.'">'.$row->party_name.'</option>';
                                        endforeach;
                                    ?>
                                </select>
                                <?php
                            }else{
                                ?>
                                <input type="hidden" id="party_id" name="party_id" value="<?=$party_id?>">
                                <?php
                            }
                            ?>
                            <input type="hidden" id="lead_id" name="lead_id" value="<?=$lead_id?>">
                        </div>
                    </div>
                </div>
                <div class="bottom-content shop-cart-wrapper"> 
                    <div class="item-list style-2">
                        <div class="row g-3 list-grid" data-isotope='{ "itemSelector": ".listItem" }'>
                            <?php
                            if(!empty($itemList)){
                                foreach($itemList as $row){
                                    $qty = 0;$ref_id = 0;
                                    if(!empty($fromRefItemList)){
                                        if(in_array($row->id,array_column($fromRefItemList,'item_id'))){
                                            $array_key = array_search($row->id,array_column($fromRefItemList,'item_id'));
                                            $qty = $fromRefItemList[$array_key]->qty;
                                            $ref_id = $fromRefItemList[$array_key]->id;
                                        }
                                    }
                                    ?>
                                    <div class="listItem item transition <?=(str_replace(" ",'_',$row->category_name))?> <?=$row->size?>" data-category="transition">
                                       <div class="card-item style-1 p-2">
                                           <div class="d-flex pb-2">
                                                <div class="pe-3">
                                                    <h5 class="font-14 font-700 pb-2"><?=$row->item_name?></h5>
                                                </div>
                                                <div class="ms-auto">
                                                    <span class="d-block fw-bold">&#8377; <?=$row->price?></span>
                                                    <input type="hidden" name="price[]" id="price<?=$row->id?>" value="<?=$row->price?>" class="form-control calculateRow" placeholder="Price" data-row_id="<?=$row->id?>">
                                                </div>
                                            </div>
                                            <div class="d-flex pb-2 ">
                                                <div class="pe-3">
                                                    <div class="input-style input-mini input-style-always-active has-borders no-icon">
                                                        <select class="form-control order_unit" name="order_unit[]" id="order_unit<?=$row->id?>" data-row_id="<?=$row->id?>" hidden>
                                                            <option value="1">Default</option>
                                                            <option value="<?=$row->primary_packing?>">Primary Packing (<?=floatval($row->primary_packing)?>)</option>
                                                            <option value="<?=$row->master_packing?>">Master Packing (<?=floatval($row->master_packing)?>)</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="ms-auto">
                                                    <?=$row->size?>
                                                </div>
                                            </div>
                                            <div class="dz-content" style="padding:2px;">
                                                <a class="btn btn-primary add-btn light addToCart" data-row_id="<?=$row->id?>" href="javascript:void(0);">Add to cart</a>
        										<div class="dz-stepper border-1 rounded-stepper stepper-fill <?=($qty > 0)?'active':''?>" style="width:revert-layer;right:0;">
                                                    <input class="stepper calculateRow <?=($qty > 0)?'active':''?>" id="qty<?=$row->id?>" type="text" value="<?=$qty?>" name="qty[]" data-row_id="<?=$row->id?>" data-item_id ="<?=$row->id?>" data-ref_id="<?=$ref_id?>">
                                                    <input type="hidden" name="item_id[]" value="<?=$row->id?>">
                                                    <input type="hidden" name="ref_id[]" value="<?=$ref_id?>">
                                                    <input type="hidden" name="item_name[]" value="<?=$row->item_code.' '.$row->item_name?>">
                                                    <input type="hidden" id="amount<?=$row->id?>" class="amount" value="<?=($qty*$row->price)?>">
                                                    <input type="hidden" name="gst_per[]" id="gst_per<?=$row->id?>" value="<?=$row->gst_per?>">
                                                    <input type="hidden" name="gst_amount[]" id="tax_amount<?=$row->id?>" class="taxAmount" value="<?=(($qty*$row->price)*$row->gst_per)/100?>">
                                                    
                                                    <input type="hidden" name="disc_price[]" id="disc_price<?=$row->id?>" value="0">
                                                    <input type="hidden" id="disc_amt<?=$row->id?>" value="" class="disc_amt">
                                                    <input type="hidden" name="net_price[]" id="net_price<?=$row->id?>" value="">
                                                    <input type="hidden" name="net_amt[]" id="net_amt<?=$row->id?>" value="<?=$qty*$row->price?>" class="netAmount">
        										</div>
                                            </div>
            							</div>
                                    </div>
                            <?php } } ?>
                        </div>    
                    </div>
                </div>
            </form> 
        </div>
        
		<div class="footer fixed ">
			<div class="container">
                <div class="view-title mb-2">
					<div class="accordion style-3" id="order_summary">
						<div class="accordion-item">
							<div class="accordion-header" id="heading1">
								<a href="javascript:void(0);" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#summary_body" aria-expanded="false" aria-controls="summary_body">
									<div class="d-flex align-items-center">
										<div class="icon-box me-3 bg-success">
											<i class="fa-solid fa-inr icon-bx text-white"></i>
										</div>
										<div>
											<h6 class="sub-title">Net Amount</h6>
											<h6 class="total_amount">0</h6>
										</div>
									</div>
								</a>
							</div>
							<div id="summary_body" class="accordion-collapse collapse" data-bs-parent="#order_summary">
								<div class="accordion-body pb-0">
									<ul>
                                        <!--<li>
                                            <span class="text-soft">Subtotal</span>
                                            <span class="text-soft sub_total">0</span>
                                        </li>
                                        <li>
                                            <span class="text-soft">Discount</span>
                                            <span class="text-soft total_disc">0</span>
                                        </li>-->
                                        <li>
                                            <span class="text-soft">Taxable</span>
                                            <span class="text-soft taxable">0</span>
                                        </li>
                                        <li>
                                            <span class="text-soft">TAX</span>
                                            <span class="text-soft total_tax">0</span>
                                        </li>
                                        <li>
                                            <h5>Total</h5>
                                            <h5 class="total_amount">0</h5>
                                        </li>
                                    </ul>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="footer-btn d-flex align-items-center">
                    <?php
                        $param = "{'formId':'orderForm','fnsave':'confirmOrder','controller':'app/lead/'}";
                    ?>
                    <a href="javascript:void(0)" class="btn btn-primary btn-block flex-1" onclick="store(<?=$param?>)">Save</a>
                </div>
			</div>
		</div>	
    </div>
<!--**********************************
    Scripts
***********************************-->
<?php $this->load->view('app/includes/footer'); ?>
<script src="<?=base_url()?>assets/plugins/isotop/isotope.pkgd.min.js"></script>

<script>
    $(document).ready(function(){
    
        var buttonFilters = {};
        var buttonFilter;
        var qsRegex;
        var isoOptions = {
            itemSelector: '.listItem',
            percentPosition: true,
            layoutMode: 'fitRows',
            // filter: function() {return qsRegex ? $(this).text().match( qsRegex ) : true;}
            filter: function() {
                        var $this = $(this);
                        var searchResult = qsRegex ? $this.text().match( qsRegex ) : true;
                        var buttonResult = buttonFilter ? $this.is( buttonFilter ) : true;
                        return searchResult && buttonResult;
                    },
        };
        $('.listItem').css('position', 'static');
        // init isotope
        var $grid = $('.list-grid').isotope( isoOptions );
        var $qs = $('.quicksearch').keyup( debounce( function() {qsRegex = new RegExp( $qs.val(), 'gi' );$grid.isotope();}, 200 ) );

        calculateAmount();
        $(document).on('change keyup input', '.calculateRow1', function () {
            var row_id = $(this).data('row_id');
            var order_unit = parseFloat($("#order_unit"+row_id).val()) || 0;
            var qty = parseFloat($("#qty"+row_id).val()) || 0;
            var price = parseFloat($("#price"+row_id).val()) || 0;
            var gst_per = parseFloat($("#gst_per"+row_id).val()) || 0;
            qty = qty * order_unit;
            var amount = qty*price;
            var tax_amount = ((gst_per * amount) / 100).toFixed(2);
            $("#amount"+row_id).val(amount);
            $("#tax_amount"+row_id).val(tax_amount);
            calculateAmount();
        });
        
        $(document).on('change keyup input', '.calculateRow', function () {
            var row_id = $(this).data('row_id');
            var order_unit = parseFloat($("#order_unit"+row_id).val()) || 0;
            var qty = parseFloat($("#qty"+row_id).val()) || 0;
            qty = qty * order_unit;
            var price = parseFloat($("#price"+row_id).val()) || 0;
            var gst_per = parseFloat($("#gst_per"+row_id).val()) || 0;
            
            var disc_per = parseFloat($("#regular_disc"+row_id).val()) || 0;
            var kg_price = parseFloat($("#kg_price"+row_id).val()) || 0;
            var wt_pcs = parseFloat($("#wt_pcs"+row_id).val()) || 0;
    
            var disc_price = 0; var net_price = 0; var net_amt = 0; var okp = 0;var aqp = 0;
    
            if(disc_per != "" || disc_per != "0"){
                disc_price = ((price * disc_per) / 100).toFixed(2);
            }
            if(kg_price != "" || kg_price != "0"){
                if(wt_pcs>0)
                {
                    aqp = kg_price * wt_pcs;
                    disc_price = price - aqp;
                }
            }
            net_price = price - disc_price;
            net_amt = parseFloat(qty * net_price).toFixed(2);
            var disc_amt = parseFloat(qty * disc_price).toFixed(2);
            var amount = qty*price;
            var tax_amount = ((gst_per * net_amt) / 100).toFixed(2);
            $("#amount"+row_id).val(amount);
            $("#disc_amt"+row_id).val(disc_amt);
            $("#tax_amount"+row_id).val(tax_amount);
            $("#total_qty"+row_id).val(qty);
    
            $("#disc_price"+row_id).val(disc_price);
            $("#net_price"+row_id).val(net_price);
            $("#net_amt"+row_id).val(net_amt);
    
            calculateAmount();
        });
        
        $(document).on('change', '.order_unit', function () {
            var row_id = $(this).data('row_id');
            $("#qty"+row_id).trigger('change');
        });

        $(document).on('click', '.addToCart', function () {
            var row_id = $(this).data('row_id');
            $("#qty"+row_id).trigger('change');
        });
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

        var $quicksearch = $('.quicksearch').keyup( debounce( function() {
            qsRegex = new RegExp( $quicksearch.val(), 'gi' );
            $grid.isotope();
        }) );
        
        // flatten object by concatting values
        function concatValues( obj ) {
            var value = '';
            for ( var prop in obj ) {
                value += obj[ prop ];
            }
            return value;
        }

        // debounce so filtering doesn't happen every millisecond
        function debounce( fn, threshold ) {
            var timeout;
            threshold = threshold || 100;
            return function debounced() {
                clearTimeout( timeout );
                var args = arguments;
                var _this = this;
                function delayed() {
                fn.apply( _this, args );
                }
                timeout = setTimeout( delayed, threshold );
            };
        }

      
    });

    function searchItems(ele){
        console.log($(ele).val());
    }

    function calculateAmount1(){
        var amountArray = $(".amount").map(function(){return $(this).val();}).get();
        var amountSum = 0;
        $.each(amountArray,function(){amountSum += parseFloat(this) || 0;});

        var taxAmountArray = $(".taxAmount").map(function(){return $(this).val();}).get();
        var taxAmountSum = 0;
        $.each(taxAmountArray,function(){taxAmountSum += parseFloat(this) || 0;});

        var total_amount = amountSum+taxAmountSum;
        $("#sub_total").html(amountSum);
        $("#total_tax").html(taxAmountSum);
        $("#total_amount").html(total_amount);
    }
    function calculateAmount(){
        var amountArray = $(".amount").map(function(){return $(this).val();}).get();
        var amountSum = 0;
        $.each(amountArray,function(){amountSum += parseFloat(this) || 0;});
        
        var discArray = $(".disc_amt").map(function(){return $(this).val();}).get();
        var discSum = 0;
        $.each(discArray,function(){discSum += parseFloat(this) || 0;});
    
        var taxAmountArray = $(".taxAmount").map(function(){return $(this).val();}).get();
        var taxAmountSum = 0;
        $.each(taxAmountArray,function(){taxAmountSum += parseFloat(this) || 0;});
    
        var netAmountArray = $(".netAmount").map(function(){return $(this).val();}).get();
        var netAmountSum = 0;
        $.each(netAmountArray,function(){netAmountSum += parseFloat(this) || 0;});
        
        var total_amount = netAmountSum+taxAmountSum;
        var taxable = amountSum - discSum;
        $(".sub_total").html(amountSum);
        $(".total_disc").html(discSum);
        $(".taxable").html(taxable);
        $(".total_tax").html(taxAmountSum);
        $(".total_amount").html((total_amount).toFixed(2));
    }

    function store(postData){
        console.log(postData);
        var formId = postData.formId;
        var fnsave = postData.fnsave || "save";
        var controllerName = postData.controller || controller;
        var party_id = $("#party_id").val() || 0;
        var lead_id = $("#lead_id").val() || 0 ;
        var from_entry_type = $("#from_entry_type").val() || 0;
        var from_ref_id = $("#from_ref_id").val() || 0;

        var itemArray = [];
        var batchQtyArr = $("input[name='qty[]']").map(function(){
            var qty = parseFloat($(this).val());
            var row_id = $(this).data('row_id');
            if(qty > 0){
                var item_id = $(this).data('item_id');
                var order_unit = $("#order_unit"+row_id).val();
                var ref_id = $(this).data('ref_id');
                itemArray.push({'qty':qty,'order_unit':order_unit,'item_id':item_id,'ref_id':ref_id});
            }
        });
        console.log(itemArray);
        if(itemArray.length === 0){
            $(".general_error").html("Select Item");
        }else{
            var url =  base_url + controller + '/confirmOrder/'+lead_id+'/' + party_id +'/'+from_entry_type+ '/'+from_ref_id+ '/' + encodeURIComponent(window.btoa(JSON.stringify(itemArray)));
            window.open(url,'_self');
        
        }
        
    }
</script>
