<form>
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""; ?>" />
            <div class="col-md-12 form-group">
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

            <div class="col-md-4 form-group">
                <label for="start_date">Start Date</label>
                <input type="date" name="start_date" id="start_date" class="form-control countTotalDays req" value="<?=(!empty($dataRow->start_date))?date('Y-m-d', strtotime($dataRow->start_date)):date("Y-m-d")?>"  />
            </div>
			
            <div class="col-md-4 form-group">
                <label for="end_date">End Date</label>
                <input type="date" name="end_date" id="end_date" class="form-control countTotalDays req" value="<?=(!empty($dataRow->end_date))?date('Y-m-d', strtotime($dataRow->end_date)):date("Y-m-d")?>" min="<?=(!empty($dataRow->end_date))?$dataRow->end_date:date("Y-m-d")?>"  />
            </div>

            <div class="col-md-4 form-group">
                <label class="totaldays" for="total_days">Total Days</label>
                <input type="text" name="total_days" id="total_days" class="form-control floatOnly req" value="<?=(!empty($dataRow->total_days))?floatval($dataRow->total_days):1; ?>" readOnly />
            </div>
            
            <div class="col-md-12 form-group">
                <label for="reason">Reason</label>
                <input type="text" name="reason" id="reason" class="form-control req" value="<?=(!empty($dataRow->reason))?$dataRow->reason:""?>" />
            </div>
        </div>
    </div>
</form>
<script>
$(document).ready(function(){
	$(document).on("change",".countTotalDays",function(){
	    var startDate  = $('#start_date').val();
		var endDate    = $('#end_date').val();
	
		const diffInMs   = new Date(endDate) - new Date(startDate)
		const diffInDays = diffInMs / (1000 * 60 * 60 * 24);
		
		var totalDay = parseFloat(diffInDays) + 1;
		if(parseFloat(totalDay) > 0){ $("#total_days").val(totalDay); }
		else{ $("#total_days").val(0); }
	});
});
</script>
