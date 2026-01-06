<?php $this->load->view('includes/header'); ?>
<div class="page-content-tab">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="page-title-box">
                    <div class="float-end">
						<button type="button" class="btn btn-info btn-sm float-right addNew press-add-btn permission-write" data-button="both" data-modal_id="right_modal" data-function="addReturnOrder"  data-fnsave="saveReturnOrder" data-form_title="Add Return Order"><i class="fa fa-plus"></i> Add Return Order</button>
					</div>
                    <ul class="nav nav-pills">
						<li class="nav-item"> 
							<button onclick="statusTab('returnOrderTable','0');"  class="nav-tab btn waves-effect waves-light btn-outline-danger active" style="outline:0px" data-bs-toggle="tab" aria-expanded="false">Pending</button> 
						</li>
						<li class="nav-item"> 
							<button onclick="statusTab('returnOrderTable','4');" class="nav-tab btn waves-effect waves-light btn-outline-success" style="outline:0px" data-bs-toggle="tab" aria-expanded="false">Accepted</button> 
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
                            <table id='returnOrderTable' class="table table-bordered ssTable" data-url='/getReturnOrderDTRows'></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('includes/footer'); ?>