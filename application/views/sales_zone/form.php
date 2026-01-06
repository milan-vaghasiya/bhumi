<form>
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""?>">
            <input type="hidden" id="statutory_id" value="<?=(!empty($dataRow->statutory_id))?$dataRow->statutory_id:""?>">

            <div class="col-md-3 form-group">
                <label for="type">Zone Type</label>
                <select name="type" id="type" class="form-control">
                    <?php
                    foreach($zoneTypeArray as $key=>$type){
                        $selected = (!empty($dataRow->type) && $dataRow->type == $key)?'selected':'';
                        ?> <option value="<?=$key?>" <?=$selected?>><?=$type?></option> <?php
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-9 form-group">
                <label for="zone_name">Zone Name</label>
                <input type="text" name="zone_name" id="zone_name" class="form-control req" value="<?=(!empty($dataRow->zone_name))?$dataRow->zone_name:""?>">
            </div>

           
            <div class="col-md-12 form-group">
                <label for="remark">Remark</label>
                <input type="text" name="remark" id="remark" class="form-control" value="<?=(!empty($dataRow->remark))?$dataRow->remark:""?>">
            </div>
        </div>
    </div>
</form>
