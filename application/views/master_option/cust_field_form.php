<form>
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""; ?>" />
            <input type="hidden" name="field_idx" id="field_idx" value="<?=(!empty($dataRow->field_idx))?$dataRow->field_idx:$nextIndex; ?>" />
            <input type="hidden" name="type" id="type" value="<?=(!empty($dataRow->type))?$dataRow->type:$type; ?>" />
			<?php
            if(!empty($nextIndex) && $nextIndex > 10){
                ?>
                <h5 class="text-danger"> Only 10 Fields Allowed</h5>
                <?php
            }else{
                ?>
               
                <div class="col-md-12 form-group">
                    <label for="field_name">Field Name <small>(Max. 50 Chars)</small></label>
                    <input type="text" name="field_name" id="field_name" class="form-control req" value="<?=(!empty($dataRow->field_name) ? $dataRow->field_name : "")?>" maxlength="50">
                </div>
                <div class="col-md-12 form-group">
                    <label for="field_type">Data Type</label>
                    <select name="field_type" id="field_type" class="form-control req modal-select2" >
                        <option value="TEXT" <?=((!empty($dataRow->field_type) && $dataRow->field_type == 'TEXT') ? "selected" : "")?>>TEXT (Alpha Numeric Text)</option>
                        <option value="NUM" <?=((!empty($dataRow->field_type) && $dataRow->field_type == 'NUM') ? "selected" : "")?>>NUMERIC (Only Numbers)</option>
                        <option value="SELECT" <?=((!empty($dataRow->field_type) && $dataRow->field_type == 'SELECT') ? "selected" : "")?>>SELECTION BOX (Dropdown)</option>
                    </select>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</form>