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
						<h5 class="title mb-0 text-nowrap">Add Approaches</h5>
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
            <form id="approachForm">
                
                <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""; ?>" />
                <input type="hidden" name="party_code" value="<?=(!empty($dataRow->party_code)) ?$dataRow->party_code : $party_code;?>" />
                <input type="hidden" name="party_type" value="<?=(!empty($dataRow->party_type))?$dataRow->party_type:$party_type?>" />
                <input type="hidden" name="executive_id" id="executive_id" value="<?=$this->loginId?>">
                <input type="hidden" name="country_id" id="country_id" value="101">
                <input type="hidden" name="currency" id="currency" value="INR">
                <input type="hidden" name="appointment_date" id="appointment_date" value="<?=(!empty($dataRow->appointment_date))?$dataRow->appointment_date:date("Y-m-d")?>" />

                <div class="row">
                    <div class="mb-3">
                        <label class="form-label" for="party_name">Company Name</label>
                        <input type="text" name="party_name" class="form-control text-capitalize req" value="<?=(!empty($dataRow->party_name))?$dataRow->party_name:""; ?>" />
                    </div>
                </div>
                <div class="row">
                    <div class="col ">
                        <div class="mb-3">
                            <label class="form-label" for="contact_person">Contact Person</label>
                            <input type="text" name="contact_person" class="form-control text-capitalize" value="<?=(!empty($dataRow->contact_person))?$dataRow->contact_person:""?>" />
                        </div>
                    </div>
                    <div class="col ">
                        <div class="mb-3">
                            <label class="form-label" for="contact_phone">Contact No.</label>
                            <input type="number" name="contact_phone" class="form-control numericOnly" value="<?=(!empty($dataRow->contact_phone))?$dataRow->contact_phone:""?>" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col ">
                        <div class="mb-3">
                            <label class="form-label" for="whatsapp_no">Whatsapp No.</label>
                            <input type="number" name="whatsapp_no" class="form-control numericOnly" value="<?=(!empty($dataRow->whatsapp_no))?$dataRow->whatsapp_no:""?>" />
                        </div>
                    </div>
                    
                    <div class="col">
                        <div class="mb-3">
                            <label for="party_email" class="form-label">Party Email</label>
                            <input type="email" name="party_email" class="form-control" value="<?=(!empty($dataRow->party_email))?$dataRow->party_email:""; ?>" />
                        </div>
                        
                    </div>
                </div>
                <div class="row">                    
                    <div class="col">
                        <div class="mb-3">
                            <label class="form-label" for="source">Source</label>
                            <select name="source" id="source" class="form-control select2">
                                <?php
                                    foreach($sourceList as $row):
                                        $selected = (!empty($dataRow->source) and $dataRow->source == $row->label)?"selected":"";
                                        echo '<option value="'.$row->label.'" '.$selected .'>'.$row->label.'</option>';
                                    endforeach;
                                ?>
                            </select>
                        </div>                   
                    </div>
                    <div class="col ">
                        <div class="mb-3">
                            <label class="form-label" for="business_type">Business Type</label>
                            <select name="business_type" id="business_type" class="form-control select2">
                                <option value="">Select Type</option>
                                <?php
                                    foreach($bTypeList as $row):
                                        $selected = (!empty($dataRow->business_type) and $dataRow->business_type == $row->type_name)?"selected":"";
                                        echo '<option value="'.$row->type_name.'" '.$selected .'>'.$row->type_name.'</option>';
                                    endforeach;
                                ?>
                            </select>
                        </div>
                    </div>     
                </div>
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label class="form-label" for="state_id">Select State</label>
                            <select  id="state_id" class="form-control state_list select2 req" data-city_id="city_id" data-selected_city_id="<?=(!empty($dataRow->city_id))?$dataRow->city_id:""?>" >
                                <option value="">Select State</option>
                                <?php
                                if(!empty($stateList)){
                                    foreach($stateList as $row){
                                        $selected = (!empty($dataRow->state) && $dataRow->state == $row->state)?'selected':'';
                                        ?>
                                        <option value="<?=$row->state?>" <?=$selected?>><?=$row->state?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label class="form-label" for="district">Select District</label>
                            <select  id="district" class="form-control modal-select2 req">
                                <?= !empty($districtList)?$districtList:''?>
                            </select>
                         </div>  
                    </div>  
                    
                    <div class="col">
                        <div class="mb-3">
                            <label  class="form-label" for="statutory_id">Select Taluka</label>
                            <select name="statutory_id" id="statutory_id" class="form-control modal-select2 req">
                                <?= !empty($talukaList)?$talukaList:''?>
                            </select>
                        </div>  
                    </div>   
                </div>
                <div class="row">  
                    <div class="col">
                        <div class="mb-3">
                            <label class="form-label" for="sales_zone_id">Sales Zone</label>
                            <select name="sales_zone_id" id="sales_zone_id" class="form-control select2">
                                <option value="">Sales Zone</option>
                                <?php
                                    foreach($salesZoneList as $row):
                                        $selected = (!empty($dataRow->sales_zone_id) && $dataRow->sales_zone_id == $row->id)?"selected":"";
                                        echo '<option value="'.$row->id.'" '.$selected.'>'.$row->zone_name.'</option>';
                                    endforeach;
                                ?>
                            </select>
                        </div>
                    </div>                  
                    
                </div>
                <div class="mb-3">
                    <label class="form-label" for="party_address">Address</label>
                    <textarea name="party_address" class="form-control req" rows="2"><?=(!empty($dataRow->party_address))?$dataRow->party_address:""?></textarea>
                </div>
              
            </form>
        </div>
        <div class="footer fixed">
            <div class="container">
                <?php
                    $param = "{'formId':'approachForm','fnsave':'save','controller':'app/lead/'}";
                ?>
                <a href="javascript:void(0)" class="btn btn-primary btn-block btn-save" onclick="store(<?=$param?>)">Save</a>
            </div>
        </div>
    </div>
<?php $this->load->view('app/includes/footer'); ?>
<script>
$(document).ready(function(){
    setPlaceHolder();
    $(".select2").select2();
    
    setTimeout(function(){ $(".country_list").trigger('change'); }, 1000);
    $(document).on('change','#party_type',function(){
        var party_type = $(this).val();
        $.ajax({
            url : base_url + 'parties/getPartyCode',
            type : 'post',
            data : {party_type:party_type},
            dataType: 'json'
        }).done(function(res){
            $("#party_code").val(res.party_code);
        });
    });
    
    $(document).on('change','#state_id',function(){
        var state = $(this).val();
        $.ajax({
            url : base_url+controller + '/getDistrictList',
            type : 'post',
            data : {state:state},
            dataType: 'json'
        }).done(function(res){
            $("#district").html(res.districtOption);
            $(".select2").select2();
        });
    });

    $(document).on('change','#district',function(){
        var district = $(this).val();
        var state = $("#state_id").val();
        $.ajax({
            url : base_url+controller + '/getTalukaList',
            type : 'post',
            data : {state:state,district:district},
            dataType: 'json'
        }).done(function(res){
            $("#statutory_id").html(res.talukaOption);
            $(".select2").select2();
        });
    });
	
	// setTimeout(function(){ initSelect2('right_modal_lg'); }, 500);
});

function store(postData){
    var formId = postData.formId;
    var fnsave = postData.fnsave || "save";
    var controllerName = postData.controller || controller;

    var form = $('#'+formId)[0];
    var fd = new FormData(form);
    $(".btn-save").attr("disabled", true);
    $.ajax({
        url: base_url + controllerName +fnsave,
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
                    window.location = base_url + 'app/lead/crmDesk/';
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
