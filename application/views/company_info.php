<?php $this->load->view('includes/header'); ?>
<div class="page-content-tab">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="page-title-box">
					<div class="float-end">
						<ol class="breadcrumb">
							<!-- <li class="breadcrumb-item"><a href="#">Kishan Autoparts Pvt. Ltd. </a></li>end nav-item -->
							<!--<li class="breadcrumb-item"><a href="#">Project</a></li>--><!--end nav-item-->
							<!-- <li class="breadcrumb-item active">Company Info</li> -->
						</ol>
					</div>
					<h4 class="page-title">Company Info</h4>
				</div><!--end page-title-box-->
			</div><!--end col-->
		</div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form id="addCompanyInfo" data-res_function="companyInfoRes">
                            <div class="col-md-12">
                                <div class="row">
                                    <input type="hidden" name="id" value="<?= (!empty($dataRow->id)) ? $dataRow->id : ""; ?>" />

                                    <div class="col-md-8 form-group">
                                       <label for="company_name">Company Name</label>
                                       <input type="text" name="company_name" id="company_name" class="form-control req" value="<?= (!empty($dataRow->company_name)) ? $dataRow->company_name : "" ?>">
                                    </div> 

                                    <div class="col-md-4 form-group">
                                       <label for="company_email">Company Email</label>
                                       <input type="text" name="company_email" id="company_email" class="form-control req" value="<?= (!empty($dataRow->company_email)) ? $dataRow->company_email : "" ?>">
                                    </div> 

                                    <div class="col-md-4 form-group">
                                        <label for="company_slogan">Company Slogen</label>
                                        <input name="company_slogan" id="company_slogan" class="form-control" value="<?= (!empty($dataRow->company_slogan)) ? $dataRow->company_slogan : "" ?>">
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="company_contact">Company Contact</label>
                                        <input name="company_contact" id="company_contact" class="form-control req" value="<?= (!empty($dataRow->company_contact)) ? $dataRow->company_contact : "" ?>">
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="company_phone">Company Phone</label>
                                        <input name="company_phone" id="company_phone" class="form-control" value="<?= (!empty($dataRow->company_phone)) ? $dataRow->company_phone : "" ?>">
                                    </div>

                                    <div class="col-md-3 form-group">
                                        <label for="company_country_id">Company Country</label>
                                        <select  id="company_country_id" class="form-control country_list select2 req"  data-state="company_state" data-selected_state="<?=(!empty($dataRow->company_state))?$dataRow->company_state:""?>">
                                            <option value="">Select Country</option>
                                            <?php foreach($countryData as $row):
                                                $selected = (!empty($dataRow->country_id) && $dataRow->country_id == $row->id)?"selected":"";
                                            ?>
                                                <option value="<?=$row->id?>" <?=$selected?>><?=$row->name?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="col-md-3 form-group">
                                        <label for="company_state">Company State</label>
                                        <select  id="company_state" class="form-control state_list select2 req" data-district="company_district" data-selected_district="<?=(!empty($dataRow->company_district))?$dataRow->company_district:""?>" >
                                            <option value="">Select State</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3 form-group">
                                        <label for="company_district">District</label>
                                        <select id="company_district" class="form-control select2 district_list req" data-statutory_id="company_statutory_id" data-selected_statutory_id="<?=(!empty($dataRow->company_statutory_id))?$dataRow->company_statutory_id:""?>">
                                            <option value="">Select District</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="company_statutory_id">Taluka</label>
                                        <select id="company_statutory_id" name="company_statutory_id" class="form-control select2 req" >
                                            <option value="">Select Taluka</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="company_gst_no">Company GST No.</label>
                                        <input name="company_gst_no" id="company_gst_no" class="form-control" value="<?= (!empty($dataRow->company_gst_no)) ? $dataRow->company_gst_no : "" ?>">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="company_pan_no">Company Pan No.</label>
                                        <input name="company_pan_no" id="company_pan_no" class="form-control" value="<?= (!empty($dataRow->company_pan_no)) ? $dataRow->company_pan_no : "" ?>">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="company_pincode">Company Pincode</label>
                                        <input name="company_pincode" id="company_pincode" class="form-control req" value="<?= (!empty($dataRow->company_pincode)) ? $dataRow->company_pincode : "" ?>">
                                    </div>
                                    <div class="col-md-12 form-group">
                                        <label for="company_address">Company Address</label>
                                        <input name="company_address" id="company_address" class="form-control req" value="<?= (!empty($dataRow->company_address)) ? $dataRow->company_address : "" ?>">
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label for="company_logo">Company Logo</label>
                                        <div class="input-group">
                                            <input type="file" class="form-control" name="company_logo">
                                            <?php if (!empty($dataRow->company_logo)) : ?>
                                                <?='<a href="' . base_url('assets/images/' . $dataRow->company_logo) . '" target="_blank">  <i style="padding: 10px; color: blue; " class="fa fa-download form-control"></i></a>'; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="company_letterhead">Company Letterhead</label>
                                        <div class="input-group">
                                            <input type="file" class="form-control" name="company_letterhead">
                                            <?php if (!empty($dataRow->company_letterhead)) : ?>
                                                <?= '<a href="' . base_url('assets/images/' . $dataRow->company_letterhead) . '" target="_blank"><i style="padding: 10px; color: blue;" class="fa fa-download form-control"></i></a>'; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                </div>                                
                            </div>
                        </form>
                    </div>
                    <div class="card-footer">
                        <div class="row">                    
                            <div class="col-md-12">
                                <button type="button" class="btn waves-effect waves-light btn-outline-success btn-save float-right save-form permission-write" onclick="customStore({'formId':'addCompanyInfo','fnsave':'save'});"><i class="fa fa-check"></i> Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('includes/footer'); ?>
<script>
$(document).ready(function(){
    $("#company_country_id").trigger('change');
});

function companyInfoRes(data,formId){
    if(data.status==1){
		Swal.fire({ icon: 'success', title: data.message});
        window.location.reload();
    }else{
        if(typeof data.message === "object"){
            $(".error").html("");
            $.each( data.message, function( key, value ) {$("."+key).html(value);});
        }else{
			Swal.fire({ icon: 'success', title: data.message});
        }			
    }			
}
</script>