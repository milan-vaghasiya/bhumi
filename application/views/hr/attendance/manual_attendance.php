<form>
	<div class="col-md-12">
        <div class="row">
			<input type="hidden" name="id" id="id" value="" />
			
            <div class="col-md-12 form-group">
                <label for="emp_id">Employee</label>   
                <select name="emp_id" id="emp_id" class="form-control modal-select2 req">
                    <option value="">Select Employee</option>
                    <?php
                    if(!empty($empList)):
                        foreach($empList as $row):  
							echo '<option value="'.$row->id.'" data-shift_id="'.$row->shift_id.'">['.$row->emp_code.']'.$row->emp_name.'</option>';
                        endforeach;
                    endif;
                    ?>
                </select>
                <div class="error emp_id"></div>          
            </div>
			<div class="col-md-12 form-group">
                <label for="shift_id">Shift</label>
                <select name="shift_id" id="shift_id" class="form-control modal-select2 req">
                    <option value="">Select Shift</option>
                </select>
				<div class="error shift_id"></div>
            </div>
            <div class="col-md-12 form-group">
                <label for='punch_date'>Punch Date</label>
				<input type="datetime-local" id="punch_date" name="punch_date" class="form-control" value="<?=date('Y-m-d H:i:s')?>">
			</div>
		</div>
	</div>
</form>

<script>
$(document).ready(function(){
    $(document).on('change','#emp_id',function(){
        var emp_id = $('#emp_id').val();  
        var shift_id = $('#emp_id').find(":selected").data('shift_id') || 0;
        
        if(emp_id){
            $.ajax({
                url : base_url + controller + '/getShiftListForEmployee',
                type : 'post',
                data : { emp_id:emp_id },
                dataType : 'json'
            }).done(function(response){
                $("#shift_id").html(response.options); 
                $('#shift_id').val(shift_id); 
            });
        }else{
            $("#shift_id").html('<option value="">Select Shift</option>'); 
        }
    });
});
</script>