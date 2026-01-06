<?php $this->load->view('includes/header'); ?>
<link href="<?=base_url()?>assets/plugins/tobii/tobii.min.css" rel="stylesheet" type="text/css" />
<div class="page-content-tab">
	<div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="page-title">Attendance</h4>
                            </div>
							<div class="col-md-3">   
                                <select name="emp_id" id="emp_id" class="form-control select2">
                                    <option value="0">Select Employee</option>
                                    <?php   
										foreach($empList as $row): 
											$row->emp_name = (!empty($row->emp_code)?'['.$row->emp_code.'] '.$row->emp_name:$row->emp_name);
											echo '<option value="'.$row->id.'">'.$row->emp_name.'</option>';
										endforeach; 
                                    ?>
                                </select>
                            </div>  
                            <div class="col-md-3">
                                <div class="input-group">
									<input type="date" id="from_date" name="from_date" class="form-control" value="<?=date("Y-m-d")?>" max=<?=date('Y-m-d')?> >
									<input type="date" id="to_date" name="to_date" class="form-control" value="<?=date("Y-m-d")?>" max=<?=date('Y-m-d')?> >
									<div class="input-group-append">
										<button class="btn btn-info loaddata" type="button">Go!</button>
									</div>
								</div>
								<div class="error reportDate"></div>
                            </div>                       
                        </div>                                         
                    </div>
                    <div class="card-body reportDiv" style="min-height:75vh">
                        <div class="table-responsive">
                            <table id='reportTable' class="table table-bordered jpDataTable colSearch">
								<thead class="thead-info" id="theadData">
									<tr>
										<th>Emp Code</th>
										<th>Emp Name</th>
										<th>Type</th>
										<th>Punch Time</th>
										<th>Location</th>
										<th>Image</th>
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
<script src="<?=base_url()?>assets/plugins/shuffle/shuffle.min.js"></script>
<script src="<?=base_url()?>assets/plugins/tobii/tobii.min.js"></script>
<script>

$(document).ready(function() {
	reportTable();
    $(document).on('click','.loaddata',function(){
		$(".error").html("");
		var valid = 1;
		var emp_id = $("#emp_id").val();
		var from_date = $("#from_date").val();
		var to_date = $("#to_date").val();
		if($("#report_date").val() == ""){$(".reportDate").html("Date is required.");valid=0;}

		if(valid){
			$.ajax({
				url: base_url + controller + '/getDailyAttendance',
				data: {from_date:from_date,to_date:to_date,emp_id:emp_id},
				type: "POST",
				dataType:'json',
				success:function(data){
					$("#reportTable").DataTable().clear().destroy();
					$("#tbodyData").html("");
					$("#tbodyData").html(data.tbody);
					const tobii = new Tobii({
										captions: false,
										zoom: false
									});
					reportTable();
				}
			});
		}
	});  
});

function reportTable()
{
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
		buttons: [ 'pageLength', 'excel', {text: 'Refresh',action: function ( e, dt, node, config ) {$(".loaddata").trigger('click');}}]
	});
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