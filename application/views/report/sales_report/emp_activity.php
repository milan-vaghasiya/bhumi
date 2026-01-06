<?php $this->load->view('includes/header'); ?>
<div class="page-content-tab">
	<div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row"> 
                            <div class="col-md-6">   
                                <select name="emp_id" id="emp_id" class="form-control select2">
                                    <option value="">Select Employee</option>
                                    <?php   
										foreach($empList as $row): 
											$emp_name = (!empty($row->emp_code)) ? '['.$row->emp_code.'] '.$row->emp_name : $row->emp_name;
											echo '<option value="'.$row->id.'">'.$emp_name.'</option>';
										endforeach; 
                                    ?>
                                </select>
								<div class="error emp_id"></div>
                            </div>     
                            <div class="col-md-6">  
                                <div class="input-group">
                                    <input type="date" name="activity_date" id="activity_date" class="form-control" value="<?=getFyDate()?>" />
                                    <div class="input-group-append ml-2">
                                        <button type="button" class="btn waves-effect waves-light btn-success float-right drawPath" style="padding: 0.38rem 1rem;" title="Load Data">
									        <i class="fas fa-sync-alt"></i> Load
								        </button>
                                    </div>
                                </div>
                                <div class="error activity_date"></div>
                            </div>                  
                        </div>                                         
                    </div>
                    <div class="card-body1 reportDiv" style="padding:0px;background:#f8f9fa;">
						<div class="row" style="margin-left:0px;margin-right:0px;">
							<div class="col-md-8">
								<div class="map-container"><div id="map-layer"></div></div>
							</div>
							<div class="col-md-4 activity-container ">
								<div class="activity bg-white mb-0 activityLog" data-simplebar style="height:70vh;overflow:scroll;"></div>
							</div>
						</div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>
<?php $this->load->view('includes/footer'); ?>
<script async="" defer="" src="https://maps.googleapis.com/maps/api/js?key=<?=$API_KEY?>&amp;callback=initMap"></script>
<script>
	var map;
	var waypoints;
	var directionsService;
	var directionsDisplay;
	$(document).ready(function(){
		$(".drawPath").on("click",function() {
				$(".error").html("");
				var valid = 1;
				var activity_date = $('#activity_date').val();
				var emp_id = $('#emp_id').val();
				if($("#activity_date").val() == ""){$(".activity_date").html("Date is required.");valid=0;}
				if($("#emp_id").val() == ""){$(".emp_id").html("Employee is required.");valid=0;}
				if(valid)
				{
					$.ajax({
						url: base_url + controller + '/getEmpActivity',
						data: {activity_date:activity_date, emp_id:emp_id},
						type: "POST",
						dataType:'json',
						success:function(res){
							if(res.status == 1)
							{
								resetMap();
								activityLog = res.activityLog;
								$('.activityLog').html(activityLog);
								var locationLog = res.locationLog;
								initMap(locationLog);
							}
						}
					});
				}
		});
	});
	
	function initMap(locationLog = {}) {
		var mapLayer = document.getElementById("map-layer"); 
		var centerCoordinates = new google.maps.LatLng(28.6139, 77.2090);
		var defaultOptions = { center: centerCoordinates, zoom: 8 }
		map = new google.maps.Map(mapLayer, defaultOptions);

		var directionsService = new google.maps.DirectionsService;
		var directionsDisplay = new google.maps.DirectionsRenderer;
		directionsDisplay.setMap(map);

		if(locationLog.length > 0){
			var start = locationLog[0];
			var end = locationLog[(locationLog.length)-1];
			drawPath(directionsService, directionsDisplay,start,end,waypoints);
		}
	}
	
	function drawPath(directionsService, directionsDisplay,start,end,waypoints) {
		directionsService.route({
		  origin: start,
		  destination: end,
		  waypoints: waypoints,
		  optimizeWaypoints: true,
		  travelMode: google.maps.TravelMode.DRIVING,
		}).then((response) => {
			directionsDisplay.setDirections(response);
		});
	}
	
	function resetMap(){
		if(directionsDisplay){
			directionsDisplay.setDirections({ routes: [] });  // Clear any routes
		}
		if(map){
			var mapLayer = document.getElementById("map-layer"); 
			var centerCoordinates = new google.maps.LatLng(28.6139, 77.2090);
			var defaultOptions = { center: centerCoordinates, zoom: 8 }
			map = new google.maps.Map(mapLayer, defaultOptions);

			var directionsService = new google.maps.DirectionsService;
			var directionsDisplay = new google.maps.DirectionsRenderer;
			directionsDisplay.setMap(map);
		}
	}
</script>