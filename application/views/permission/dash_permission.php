<?php $this->load->view('includes/header'); ?>
<div class="page-content-tab">
        <div class="container-fluid">
            <form id="empPermission" data-res_function="resPermission">
                <div class="row">
                    <div class="col-9">
                        <div class="page-title-box">
                            <ul class="nav nav-pills">
                                <a href="<?= base_url($headData->controller . "/appPermission/") ?>" class="btn waves-effect waves-light btn-outline-warning permission-write"> App Permission</a>
                            </ul>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="page-title-box">
                        <input type="hidden" id="menu_type" name="menu_type" value="<?=!empty($menu_type)?$menu_type:1;?>">						<select name="emp_id" id="emp_id" class="form-control select2 ">
                                <option value="">Select Employee</option>
                                <?php
                                    foreach ($empList as $row) :
                                        $empName = (!empty($row->emp_code))?'[' . $row->emp_code . '] ' . $row->emp_name:$row->emp_name;
                                        echo '<option value="' . $row->id . '">' . $empName . '</option>';
                                    endforeach;
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body reportDiv" style="min-height:75vh">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="thead-info">
                                            <tr>
                                                <th class="text-center">
                                                <input type="checkbox" id="masterSelect" class="filled-in chk-col-success checkAll" value="1"><label for="masterSelect">Select All</label>
                                                </th>
                                                <th class="text-center">Dashboard Widgets</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody >
                                            <tr>
                                                <?php
                                                if(!empty($dashPermisson)):
                                                    $i=1;
                                                    foreach($dashPermisson as $row):
                                                            echo '<tr>';
                                                            echo '<td class="text-center">
                                                                        <input type="checkbox" id="is_read'.$row->id.'" name="is_read_'.$row->id.'" class="filled-in chk-col-success checkRead" value="1" ><label for="is_read'.$row->id.'" class="mr-3"></label>
                                                                        <input type="hidden" name="widget_id[]" id="widget_id' . $row->id . '" value="' . $row->id . '">

                                                                    </td>';
                                                            echo '  <td class="text-center">'.$row->widget_name.'</td>';
                                                            echo ' </tr>';
                                                    endforeach;
                                                endif;
                                                ?>                                              
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
</div>
<div class="bottomBtn bottom-25 right-25 permission-write">
    <?php $postData = "{'formId':'empPermission','fnsave':'saveDashPermission'}"; ?>
    <button type="button" class=" btn btn-primary btn-round btn-outline-dashed font-bold permission-write save-form" style="letter-spacing:1px;" onclick="customStore(<?=$postData?>);">SAVE PERMISSION</button>
</div>


<?php $this->load->view('includes/footer'); ?>
<script>
$(document).ready(function() {
   
    $(document).on('change',"#emp_id",function(){
        var emp_id = $(this).val();
        $("#empPermission")[0].reset();
        $(".error").html("");
        $(this).val(emp_id);
        $(this).select2();
        $(".chk-col-success").removeAttr("checked");
        
        $.ajax({
            type: "POST",   
            url: base_url + controller + '/editDashPermission',   
            data: {emp_id:emp_id},
            dataType:"json"
        }).done(function(response){
            var permission = response.empPermission;
            if(permission.length > 0){
                $.each(response.empPermission,function(key, value) {
                    $("#"+value).attr("checked","checked");
                }); 
            }
        });
    });
    
    $(document).on('click', '.checkAll', function() {
        
        if ($(this).prop('checked') == true) {
            $(".checkRead").prop('checked', true);
        } else {
            $(".checkRead").prop('checked', false);
        }
    });
    

});
function resPermission(data,formId){
    if(data.status==1){
        $("#"+formId)[0].reset();
        $(".chk-col-success").removeAttr("checked");
		Swal.fire( 'Success', data.message, 'success' );
    }else{
        if(typeof data.message === "object"){
            $(".error").html("");
            $.each( data.message, function( key, value ) { $("."+key).html(value); });
        }else{
			Swal.fire( 'Sorry...!', data.message, 'error' );
        }			
    }
}
</script>