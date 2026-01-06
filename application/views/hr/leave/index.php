<?php $this->load->view('includes/header'); ?>
<div class="page-content-tab">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="page-title-box">
					<div class="float-end">
						<!--<button type="button" class="btn btn-info btn-sm float-right addNew press-add-btn permission-write" data-button="both" data-modal_id="right_modal" data-function="addLeave" data-form_title="Add Leave"><i class="fa fa-plus"></i> Add Leave</button>-->
					</div>
					<ul class="nav nav-pills">
						<li class="nav-item">   <!-- Pending -->
							<button onclick="statusTab('leaveTable',1);" class="nav-tab btn waves-effect waves-light btn-outline-danger active" style="outline:0px" data-bs-toggle="tab" aria-expanded="false">Pending</button> 
						</li>
						<li class="nav-item"> <!-- Approved By HOD -->
							<button onclick="statusTab('leaveTable',2);" class="nav-tab btn waves-effect waves-light btn-outline-success" style="outline:0px" data-bs-toggle="tab" aria-expanded="false">Approved</button>
						</li>
						<li class="nav-item"> <!-- Approved By HOD -->
							<button onclick="statusTab('leaveTable',3);" class="nav-tab btn waves-effect waves-light btn-outline-dark" style="outline:0px" data-bs-toggle="tab" aria-expanded="false">Rejected</button>
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
                            <table id='leaveTable' class="table table-bordered ssTable bt-switch1" data-url="/getDTRows"></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>


<?php $this->load->view('includes/footer'); ?>
