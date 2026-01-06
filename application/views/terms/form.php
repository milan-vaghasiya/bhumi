<form>
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" value="<?= (!empty($dataRow->id)) ? $dataRow->id : ""; ?>" />

            <div class="col-md-12 form-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" class="form-control req" value="<?= (!empty($dataRow->title)) ? $dataRow->title : "" ?>">
            </div>

            <div class="col-md-6 form-group">
                <label for="type">Type</label>
                <select name="type" id="type" class="form-control modal-select2 req ">
                    <?php
                        foreach ($typeArray as $row) :
                            $selected = (!empty($dataRow->type) AND $row == $dataRow->type) ? "selected" : "";
                            echo '<option value="' . $row . '" ' . $selected . '>' . $row . '</option>';
                        endforeach;
                    ?>
                </select>
                <div class="error type"></div>
            </div>

            <div class="col-md-6 form-group">
                <label for="is_default">Is Default ?</label>
                <select name="is_default" id="is_default" class="form-control modal-select2">
                    <option value="0" <?=(!empty($dataRow->is_default) && $dataRow->is_default == 0)?"selected":""?>>No</option>
                    <option value="1" <?=(!empty($dataRow->is_default) && $dataRow->is_default == 1)?"selected":""?>>Yes</option>
                </select>
            </div>

            <div class="col-md-12 form-group">
                <label for="conditions">Conditions</label>
                <textarea name="conditions" id="conditions" class="form-control req" rows="2"><?= (!empty($dataRow->conditions)) ? $dataRow->conditions : "" ?></textarea>
            </div>
            
        </div>
    </div>
</form>
<script src="<?=base_url()?>assets/plugins/tinymce/tinymce.min.js"></script>
<script>
    $(document).ready(function(){
        initEditor({
            selector: '#conditions',
            height: 400
        });
    });
    
</script>