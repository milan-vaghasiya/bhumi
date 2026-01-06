
<?php
class SalesReportModel extends MasterModel
{
    private $visits = "visits";
    private $so_master = "so_master";
    private $so_trans = "so_trans";
	
    /* Visit History Data */
    public function getVisitHistory($data){
        $queryData = array();
		$queryData['tableName'] = $this->visits;
        $queryData['select'] = "visits.*,apr_by.emp_name as apr_by_name,empMaster.emp_name as emp_name,empMaster.emp_code as emp_code";
        $queryData['leftJoin']['employee_master'] = "employee_master.id = visits.created_by";
        $queryData['leftJoin']['employee_master apr_by'] = "apr_by.id = visits.approve_by";
        $queryData['leftJoin']['employee_master empMaster'] = "empMaster.id = visits.emp_id";
		$queryData['customWhere'][] = "start_at BETWEEN '".date('Y-m-d H:i:s',strtotime($data['from_date'].' 00:00:00'))."' AND '".date('Y-m-d H:i:s',strtotime($data['to_date'].' 23:59:59'))."'";
        
		if(!empty($data['emp_id'])){$queryData['where']['visits.emp_id'] = $data['emp_id'];}
		
		$result = $this->rows($queryData);
		return $result;
    }
    
    /* Sales Register Data */
    public function getSalesRegisterData($data){
        $queryData['tableName'] = $this->so_master;
        $queryData['select'] = 'so_master.*,party_master.party_name,party_udf.gstin,statutory_detail.state,statutory_detail.district,statutory_detail.taluka';
        $queryData['leftJoin']['party_master'] = "party_master.id = so_master.party_id";
        $queryData['leftJoin']['party_udf'] = "party_udf.party_id = party_master.id";
        $queryData['leftJoin']['statutory_detail'] = "statutory_detail.id = party_master.statutory_id";
        $queryData['leftJoin']['employee_master'] = "employee_master.id = so_master.sales_executive";
        $queryData['where']['so_master.entry_type'] = 1;

        $queryData['where']['so_master.trans_date >='] = $data['from_date'];
        $queryData['where']['so_master.trans_date <='] = $data['to_date'];

        if($data['state'] != 'ALL'):
            $queryData['where']['statutory_detail.state'] = $data['state'];
        endif;

        if($data['district'] != 'ALL' && !empty($data['district'])):
            $queryData['where']['statutory_detail.district'] = $data['district'];
        endif;

        if($data['taluka'] != 'ALL' && !empty($data['taluka'])):
            $queryData['where']['statutory_detail.id'] = $data['taluka'];
        endif;
        if(!in_array($this->userRole,[1,-1])):
            $queryData['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id ) >0 OR employee_master.id = '.$this->loginId.')';
        endif;
        $queryData['order_by']['trans_date'] = 'ASC';
        return $this->rows($queryData);
    }

    public function getSalesRegisterDataItemWise($data){
        $queryData['tableName'] = $this->so_trans;
        $queryData['select'] = "so_trans.*,so_master.trans_date,so_master.entry_type,statutory_detail.state,statutory_detail.district,statutory_detail.taluka,item_master.item_name";

        $queryData['leftJoin']['so_master'] = "so_master.id = so_trans.trans_main_id";
        $queryData['leftJoin']['party_master'] = "party_master.id = so_master.party_id";
        $queryData['leftJoin']['party_udf'] = "party_udf.party_id = party_master.id";
        $queryData['leftJoin']['statutory_detail'] = "statutory_detail.id = party_master.statutory_id";
        $queryData['leftJoin']['item_master'] = "item_master.id = so_trans.item_id";
        $queryData['leftJoin']['employee_master'] = "employee_master.id = so_master.sales_executive";
        $queryData['where']['so_master.entry_type'] = 1;

        $queryData['where']['so_master.trans_date >='] = $data['from_date'];
        $queryData['where']['so_master.trans_date <='] = $data['to_date'];

        if($data['state'] != 'ALL'):
            $queryData['where']['statutory_detail.state'] = $data['state'];
        endif;

        if($data['district'] != 'ALL' && !empty($data['district'])):
            $queryData['where']['statutory_detail.district'] = $data['district'];
        endif;

        if($data['taluka'] != 'ALL' && !empty($data['taluka'])):
            $queryData['where']['statutory_detail.id'] = $data['taluka'];
        endif;
        if(!in_array($this->userRole,[1,-1])):
            $queryData['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id ) >0 OR employee_master.id = '.$this->loginId.')';
        endif;

        $queryData['order_by']['so_master.trans_date']='ASC';
        $queryData['order_by']['so_master.id']='ASC';

        return $this->rows($queryData);
    }

    /* Sales Analysis Data */
    public function getSalesAnalysisData($data){
        $queryData = array();
        if($data['report_type'] == 1):
            $queryData['tableName'] = "so_master";
            $queryData['select'] = "party_master.party_name,SUM(taxable_amount) as taxable_amount,SUM(gst_amount) as gst_amount,SUM(net_amount) as net_amount";
            $queryData['leftJoin']['party_master'] = "party_master.id = so_master.party_id";
            $queryData['leftJoin']['employee_master'] = "employee_master.id = so_master.sales_executive";

            $queryData['where']['trans_date >='] = $data['from_date'];
            $queryData['where']['trans_date <='] = $data['to_date'];

            if($data['business_type'] != 'ALL'):
                $queryData['where']['party_master.business_type'] = $data['business_type'];
            endif;

            if($data['executive_id'] != 'ALL'):
                $queryData['where']['so_master.sales_executive'] = $data['executive_id'];
            endif;
             if(!in_array($this->userRole,[1,-1])):
                $queryData['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id ) >0 OR employee_master.id = '.$this->loginId.')';
            endif;
        

            $queryData['group_by'][] = 'so_master.party_id';
            $queryData['order_by']['SUM(taxable_amount)'] = $data['order_by'];

            $result = $this->rows($queryData);
        else:
            $queryData['tableName'] = "so_trans";
            $queryData['select'] = "item_master.item_name,SUM(so_trans.qty) as qty,SUM(so_trans.taxable_amount) as taxable_amount,ROUND((SUM(so_trans.taxable_amount) / SUM(so_trans.qty)),2) as price";
            $queryData['leftJoin']['so_master'] = "so_trans.trans_main_id = so_master.id";
            $queryData['leftJoin']['item_master'] = "item_master.id = so_trans.item_id";
            $queryData['leftJoin']['employee_master'] = "employee_master.id = so_master.sales_executive";

            $queryData['where']['so_master.trans_date >='] = $data['from_date'];
            $queryData['where']['so_master.trans_date <='] = $data['to_date'];

            if(!in_array($this->userRole,[1,-1])):
                $queryData['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id ) >0 OR employee_master.id = '.$this->loginId.')';
            endif;
        
            $queryData['group_by'][] = 'so_trans.item_id';
            $queryData['order_by']['SUM(so_trans.taxable_amount)'] = $data['order_by'];

            $result = $this->rows($queryData);
        endif;
       
        return $result;
    }

    /* Executive Analysis Data */
    public function getExecutiveAnalysisData($data){
        $queryData = array();
        $queryData['tableName'] = "employee_master";
        $queryData['select'] = "employee_master.emp_name,SUM(salesLog.total_new_lead) as total_new_lead,salesLog.lead_id,salesLog.created_at,lead_master.business_type,SUM(visit.total_visit) as total_visit,SUM(seMaster.total_enq) as total_enq,SUM(soMaster.total_ord) as total_ord,statutory_detail.state,statutory_detail.district";
        
		$queryData['leftJoin']['(SELECT count(*) as total_new_lead,executive_id,lead_id,created_at FROM sales_logs WHERE log_type = 1 AND is_delete = 0 GROUP BY executive_id) as salesLog'] = "salesLog.executive_id = employee_master.id";
        
        $queryData['leftJoin']['lead_master'] = "lead_master.id = salesLog.lead_id";

        $queryData['leftJoin']['statutory_detail'] = "statutory_detail.id = lead_master.statutory_id";

        $queryData['leftJoin']['(SELECT count(visits.lead_id) as total_visit,created_by FROM visits WHERE is_delete = 0 GROUP BY created_by) as visit'] = "visit.created_by = employee_master.id";

        $queryData['leftJoin']['(SELECT count(se_trans.id) as total_enq,sales_executive FROM se_master LEFT JOIN se_trans ON se_master.id = se_trans.trans_main_id WHERE se_master.is_delete = 0 GROUP BY sales_executive) as seMaster'] = "seMaster.sales_executive = employee_master.id";

        $queryData['leftJoin']['(SELECT count(so_trans.id) as total_ord,sales_executive FROM so_master LEFT JOIN so_trans ON so_master.id = so_trans.trans_main_id WHERE so_master.is_delete = 0 GROUP BY sales_executive) as soMaster'] = "soMaster.sales_executive = employee_master.id";

        if($data['business_type'] != 'ALL'):
            $queryData['where']['lead_master.business_type'] = $data['business_type'];
        endif;

        if($data['state'] != 'ALL'):
            $queryData['where']['statutory_detail.state'] = $data['state'];
        endif;

        if(!empty($data['district'])):
            $queryData['where']['statutory_detail.district'] = $data['district'];
        endif;

        if(!empty($data['taluka'])):
            $queryData['where']['statutory_detail.id'] = $data['taluka'];
        endif;

        if(!in_array($this->userRole,[1,-1])):
            $queryData['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id ) >0 OR employee_master.id = '.$this->loginId.')';
        endif;
        $queryData['where']['DATE(salesLog.created_at) >='] = $data['from_date'];
        $queryData['where']['DATE(salesLog.created_at) <='] = $data['to_date'];
		$queryData['group_by'][] = 'salesLog.executive_id';

        return $this->rows($queryData);        
    }
}
?>