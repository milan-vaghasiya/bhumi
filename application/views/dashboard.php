<?php
    $this->load->view('includes/header');
    
    $distance = '';//codexworldGetDistanceOpt("22.470701,70.057732", "22.308155,70.800705", "M") . " Km<br>";
?>
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/plugins/jp-noticebar/nb-style.css">
	<style>
		.d-none{display: none;}
		.d-block {display: block;}
		#from_date,#to_date{padding:0px 0.5rem;background:#c3e1e4;border-color:#90bdc2;}
		.LAN .select2-container--default .select2-selection--single .select2-selection__rendered{line-height:32px!important;}.LAN .select2-container .select2-selection--single{height:32px!important;border-color:#90bdc2;}
		.LAN .select2-container--default .select2-selection--single {border-color:#90bdc2;background:transparent;}
	</style>
	<div class="page-content-tab">
		<div class="container-fluid" style="padding:0px 10px;">
			<div class="row">
				<div class="col-md-12 mb-2"><div id="announcement"></div></div>
				<div class="col-md-6 col-lg-3 TDO d-none">
					<div class="row justify-content-left" >
						<div class="col-lg-12 WLD d-none">
							<div class="card overflow-hidden">
								<div class="card-body">
									<div class="row d-flex">
										<div class="col-6">
											<h3 class="text-dark my-0 font-25 fw-bold"><?=!empty($empList) ? count($empList) : '0'?></h3>
										</div>
										<div class="col-6 text-right">
											<i class="fas fa-users font-30 align-self-center text-dark"></i>
										</div>
									
										<div class="col-12 ms-auto align-self-center">
											<div id="dash_spark_2" class="mb-3"></div>
										</div>
										<div class="col-12 ms-auto align-self-center">
											<p class="text-muted mb-0 font-18 fw-semibold">Total Employee</p>
										</div>
									</div>
								</div>
							</div>                               
						</div> 
						<div class="col-lg-12 WLD d-none">
							<div class="card overflow-hidden">
								<div class="card-body">
									<div class="row d-flex">
										<div class="col-6">
											<h3 class="text-dark my-0 font-25 fw-bold"><?=!empty($totalPresent) ? count($totalPresent) : '0'?></h3>
										</div>
										<div class="col-6 text-right">
											<i class="fas fa-user-check font-30 align-self-center text-dark"></i>
										</div>
									
										<div class="col-12 ms-auto align-self-center">
											<div id="dash_spark_2" class="mb-3"></div>
										</div>
										<div class="col-12 ms-auto align-self-center">
											<p class="text-muted mb-0 font-18 fw-semibold">Total Present</p>
										</div>
									</div>
								</div>
							</div>                               
						</div>  
						<div class="col-lg-6 LLD d-none">
							<div class="card overflow-hidden">
								<div class="card-body">
									<div class="row d-flex">
										<div class="col-3">
											<i class="fas fa-user-times font-30 align-self-center text-dark"></i>
										</div>
										<div class="col-12 ms-auto align-self-center">
											<div id="dash_spark_3" class="mb-3"></div>
										</div>
										<div class="col-12 ms-auto align-self-center">
											<h3 class="text-dark my-0 font-25 fw-bold"><?=(!empty($empList)  ? (count($empList) - count($totalPresent)) : 0)?></h3>
											<p class="text-muted mb-0 font-18 fw-semibold">Total Absent</p>
										</div>
									</div>
								</div>
							</div>                               
						</div>  
						<div class="col-lg-6">
							<div class="card overflow-hidden">
								<div class="card-body">
									<div class="row d-flex">
										<div class="col-3">
											<i class="fas fa-user-times font-30 align-self-center text-dark"></i>
										</div>
										
										<div class="col-12 ms-auto align-self-center">
											<div id="dash_spark_4" class="mb-3"></div>
										</div>
										<div class="col-12 ms-auto align-self-center">
											<h3 class="text-dark my-0 font-25 fw-bold"><?=(!empty($totalLeave) ? count($totalLeave) : 0)?></h3>
											<p class="text-muted mb-0 font-18 fw-semibold">Total Leave</p>
										</div>
									</div>
								</div>
							</div>                               
						</div>
					</div>
				</div>

				<div class="col-md-6 col-lg-9">
					<div class="row LDO d-none" >
						<div class="col-12" >       
							<div class="card" style="height: 350px;">
								<div class="card-header">
									<div class="row align-items-center">
										<div class="col"><h4 class="card-title">Overview</h4> </div>
									</div>
								</div>
								<div class="card-body">
									<div class="text-center">
										<div class="chart-container">
											<div id="enqChart" class="apex-charts"></div>
										</div>
									</div>                                     
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-lg-12">
					<div class="row justify-content-left" > 
						<div class="col-lg-4">
							<div class="bg-white">
								<h4 class="jp-list-title m-0 d-flex justify-content-between align-items-center">
									Pending Leave List
									<a href="<?=base_url('hr/leave')?>" target="_blank"><span class="badge bg-soft-primary badge-pill">View All</span></a>
									<!--<span class="badge bg-soft-primary badge-pill"><?=(!empty($pLeaveList) ? count($pLeaveList) : 0)?></span>-->
								</h4> 
								<div class="jp-list-body" data-simplebar style="height: 250px;">
									<div>
										<?php
										if(!empty($pLeaveList)){
											foreach($pLeaveList as $row){
												echo '
												<div class="jp-list-item py-3">
													<small class="float-end text-muted ps-2">
														<p class="mt-1 mb-0 fs-13">'.(!empty($row->total_days) ? $row->total_days : '').'</p>
													</small>
													<div class="media">
														<div class="avatar-md bg-soft-primary">
															<i class="fas fa-user"></i>
														</div>
														<div class="media-body align-self-center ms-2 text-truncate">
															<span text-dark"> '.(!empty($row->emp_name) ? $row->emp_name : '').'</span>	<br>
															<span class="text-muted fs-13"><i class="far fa-fw fa-clock"></i>'.(!empty($row->start_date) ? formatDate($row->start_date) : '').' To '.(!empty($row->end_date) ? formatDate($row->end_date) : '').'</span>
														</div>	
													</div>
												</div>';
											}
										}
										?>
									</div>
								</div>
							</div>                               
						</div>    
						<div class="col-lg-4">
							<div class="bg-white">
								<h4 class="jp-list-title m-0 d-flex justify-content-between align-items-center">
									Pending Attendance List
									<a href="<?=base_url('hr/attendance/attendanceIndex')?>" target="_blank"><span class="badge bg-soft-primary badge-pill">View All</span></a>
									<!--<span class="badge bg-soft-primary badge-pill"><?=(!empty($pAtdList) ? count($pAtdList) : 0)?></span>-->
								</h4> 
								<div class="jp-list-body" data-simplebar style="height: 250px;">
									<div>
										<?php
										if(!empty($pAtdList)){
											foreach($pAtdList as $row){
												echo '
												<div class="jp-list-item py-3">
													<small class="float-end text-muted ps-2">
														<p class="mt-1 mb-0 fs-13">'.(!empty($row->type) ? $row->type : '').'</p>
													</small>
													<div class="media">
														<div class="avatar-md bg-soft-primary">
															<i class="fas fa-user"></i>
														</div>
														<div class="media-body align-self-center ms-2 text-truncate">
															<span text-dark"> '.(!empty($row->emp_name) ? $row->emp_name : '').'</span>	<br>
															<span class="text-muted fs-13"><i class="far fa-fw fa-clock"></i>'.date('j M Y H:i A',strtotime($row->punch_date)).'</span>
														</div>	
													</div>
												</div>';
											}
										}
										?>
									</div>
								</div>
							</div>                            
						</div>  
						<div class="col-lg-4">
							<div class="bg-white">
								<h4 class="jp-list-title m-0 d-flex justify-content-between align-items-center">
									Pending Expense List
									<a href="<?=base_url('expense')?>" target="_blank"><span class="badge bg-soft-primary badge-pill">View All</span></a>
									<!--<span class="badge bg-soft-primary badge-pill"><?=(!empty($pExpList) ? count($pExpList) : 0)?></span>-->
								</h4> 
								<div class="jp-list-body" data-simplebar style="height: 250px;">
									<div>
										<?php
										if(!empty($pExpList)){
											foreach($pExpList as $row){
												echo '
												<div class="jp-list-item py-3">
													<small class="float-end text-muted ps-2">
														<p class="mt-1 mb-0 fs-13">'.(!empty($row->demand_amount) ? $row->demand_amount : '').'</p>
													</small>
													<div class="media">
														<div class="avatar-md bg-soft-primary">
															<i class="fas fa-user"></i>
														</div>
														<div class="media-body align-self-center ms-2 text-truncate">
															<span text-dark"> '.(!empty($row->exp_number) ? $row->exp_number : '').'</span>	<br>
															<span text-dark"> '.(!empty($row->emp_name) ? $row->emp_name : '').'</span>	<br>
															<span class="text-muted fs-13"><i class="far fa-fw fa-clock"></i> '.date('j M Y H:i A',strtotime($row->exp_date)).'</span>
														</div>	
													</div>
												</div>';
											}
										}
										?>
									</div>
								</div>
							</div>                           
						</div>                                                    
					</div>
				</div>

		</div>
	</div>

<?php $this->load->view('includes/footer'); ?>

<!-- Javascript  -->   
<script src="<?=base_url()?>assets/plugins/apexcharts/apexcharts.min.js"></script>
<!--<script src="<?=base_url()?>assets/plugins/newsticker/breaking-news-ticker.min.js"></script>-->
<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js"></script>
<script src="<?=base_url()?>assets/plugins/jp-noticebar/jquery.easing.js"></script>
<script src="<?=base_url()?>assets/plugins/jp-noticebar/nb-script.min.js"></script>
<script>
	<?php $totalEmployee = count($empList); ?>
	$(document).ready(function() {
		var wp = '<?=(!empty($widgetPermission) ? implode(",",$widgetPermission) : '')?>';
		var wpArr = wp.split(',');
	
		$.each(wpArr , function(index, val) {
		    $('.'+val).removeClass('d-none');
		    $('.'+val).addClass('d-block');
        });
		//$('#rtdNews').breakingNews();
		loadAnnouncements();
		
		var present = <?=json_encode($pChartData)?>;
		var date = new Date();
		var year = date.getFullYear();
		var month = date.getMonth(); // January is 0
		var today = date.getDate(); // Current day
		var daysArray = []; var presentEmp = [];var absentEmp = [];
		for (let i = 1; i <= today; i++) {
			daysArray.push(i);
			presentEmp.push(present[i-1].present);
			var absent = parseFloat(<?=$totalEmployee?>) - parseFloat(present[i-1].present);
			absentEmp.push(absent);
		}
		var chartOptions = {
			chart: {
				height: 270,
				type: 'bar',
				toolbar: {
					show: false
				},
			},
			plotOptions: {
				bar: {
					horizontal: false,
					endingShape: 'rounded',
					columnWidth: '50%',
				},
			},
			dataLabels: {
				enabled: false
			},
			stroke: {
				show: true,
				width: 2,
				colors: ['transparent']
			},
			colors: ['#2a76f4', "rgba(42, 118, 244, .18)", "rgba(251, 182, 36, .6)"],
			series: [ {
				name: 'Total Present',
				data: presentEmp
			},{
				name: 'Total Absent',
				data: absentEmp
			}],
			xaxis: {
				categories:daysArray,
				axisBorder: {
					show: true,
					color: '#bec7e0',
				},  
				axisTicks: {
					show: true,
					color: '#bec7e0',
				},    
			},
			legend: {
				offsetY: 6,
			},
			yaxis: {
				title: {
					text: 'No Of Employees'
				}
			},
			fill: {
				opacity: 1

			},
				// legend: {
				//     floating: true
				// },
				grid: {
					row: {
						colors: ['transparent', 'transparent'], // takes an array which will be repeated on columns
						opacity: 0.2
					},
					borderColor: '#f1f3fa'
				},
				tooltip: {
					y: {
						formatter: function (val) {
							return val + " Employee"
						}
					}
				}
			}
			var chart = new ApexCharts(
				document.querySelector("#enqChart"),
				chartOptions
			);
			chart.render();
		/********************************************************* */
		
		
	});
function loadAnnouncements(){
	$.ajax({
		url : base_url + controller + '/loadAnnouncements',
		type : 'post',
		data : {},
		global:false,
		dataType : 'json'
	}).done(function(response){
		$("#announcement").easyNewsTicker({
			"animation": {
			  "effect": "scroll",
			},
			"data": response.announcement,
			label: {
                padding: "15px",
                fontFamily: "Roboto",
                fontSize: 22,
                fontWeight: "700",
                background: "#4B7727",
                color: "#FFF",
                text: "ANNOUNCEMENT"
            },
			news: { background: "#C4C4C7" }
		});
	});
}
</script>