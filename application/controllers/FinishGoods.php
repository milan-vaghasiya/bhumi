<?php
class FinishGoods extends MY_Controller{
    private $indexPage = "finish_goods/index";
    private $form = "finish_goods/form";

    private $gstPercentage = Array(["rate"=>0,"val"=>'NIL'],["rate"=>0.1,"val"=>'0.1%'],["rate"=>0.25,"val"=>'0.25%'],["rate"=>3,"val"=>'3%'],["rate"=>5,"val"=>'5%'],["rate"=>12,"val"=>'12%'],["rate"=>18,"val"=>'18%'],["rate"=>28,"val"=>'28%']);

    public function __construct(){
        parent::__construct();
        $this->data['headData']->pageTitle = "Finish Goods";
        $this->data['headData']->controller = "finishGoods"; 
	}

    public function list($item_type= 1){ 
        $this->data['headData']->pageUrl = "finishGoods/list/".$item_type;
        $this->data['item_type'] = $item_type;
        $headerName = str_replace(" ","_",strtolower($this->itemTypes[$item_type])); 
        $this->data['tableHeader'] = getMasterDtHeader($headerName);
        $this->load->view($this->indexPage,$this->data);
    }
   
    public function getDTRows(){
        $data = $this->input->post(); 
        $result = $this->item->getFinishGoodsDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):
            $row->sr_no = $i++;
            $sendData[] = getFinishGoodsData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addFinishGoods(){
        $data = $this->input->post();
        $this->data['item_type'] = $data['item_type'];
        $this->data['unitData'] = $this->item->getUnitList();
        $this->data['gstPercentage'] = $this->gstPercentage;
        $this->data['customFieldList'] = $this->configuration->getCustomFieldList(['type'=>1]);
        $this->data['masterDetailList'] = $this->configuration->getMasterList();
        $this->data['categoryData'] = $this->item->getCategoryList(['final_category'=>1]);
        $this->load->view($this->form,$this->data); 
    }

    public function save(){
        $data = $this->input->post(); 
        $errorMessage = array();
        if(empty($data['item_name'])){
            $errorMessage['item_name'] = "Item Name is required.";
        }
        if(empty($data['item_code'])){
            $errorMessage['item_code'] = "Item Code is required.";
        }

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            if(!empty($_FILES['img_file'])):
                if($_FILES['img_file']['name'] != null || !empty($_FILES['img_file']['name'])):
                    $this->load->library('upload');
    				$_FILES['userfile']['name']     = $_FILES['img_file']['name'];
    				$_FILES['userfile']['type']     = $_FILES['img_file']['type'];
    				$_FILES['userfile']['tmp_name'] = $_FILES['img_file']['tmp_name'];
    				$_FILES['userfile']['error']    = $_FILES['img_file']['error'];
    				$_FILES['userfile']['size']     = $_FILES['img_file']['size'];
    				
    				$imagePath = realpath(APPPATH . '../assets/uploads/finish_goods/');
    				$config = ['file_name' => 'FG-'.time(),'allowed_types' => '*','max_size' => 10240,'overwrite' => FALSE, 'upload_path'	=>$imagePath];
    
    				$this->upload->initialize($config);
    				if (!$this->upload->do_upload()):
    					$errorMessage['img_file'] = $this->upload->display_errors();
    					$this->printJson(["status"=>0,"message"=>$errorMessage]);
    				else:
    					$uploadData = $this->upload->data();
    					$data['img_file'] = $uploadData['file_name'];
    				endif;
    			endif;
            endif;
        
            $this->printJson($this->item->saveFinishGoods($data)); 
        endif;
    }

    public function edit(){
        $data = $this->input->post();
        $this->data['unitData'] = $this->item->getUnitList();
        $this->data['gstPercentage'] = $this->gstPercentage;
        $this->data['dataRow'] = $this->item->getFinishGoodsData($data);
        $this->data['categoryData'] = $this->item->getCategoryList(['final_category'=>1]); 
        $this->data['customFieldList'] = $this->configuration->getCustomFieldList(['type'=>1]);
        $this->data['masterDetailList'] = $this->configuration->getMasterList();
        $this->data['customData'] = $this->item->getItemUdfData(['item_id'=>$data['id']]);
        $this->load->view($this->form,$this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->item->trash('item_master',['id'=>$id]));
        endif;
    }

    public function getItemDetails(){
        $data = $this->input->post();
        $itemDetail = $this->item->getFinishGoodsData($data);
        $this->printJson(['status'=>1,'data'=>['itemDetail'=>$itemDetail]]);
    }

    public function getOrderUnitOptions(){
        $data = $this->input->post();
        $itemData = $this->item->getFinishGoodsData(['id'=>$data['item_id']]);
        $options='<option value="1">Default</option>
                <option value="'.floatval($itemData->primary_packing).'">Primary Packing ['.$itemData->primary_packing.']</option>
                <option value="'.floatval($itemData->master_packing).'">Master Packing ['.$itemData->master_packing.']</option>';
        $this->printJson(['status'=>1,'options'=>$options]);
    }
}
?>