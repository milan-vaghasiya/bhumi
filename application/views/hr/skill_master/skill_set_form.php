<div class="col-md-12">
    <form id="addSkillSet">
        <div class="row">
            <input type="hidden" name="id" id="id" value="" />

            <div class="col-md-4 form-group">
                <label for="set_name">Set Name</label>
                <input type="text" name="set_name" id="set_name" class="form-control req" value="<?=(!empty($set_name))?$set_name:""; ?>" />
            </div>


			
			<div class="col-md-4 form-group">
                <label for="emp_dept_id">Department</label>
                <select name="emp_dept_id" id="emp_dept_id" class="form-control modal-select2 req">
                    <option value="">Select Department</option>
                    <?php
                        foreach($departmentList as $row):
                            $selected = (!empty($dataRow->emp_dept_id) && $row->id == $dataRow->emp_dept_id)?"selected":"";
                            echo '<option value="'.$row->id.'" '.$selected.'>'.$row->name.'</option>';
                        endforeach;
                    ?>
                </select>
            </div>
			
			<div class="col-md-4 from-group">
                <label for="emp_designation">Designation</label>
                <select name="emp_designation" id="emp_designation" class="form-control modal-select2 req">
                    <option value="">Select Designation</option>
                    <?php
                        foreach($designationList as $row):
                            $selected = (!empty($dataRow->emp_designation) && $row->id == $dataRow->emp_designation)?"selected":"";
                            echo '<option value="'.$row->id.'" '.$selected.'>'.$row->title.'</option>';
                        endforeach;
                    ?>
                </select>
            </div>
			
			<div class="col-md-8 form-group">
                <label for="skill_id">Skill Name</label>
                <select name="skill_id" id="skill_id" class="form-control modal-select2 req">
                    <option value="">Select Skill Name</option>
                    <?php
                        foreach($skillList as $row):
                            $selected = (!empty($dataRow->skill_id) && $row->id == $dataRow->skill_id)?"selected":"";
                            echo '<option value="'.$row->id.'" '.$selected.'> '.$row->skill_name.'</option>';
                        endforeach;
                    ?>
                </select>
            </div>

            <div class="col-md-4">
                <label for="skill_per">Req. Skill(%)</label>
                <div class="input-group">
                    <input type="text" name="skill_per" id="skill_per" class="form-control req floatOnly" value="" />
                    <div class="input-group-append">
                        <button type="button" class="btn waves-effect waves-light btn-outline-success btn-save save-form  float-right" onclick="saveSkillSet('addSkillSet','saveSkillSet');"><i class="fa fa-check"></i> Save</button>
                    </div>
                </div>                
            </div>
        </div>
    </form>
    <hr>
    <div class="row">
        <div class="table-responsive">
        <table id="skillSetTbl" class="table table-bordered align-items-center">
            <thead class="thead-info">
                <tr>
                    <th style="width:5%;">#</th>
                    <th>Set Name</th>
                    <th>Department</th>
                    <th>Designation</th>
                    <th>Skil Name</th>
                    <th>Percentage Of Skill</th>
                    <th class="text-center" style="width:10%;">Action</th>
                </tr>
            </thead>
            <tbody id="skillSet">
                <?php
                    if (!empty($dataRow)) :
                        $i = 1;
                        foreach ($dataRow as $row) :
                            $deleteParam = "{'postData':{'id' : ".$row->id.",'set_name':'".$row->set_name."'},'message' : 'Skill Set','fndelete':'deleteSkillSet'}";

                            echo '<tr>
								<td>' . $i++ . '</td>
								<td>' . $row->set_name . '</td>
								<td>' . $row->dept_name . '</td>
								<td>' . $row->dsg_title . '</td>
								<td>' . $row->skill_name . '</td>
								<td>' . $row->skill_per . '</td>
								<td class="text-center">
									<button type="button" onclick="trashSkillSet('.$deleteParam.');" class="btn btn-sm btn-outline-danger waves-effect waves-light permission-remove"><i class="mdi mdi-trash-can-outline"></i></button>
								</td>
							</tr>';
                        endforeach;
                    else :
                        echo '<tr><td colspan="7" style="text-align:center;">No Data Found</td></tr>';
                    endif;
                    ?>
            </tbody>
        </table>
        </div>
    </div>
</div>
<script>
  
function saveSkillSet(formId, fnsave) {
        //var fd = $('#'+formId).serialize();
    setPlaceHolder();
    if (fnsave == "" || fnsave == null) {
        fnsave = "save";
    }
    var form = $('#' + formId)[0];
    var fd = new FormData(form);
    $.ajax({
        url: base_url + controller + '/' + fnsave,
        data: fd,
        type: "POST",
        processData: false,
        contentType: false,
        dataType: "json",
    }).done(function(data) {
		console.log(data);
        if (data.status === 0) {
            $(".error").html("");
            $.each(data.message, function(key, value) {
                $("." + key).html(value);
            });
        } else if (data.status == 1) {
            initTable(); 
            $('#'+formId)[0].reset();//$(".modal").modal('hide');   
            Swal.fire({ icon: 'success', title: data.message});
                                
            $("#skillSet").html(data.tbodyData);
            $("#set_name").val(data.set_name);
            initSelect2("addSkillSet");
            $("#id").val("");
            $("#skill_id").val("");
            $("#skil_per").val("");
        } else {
            initTable();
            $('#' + formId)[0].reset();
            $(".modal").modal('hide');
            Swal.fire({ icon: 'error', title: data.message });
        }
    });
}

function trashSkillSet(data){
        var controllerName = data.controller || controller;
        var fnName = data.fndelete || "delete";
        var msg = data.message || "Record";
        var send_data = data.postData;
        var resFunctionName = data.res_function || "";
        
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to delete this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
        }).then(function(result) {
            if (result.isConfirmed)
            {
                $.ajax({
                    url: base_url + controllerName + '/' + fnName,
                    data: send_data,
                    type: "POST",
                    dataType:"json",
                }).done(function(response){
                    if(resFunctionName != ""){
                        window[resFunctionName](response);
                    }else{
                        if(response.status==0){
                            Swal.fire( 'Sorry...!', response.message, 'error' );
                        }else{
                            $('#skillSet').html('');
                            $('#skillSet').html(response.tbodyData);
                            initTable();
                            Swal.fire( 'Deleted!', response.message, 'success' );
                        }	
                    }
                });
                Swal.fire( 'Deleted!', 'Your file has been deleted.', 'success' );
            }
        });
        
    }
</script>