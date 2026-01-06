<form id="dispatchPlan" data-res_function="getDispatchHtml">
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" id="id" value="" />
            <input type="hidden" name="so_main_id" id="so_main_id" value="<?=(!empty($dataRow['id']) ? $dataRow['id'] : '')?>" />
            <input type="hidden" name="ref_no" id="ref_no" value="<?=(!empty($dataRow['trans_number']) ? $dataRow['trans_number'] : '')?>" />
			
            <div class="col-md-3 form-group">
                <label for="dispatch_date">Dispatch Date</label>
                <input type="date" id="dispatch_date" name="dispatch_date" class="form-control req" value="<?=date('Y-m-d')?>" />
            </div>

            <div class="col-md-6 form-group">
                <label for="item_id">Item</label>
                <select  id="item_id" name="item_id" class="form-control modal-select2 req">
                    <option value="">Select Item</option>
                    <?php
                        if(!empty($itemList)):
                            foreach($itemList as $row):
                                if($row->pending_qty > 0):
                                    echo '<option data-so_trans_id="'.$row->id.'" data-order_qty="'.floatval($row->pending_qty).'" value="'.$row->item_id.'">'.(!empty($row->item_code)?'['.$row->item_code.'] ':'').$row->item_name.' | Qty : '.floatval($row->pending_qty).'</option>';
                                endif;
                            endforeach;
                        endif;
                    ?>
                </select>
                <input type="hidden" name="so_trans_id" id="so_trans_id" value="">
                <input type="hidden" name="order_qty" id="order_qty" value="">
                <div class="error item_id"></div>
            </div>
            
			<div class="col-md-3 form-group">
                <label for="qty">Dispatch Qty</label>
                <input type="text" id="qty" name="qty" class="form-control floatOnly req" value="" />
            </div>

            <div class="col-md-10 form-group">
                <label for="notes">Notes</label>
                <input type="text" id="notes" name="notes" class="form-control" value="" />
            </div>

            <div class="col-md-2 form-group">                
                <?php
                    $param = "{'formId':'dispatchPlan','fnsave':'saveDispatch','controller':'lead','res_function':'getDispatchHtml'}";
                ?>
                <button type="button" class="btn waves-effect waves-light btn-outline-success btn-save save-form btn-block mt-25" onclick="customStore(<?=$param?>)"><i class="fa fa-check"></i> Save</button>
            </div>
           
        </div>
        <hr>
        <div class="row">
            <div class="table-responsive">
            <table id="dispatchPlanTable" class="table table-bordered align-items-center">
                <thead class="thead-info">
                    <tr>
                        <th style="width:5%;">#</th>                      
                        <th>Dispatch Date</th>    
                        <th>SO Number</th>    
                        <th>Item</th>    
                        <th>Dispatch Qty</th>
                        <th>Notes</th>
                        <th class="text-center" style="width:10%;">Action</th>
                    </tr>
                </thead>
                <tbody id="dispatchPlanBody">
                </tbody>
            </table>
        </div>
        </div>
    </div>
</form>

<script>
var tbodyData = false;
$(document).ready(function(){

    $(document).on('change','#item_id',function(){
		var so_trans_id = $("#item_id :selected").data("so_trans_id");
		var order_qty = $("#item_id :selected").data("order_qty");
        $("#so_trans_id").val(so_trans_id);
        $("#order_qty").val(order_qty);
	});

    if(!tbodyData){
        var postData = {'postData':{'so_main_id':$("#so_main_id").val()},'table_id':"dispatchPlanTable",'tbody_id':'dispatchPlanBody','tfoot_id':'','fnget':'getDispatchHtml'};
        getTransHtml(postData);
        tbodyData = true;
    }
});
function getDispatchHtml(data,formId="dispatchPlan"){ 
    if(data.status==1){
        $('#'+formId)[0].reset();
        var postData = {'postData':{'so_main_id':$("#so_main_id").val()},'table_id':"dispatchPlanTable",'tbody_id':'dispatchPlanBody','tfoot_id':'','fnget':'getDispatchHtml'};
        getTransHtml(postData);
        initSelect2('right_modal_lg');
    }else {
        if(typeof data.message === "object"){
            $(".error").html("");
            $.each( data.message, function( key, value ) {$("."+key).html(value);});
        }
    }
}
</script>