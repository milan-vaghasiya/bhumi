<form data-res_function="getOtherPartyHtml">
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" id="id" value="" />			
            <input type="hidden" name="ref_id" id="ref_id" value="<?=$ref_id?>" />			

            <div class="col-md-12 form-group">
                <label for="party_name">Party Name</label>
                <input type="text" name="party_name" id="party_name" class="form-control req" value="">
            </div>

            <div class="col-md-6 form-group">
                <label for="party_contact">Contact No.</label>
                <input type="text" name="party_contact" id="party_contact" class="form-control numericOnly req" value="">
            </div>

            <div class="col-md-6 form-group">
                <label for="party_type">Type</label>
                <select name="party_type" id="party_type" class="form-control modal-select2 req">
                    <option value="1">Customer</option>
                    <option value="2">Plumber</option>
                </select>
            </div>

            <div class="col-md-12 form-group">
                <?php
                    $param = "{'formId':'addOtherParty','fnsave':'saveOtherParty','controller':'parties','res_function':'getOtherPartyHtml'}";
                ?>
                <button type="button" class="btn waves-effect waves-light btn-outline-success btn-save save-form float-right" onclick="customStore(<?=$param?>)" style="height:36px"><i class="fa fa-check"></i> Save</button>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="table-responsive">
            <table id="otherPartyTbl" class="table table-bordered align-items-center">
                <thead class="thead-info">
                    <tr>
                        <th style="width:5%;">#</th>
                        <th>Party Name</th>
                        <th>Contact No.</th>
                        <th>Type</th>
                        <th class="text-center" style="width:10%;">Action</th>
                    </tr>
                </thead>
                <tbody id="otherParties">
                </tbody>
            </table>
        </div>        
    </div>
</form>
<script>
var tbodyData = false;
$(document).ready(function(){
    if(!tbodyData){
        var postData = {'postData':{'ref_id':$("#ref_id").val()},'table_id':"otherPartyTbl",'tbody_id':'otherParties','tfoot_id':'','fnget':'otherPartiesHtml'};
        getTransHtml(postData);
        tbodyData = true;
    }
});
function getOtherPartyHtml(data,formId="addOtherParty"){ 
    if(data.status==1){
        $('#'+formId)[0].reset();
        var postData = {'postData':{'ref_id':$("#ref_id").val()},'table_id':"otherPartyTbl",'tbody_id':'otherParties','tfoot_id':'','fnget':'otherPartiesHtml'};
        getTransHtml(postData);
    }else{
        if(typeof data.message === "object"){
            $(".error").html("");
            $.each( data.message, function( key, value ) {$("."+key).html(value);});
        }else{
            Swal.fire({ icon: 'error', title: data.message });
        }			
    }	
}
</script>