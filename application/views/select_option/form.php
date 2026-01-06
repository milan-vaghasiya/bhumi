<form>
    <div class="col-md-12">
        <div class="row">            
            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id) ? $dataRow->id : "")?>" />
            <input type="hidden" name="type" id="type" value="<?=(!empty($dataRow->type) ? $dataRow->type : $type)?>" />
            <?php $type = (!empty($dataRow->type) ? $dataRow->type : $type); ?>
            <div class="col-md-12 form-group">
                <label for="label">Option</label>
                <input type="text" name="label" id="label" class="form-control req" value="<?=(!empty($dataRow->label) ? $dataRow->label : "")?>" />
            </div>
             <?php if($type == 5){?>
                <div class="col-md-12 form-group ">
                    <label for="price_km"> Rupees/K.M</label>
                    <input type="text" name="price_km" id="price_km" class="form-control" value="<?=(!empty($dataRow->price_km) ? $dataRow->price_km : "")?>" />
                </div> 
            <?php }else{ ?>
			<div class="col-md-12 form-group">
                <label for="remark">Remark</label>
                <textarea name="remark" id="remark" class="form-control"><?=(!empty($dataRow->remark) ? $dataRow->remark : "")?></textarea>
            </div>
            <?php }?>
        </div>
    </div>
</form>
