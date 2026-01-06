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
                    <h5 class="title mb-0 text-nowrap">Confirm Order</h5>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- Header -->

<div class="page-content">
    <div class="container bottom-content shop-cart-wrapper"> 

        <form id="orderForm" >
            <input type="hidden" id="party_id" name="party_id" value="<?=$party_id?>">
            <input type="hidden" id="lead_id" name="lead_id" value="<?=$lead_id?>">
            <input type="hidden" id="from_entry_type" name="from_entry_type" value="<?=$from_entry_type?>">
            <input type="hidden" id="from_ref_id" name="from_ref_id" value="<?=$from_ref_id?>">
            <input type="hidden" id="id" name="id" value="">
            
            <ul>
            <?php
            if(!empty($itemList)){
                foreach($itemList as $row){
                    ?>
                    <li>
                        <div class="item-content">
                            
                            <input type="hidden" name="price[]" id="price<?=$row->id?>" value="<?=$row->price?>" class="form-control calculateRow" placeholder="Price" data-row_id="<?=$row->id?>">
                            <input type="hidden" name="ref_id[]" id="ref_id<?=$row->id?>" value="<?=$itemData[$row->id]['ref_id']?>">
                            <input type="hidden" name="wt_pcs[]" id="wt_pcs<?=$row->id?>" value="<?=$row->wt_pcs?>">
                            <input type="hidden" name="item_id[]" value="<?=$row->id?>">
                            <input type="hidden" name="item_name[]" value="<?=$row->item_code.' '.$row->item_name?>">
                            <input type="hidden" id="amount<?=$row->id?>" class="amount" value="<?=($itemData[$row->id]['qty']*$row->price)?>">
                            <input type="hidden" name="gst_per[]" id="gst_per<?=$row->id?>" value="<?=$row->gst_per?>">
                            <input type="hidden" name="gst_amount[]" id="tax_amount<?=$row->id?>" class="taxAmount" value="<?=(($itemData[$row->id]['qty']*$row->price)*$row->gst_per)/100?>">
                            <input type="hidden" name="disc_price[]" id="disc_price<?=$row->id?>" value="">
                            <input type="hidden" id="disc_amt<?=$row->id?>" value="" class="disc_amt">
                            <input type="hidden" name="net_price[]" id="net_price<?=$row->id?>" value="">
                            <input type="hidden" name="net_amt[]" id="net_amt<?=$row->id?>" value="<?=$itemData[$row->id]['qty']*$row->price?>" class="netAmount">
                            
                            <input type="hidden" name="total_qty[]" id="total_qty<?=$row->id?>" value="">
                            <div class="d-flex pb-2">
                                <div class="pe-3">
                                    <h5 class="font-14 font-700 pb-2"><?=$row->item_name?></h5>
                                </div>
                                <div class="ms-auto">
                                     <span class="d-block fw-bold">&#8377; <?=$row->price?></span>
                                </div>
                            </div>
                            <div class="d-flex pb-2">
                                <div class="pe-3">
                                    <div class="input-style input-mini input-style-always-active has-borders no-icon">
                                        <!-- <select class="form-control order_unit" name="order_unit[]" id="order_unit<?=$row->id?>" data-row_id="<?=$row->id?>" hidden>
                                            <option value="1" <?=($itemData[$row->id]['order_unit'] == 1)?'selected':''?> >Default</option>
                                            <option value="<?=$row->primary_packing?>" <?=($itemData[$row->id]['order_unit'] == $row->primary_packing)?'selected':''?> >Primary Packing (<?=floatval($row->primary_packing)?>)</option>
                                            <option value="<?=$row->master_packing?>" <?=($itemData[$row->id]['order_unit'] == $row->master_packing)?'selected':''?> >Master Packing (<?=floatval($row->master_packing)?>)</option>
                                        </select> -->
                                        <div class="input-style input-mini input-style-always-active has-borders no-icon">
                                            <label for="regular_disc" class="color-highlight">Disc. (%)</label>
                                            <input type="number" class="form-control calculateRow" name="regular_disc[]" id="regular_disc<?=$row->id?>" value="0" data-row_id="<?=$row->id?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="ms-auto">
                                    <div class="dz-stepper border-1 stepper-fill small-stepper <?=($itemData[$row->id]['qty'] > 0)?'active':''?>">
                                        <input class="stepper calculateRow floatOnly font-12 <?=($itemData[$row->id]['qty'] > 0)?'active':''?>" id="qty<?=$row->id?>" type="number" value="<?=$itemData[$row->id]['qty']?>" name="qty[]" data-row_id="<?=$row->id?>" data-item_id ="<?=$row->id?>" style="padding:0px 5px;">
                                    </div>                                    
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <!-- <div class="content row">
                                <div class="col-6">
                                    
                                </div>
                                <div class="col-6">
                                    <div class="input-style input-mini input-style-always-active has-borders no-icon">
                                        <label for="kg_price" class="color-highlight">KG Price (<?=(($row->wt_pcs > 0) ? ((1/$row->wt_pcs)*$row->price) : floatval($row->price))?> / Kg)</label>
                                        <input type="number" class="form-control calculateRow" name="kg_price[]" id="kg_price<?=$row->id?>" value="0" data-row_id="<?=$row->id?>">
                                    </div>
                                </div>
                            </div> -->
                            <div class="divider"></div>
                        </div>

                    </li>
                    
                    <?php
                }
            }
            ?>
            </ul>
        </form>
            
        <div class="footer fixed">
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
                                        <li>
                                            <span class="text-soft">Subtotal</span>
                                            <span class="text-soft sub_total">0</span>
                                        </li>
                                        <li>
                                            <span class="text-soft">Discount</span>
                                            <span class="text-soft total_disc">0</span>
                                        </li>
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
                        $param = "{'formId':'orderForm','party_id':".$party_id.",'fnsave':'saveSalesOrder','controller':'app/lead/'}";
                    ?>
                    <a href="javascript:void(0)" class="btn btn-primary btn-block flex-1 text-uppercase btn-save" onclick="store(<?=$param?>)">Confirm & Save</a>
                </div>
            </div>
        </div>	
     
    </div>   
