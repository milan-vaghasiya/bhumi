<form>
    <div class="col-md-12">
        <div class="row">

            <div class="hiddenInput">
                <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""?>">
                <input type="hidden" name="trans_prefix" id="trans_prefix" value="<?=(!empty($dataRow->trans_prefix))?$dataRow->trans_prefix:$trans_prefix?>">
                <input type="hidden" name="trans_no" id="trans_no" value="<?=(!empty($dataRow->trans_no))?$dataRow->trans_no:$trans_no?>">
                <input type="hidden" name="entry_type" id="entry_type" value="<?=(!empty($dataRow->entry_type))?$dataRow->entry_type:2?>">
            </div>

            <div class="col-md-6 form-group">
                <label for="trans_number">Return No.</label>
                <input type="text" name="trans_number" id="trans_number" class="form-control req" value="<?=(!empty($dataRow->trans_number))?$dataRow->trans_number:$trans_prefix.sprintf("%03d",$trans_no)?>" readonly>
            </div>

            <div class="col-md-6 form-group">
                <label for="trans_date">Return Date</label>
                <input type="date" name="trans_date" id="trans_date" class="form-control req" value="<?=(!empty($dataRow->trans_date))?$dataRow->trans_date:getFyDate()?>">
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
                    <?=getPartyListOption($partyList,((!empty($dataRow->party_id))?$dataRow->party_id:0))?>
                </select>
                <div class="error party_id"></div>          
            </div>

            <div class="col-md-7 form-group">
                <label for="item_id">Product</label>   
                <select name="item_id" id="item_id" class="form-control modal-select2 req">
                    <option value="">Select Product</option>
                    <?php echo $options; ?>
                </select>
                <div class="error item_id"></div>    
                <input type="hidden" name="trans_id" id="trans_id" value="<?=(!empty($transData->id))?$transData->id:""?>">      
            </div>

            <div class="col-md-5 form-group">
                <label for="qty">Qty.</label>
                <input type="text" name="qty" id="qty" class="form-control floatOnly req" value="<?=(!empty($transData->qty))?$transData->qty:""?>">
            </div>

            <div class="col-md-12 form-group">
                <label for="remark">Remark</label>
                <input type="text" name="remark" id="remark" class="form-control" value="<?=(!empty($transData->item_remark))?$transData->item_remark:""?>">
            </div>

        </div>
    </div>
</form>
<script>
$(document).ready(function(){

    $(document).on('change','#party_id',function(){
        var party_id = $(this).val();        
        $.ajax({
            url : base_url + 'lead/getSoWiseItemList',
            type : 'post',
            data : {party_id:party_id},
            dataType: 'json'
        }).done(function(res){
            $("#item_id").html("");
            $("#item_id").html(res.options);
        });
    });

    $(document).on('change','#business_type',function(){
        var business_type = $(this).val();
        getPartyList({"business_type":business_type});
        initSelect2("right_modal");
        
    });

});
</script>