<?php $this->load->view('includes/header'); ?>
<div class="page-content-tab">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="page-title-box">
					<div class="float-end">
						<button type="button" class="btn btn-info btn-sm float-right addNew press-add-btn permission-write addbtn" data-button="both" data-modal_id="right_modal" data-function="addMeeting/<?=$type?>" data-form_title="Add Event" ><i class="fa fa-plus"></i> Add Event</button>
					</div>
					<ul class="nav nav-pills">
						<li><button onclick="statusTab('eventTable',0);" class="btn btn-outline-info active" data-bs-toggle="tab">Pending</button></li>
						<li><button onclick="statusTab('eventTable',2);" class="btn btn-outline-info" data-bs-toggle="tab">Completed</button></li>
						<li><button onclick="statusTab('eventTable',3);" class="btn btn-outline-info" data-bs-toggle="tab">Cancel</button></li>
					</ul>
				</div>
			</div>
		</div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id='eventTable' class="table table-bordered ssTable" data-url='/getDTRows/<?=$type?>'></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>

<?php $this->load->view('includes/footer'); ?>