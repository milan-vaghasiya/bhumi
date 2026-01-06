<form>
    <div class="col-md-12">
        <div class="row">

            <div class="error general_error"></div>

            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""; ?>" />
                
            <div class="col-md-12 form-group">
                <label for="shift_name">Shift Name</label>
                <input type="text" name="shift_name" class="form-control req" value="<?=(!empty($dataRow->shift_name))?$dataRow->shift_name:""; ?>" />
            </div>
            <div class="col-md-6 form-group">
                <label for="shift_start">Shift Start Time</label>
                <input type="time" name="shift_start" class="form-control req" value="<?=(!empty($dataRow->shift_start))?$dataRow->shift_start:""; ?>" />
            </div>
            <div class="col-md-6 form-group">
                <label for="shift_end">Shift End Time</label>
                <input type="time" name="shift_end" class="form-control req" value="<?=(!empty($dataRow->shift_end))?$dataRow->shift_end:""; ?>" />
            </div>
            <div class="col-md-6 form-group">
                <label for="let_in_time">Let In (In Minutes)</label>
                <input type="text" name="let_in_time" class="form-control numericOnly" value="<?=(!empty($dataRow->let_in_time))?$dataRow->let_in_time:0; ?>" />
            </div>
            <div class="col-md-6 form-group">
                <label for="early_out_time">Early Out (In Minutes)</label>
                <input type="text" name="early_out_time" class="form-control numericOnly" value="<?=(!empty($dataRow->early_out_time))?$dataRow->early_out_time:0; ?>" />
            </div>
        </div>
    </div>
</form>