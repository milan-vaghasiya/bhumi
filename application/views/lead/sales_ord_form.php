<form autocomplete="off" id="saveSalesOrder" data-res_function="resSaveOrder">
    <div class="col-md-12">
        <div class="row">

            <div class="hiddenInput">
                <input type="hidden" name="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""?>">
                <input type="hidden" name="lead_id" id="lead_id" value="<?=(!empty($dataRow->lead_id))?$dataRow->lead_id:(!empty($lead_id)?$lead_id:"")?>">
                <input type="hidden" name="sales_executive" id="sales_executive" value="<?=(isset($dataRow->sales_executive))?$dataRow->sales_executive:(!empty($executive_id)?$executive_id:"")?>">
                <input type="hidden" name="trans_prefix" value="<?=(!empty($dataRow->trans_prefix))?$dataRow->trans_prefix:(!empty($trans_prefix)?$trans_prefix:"")?>" />
                <input type="hidden" name="trans_no" value="<?=(!empty($dataRow->trans_no))?$dataRow->trans_no:(!empty($trans_no)?$trans_no:"")?>" /> 
                <input type="hidden" name="from_entry_type" id="from_entry_type" value="<?=(!empty($dataRow->from_entry_type))?$dataRow->from_entry_type:(!empty($from_entry_type)?$from_entry_type:'')?>">
                <input type="hidden" name="from_ref_id" id="from_ref_id" value="<?=(!empty($dataRow->ref_id))?$dataRow->ref_id:(!empty($from_ref_id)?$from_ref_id:'')?>">

            </div>

            <div class="col-md-4 form-group">
                <label for="trans_number">So. No.</label>
                <input type="text" name="trans_number" id="trans_number" class="form-control" value="<?=(!empty($dataRow->trans_number))?$dataRow->trans_number:$trans_number?>" readonly>
            </div>

            <div class="col-md-4 form-group">
                <label for="trans_date">So. Date</label>
                <input type="date" name="trans_date" id="trans_date" class="form-control" value="<?=(!empty($dataRow->trans_date))?$dataRow->trans_date:getFyDate()?>">
            </div>
            <?php if((empty($party_id)) && empty($dataRow->id)) { ?>
                <div class="col-md-4 form-group">
                    <label for="party_id">Customer</label>   
                    <select name="party_id" id="party_id" class="form-control modal-select2 req getDisc">
                        <option value="">Select Customer</option>
                        <?=getPartyListOption($partyList,((!empty($dataRow->party_id))?$dataRow->party_id:0))?>
                    </select>
                    <div class="error party_id"></div>          
                </div>
            <?php }else{?> 
                <input type="hidden" name="party_id" id="party_id" value="<?=(!empty($dataRow->party_id))?$dataRow->party_id:(!empty($party_id)?$party_id:"")?>">
            <?php } ?>
            <div class="col-md-4 form-group">
                <label for="doc_no">Cust. PO. No.</label>
                <input type="text" name="doc_no" id="doc_no" class="form-control" value="<?=(!empty($dataRow->doc_no))?$dataRow->doc_no:""?>">
            </div>

            <div class="col-md-<?=((empty($party_id)) && empty($dataRow->id)) ? '4' : '3'?> form-group">
                <label for="doc_date">Cust. PO. Date</label>
                <input type="date" name="doc_date" id="doc_date" class="form-control" value="<?=(!empty($dataRow->doc_date))?$dataRow->doc_date:getFyDate()?>">
            </div>
            
            <div class="col-md-<?=((empty($party_id)) && empty($dataRow->id)) ? '4' : '9'?> form-group">
                <label for="order_file">Attachment</label>
                <div class="input-group">
                    <input type="file" name="order_file" id="order_file" <?=(!empty($dataRow->order_file)?'style="width:70%"':'')?> class="form-control">
                    <div class="input-group-append">
                        <?php
                        if(!empty($dataRow->order_file)){
                            ?>
                            <a href="<?=base_url('assets/uploads/sales_order/'.$dataRow->order_file)?>" class="btn btn-outline-primary" download><i class="fa fa-arrow-down"></i></a>
                            <?php
                        }
                        ?>
                    </div>                                        
                </div>                           
            </div>

        </div>

        <hr>
        <div class="row" id="itemForm">

            <input type="hidden" id="id" value="">
            <input type="hidden" name="row_index" id="row_index" value="">
			<input type="hidden" id="ref_id" value="">

            <div class="col-md-6 form-group">
                <label for="item_id">Product Name</label>
                <select id="item_id" class="form-control modal-select2 req getDisc">
                    <option value="">Select Product Name</option>
                    <?=getItemListOption($itemList)?>
                </select>
            </div>
            <div class="col-md-3 form-group">
                <label for="order_unit">Order Unit</label>
                <select id="order_unit" class="form-control modal-select2">
                    <option value="">Select Order Unit</option>
                </select>
            </div>
            <div class="col-md-3 form-group">
                <label for="qty">Quantity</label>
                <input type="text" id="qty" class="form-control floatOnly req" value="0">
            </div>
            
            <div class="col-md-4 form-group">
                <label for="price">Price</label>
                <input type="text" id="price" class="form-control floatOnly req calculateRow" value="0" readonly/>
            </div>

            <div class="col-md-4 form-group">
                <label for="regular_disc">Disc. (%)</label>
                <input type="text" id="regular_disc" class="form-control floatOnly req calculateRow" value="0" />
            </div>
            <div class="col-md-4 form-group">
                <label for="kg_price">KG Price</label>
                <input type="text" id="kg_price" class="form-control floatOnly req calculateRow" value="0" />
            </div>
            
            <div class="col-md-9 form-group">
                <label for="item_remark">Remark</label>
                <input type="text" id="item_remark" class="form-control" value="" />
            </div>

            <div class="col-md-3">
				<button type="button" class="btn btn-info btn-block waves-effect float-right addOrderItem mt-25"><i class="fa fa-plus"></i> Add </button>
			</div>

        </div>

        <hr>
        <div class="row">
            <div class="error itemData"></div>
            <div class="table-responsive">
                <table id="salesOrderItems" class="table table-striped table-borderless">
                    <thead class="thead-info">
                        <tr>
                            <th style="width:5%;">#</th>
                            <th style="width:25%;">Item Name</th>
                            <th style="width:15%;">Qty.</th>
                            <th style="width:15%;">Price</th>
                            <th style="width:25%;">Remark</th>
                            <th class="text-center" style="width:15%;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="tempItem" class="temp_item">
                        <tr id="noData">
                            <td colspan="6" class="text-center">No data available in table</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</form>

<script src="<?php echo base_url(); ?>assets/js/custom/sales-order-form.js?v=<?= time() ?>"></script>
<?php
if(!empty($fromRefItemList)):
    foreach($fromRefItemList as $row):
        $row->row_index = "";
        $row->ref_id = $row->id;
        $row->packing_qty = $row->qty;
        $row->from_entry_type = $from_entry_type;
		$row->item_name = (!empty($row->item_code) ? "[ ".$row->item_code." ] ".$row->item_name : $row->item_name);
        unset($row->id);
        echo '<script>AddLeadOrdRow('.json_encode($row).');</script>';
    endforeach;
endif;

if(!empty($dataRow->itemList)):
    foreach($dataRow->itemList as $row):
        $row->row_index = "";
		$row->item_name = (!empty($row->item_code) ? "[ ".$row->item_code." ] ".$row->item_name : $row->item_name);
        echo '<script>AddLeadOrdRow('.json_encode($row).');</script>';
    endforeach;
endif;
?>