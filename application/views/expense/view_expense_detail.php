<form>
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-info">
                        <tr>
                            <th>#</th>
                            <th>Expense Date</th>
                            <th>Expense Type</th>
                            <th>Demand Amount</th>
                            <th>Approved Amount</th>
                            <th>Approved Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i=1;
                        if(!empty($expenseData)){
                            foreach ($expenseData as $row) {
                                echo '<tr>
                                    <td>'.$i++.'</td>
                                    <td>'.$row->exp_date.'</td>
                                    <td>'.$row->expense_label.'</td>
                                    <td>'.$row->amount.'</td>
                                    <td>'.$row->approve_amount.'</td>
                                    <td>'.$row->approve_remark.'</td>
                                </tr>';                                
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div> 
    </div>
</form>