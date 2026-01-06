<form>
    <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""; ?>" />

    <div class="mb-3 ">
        <label for="emp_id">Employee</label>
        <select name="emp_id" id="emp_id" class="form-control modal-select2 req">
            <option value="">Select Employee</option>
            <?php
                foreach($empList as $row):
                    $selected = (!empty($dataRow->emp_id) && $row->id == $dataRow->emp_id)?"selected":"";
                    $emp_name = (!empty($row->emp_code)) ? '['.$row->emp_code.'] '.$row->emp_name : $row->emp_name;
                    echo '<option value="'.$row->id.'" '.$selected.'>'.$emp_name.'</option>';
                endforeach;
            ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="start_date">Start Date</label>
        <input type="date" name="start_date" id="start_date" class="form-control countTotalDays req" value="<?=(!empty($dataRow->start_date))?date('Y-m-d', strtotime($dataRow->start_date)):date("Y-m-d")?>"  />
    </div>
    
    <div class="mb-3">
        <label for="end_date">End Date</label>
        <input type="date" name="end_date" id="end_date" class="form-control countTotalDays req" value="<?=(!empty($dataRow->end_date))?date('Y-m-d', strtotime($dataRow->end_date)):date("Y-m-d")?>" min="<?=(!empty($dataRow->end_date))?$dataRow->end_date:date("Y-m-d")?>"  />
    </div>

    <div class="mb-3">
        <label class="totaldays" for="total_days">Total Days</label>
        <input type="text" name="total_days" id="total_days" class="form-control floatOnly req" value="<?=(!empty($dataRow->total_days))?floatval($dataRow->total_days):1; ?>" readOnly />
    </div>
    
    <div class="mb-3 ">
        <label for="reason">Reason</label>
        <input type="text" name="reason" id="reason" class="form-control req" value="<?=(!empty($dataRow->reason))?$dataRow->reason:""?>" />
    </div>
  
</form> 