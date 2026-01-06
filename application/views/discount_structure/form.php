<form>
    <div class="col-md-12">
        <div class="row">            
                

            <div class="col-md-9 form-group">
                <label for="structure_name">Name</label>
                <input type="text" name="structure_name" id="structure_name" class="form-control req" value="<?=(!empty($dataRow->structure_name) ? $dataRow->structure_name : "")?>" />
            </div>

            <div class="col-md-3 form-group">
                <label for="is_default">Is Default ? </label>
                <select name="is_default" id="is_default" class="form-control modal-select2">
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                    
                </select>
            </div>
            
            <div class="row">
               <?=$catHtml?>
            </div>

        </div>
    </div>
</form>