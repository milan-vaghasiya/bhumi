<?php $this->load->view('includes/header'); ?>
<div class="page-content-tab">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="page-title-box">
					<div class="float-end">
						<button type="button" class="btn btn-info btn-sm float-right addNew press-add-btn permission-write addbtn" id="addbtn" data-button="both" data-modal_id="right_modal" data-function="addSelectOption" data-form_title="Add Option" data-postdata='{"type":"3"}'><i class="fa fa-plus"></i> Add Option</button>
					</div>
					<ul class="nav nav-pills">
						<!--<li><button onclick="statusTab('selectOptionTable',1);" data-type="1" class="btn btn-outline-info statusTabChange" data-bs-toggle="tab">Source</button></li>
						<li><button onclick="statusTab('selectOptionTable',2);" data-type="2" class="btn btn-outline-info statusTabChange" data-bs-toggle="tab">Lost Reason</button></li>-->
						<li><button onclick="statusTab('selectOptionTable',3);" data-type="3" class="btn btn-outline-info statusTabChange active" data-bs-toggle="tab">Expense Type</button></li>
						<li><button onclick="statusTab('selectOptionTable',4);" data-type="4" class="btn btn-outline-info statusTabChange" data-bs-toggle="tab">Visit Type</button></li>
						<li><button onclick="statusTab('selectOptionTable',5);" data-type="5" class="btn btn-outline-info statusTabChange" data-bs-toggle="tab">Vehical Type</button></li>
					</ul>
				</div>
			</div>
		</div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id='selectOptionTable' class="table table-bordered ssTable" data-url='/getDTRows'></table>
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
	$(document).on('click',".statusTabChange",function(){
		var type = $(this).data('type');
		$('#addbtn').data('postdata','{"type":"'+type+'"}');
		$('#addbtn').data('form_title','Add '+$(this).text());
	});
});
</script>
