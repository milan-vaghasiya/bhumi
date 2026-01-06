<?php $this->load->view('includes/header'); ?>
<div class="page-content-tab">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="page-title-box">
					<div class="float-end">
						<button type="button" class="btn btn-info btn-sm float-right addNew press-add-btn permission-write" data-button="both" data-modal_id="right_modal" data-function="addServiceRequest" data-form_title="Add Service Request"><i class="fa fa-plus"></i> Add Request</button>
					</div>
					<ul class="nav nav-pills">
						<li class="nav-item"> 
							<button onclick="statusTab('serviceRequestTable',0);" class="nav-tab btn waves-effect waves-light btn-outline-danger active" style="outline:0px" data-bs-toggle="tab" aria-expanded="false">Pending</button> 
						</li>
						<li class="nav-item"> 
							<button onclick="statusTab('serviceRequestTable',1);" class="nav-tab btn waves-effect waves-light btn-outline-info" style="outline:0px" data-bs-toggle="tab" aria-expanded="false">Completed</button> 
						</li>
						<!--<li class="nav-item"> -->
						<!--	<button onclick="statusTab('serviceRequestTable',2);" class="nav-tab btn waves-effect waves-light btn-outline-success" style="outline:0px" data-bs-toggle="tab" aria-expanded="false">Completed</button> -->
						<!--</li>-->
						<!--<li class="nav-item"> -->
						<!--	<button onclick="statusTab('serviceRequestTable',3);" class="nav-tab btn waves-effect waves-light btn-outline-dark" style="outline:0px" data-bs-toggle="tab" aria-expanded="false">Short Close</button> -->
						<!--</li>-->
					</ul>
				</div>
			</div>
		</div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id='serviceRequestTable' class="table table-bordered ssTable" data-url='/getDTRows'></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>

<?php $this->load->view('includes/footer'); ?>