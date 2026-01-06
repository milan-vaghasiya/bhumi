<form>
    <div class="col-md-12">
        <div class="row">            
            <input type="hidden" name="id" id="id" value="<?=(!empty($id) ? $id : "")?>" />

            <div class="col-md-12 form-group">
				<label for="emp_id">Employee</label>
                <select name="emp_id[]" id="emp_id" class="form-control modal-select2 req" multiple>
                    <option value="">Select Employee</option>
                    <?php
                    if(!empty($empData)):
                        foreach($empData as $row):
                            echo '<option value="'.$row->id.'">'.$row->emp_name.'</option>';
                        endforeach;
                    endif;
                    ?>
                </select>
                <div class="error emp_id"></div>
			</div>

        </div>
    </div>
</form>