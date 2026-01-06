<?php
class ItemCategory extends MY_Controller
{
    private $indexPage = "item_category/index";
    private $itemCategoryForm = "item_category/form";
    private $subCategoryPage = "item_category/sub_category";
	public function __construct(){
		parent::__construct();
		$this->isLoggedin();
		$this->data['headData']->pageTitle = "Item Category";
		$this->data['headData']->controller = "itemCategory";
		$this->data['headData']->pageUrl = "itemCategory/list";
	}
	
    public function list($id=1){
        $subCategoryName = $this->item->getCategory($id);
        $this->data['pageHeader'] = !empty($subCategoryName->category_name)?$subCategoryName->category_name:'Item Category';
        $this->data['category_ref_id']=!empty($subCategoryName->ref_id)?$subCategoryName->ref_id:0;
        $this->data['SubCategortData'] = $this->item->getCategoryList(['ref_id'=>$id]);
        $this->data['catId'] = $id;

        $this->load->view($this->indexPage,$this->data);
    }

	public function addItemCategory(){
        $mainCatId = $this->input->post('mainCatId');
        $this->data['mainCategory'] = $this->item->getCategoryList(['id'=>$mainCatId,'final_category'=>0]);
        $this->data['mainCatId'] = $mainCatId;
        $this->load->view($this->itemCategoryForm,$this->data);
    }

    public function save(){
        $data = $this->input->post();
        $errorMessage = array();
        if(empty($data['category_name']))
            $errorMessage['category_name'] = "Category is required.";
        if(empty($data['ref_id'])):
            $errorMessage['ref_id'] = "Main Category is required.";
    
        endif;
       
        $nextlevel='';
        if(!empty($data['category_level']) && empty($data['id'])):
            $level = $this->item->getNextCategoryLevel($data['ref_id']);
            $count = count($level);
            $nextlevel = $data['category_level'].'.'.($count+1);
            $data['category_level'] = $nextlevel;
        endif; 
        
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $data['created_by'] = $this->session->userdata('loginId');
            $this->printJson($this->item->saveCategory($data));
        endif;
    }

    public function edit(){
        $this->data['mainCategory'] = $this->item->getCategoryList(['final_category'=>0]);
        $this->data['dataRow'] = $this->item->getCategory($this->input->post('id'));
        $this->load->view($this->itemCategoryForm,$this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->item->deleteCategory($id));
        endif;
    }
	
}
?>