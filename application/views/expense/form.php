<form enctype='multipart/form-data'>
    <div class="col-md-12">
        <div class="row">            
            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id) ? $dataRow->id : "")?>" />
            <div class="col-md-4 form-group">
                <label for="exp_number">Expense No.</label>
                <input type="text" name="exp_number" id="exp_number" class="form-control req" value="<?=(!empty($dataRow->exp_number) ? $dataRow->exp_number : $exp_prefix.sprintf("%03d",$exp_no))?>" readOnly />
                <input type="hidden" name="exp_prefix" id="exp_prefix" value="<?=(!empty($dataRow->exp_prefix)) ? $dataRow->exp_prefix : $exp_prefix?>" />
                <input type="hidden" name="exp_no" id="exp_no" value="<?=(!empty($dataRow->exp_no)) ? $dataRow->exp_no : $exp_no?>" />
            </div>
            <div class="col-md-8 form-group">
                <label for="exp_by_id">Employee</label>
                <select name="exp_by_id" id="exp_by_id" class="form-control modal-select2 req">
                    <option value="">Select Employee</option>
                    <?php
                        if(!empty($options)){
                            echo $options;
                        }else{
                            foreach($empList as $row){
                                $emp_name = (!empty($row->emp_code)) ? '['.$row->emp_code.'] '.$row->emp_name : $row->emp_name;
                                echo '<option value="'.$row->id.'" '.$selected.'>'.$emp_name.'</option>';
                            }
                        }
                    ?>
                </select>
            </div>

            <div class="col-md-4 form-group">
                <label for="exp_date">Exp Date</label>
                <input type="date" name="exp_date" id="exp_date" max="<?=date('Y-m-d')?>" class="form-control req" value="<?=(!empty($dataRow->exp_date) ? $dataRow->exp_date : date('Y-m-d'))?>" />
            </div>

            <div class="col-md-8 form-group">
                <label for="proof_file">File Upload</label>
                <div class="input-group">
                    <input type="file" name="proof_file[]" class="form-control" style="width:<?=(!empty($dataRow->proof_file)) ? "75%" : "" ?>" multiple="multiple" />
                </div>
            </div>
            <div class="col-md-12 form-group">
                <label for="notes">Description</label>
                <textarea name="notes" id="notes" class="form-control" rows="2"><?=(!empty($dataRow->notes) ? $dataRow->notes : "")?></textarea>
            </div>

            <div class="col-md-12 form-group">
                <div class="error exp_type_data"></div>
                <div class="table-responsive">
                    <table id="expenceTypeData" class="table table-striped table-borderless">
                        <thead class="thead-info">
                            <tr>
                                <th style="width:10%;">#</th>
                                <th style="width:40%;">Expence Type</th>
                                <th style="width:40%;">Amount</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyData">
                            <?php
                                if(!empty($expTypeList)){
                                    $expTransData = (!empty($expTransData))?$expTransData:[];
                                    $expTransData = array_reduce($expTransData, function($itemData, $row) {
                                        $itemData[$row->exp_type_id] = $row;
                                        return $itemData;
                                    }, []);

                                    $i=1;
                                    foreach($expTypeList as $row){
                                        $transId = $transValue = 0;

                                        $transId = (isset($expTransData[$row->id]) && !empty($expTransData[$row->id]->id))?$expTransData[$row->id]->id:0;
                                        $transValue = (isset($expTransData[$row->id]) && !empty($expTransData[$row->id]->amount))?$expTransData[$row->id]->amount:0;

                                        echo "<tr>";
                                        echo "<td>".$i++."</td>";
                                        echo "<td>".$row->label."</td>";
                                        echo "<td>
                                                <input type='hidden' name='expTrans[".$i."][id]' value='".$transId."' />
                                                <input type='hidden' name='expTrans[".$i."][exp_type_id]' value='".$row->id."' />
                                                <input type='number' name='expTrans[".$i."][amount]' class='form-control floatOnly' value='".floatval($transValue)."' />
                                            </td>";
                                        echo "</tr>";
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</form>