<form>
    <div class="col-md-12">
        <div class="row">
            <?php
            $party_type = (!empty($dataRow->party_type))?$dataRow->party_type:$party_type;
            ?>
            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""; ?>" />
			<input type="hidden" name="disc_per" value="<?=(!empty($dataRow->disc_per))?$dataRow->disc_per:""?>" />
			<input type="hidden" name="party_type" value="<?=(!empty($dataRow->party_type))?$dataRow->party_type:$party_type?>" />
			<input type="hidden" name="country_id" value="<?=(!empty($dataRow->country_id))?$dataRow->country_id:101?>" />

            <?php if(!empty($party_type) && $party_type == 2): ?>
                <input type="hidden" name="party_code" value="<?=(!empty($dataRow->party_code))?$dataRow->party_code:$party_code?>" />
            <?php endif; ?>

            <?php if(!empty($party_type) && $party_type != 2): ?>
            <div class="col-md-2 form-group">
                <label for="party_code">Party Code</label>
                <input type="text" name="party_code" id="party_code" class="form-control" value="<?= (!empty($dataRow->party_code)) ?$dataRow->party_code : $party_code; ?>" readonly/>
            </div>  
            <?php endif; ?>

            <div class="col-md-<?=((!empty($party_type) && $party_type != 2)?"10":"12")?> form-group">
                <label for="party_name">Company/Trade Name</label>
                <input type="text" name="party_name" id="party_name" class="form-control text-capitalize req" value="<?=(!empty($dataRow->party_name))?$dataRow->party_name:""; ?>" />
            </div>

            

            <div class="col-md-<?=(!empty($party_type) && $party_type != 2) ? '3' : '4'?> form-group">
                <label for="source">Source</label>
                <select name="source" id="source" class="form-control modal-select2">
                    <?php
                        foreach($sourceList as $row):
							$selected = (!empty($dataRow->source) and $dataRow->source == $row->label)?"selected":"";
                            echo '<option value="'.$row->label.'" '.$selected .'>'.$row->label.'</option>';
                        endforeach;
                    ?>
                </select>
            </div>
            <?php
            if(in_array($this->userRole,[0,-1,1])):
                ?>
                <div class="col-md-<?=(!empty($party_type) && $party_type != 2) ? '3' : '4'?> form-group">
                    <label for="executive_id">Sales Executives</label>
                    <select name="executive_id" id="executive_id" class="form-control modal-select2">
                        <option value="">Select Sales Executive</option>
                        <?php
                            if(!empty($salesExecutives)){
                                foreach($salesExecutives as $row){
                                    $selected = (!empty($dataRow->executive_id) and $dataRow->executive_id == $row->id)?"selected":"";
                                    echo '<option value="'.$row->id.'" '.$selected .'>'.$row->emp_name.'</option>';
                                }
                            }
                        ?>
                    </select>
                </div>
                <?php
            else:
                ?>
                <input type="hidden" name="executive_id" id="executive_id" value="<?=($this->loginId)?>">
                <?php
            endif;
            ?>
            <div class="col-md-<?=(!empty($party_type) && $party_type != 2) ? '3' : '4'?> form-group">
                <label for="sales_zone_id">Sales Zone</label>
                <select name="sales_zone_id" id="sales_zone_id" class="form-control modal-select2 req">
					<option value="">Sales Zone</option>
					<?php
						foreach($salesZoneList as $row):
							$selected = (!empty($dataRow->sales_zone_id) && $dataRow->sales_zone_id == $row->id)?"selected":"";
							echo '<option value="'.$row->id.'" '.$selected.' >'.$row->zone_name.'</option>';
						endforeach;
					?>
				</select>
            </div>
            
            
            <div class="<?=(!empty($party_type) && $party_type != 2) ? 'col-md-3' : 'col-md-3' ?> form-group">
                <label for="business_type">Business Type</label>
                <select name="business_type" id="business_type" class=" <?=((!empty($party_type) && $party_type != 2) ? 'businessType' : '') ?> form-control modal-select2">
                    <option value="">Select Type</option>
                    <?php
                        foreach($bTypeList as $row):
                            $selected = (!empty($dataRow->business_type) && $dataRow->business_type == $row->type_name)?"selected":"";
                            echo '<option value="'.$row->type_name.'" '.$selected.' data-parent_type="'.$row->parentType.'">'.$row->type_name.'</option>';
                        endforeach;
                    ?>
                </select>
            </div>
			<?php if(!empty($party_type) && $party_type != 2): ?>
            <div class="col-md-3 form-group">
                <label for="parent_id">Parent Type</label>
                <select name="parent_id" id="parent_id" class="form-control modal-select2">
                    <option value="">Select</option>
                    <?=!empty($parentOption)?$parentOption:''?>
                </select>
            </div>
            <?php endif; ?>
            
            <div class="col-md-3 form-group">
                <label for="contact_person">Contact Person</label>
                <input type="text" name="contact_person" class="form-control text-capitalize" value="<?=(!empty($dataRow->contact_person))?$dataRow->contact_person:""?>" />
            </div>

            <div class="col-md-3 form-group">
                <label for="contact_phone">Contact No.</label>
                <input type="text" name="contact_phone" class="form-control numericOnly" value="<?=(!empty($dataRow->contact_phone))?$dataRow->contact_phone:""?>" />
            </div>
			<div class="col-md-3 form-group">
                <label for="whatsapp_no">Whatsapp No.</label>
                <input type="text" name="whatsapp_no" class="form-control numericOnly" value="<?=(!empty($dataRow->whatsapp_no))?$dataRow->whatsapp_no:""?>" />
            </div>
            <div class="col-md-4 form-group">
                <label for="party_email">Email</label>
                <input type="email" name="party_email" class="form-control" value="<?=(!empty($dataRow->party_email))?$dataRow->party_email:""; ?>" />
            </div>  
            
            <div class="col-md-4 form-group">
                <label for="registration_type">Registration Type</label>
                <select name="registration_type" id="registration_type" class="form-control modal-select2">
                    <?php
                        foreach($this->gstRegistrationTypes as $key=>$value):
                            $selected = (!empty($dataRow->registration_type) && $dataRow->registration_type == $key)?"selected":"";
                            echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
                        endforeach;
                    ?>
                </select>
            </div>
            
			<div class="col-md-4 form-group">
                <label for="gstin">Party GSTIN</label>
                    <span class="float-right">
                        <a class="text-primary font-bold" id="getGstinDetail" href="javascript:void(0)">Verify</a>
                    </span>
                <input type="text" name="gstin" id="gstin" class="form-control text-uppercase req" value="<?=(!empty($dataRow->gstin))?$dataRow->gstin:""; ?>" />
            </div>
            <div class="col-md-<?=(!empty($party_type) && $party_type != 2) ? '6' : '4'?> form-group">
                <label for="state_id">Select State</label>
                <select  id="state_id" class="form-control  modal-select2 req state_list" data-district="district" data-selected_district="<?=(!empty($dataRow->district))?$dataRow->district:""?>" >
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
            
            <div class="col-md-<?=(!empty($party_type) && $party_type != 2) ? '6' : '4'?> form-group">
                <label for="district">Select District</label>
                <select  id="district" class="form-control modal-select2 req district_list" data-statutory_id="statutory_id" data-selected_statutory_id="<?=(!empty($dataRow->statutory_id))?$dataRow->statutory_id:""?>">
                </select>
            </div>  
            
            <div class="col-md-<?=(!empty($party_type) && $party_type != 2) ? '3' : '4'?> form-group">
                <label for="statutory_id">Select Taluka</label>
                <select name="statutory_id" id="statutory_id" class="form-control modal-select2 req">
                </select>
            </div>
			<?php if(!empty($party_type) && $party_type != 2): ?>
            <div class="col-md-<?=(!empty($party_type) && $party_type != 2) ? '3' : '4'?> form-group">
                <label for="party_pincode">Pincode</label>
                <input type="text" name="party_pincode" id="party_pincode" class="form-control req" value="<?=(!empty($dataRow->party_pincode))?$dataRow->party_pincode:""?>" />
            </div>

            <?php endif; if(!empty($party_type) && $party_type != 2): ?>
            <div class="col-md-<?=(in_array($this->userRole,[0,-1,1])) ? "6" : "6"?> form-group">
				<label for="discount_structure">Discount</label>
				<select class="form-control modal-select2" name="discount_structure" id="discount_structure">
                    <option value="">Select</option>
                    <?php
                    if(!empty($discountList)){
                        foreach($discountList as $row){
                            $selected = (!empty($dataRow->discount_structure) && $dataRow->discount_structure == $row->structure_name)?'selected':'';
                            ?><option value="<?=$row->structure_name?>" <?=$selected?>><?=$row->structure_name?></option><?php
                        }
                    }
                    ?>
                </select>
			</div>
            <?php endif; ?>

            <div class="col-md-12 form-group">
                <label for="party_address">Address</label>
                <textarea name="party_address" id="party_address" class="form-control req" rows="2"><?=(!empty($dataRow->party_address))?$dataRow->party_address:""?></textarea>
            </div>
            <?php 
            if($party_type == 1){
                ?>
                <div class="col-md-6 form-group">
                    <label for="party_image">Image File</label>
                    <div class="input-group">
                        <input type="file" name="party_image" id="party_image" class="form-control">
                        <div class="input-group-append">
                            <?php
                            if(!empty($dataRow->party_image)){
                                ?>
                                <a href="<?=base_url('assets/uploads/party/'.$dataRow->party_image)?>" class="btn btn-outline-primary" download><i class="fa fa-arrow-down"></i></a>
                                <?php
                            }
                            ?>
                        </div>                                        
                    </div>   

                </div>
                <h4 class="fs-15 text-primary border-bottom-sm">Custom Fields</h4>
            <div class="row">
            <?php
            if(!empty($customFieldList)){
                foreach($customFieldList as $field){
                    ?>
                    <div class="col-md-6 form-group">
                        <label for="wt_pcs"><?=$field->field_name?></label>
                        <?php
                        if($field->field_type == 'SELECT'){
                            ?>
                            <select name="customField[f<?=$field->field_idx?>]" id="f<?=$field->field_idx?>" class="form-control modal-select2">
                                <option value="">Select</option>
                            <?php
                            foreach($masterDetailList as $row){
                                if($row->type == $field->id){
                                    $selected = (!empty($customData) && !empty(htmlentities($customData->{'f'.$field->field_idx}) && htmlentities($customData->{'f'.$field->field_idx}) == htmlentities($row->title)))?'selected':'';
                                    ?>
                                    <option value="<?=htmlentities($row->title)?>" <?=$selected?>><?=$row->title?></option>
                                    <?php
                                }
                            }
                        }elseif($field->field_type == 'TEXT'){
                            ?>
                            <input type="text" name="customField[f<?=$field->field_idx?>]" id="f<?=$field->field_idx?>" class="form-control" value="<?=(!empty($customData) && !empty($customData->{'f'.$field->field_idx}))?$customData->{'f'.$field->field_idx}:''?>">
                            <?php
                        }elseif($field->field_type == 'NUM'){
                            ?>
                            <input type="text" name="customField[f<?=$field->field_idx?>]" id="f<?=$field->field_idx?>" class="form-control floatOnly" value="<?=(!empty($customData) && !empty($customData->{'f'.$field->field_idx}))?$customData->{'f'.$field->field_idx}:''?>">
                            <?php
                        }
                        ?>
                        </select>
                    </div>
                    <?php
                }
            }
            ?>
            </div>   
                <?php
            }
            ?>
                 
        </div>
    </div>
