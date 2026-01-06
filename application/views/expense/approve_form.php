

<form enctype='multipart/form-data'>
    <div class="col-md-12">
        <div class="row">            

            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id) ? $dataRow->id :"")?>" />
            <input type="hidden" name="exp_source" id="exp_source" value="<?=(!empty($dataRow->exp_source) ? $dataRow->exp_source : 1)?>" />

            <div class="col-md-4 form-group">
                <label for="exp_number">Expense No.</label>
                <input type="text" name="exp_number" id="exp_number" class="form-control req" value="<?=(!empty($dataRow->exp_number) ? $dataRow->exp_number : $exp_prefix.sprintf("%03d",$exp_no))?>" readOnly />
                <input type="hidden" name="exp_prefix" id="exp_prefix" value="<?=(!empty($dataRow->exp_prefix)) ? $dataRow->exp_prefix : $exp_prefix?>" />
                <input type="hidden" name="exp_no" id="exp_no" value="<?=(!empty($dataRow->exp_no)) ? $dataRow->exp_no : $exp_no?>" />
            </div>

            <div class="col-md-8 form-group">
                <label for="exp_date">Exp Date</label>
                <input type="datetime-local" name="exp_date" id="exp_date" class="form-control req" value="<?=(!empty($dataRow->exp_date) ? $dataRow->exp_date : date('Y-m-d h:i A'))?>" />
            </div>
            <div class="col-md-12 form-group">
                <label for="exp_by_id">Employee</label>
                <select name="exp_by_id" id="exp_by_id" class="form-control modal-select2 req">
                    <option value="">Select Employee</option>
                    <?php
                        if(!empty($options)){
                            echo $options;
                        }else{
                            foreach($empList as $row){
                                echo '<option value="'.$row->id.'" '.$selected.'>'.$row->emp_name.'</option>';
                            }
                        }
                    ?>
                </select>
            </div>
            <div class="col-md-12 form-group">
                <label for="notes">Description</label>
                <textarea name="notes" id="notes" class="form-control" rows="2"><?=(!empty($dataRow->notes) ? $dataRow->notes : "")?></textarea>
            </div>

            <div class="col-md-12 form-group">
                <label for="proof_file">File Upload</label>
                <div class="input-group">
                    <input type="file" name="proof_file[]" class="form-control" style="width:<?=(!empty($dataRow->proof_file)) ? "75%" : "" ?>" multiple="multiple" />
                </div>
            </div>

            <div class="col-md-12 form-group">
                <div class="error exp_type_data"></div>
                <div class="table-responsive">
                    <table id="expenceTypeData" class="table table-striped table-borderless">
                        <thead class="thead-info">
                            <tr>
                                <th style="width:10%;">#</th>
                                <th style="width:30%;">Expence Type</th>
                                <th style="width:20%;">Demand Amount</th>
                                <th style="width:20%;">Approved Amount</th>
                                <th style="width:20%;">Notes</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyData">
                            <?php
                                if(!empty($expTransData)){
                                    $i=1;
                                    foreach($expTransData as $row){
                                        echo "<tr>";
                                        echo "<td>".$i."</td>";
                                        echo "<td>".$row->expense_label."</td>";
                                        echo "<td>
                                                <input type='hidden' name='expTrans[".$i."][id]' value='".$row->id."' />
                                                <input type='hidden' name='expTrans[".$i."][exp_type_id]' value='".$row->exp_type_id."' />
                                                <input type='number' name='expTrans[".$i."][amount]' class='form-control floatOnly' value='".floatval($row->amount)."' readonly />
                                            </td>";
                                        echo "<td>
                                                <input type='number' name='expTrans[".$i."][approve_amount]' class='form-control floatOnly' value='".floatval($row->amount)."' />
                                            </td>";
                                        echo "<td>
                                                <input type='text' name='expTrans[".$i."][approve_remark]' class='form-control' value='".$row->approve_remark."' />
                                                <div class='error approve_remark".$i."'></div>
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
        </div>
    </div>
</form>