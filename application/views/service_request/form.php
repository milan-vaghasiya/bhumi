<form>
    <div class="col-md-12">
        <div class="row">
            
            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""; ?>" />
			
            <div class="col-md-6 form-group">
                <label for="req_no">Request No.</label>                    
                <input type="text" id="req_number" class="form-control req" value="<?=(!empty($dataRow->req_no)?$dataRow->req_no:$req_prefix.sprintf("%03d",$nextReqNo))?>" readOnly />
                <input type="hidden" name="req_prefix" id="req_prefix" value="<?=(!empty($dataRow->req_prefix)?$dataRow->req_prefix:$req_prefix)?>" readOnly />
                <input type="hidden" name="req_no" id="req_no" value="<?=(!empty($dataRow->req_no)?$dataRow->req_no:$nextReqNo)?>" />
            </div>

            <div class="col-md-6 form-group">
                <label for="req_date">Request Date</label>                    
                <input type="date" name="req_date" id="req_date" class="form-control" value="<?=(!empty($dataRow->req_date)?$dataRow->req_date:date('Y-m-d'))?>" />
            </div>

            <div class="col-md-6 form-group">
                <label for="on_site">On Site</label>   
                <select name="on_site" id="on_site" class="form-control modal-select2">
                    <option value="No">No</option>
                    <option value="Yes">Yes</option>
                </select>
            </div>

            <div class="col-md-6 form-group">
                <label for="business_type">Business Type</label>
                <select id="business_type" class="form-control modal-select2">
                    <option value="">Select Type</option>
                    <?php
                        foreach($bTypeList as $row):
                            $selected = (!empty($dataRow->business_type) && $dataRow->business_type == $row->type_name)?"selected":"";
                            echo '<option value="'.$row->type_name.'" '.$selected.' data-parent_type="'.$row->parentType.'">'.$row->type_name.'</option>';
                        endforeach;
                    ?>
                </select>
            </div>

            <div class="col-md-12 form-group">
                <label for="party_id">Customer</label>   
                <select name="party_id" id="party_id" class="form-control modal-select2 req partyOptions">
                    <option value="">Select Customer</option>
                    <?=getPartyListOption($partyList,((!empty($dataRow->party_id))?$dataRow->party_id:0))?>
                </select>
                <div class="error party_id"></div>          
            </div>

            <div class="col-md-12 form-group">
                <label for="item_id">Product</label>   
                <select name="item_id" id="item_id" class="form-control modal-select2 req">
                    <option value="">Select Product</option>
                    <?= (!empty($options) ? $options : '') ?>
                </select>
                <div class="error item_id"></div>          
            </div>

            <div class="col-md-12 form-group">
                <label for="description">Reason</label>                    
                <input type="text" name="description" id="description" class="form-control" value="<?=(!empty($dataRow->description)?$dataRow->description:'')?>" />
            </div>

        </div>        
    </div>
</form>

<script>
$(document).ready(function(){

    $(document).on('change','#party_id',function(){
		var party_id = $(this).val();
        if(party_id){
            $.ajax({
				url: base_url + controller + '/getItemOptions',
				type:'post',
				data:{party_id:party_id},
				dataType:'json',
				success:function(data)
                {
					$("#item_id").html("");
					$("#item_id").html(data.options);
				}
			});
        }
	});

    $(document).on('change','#business_type',function(){
        var business_type = $(this).val();
        getPartyList({"business_type":business_type});
        initSelect2("right_modal");
        
    });

});
</script>