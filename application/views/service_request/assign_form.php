<form>
    <div class="col-md-12">
        <div class="row">
            
            <input type="hidden" name="id" id="id" value="<?=$id ?>" />
            <input type="hidden" name="status" id="status" value="1">
			
            <div class="col-md-12 form-group">
                <label for="assign_to">Employee</label>   
                <select name="assign_to" id="assign_to" class="form-control modal-select2 req">
                    <option value="">Select Employee</option>
                    <?php
                    if(!empty($empData)):
                        foreach($empData as $row):
                            echo '<option value="'.$row->id.'">'.$row->emp_name.'</option>';
                        endforeach;
                    endif;
                    ?>
                </select>
                <div class="error assign_to"></div>          
            </div>

        </div>        
    </div>
</form>