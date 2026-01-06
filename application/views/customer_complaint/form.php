<form>
    <div class="col-md-12">
        <div class="row">

            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""; ?>" />
			
            <div class="col-md-6 form-group">
                <label for="trans_number">Complaint No.</label>
                <input type="text" name="trans_number" id="trans_number" class="form-control req" value="<?=(!empty($dataRow->trans_number)) ? $dataRow->trans_number : ((!empty($trans_no) && !empty($trans_prefix)) ? $trans_prefix.sprintf('%03d',$trans_no) : "")?>" readOnly>
            </div>

            <div class="col-md-6 form-group">
                <label for="trans_date">Complaint Date</label>
                <input type="date" name="trans_date" id="trans_date" class="form-control req" value="<?=(!empty($dataRow->trans_date) ? $dataRow->trans_date : date('Y-m-d'))?>">
            </div>

            <div class="col-md-4 form-group">
                <label for="business_type">Business Type</label>
                <select  id="business_type" class="form-control modal-select2">
                    <option value="">Select Type</option>
                    <?php
                        foreach($bTypeList as $row):
                            $selected = (!empty($dataRow->business_type) && $dataRow->business_type == $row->type_name)?"selected":"";
                            echo '<option value="'.$row->type_name.'" '.$selected.' data-parent_type="'.$row->parentType.'">'.$row->type_name.'</option>';
                        endforeach;
                    ?>
                </select>
            </div>

            <div class="col-md-8 form-group">
                <label for="party_id">Customer</label>
                <select name="party_id" id="party_id" class="form-control modal-select2 req partyOptions">
                    <option value="">Select Customer</option>
                    <?=getPartyListOption($partyList, ((!empty($dataRow->party_id)) ? $dataRow->party_id : 0))?>
                </select>
            </div>

            <div class="col-md-12 form-group">
                <label for="item_id">Product</label>
                <select name="item_id" id="item_id" class="form-control modal-select2 req">
                    <option value="">Select Product</option>
                    <?=getItemListOption($itemList, ((!empty($dataRow->item_id)) ? $dataRow->item_id : 0))?>
                </select>
            </div>

            <div class="col-md-12 form-group">
                <label for="order_id">Return Order</label>
                <select name="order_id" id="order_id" class="form-control modal-select2 req">
                    <option value="">Select Order</option>
                    <?=(!empty($options) ? $options : '')?>
                </select>
            </div>

            <div class="col-md-12 form-group">
                <label for="notes">Notes</label>
                <input type="text" name="notes" id="notes" class="form-control" value="<?=(!empty($dataRow->notes) ? $dataRow->notes : "")?>">
            </div>

        </div>        
    </div>
</form>
<script>
$(document).ready(function(){
    $(document).on('change','#party_id',function(){
        var party_id = $(this).val();

        $.ajax({
            url : base_url + controller + '/getReturnOrderList',
            type : 'post',
            data : {party_id:party_id},
            dataType: 'json'
        }).done(function(res){
            $("#order_id").html("");
            $("#order_id").html(res.options);
        });
    });
    $(document).on('change','#business_type',function(){
        var business_type = $(this).val();
        $("#party_id").html("");
        getPartyList({"business_type":business_type});
        initSelect2("right_modal");
        
    });
});
</script>