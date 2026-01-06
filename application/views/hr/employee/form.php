<form autocomplete="off">
    <div class="col-md-12">
        <div class="row">

            <input type="hidden" name="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""; ?>" />
			<input type="hidden" name="status" value="<?=(!empty($dataRow->status))?$dataRow->status:$status; ?>" />
            <input type="hidden" name="emp_role" value="6" />
			
            <div class="col-md-4 form-group">
				<label for="emp_code">User/Login ID</label>
				<div class="input-group">
					<input type="text" name="emp_code" class="form-control numericOnly req" value="<?=(!empty($dataRow->emp_code))?$dataRow->emp_code:''?>" />
				</div>
            </div>
			
			<div class="col-md-4 form-group">
                <label for="emp_category">Emp Category</label>
                <select name="emp_category" id="emp_category" class="form-control modal-select2">
                    <option value="">Select Category</option>
                    <?php
                        foreach($empCategoryList as $row):
                            $selected = (!empty($dataRow->emp_category) && $row->id == $dataRow->emp_category)?"selected":"";
                            echo '<option value="'.$row->id.'" '.$selected.'> '.$row->category.'</option>';
                        endforeach;
                    ?>
                </select>
            </div>

            <div class="col-md-4 form-group">
                <label for="emp_name">User Name</label>
                <input type="text" name="emp_name" class="form-control text-capitalize req" value="<?=(!empty($dataRow->emp_name))?$dataRow->emp_name:""; ?>" />
            </div>
			
			<div class="col-md-4 form-group">
                <label for="father_name">Father/Husband Name</label>
                <input type="text" name="empDetails[father_name]" class="form-control" value="<?=(!empty($dataRow->father_name))?$dataRow->father_name:""?>" />
            </div>
           
            <div class="col-md-4 form-group">
                <label for="emp_email">Email ID</label>
                <input type="text" name="emp_email" class="form-control" value="<?=(!empty($dataRow->emp_email))?$dataRow->emp_email:""?>" />
            </div>
			
            <div class="col-md-4 form-group">
                <label for="emp_contact">Phone No.</label>
                <input type="text" name="emp_contact" class="form-control numericOnly req" value="<?=(!empty($dataRow->emp_contact))?$dataRow->emp_contact:""?>" />
            </div>

			<div class="col-md-3 form-group">
                <label for="emp_alt_contact">Alt. Phone Name</label>
                <input type="text" name="empDetails[emp_alt_contact]" class="form-control numericOnly" value="<?=(!empty($dataRow->emp_alt_contact))?$dataRow->emp_alt_contact:""?>" />
            </div>
			
			<div class="col-md-3 form-group">
                <label for="marital_status">Marital Status</label>
                <select name="empDetails[marital_status]" id="marital_status" class="form-control modal-select2">
                    <option value="">Select status</option>
                    <option <?= (!empty($dataRow->marital_status) && $dataRow->marital_status == "Married")? "selected":"";?> value="Married">Married</option>
                    <option <?= (!empty($dataRow->marital_status) && $dataRow->marital_status == "Unmarried")? "selected":"";?> value="Unmarried">Unmarried</option>
                </select>
            </div>

            <div class="col-md-3 form-group">
                <label for="emp_gender">Gender</label>
                <select name="emp_gender" id="emp_gender" class="form-control modal-select2">
                    <option value="">Select Gender</option>
                    <?php
                        foreach($genderList as $value):
                            $selected = (!empty($dataRow->emp_gender) && $value == $dataRow->emp_gender)?"selected":"";
                            echo '<option value="'.$value.'" '.$selected.'>'.$value.'</option>';
                        endforeach;
                    ?>
                </select>
				<div class="error emp_gender"></div>
            </div>

            <div class="col-md-3 form-group">
                <label for="shift_id">Shift</label>
                <select name="shift_id" id="shift_id" class="form-control modal-select2 req">
                    <option value="">Select Shift</option>
                    <?php
                        foreach($shiftList as $row):
                            $selected = (!empty($dataRow->shift_id) && $row->id == $dataRow->shift_id)?"selected":"";
                            echo '<option value="'.$row->id.'" '.$selected.'>'.$row->shift_name.'</option>';
                        endforeach;
                    ?>
                </select>
				<div class="error shift_id"></div>
            </div>

            <div class="col-md-3 form-group">
                <label for="joining_date">Joining Date</label>
                <input type="date" name="joining_date" id="joining_date" class="form-control" value="<?=(!empty($dataRow->joining_date))?$dataRow->joining_date:date("Y-m-d")?>" max="<?=(!empty($dataRow->joining_date))?$dataRow->joining_date:date("Y-m-d")?>" />
            </div>

			<div class="col-md-3 form-group">
                <label for="aadhar_no">Aadhar No.</label>
                <input type="text" name="aadhar_no" class="form-control numericOnly" value="<?=(!empty($dataRow->aadhar_no))?$dataRow->aadhar_no:""?>" />
            </div>

            <div class="col-md-3 form-group">
                <label for="pan_no">Pan No.</label>
                <input type="text" name="pan_no" class="form-control numericOnly" value="<?=(!empty($dataRow->pan_no))?$dataRow->pan_no:""?>" />
            </div>

            <div class="col-md-3 form-group">
                <label for="emp_birthdate">Date of Birth</label>
                <input type="date" name="emp_birthdate" id="emp_birthdate" class="form-control" value="<?=(!empty($dataRow->emp_birthdate))?$dataRow->emp_birthdate:date("Y-m-d")?>" max="<?=(!empty($dataRow->emp_birthdate))?$dataRow->emp_birthdate:date("Y-m-d")?>" />
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
				<div class="error emp_dept_id"></div>
            </div>
            
            <div class="col-md-4 form-group">
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
				<div class="error emp_designation"></div>
            </div>
			
			<div class="col-md-4 form-group">
                <label for="emp_experience">Experience</label>
                <input type="text" name="empDetails[emp_experience]" class="form-control" value="<?=(!empty($dataRow->emp_experience))?$dataRow->emp_experience:""?>" />
            </div>
			
			<div class="col-md-4 form-group">
                <label for="rec_source">Source</label>
				<select name="empDetails[rec_source]" class="form-control modal-select2">
					<option value="">Select Source</option>
					<?php
						foreach($this->recSource as $row):
							$selected = (!empty($dataRow->rec_source) && $dataRow->rec_source == $row) ? "selected" : "";
							echo '<option '.$selected.' value="'.$row.'">'.$row.'</option>';
						endforeach;
					?>
				</select>
            </div>
			
			<div class="col-md-4 form-group">
                <label for="ref_by">Reference</label>
                <input type="text" name="empDetails[ref_by]" class="form-control" value="<?=(!empty($dataRow->ref_by))?$dataRow->ref_by:""?>" />
            </div>

            <div class="col-md-4 form-group">
                <label for="is_se">Is Executive?</label>
                <select name="is_se" id="is_se" class="form-control modal-select2">
                    <option value="Yes" <?=(!empty($dataRow->is_se) && $dataRow->is_se == "Yes") ? "selected" : ""?>>Yes</option>
                    <option value="No" <?=(!empty($dataRow->is_se) && $dataRow->is_se == "No") ? "selected" : ""?>>No</option>
                </select>
            </div>
            
            <div class="col-md-4 form-group">
                <label for="zone_id">Sales Zone</label>
                <select name="zone_id[]" id="zone_id" class="form-control modal-select2" multiple>
                    <option value="">Select Sales Zone</option>
                    <?php
                        foreach($zoneList as $row):
                            $selected = (!empty($dataRow->zone_id) && in_array($row->id,explode(',',$dataRow->zone_id)))?"selected":"";
                            echo '<option value="'.$row->id.'" '.$selected.'>'.$row->zone_name.'</option>';
                        endforeach;
                    ?>
                </select>
            </div>

            <div class="col-md-4 form-group">
                <label for="auth_id">Higher Authority</label>
                <select name="auth_id[]" id="auth_id" class="form-control modal-select2">
                    <option value="">Select Higher Authority</option>
                    <?php
                        foreach($authList as $row):
                            $selected = (!empty($dataRow->auth_id) && in_array($row->id,explode(',',$dataRow->auth_id)))?"selected":"";
                            if($dataRow->id != $row->id):
                                echo '<option value="'.$row->id.'" '.$selected.'>'.$row->emp_name.'</option>';
                            endif;    
                        endforeach;
                    ?>
                </select>
            </div>

            <div class="col-md-4 form-group">
                <label for="quarter_id">Head Quarter</label>
                <select name="quarter_id" id="quarter_id" class="form-control modal-select2 req">
                    <option value="">Select Head Quarter</option>
                    <?php
                        foreach($quarterList as $row):
                            $selected = (!empty($dataRow->quarter_id) && $row->id == $dataRow->quarter_id)?"selected":"";
                            echo '<option value="'.$row->id.'" '.$selected.'>'.$row->name.'</option>';
                        endforeach;
                    ?>
                </select>
            </div>
           
            <div class="col-md-12 form-group">
                <label for="emp_address">Address</label>
                <textarea name="emp_address" class="form-control" style="resize:none;" rows="2"><?=(!empty($dataRow->emp_address))?$dataRow->emp_address:""?></textarea>
            </div>
			
			<div class="col-md-12 form-group">
                <label for="permanent_address">Permanent Address</label>
                <textarea name="empDetails[permanent_address]" class="form-control" style="resize:none;" rows="1"><?=(!empty($dataRow->permanent_address))?$dataRow->permanent_address:""?></textarea>
            </div>
        </div>
    </div>
</form>