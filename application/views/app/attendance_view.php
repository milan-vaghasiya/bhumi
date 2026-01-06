<?php $this->load->view('app/includes/header'); ?>


<header class="header">
	<div class="main-bar bg-primary-2">
		<div class="container">
			<div class="header-content">
				<div class="left-content">
					<a href="javascript:void(0);" class="menu-toggler me-2">
                        <svg class="text-dark" xmlns="http://www.w3.org/2000/svg" height="30px" viewBox="0 0 24 24" width="30px" fill="#000000"><path d="M13 14v6c0 .55.45 1 1 1h6c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1h-6c-.55 0-1 .45-1 1zm-9 7h6c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1zM3 4v6c0 .55.45 1 1 1h6c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1zm12.95-1.6L11.7 6.64c-.39.39-.39 1.02 0 1.41l4.25 4.25c.39.39 1.02.39 1.41 0l4.25-4.25c.39-.39.39-1.02 0-1.41L17.37 2.4c-.39-.39-1.03-.39-1.42 0z"></path></svg>
    				</a>
					<h5 class="title mb-0 text-nowrap">Attendance</h5>
				</div>
				<div class="mid-content"> </div>
				<div class="right-content headerSearch"> </div>
			</div>
		</div>
	</div>
</header>    
<link href="<?=base_url('assets/app/vendor/imageuplodify/imageuploadify.min.css')?>" rel="stylesheet">
<!-- Page Content -->
<div class="page-content">
    <div class="content-inner pt-0">
        <div class="container bottom-content">
            
            <form>
                <?php
				$button = '';
				if(!empty($hqLocation->quarter_id)){

					if(empty($todayPunch)){
						$button = '<a class="btn light btn-primary add-btn permission-write attendanceBtn" data-form_title="IN" data-bs-toggle="offcanvas" data-bs-target="#attendanceModel" data-type="IN" aria-controls="offcanvasBottom" style="width:100%">IN</a>';   
				   
						if(!empty($empData)){              
							if($empData->type == 'IN'){
								$button = '<a class="btn light btn-primary add-btn permission-write attendanceBtn" data-form_title="OUT" data-bs-toggle="offcanvas" data-bs-target="#attendanceModel" data-type="OUT" aria-controls="offcanvasBottom" style="width:100%">OUT</a>';
							}
							elseif($empData->type == 'OUT'){  
								$button = '<a class="btn light btn-primary add-btn permission-write attendanceBtn" data-form_title="IN" data-bs-toggle="offcanvas" data-bs-target="#attendanceModel" data-type="IN" aria-controls="offcanvasBottom" style="width:100%">IN</a>';
							} 
						}
					}
				}else{
                    $button = '<a class="btn light btn-danger" data-bs-toggle="offcanvas" aria-controls="offcanvasBottom" style="width:100%">You will not be able to add attendance without Headquarters.</a>';
                }
                echo $button;
                ?>                
            </form>
            <div class="tab-pane fade active show" role="tabpanel" aria-labelledby="home-tab" tabindex="0" >
                <ul class="dz-list message-list" data-isotope='{ "itemSelector": ".listItem" }'>
                    <?php
                    if(!empty($logData)):
                        foreach($logData as $row):
                            $cls='';
                            if($row->approve_by > 0) { $cls = 'btn-primary'; }
                            else { $cls = 'btn-danger'; }

                            $distance=2;
                            if(!empty($hqLocation->hq_location) && !empty($row->start_location)){
                                $distance = getDistanceOpt($hqLocation->hq_location,$row->start_location);
                            }
							//echo $hqLocation->hq_location;
							//echo $row->start_location;
                            ?> 
                            <li class="grid_item listItem item transition position-static" data-category="transition">
                                <a href="javascript:void(0)" class="position-relative">
                                    <div class="mb-2 me-2 btn btn-rounded btn-icon <?=$cls?>"><i class="fa fa-user"></i></div>
                                    <div class="media-content">
                                        <div>
                                            <h6 class="name"><?=(!empty($row->punch_date)?date('d-m-Y H:i:s',strtotime($row->punch_date)):'')?></h6>                                  
                                            <p class="order-name"><?=(($distance <= 1) ? 'From Head Quarter' : 'Out From Head Quarter')?></p>                                            
                                        </div>
                                    </div>
                                    <div class="left-content">   
                                        <a class="javascript:void(0)"><?=(!empty($row->type)?$row->type:'')?></a>
                                    </div>
                                </a>
                            </li>
                            <?php
                        endforeach;
                    endif;
                    ?>
                </ul>
            </div>
            
        </div>    
    </div>
