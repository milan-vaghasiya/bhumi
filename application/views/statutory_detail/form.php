<form>
    <div class="col-md-12">
        <div class="row">            
                
            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id) ? $dataRow->id : "")?>" />
            <input type="hidden" name="type" id="type" value="<?=(!empty($dataRow->type)) ? $dataRow->type : (!empty($type) ? $type : "")?>" />

            <?php if(!empty($type)): ?>

                <div class="col-md-12 form-group">
                    <label for="country_id">Country</label>
                    <select name="country_id" id="country_id" class="form-control country_list modal-select2 req" data-state_id="state_id" data-selected_state_id="<?=(!empty($dataRow->state_id))?$dataRow->state_id:4030?>">
                        <option value="">Select Country</option>
                        <?php foreach($countryData as $row):
                            $selected = (!empty($dataRow->country_id) && $dataRow->country_id == $row->id)?"selected":((empty($dataRow) && $row->id == 101)?"selected":"");

                        ?>
                            <option value="<?=$row->id?>" <?=$selected?>><?=$row->name?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <?php if($type == 'State'): ?>

                    <div class="col-md-12 form-group">
                        <label for="state">State</label>
                        <input type="text" name="state" id="state" class="form-control req" value="<?=(!empty($dataRow->state) ? $dataRow->state : "")?>" />
                    </div>

                    <div class="col-md-12 form-group">
                        <label for="state_code">State Code</label>
                        <input type="text" name="state_code" id="state_code" class="form-control req" value="<?=(!empty($dataRow->state_code) ? $dataRow->state_code : "")?>" />
                    </div>

                <?php else: ?>                    

                    <div class="col-md-12 form-group">
                        <label for="state">State</label>
                        <select name="state" id="state" class="form-control modal-select2 req">
                            <option value="">Select State</option>
                            <?php foreach($stateData as $row):
                                $selected = (!empty($dataRow->state) && $dataRow->state == $row->state)?"selected":"";
                            ?>
                                <option data-state_code="<?=$row->state_code?>" value="<?=$row->state?>" <?=$selected?>><?=$row->state?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="hidden" name="state_code" id="state_code" value="<?=(!empty($dataRow->state_code) ? $dataRow->state_code : "")?>">
                    </div>

                <?php endif; ?>

                <?php if($type == 'District'): ?>

                    <div class="col-md-12 form-group">
                        <label for="district">District</label>
                        <input type="text" name="district" id="district" class="form-control req" value="<?=(!empty($dataRow->district) ? $dataRow->district : "")?>" />
                    </div>

                <?php endif; ?>

                <?php if($type == 'Taluka'): ?>

                    <div class="col-md-12 form-group">
                        <label for="district">District</label>
                        <select name="district" id="district" class="form-control modal-select2 req">
                            <option value="">Select District</option>
                            <?php foreach($districtData as $row):
                                $selected = (!empty($dataRow->district) && $dataRow->district == $row->district)?"selected":"";
                            ?>
                                <option value="<?=$row->district?>" <?=$selected?>><?=$row->district?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-12 form-group">
                        <label for="taluka">Taluka</label>
                        <input type="text" name="taluka" id="taluka" class="form-control req" value="<?=(!empty($dataRow->taluka) ? $dataRow->taluka : "")?>" />
                    </div>

                <?php endif; ?>

            <?php else: ?>

                <div class="col-md-12 form-group">
                    <label for="name">Country</label>
                    <input type="text" name="name" id="name" class="form-control req" value="<?=(!empty($dataRow->name) ? $dataRow->name : "")?>" />
                </div>

            <?php endif; ?>

        </div>
    </div>
</form>
<script>
$(document).ready(function(){
    $(document).on('change','#state',function(){
        var type = $('#type').val();
        if(type){
            $('#state_code').val($('#state :selected').data('state_code'));
        }
    });
});
</script>