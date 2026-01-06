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
						<h5 class="title mb-0 text-nowrap">Add Expense</h5>
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
            <form id="expForm" enctype='multipart/form-data'>
                
                <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id) ? $dataRow->id : "")?>" />
                <input type="hidden" name="exp_by_id" id="exp_by_id" value="<?=(!empty($dataRow->emp_id) ? $dataRow->emp_id : $this->loginId)?>" />
                <input type="hidden" name="exp_source" id="exp_source" value="1" />

                <div class="row">
                    <div class="col-6">
                        <div class="mb-3">
                            <label class="form-label" for="exp_number">Expense No.</label>
                            <input type="text" name="exp_number" id="exp_number" class="form-control req" value="<?=(!empty($dataRow->exp_number) ? $dataRow->exp_number : $exp_prefix.sprintf("%03d",$exp_no))?>" readOnly />
                            <input type="hidden" name="exp_prefix" id="exp_prefix" value="<?=(!empty($dataRow->exp_prefix)) ? $dataRow->exp_prefix : $exp_prefix?>" />
                            <input type="hidden" name="exp_no" id="exp_no" value="<?=(!empty($dataRow->exp_no)) ? $dataRow->exp_no : $exp_no?>" />
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="exp_date">Exp Date</label>
                            <input type="datetime-local" name="exp_date" id="exp_date" class="form-control req" value="<?=(!empty($dataRow->exp_date) ? $dataRow->exp_date : date('Y-m-d h:i'))?>" max="<?= date('Y-m-d h:i');?>"/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="error exp_type_data"></div>
                        <div class="table-responsive">
                            <table id="expenceTypeData" class="table table-striped table-borderless">
                                <thead class="thead-info">
                                    <tr>
                                        <th style="width:10%;">#</th>
                                        <th style="width:40%;">Expence Type</th>
                                        <th style="width:40%;">Amount</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyData">
                                    <?php
                                        if(!empty($expTypeList)){
                                            $expTransData = (!empty($expTransData))?$expTransData:[];
                                            $expTransData = array_reduce($expTransData, function($itemData, $row) {
                                                $itemData[$row->exp_type_id] = $row;
                                                return $itemData;
                                            }, []);
        
                                            $i=1;
                                            foreach($expTypeList as $row){
                                                $transId = $transValue = 0;
        
                                                $transId = (isset($expTransData[$row->id]) && !empty($expTransData[$row->id]->id))?$expTransData[$row->id]->id:0;
                                                $transValue = (isset($expTransData[$row->id]) && !empty($expTransData[$row->id]->amount))?$expTransData[$row->id]->amount:0;
        
                                                echo "<tr>";
                                                echo "<td>".$i++."</td>";
                                                echo "<td>".$row->label."</td>";
                                                echo "<td>
                                                        <input type='hidden' name='expTrans[".$i."][id]' value='".$transId."' />
                                                        <input type='hidden' name='expTrans[".$i."][exp_type_id]' value='".$row->id."' />
                                                        <input type='number' name='expTrans[".$i."][amount]' class='form-control floatOnly' value='".floatval($transValue)."' />
                                                    </td>";
                                                echo "</tr>";
                                            }
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6 form-group travelExpense">
                        <label for="vehicle_type">Vehicle</label>
                        <select class="form-control calculateKM" name="vehicle_type"  id="vehicle_type">
                            <option value="1" <?=(!empty($dataRow->vehicle_type) && $dataRow->vehicle_type == 1)?'selected':''?>>Bike</option>
                            <option value="2" <?=(!empty($dataRow->vehicle_type) && $dataRow->vehicle_type == 2)?'selected':''?>>Car</option>
                        </select>
                            
                    </div>
                    <div class="col-6 form-group travelExpense">
                        <label for="start_km">Start KM</label>
                        <input type="number" class="form-control floatOnly calculateKM" name="start_km"  id="start_km" value="<?=(!empty($dataRow->start_km) ? $dataRow->start_km : "")?>">
                    </div>
                    <div class="col-6 form-group travelExpense">
                        <label for="end_km">End KM</label>
                        <input type="number" class="form-control floatOnly calculateKM" name="end_km"  id="end_km"  value="<?=(!empty($dataRow->end_km) ? $dataRow->end_km : "")?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="proof_file">File Upload</label>
                    <div class="input-group">
                        <input type="file" name="proof_file[]" class="form-control" style="width:<?=(!empty($dataRow->proof_file)) ? "75%" : "" ?>" multiple="multiple" />
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label" for="notes">Notes</label>
                    <textarea name="notes" id="notes" class="form-control" rows="2"><?=(!empty($dataRow->notes) ? $dataRow->notes : "")?></textarea>
                </div>

            </form>
        </div>
        <div class="footer fixed">
            <div class="container">
                <?php
                    $param = "{'formId':'expForm','fnsave':'save','controller':'app/expense/'}";
                ?>
                <a href="javascript:void(0)" class="btn btn-primary btn-block btn-save" onclick="store(<?=$param?>)">Save</a>
            </div>
        </div>
    </div>
<?php $this->load->view('app/includes/footer'); ?>
<script>
$(document).ready(function(){
    setPlaceHolder();

    setTimeout(function(){ $("#exp_type").trigger('change'); }, 50);
    $(".travelExpense").hide();
    
    $(document).on('change','#exp_type',function(){
        var is_travel = ($("#exp_type :selected").data('is_travel'));
        if(is_travel == 1){  $(".travelExpense").show();  $('#demand_amount').attr("readonly","readonly");}
        else{ $(".travelExpense").hide(); $('#demand_amount').removeAttr("readonly");}
    });

    $(document).on('input keyup change','.calculateKM',function(){
        var bike_expense = parseFloat($("#exp_type :selected").data('bike_expense')) || 0;
        var car_expense = parseFloat($("#exp_type :selected").data('car_expense')) || 0;
        var start_km = parseFloat($("#start_km").val()) || 0;
        var end_km = parseFloat($("#end_km").val())|| 0;
        var vehicle_type = $("#vehicle_type").val();
        var totalKm = 0; 
        if(end_km >= start_km){ totalKm = end_km-start_km; }
        var amount = 0;
        if(vehicle_type == 1){  amount = totalKm*bike_expense; }
        else{ amount = totalKm*car_expense; }

        $('#demand_amount').val(amount);
        
    });
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
                    window.location = base_url + 'app/expense';
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