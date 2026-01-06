

<?php $this->load->view('includes/header'); ?>
<div class="page-content-tab">
	<div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            
                            <div class="col-md-6">
                                <h4><?php  echo '<a href="' . base_url("itemCategory/list/" . $category_ref_id) . '">' .$pageHeader . '</a>'; ?></h4>
                            </div>
                            <div class="col-md-6">
                                <?php $addPostData = "{'postData':{'mainCatId' : ".$catId."}}"; ?>
                                <button type="button" class="btn btn-info btn-sm float-right addNew press-add-btn permission-write" data-button="both" data-modal_id="modal-md" data-function="addItemCategory" data-postdata='{"mainCatId" : <?=$catId?> }' data-table_id="commanTable" data-form_title="Add Item Category"><i class="fa fa-plus"></i> Add Item Category</button>
                            </div>                             
                        </div>                                         
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                        <table id='commanTable' class="table table-bordered">
								<thead class="thead-info" id="theadData">
									<tr>
										<th>Action</th>
										<th>#</th>
										<th>Category Name</th>
										<th>Parent Category</th>
										<th>Is Final ?</th>
									</tr>
								</thead>
								<tbody>
									<?php $i=1;
										foreach($SubCategortData as $row):
                                            $deleteParam = "{'postData':{'id' : ".$row->id."},'message' : 'Item Category','table_id' : 'commanTable'}";
                                            $editParam = "{'postData':{'id' : ".$row->id."},'modal_id' : 'right_modal', 'form_id' : 'editItemCategory', 'title' : 'Update Item Category','table_id':'commanTable'}";
                                        
    										$editButton=''; $deleteButton='';
											if(!empty($row->ref_id)):
                                                $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';
                                                $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
											endif;
											$cat_code ='';
											if($row->ref_id ==6 || $row->ref_id == 7){$cat_code =(!empty($row->tool_type))?'['.str_pad($row->tool_type,3,'0',STR_PAD_LEFT).'] ':'';}
											if($row->final_category == 0){$cName = $cat_code.'<a href="' . base_url("itemCategory/list/" . $row->id) . '">' . $row->category_name . '</a>';}
											else{$cName = $cat_code.$row->category_name;}
											
										
											echo '<tr>
												    <td>
												        <div class="actionWrapper" style="position:relative;">
            												<div class="actionButtons actionButtonsRight">
            													<a class="mainButton btn-instagram" href="javascript:void(0)"><i class="fa fa-cog"></i></a>
            													<div class="btnDiv" style="left:85%;">
            														'.$editButton.$deleteButton.'
            													</div>
            												</div>
											            </div>
											        </td>
    												<td>'.$i++.'</td>
    												<td>'.$cName.'</td>
    												<td>'.$row->parent_cat.'</td>
    												<td>'.(!empty($row->final_category) ? 'Yes' : 'No' ).'</td>
											    </tr>';
										endforeach;
									?>
								</tbody>
							</table>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>

<?php $this->load->view('includes/footer'); ?>

<script>
    $(document).ready(function () {
        initDataTable();
	});
</script>