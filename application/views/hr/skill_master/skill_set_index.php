<?php $this->load->view('includes/header'); ?>
<div class="page-content-tab">
	<div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
				<div class="page-title-box">
                    <div class="float-start">
                        <ul class="nav nav-pills">
                            <li class="nav-item"> <a href="<?=base_url($headData->controller."/index/0")?>" class="btn waves-effect waves-light btn-outline-info mr-1 <?=($status == 0) ? "active" : ""?>"> Skills </a> </li>
                            <li class="nav-item"> <a href="<?=base_url($headData->controller."/skillSetIndex/1")?>" class="btn waves-effect waves-light btn-outline-danger mr-1 <?=($status == 1) ? "active" : "" ?>"> Skill Set </a> </li>
                        </ul>
                    </div>

					<div class="float-end">
                        <button type="button" class="btn btn-info btn-sm float-right addNew press-add-btn permission-write" data-button="both" data-modal_id="right_modal_lg" data-function="addSkillSet" data-form_title="Add Skill Set" data-form_id="addSkillSet"><i class="fa fa-plus"></i> Add Skill Set</button>
					</div>
				</div>
            </div>
		</div>
        <div class="row">
            <div class="col-12">
				<div class="col-12">
					<div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id='skillMasterTable' class="table table-bordered ssTable ssTable-cf" data-url="/getSkillSetDTRows"></table>
                            </div>
                        </div>
					</div>
				</div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('includes/footer'); ?>

