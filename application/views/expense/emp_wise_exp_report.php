<?php $this->load->view('includes/header'); ?>
<div class="page-content-tab">
	<div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">				
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-5 form-group"><h6>Employee Wise Expense Report</h6></div>
                            <div class="col-md-3 form-group">
                                <select id="emp_id" name="emp_id" class="form-control select2">
                                    <option value="">All Employees</option>
                                    <?php foreach($empData as $row){
										$emp_name = (!empty($row->emp_code)) ? '['.$row->emp_code.'] '.$row->emp_name : $row->emp_name;
										echo '<option value="'.$row->id.'">'.$emp_name.'</option>';} ?>
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <div class="input-group">
                                    <input type="date" name="from_date" id="from_date" class="form-control fyDates" style="width:40%" value="<?=getFyDate("Y-m-d",date('Y-m-01'))?>" />
                                    <div class="error fromDate" ></div>
                                    <input type="date" name="to_date" id="to_date" class="form-control fyDates" style="width:40%" value="<?=getFyDate()?>" />
                                    <div class="input-group-append ml-2">
                                        <button type="button" class="btn waves-effect waves-light btn-success float-right loaddata" title="Load Data">
                                            <i class="fas fa-sync-alt"></i> Load
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="error toDate"></div>
                        </div>                                         
                    </div>
                    <div class="card-body reportDiv" style="min-height:75vh">					
                        <div class="table-responsive">
                            <table id='reportTable' class="table table-bordered jpDataTable colSearch" data-srowposition="2" data-ninput="[0]">
								<thead class="thead-info " id="theadData">
									<tr class="text-center">
										<th style="min-width:25px;">#</th>
										<th style="min-width:25px;">Expense Date</th>
										<th style="min-width:25px;">Expense No.</th>
										<th style="min-width:25px;">Expense Type</th>
										<th style="min-width:100px;">Employee Name</th>
										<th style="min-width:100px;">Amount</th>
										<th style="min-width:100px;">Notes</th>
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
<?php $this->load->view('includes/footer'); ?>
<script>
$(document).ready(function() {
	$(".select2").select2()
	reportTable();
	loadData();
    $(document).on('click','.loaddata',function(){
		var file_type = $(this).data('file_type');
		loadData(file_type);
	}); 
});

function loadData(file_type=''){
	$(".error").html("");
	var valid = 1;
	var from_date = $('#from_date').val();
	var to_date = $('#to_date').val();
	var emp_id = $('#emp_id').val();

	if($("#from_date").val() == ""){$(".fromDate").html("From Date is required.");valid=0;}
	if($("#to_date").val() == ""){$(".toDate").html("To Date is required.");valid=0;}
	if($("#to_date").val() < $("#from_date").val()){$(".toDate").html("Invalid Date.");valid=0;}
	var postData= {from_date:from_date, to_date:to_date,emp_id:emp_id,file_type:file_type};
	if(valid){
		if(file_type == "")
		{
			$.ajax({
			url: base_url + controller + '/getExpenseReportData',
			data: postData,
			type: "POST",
			dataType:'json',
				success:function(data){
					$("#reportTable").DataTable().clear().destroy();
					$("#tbodyData").html("");
					$("#tbodyData").html(data.tbody);
					reportTable();
				}
			});
		}
		else
		{
			var u = window.btoa(JSON.stringify(postData)).replace(/=+$/, "");
			var url = base_url + controller + '/getExpenseReportData/' + encodeURIComponent(u);
			window.open(url);
		}
	}
}

function reportTable(){
	var reportTable = $('#reportTable').DataTable( 
	{
		responsive: true,
		scrollY: '55vh',
        scrollCollapse: true,
		"scrollX": true,
		"scrollCollapse":true,
		//'stateSave':true,
		"autoWidth" : false,
		order:[],
		"columnDefs": 	[
							{ type: 'natural', targets: 0 },
							{ orderable: false, targets: "_all" }, 
							{ className: "text-left", targets: [0,1] }, 
							{ className: "text-center", "targets": "_all" } 
						],
		pageLength:25,
		language: { search: "" },
		lengthMenu: [
            [ 10, 25, 50, 100, -1 ],[ '10 rows', '25 rows', '50 rows', '100 rows', 'Show all' ]
        ],
		dom: "<'row'<'col-sm-7'B><'col-sm-5'f>>" +"<'row'<'col-sm-12't>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
		buttons: [ 'pageLength', 'excel', {text: 'Refresh',action: function ( e, dt, node, config ) {loadData();}}]
	});
	var printBtn = '<button class="btn btn-outline-primary loaddata" data-file_type="PDF" type="button"><span>PDF</span></button>';
    reportTable.buttons().container().append(printBtn);
	reportTable.buttons().container().appendTo( '#reportTable_wrapper toolbar' );
	$('.dataTables_filter .form-control-sm').css("width","97%");
	$('.dataTables_filter .form-control-sm').attr("placeholder","Search.....");
	$('.dataTables_filter').css("text-align","left");
	$('.dataTables_filter label').css("display","block");
	$('.btn-group>.btn:first-child').css("border-top-right-radius","0");
	$('.btn-group>.btn:first-child').css("border-bottom-right-radius","0");
	return reportTable;
}
</script>