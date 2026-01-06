<?php 
	$this->load->view('includes/header'); 	
	$today = new DateTime();
	$today->modify('first day of this month');$first_day = date('Y-m-d');
	$today->modify('last day of this month');$last_day = date("t",strtotime($today->format('Y-m-d')));
	/*$monthArr = ['Apr-'.$startYear=>'01-04-'.$startYear,'May-'.$startYear=>'01-05-'.$startYear,
	'Jun-'.$startYear=>'01-06-'.$startYear,'Jul-'.$startYear=>'01-07-'.$startYear,'Aug-'.$startYear=>'01-08-'.$startYear,'Sep-'.$startYear=>'01-09-'.$startYear,'Oct-'.$startYear=>'01-10-'.$startYear,'Nov-'.$startYear=>'01-11-'.$startYear,'Dec-'.$startYear=>'01-12-'.$startYear,'Jan-'.$endYear=>'01-01-'.$endYear,'Feb-'.$endYear=>'01-02-'.$endYear,'Mar-'.$endYear=>'01-03-'.$endYear];*/
	
	$yearList = $this->db->get('financial_year')->result();
?>
<div class="page-content-tab">
	<div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-3">
                                <h4 class="card-title">Monthly Attendance</h4>
                            </div>
							<div class="col-md-3">
								<select name="zone_id[]" id="zone_id" class="form-control select2">
									<option value="">Select Sales Zone</option>
									<?php
										foreach($zoneList as $row):
											echo '<option value="'.$row->id.'">'.$row->zone_name.'</option>';
										endforeach;
									?>
								</select>
							</div>
							<div class="col-md-2">
                                <select name="year" id="year" class="form-control select2">
                                    <?php
										$cyKey = array_search(1,array_column($yearList,'is_active'));
										
                                        foreach($yearList as $key=>$row):
											if($cyKey >= $key):
												$selected = (date('Y') == $row->end_year)?"selected":"";
												echo "<option value='".$row->end_year."' ".$selected.">".$row->end_year."</option>";
											endif;
										endforeach;
                                    ?>
                                </select>
							</div>
							<div class="col-md-2">
                                <select name="month" id="month" class="form-control select2">
                                   <option value="01" <?=((date('m') == "01")?"selected":"")?>>January</option>
								   <option value="02" <?=((date('m') == "02")?"selected":"")?>>February</option>
								   <option value="03" <?=((date('m') == "03")?"selected":"")?>>March</option>
								   <option value="04" <?=((date('m') == "04")?"selected":"")?>>April</option>
								   <option value="05" <?=((date('m') == "05")?"selected":"")?>>May</option>
								   <option value="06" <?=((date('m') == "06")?"selected":"")?>>June</option>
								   <option value="07" <?=((date('m') == "07")?"selected":"")?>>July</option>
								   <option value="08" <?=((date('m') == "08")?"selected":"")?>>August</option>
								   <option value="09" <?=((date('m') == "09")?"selected":"")?>>September</option>
								   <option value="10" <?=((date('m') == "10")?"selected":"")?>>October</option>
								   <option value="11" <?=((date('m') == "11")?"selected":"")?>>November</option>
								   <option value="12" <?=((date('m') == "12")?"selected":"")?>>December</option>
                                </select>
							</div>
                            <div class="col-md-2">
								<button type="button" class="btn btn-success loadData" data-type="0" datatip="View Report" flow="down"><i class="fa fa-eye"></i> View</button>
								<button type="button" class="btn btn-success loadData" data-type="excel" datatip="EXCEL" flow="down" target="_blank"><i class="fa fa-file-excel"></i> Excel</button>
                            </div>                     
                        </div>                                         
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id='attendanceTable' class="table table-bordered">
								<thead class="thead-info" id="theadData">
									<tr>
										<th>Emp Code</th>
										<th>Employee</th>
										<th>Dept./Designation</th>
										<th>Sales Zone</th>
										<?php for($d=1;$d<=$last_day;$d++){echo '<th>'.$d.'</th>';} ?>
										<th>Total</th>
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
$(document).ready(function(){
    reportTable();
    $(document).on('click','.loadData',function(e){
        var month = $("#month").val();
		var year = $("#year").val();
        var zone_id = $("#zone_id").val();
		var type = $(this).data('type');
		if(month){
			var sendData = {month:month,year:year,file_type:type,zone_id:zone_id};
			if(type == 'excel'){
				var url =  base_url + controller + '/getMonthlyReport/' + encodeURIComponent(window.btoa(JSON.stringify(sendData)));
				window.open(url,'_blank');
			}else{
				$.ajax({
					url: base_url + controller + '/getMonthlyReport',
					data: sendData,
					type: "POST",
					dataType:'json',
					success:function(data){
						$("#reportTable").DataTable().clear().destroy();
						$("#theadData").html(data.thead);
						$("#tbodyData").html(data.tbody);
						reportTable();
					}
				});
			}
        }
    });   
});
</script>