<form>
    <div class="col-md-12">

        <div class="row">
            <div class="col-md-12 form-group">
                <label for="executive_id">Executive</label>
                <select name="executive_id" id="executive_id" class="form-control select2 req">
                    <option value="">Select Executive</option>
                    <?php
                    if(!empty($empData)){
                        foreach($empData as $row){
                            echo '<option value="'.$row->id.'">'.$row->emp_name.'</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-12 form-group">
                <label for="sales_zone_id">Sales Zone</label>
                <select name="sales_zone_id" id="sales_zone_id" class="form-control select2 req">
                    <option value="">Select Sales Zone</option>
                </select>
            </div>
        </div>

        <hr>
        <div class="row">
            <div class="table-responsive">
                <div class="error general_error"></div>
                <table class="table table-bordered">
                    <thead class="thead-info">
                        <tr class="text-center">
                            <th style="width:30%;">
                                <input type="checkbox" id="masterSelect" class="filled-in chk-col-success BulkExecutive" value=""><label for="masterSelect">Select ALL</label>
                            </th>
                            <th style="width:70%;">Lead Name</th>
                        </tr>
                    </thead>
                    <?php
                        if(!empty($leadData)) : 
                            $i=1;
                            foreach($leadData as $row): 
                                ?>
                                <tbody>
                                    <tr>
                                        <?php
                                            echo '<tr>
                                                    <td class="text-center">
                                                        <input type="checkbox" name="ref_id[]" id="ref_id_'.$i.'" class="filled-in chk-col-success BulkExecutive" value="'.$row->id.'"><label for="ref_id_'.$i.'"></label>
                                                    </td>
                                                    <td class="text-left">'.$row->party_name.'</td>
                                                </tr>';
                                        ?>                                              
                                    </tr>
                                </tbody>
                                <?php 
                                $i++;
                            endforeach;
                        endif;
                    ?>
                </table>
            </div>

        </div>
    </div>
</form>
<script>
$(document).ready(function() {

    $(document).on('change','#executive_id',function(){
		var executive_id = $(this).val();
        if(executive_id){
            $.ajax({
				url: base_url + 'parties/getSeForSalesZoneList',
				type:'post',
				data:{executive_id:executive_id},
				dataType:'json',
				success:function(data)
                {
					$("#sales_zone_id").html("");
					$("#sales_zone_id").html(data.options);
				}
			});
        }
	});
    
    $(document).on('click', '.BulkExecutive', function() {
        if ($(this).attr('id') == "masterSelect") {
            if ($(this).prop('checked') == true) {
                $("input[name='ref_id[]']").prop('checked', true);
            } else {
                $("input[name='ref_id[]']").prop('checked', false);
            }
        } else {
            if ($("input[name='ref_id[]']").not(':checked').length != $("input[name='ref_id[]']").length) {
                $("#masterSelect").prop('checked', false);
            } else {                
            }
            if ($("input[name='ref_id[]']:checked").length == $("input[name='ref_id[]']").length) {
                $("#masterSelect").prop('checked', true);
            }
            else{$("#masterSelect").prop('checked', false);}
        }
    });    

});
</script>