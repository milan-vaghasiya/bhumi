<?php
class LeaveModel extends MasterModel{
    private $leaveMaster = "leave_master";

    public function getDTRows($data){
        $data['tableName'] = $this->leaveMaster;
        $data['select'] = "leave_master.*,employee_master.emp_name";
        $data['leftJoin']['employee_master'] = "employee_master.id = leave_master.emp_id";

		if($data['status'] == 2){
			$data['where']['leave_master.leave_status'] = 2;
            $data['where']['leave_master.approve_by >'] = 0;
        }elseif($data['status'] == 1){
			$data['where']['leave_master.leave_status'] = 1;
            $data['where']['leave_master.approve_by >'] = 0;
        }else{
            $data['where']['leave_master.leave_status'] = 0;
            $data['where']['leave_master.approve_by'] = 0;
        }

        if(!in_array($this->userRole,[1,-1])):
            $data['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id ) >0 OR employee_master.id = '.$this->loginId.')';
        endif;
        
        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "employee_master.emp_name";
        $data['searchCol'][] = "leave_master.start_date";
        $data['searchCol'][] = "leave_master.end_date";
        $data['searchCol'][] = "leave_master.total_days";
        $data['searchCol'][] = "leave_master.reason";

        $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;

        if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        $result = $this->pagingRows($data);
        return $result;
    }

    public function getLeaveData($data){
        $queryData['tableName'] = $this->leaveMaster;
        $queryData['where']['id'] = $data['id'];
        return $this->row($queryData);
    }
				
    public function save($data){
        try{
            $this->db->trans_begin();

            $result = $this->store($this->leaveMaster,$data,'Leave');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

    public function delete($data){
        try{
            $this->db->trans_begin();

            $leaveData = $this->getLeaveData($data);
            if(!empty($leaveData->leave_status)):
                return ['status'=>0,'message'=>'Leave status Changed. You can not delete it.'];
            endif;
            
            $result = $this->trash($this->leaveMaster,['id'=>$data['id']],'Leave');
            
            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }			
    }

    public function approveLeave($data){
        try{
            $data['approve_by'] = $this->loginId; 
            $data['approve_at'] = date('Y-m-d H:i:s');
            $result = $this->store($this->leaveMaster,$data,'Leave');
            
            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

    public function getLeaveList($data = []){
        $queryData['tableName'] = $this->leaveMaster;
        $queryData['select'] = "leave_master.*,employee_master.emp_code,employee_master.emp_name";
        $queryData['leftJoin']['employee_master'] = "employee_master.id = leave_master.emp_id";

        if(isset($data['leave_status'])):
			$queryData['where']['leave_master.leave_status'] = $data['leave_status'];
        endif;
		
		$self_condition = '';
		if(!empty($data['self_leave']) AND $data['self_leave']==2):
            $self_condition = 'OR employee_master.id = '.$this->loginId;
		endif;
		
        if(!in_array($this->userRole,[1,-1])):
			if(!empty($data['self_leave']) AND $data['self_leave']==1):	// Self Leave
				$queryData['where']['leave_master.emp_id'] = $this->loginId;
			elseif(!empty($data['self_leave']) AND $data['self_leave']==2):	// Leave To Be Approved
				$queryData['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id ) > 0 )';
			else:
				$queryData['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id ) > 0 OR leave_master.emp_id = '.$this->loginId.')';
			endif;
        endif;

        if(!empty($data['search'])):
            $queryData['like']['employee_master.emp_name'] = $data['search'];
            $queryData['like']['DATE_FORMAT(leave_master.start_date,"%d-%m-%Y")'] = $data['search'];
            $queryData['like']['DATE_FORMAT(leave_master.end_date,"%d-%m-%Y")'] = $data['search'];
            $queryData['like']['leave_master.total_days'] = $data['search'];
            $queryData['like']['leave_master.reason'] = $data['search'];
        endif;

        if(isset($data['start']) && isset($data['length'])):
            $queryData['start'] = $data['start'];
            $queryData['length'] = $data['length'];
        endif;
        
        $result = $this->rows($queryData);
        return $result;
    }

    public function getTodayLeaveData(){
		$data['tableName'] = $this->leaveMaster;
        $data['customWhere'][] = "DATE_FORMAT(leave_master.start_date,'%Y-%m-%d') BETWEEN '".date('Y-m-d')."' AND '".date('Y-m-d')."'";
        $data['group_by'][] = 'leave_master.emp_id';
		return $this->rows($data);
	}

}
?>