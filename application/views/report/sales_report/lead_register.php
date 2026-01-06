<?php $this->load->view('includes/header'); ?>
<div class="page-content-tab">
	<div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
				<div class="page-title-box">
					<div class="float-end" style="width:100%;">
					    <div class="input-group">
                        <div class="form-group col-md-2">
                            <label for="executive_id">Sales Executives</label>
                            <select name="executive_id" id="executive_id" class="form-control select2">
                                <option value="">Select Sales Executive</option>
                                <option value="ALL">All</option>
                                <?php
                                    if(!empty($salesExecutives)){
                                        foreach($salesExecutives as $row){
                                            echo '<option value="'.$row->id.'">'.$row->emp_name.'</option>';
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="business_type">Business Type</label>
                            <select name="business_type" id="business_type" class="form-control select2">
                                <option value="">Select Business Type</option>
                                <option value="ALL">All</option>
                                <?php
                                    foreach($bTypeList as $row):
                                        echo '<option value="'.$row->type_name.'" >'.$row->type_name.'</option>';
                                    endforeach;
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="party_type">Status</label>
                            <select name="party_type" id="party_type" class="form-control select2">
                                <?php
                                    foreach($leadStages as $row):
                                        echo '<option value="'.$row->id.'" >'.$row->stage_type.'</option>';
                                    endforeach;
                                ?>
                            </select>
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="state_id">Select State</label>
                            <select  id="state_id" class="form-control  select2 req state_list" data-district="district" data-selected_district="<?=(!empty($dataRow->district))?$dataRow->district:""?>" >
                                <option value="">Select State</option>
                                <option value="ALL">All</option>
                                <?php
                                if(!empty($stateList)){
                                    foreach($stateList as $row){
                                        $selected = (!empty($dataRow->state) && $dataRow->state == $row->state)?'selected':'';
                                        ?>
                                        <option value="<?=$row->state?>" <?=$selected?>><?=$row->state?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="district">Select District</label>
                            <select  id="district" class="form-control select2 req district_list" data-statutory_id="statutory_id" data-selected_statutory_id="<?=(!empty($dataRow->statutory_id))?$dataRow->statutory_id:""?>">
                            </select>
                        </div>  
                        <div class="col-md-2 form-group">
                            <label for="statutory_id">Select Taluka</label>
                            <select name="statutory_id" id="statutory_id" class="form-control select2 req" >
                            </select>
                        </div>
                        <div class="col-md-12 form-group">
                            <button type="button" class="btn waves-effect waves-light btn-success float-end  loadData" title="Load Data" >
                                <i class="fas fa-sync-alt"></i> Load
                            </button>
                        </div>
                        
					</div>
				</div>
            </div>
		</div>
        <div class="row">
            <div class="col-12">
				<div class="col-12">
					<div class="card">
                        <div class="card-body reportDiv" style="min-height:75vh">
                            <div class="table-responsive">
                                <table id='reportTable' class="table table-bordered">
                                    <thead id="theadData" class="thead-info">
                                        <tr>
                                            <th>#</th>
                                            <th>Party Name</th>
                                            <th>Sales Executive</th>
                                            <th>Contact Person</th>
                                            <th>Contact No</th>
                                            <th>Business Type</th>
                                            <th>District</th>
                                            <th>Taluka</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyData"></tbody>
                                    
                                </table>
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
          
	reportTable();
    setTimeout(function(){
        $(".loadData").trigger('click');
        $("#statutory_id").select2({ width: '50%' });
    },500);
    
    $(document).on('click','.loadData',function(e){
		$(".error").html("");
		var valid = 1;
        var executive_id = $("#executive_id").val();
		var state = $('#state_id').val();
		var district = $('#district').val();
		var statutory_id = $('#statutory_id').val();
		var party_type = $('#party_type').val();
		var business_type = $('#business_type').val();
		if(valid){
            $.ajax({
                url: base_url + controller + '/getLeadRegister',
                data: {executive_id:executive_id, state:state, district:district, statutory_id:statutory_id, party_type:party_type, business_type:business_type},
				type: "POST",
				dataType:'json',
				success:function(data){
                    $("#reportTable").DataTable().clear().destroy();
					$("#theadData").html(data.thead);
					$("#tbodyData").html(data.tbody);
					reportTable();
                }
            });
        }
    });   
});
</script>