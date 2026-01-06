<?php 
	$this->load->view('includes/header'); 	
	$today = new DateTime();
	$today->modify('first day of this month');$first_day = date('Y-m-d');
	$today->modify('last day of this month');$last_day = date("t",strtotime($today->format('Y-m-d')));
	$monthArr = ['Apr-'.$startYear=>'01-04-'.$startYear,'May-'.$startYear=>'01-05-'.$startYear,
	'Jun-'.$startYear=>'01-06-'.$startYear,'Jul-'.$startYear=>'01-07-'.$startYear,'Aug-'.$startYear=>'01-08-'.$startYear,'Sep-'.$startYear=>'01-09-'.$startYear,'Oct-'.$startYear=>'01-10-'.$startYear,'Nov-'.$startYear=>'01-11-'.$startYear,'Dec-'.$startYear=>'01-12-'.$startYear,'Jan-'.$endYear=>'01-01-'.$endYear,'Feb-'.$endYear=>'01-02-'.$endYear,'Mar-'.$endYear=>'01-03-'.$endYear];	
		
?>
<div class="page-content-tab">
	<div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <h6>Expense Register</h6>
                            </div>
							<div class="col-md-4 form-group">
                                <select id="emp_id" name="emp_id" class="form-control select2">
                                    <option value="">Select Employee</option>
                                    <?php foreach($empData as $row){
										$emp_name = (!empty($row->emp_code)) ? '['.$row->emp_code.'] '.$row->emp_name : $row->emp_name;
										echo '<option value="'.$row->id.'">'.$emp_name.'</option>';} ?>
                                </select>
								<div class="error emp_id"></div>
							</div>
							<div class="col-md-2 form-group">
                                <select name="month" id="month" class="form-control select2">
                                    <?php
                                        foreach($monthArr as $key=>$value):
                                            $selected = (date('m') == $value)?"selected":"";
                                            echo '<option value="'.$value.'" '.$selected.'>'.$key.'</option>';
                                        endforeach;
                                    ?>
                                </select>
								<div class="error month"></div>
							</div>
                            <div class="col-md-2 form-group">
								<div class="input-group">
									<button type="button" class="btn waves-effect waves-light btn-dark loaddata mr-10" datatip="View Report" flow="down"><i class="fa fa-eye"></i> View</button>
									<div class="input-group-append">
										<button type="button" class="btn waves-effect waves-light btn-dribbble loaddata float-right" datatip="View Report" flow="down" data-file_type="PDF"><i class="fa fa-print"></i> PDF</button>
									</div>
								</div>
                            </div>                     
                        </div>                                         
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id='attendanceTable' class="table table-bordered">
								<thead class="thead-info" id="theadData">
									<tr>
										<th>Expense Type</th>
										<?php for($d=1;$d<=$last_day;$d++){echo '<th>'.$d.'</th>';} ?>
										<th>Total</th>
									</tr>
								</thead>
								<tbody id="tbodyData"></tbody>
								<tfoot id="tfootData">
									<tr class="thead-info">
										<th class="text-left">Total</th>
										<?php for($d=1;$d<=$last_day;$d++){echo '<th class="text-center">0</th>';} ?>
										<th class="text-center">0</th>
									</tr>
								</tfoot>
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
	var month = $('#month').val();
	var emp_id = $('#emp_id').val();

	if($("#month").val() == ""){$(".month").html("Month is required.");valid=0;}
	if($("#emp_id").val() == ""){$(".emp_id").html("Employee is required.");valid=0;}

	var postData= {month:month, emp_id:emp_id, file_type:file_type, type:3};
	if(valid){
		if(file_type == "")
		{
			$.ajax({
			url: base_url + controller + '/getExpenseRegisterData',
			data: postData,
			type: "POST",
			dataType:'json',
				success:function(data){
					$("#reportTable").DataTable().clear().destroy();
					$("#theadData").html(data.thead);
					$("#tbodyData").html(data.tbody);
					$("#tfootData").html(data.tfoot);
					reportTable();
				}
			});
		}
		else
		{
			var u = window.btoa(JSON.stringify(postData)).replace(/=+$/, "");
			var url = base_url + controller + '/getExpenseRegisterData/' + encodeURIComponent(u);
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