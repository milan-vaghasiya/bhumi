
<?php
class MeetingModel extends MasterModel
{
    private $meeting_event = "meeting_event";	

    /********** Meeting & Event **********/
        public function getDTRows($data){
            $data['tableName'] = $this->meeting_event;
            $data['select'] = "meeting_event.*";
            $data['where']['meeting_event.trans_type'] = $data['type'];

            if(empty($data['status'])) { $data['where_in']['meeting_event.status'] =[0,1]; }
            else{ $data['where']['meeting_event.status'] = $data['status']; }
            if($data['type'] == 1){
                $data['searchCol'][] = "";
                $data['searchCol'][] = "";
                $data['searchCol'][] = "DATE_FORMAT(me_date,'%d-%m-%Y')";
                $data['searchCol'][] = "me_type";
                $data['searchCol'][] = "location";
                $data['searchCol'][] = "description";
            }else{
                $data['searchCol'][] = "";
                $data['searchCol'][] = "";
                $data['searchCol'][] = "DATE_FORMAT(me_date,'%d-%m-%Y')";
                $data['searchCol'][] = "me_type";
                $data['searchCol'][] = "event_name";
                $data['searchCol'][] = "event_duration";
                $data['searchCol'][] = "location";
                $data['searchCol'][] = "description";
            }
        
            $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;

            if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
            return $this->pagingRows($data);
        }

        public function getMeeting($data){
            $queryData['tableName'] = $this->meeting_event;
            $queryData['where']['id'] = $data['id'];
            return $this->row($queryData);
        }

        public function save($data){
            try{
                $this->db->trans_begin();

                $result = $this->store($this->meeting_event,$data,'Meeting');

                if ($this->db->trans_status() !== FALSE):
                    $this->db->trans_commit();
                    return $result;
                endif;
            }catch(\Exception $e){
                $this->db->trans_rollback();
                return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
            }	
        }

        public function changeMeetStatus($data) {
    
            $this->store($this->meeting_event, ['id'=> $data['id'], 'status' => $data['status']]);
            return ['status' => 1, 'message' => $data['type'] .' '. $data['msg'] . ' successfully.'];
        }
    /********** End Meeting & Event **********/
}
?>