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
						<h5 class="title mb-0 text-nowrap"><?=$routeData->route_number?></h5>
					</div>
					<div class="mid-content">

					</div>
					<div class="right-content">
				    </div>
				</div>
			</div>
		</div>
	</header>
 <!-- Page content  -->
 <div class="page-content message-content bg-white">
        <div class="container chat-box-area bottom-content routeLog bg-white" > 
           <?=$routeLog?>
        </div>  
    </div>
    <!-- Page content End -->
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
       
    });
    function logStatusChange(data){
        var button = data.button;if(button == "" || button == null){button="both";};
        var fnedit = data.fnedit;if(fnedit == "" || fnedit == null){fnedit="edit";}
        var fnsave = data.fnsave;if(fnsave == "" || fnsave == null){fnsave="save";}
        var formId = data.formId;if(formId == "" || formId == null){formId="logStatus";}
        var title = data.title;if(title == "" || title == null){formId="Change Status";}

        $.ajax({ 
            type: "POST",   
            url: base_url + controller + '/logStatusChange',   
            data: data.postData,
        }).done(function(response){
            $("#"+data.modal_id).css({'z-index':1051});
            $('#'+data.modal_id+' .modal-title').html(title);
            $('#'+data.modal_id+' .modal-body').html(response);
            $("#"+data.modal_id+" .modal-body form").attr('id',formId);
            $("#"+data.modal_id+" .modal-footer .btn-save").attr('onclick',"changeRouteStatue('"+formId+"','"+fnsave+"','"+data.modal_id+"');");
            $('#'+data.modal_id).modal('show');
            $(".select2").select2();
            setPlaceHolder();
        });
    }

    function changeRouteStatue(formId,fnsave,modal_id){
        setPlaceHolder();
        var form = $('#'+formId)[0];
	    var fd = new FormData(form);
        $.ajax({
            url: base_url + controller + '/' + fnsave,
            data:fd,
            type: "POST",
            processData:false,
            contentType:false,
            dataType:"json",
        }).done(function(response){
            if(response.status==1){
                $('#'+formId)[0].reset(); 
                $("#"+modal_id+' .modal-body').html("");
                $("#"+modal_id).modal('hide');	
                $(".modal").css({'overflow':'auto'});
                Swal.fire({ icon: 'success', title: response.message});
                $(".routeLog").html(response.routeLog);
            }else{
                if(typeof response.message === "object"){
                    $(".error").html("");
                    $.each( response.message, function( key, value ) {$("."+key).html(value);});
                }else{
                    Swal.fire({ icon: 'error', title: response.message });
                }			
            }				
        });
    }
</script>