<?php $this->load->view('app/includes/header'); ?>
<?php //$this->load->view('app/includes/topbar'); ?>
<header class="header">
		<div class="main-bar">
			<div class="container">
				<div class="header-content">
					<div class="left-content">
						<a href="javascript:void(0);" class="back-btn">
							<svg height="512" viewBox="0 0 486.65 486.65" width="512"><path d="m202.114 444.648c-8.01-.114-15.65-3.388-21.257-9.11l-171.875-171.572c-11.907-11.81-11.986-31.037-.176-42.945.058-.059.117-.118.176-.176l171.876-171.571c12.738-10.909 31.908-9.426 42.817 3.313 9.736 11.369 9.736 28.136 0 39.504l-150.315 150.315 151.833 150.315c11.774 11.844 11.774 30.973 0 42.817-6.045 6.184-14.439 9.498-23.079 9.11z"/><path d="m456.283 272.773h-425.133c-16.771 0-30.367-13.596-30.367-30.367s13.596-30.367 30.367-30.367h425.133c16.771 0 30.367 13.596 30.367 30.367s-13.596 30.367-30.367 30.367z"/>
							</svg>
						</a>
						<!-- <div class="media me-3 media-35 rounded-circle">
							<img src="../assets/images/avatar/4.jpg" alt="/">
						</div>	 -->
						<h5 class="title mb-0 text-nowrap"><?=$partyData->party_name?></h5>
					</div>
					<div class="mid-content">
					</div>
					<div class="right-content">
                        <?php
					    $party_id = ($partyData->party_type == 1)?$partyData->id : $partyData->party_id;
					    $lead_id = ($partyData->party_type == 1)?$partyData->lead_id : $partyData->id;
					    ?>
                        <div class="basic-dropdown">
                            <div class="dropdown">
                                <a type="button" class=" dropdown-toggle show font-20" data-bs-toggle="dropdown" aria-expanded="true">
                                    <i class="fas fa-ellipsis-v"></i>
                                </a>
                                <div class="dropdown-menu" data-popper-placement="bottom-start" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(0px, 48px);">
                                    <?php
                                    if($partyData->party_type != 1){
                                        ?>
                                        <a type="button" class="text-danger permission-write btn-remind dropdown-item"  data-form_title="Add Reminder" datatip="Add Reminder" data-bs-toggle="offcanvas" data-bs-target="#remind_dd1" aria-controls="offcanvasBottom"><i class="far fa-bell fs-22"></i> Reminder</a>
                                        <?php
                                    }
                                    ?>  
                                                                    
                                    <a href="<?=base_url("app/lead/addSalesEnquiry/".$lead_id.'/'.$party_id)?>" class="dropdown-item text-primary permission-write press-add-btn"  data-form_title="Add Enquiry" datatip="Add Enquiry"><i class="far fa-question-circle fs-22"></i> Enquiry</a>

                                    <!-- <a href="<?=base_url("app/lead/addSalesQuotation/".$lead_id.'/'.$party_id)?>" class="dropdown-item text-info addCrmForm permission-write press-add-btn" data-button="both" data-modal_id="right_modal_lg" data-function="addSalesQuotation" data-fnsave="saveSalesQuotation" data-form_title="Add Quotation" datatip="Add Quotation"><i class="far fa-file-alt fs-22"></i> Quotation</a> -->
                                    
                                    <a href="<?=base_url("app/lead/addSalesOrder/".$lead_id.'/'.$party_id)?>" class="dropdown-item text-success addCrmForm permission-write press-add-btn" data-button="both" data-modal_id="right_modal_lg" data-function="addSalesOrder" data-fnsave="saveSalesOrder" data-form_title="Add Order" datatip="Add Order"><i class="fas fa-cart-plus fs-22"></i> Order</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="offcanvas offcanvas-bottom" tabindex="-1" id="remind_dd1" aria-modal="true" role="dialog">
                            <div class="offcanvas-body small">
                                <form id="reminderForm">
                                    <!--<div class="card">
                                        <div class="card-body">-->
                                            <input type="hidden" name="party_id" id="party_id" value="<?=$party_id?>" />
                                            <input type="hidden" name="lead_id" id="lead_id" value="<?=$lead_id?>" />
                                            <input type="hidden" name="id" id="id" value="" />
                                            <div class="mb-3">
                                                <label for="ref_date" class="form-label">Date</label>
                                                <input type="date" name="ref_date" id="ref_date" class="form-control req" value="<?=(!empty($dataRow->ref_date ))?$dataRow->ref_date :date("Y-m-d")?>" min="<?=date("Y-m-d")?>" />
                                            </div>
                                            <div class="mb-3">
                                                <label for="reminder_time" class="form-label">Time</label>
                                                <input type="time" name="reminder_time" id="reminder_time" class="form-control req" value="<?=(!empty($dataRow->reminder_time))?date("h:i:s",strtotime($dataRow->reminder_time)):date("h:i:s")?>" min="<?=date("h:i:s")?>" />
                                            </div>
                                            <!-- 13-03-2024 -->
                                            <div class="mb-3">
                                                <label for="mode" class="form-label">Mode</label>
                                                <select name="mode" id="mode" class="form-control req dz-form-select">
                                                    <?php
                                                        foreach($this->appointmentMode as $key=>$mode):
                                                            $selected = (!empty($dataRow->source) and $dataRow->source == $mode)?"selected":"";
                                                            echo '<option value="'.$mode.'" '.$selected .'>'.$mode.'</option>';
                                                        endforeach;
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="notes" class="form-label">Notes</label>
                                                <textarea name="notes" class="form-control" rows="3"><?=(!empty($dataRow->notes))?$dataRow->notes:""?></textarea>
                                            </div>
                                            <div class="">
                                                <button type="button" class="btn btn-success btn-round btn-outline-dashed btn-block saveReminder" >Save Reminder</button>
                                            </div>
                                        <!--</div>        
                                    </div>-->
                                </form>
                            </div>
                        </div>

				    </div>
				</div>
			</div>
		</div>
	</header>
 <!-- Page content  -->
 <div class="page-content message-content bg-white">
        <div class="container chat-box-area bottom-content salesLog bg-white" > 
           <?=$salesLog?>
        </div>  
    </div>
    <!-- Page content End -->
    
    <!-- Footer -->
    <footer class="footer border-0 fixed" style="border-top:1px solid #009688 !important;">
        <div class="container p-2">
            <div class="chat-footer">
                <form>
                    <div class="form-group boxed">
                        <div class="input-wrapper message-area">
							<div class="append-media"></div>
                            <input type="text" rows="1" name="msg_content" id="msg_content" class="form-control" placeholder="Type a Message...">
                            <a href="javascript:void(0);" class="btn  btn-icon btn-secondary p-0 btn-rounded saveFollowups">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M21.4499 11.11L3.44989 2.11C3.27295 2.0187 3.07279 1.9823 2.87503 2.00546C2.67728 2.02862 2.49094 2.11029 2.33989 2.24C2.18946 2.37064 2.08149 2.54325 2.02982 2.73567C1.97815 2.9281 1.98514 3.13157 2.04989 3.32L4.99989 12L2.09989 20.68C2.05015 20.8267 2.03517 20.983 2.05613 21.1364C2.0771 21.2899 2.13344 21.4364 2.2207 21.5644C2.30797 21.6924 2.42378 21.7984 2.559 21.874C2.69422 21.9496 2.84515 21.9927 2.99989 22C3.15643 21.9991 3.31057 21.9614 3.44989 21.89L21.4499 12.89C21.6137 12.8061 21.7512 12.6786 21.8471 12.5216C21.9431 12.3645 21.9939 12.184 21.9939 12C21.9939 11.8159 21.9431 11.6355 21.8471 11.4784C21.7512 11.3214 21.6137 11.1939 21.4499 11.11ZM4.70989 19L6.70989 13H16.7099L4.70989 19ZM6.70989 11L4.70989 5L16.7599 11H6.70989Z" fill="#fff"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </form>
            </div>    
        </div>
    </footer>
    <!-- Footer End -->

