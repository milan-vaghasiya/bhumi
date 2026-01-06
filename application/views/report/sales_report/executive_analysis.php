<?php $this->load->view('includes/header'); ?>
<div class="page-content-tab">
	<div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
				<div class="page-title-box">
					<div class="col-md-12" style="width:100%;">
					    <div class="input-group">
                            <div class="input-group-append" style="width:15%;">
                                <label for="business_type">Business Type</label>
                                <select id="business_type" class="form-control select2">
                                    <option value="ALL">All Business Type</option>
                                    <?php
                                        foreach($bTypeList as $row):
                                            echo '<option value="'.$row->type_name.'" >'.$row->type_name.'</option>';
                                        endforeach;
                                    ?>
                                </select>
                            </div>
                            <div class="input-group-append" style="width:15%;">
                                <label for="state">Select State</label>
                                <select id="state" class="form-control select2 state_list" data-district="district">
                                    <option value="ALL">All State</option>
                                    <?php
                                    if(!empty($stateList)){
                                        foreach($stateList as $row){
                                            ?>
                                            <option value="<?=$row->state?>"><?=$row->state?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="input-group-append" style="width:15%;">
                                <label for="district">Select District</label>
                                <select id="district" class="form-control select2 district_list" data-statutory_id="statutory_id">
                                </select>
                            </div>  
                            <div class="input-group-append" style="width:15%;">
                                <label for="statutory_id">Select statutory_id</label>
                                <select id="statutory_id" class="form-control select2">
                                </select>
                            </div>
                            <div class="input-group-append" style="width:15%;">
                                <label for="from_date">From Date</label>
                                <input type="date" id="from_date" class="form-control" value="<?=$startDate?>" /> 
                            </div>   
                            <div class="input-group-append" style="width:15%;">   
                                <label for="to_date">To Date</label>                             
                                <input type="date" id="to_date" class="form-control" value="<?=$endDate?>" />
                            </div>  
                            <div class="input-group-append mt-4" style="width:10%;">   
                                <button type="button" class="btn waves-effect waves-light btn-success float-right loadData" data-type="0" style="width:100%;" title="Load Data">
                                    <i class="fas fa-sync-alt"></i> Load
                                </button>
                            </div>  
                            <div class="error fromDate"></div>
                            <div class="error toDate"></div>                     
                        </div>                       
					</div>
				</div>
            </div>
		</div>
        <div class="row">
            <div class="col-12">
				<div class="col-12">
					<div class="card">
                        <div class="card-body reportDiv" style="min-height:75vh">
                            <div class="table-responsive">
                                <table id='reportTable' class="table table-bordered">
                                    <thead class="thead-info">
                                        <tr>
                                            <th>Executive</th>
                                            <th>Visit</th>
                                            <th>New Lead</th>
                                            <th>Sales Enquiry</th>
                                            <th>Sales Order</th>
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
</div>
<?php $this->load->view('includes/footer'); ?>
<script>
$(document).ready(function(){

	reportTable();
    // setTimeout(function(){ $(".loadData").trigger('click'); },500);
    
    $(document).on('click','.loadData',function(e){
		var type = $(this).data('type');
		$(".error").html("");
		var valid = 1;
		var business_type = $('#business_type').val();
		var state = $('#state').val();
		var district = $('#district').val();
		var statutory_id = $('#statutory_id').val();
        var from_date = $('#from_date').val();
	    var to_date = $('#to_date').val();
        if($("#from_date").val() == ""){$(".fromDate").html("From Date is required.");valid=0;}
	    if($("#to_date").val() == ""){$(".toDate").html("To Date is required.");valid=0;}
	    if($("#to_date").val() < $("#from_date").val()){$(".toDate").html("Invalid Date.");valid=0;}

        var sendData = {business_type:business_type, state:state, district:district, statutory_id:statutory_id, from_date:from_date, to_date:to_date, type:type};

		if(valid){
            if(type == 1){
				var url =  base_url + controller + '/getExecutiveAnalysisData/' + encodeURIComponent(window.btoa(JSON.stringify(sendData)));
				window.open(url);
				
			}else{
                $.ajax({
                    url: base_url + controller + '/getExecutiveAnalysisData',
                    data: sendData,
                    type: "POST",
                    dataType:'json',
                    success:function(data){
                        $("#reportTable").DataTable().clear().destroy();
                        $("#tbodyData").html(data.tbody);
                        reportTable();
                    }
                });
            }
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
		buttons: [ 'pageLength', 'excel', {text: 'Refresh',action: function ( e, dt, node, config ) {}}]
	});
	var printBtn = '<button class="btn btn-outline-primary loadData" data-type="1" type="button"><span>PDF</span></button>';
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