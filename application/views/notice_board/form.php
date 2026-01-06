<form>
    <div class="col-md-12">
        <div class="row">     

            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id) ? $dataRow->id : "")?>" />

            <div class="col-md-12 form-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" class="form-control req" value="<?=(!empty($dataRow->title) ? $dataRow->title : "")?>" />
            </div>
			
			<div class="col-md-12 form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control req" ><?=(!empty($dataRow->description) ? $dataRow->description : "")?></textarea>
            </div>

            <div class="col-md-4 form-group">
                <label for="from_date">From Date</label>
                <input type="date" name="from_date" id="from_date" class="form-control req" value="<?=(!empty($dataRow->from_date) ? $dataRow->from_date : date("Y-m-d"))?>" />
            </div>

            <div class="col-md-4 form-group">
                <label for="to_date">To Date</label>
                <input type="date" name="to_date" id="to_date" class="form-control req" value="<?=(!empty($dataRow->to_date) ? $dataRow->to_date : date("Y-m-d"))?>" />
            </div>

             <div class="col-md-4 form-group">
                <label for="reminder_days">Reminder Days</label>
                <input type="text" name="reminder_days" id="reminder_days" class="form-control" value="<?=(!empty($dataRow->reminder_days) ? $dataRow->reminder_days : "")?>" />
            </div>

        </div>
    </div>
</form>