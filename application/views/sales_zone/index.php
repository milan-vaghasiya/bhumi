<?php $this->load->view('includes/header'); ?>
<div class="page-content-tab">
	<div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
				<div class="page-title-box">
					<div class="float-end">
                        <button type="button" class="btn btn-info btn-sm float-right addNew press-add-btn permission-write" data-button="both" data-modal_id="right_modal" data-function="addSalesZone" data-form_title="Add Sales Zone"><i class="fa fa-plus"></i> Add Sales Zone</button>
					</div>
                    <h4 class="card-title">Sales Zone</h4>
				</div>
            </div>
		</div>
        <div class="row">
            <div class="col-12">
				<div class="col-12">
					<div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id='salesZoneTable' class="table table-bordered ssTable" data-url='/getDTRows'>
                                </table>
                            </div>
                        </div>
					</div>
				</div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="talukaModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title m-0" id="districtTitle"></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body"  >
                <h6>Taluka List</h6>
                <form id="talukaForm">
                    <input type="hidden" id="tal_state_id" name="state_id">
                    <input type="hidden" id="tal_id" name="id">
                    <input type="hidden" id="type" name="type" value="5">
                    <div class="error general_error"></div>
                    <div data-simplebar>
                        <ul class="list-group" id="talukaList">        
                            
                        </ul>
                    </div>
                </form>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary press-close-btn " data-bs-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                <button type="button" class="btn btn-success btn-save save-form addTaluka"><i class="fa fa-check"></i> Save</button>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('includes/footer'); ?>
<script src="<?=base_url()?>assets/plugins/isotop/isotope.pkgd.min.js"></script>
<script>
     $(document).on('change','#state_id',function(){
        var state = $(this).val();
        var statutory_id = $("#statutory_id").val() || 0;
        var type = $("#zone_type").val();
        console.log(type);
        if(type != 3){
            $.ajax({
                url: base_url + 'salesZone/getDistrictList',
                type:'post',
                data:{type:type,statutory_id:statutory_id,state:state},
                dataType:'json',
                success:function(data){
                    $('.grid').isotope('destroy');
                    $(".districtList").html(data.html);
                    initISOTOP();
                
                }
            });
        }
        
    });
    $(document).on('click','.getTaluka',function(){
        var state = $("#state_id").val();
        var id = $("#id").val();
        var statutory_id = $("#statutory_id").val() || 0;

        var district = $("input[name='district[]']").map(function(){
            if($(this).prop("checked") == true){  return "'"+$(this).val()+"'";  }
        }).get();
        $("#talukaModal").modal('show');
        $("#talukaModal").css({'z-index':9999,'overflow':'auto'});
		$("#talukaModal").addClass("talukaFormModal");
        $("#talukaModal .modal-title").html(district.join(", "));
        $("#talukaModal .modal-body #tal_state_id").val(state);
        $("#talukaModal .modal-body #tal_id").val(id);
        var fnJson = "{'formId':'talukaForm','fnsave':'saveStatutoryDetail','formId':'talukaForm'}";
        $("#talukaModal .addTaluka").attr('onclick',"storeTaluka("+fnJson+");");

        $.ajax({
            url: base_url + 'salesZone/getTalukaList',
            type:'post',
            data:{district:district,statutory_id:statutory_id,state:state},
            dataType:'json',
            success:function(data){
                $("#talukaList").html(data.html);
            }
        });
    });

    function storeTaluka(postData){
        setPlaceHolder();
            
        var formId = postData.formId;
        var fnsave = postData.fnsave || "save";
        var controllerName = postData.controller || controller;

        var form = $('#'+formId)[0];
        var fd = new FormData(form);
        $.ajax({
            url: base_url + controllerName + '/' + fnsave,
            data:fd,
            type: "POST",
            processData:false,
            contentType:false,
            dataType:"json",
        }).done(function(data){
            if(data.status==1){
                initTable(); $('#'+formId)[0].reset(); 
                $(".modal").modal('hide');
                Swal.fire({ icon: 'success', title: data.message});
                /*toastr.success(data.message, 'Success', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });*/
            }else{
                if(typeof data.message === "object"){
                    $(".error").html("");
                    $.each( data.message, function( key, value ) {$("."+key).html(value);});
                }else{
                    Swal.fire({ icon: 'error', title: data.message });
                }			
            }				
        });
    }


    
</script>