<!--**********************************
    Scripts
***********************************-->
<?php $this->load->view('app/includes/footer'); ?>
<script>
    $(document).ready(function(){
        setPlaceHolder();
        window.scrollTo(0, document.body.scrollHeight);
        $("#msg_content").keypress(function (e) {
            if(e.which === 13 && !e.shiftKey) {
                e.preventDefault();
                $(".saveFollowups").trigger("click");
            }
        });
        $(document).on('click','.saveFollowups',function(){
            var party_id = $("#party_id").val();
            var lead_id = $("#lead_id").val();
            var notes = $("#msg_content").val();
            console.log(notes);
            $.ajax({
                url: base_url + controller + '/saveFollowups',
                data: {party_id:party_id, lead_id:lead_id, notes:notes},
                type: "POST",
                global:false,
                dataType:"json",
            }).done(function(response){
                if(response.status==1){$("#msg_content").val('');$(".salesLog").html(response.salesLog);}
                window.scrollTo(0, document.body.scrollHeight);
            });
        });


        $(document).on('click','.saveReminder',function(){
            var formId = "reminderForm";
            var form = $('#'+formId)[0];
            var fd = new FormData(form);

            $.ajax({
                url: base_url + controller + '/saveReminder',
                data:fd,
                type: "POST",
                global:false,
                processData:false,
                contentType:false,
                dataType:"json",
            }).done(function(response){
                if(response.status==1)
                {
                    $(".salesLog").html(response.salesLog);
                    $("#reminderForm")[0].reset();
                    $('#remind_dd1').offcanvas('hide');
                }
                else{$(".error").html("");$.each( response.message, function( key, value ) {$("."+key).html(value);});}
                window.scrollTo(0, document.body.scrollHeight);
            });
        });


       
    });
   
   function addQuoteRequest(data){
        var send_data = data.postData;
        // console.log(data);
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't to send Quotation request!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Send it!',
        }).then(function(result) {
            if (result.isConfirmed)
            {
                $.ajax({
                    url: base_url + controller + '/sendQuotationRequest',
                    data: send_data,
                    type: "POST",
                    dataType:"json",
                }).done(function(response){
                    if(response.status==0){
                        Swal.fire( 'Sorry...!', response.message, 'error' );
                    }else{
                        $(".salesLog").html(response.salesLog);
                        window.scrollTo(0, document.body.scrollHeight);
                        Swal.fire( 'Sent!', response.message, 'success' );
                    }	
                });
            
                
            }
        });
   }
</script>