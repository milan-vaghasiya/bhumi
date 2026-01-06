<?php $this->load->view('includes/header'); ?>
<div class="page-content-tab">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="page-title-box">
					<div class="float-end">
						<button type="button" class="btn btn-info btn-sm float-right addNew permission-write press-add-btn" data-button="both" data-modal_id="right_modal" data-function="addFinishGoods" data-form_title="Add <?=$this->itemTypes[$item_type]?>" data-postdata='{"item_type" : <?=$item_type?> }' ><i class="fa fa-plus"></i> Add <?=$this->itemTypes[$item_type]?></button>
					</div>
					<h4 class="page-title "><?=$this->itemTypes[$item_type]?></h4>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table id='finishGoodsTable' class="table table-bordered ssTable" data-url='/getDTRows/<?=$item_type?>'></table>
						</div>
					</div>
				</div>
			</div>
		</div>
    </div>
</div>

<?php $this->load->view('includes/footer'); ?>
<script>
$(document).ready(function(){
    $(document).on('click','.viewItemProcess',function(){
        var id = $(this).data('id');
        var itemName = $(this).data('product_name');
        var functionName = $(this).data("function");
        var modalId = $(this).data('modal_id');
        var button = $(this).data('button');
		var title = $(this).data('form_title');
		var formId = functionName;
        $.ajax({ 
            type: "POST",   
            url: base_url + controller + '/' + functionName,   
            data: {id:id}
        }).done(function(response){
            $("#"+modalId).modal();
			$("#"+modalId+' .modal-title').html(title + " [ Product : "+itemName+" ]");
            $("#"+modalId+' .modal-body').html(response);
            if(button == "close"){
                $("#"+modalId+" .modal-footer .btn-close").show();
                $("#"+modalId+" .modal-footer .btn-save").hide();
            }else if(button == "save"){
                $("#"+modalId+" .modal-footer .btn-close").hide();
                $("#"+modalId+" .modal-footer .btn-save").show();
            }else{
                $("#"+modalId+" .modal-footer .btn-close").show();
                $("#"+modalId+" .modal-footer .btn-save").show();
            }  
            $("#itemProcess tbody").sortable({ 
                items: 'tr',
                cursor: 'pointer',
                axis: 'y',
                dropOnEmpty: false,
                helper: fixWidthHelper,
                start: function (e, ui) {
                    ui.item.addClass("selected");
                },
                stop: function (e, ui) {
                    ui.item.removeClass("selected");
                    $(this).find("tr").each(function (index) {
                        $(this).find("td").eq(2).html(index+1);
                    });
                },
                update: function () 
                {
                    var ids='';
                    $(this).find("tr").each(function (index) {ids += $(this).attr("id")+",";});
                    var lastChar = ids.slice(-1);
                    if (lastChar == ',') {ids = ids.slice(0, -1);}
                    
                    $.ajax({
                        url: base_url + controller + '/updateProductProcessSequance',
                        type:'post',
                        data:{id:ids},
                        dataType:'json',
                        global:false,
                        success:function(data){}
                    });
                }
            });             
        });		
	}); 

    $(document).on('change','#unit_id',function(){
		$("#unit_name").val("");
		if($(this).val()){ $("#unit_name").val($("#unit_id :selected").data('unit')); }		
	});
}); 

function fixWidthHelper(e, ui) {
    ui.children().each(function() {
        $(this).width($(this).width());
    });
    return ui;
}
</script>