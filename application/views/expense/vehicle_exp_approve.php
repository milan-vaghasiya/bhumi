

<form enctype='multipart/form-data'>
    <div class="col-md-12">
        <div class="row">            
            <input type="hidden" name="emp_id" id="emp_id" value="<?=(!empty($emp_id) ? $emp_id :"")?>" />
            <input type="hidden" name="log_time" id="log_time" value="<?=(!empty($log_time) ? $log_time :"")?>" />
            <input type="hidden" name="vehicle_id" id="vehicle_id" value="<?=(!empty($vehicle_id) ? $vehicle_id :"")?>" />
            <div class="col-md-12 form-group">
                <div class="error"></div>
                <div class="table-responsive">
                    <table id="expenceTypeData" class="table table-striped table-borderless">
                        <thead class="thead-info">
                            <tr>
                                <th style="width:10%;">#</th>
                                <th style="width:30%;">Date</th>
                                <th style="width:20%;">Address</th>
                                <th style="width:20%;">Travel Km</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyData">
                            <?php
                                if(!empty($expData)){
                                    $i=1;
                                    foreach($expData as $row){
                                        echo "<tr>"; 
                                        echo "<td>".$i."</td>";
                                        echo "<td>".date('d-m-Y H:i:s',strtotime($row->log_time))."</td>";
                                        echo "<td>".$row->address."</td>";
                                        echo "<td>
                                                <input type='hidden' name='expData[".$i."][id]' value='".$row->id."' />
                                                <input type='number' name='expData[".$i."][travel_km]' class='form-control floatOnly' value='".$row->travel_km."' />
                                                <input type='hidden' name='expData[".$i."][price_km]' value='".$row->price_km."' />
                                            </td>";
                                        echo "</tr>";
                                        $i++;
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
             <div class="col-md-12 form-group">
                <label for="notes">Remark</label>
                <textarea name="notes" id="notes" class="form-control" rows="1"></textarea>
            </div>
        </div>
    </div>
   
</form>