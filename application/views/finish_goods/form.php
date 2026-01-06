	<form>
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""?>">

			<div class="col-md-12 form-group">
				<div class="input-group">
					<label for="item_code " style="width:25%">Item Code</label>
					<label for="item_name" style="width:75%">Item Name</label>
				</div>
				<div class="input-group">
					<input type="text" name="item_code" id="item_code" class="form-control req" value="<?=(!empty($dataRow->item_code)) ? $dataRow->item_code : ""?>" style="width:25%"/>
					<input type="text" name="item_name" id="item_name" class="form-control req" value="<?=(!empty($dataRow->item_name)) ? htmlentities($dataRow->item_name) : ""?>"  style="width:75%" />
				</div>
				
			</div>
			<div class="col-md-6 form-group">
				<label for="category_id">Item Category</label>
				<select name="category_id" id="category_id" class="form-control modal-select2 req">
                    <option value="0">--</option>
                    <?php
					$groupedCat = array_reduce($categoryData, function($pCat, $category) {
						$pCat[$category->parent_cat][] = $category;
						return $pCat;
					}, []);
					
					$options = '';
					foreach ($groupedCat as $pCat => $category):
						$options .= '<optgroup label="' . $pCat . '">';
						foreach ($category as $row):
							$selected = (!empty($dataRow->category_id) && $dataRow->category_id == $row->id) ? "selected" : "";
							$options .= '<option value="' . $row->id . '" ' . $selected . '>' . $row->category_name . '</option>';
						endforeach;
						$options .= '</optgroup>';
					endforeach;
					echo $options;
                    ?>
                </select>
			</div>
			<div class="col-md-6 form-group">
				<label for="hsn_code">HSN Code</label>
				<input type="text" name="hsn_code" id="hsn_code" class="form-control" value="<?=(!empty($dataRow->hsn_code)) ? $dataRow->hsn_code : ""?>" />
			</div>

			<div class="col-md-6 form-group">
				<label for="unit_name">Unit</label>
				<select name="unit_name" id="unit_name" class="form-control modal-select2 req">
                    <option value="0">--</option>
                    <?php
                    foreach ($unitData as $row) :
                        $selected = (!empty($dataRow->unit_name) && $dataRow->unit_name == $row->unit_name) ? "selected" : "";
                        echo '<option value="' . $row->unit_name . '" data-unit="'.$row->unit_name.'" ' . $selected . '>[' . $row->unit_name . '] ' . $row->description . '</option>';
                    endforeach;
                    ?>
                </select>
			</div>
			<div class="col-md-6 form-group">
				<label for="gst_per">GST Per.</label>
				<select name="gst_per" id="gst_per" class="form-control modal-select2 calMRP">
                    <?php
                    foreach ($gstPercentage as $row) :
                        $selected = (!empty($dataRow->gst_per) && $dataRow->gst_per == $row['rate']) ? "selected" : "";
                        echo '<option value="' . $row['rate'] . '" ' . $selected . '>' . $row['val'] . '</option>';
                    endforeach;
                    ?>
                </select>
			</div>
			<div class="col-md-4 form-group">
				<label for="price">Price<small>(Exc. Tax)</small></label>
				<input type="text" name="price" id="price" class="form-control calMRP" value="<?=(!empty($dataRow->price)) ? $dataRow->price : ""?>" />
			</div>
			<div class="col-md-4 form-group">
				<label for="mrp">MRP<small>(Inc. Tax)</small></label>
				<input type="text" name="mrp" id="mrp" class="form-control calMRP" value="<?=(!empty($dataRow->mrp)) ? $dataRow->mrp : ""?>" />
			</div>
			<div class="col-md-4 form-group">
				<label for="wt_pcs">Weight Per Pcs.</label>
				<input type="text" name="wt_pcs" id="wt_pcs" class="form-control" value="<?=(!empty($dataRow->wt_pcs)) ? $dataRow->wt_pcs : ""?>" />
			</div>
			<div class="col-md-6 form-group">
				<label for="primary_packing">Primary Packing</label>
				<input type="text" name="primary_packing" id="primary_packing" class="form-control numericOnly" value="<?=(!empty($dataRow->primary_packing)) ? floatval($dataRow->primary_packing) : ""?>" />
			</div>

			<div class="col-md-6 form-group">
				<label for="master_packing">Master Packing</label>
				<input type="text" name="master_packing" id="master_packing" class="form-control numericOnly" value="<?=(!empty($dataRow->master_packing)) ? floatval($dataRow->master_packing) : ""?>" />
			</div>						
			<div class="col-md-12 form-group">
				<label for="img_file">Image File</label>
				<div class="input-group">
					<input type="file" class="form-control" name="img_file">
				</div>
			</div>
			<div class="col-md-12 form-group">
				<label for="remark">Remark</label>
				<input type="text" name="remark" id="remark" class="form-control" value="<?=(!empty($dataRow->remark)) ? $dataRow->remark : ""?>" />
			</div>

        </div>
		<h4 class="fs-15 text-primary border-bottom-sm">Custom Fields</h4>
		<div class="row">
		<?php
		if(!empty($customFieldList)){
			foreach($customFieldList as $field){
				?>
				<div class="col-md-6 form-group">
					<label for="wt_pcs"><?=$field->field_name?></label>
					<?php
					if($field->field_type == 'SELECT'){
						?>
						<select name="customField[f<?=$field->field_idx?>]" id="f<?=$field->field_idx?>" class="form-control modal-select2">
							<option value="">Select</option>
						<?php
						foreach($masterDetailList as $row){
							if($row->type == $field->id){
								$selected = (!empty($customData) && !empty(htmlentities($customData->{'f'.$field->field_idx}) && htmlentities($customData->{'f'.$field->field_idx}) == htmlentities($row->title)))?'selected':'';
								?>
								<option value="<?=htmlentities($row->title)?>" <?=$selected?>><?=$row->title?></option>
								<?php
							}
						}
					}elseif($field->field_type == 'TEXT'){
						?>
						<input type="text" name="customField[f<?=$field->field_idx?>]" id="f<?=$field->field_idx?>" class="form-control" value="<?=(!empty($customData) && !empty($customData->{'f'.$field->field_idx}))?$customData->{'f'.$field->field_idx}:''?>">
						<?php
					}elseif($field->field_type == 'NUM'){
						?>
						<input type="text" name="customField[f<?=$field->field_idx?>]" id="f<?=$field->field_idx?>" class="form-control floatOnly" value="<?=(!empty($customData) && !empty($customData->{'f'.$field->field_idx}))?$customData->{'f'.$field->field_idx}:''?>">
						<?php
					}
					?>
					</select>
				</div>
				<?php
			}
		}
		?>
		</div>
    </div>
