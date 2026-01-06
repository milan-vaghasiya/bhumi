<?php $this->load->view('includes/header'); ?>
<link href="<?=base_url()?>assets/plugins/tobii/tobii.min.css" rel="stylesheet" type="text/css" />
<div class="page-content-tab">
	<div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">				
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-8 form-group"><h5>CUSTOMER HISTORY REPORT</h5></div>
                            <div class="col-md-3 form-group">
                                <select id="party_id" name="party_id" class="form-control select2">
                                    <?php foreach($customerData as $row){echo '<option value="'.$row->id.'">'.$row->party_name.'</option>';} ?>
                                </select>
                            </div>
                            <div class="col-md-1 form-group">
                                <button type="button" class="btn waves-effect waves-light btn-success float-right loaddata" title="Load Data"><i class="fas fa-sync-alt"></i> Load</button>
                            </div>
                        </div>                                         
                    </div>
                </div>
            </div>
        </div>    
        
        <div class="row">                            
            <div class="col-lg-6">
                <div class="bg-white">
                    <div class="jp-list-body m-2" data-simplebar style="height:75vh;">
                        <div class="cd-detail slimscroll activity-scroll">
                            <div class="activity salesLogDiv">
                            </div>
                        </div> 
                    </div>
                </div>
            </div>   
            <div class="col-md-6">
                <div class="bg-white">
                    <div class="jp-list-body m-2" data-simplebar style="height:75vh;">
                        <div class="cd-detail slimscroll activity-scroll">
                            <div class="activity activityDiv">
                            </div>
                        </div> 
                    </div>
                </div>
            </div>    
        </div>

    </div>
</div>
<?php $this->load->view('includes/footer'); ?>
<script>
$(document).ready(function() {
    $(document).on('click','.loaddata',function(){
		$(".error").html("");
        var valid = 1;
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        var party_id = $('#party_id').val();
        if($("#from_date").val() == ""){$(".fromDate").html("From Date is required.");valid=0;}
        if($("#to_date").val() == ""){$(".toDate").html("To Date is required.");valid=0;}
        if($("#to_date").val() < $("#from_date").val()){$(".toDate").html("Invalid Date.");valid=0;}

        var postData= {from_date:from_date, to_date:to_date, party_id:party_id};
        if(valid){
            $.ajax({
            url: base_url + controller + '/getCustomerHistory',
            data: postData,
            type: "POST",
            dataType:'json',
                success:function(data){
                    $(".salesLogDiv").html("");
                    $(".activityDiv").html("");
                    $(".salesLogDiv").html(data.html);
                    $(".activityDiv").html(data.html2);
                }
            });
        }
	}); 
});
</script>