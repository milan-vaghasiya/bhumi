<?php
class LocationLogModel extends MasterModel{
    private $locationLog = "location_log";
	private $visits = "visits";

    public function getLocationLogs($date,$emp_id){
        $queryData['tableName'] = $this->locationLog;
        $queryData['select'] = "location_log.*,IFNULL(party_master.party_name,'') as party_name";
        $queryData['leftJoin']['party_master'] = "party_master.id = location_log.party_id";
        $queryData['where']['DATE(location_log.log_time)'] = $date;
        $queryData['where']['location_log.emp_id'] = $emp_id;
        $queryData['order_by']['location_log.log_time'] = 'DESC';
        $locationLogs = $this->rows($queryData);
		return $locationLogs;
    }

    public function save($data){
        return $this->store($this->locationLog,$data);
    }
}
?>