</div> 

<div class="offcanvas offcanvas-bottom m-3 rounded" tabindex="-1" id="attendanceModel" aria-modal="true" role="dialog">
    <div class="offcanvas-body small">
        <form id="attendanceForm" enctype="multipart/form-data">
            <div class="card">
                <div class="card-body">

                    <input type="hidden" name="id" id="id" value="" />
                    <input type="hidden" name="type" id="type" value="" />
                    <input type="hidden" name="emp_id" id="emp_id" value="" />
                    <input type="hidden" name="s_lat" id="s_lat" value="" />
                    <input type="hidden" name="s_lon" id="s_lon" value="" />
                    <div class="mb-3">
                        <input type="file" name="img_file" id="img_file" class="form-control" accept="image/*" capture="user" />
                    </div>

                    <div class="">
                        <button type="button" class="btn btn-success btn-round btn-outline-dashed btn-block saveAttendance btn-save">Add</button>
                    </div>

                </div>        
            </div>
        </form>
    </div>
</div>

<!-- Page Content End-->
<?php $this->load->view('app/includes/bottom_menu'); ?>
<?php $this->load->view('app/includes/footer'); ?>
<?php $this->load->view('app/includes/sidebar'); ?>

<script src="<?=base_url()?>assets/plugins/isotop/isotope.pkgd.min.js"></script>
<script src="<?=base_url('assets/app/vendor/imageuplodify/imageuploadify.min.js')?>"></script>
<script src="https://maps.google.com/maps/api/js?key=AIzaSyAAzYbgqM1TKIa7psryIXXP07g6FTk_inY"></script>
<script>
$(document).ready(function(){
    setPlaceHolder();
    
    
	var qsRegex;
	var isoOptions = {
		itemSelector: '.listItem',
		percentPosition: true,
		layoutMode: 'fitRows',
		filter: function() {return qsRegex ? $(this).text().match( qsRegex ) : true;}
	};
	$('.listItem').css('position', 'static');
	// init isotope
	var $grid = $('.dz-list').isotope( isoOptions );
	var $qs = $('.quicksearch').keyup( debounce( function() {qsRegex = new RegExp( $qs.val(), 'gi' );$grid.isotope();}, 200 ) );

    $(document).on('click','.saveAttendance',function(){
        $(".btn-save").attr("disabled", true);
		var lat = '';var lon = '';
		if (navigator.geolocation) { 
            navigator.geolocation.getCurrentPosition(function(position){
    			lat = position.coords.latitude;
    			lon = position.coords.longitude;
    			var a = lat+','+lon;
    			$('#s_lat').val(lat);
                $('#s_lon').val(lon);

                var formId = "attendanceForm";
                var form = $('#'+formId)[0];
                var fd = new FormData(form);
                
                $.ajax({
                    url: base_url + controller + '/saveAttendance',
                    data : fd,
                    type: "POST",
                    processData:false,
                    contentType:false,
                    dataType:"json",
                }).done(function(response){
                    $(".btn-save").removeAttr("disabled");
                    if(response.status==1)
                    {
                        $('#'+formId)[0].reset(); $('#attendanceModel').removeClass('show');
                        Swal.fire({ icon: 'success', title: response.message});
                        window.location.reload();
                    }
                    else{
                        if(typeof response.message === "object"){
                            $(".error").html("");
                            $.each( response.message, function( key, value ) {$("."+key).html(value);});
                        }else{
                            Swal.fire({ icon: 'error', title: response.message });
                        }	
                    }
                });
                
    		});
        }
        
    });

    $(document).on('click','.attendanceBtn',function(){
        var emp_id = $(this).data('emp_id');
        var type = $(this).data('type');
        $("#emp_id").val(emp_id);
        $("#type").val(type);
    });
    
    setTimeout(function(){ $('input[type="file"]').imageuploadify(); }, 1000);
});

function searchItems(ele){
	console.log($(ele).val());
}

function debounce( fn, threshold ) {
  var timeout;
  threshold = threshold || 100;
  return function debounced() {
	clearTimeout( timeout );
	var args = arguments;
	var _this = this;
	
	function delayed() {fn.apply( _this, args );}
	timeout = setTimeout( delayed, threshold );
  };
}
</script>