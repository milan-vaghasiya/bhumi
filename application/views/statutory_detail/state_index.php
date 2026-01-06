<?php $this->load->view('includes/header'); ?>
<div class="page-content-tab">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="page-title-box">
					<div class="float-end">
						<button type="button" class="btn btn-info btn-sm float-right addNew press-add-btn permission-write" data-button="both" data-modal_id="right_modal" data-function="addStatutoryDetail/State" data-form_title="Add State"><i class="fa fa-plus"></i> Add State</button>
					</div>
					<ul class="nav nav-pills">
						<li>
                            <a href="<?=base_url($headData->controller)?>" class="btn btn-outline-info permission-modify">Country</a>
                        </li>
						<li>
                            <a href="<?=base_url($headData->controller.'/stateIndex')?>" class="btn btn-outline-info permission-modify <?=((!empty($type) && $type == 'State') ? 'active' : '')?>">State</a>
                        </li>
						<li>
                            <a href="<?=base_url($headData->controller.'/districtIndex')?>" class="btn btn-outline-info permission-modify <?=((!empty($type) && $type == 'District') ? 'active' : '')?>">District</a>
                        </li>
						<li>
                            <a href="<?=base_url($headData->controller.'/talukaIndex')?>" class="btn btn-outline-info permission-modify <?=((!empty($type) && $type == 'Taluka') ? 'active' : '')?>">Taluka</a>
                        </li>
					</ul>
				</div>
			</div>
		</div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id='countryTable' class="table table-bordered ssTable" data-url='/getStateDTRows/<?=$type?>'></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>

<?php $this->load->view('includes/footer'); ?>