<?php
class ItemModel extends MasterModel{
    private $item_master = "item_master";
    private $unit_master = "unit_master";
	private $item_udf = "item_udf";
    private $itemCategory = "item_category";

    /********** Finish Goods **********/
        public function getFinishGoodsDTRows($data){
            $data['tableName'] = $this->item_master;
            $data['select'] = 'item_master.*,item_udf.f1 as size, item_category.category_name';
            $data['leftJoin']['item_udf'] = "item_udf.item_id  = item_master.id";
            $data['leftJoin']['item_category'] = 'item_category.id = item_master.category_id';


            $data['searchCol'][] = "";
            $data['searchCol'][] = "";
            $data['searchCol'][] = "";
            $data['searchCol'][] = "item_name";
            $data['searchCol'][] = "item_category.category_name";
            $data['searchCol'][] = "price";
            $data['searchCol'][] = "primary_packing";
            $data['searchCol'][] = "master_packing";

            $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
            if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
            return $this->pagingRows($data);
        }
        
        public function getFinishGoodsData($data=array()){
            $queryData['tableName'] = $this->item_master;
            $queryData['select']="item_master.*,item_udf.f1 as size";
            $queryData['leftJoin']['item_udf'] = "item_udf.item_id  = item_master.id";
            if(!empty($data['id'])):
                $queryData['where']['item_master.id'] = $data['id'];
            endif;
            
            return $this->row($queryData);
        }

        public function saveFinishGoods($data){
            try{
                $this->db->trans_begin();
                $customField = !empty($data['customField'])?$data['customField']:[]; unset($data['customField']);
                $result = $this->store($this->item_master, $data, "Item");    
                $itemUdfData = $this->getItemUdfData(['item_id'=>$result['id']]); 
                $customField['item_id'] =$result['id'];       
                $customField['id'] = !empty($itemUdfData->id)?$itemUdfData->id :'';
                $this->store($this->item_udf,$customField);
                if ($this->db->trans_status() !== FALSE):
                    $this->db->trans_commit();
                    return $result;
                endif;
            }catch(\Exception $e){
                $this->db->trans_rollback();
                return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
            }
        }

        public function getItemUdfData($param = []){
            $queryData['tableName'] = $this->item_udf;
            if(!empty($param['item_id'])):
                $queryData['where']['item_udf.item_id'] = $param['item_id'];
            endif;
            return $this->row($queryData);
        }

        public function getUnitList(){
            $data['tableName'] = $this->unit_master;
            return $this->rows($data);
        }
        
        public function getItemList($data=array()){
            $queryData['tableName'] = $this->item_master;
            $queryData['select'] = "item_master.*,item_category.category_name,item_udf.f1 as size";

            $queryData['leftJoin']['item_udf'] = "item_udf.item_id  = item_master.id";
            $queryData['leftJoin']['item_category'] = "item_category.id  = item_master.category_id";
            
            
            if(!empty($data['ids'])):
                $queryData['where_in']['item_master.id'] = $data['ids'];
            endif;

            if(!empty($data['active_item'])):
                $queryData['where_in']['item_master.active'] = $data['active_item'];
            else:
                $queryData['where']['item_master.active'] = 1;
            endif;

            return $this->rows($queryData);
        }
    /********** End Finish Goods **********/

    /********** Item Category **********/
        public function getCategory($id){
            $data['where']['id'] = $id;
            $data['tableName'] = $this->itemCategory;
            return $this->row($data); 
        }

        public function saveCategory($data){
            try {
                $this->db->trans_begin();
                $data['checkDuplicate'] = ['category_name','ref_id'];
                $result =  $this->store($this->itemCategory,$data,'Item Category');
                if ($this->db->trans_status() !== FALSE) :
                    $this->db->trans_commit();
                    return $result;
                endif;
            } catch (\Exception $e) {
                $this->db->trans_rollback();
                return ['status' => 2, 'message' => "somthing is wrong. Error : " . $e->getMessage()];
            }
        }
    
        public function deleteCategory($id){
            try {
                $this->db->trans_begin();

                $checkData['columnName'] = ['category_id','item_category'];
                $checkData['value'] = $id;
                $checkUsed = $this->checkUsage($checkData);
                if($checkUsed == true):
                    return ['status'=>0,'message'=>'The Category is currently in use. you cannot delete it.'];
                endif;
                $data = [];
                $data['tableName'] = $this->itemCategory;
                $data['where']['ref_id'] = $id;
                $data['resultType'] = "numRows";
                $checkRef = $this->specificRow($data);
                if($checkRef > 0):
                    return ['status'=>0,'message'=>'The Category is currently in use. you cannot delete it.'];
                endif;
                $result = $this->trash($this->itemCategory,['id'=>$id],'Item Category');

                if ($this->db->trans_status() !== FALSE) :
                    $this->db->trans_commit();
                    return $result;
                endif;
            } catch (\Exception $e) {
                $this->db->trans_rollback();
                return ['status' => 2, 'message' => "somthing is wrong. Error : " . $e->getMessage()];
            }
        }    

        public function getNextCategoryLevel($ref_id){
            $data['tableName'] = $this->itemCategory;
            $data['where']['ref_id'] = $ref_id;
            return $this->rows($data);
        }   
        
        public function getCategoryList($param = []){
            $data['tableName'] = $this->itemCategory; 
            $data['where']['item_category.category_type'] =1;
            $data['select'] = "item_category.*, IFNULL(pcat.category_name,'NA') as parent_cat"; 
            $data['leftJoin']['item_category pcat'] = 'pcat.id=item_category.ref_id'; 
            if(!empty($param['customWhere'])){
                $data['customWhere'][]=$param['customWhere'];
            }
            if(!empty($param['ref_id'])){
                $data['where']['item_category.ref_id'] = $param['ref_id'];
            }
            if(!empty($param['id'])){
                $data['where']['item_category.id'] = $param['id'];
            }
            if(isset($param['final_category'])){
                $data['where']['item_category.final_category'] = $param['final_category'];
            }
            $result= $this->rows($data);
            return $result;
        }  
    /********** End Item Category **********/
}
?>