</div>   

    
<?php $this->load->view('app/includes/footer'); ?>
<script>
$(document).ready(function(){
    window.scrollTo(0, document.body.scrollHeight);

    
    setTimeout(function() { $('.calculateRow').trigger("change"); }, 100);
    //calculateAmount();
    $(document).on('change keyup input', '.calculateRow', function () {
        var row_id = $(this).data('row_id');
        var order_unit = 1;//parseFloat($("#order_unit"+row_id).val()) || 0;
        var qty = parseFloat($("#qty"+row_id).val()) || 0;
        qty = qty * order_unit;
        var price = parseFloat($("#price"+row_id).val()) || 0;
        var gst_per = parseFloat($("#gst_per"+row_id).val()) || 0;
        
        var disc_per = parseFloat($("#regular_disc"+row_id).val()) || 0;
        var kg_price = 0;//parseFloat($("#kg_price"+row_id).val()) || 0;
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

    // flatten object by concatting values
    function concatValues( obj ) {
        var value = '';
        for ( var prop in obj ) {
            value += obj[ prop ];
        }
        return value;
    }
    
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

function store(postData){
    console.log(postData);
    var formId = postData.formId;
    var fnsave = postData.fnsave || "save";
    var controllerName = postData.controller || controller;

    var form = $('#'+formId)[0];
    var fd = new FormData(form);
    $(".btn-save").attr("disabled", true);
    $.ajax({
        url: base_url + controllerName+'/'+fnsave,
        data:fd,
        type: "POST",
        processData:false,
        contentType:false,
        dataType:"json",
    }).done(function(data){
        $(".btn-save").removeAttr("disabled");
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
                    window.location = base_url + controller + '/order';
                });
        }else{
            if(typeof data.message === "object"){
                $(".error").html("");
                $.each( data.message, function( key, value ) {$("."+key).html(value);});
            }else{
                Swal.fire( 'Sorry...!', data.message, 'error' );

                
            }			
        }				
    });
}
 
function reInitStepper (){
    var stepperAdd = document.querySelectorAll('.stepper-add');
    var stepperSub = document.querySelectorAll('.stepper-sub');
    if(stepperAdd.length){
        stepperAdd.forEach(el => el.addEventListener('click', event => {
            var currentValue = el.parentElement.querySelector('input').value
            el.parentElement.querySelector('input').value = +currentValue + 1
        }))

        stepperSub.forEach(el => el.addEventListener('click', event => {
            var currentValue = el.parentElement.querySelector('input').value
            el.parentElement.querySelector('input').value = +currentValue - 1
        }))
    }
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
    var taxable =( amountSum - discSum.toFixed(2));
    $(".sub_total").html(amountSum);
    $(".total_disc").html(discSum);
    $(".taxable").html(taxable);
    $(".total_tax").html(taxAmountSum);
    $(".total_amount").html((total_amount).toFixed(2));
}
</script>