<form>
    <input type="hidden" name="id" value="<?=$id?>">
    <div class="col-md-12 form-group">
        <label for="executive_id">Executive</label>
        <select id="executive_id" name="executive_id" class="form-control select2 req">
            <option value="">Select Executive</option>
            <?php
                if(!empty($salesExecutives)){
                    foreach($salesExecutives as $row){
                        $selected = (!empty($executive_id) and $executive_id == $row->id)?"selected":"";
                        echo '<option value="'.$row->id.'" '.$selected .'>'.$row->emp_name.'</option>';
                    }
                }
            ?>
        </select>
    </div>

    <div class="col-md-12 form-group">
        <label for="sales_zone_id">Sales Zone</label>
        <select name="sales_zone_id" id="sales_zone_id" class="form-control select2 req">
            <option value="">Sales Zone</option>
            <?php
                foreach($salesZoneList as $row):
                    $selected = (!empty($dataRow->sales_zone_id) && $dataRow->sales_zone_id == $row->id)?"selected":"";
                    echo '<option value="'.$row->id.'" '.$selected.'>'.$row->zone_name.'</option>';
                endforeach;
            ?>
        </select>
    </div>
</form>
<script>
$(document).ready(function(){    
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
});
</script>