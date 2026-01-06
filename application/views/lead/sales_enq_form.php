<form autocomplete="off" id="saveSalesEnquiry" data-res_function="resSaveEnquiry">
	<div class="col-md-12">
		<div class="row">
			<input type="hidden" name="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""?>">
			<input type="hidden" name="party_id" id="party_id" value="<?=(!empty($dataRow->party_id))?$dataRow->party_id:(!empty($party_id)?$party_id:"")?>">
			<input type="hidden" name="lead_id" id="lead_id" value="<?=(!empty($dataRow->lead_id))?$dataRow->lead_id:(!empty($lead_id)?$lead_id:"")?>">
			<input type="hidden" name="sales_executive" id="sales_executive" value="<?=(isset($dataRow->sales_executive))?$dataRow->sales_executive:(!empty($executive_id)?$executive_id:"")?>">
			<input type="hidden" name="trans_prefix" value="<?=(!empty($dataRow->trans_prefix))?$dataRow->trans_prefix:(!empty($trans_prefix)?$trans_prefix:"")?>" />
			<input type="hidden" name="trans_no" value="<?=(!empty($dataRow->trans_no))?$dataRow->trans_no:(!empty($trans_no)?$trans_no:"")?>" /> 

			<div class="col-md-6 form-group">
                <label for="trans_number">Enquiry No.</label>
                <input type="text" name="trans_number" id="trans_number" class="form-control req" value="<?=(!empty($dataRow->trans_number))?$dataRow->trans_number:$trans_number?>" readonly>
            </div>

			<div class="col-md-6 form-group">
				<label for="trans_date">Enquiry Date</label>
				<input type="date" id="trans_date" name="trans_date" class=" form-control req" placeholder="dd-mm-yyyy" aria-describedby="basic-addon2" value="<?=(!empty($dataRow->trans_date))?$dataRow->trans_date:date("Y-m-d")?>" />	
			</div>

			<div class="col-md-12 form-group">
				<label for="remark">Notes</label>
				<input type="text" name="remark" id="remark" class="form-control" value="<?=(!empty($dataRow->remark))?$dataRow->remark:""?>">
			</div>
		</div>
		<hr>
		<div class="row">
            <input type="hidden" id="trans_id" value="">
			<input type="hidden" name="row_index" id="row_index" value="">
			<div class="col-md-8 form-group">
				<label for="item_id">Product Name</label>
				<select id="item_id" class="form-control modal-select2 req">
					<option value="">Select Product Name</option>
					<?=getItemListOption($itemList)?>
				</select>
			</div>

			<div class="col-md-4 form-group">
				<label for="qty">Quantity</label>
				<input type="number" id="qty" class="form-control floatOnly req" value="0">
			</div>

			<div class="col-md-9 form-group">
				<label for="item_remark">Remarks</label>
				<input type="text" id="item_remark" class="form-control" value="">
			</div>

			<div class="col-md-3">
				<button type="button" class="btn btn-info btn-block waves-effect float-right addEnqItem mt-25"><i class="fa fa-plus"></i> Add </button>
			</div>
		</div>
		<hr>
		<div class="row">
            <div class="error itemData"></div>
            <div class="table-responsive">
                <table id="salesEnqItems" class="table table-bordered">
                    <thead class="thead-info">
                        <tr>
                            <th style="width:5%;">#</th>
                            <th style="width:30%;">Item Name</th>
							<th style="width:15%;">Qty.</th>
							<th style="width:25%;">Remarks</th>
                            <th style="width:25%;" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="batchData">                            
                        <tr id="noData">
                            <td class="text-center" colspan="5">No data available in table</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
	</div>
</form>
<script src="<?php echo base_url();?>assets/js/custom/sales-enquiry-form.js?v=<?=time()?>"></script>
<?php
if(!empty($dataRow->itemList)):
    foreach($dataRow->itemList as $row):
        $row->row_index = "";
		$row->item_name = (!empty($row->item_code) ? "[ ".$row->item_code." ] ".$row->item_name : $row->item_name);
        echo '<script>AddEnqRow('.json_encode($row).');</script>';
    endforeach;
endif;
?>