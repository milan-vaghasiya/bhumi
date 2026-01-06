<form>
    <div class="form-item">
        <input type="hidden" name="id" value="<?=$id?>">
        <input type="hidden" name="executive_id" value="<?=$executive_id?>">
        <input type="hidden" name="status" value="4">
        <label class="form-label">Reason</label>
        <select id="notes" name="notes" class="form-control select2 req">
            <option value="">Select Reason</option>
            <?php
            if(!empty($reasonList)){
                foreach($reasonList as $row){
                    ?> <option value="<?=$row->label?>"><?=$row->label?></option> <?php
                }
            }
            ?>
        </select>
    </div>
</form>