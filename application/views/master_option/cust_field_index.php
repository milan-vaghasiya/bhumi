<?php $this->load->view('includes/header'); ?>
<div class="page-content-tab">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="page-title-box">
					<div class="float-end">
						<button type="button" id="addbtn" class="btn btn-info btn-sm float-right addNew press-add-btn permission-write" data-button="both" data-modal_id="right_modal" data-postdata='{"type":"1"}' data-function="addCustomField" data-fnsave="saveCustomField" data-type="1" data-form_title="Add Custom Field"><i class="fa fa-plus"></i> Add Field</button>

					</div>
					<ul class="nav nav-pills">
						<li class="nav-item fTb" > 
							<button onclick="statusTab('custmFieldTable','1');" data-type="1"  class="sTab nav-tab btn waves-effect waves-light btn-outline-danger active" style="outline:0px" data-bs-toggle="tab" aria-expanded="false">Item</button> 
						</li>
						<li class="nav-item"> 
							<button onclick="statusTab('custmFieldTable','2');" data-type="2"  class="sTab nav-tab btn waves-effect waves-light btn-outline-success" style="outline:0px" data-bs-toggle="tab" aria-expanded="false">Customer</button> 
						</li>
					</ul>
				</div>
			</div>
		</div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id='custmFieldTable' class="table table-bordered ssTable" data-url='/getCustomFieldDTRows/'></table>
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
	$(document).on('click',".sTab",function(){
		var type = $(this).data('type');
        $('#addbtn').data('postdata','{"type":"'+type+'"}');
	});

	
    
});
</script>
