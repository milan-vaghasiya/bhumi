<?php $this->load->view('includes/header'); ?>
<div class="page-content-tab">
	<div class="container-fluid">
		<div class="row">
			<div class="page-title-box">					
				<div class="float-end">
					<button type="button" class="btn btn-info btn-sm float-right addNew press-add-btn permission-write" data-button="both" data-modal_id="right_modal_lg" data-function="addExpense" data-form_title="Add Expense"><i class="fa fa-plus"></i> Add Expense</button>
				</div>
				<ul class="nav nav-pills">
					<li class="nav-item"> 
						<button onclick="statusTab('expenseTable',0);" class="nav-tab btn waves-effect waves-light btn-outline-warning active" style="outline:0px" data-bs-toggle="tab" aria-expanded="false">Pending</button> 
					</li>
					<li class="nav-item"> 
						<button onclick="statusTab('expenseTable',1);" class="nav-tab btn waves-effect waves-light btn-outline-success" style="outline:0px" data-bs-toggle="tab" aria-expanded="false">Approved</button> 
					</li>
					<li class="nav-item"> 
						<button onclick="statusTab('expenseTable',2);" class="nav-tab btn waves-effect waves-light btn-outline-danger" style="outline:0px" data-bs-toggle="tab" aria-expanded="false">Rejected</button> 
					</li>
				</ul>
			</div>
		</div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id='expenseTable' class="table table-bordered ssTable" data-url='/getDTRows'></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>

<?php $this->load->view('includes/footer'); ?>

<script>
$(document).ready(function() {
	
	initBulkApproveButton();	
	$(document).on('click', '.BulkApproveRequest', function() {
		if ($(this).attr('id') == "masterApproveSelect") {
			if ($(this).prop('checked') == true) {
				$(".bulkApprove").show();
				$("input[name='ref_id[]']").prop('checked', true);
			} else {
				$(".bulkApprove").hide();
				$("input[name='ref_id[]']").prop('checked', false);
			}
		} else {
			if ($("input[name='ref_id[]']").not(':checked').length != $("input[name='ref_id[]']").length) {
				$(".bulkApprove").show();
				$("#masterApproveSelect").prop('checked', false);
			} else {
				$(".bulkApprove").hide();
			}

			if ($("input[name='ref_id[]']:checked").length == $("input[name='ref_id[]']").length) {
				$("#masterApproveSelect").prop('checked', true);
				$(".bulkApprove").show();
			}
			else{$("#masterApproveSelect").prop('checked', false);}
		}
	});

	$(document).on('click', '.bulkApprove', function() {
		var ref_id = [];
		$("input[name='ref_id[]']:checked").each(function() {
			ref_id.push(this.value);
		});
		var ids = ref_id.join("~");

		var postData = {'postData' : {ids : ids}, 'fnsave' : 'approveBulkRequest', 'message' : 'Are you sure want to Approve Expense?'};
		confirmStore(postData)
	});

});

function initBulkApproveButton() {
	var bulkApproveBtn = '<button class="btn btn-outline-primary bulkApprove" tabindex="0" aria-controls="expenseTable" type="button"><span>Bulk Approve</span></button>';
	$("#expenseTable_wrapper .dt-buttons").append(bulkApproveBtn);
	$(".bulkApprove").hide();
}
</script>