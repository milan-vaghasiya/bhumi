<?php
class VisitModel extends MasterModel{
    private $visits = "visits";
    
	public function getVisit($id){
        $data['tableName'] = $this->visits;
        $data['where']['visits.id'] = $id;
        return $this->row($data);
    }
    
	public function save($data){
        try{
            $this->db->trans_begin();
            $data['created_at'] = date("Y-m-d H:i:s");
            unset($data['party_type'],$data['business_type']);
            $data['emp_id'] = $this->loginId;
            $result = $this->store($this->visits,$data,'Visit');
            
            // Insert Location Log
            $locLog = Array();
            $locLog['log_type'] = 3;
			$locLog['ref_id'] = $result['insert_id'];
            $locLog['emp_id'] = $this->loginId;
            $locLog['party_id'] = (!empty($data['party_id']) ? $data['party_id'] : 0);
            $locLog['lead_id'] = (!empty($data['lead_id']) ? $data['lead_id'] : 0);
            $locLog['log_time'] = date('Y-m-d H:i:s');
            $locLog['location'] = $data['start_location'];
            $locLog['address'] = $data['s_add'];
            $locLog['vehicle_id'] = $data['vehicle_id'];
            $locLog['price_km'] = $data['price_km'];
            $llResult = $this->saveLocationLog($locLog);

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    public function saveEndVisit($data){
        try{
            $this->db->trans_begin();

            $party_type = $data['party_type'];
            $next_visit = $data['next_visit'];
            $reminder_date = $data['reminder_date']; $reminder_time = $data['reminder_time']; $reminder_note = $data['reminder_note'];
            unset($data['party_type'],$data['reminder_date'],$data['reminder_time'],$data['reminder_note'],$data['next_visit']);
            $this->store($this->visits,$data);
            
            // Insert Location Log
            $vLog = $this->getVisit($data['id']);
            $locLog = Array();
            $locLog['log_type'] = 4;
            $locLog['emp_id'] = $this->loginId;
            $locLog['party_id'] = (!empty($vLog->party_id) ? $vLog->party_id : 0);
            $locLog['lead_id'] = (!empty($vLog->lead_id) ? $vLog->lead_id : 0);
            $locLog['log_time'] = $data['created_at'];
            $locLog['location'] = $data['end_location'];
            $locLog['address'] = $data['e_add'];
            $llResult = $this->saveLocationLog($locLog);

            /** Sales Log Entry */
            $logData = [
                'id' => '',
                'log_type' => 26,
                'party_id' => (!empty($vLog->party_id) ? $vLog->party_id : 0),
                'lead_id' => (!empty($vLog->lead_id) ? $vLog->lead_id : 0),
                'ref_id' => $data['id'],
                'ref_date' => date("Y-m-d"),
                'ref_no' =>'',
                'executive_id' => $this->loginId,
                'notes' => 'Purpose : '.$vLog->purpose,
                'remark' => 'Discussion : '.$vLog->discussion_points.'<br>Contact Person : '.$vLog->contact_person,
                'created_by' => $this->loginId,
                'created_at' => date("Y-m-d H:i:s")
            ];            
            $this->sales->saveSalesLogs($logData);

            /*** If Lead status Changed  */
            if(!empty($party_type) && !empty($vLog->lead_id) && $vLog->party_type != $party_type){
                $stage = $this->configuration->getLeadStage(['id'=>$party_type]);
                $stageData = [
                    'id'=>$vLog->lead_id,
                    'party_type'=>$party_type,
                    'log_type'=>$stage->log_type,
                    'ref_date' => date("Y-m-d"),
                    'notes' => $stage->stage_type,
                    'executive_id' => $this->loginId,
                    'created_by' => $this->loginId,
                    'is_active' => 1,
                    'remark' => '',
                    'created_at' => date("Y-m-d H:i:s")
                ];
                $this->party->changeLeadStatus($stageData);
            }

            /*** If Next Reminder Set */
            if($next_visit == 'Yes'){
                $logData = [
                    'id' => '',
                    'log_type' => 3,
                    'party_id' => (!empty($vLog->party_id) ? $vLog->party_id : 0),
                    'lead_id' => (!empty($vLog->lead_id) ? $vLog->lead_id : 0),
                    'ref_id' => $data['id'],
                    'ref_date' =>$reminder_date,
                    'reminder_time' =>$reminder_time,
                    'notes' =>$reminder_note,
                    'mode' => 'Visit',
                    'executive_id' => $this->loginId,
                    'created_by' => $this->loginId,
                    'created_at' => date("Y-m-d H:i:s")
                ];
                $this->sales->saveSalesLogs($logData);
            }
            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return ['status'=>1,'message'=>"Visit Ended Successfully."];
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }
    
	public function delete($id){
        try{
            $this->db->trans_begin();

            $result = $this->trash($this->visits,['id'=>$id],'Visit');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

	public function getVisitList($param = Array()){
        $data['tableName'] = $this->visits;
		$data['select'] = 'visits.*,employee_master.super_auth_id';
		$data['leftJoin']['employee_master'] = "employee_master.id = visits.created_by";
		
        if(!empty($param['from_date']) && !empty($param['to_date'])):
            $data['where']['visits.created_at >= '] = $param['from_date'];
            $data['where']['visits.created_at <= '] = $param['to_date'];
		endif;

        if(!empty($param['customWhere'])){
            $data['customWhere'][] = $param['customWhere'];
        }

        if(!in_array($this->userRole,[-1,1])){ 
			$data['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id ) > 0 OR employee_master.id = '.$this->loginId.')';
		}
		if(!empty($param['sales_executive'])){$data['where']['visits.created_by'] = $param['sales_executive'];}
		
		if(!empty($param['report_data'])) { 
            $data['customWhere'][] = "MONTH(visits.created_at) = '".date('m',strtotime($param['month']))."' AND YEAR(visits.created_at) = '".date('Y',strtotime($param['month']))."'";
            $data['order_by']['visits.created_at'] = 'ASC';
        }
		
		if(!empty($param['emp_id'])){$data['where']['visits.emp_id'] = $param['emp_id'];}
		
		/*
        if(!empty($param['visit_status']) && $param['visit_status'] == 1){
            $data['where']['visits.created_at'] = null;
        }elseif(!empty($param['visit_status']) && $param['visit_status'] == 2){
            $data['customWhere'][] = 'visits.created_at IS NOT NULL';
            $data['order_by']['visits.created_at'] = 'DESC';
			$data['limit'] = 30;
        }
		*/
		
		$data['order_by']['visits.id'] = 'DESC';
		
        if(!empty($param['search'])):
            $data['like']['visits.party_name'] = $param['search'];
            $data['like']['visits.contact_person'] = $param['search'];
            $data['like']['visits.purpose'] = $param['search'];
        endif;

        if(isset($param['start']) && isset($param['length'])):
            $data['start'] = $param['start'];
            $data['length'] = $param['length'];
        endif;

        return $this->rows($data);
    }

    public function getVisitHistory($data = Array()){
        $queryData = array();
		$queryData['tableName'] = $this->visits;
        $queryData['select'] = "visits.id,DATE_FORMAT(visits.created_at, '%d-%m-%Y') as date, DATE_FORMAT(visits.created_at, '%H:%i:%s') as time, visits.start_location as location, party_master.party_name,party_master.party_address as address";
		$queryData['leftJoin']['party_master'] = "party_master.id = visits.party_id";
        $queryData['customWhere'][] = "created_at BETWEEN '".date('Y-m-d H:i:s',strtotime($data['from_date'].' 00:00:00'))."' AND '".date('Y-m-d H:i:s',strtotime($data['to_date'].' 23:59:59'))."'";
		
		if(!empty($data['party_id'])){$queryData['where']['party_master.id'] = $data['party_id'];}
		if(!in_array($this->userRole,[-1,1])){$data['where']['visits.created_by'] = $this->loginId;}
		if(!empty($data['sales_executive'])){$queryData['where']['visits.created_by'] = $data['sales_executive'];}
		
		$queryData['order_by']['visits.created_at'] = 'DESC';
		return $this->rows($queryData);
    }
	
	public function confirmVisit(){
        try{
            $this->db->trans_begin();
            $vLog = $this->getVisitList(['visit_status'=>1]);
            $cmInfo = $this->getCompanyInfo();
            foreach($vLog as $row){ 
                $end_time = date("Y-m-d H:i:s");
                if(date("Y-m-d",strtotime($row->created_at)) != date("Y-m-d")){
                    $end_time = date("Y-m-d",strtotime($row->created_at)).' '.$cmInfo->punch_out_time;
                }
                // print_r($end_time);exit;
                if(date("Y-m-d",strtotime($row->created_at)) != date("Y-m-d") OR (date("Y-m-d",strtotime($row->created_at)) == date("Y-m-d") && date('H:i') >= date("H:i",strtotime($cmInfo->punch_out_time)))){
                    $vData = [
                        'created_at' => $end_time,
                        'end_location'=>$row->start_location,
                        'e_add' =>$row->s_add
                    ];
                    $this->edit($this->visits,['id'=>$row->id],$vData);
    
                    $locLog = Array();
                    $locLog['log_type'] = 4;
                    $locLog['emp_id'] = $this->loginId;
                    $locLog['party_id'] = $row->party_id;
                    $locLog['log_time'] = $end_time;
                    $locLog['location'] = $row->start_location;
                    $locLog['address'] = $row->s_add;
                    $llResult = $this->saveLocationLog($locLog);
                }
                
            }
            $result = ['status'=>1,'message'=>"Viisit Confirmed Successfully."];

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }
	
	public function approveVisit($data){
        try{
            $this->db->trans_begin();

            $this->store($this->visits, ['id'=>$data['id'], 'approve_by'=>$this->loginId, 'approve_at'=>date('Y-m-d')]);

            $result = ['status'=>1,'message'=>"Visit Approved Successfully."];

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }
	
	//-----------  API Function Start -----------//
		
    public function getVisitList_api($limit, $start,$emp_id=0){
        $data['tableName'] = $this->visits;
        $data['select'] = "visits.*,party_master.party_name";
        $data['leftJoin']['party_master'] = "party_master.id = visits.party_id";
        if(!empty($emp_id) && !in_array($this->userRole,[-1,1]))
            $data['where']['party_master.sales_executive'] = $emp_id;
            
        $data['order_by']['visits.id'] = "DESC";
        $data['length'] = $limit;
        $data['start'] = $start;
        return $this->rows($data);
    }
    
	public function getCount($emp_id=0){
        $queryData['tableName'] = $this->visits;
        $queryData['select'] = "visits.*,party_master.party_name";
        $queryData['leftJoin']['party_master'] = "party_master.id = visits.party_id";
        if(!empty($emp_id) && !in_array($this->userRole,[-1,1])){ $queryData['where']['party_master.sales_executive'] = $emp_id; }
		
        $result = $this->numRows($queryData);
		return $result;
    }
	
	public function getVisitCount($param=[]){
        $queryData['tableName'] = $this->visits;
        $queryData['select'] = "visits.id";
        if(!empty($param['emp_id']) AND !in_array($this->userRole,[-1,1])){ $queryData['where']['visits.emp_id'] = $param['emp_id']; }
		if(!empty($param['from_date'])){$queryData['where']['DATE(visits.created_at) >= '] = $param['from_date'];}
		if(!empty($param['to_date'])){$queryData['where']['DATE(visits.created_at) <= '] = $param['to_date'];}
		
        $result = $this->numRows($queryData);
		//$this->printQuery();
		return $result;
    }
    
	//----------- API Function End -----------//
}
?>