</form>
<script>
$(document).ready(function(){
   
	// $(document).on('change','#gst_per',function(){
	// 	// $("#gst_per").val(($(this).find(':selected').data('gst_per') || 0));
    //     // $("#price").trigger('change');
    // }); 
    $(document).on('change','.calMRP',function(){
        var gst_per = $("#gst_per").val() || 0;
        var price = $("#price").val() || 0;
        var mrp = $("#mrp").val() || 0;
        if(gst_per > 0){
            if(($(this).attr('id') == "price" || $(this).attr('id') == "gst_per") && parseFloat(price) > 0){
                var tax_amt = parseFloat( (parseFloat(price) * parseFloat(gst_per) ) / 100 ).toFixed(2);
                var new_mrp = parseFloat( parseFloat(price) + parseFloat(tax_amt) ).toFixed(2);
                $("#mrp").val(new_mrp);
                return true;
            }

            if($(this).attr('id') == "mrp"  && parseFloat(mrp) > 0){
                var gstReverse = parseFloat(( ( parseFloat(gst_per) + 100 ) / 100 )).toFixed(2);
                var new_price = parseFloat( parseFloat(mrp) / parseFloat(gstReverse) ).toFixed(2);
    		    $("#price").val(new_price);
                return true;
            }
        }else{
            if(($(this).attr('id') == "price" || $(this).attr('id') == "gst_per") && price > 0){
                $("#mrp").val(price);
                return true;
            }

            if(mrp > 0){
                $("#price").val(mrp);
                return true;
            }
        }
        
    });
});
</script>