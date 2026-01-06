<?php
class ServiceModel extends MasterModel
{
    private $service_category = "service_category";
    private $service_request = "service_request";
    private $item_master = "item_master";
	
    /********** Service Category **********/
        public function getServiceCategoryDTRows($data){
            $data['tableName'] = $this->service_category;

            $data['searchCol'][] = "";
            $data['searchCol'][] = "";
            $data['searchCol'][] = "service_name";

            $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;

            if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
            return $this->pagingRows($data);
        }

        public function getServiceCategory($data){
            $queryData['where']['id'] = $data['id'];
            $queryData['tableName'] = $this->service_category;
            return $this->row($queryData);
        }

        public function getServiceCategoryList(){
            $data['tableName'] = $this->service_category;
            return $this->rows($data);
        }

        public function saveServiceCategory($data){
            try{
                $this->db->trans_begin();

                $result = $this->store($this->service_category,$data,'System');

                if ($this->db->trans_status() !== FALSE):
                    $this->db->trans_commit();
                    return $result;
                endif;
            }catch(\Exception $e){
                $this->db->trans_rollback();
                return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
            }	
        }
    /********** End Service Category **********/

    /********** Service Request **********/
        public function getserviceRequestNextNo(){
            $queryData['tableName'] = $this->service_request;
            $queryData['select'] = "ifnull(MAX(req_no + 1),1) as nextReqNo";
            $queryData['where']['req_date >='] = $this->startYearDate;
            $queryData['where']['req_date <='] = $this->endYearDate;
            return $this->row($queryData)->nextReqNo;
        }

        public function getserviceRequestDTRows($data){
            $data['tableName'] = $this->service_request;
            $data['select'] = "service_request.*,party_master.party_name,item_master.item_code,item_master.item_name";
            $data['leftJoin']['party_master'] = "party_master.id = service_request.party_id";
            $data['leftJoin']['item_master'] = "item_master.id = service_request.item_id";

            if(!empty($data['status'])){
                $data['where']['service_request.status'] = $data['status'];
            }else{
                $data['where']['service_request.status'] = 0;
            }

            $data['searchCol'][] = "";
            $data['searchCol'][] = "";
            $data['searchCol'][] = "CONCAT(req_prefix,req_no)";
            $data['searchCol'][] = "DATE_FORMAT(req_date,'%d-%m-%Y')";
            $data['searchCol'][] = "party_master.party_name";
            $data['searchCol'][] = "CONCAT('[',item_master.item_code,'] ',item_master.item_name)";
            $data['searchCol'][] = "on_site";
            $data['searchCol'][] = "description";

            $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;

            if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
            return $this->pagingRows($data);
        }

        public function getServiceRequest($data){
            $queryData['tableName'] = $this->service_request;
            $queryData['select'] = "service_request.*,party_master.party_name,party_master.business_type";
            $queryData['leftJoin']['party_master'] = "party_master.id = service_request.party_id";
            $queryData['where']['service_request.id'] = $data['id'];
            return $this->row($queryData);
        }

        public function getServiceRequestList($postData=[]){
            $data['tableName'] = $this->service_request;
            $data['select'] = "service_request.*,party_master.party_name,item_master.item_code,item_master.item_name";
            $data['leftJoin']['party_master'] = "party_master.id = service_request.party_id";
            $data['leftJoin']['item_master'] = "item_master.id = service_request.item_id";
            if(!empty($postData['status'])) { $data['where']['service_request.status'] = $postData['status']; }
            return $this->rows($data);
        }

        public function saveserviceRequest($data){
            try{
                $this->db->trans_begin();

                $result = $this->store($this->service_request,$data,'Service Request');

                if ($this->db->trans_status() !== FALSE):
                    $this->db->trans_commit();
                    return $result;
                endif;
            }catch(\Exception $e){
                $this->db->trans_rollback();
                return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
            }	
        }
    /********** End Service Request **********/
}
?>