<?php $this->load->view('app/includes/header'); ?>
<!-- Header -->
<header class="header">
	<div class="main-bar bg-primary-2">
		<div class="container">
			<div class="header-content">
				<div class="left-content">
					<a href="javascript:void(0);" class="menu-toggler me-2">
                        <svg class="text-dark" xmlns="http://www.w3.org/2000/svg" height="30px" viewBox="0 0 24 24" width="30px" fill="#000000"><path d="M13 14v6c0 .55.45 1 1 1h6c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1h-6c-.55 0-1 .45-1 1zm-9 7h6c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1zM3 4v6c0 .55.45 1 1 1h6c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1zm12.95-1.6L11.7 6.64c-.39.39-.39 1.02 0 1.41l4.25 4.25c.39.39 1.02.39 1.41 0l4.25-4.25c.39-.39.39-1.02 0-1.41L17.37 2.4c-.39-.39-1.03-.39-1.42 0z"></path></svg>
    				</a>
					<h5 class="title mb-0 text-nowrap">Visit</h5>
				</div>
				<div class="mid-content"> </div>
				<div class="right-content headerSearch">
					<div class="jpsearch" id="qs1">
						<input type="text" class="input quicksearch qs1" placeholder="Search Here ..." />
						<button class="search-btn"><i class="fas fa-search"></i></button>
					</div>
				</div>
			</div>
		</div>
	</div>
</header>
<!-- Header -->
<!-- Page Content -->
<div class="page-content">
    <div class="content-inner pt-0">
        <div class="container bottom-content">
            <div class="dz-tab style-4">
            <div class="tab-slide-effect">
                <ul class="nav nav-tabs"  role="tablist" >
                    <li class="tab-active-indicator " style="width: 108.391px; transform: translateX(177.625px);"></li>
                    <li class="nav-item   active" role="presentation">
                        <button class="nav-link buttonFilter active" id="home-tab" data-status="1"  data-filter=".pending" data-bs-toggle="tab" data-bs-target="#reqList" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="false" tabindex="-1">Pending</button>
                    </li>
                    <li class="nav-item " role="presentation">
                        <button class="nav-link buttonFilter" id="profile-tab" data-status="2" data-bs-toggle="tab" data-filter=".issued" data-bs-target="#reqList" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false" tabindex="-1">Completed</button>
                    </li>
                    
                </ul>
            </div>  

            <div class="tab-pane fade active show list-grid" role="tabpanel" aria-labelledby="home-tab" tabindex="0" >
                <ul class="dz-list message-list" data-isotope='{ "itemSelector": ".listItem" }'>
                    <?php
                     echo  $visitHtml;
                    ?>
                </ul>
            </div>
            <?php 
             if(empty($visitHtml)):
            ?>
            <div class="review-box" >
                <a type="button" class="add-btn permission-write btn-remind dropdown-item"  data-form_title="Add Visit" datatip="Add Visit" data-bs-toggle="offcanvas" data-bs-target="#visitModel" aria-controls="offcanvasBottom" style="margin-bottom:80px"><i class="fa-solid fa-plus"></i></a>
            </div> 
            <?php
            endif;
            ?>
            
        </div>    
    </div>
</div> 

<div class="offcanvas offcanvas-bottom m-3 rounded" tabindex="-1" id="visitModel" aria-modal="true" role="dialog">
    <div class="offcanvas-body small">
        <form id="visitForm">
            <div class="card">
                <div class="card-body">
                    <input type="hidden" name="id" id="id" value="" />
                    <input type="hidden" name="s_lat" id="s_lat" value="" />
                    <input type="hidden" name="s_lon" id="s_lon" value="" />
                    <input type="hidden" name="emp_id" id="emp_id" value="" /> 
                    <div class="mb-3 partyList">
                        <label for="party_name" class="form-label">Party</label>
                        <input type="text" name="party_name" id="party_name" class="form-control req" value="" />
                    </div>
                    <div class="mb-3">
                        <label for="contact_person" class="form-label">Contact Person</label>
                        <input type="text" name="contact_person" id="contact_person" class="form-control req" value="" />
                    </div>
					<div class="mb-3">
                        <label for="visit_type" class="form-label">Visit Type</label>
						<select name="visit_type" id="visit_type" class="form-control select2 req">
							<option value="">Select Visit Type</option>
							<?php
								foreach($visitTypeList as $row){
									echo '<option value="'.$row->label.'">'.$row->label.'</option>';
								}
							?>
						</select>
                    </div>
                    <!--<div class="mb-3">
                        <input type="file" name="img_file" id="img_file" class="form-control" accept="image/*" capture="user" />
                    </div>-->
                    <div class="mb-3">
                        <label for="purpose" class="form-label">Purpose</label>
                        <textarea name="purpose" id="purpose" class="form-control req" rows="2"></textarea>
                    </div>
                    <div class="">
                        <button type="button" class="btn btn-success btn-round btn-outline-dashed btn-block saveVisit btn-save" >Add Visit</button>
                    </div>
                </div>        
            </div>
        </form>
    </div>
