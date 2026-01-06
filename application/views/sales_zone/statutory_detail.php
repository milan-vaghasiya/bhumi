<form>
   <div class="error general_error"></div>
    <div class="row">
        <input type="hidden" id="zone_type" name="type" value="<?=$dataRow['type']?>">
        <input type="hidden" id="id" name="id" value="<?=$dataRow['id']?>">
        <input type="hidden" id="statutory_id" value="<?=$dataRow['statutory_id']?>">
        <!-- 08-04-2024 -->
        <input type="hidden" name="zone_name" id="zone_name" value="<?=$dataRow['zone_name']?>">
       
        <?php
        if(($dataRow['type'] == 3)){
            ?>
            <div class="col-md-12">
                <input type="hidden" id="state_id" name="state_id"  value="<?=$dataRow['state_id']?>">
                <div class="crm-desk-left">
                    <div class="cd-body-left" data-simplebar>
                        <div class="cd-list jpPanel-widget">
                            <h6>State List</h6>
                            <div class="card-body ">
                                <div class="cd-search mb-1 ">
                                    <div class="form-group row"> 
                                        <div class="jpsearch col-md-12" id="qs">                                                
                                            <input type="text" id="quick_search" class="form-control qs quicksearch" placeholder="Search Here...">
                                        </div>   
                                                                                        
                                    </div>
                                </div>
                                <ul class="list-group grid">   
                                    <li class="list-group-item"  style="width:100%">
                                        <input type="checkbox" id="master_checkbox" class="filled-in chk-col-success" value="true">
                                        <label for="master_checkbox" class="mr-3"><a href="javascript:void(0)" class="mt-0 fs-13 fw-bold " >Check All</a></label>
                                    </li>     
                                <?php
                                if(!empty($stateList)){
                                    $statutoryArray = !empty($dataRow['statutory_id'])?explode(",",$dataRow['statutory_id']):[];
                                    foreach($stateList as $row){ ?>
                                        <li class="list-group-item"  style="width:100%">
                                            <?php
                                            if($dataRow['type'] == 3){
                                                $checked = (!empty($statutoryArray) && in_array($row->id,$statutoryArray))?'checked':'';
                                                ?>
                                                <input type="checkbox" id="statutory_id<?=$row->id?>" name="statutory_id[]" class="filled-in chk-col-success stateCheck" <?=$checked?>  value="<?=$row->id?>"><label for="statutory_id<?=$row->id?>" class="mr-0"> <a href="javascript:void(0)" class="mt-0 fs-13 stateName fw-bold " data-state="<?=$row->state?>"><?=$row->state?></a></label>
                                                <?php
                                            }
                                            ?>
                                           
                                        </li>
                            <?php   }
                                }
                                ?>
                                </ul>
                            </div>
                        </div>
                    </div>  
                </div>
            </div>
            <?php
        }else{?>
            <div class="col-lg-12">
                <div class="crm-desk-right" >
                    <div class="cd-header">
                        <a href="javascript:void(0)" class="media">
                            <div class="media-body">
                                <div class="row">
                                    <select name="state_id" id="state_id" class="form-control modal-select2">
                                        <option value="">Select</option>
                                        <?php
                                        foreach($stateList as $row){ 
                                            $selected = (!empty($dataRow['state_id']) && $dataRow['state_id'] == $row->state)?'selected':'';
                                            ?>
                                            <option value="<?=$row->state?>" <?=$selected?>><?=$row->state?></option>
                                <?php   }
                                        ?>
                                    </select>
                                </div>
                               
                            </div>
                        </a>
                    </div>
                    <div class="cd-body cd_body" id="cd_body" data-simplebar style="overflow1:scroll;" >
                        <div class="cd-search mb-1 ">
                            <div class="form-group row"> 
                                <div class="jpsearch col-md-12" id="qs">                                                
                                    <input type="text" id="quick_search" class="form-control qs quicksearch" placeholder="Search Here...">
                                </div>   
                                                                                
                            </div>
                        </div>
                        <h6 class="m-1 " id="state_name">Distict List</h6>
                        <div class="activity-scroll" >
                            <ul class="list-group districtList grid" >        
                                <?=!empty($html)?$html:''?>
                            </ul>
                        </div>  
                                                                   
                    </div>
                    <?php
                    if($dataRow['type'] == 5){
                        ?>
                        <div class="cd-footer">
                            <button type="button" class="btn btn-success getTaluka text-right float-right">Load</button>
                        </div> 
                        <?php
                    }
                    ?>
                    
                </div>
            </div>
            <?php
        }
        ?>
        
    </div>
    
</form>

<script>
    // quick search regex
var buttonFilter;
var qsRegex;
var isoOptions ={};
var $grid = '';
$(document).ready(function(){
    var $qs = $('.quicksearch').keyup( debounce( function() {qsRegex = new RegExp( $qs.val(), 'gi' );initISOTOP();}, 200 ) );
  
    setTimeout(function(){ initISOTOP(); }, 200);
    function searchItems(ele){
        console.log($(ele).val());
    }

    function debounce( fn, threshold ) {
        var timeout;
        threshold = threshold || 100;
        return function debounced() {
            clearTimeout( timeout );
            var args = arguments;
            var _this = this;
            
            function delayed() {fn.apply( _this, args );}
            timeout = setTimeout( delayed, threshold );
        };
    } 
   

    $(document).on('click','.addTaluka',function(){
        var talukaArray = $("input[name='taluka_name[]']").map(function(){
            if($(this).prop("checked") == true){  return $(this).val();  }
        }).get();
        var lebelArray = $("input[name='taluka_name[]']").map(function(){
            if($(this).prop("checked") == true){  return  '<span class="badge badge-outline-primary">'+$(this).data('taluka')+'</span>';  }
        }).get();
        if(talukaArray != ""){
            var talukaName = talukaArray.join(",");
            var oldStatutoryId = $("#statutoryId").val();
            if(oldStatutoryId == ''){
                $("#statutoryId").val(talukaName);
            }else{
                $("#statutoryId").val(oldStatutoryId+','+talukaName);
            }
            $(".talukaLabels").append(" "+lebelArray.join(" "));
            $("#talukaModal").modal('hide');  

        }

    });

    
    $(document).on('click','#master_checkbox',function(){
        if($(this).prop('checked') == true){
            $(".stateCheck").prop('checked', true);  
        }else{
            $(".stateCheck").prop('checked', false);
        }
    });

});
function initISOTOP(){
	isoOptions = {
		itemSelector: '.list-group-item',
		percentPosition: true,
		layoutMode: 'fitRows',
		filter: function() {
			var $this = $(this);
			var searchResult = qsRegex ? $this.text().match( qsRegex ) : true;
			var buttonResult = buttonFilter ? $this.is( buttonFilter ) : true;
			return searchResult && buttonResult;
		}
	};
	// init isotope
	var $grid = $('.grid').isotope( isoOptions );
}

</script>