
<?php
class ExpenseModel extends MasterModel
{
    private $expense_manager = "expense_manager";
    private $expense_trans = "expense_trans";
    private $select_master = "select_master";

    /********** Expense **********/
        public function getNextExpNo(){
            $data['tableName'] = $this->expense_manager;
            $data['select'] = "MAX(exp_no) as exp_no";
            $data['where']['YEAR(exp_date)'] = date("Y");
            $data['where']['MONTH(exp_date)'] = date("m");
            $maxNo = $this->specificRow($data)->exp_no;
            $nextExpNo = (!empty($maxNo)) ? ($maxNo + 1) : 1;
            return $nextExpNo;
        }

		public function getExpenseDTRows($data){
            $data['tableName'] = $this->expense_manager;
            $data['select'] = "expense_manager.*,employee_master.emp_name as exp_by_name,apr.emp_name as approve_name";
            $data['leftJoin']['employee_master'] = "employee_master.id = expense_manager.exp_by_id";
			$data['leftJoin']['employee_master apr'] = "apr.id = expense_manager.approved_by";

            if(empty($data['status'])) { $data['where']['expense_manager.status'] = 0; }
            else{ $data['where']['expense_manager.status'] = $data['status']; }
			
			if(!in_array($this->userRole,[1,-1])):
				$data['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id) > 0 OR employee_master.id = '.$this->loginId.')';
			endif;
			
            $data['order_by']['expense_manager.exp_date'] = "DESC";
			$data['order_by']['expense_manager.id'] = "DESC";

            $data['searchCol'][] = "";
            $data['searchCol'][] = "";
            $data['searchCol'][] = "DATE_FORMAT(exp_date,'%d-%m-%Y')";
            // $data['searchCol'][] = "exp_number";
            $data['searchCol'][] = "employee_master.emp_name";
            $data['searchCol'][] = "demand_amount";
            $data['searchCol'][] = "amount";

            $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;

            if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
            return $this->pagingRows($data);
        }
        
		public function getExpenseData($data=[]){
            $queryData['tableName'] = $this->expense_manager;
            $queryData['select'] = "expense_manager.*,employee_master.emp_name,employee_master.super_auth_id";
            
            $queryData['leftJoin']['employee_master'] = "employee_master.id = expense_manager.exp_by_id";
			
			if(empty($data['approve_by'])){
				$queryData['select'] .= ",select_master.label as expense_label";
				$queryData['leftJoin']['select_master'] = "select_master.id = expense_manager.exp_type";
			}

            if(isset($data['status']) && $data['status'] != "") { $queryData['where_in']['expense_manager.status'] = $data['status']; }
			
            if(!empty($data['exp_id'])) { $queryData['where']['expense_manager.id'] = $data['exp_id']; }
            if(!empty($data['emp_id'])) { $queryData['where']['expense_manager.exp_by_id'] = $data['emp_id']; }

            if(!empty($data['from_date']) && !empty($data['to_date'])) {
                $queryData['customWhere'][] = "DATE_FORMAT(expense_manager.exp_date,'%Y-%m-%d') BETWEEN '".$data['from_date']."' AND '".$data['to_date']."'";
            }
            if(!empty($data['ids'])) { 
				$queryData['where_in']['expense_manager.id'] = str_replace("~", ",", $data['ids']); 
			}
			if(!empty($data['date'])) { 
				$queryData['customWhere'][] = "MONTH(expense_manager.exp_date) = '".date('m',strtotime($data['date']))."' AND YEAR(expense_manager.exp_date) = '".date('Y',strtotime($data['date']))."'";
			}
			if(!empty($data['group_by'])){
                $queryData['group_by'][] = $data['group_by'];
            }
			if(!empty($data['approve_by'])){
                $queryData['where']['expense_manager.approved_by'] = 0;
            }
			
            $queryData['order_by']['expense_manager.exp_date'] = "DESC";
			$queryData['order_by']['expense_manager.id'] = "DESC";
			
			if(!in_array($this->userRole,[1,-1])):
				$queryData['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id ) > 0 OR employee_master.id = '.$this->loginId.')';
			endif;

            if(!empty($data['search'])):
                $queryData['like']['employee_master.emp_name'] = $data['search'];
                $queryData['like']['expense_manager.exp_number'] = $data['search'];
                $queryData['like']['DATE_FORMAT(expense_manager.exp_date,"%d-%m-%Y")'] = $data['search'];
                $queryData['like']['expense_manager.expense_label'] = $data['search'];
                $queryData['like']['expense_manager.amount'] = $data['search'];
                $queryData['like']['expense_manager.notes'] = $data['search'];
            endif;
    
            if(isset($data['start']) && isset($data['length'])):
                $queryData['start'] = $data['start'];
                $queryData['length'] = $data['length'];
            endif;
			
			if(!empty($data['limit'])){
                $queryData['limit'] = $data['limit'];
            }
          
            $result = $this->rows($queryData);
			//$this->printQuery();
			return $result;
        }

        public function getExpense($data){
            $queryData['tableName'] = $this->expense_manager;
            $queryData['select'] = "expense_manager.*,employee_master.emp_name,party_master.party_name,(CASE WHEN expense_manager.status = 0 THEN demand_amount ELSE amount END) as amount,select_master.label as expense_label";
            $queryData['leftJoin']['employee_master'] = "employee_master.id = expense_manager.exp_by_id";
            $queryData['leftJoin']['party_master'] = "party_master.id = expense_manager.exp_by_id";
            $queryData['leftJoin']['select_master'] = "select_master.id = expense_manager.exp_type";

            $queryData['where']['expense_manager.id'] = $data['id'];
            return $this->row($queryData);
        }

        public function getExpenseTransData($data) {
            $queryData['tableName'] = $this->expense_trans;
            $queryData['select'] = "expense_trans.*,select_master.label as expense_label,expense_manager.exp_date";
            $queryData['leftJoin']['expense_manager'] = 'expense_manager.id = expense_trans.exp_id';
            $queryData['leftJoin']['select_master'] = 'select_master.id = expense_trans.exp_type_id';
            $queryData['where']['select_master.type'] = 3;
            $queryData['where_in']['expense_trans.exp_id'] = $data['exp_id'];
            return $this->rows($queryData);
        }

        public function saveExpense($data){
            try{
                $this->db->trans_begin();

                $expTransData = $data['expTrans'];unset($data['expTrans']);
                $data['demand_amount'] = array_sum(array_column($expTransData,'amount'));

                if($this->checkDuplicateExpense($data) > 0):
                    $errorMessage['exp_date'] = "You can't enter more than one expense in a day.";
                    return ['status'=>0,'message'=>$errorMessage];
                else:

                    if(empty($data['id'])):
                        $data['exp_prefix'] = $exp_prefix = "EXP".n2y(date('Y')).n2m(date('m'));  
                        $data['exp_no'] = $exp_no = $this->expense->getNextExpNo();
                        $data['exp_number'] = $exp_prefix.sprintf("%03d",$exp_no);
                    endif;

					$result = $this->store($this->expense_manager,$data,'Expense');
					$exp_id = (!empty($data['id'])) ? $data['id'] : $result['insert_id'];

					$this->trash($this->expense_trans,['exp_id'=>$exp_id]);

					foreach ($expTransData as $row) {
						if(!empty($row['amount'])){
                            $row['exp_id'] = $exp_id;
                            $row['is_delete'] = 0;
							$this->store($this->expense_trans, $row, 'Expense Trans');
						}
					}

					if ($this->db->trans_status() !== FALSE):
						$this->db->trans_commit();
						return $result;
					endif;
				endif;
			
            }catch(\Exception $e){
                $this->db->trans_rollback();
                return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
            }	
        }

        public function checkDuplicateExpense($data){ 
            $data['tableName'] = $this->expense_manager;
            $data['where']['DATE(exp_date)'] = date('Y-m-d',strtotime($data['exp_date']));
            $data['where']['exp_by_id'] = $data['exp_by_id'];
			$data['where']['is_delete'] = 0;
            if(!empty($data['id']))
                $data['where']['id !='] = $data['id'];
            return $this->numRows($data);
        }
		
        public function hasValidExpDate($param = []){ 
            $queryData['tableName'] = "employee_master";
            $queryData['select'] = "CASE
										WHEN lm.id IS NOT NULL THEN 'LEAVE'
										WHEN al.id IS NULL THEN 'ABSENT'
										ELSE 'OK'
									END AS expense_status";
            $queryData['leftJoin']['leave_master lm'] = "employee_master.id = lm.emp_id AND lm.status != 3 AND '".$param['exp_date']."' BETWEEN lm.start_date AND lm.end_date";
            $queryData['leftJoin']['attendance_log al'] = "employee_master.id = al.emp_id AND al.attendance_status IN (1,3) AND DATE(al.punch_date) = '".$param['exp_date']."'";

            $queryData['where']['employee_master.id'] = $param['exp_by_id'];
            $queryData['limit'] = 1;
            return $this->row($queryData);
        }

        public function saveApprovedData($data){
            try{
                $this->db->trans_begin();

                foreach($data['expTrans'] as $row) { 
                    if(!empty($row['id'])){
                        $this->store($this->expense_trans, $row, 'Expense Trans');
                    }
                }
                $amount = array_sum(array_column($data['expTrans'],'approve_amount'));
                $approveData = [
                    'id' => $data['id'],
                    'amount'=>$amount,
                    'approved_by' => $this->loginId, 
                    'approved_at' => date('Y-m-d H:i:s'),
                    'status' => 1
                ];
                $this->store($this->expense_manager, $approveData);
                $result =  ['status' => 1, 'message' => 'Expense Approve Successfully.'];
                if ($this->db->trans_status() !== FALSE):
                    $this->db->trans_commit();
                    return $result;
                endif;
            }catch(\Exception $e){
                $this->db->trans_rollback();
                return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
            }	
        }

        public function changeExpenseStatus($data) {
            try{
                $this->db->trans_begin();
                // 27-09-25
                if(!empty($data['exp_source'] && $data['exp_source'] == 2)):
                    $result = $this->edit('location_log',['exp_id'=>$data['id']],['exp_id'=>0,'approved_by'=>0,'approved_at'=>null]);
                    $this->trash($this->expense_manager,['id'=>$data['id']]);
                    $this->trash($this->expense_trans,['exp_id'=>$data['id']]);
                else:
                    $result = $this->store($this->expense_manager, $data, 'Expense');
                endif;

                if ($this->db->trans_status() !== FALSE):
                    $this->db->trans_commit();
                    return $result;
                endif;
            }catch(\Exception $e){
                $this->db->trans_rollback();
                return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
            }
        }

        public function getExpenseTypeList($data=[]) {
            $queryData['tableName'] = $this->select_master;
            $queryData['where']['select_master.type'] = 3;
            return $this->rows($queryData);
        }

		public function getExpenseForReport($data){
            $queryData['tableName'] = 'select_master';
            //$queryData['select'] = "select_master.label,et.exp_date,et.exp_type_id,et.amount,et.proof_file";
			$queryData['select'] = "select_master.label,et.exp_date,et.exp_type_id,et.amount,et.proof_file,et.approve_amount";

			$queryData['leftJoin']['(SELECT 
                DATE(expense_manager.exp_date) as exp_date, expense_trans.exp_type_id, SUM(expense_trans.amount) as amount,  SUM(expense_trans.approve_amount) as approve_amount,expense_manager.exp_by_id, expense_manager.proof_file
            FROM 
                expense_manager 
            LEFT JOIN expense_trans ON expense_trans.exp_id = expense_manager.id AND expense_trans.is_delete = 0
            WHERE expense_manager.is_delete = 0 AND expense_manager.status IN(0,1) AND
                MONTH(exp_date) = "'.date('m',strtotime($data['month'])).'" AND 
                YEAR(exp_date) = "'.date('Y',strtotime($data['month'])).'" AND
                expense_manager.exp_by_id = "'.$data['emp_id'].'"
            GROUP BY
                expense_trans.exp_type_id,DATE(expense_manager.exp_date))
            as et'] = "et.exp_type_id = select_master.id";

            $queryData['where']['select_master.type'] = 3;
            return $this->rows($queryData);
        }

		public function getMonthWiseExpenseData($data=[]){
            $where = ((!empty($data['emp_id'])) ? 'AND expense_manager.exp_by_id = "'.$data['emp_id'].'"' : '');
            $queryData['tableName'] = 'employee_master';
            $queryData['select'] = "employee_master.*,et.exp_date,et.exp_type_id,et.amount,et.approve_amount,GROUP_CONCAT(sales_zone.zone_name) as zone_name";

            $queryData['leftJoin']['(SELECT 
                    DATE(expense_manager.exp_date) as exp_date,expense_trans.exp_type_id,SUM(expense_trans.amount) as amount,SUM(expense_trans.approve_amount) as approve_amount,expense_manager.exp_by_id 
                FROM 
                    expense_manager 
                LEFT JOIN expense_trans ON expense_trans.exp_id = expense_manager.id AND expense_trans.is_delete = 0
                WHERE expense_manager.is_delete = 0 AND expense_manager.status IN(1) AND
                    MONTH(exp_date) = "'.date('m',strtotime($data['month'])).'" AND 
                    YEAR(exp_date) = "'.date('Y',strtotime($data['month'])).'" '.$where.'
                GROUP BY
                    expense_trans.exp_type_id,expense_manager.exp_by_id)
                 et'] = "et.exp_by_id = employee_master.id";
			
            $queryData['leftJoin']['sales_zone'] = 'find_in_set(sales_zone.id,employee_master.zone_id) > 0';
			
            if(!in_array($this->userRole,[1,-1])):
                $queryData['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id ) >0 OR employee_master.id = '.$this->loginId.')';
            endif;
			$queryData['group_by'][]='employee_master.id';
            $result = $this->rows($queryData);
			return $result;
        }
       
		public function getExpCount($param=[]){
			$queryData['tableName'] = $this->expense_manager;
			$queryData['select'] = "SUM(CASE WHEN approved_by > 0 THEN 1 ELSE 0 END) as approved_exp,SUM(CASE WHEN approved_by = 0 THEN 1 ELSE 0 END) as unapproved_exp";
			if(!empty($param['emp_id']) AND !in_array($this->userRole,[-1,1])){ $queryData['where']['expense_manager.exp_by_id'] = $param['emp_id']; }
			if(!empty($param['from_date'])){$queryData['where']['DATE(expense_manager.exp_date) >= '] = $param['from_date'];}
			if(!empty($param['to_date'])){$queryData['where']['DATE(expense_manager.exp_date) <= '] = $param['to_date'];}
			
			$result = $this->row($queryData);
			//$this->printQuery();
			return $result;
		}
    /********** End Expense **********/
	// 27-09-25
    /********** Start Vehicle Expense **********/
        public function getVehicleExpDTRows($data){
            $data['tableName'] = 'location_log';
            $data['select'] = "location_log.*,employee_master.emp_name,employee_master.emp_code,SUM(location_log.travel_km) as total_km,SUM(location_log.price_km) as total_price,select_master.label as vehicle_name";
            $data['leftJoin']['employee_master'] = "employee_master.id = location_log.emp_id";
            $data['leftJoin']['select_master'] = "select_master.id = location_log.vehicle_id";
			
			$data['where']['location_log.travel_km > '] = 0; 

            if(empty($data['status'])) { 
                $data['where']['location_log.approved_by'] = 0; 
                $data['where']['location_log.exp_id'] = 0; 
            }
            $data['order_by']['DATE(location_log.log_time)'] = "DESC";
            $data['group_by'][] = "location_log.emp_id,DATE(location_log.log_time),location_log.vehicle_id";
    
            $data['searchCol'][] = "";
            $data['searchCol'][] = "";
            $data['searchCol'][] = "DATE_FORMAT(log_time,'%d-%m-%Y')";
            $data['searchCol'][] = "employee_master.emp_code";
            $data['searchCol'][] = "employee_master.emp_name";
            $data['searchCol'][] = "select_master.label";
            $data['searchCol'][] = "";
            $data['searchCol'][] = "";

            $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;

            if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
            return $this->pagingRows($data);
        }

        public function getVehicleExpenseData($data){
            $data['tableName'] = 'location_log';
            $data['select'] = "location_log.id,location_log.log_time,location_log.address,location_log.travel_km,location_log.price_km";

            if(!empty($data['emp_id'])){ $data['where']['location_log.emp_id'] = $data['emp_id']; }
            if(!empty($data['vehicle_id'])){ $data['where']['location_log.vehicle_id'] = $data['vehicle_id']; }
            if(!empty($data['log_time'])){
                $data['where']['DATE(location_log.log_time)'] = date('Y-m-d', strtotime($data['log_time']));
            }
            $data['order_by']['DATE(location_log.log_time)'] = "DESC";
            $result = $this->rows($data);
            return $result;
        
        }

        public function saveVehicleExpApprovedData($data){
            try{
                $this->db->trans_begin();
                $travel_km = array_sum(array_column($data['expData'],'travel_km'));
                $price_km = array_sum(array_column($data['expData'],'price_km'));
                $log_time = date('Y-m-d H:i:s',strtotime($data['log_time']));
                //Expense Manger Add 
                $expData =[
                    'id' =>'',
                    'exp_type' =>7,
                    'exp_date' =>$log_time ,
                    'exp_by_id' =>$data['emp_id'],
                    'notes' => ($data['status'] == 1) ? $data['notes'] : null,
                    'demand_amount'=>$travel_km * $price_km,
                    'amount' =>($data['status'] == 1) ? $travel_km * $price_km : 0,
                    'approved_by' => ($data['status'] == 1) ? $this->loginId : 0, 
                    'approved_at' => ($data['status'] == 1) ? $log_time : null,
                    'rej_reason' => ($data['status'] == 2) ? $data['notes'] : null,
                    'status'=>$data['status'],
                    'exp_source'=>2,
                ];
                $expData['expTrans'][] = [
                    'id'=>'',
                    'exp_type_id' => $data['vehicle_id'],
                    'amount' => $travel_km * $price_km,
                    'approve_amount' => ($data['status'] == 1) ? $travel_km * $price_km : 0,
                    'approve_remark' => ($data['status'] == 1) ? $data['notes'] : null,
                    'travel_by' => $data['vehicle_id'],
                    'travel_distance' => $travel_km,
                    'price_km' => $price_km
                ];
                $result = $this->saveExpense($expData);

                foreach($data['expData'] as $row) { 
                    $approveData = [
                        'id' =>$row['id'],
                        'exp_id'=>$result['id'],
                        'approved_by'=>($data['status'] == 1) ? $this->loginId : 0,
                        'approved_at' => ($data['status'] == 1) ? $log_time : null,
                    ];
                    $this->store('location_log', $approveData);
                }
            
                $result =  ['status' => 1, 'message' =>'Vehicle Expense Approve Successfully.'];
                if ($this->db->trans_status() !== FALSE):
                    $this->db->trans_commit();
                    return $result;
                endif;
            }catch(\Exception $e){
                $this->db->trans_rollback();
                return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
            }	
        }

        public function getExpenseApproveDTRows($data){
            $data['tableName'] = $this->expense_manager;
            $data['select'] = "expense_manager.*,employee_master.emp_name as exp_by_name,select_master.label as vehicle_name";
            $data['leftJoin']['employee_master'] = "employee_master.id = expense_manager.exp_by_id";
            $data['leftJoin']['expense_trans'] = "expense_trans.exp_id = expense_manager.id";
            $data['leftJoin']['select_master'] = "select_master.id = expense_trans.travel_by";

            $data['where']['expense_manager.status'] = $data['status']; 
            $data['where']['expense_manager.exp_source'] = 2; 
			
            $data['order_by']['expense_manager.exp_date'] = "DESC";
			$data['order_by']['expense_manager.id'] = "DESC";

            $data['searchCol'][] = "";
            $data['searchCol'][] = "";
            $data['searchCol'][] = "DATE_FORMAT(expense_manager.exp_date,'%d-%m-%Y')";
            $data['searchCol'][] = "expense_manager.exp_number";
            $data['searchCol'][] = "employee_master.emp_name";
            $data['searchCol'][] = "select_master.vehicle_name";
            $data['searchCol'][] = "expense_manager.demand_amount";
            $data['searchCol'][] = "expense_manager.amount";

            $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;

            if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
            return $this->pagingRows($data);
        }
    /********** End Vehicle Expense **********/
    
    
}
?>