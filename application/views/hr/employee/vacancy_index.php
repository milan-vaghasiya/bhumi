<?php $this->load->view('includes/header'); ?>
<div class="page-content-tab">
	<div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
				<div class="page-title-box">
					<div class="float-end">
                        <?php
                            // $addParam = "{'modal_id' : 'bs-right-md-modal', 'call_function':'addVacancy', 'form_id' : 'addVacancy', 'title' : 'Add Vacancy','fnsave':'saveVacancy'}";
                        ?>
                        <button type="button" class="btn btn-info btn-sm float-right addNew press-add-btn permission-write" data-button="both" data-modal_id="right_modal" data-function="addVacancy" data-form_title="Add Vacancy" data-fnsave="saveVacancy"><i class="fa fa-plus"></i> Add Vacancy</button>
					</div>
                    <h4 class="card-title text-center">Vacancy</h4>
				</div>
            </div>
		</div>
        <div class="row">
            <div class="col-12">
				<div class="col-12">
					<div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id='vacancyTable' class="table table-bordered ssTable ssTable-cf" data-url="/getVacancyDTRows"></table>
                            </div>
                        </div>
					</div>
				</div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('includes/footer'); ?>

