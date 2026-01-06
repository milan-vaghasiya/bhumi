<form>
    <div class="col-md-12">
        <div class="row">            

            <?php 
                if(!empty($dataRow->proof_file)) {
                    $files = explode(',', $dataRow->proof_file);
                    foreach ($files as $row) {
                        ?>
                        <div class="col-md-6 form-group">
                            <a href="<?=base_url('assets/uploads/expense/'.$row)?>" class="" target="_blank">
                                <img src="<?=base_url('assets/uploads/expense/'.$row)?>" class="border border-primary p-3" width='100%'>
                            </a>
                        </div>
                        <?php 
                    }
                } else {

                }
            ?>

        </div>
    </div>
</form>