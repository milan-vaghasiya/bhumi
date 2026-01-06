<?php $this->load->view('includes/header'); ?>
<div class="page-content-tab">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="page-title-box">
                    <div class="float-end">
						<button type="button" class="btn btn-info btn-sm float-right addNew press-add-btn permission-write" data-button="both" data-modal_id="right_modal_lg" data-function="addSalesData"  data-fnsave="saveSalesData" data-postdata='{"module_type" : 2 }' data-form_title="Add Sales Quotation"><i class="fa fa-plus"></i> Add Sales Quotation</button>
					</div>
					<ul class="nav nav-pills">
						<li><button onclick="statusTab('salesQuotationTable',0);" class="btn btn-outline-info active" data-bs-toggle="tab">Pending</button></li>
						<li><button onclick="statusTab('salesQuotationTable',1);" class="btn btn-outline-info " data-bs-toggle="tab">Approved</button></li>
						<li><button onclick="statusTab('salesQuotationTable',2);" class="btn btn-outline-info" data-bs-toggle="tab">Completed</button></li>
						<li><button onclick="statusTab('salesQuotationTable',3);" class="btn btn-outline-info" data-bs-toggle="tab">Cancelled</button></li>
					</ul>
				</div>
			</div>
		</div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id='salesQuotationTable' class="table table-bordered ssTable" data-url='/getSalesQuotationDTRows'></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('includes/footer'); ?>