<?php $this->load->view('app/includes/header'); ?>
	<!-- Header -->
	<header class="header">
		<div class="main-bar">
			<div class="container">
				<div class="header-content">
					<div class="left-content">
						<a href="javascript:void(0);" class="back-btn">
							<svg height="512" viewBox="0 0 486.65 486.65" width="512"><path d="m202.114 444.648c-8.01-.114-15.65-3.388-21.257-9.11l-171.875-171.572c-11.907-11.81-11.986-31.037-.176-42.945.058-.059.117-.118.176-.176l171.876-171.571c12.738-10.909 31.908-9.426 42.817 3.313 9.736 11.369 9.736 28.136 0 39.504l-150.315 150.315 151.833 150.315c11.774 11.844 11.774 30.973 0 42.817-6.045 6.184-14.439 9.498-23.079 9.11z"></path><path d="m456.283 272.773h-425.133c-16.771 0-30.367-13.596-30.367-30.367s13.596-30.367 30.367-30.367h425.133c16.771 0 30.367 13.596 30.367 30.367s-13.596 30.367-30.367 30.367z"></path>
							</svg>
						</a>
						<h5 class="title mb-0 text-nowrap">Add Head Quarter</h5>
					</div>
					<div class="mid-content">
					</div>
					<div class="right-content">
					</div>
				</div>
			</div>
		</div>
	</header>
	<!-- Header -->
    <div class="container pb">
        <div class="product-area">	
            <form id="headForm" enctype='multipart/form-data'>
                
                <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id) ? $dataRow->id : "")?>" />
                <input type="hidden" name="emp_id" id="emp_id" value="<?=(!empty($emp_id) ? $emp_id : 0)?>" />

                <div class="row">
                    <div class="mb-3">
                        <label for="name">Head Quarter Name</label>
                        <input type="text" name="name" id="name" class="form-control req" value="<?=(!empty($dataRow->name) ? $dataRow->name : "")?>" />
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="hq_lat">Latitude</label>
                    <input type="text" name="hq_lat" id="hq_lat" class="form-control req" value="<?=(!empty($dataRow->hq_lat) ? $dataRow->hq_lat : "")?>" />
                </div>

                <div class="mb-3">
                    <label for="hq_long">Longitude</label>
                    <input type="text" name="hq_long" id="hq_long" class="form-control req" value="<?=(!empty($dataRow->hq_long) ? $dataRow->hq_long : "")?>" />
                </div>

                <div class="mb-3">
                    <label for="remark">Remark</label>
                    <textarea name="remark" id="remark" class="form-control"><?=(!empty($dataRow->remark) ? $dataRow->remark : "")?></textarea>
                </div>
            </form>
        </div>
        <div class="footer fixed">
            <div class="container">
                <?php
                    $param = "{'formId':'headForm','fnsave':'save','controller':'app/hqChangeRequest/'}";
                ?>
                <a href="javascript:void(0)" class="btn btn-primary btn-block btn-save" onclick="store(<?=$param?>)">Save</a>
            </div>
        </div>
    </div>
<?php $this->load->view('app/includes/footer'); ?>
<script>
$(document).ready(function(){
    var lat = '';var lon = '';
    if (navigator.geolocation) { 
        navigator.geolocation.getCurrentPosition(function(position){
            lat = position.coords.latitude;
            lon = position.coords.longitude;
            var a = lat+','+lon;
            $('#hq_lat').val(lat);
            $('#hq_long').val(lon);
        });
    }
});
function store(postData){
    var formId = postData.formId;
    var fnsave = postData.fnsave || "save";
    var controllerName = postData.controller || controller;

    var form = $('#'+formId)[0];
    var fd = new FormData(form);
    $(".btn-save").attr("disabled", true);
    $.ajax({
        url: base_url + controllerName+'/'+fnsave,
        data:fd,
        type: "POST",
        processData:false,
        contentType:false,
        dataType:"json",
    }).done(function(data){
        $(".btn-save").removeAttr("disabled");
        if(data.status==1){
            $('#'+formId)[0].reset(); 
            Swal.fire({
                title: "Success",
                text: data.message,
                icon: "success",
                showCancelButton: false,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ok!"
                }).then((result) => {
                    window.location = base_url + 'app/employee';
                });
        }else{
            if(typeof data.message === "object"){
                $(".error").html("");
                $.each( data.message, function( key, value ) {$("."+key).html(value);});
            }else{
                Swal.fire( 'Sorry...!', data.message, 'error' );
            }			
        }				
    });
}
</script>