<form>
    <div class="col-md-12">
        <div class="row">            
            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id) ? $dataRow->id : "")?>" />
            <input type="hidden" name="trans_type" id="trans_type" value="2" />

            <div class="col-md-6 form-group">
				<label for="me_date">Event Date</label>
				<input type="date" name="me_date" id="me_date" class="form-control req" min="<?= date('Y-m-d')?>" value="<?=(!empty($dataRow->me_date))?$dataRow->me_date:date('Y-m-d')?>">
			</div>

            <div class="col-md-6 form-group">
                <label for="me_type">Event Type</label>
                <input type="text" name="me_type" id="me_type" class="form-control req" value="<?=(!empty($dataRow->me_type) ? $dataRow->me_type : "")?>" />
            </div>

            <div class="col-md-6 form-group">
                <label for="event_name">Event Name</label>
                <input type="text" name="event_name" id="event_name" class="form-control req" value="<?=(!empty($dataRow->event_name) ? $dataRow->event_name : "")?>" />
            </div>

            <div class="col-md-6 form-group">
                <label for="event_duration">Event Duration</label>
                <input type="text" name="event_duration" id="event_duration" class="form-control" value="<?=(!empty($dataRow->event_duration) ? $dataRow->event_duration : "")?>" />
            </div>

            <div class="col-md-12 form-group">
                <label for="location">Location</label>
                <input type="text" name="location" id="location" class="form-control" value="<?=(!empty($dataRow->location) ? $dataRow->location : "")?>" />
            </div>
            
            <div class="col-md-12 form-group">
                <label for="description">Description</label>
                <input type="text" name="description" id="description" class="form-control" value="<?=(!empty($dataRow->description) ? $dataRow->description : "")?>" />
            </div>
        </div>
    </div>
</form>