</form>
<script>
$(document).ready(function(){    
    $("#country_id").trigger('change');
    $(".state_list").trigger('change');

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

    $(document).on('change','#executive_id',function(){
		var executive_id = $(this).val();
        if(executive_id){
            $.ajax({
				url: base_url + 'parties/getSeForSalesZoneList',
				type:'post',
				data:{executive_id:executive_id},
				dataType:'json',
				success:function(data)
                {
					$("#sales_zone_id").html("");
					$("#sales_zone_id").html(data.options);
				}
			});
        }
	});

    $(document).on('change','.businessType',function(){
		var business_type = $("#business_type :selected").data('parent_type');
		var sales_zone_id = $("#sales_zone_id").val();
        if(business_type){
            $.ajax({
				url: base_url + 'parties/getParentType',
				type:'post',
				data:{business_type:business_type,sales_zone_id:sales_zone_id},
				dataType:'json',
				success:function(data)
                {
					$("#parent_id").html("");
					$("#parent_id").html(data.options);
				}
			});
        }
	});

    // $(document).on('change','#state_id',function(){
    //     var state = $(this).val();
    //     $.ajax({
    //         url : base_url + 'parties/getDistrictList',
    //         type : 'post',
    //         data : {state:state},
    //         dataType: 'json'
    //     }).done(function(res){
    //         $("#district").html(res.districtOption);
    //     });
    // });

    // $(document).on('change','#district',function(){
    //     var district = $(this).val();
    //     var state = $("#state_id").val();
    //     $.ajax({
    //         url : base_url + 'parties/getTalukaList',
    //         type : 'post',
    //         data : {state:state,district:district},
    //         dataType: 'json'
    //     }).done(function(res){
    //         $("#statutory_id").html(res.talukaOption);
    //     });
    // });
    setTimeout(function(){ initSelect2('right_modal_lg'); }, 500);
});
</script>