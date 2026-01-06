<form>
    <div class="col-md-12">
        <div class="row">            
                
            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id) ? $dataRow->id : "")?>" />

            <div class="col-md-6 form-group">
                <label for="type_name">Type Name</label>
                <input type="text" name="type_name" id="type_name" class="form-control req" value="<?=(!empty($dataRow->type_name) ? $dataRow->type_name : "")?>" />
            </div>

            <div class="col-md-6 form-group">
                <label for="parent_id">Parent Type </label>
                <select name="parent_id" id="parent_id" class="form-control modal-select2">
                    <option value="">Select Type</option>
                    <option value="0">N/A</option>
                    <?php
                        foreach($bTypeList as $row){
                            $selected = ((!empty($dataRow->parent_id) && $dataRow->parent_id == $row->id) ? "selected" : "");
                            echo '<option value="'.$row->id.'" '.$selected.'>'.$row->type_name.'</option>';
                        }
                    ?>
                </select>
            </div>
            
            <div class="col-md-12 form-group">
                <label for="remark">Remark</label>
                <textarea name="remark" id="remark" class="form-control" rows="2"><?=(!empty($dataRow->remark) ? $dataRow->remark : "")?></textarea>
            </div>

        </div>
    </div>
</form>