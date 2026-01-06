<form>
    <div class="col-md-12">
        <div class="row">

            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""; ?>" />
                
            <div class="col-md-12 form-group">
                <label for="skill_name">Skill Name</label>
                <input type="text" name="skill_name" id="skill_name" class="form-control req" value="<?=(!empty($dataRow->skill_name))?$dataRow->skill_name:""; ?>" />
            </div>
           
        </div>
    </div>
</form>