<form id="addMasterOption">
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""; ?>" />
            <input type="hidden" name="type" id="type" value="<?=(!empty($dataRow->type))?$dataRow->type:$type; ?>" />
			
            <div class="col-md-9 form-group">
                <label for="title">Options</label>
                <input type="text" name="title" id="title" class="form-control req" value="<?=(!empty($dataRow->title) ? $dataRow->title : "")?>">
            </div>
			<div class="col-md-3 form-group">
				<button type="button" class="btn btn-success btn-save save-form btn-block mt-4" onclick="saveOptions({'formId':'addMasterOption','controller':'masterOption','fnsave':'save'});"><i class="fa fa-check"></i> Save</button>
			</div>
        </div>
		<hr>
		<div class="row">
			<table class="table table-bordered">
				<thead class="thead-info"><tr><th>#</th><th>Option</th><th>Action</th></tr></thead>
				<tbody class="optionRows"><?=$optionRows?></tbody>
			</table>
		</div>
    </div>
</form>
<script>

function saveOptions(postData){
	setPlaceHolder();

	var formId = postData.formId;
	var form = $('#'+formId)[0];
	var fd = new FormData(form);
	$.ajax({
		url: base_url + controller + '/save',
		data:fd,
		type: "POST",
		processData:false,
		contentType:false,
		dataType:"json",
	}).done(function(data){
		if(data.status==1){
			$('#title').val('');
			$(".optionRows").html(data.optionRows);
			Swal.fire({ icon: 'success', title: data.message});
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
function removeOptions(data){
	var msg = "Record";
	var send_data = data.postData;
	
	Swal.fire({
		title: 'Are you sure?',
		text: "You won't be able to revert this!",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Yes, delete it!',
	}).then(function(result) {
		if (result.isConfirmed)
		{
			$.ajax({
				url: base_url + controller + '/delete',
				data: send_data,
				type: "POST",
				dataType:"json",
			}).done(function(response){
				if(response.status==0){
					Swal.fire( 'Sorry...!', response.message, 'error' );
				}else{
					$(".optionRows").html(response.optionRows);
				}
			});
			Swal.fire( 'Deleted!', 'Your Record has been deleted.', 'success' );
		}
	});
	
}
</script>