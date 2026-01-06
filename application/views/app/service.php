<?php $this->load->view('app/includes/header'); ?>
<?php $this->load->view('app/includes/topbar'); ?>
    
<div class="page-content">
    <div class="content-inner pt-0">
        <div class="container bottom-content">
            
            <form>
                <div class="input-group cd-search" id="qs">
                    <input type="text" class="form-control  qs quicksearch" id="cd-search" name="cd-search" placeholder="Search..">
                    <span class="input-group-text"> 
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M23.7871 22.7761L17.9548 16.9437C19.5193 15.145 20.4665 12.7982 20.4665 10.2333C20.4665 4.58714 15.8741 0 10.2333 0C4.58714 0 0 4.59246 0 10.2333C0 15.8741 4.59246 20.4665 10.2333 20.4665C12.7982 20.4665 15.145 19.5193 16.9437 17.9548L22.7761 23.7871C22.9144 23.9255 23.1007 24 23.2816 24C23.4625 24 23.6488 23.9308 23.7871 23.7871C24.0639 23.5104 24.0639 23.0528 23.7871 22.7761ZM1.43149 10.2333C1.43149 5.38004 5.38004 1.43681 10.2279 1.43681C15.0812 1.43681 19.0244 5.38537 19.0244 10.2333C19.0244 15.0812 15.0812 19.035 10.2279 19.035C5.38004 19.035 1.43149 15.0865 1.43149 10.2333Z" fill="var(--primary)"/>
                        </svg>
                    </span>
                </div>
            </form>
            
            <div class="dz-tab style-4">
                <div class="tab-slide-effect">
                    <ul class="nav nav-tabs"  role="tablist" >
                        <li class="tab-active-indicator " style="width: 108.391px; transform: translateX(177.625px);"></li>
                        <li class="nav-item   active" role="presentation">
                            <button class="nav-link buttonFilter active" id="home-tab"  data-filter=".pending_req" data-bs-toggle="tab" data-bs-target="#pending_req" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="false" tabindex="-1">Pending</button>
                        </li>
                        <li class="nav-item " role="presentation">
                            <button class="nav-link buttonFilter" id="home-tab" data-bs-toggle="tab" data-filter=".complete_req" data-bs-target="#complete_req" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="false" tabindex="-1">Completed</button>
                        </li>
                    </ul>
                </div>    
                <div class="tab-content px-0 list-grid" id="myTabContent1"  data-isotope='{ "itemSelector": ".listItem" }'>
                    <div class="tab-pane fade active show" id="pending_req" role="tabpanel" aria-labelledby="home-tab" tabindex="0" >
                        <ul class="list-grid" data-isotope='{ "itemSelector": ".listItem" }'>
                            <?=$reqList['pendingReq']?>
                        </ul>
                    </div>
                    <div class="tab-pane fade " id="complete_req" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                        <ul class="list-grid" data-isotope='{ "itemSelector": ".listItem" }'>
                            <?=$reqList['completeReq']?>
                        </ul>
                    </div>
                </div>
            </div>                     
            
        </div>    
    </div>
</div>    

<div class="offcanvas offcanvas-bottom m-3 rounded" tabindex="-1" id="closeModel" aria-modal="true" role="dialog">
    <div class="offcanvas-body small">
        <form id="closeReq">
            <div class="card">
                <div class="card-body">

                    <input type="hidden" name="id" id="id" value="" />

                    <div class="mb-3">
                        <label for="solution_type" class="form-label">Description</label>
                        <select name="solution_type" id="solution_type" class="form-control">
                            <option value="Paid">Paid</option>
                            <option value="FOC">FOC</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category</label>
                        <select name="category_id" id="category_id" class="form-control">
                            <option value="">Select Category</option>
                            <?php
                            if(!empty($categoryData)){
                                foreach($categoryData as $row){
                                    echo '<option value="'.$row->id.'">'.$row->service_name.'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="solution_desc" class="form-label">Description</label>
                        <textarea name="solution_desc" id="solution_desc" class="form-control req" rows="2"></textarea>
                    </div>

                    <div class="">
                        <button type="button" class="btn btn-success btn-round btn-outline-dashed btn-block saveCloseReq btn-save">Save</button>
                    </div>

                </div>        
            </div>
        </form>
    </div>
</div>

<?php $this->load->view('app/includes/bottom_menu'); ?>
<?php $this->load->view('app/includes/footer'); ?>

<script src="<?=base_url()?>assets/plugins/isotop/isotope.pkgd.min.js"></script>
<script>
var qsRegex;
var isoOptions ={};
var $grid = '';
$(document).ready(function(){
    setPlaceHolder();

    initISOTOP();
    var $qs = $('.quicksearch').keyup( debounce( function() {qsRegex = new RegExp( $qs.val(), 'gi' );$grid.isotope();}, 200 ) );

	$(document).on( 'click', '.buttonFilter', function() {
		initISOTOP();
	});

    $(document).on('click','.saveCloseReq',function(){
        var formId = "closeReq";
        var form = $('#'+formId)[0];
        var fd = new FormData(form);
        $(".btn-save").attr("disabled", true);
        $.ajax({
            url: base_url + controller + '/saveCloseReq',
            data:fd,
            type: "POST",
            global:false,
            processData:false,
            contentType:false,
            dataType:"json",
        }).done(function(response){
            $(".btn-save").removeAttr("disabled");
            if(response.status==1)
            {
                $('#'+formId)[0].reset(); $('#closeModel').removeClass('show');
                Swal.fire({ icon: 'success', title: response.message});
                window.location.reload();
            }
            else{
                if(typeof response.message === "object"){
                    $(".error").html("");
                    $.each( response.message, function( key, value ) {$("."+key).html(value);});
                }else{
                    Swal.fire({ icon: 'error', title: response.message });
                }	
            }
            window.scrollTo(0, document.body.scrollHeight);
        });
    });

    $(document).on('click','.closeBtn',function(){
        var id = $(this).data('id');
        $("#id").val(id);
    });

});

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

function initISOTOP(){
    var isoOptions = {
		itemSelector: 'tab-pane active .listItem',
		layoutMode: 'fitRows',
		filter: function() {return qsRegex ? $(this).text().match( qsRegex ) : true;}
	};
    $('.listItem').css('position', '');
	// init isotope
	$grid = $('.list-grid').isotope( isoOptions );
}
</script>