<form>
    <div class="col-md-12">
        <div class="row">
            
            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""; ?>" />
			
            <div class="col-md-12 form-group">
                <label for="service_name">Service Name</label>                    
                <input type="text" name="service_name" id="service_name" class="form-control req" value="<?=(!empty($dataRow->service_name)?$dataRow->service_name:'')?>" />
            </div>

        </div>        
    </div>
</form>
