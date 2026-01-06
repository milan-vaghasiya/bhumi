<form>
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""; ?>" />
            <input type="hidden" name="category_level" id="maincate_level" value="<?=(!empty($dataRow->category_level))?$dataRow->category_level:""; ?>" />
            <input type="hidden" name="category_type" id="category_type" value="<?=(!empty($dataRow->category_type))?$dataRow->category_type:1; ?>" />
			<div class="col-md-12 form-group">
                <label for="category_name">Category Name</label>
                <input type="text" name="category_name" class="form-control req" value="<?=(!empty($dataRow->category_name))?$dataRow->category_name:""?>" />
            </div>
            
			<div class="col-md-12 form-group">
                <label for="ref_id">Parent Category</label>
                <select name="ref_id" id="ref_id" class="form-control modal-select2 req">
                    <option value="0">Select</option>
                    <?php
                        foreach ($mainCategory as $row) :
                            if($row->id != $dataRow->id):
                                $selected = (!empty($dataRow->ref_id) && $dataRow->ref_id == $row->id) ? "selected" : ((!empty($ref_id) && $ref_id == $row->id)?"selected":"");
                                echo '<option value="' . $row->id . '" class="level_'.$row->category_level.'" data-level="'.$row->category_level.'" data-category_type="'.$row->category_type.'" ' . $selected . '>' . $row->category_name . '</option>';
                            endif;
                        endforeach;
                    ?>
                </select>
            </div>

            <div class="col-md-12 form-group">
                <label for="final_category">Is Final (Last)?</label>
                <select name="final_category" id="final_category" class="form-control modal-select2">
                    <option value="0" <?=(!empty($dataRow) && $dataRow->final_category == 0) ? "selected" : "";?>>No</option>
                    <option value="1" <?=(!empty($dataRow) && $dataRow->final_category == 1) ? "selected" : "";?>>Yes</option>
                </select>
            </div>
        </div>
    </div>
</form>
<script type="text/javascript">
$(document).ready(function(){
    var ctype = $('#category_type').val();
    $(document).on('change','#ref_id',function(){
		var ref_id = $(this).val();
		var level = $('#ref_id :selected').data('level'); 
		var category_type = $('#ref_id :selected').data('category_type'); 
        $('#maincate_level').val(level);
		$('#category_type').val(category_type);
	});
});
</script>


