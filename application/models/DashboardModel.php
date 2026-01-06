<?php
class DashboardModel extends MasterModel{
    
    public function sendSMS($mobiles,$message){
        
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,"http://sms.scubeerp.in/sendSMS?");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "username=9427235336&message=".$message."&sendername=NTVBIT&smstype=TRANS&numbers=".$mobiles."&apikey=7d37fc6d-a141-4f81-9d79-159cf37c3342");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$server_output = curl_exec($ch);
		curl_close ($ch);
	}
	
	public function getLeadAnalysisData(){
        $queryData['tableName'] = "lead_master";
        $queryData['leftJoin']['employee_master'] = "lead_master.executive_id = employee_master.id";
        $queryData['select'] = "count(*) as lead_count,party_type,business_type";        
        $queryData['group_by'][] = "lead_master.party_type,lead_master.business_type";
		if(!in_array($this->userRole,[1,-1])):
            $queryData['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id ) >0 OR employee_master.id = '.$this->loginId.')';
        endif;
		
        return $this->rows($queryData);
    }

	// Dashboard Notice board Data
	public function getNoticeBoardData() {
		$queryData['tableName'] = "notice_board";
		$queryData['select'] = "notice_board.id, notice_board.title, notice_board.from_date, notice_board.to_date, notice_board.reminder_days, notice_board.description"; 
    	$queryData['customWhere'][] = "CURDATE() BETWEEN DATE_SUB(from_date, INTERVAL reminder_days DAY) AND to_date";
		$queryData['order_by']['notice_board.from_date'] = "ASC";
		$result = $this->rows($queryData);
		//$this->printQuery();
		return $result;
	}

}
?>