</div>

<div class="offcanvas offcanvas-bottom m-3 rounded" tabindex="-1" id="endModel" aria-modal="true" role="dialog">
    <div class="offcanvas-body small">
        <form id="endVisit">
            <div class="card">
                <div class="card-body">
                    <input type="hidden" name="main_id" id="main_id" value="" />
                    <input type="hidden" name="e_lat" id="e_lat" value="" />
                    <input type="hidden" name="e_lon" id="e_lon" value="" />
                    <div class="mb-3">
                        <label for="discussion_points" class="form-label">Discussion Points</label>
                        <textarea name="discussion_points" id="discussion_points" class="form-control req" rows="2"></textarea>
                    </div>
                    
                    <div class="mb-3 stageDiv" style="display:none">
                        <label for="party_type">Lead Stage</label>
                        <select name="party_type" id="party_type" class="form-control">
                            <option value="">Select Stage</option>
                            <?php
                            if(!empty($leadStages)){
                                foreach($leadStages as $row){
                                    ?>
                                    <option value="<?=$row->id?>"><?=$row->stage_type?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3 stageDiv">
                        <label for="next_visit">Next Visit ?</label>
                        <select name="next_visit" id="next_visit" class="form-control">
                            <option value="No">No</option>
                            <option value="Yes">Yes</option>
                        </select>
                    </div>
                    <div class="row reminderDiv">
                        <div class="col">
                            <div class="mb-3">
                                <label for="reminder_date">Date</label>
                                <input type="date" name="reminder_date" id="reminder_date" class="form-control" min="<?=date("Y-m-d")?>">
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <label for="reminder_time">Time</label>
                                <input type="time" name="reminder_time" id="reminder_time" class="form-control" min="">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="reminder_note" class="form-label">Notes</label>
                            <textarea name="reminder_note" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="">
                        <button type="button" class="btn btn-success btn-round btn-outline-dashed btn-block saveEndVisit btn-save" >End Visit</button>
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
<script src="https://maps.google.com/maps/api/js?key=AIzaSyAAzYbgqM1TKIa7psryIXXP07g6FTk_inY"></script>
<script>
var qsRegex;
var isoOptions ={};
var $grid = '';
$(document).ready(function(){
    $('.select2').each(function() { 
        $(this).select2({ dropdownParent: $(this).parent()});
    })
    initISOTOP();
	var $qs = $('.quicksearch').keyup( debounce( function() {qsRegex = new RegExp( $qs.val(), 'gi' );$grid.isotope();}, 200 ) );
    $(document).on( 'click', '.buttonFilter', function() {
		var status = $(this).data('status');
        $(".message-list").html("");	
        $.ajax({
            url: base_url  + 'app/visit/getVisitData',
            data:{'visit_status':status},
            type: "POST",
            dataType:"json",
        }).done(function(response){
                $('.list-grid').isotope('destroy');
                $(".message-list").html(response.html);	
                initISOTOP();
                                
            });
    });
    setPlaceHolder();    
	
	

    $(document).on('click','.saveVisit',function(){
		var lat = '';var lon = '';
        $(".btn-save").attr("disabled", true);
		if (navigator.geolocation) { 
            navigator.geolocation.getCurrentPosition(function(position){
    			lat = position.coords.latitude;
    			lon = position.coords.longitude;
    			var a = lat+','+lon;
    			$('#s_lat').val(lat);
                $('#s_lon').val(lon);
                
                var formId = "visitForm";
                var form = $('#'+formId)[0];
                var fd = new FormData(form);
                
                $.ajax({
                    url: base_url + controller + '/saveVisit',
                    data:fd,
                    type: "POST",
                    global:false,
                    processData:false,
                    contentType:false,
                    dataType:"json",
                }).done(function(response){
                    $(".btn-save").removeAttr("disabled");
                    if(response.status==1)
                    {
                        $('#'+formId)[0].reset(); $('#visitModel').removeClass('show');
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
                    window.scrollTo(0, document.body.scrollHeight);
                });
    		});
        }
        
    });

    $(document).on('click','.saveEndVisit',function(){
        $(".btn-save").attr("disabled", true);
		var elat = '';var elon = '';var a ='';
		if (navigator.geolocation) { 
            navigator.geolocation.getCurrentPosition(function(position){
    			elat = position.coords.latitude;
    			elon = position.coords.longitude;
    			a = elat+','+elon;
    			//console.log(a);
    			$('#e_lat').val(elat);
                $('#e_lon').val(elon);
                
                var formId = "endVisit";
                var form = $('#'+formId)[0];
                var fd = new FormData(form);
                
                $.ajax({
                    url: base_url + controller + '/saveEndVisit',
                    data:fd,
                    type: "POST",
                    global:false,
                    processData:false,
                    contentType:false,
                    dataType:"json",
                }).done(function(response){
                    $(".btn-save").removeAttr("disabled");
                    if(response.status==1)
                    {
                        $('#'+formId)[0].reset(); $('#endModel').removeClass('show');
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
                    window.scrollTo(0, document.body.scrollHeight);
                });
    		});
        }
        
    });

    $(document).on('click','.endVisitBtn',function(){
        var id = $(this).data('id');        
        var lead_id = $(this).data('lead_id');
        $("#endVisit")[0].reset();
        $(".stageDiv").hide();$(".reminderDiv").hide();
        if(lead_id){
            var party_type = $(this).data('party_type');
            $("#party_type").val(party_type);
            $(".stageDiv").show();
        }

        $("#main_id").val(id);
    });

    $(document).on('change','#business_type',function(){
        var business_type = $(this).val();
		$("#party_id").html("");
		if(business_type){
            $.ajax({
                url : base_url + 'lead/getPartyOptions',
                type : 'post',
                data : {business_type:business_type},
                dataType: 'json'
            }).done(function(res){
                $("#party_id").html(res.options);
            });
        }
		$("#party_id").select2({ dropdownParent: $(this).parent()});
    });

    $('.leadList').hide();
    $(document).on('change','#party_type',function(){
        var party_type = $(this).val();
        if(party_type == 2){
            $('.leadList').show();
            $('.partyList').hide();
        }else{
            $('.leadList').hide();
            $('.partyList').show();
        }
    });

    $(document).on('change','#next_visit',function(){
        var next_visit = $("#next_visit").val();
        
        $(".reminderDiv").hide();
        if(next_visit == 'Yes'){
            $(".reminderDiv").show();
        }
    });
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


function initISOTOP(){
    var isoOptions = {
		itemSelector: '.listItem',
		layoutMode: 'fitRows',
		filter: function() {return qsRegex ? $(this).text().match( qsRegex ) : true;}
	};
    $('.listItem').css('position', 'static');
	// init isotope
	$grid = $('.list-grid').isotope( isoOptions );
}

function confirmStore(data){
	setPlaceHolder();

	var fnsave = data.fnsave || "save";
	
	var fd = data.postData;
	var msg = data.message || "Are you sure want to save this change ?";
	var ajaxParam = {
		url: base_url + controller + '/' + fnsave,
		data:fd,
		type: "POST",
		dataType:"json"
	};
	
	Swal.fire({
		title: 'Are you sure?',
		text: msg,
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Yes, Do it!',
	}).then(function(result) {
		if (result.isConfirmed)
		{
			$.ajax(ajaxParam).done(function(response){
				if(response.status==1){										
					Swal.fire( 'Success', response.message, 'success' ).then((result) => { window.location = base_url + 'app/visit'; });
				}else{
					if(typeof response.message === "object"){
						$(".error").html("");
						$.each( response.message, function( key, value ) {$("."+key).html(value);});
					}else{
						Swal.fire( 'Sorry...!', response.message, 'error' );
					}			
				}			
			});
		}
	});
}
</script>