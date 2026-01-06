<form>
    <div class="col-md-12">
        <div class="row">     

            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id) ? $dataRow->id : "")?>" />

            <div class="col-md-12 form-group">
                <label for="name">Head Quarter Name</label>
                <input type="text" name="name" id="name" class="form-control req" value="<?=(!empty($dataRow->name) ? $dataRow->name : "")?>" />
            </div>

            <div class="col-md-6 form-group">
                <label for="hq_lat">Latitude</label>
                <input type="text" name="hq_lat" id="hq_lat" class="form-control req" value="<?=(!empty($dataRow->hq_lat) ? $dataRow->hq_lat : "")?>" />
            </div>

            <div class="col-md-6 form-group">
                <label for="hq_long">Longitude</label>
                <input type="text" name="hq_long" id="hq_long" class="form-control req" value="<?=(!empty($dataRow->hq_long) ? $dataRow->hq_long : "")?>" />
            </div>

			<div class="col-md-12 form-group">
                <label for="remark">Remark</label>
                <textarea name="remark" id="remark" class="form-control"><?=(!empty($dataRow->remark) ? $dataRow->remark : "")?></textarea>
            </div>
            
        </div>
    </div>
</form>
<script>
$(document).ready(function(){
    var lat = '';var lon = '';/*
    if (navigator.geolocation) { 
        navigator.geolocation.getCurrentPosition(function(position){
            lat = position.coords.latitude;
            lon = position.coords.longitude;
            var a = lat+','+lon;
            $('#hq_lat').val(lat);
            $('#hq_long').val(lon);
        });
    }*/
});
</script>