<?php
class SalesModel extends MasterModel{
    private $partyMaster = "party_master";
	private $sales_logs = "sales_logs";
	private $se_master = "se_master";
	private $se_trans = "se_trans";
	private $sq_master = "sq_master";
	private $sq_trans = "sq_trans";
	private $so_master = "so_master";
	private $so_trans = "so_trans";
	private $leadMaster = "lead_master"; 
    private $dispatch_trans = "dispatch_trans";
    private $executive_targets = "executive_targets";
    private $master_table = [1=>'se_master',2=>'sq_master',3=>'so_master'];
    private $trans_table = [1=>'se_trans',2=>'sq_trans',3=>'so_trans'];

    /******  Next Trans No For Sales Enquiry, Sales Quotation, Sales Order *******/
        public function getNextTransNumber($param = []){
            $data['select'] = "MAX(trans_no) as trans_no";
            $data['where']['trans_date >='] = $this->startYearDate;
            $data['where']['trans_date <='] = $this->endYearDate;
            if(!empty($param['entry_type'])) { $data['where']['entry_type'] = $param['entry_type']; }
            $data['tableName'] = $param['tableName'];
            $trans_no = $this->specificRow($data)->trans_no;
            $trans_no = (empty($last_no))?($trans_no + 1):$trans_no;
            return $trans_no;
        }
    /******* End *******/

    /*** Save Sales Enquiry, Sales Quotation, Sales Order */
        public function getAmountCalculation($param = []){
            $disc_price = 0; $net_price = 0;  $okp = 0;$aqp = 0;
            if(!empty($param['disc_per'])){
                $disc_price = (($param['price'] *  $param['disc_per']) / 100);
            }
            if(!empty($param['kg_price'])){
                if($param['wt_pcs']>0)
                {
                    $aqp = $param['kg_price'] * $param['wt_pcs'];
                    $disc_price = $param['price'] - $aqp;
                }
            }
            $net_price = $param['price'] - $disc_price;
            $taxableAmount = floatval($param['qty']) * floatval($net_price);
            $disc_amt =floatval($param['qty']) * $disc_price;
            $amount = floatval($param['qty'])*$param['price'];
            $tax_amount = ($param['gst_per'] * $taxableAmount)/100;
            $net_amount = $tax_amount + $taxableAmount;
            return ['net_price'=>$net_price,'amount'=>$amount,'disc_amount'=>$disc_amt,'taxableAmount'=>$taxableAmount,'gst_amount'=>$tax_amount,'net_amount'=>$net_amount];
        }
        
        public function saveSalesData($data){
            try{
                $this->db->trans_begin();

                $module_type = $data['module_type'];
                $itemData = $data['itemData']; unset($data['module_type'],$data['itemData'],$data['row_index']);	
                $functionName="";$logType="";
        
                /** Function Name to get old data module wise && Set Log type module wise*/
                switch ($module_type) {
                    case 1:
                        $functionName = 'getSalesEnquiry';
                        $logType = 4;
                        break;
                    case 2:
                        $functionName = 'getSalesQuotation';
                        $logType = 5;
                        break;
                    case 3:
                        $functionName = 'getSalesOrder';
                        $logType = 6;
                        break;
                }
                /** If Edit then get old data*/
                if(!empty($data['id'])):
                    $dataRow = $this->$functionName(['id'=>$data['id'],'itemList'=>1]);
                    foreach($dataRow->itemList as $row):
                        if(!empty($row->ref_id) && $row->from_entry_type == 1):
                            $this->store($this->se_trans,['id'=>$row->ref_id,'trans_status'=>0]);
                        elseif(!empty($row->ref_id) && $row->from_entry_type == 2):
                            $this->store($this->sq_trans,['id'=>$row->ref_id,'trans_status'=>0]);
                        endif;
                    endforeach;
                    $this->trash($this->trans_table[$module_type],['trans_main_id'=>$data['id']]);
                endif;
                /** Save Master Data */
                $result = $this->store($this->master_table[$module_type],$data);
                $mainId = (!empty($data['id']) ? $data['id'] : $result['insert_id']);
                /** Save Log Data */
                if(empty($data['id'])):
                    $logData = [
                        'id' => '',
                        'log_type' => $logType,
                        'party_id' => !empty($data['party_id'])?$data['party_id']:'',
                        'lead_id' => !empty($data['lead_id'])?$data['lead_id']:'',
                        'ref_id' => $mainId,
                        'ref_date' => $data['trans_date'],
                        'ref_no' => $data['trans_number'],
                        'executive_id' => (!empty($data['sales_executive']) ? $data['sales_executive'] : 0),
                        'notes' => $this->notes[$logType],
                        'created_by' => $this->loginId,
                        'created_at' => date("Y-m-d H:i:s")
                    ];
                    $this->saveSalesLogs($logData);
                endif;
                $total_amount=0;$net_amount = 0;$taxable_amount=0;$gst_amount=0;$disc_amount=0;
                foreach($itemData as $row):
                    /** Gst Calculation*/  
                    if($module_type != 1){
                        $param = ['price'=>$row['price'],'disc_per'=>$row['disc_per'],'gst_per'=>$row['gst_per'],'qty'=>$row['qty']];
                        $amountData = $this->getAmountCalculation($param);
                        $row['gst_amount'] = $amountData['gst_amount'];
                        $row['amount'] = $amountData['amount'];
                        $row['disc_amount'] = $amountData['disc_amount'];
                        $row['taxable_amount'] = $amountData['taxableAmount'];
                        $row['net_amount'] = $amountData['net_amount'];

                        $total_amount += $row['amount'];
                        $net_amount += $row['net_amount'];
                        $taxable_amount += $row['taxable_amount'];
                        $gst_amount += $row['gst_amount'];
                        $disc_amount += $row['disc_amount'];
                    }else{
                        unset($row['from_entry_type'],$row['ref_id'],$row['price'],$row['gst_per'],$row['disc_per'],$row['amount']);
                    }
                    $row['trans_main_id'] = $mainId;
                    $row['is_delete'] = 0;
                    /** Save Trans Data */
                    $this->store($this->trans_table[$module_type],$row);
                    /** Save Ref Effect If From Entry Type is 1 then sales enquiry else sales quotation*/
                    if(isset($data['from_entry_type']) && !empty($row['ref_id']) && $data['from_entry_type'] == 1){
                        $this->store($this->se_trans,['id'=>$row['ref_id'],'trans_status'=>1]);
                    }elseif(isset($data['from_entry_type']) && !empty($row['ref_id']) && $data['from_entry_type'] == 2){
                        $this->store($this->sq_trans,['id'=>$row['ref_id'],'trans_status'=>2]);
                    }
                    
                endforeach;
                /** Update Total Amounts */
                if($module_type != 1){
                    $result = $this->store($this->master_table[$module_type],['id'=>$mainId,'total_amount'=>$total_amount,'net_amount'=>$net_amount,'taxable_amount'=>$taxable_amount,'gst_amount'=>$gst_amount,'disc_amount'=>$disc_amount,'conditions'=>$data['conditions']]);
                }
                
                if ($this->db->trans_status() !== FALSE):
                    $this->db->trans_commit();
                    return $result;
                endif;
            }catch(\Exception $e){
                $this->db->trans_rollback();
                return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
            }
        }

        /********** Sales Enquiry **********/
        public function getSalesEnquiry($data){
            $queryData = array();
            $queryData['tableName'] = $this->se_master;
            $queryData['select'] = "se_master.*,party_master.executive_id";
            $queryData['leftJoin']['party_master'] = "party_master.id = se_master.party_id";
            $queryData['where']['se_master.id'] = $data['id'];
            $result = $this->row($queryData);
    
            if($data['itemList'] == 1):
                $result->itemList = $this->getSalesEnquiryItems($data);
            endif;
            return $result;
        }
    
        public function getSalesEnquiryItems($data){
            $queryData = array();
            $queryData['tableName'] = $this->se_trans;
            $queryData['select'] = "se_trans.*,item_master.item_name,item_master.item_code,item_master.unit_name";
            $queryData['leftJoin']['item_master'] = "item_master.id = se_trans.item_id";
            $queryData['where']['se_trans.trans_main_id'] = $data['id'];
            $result = $this->rows($queryData);
            return $result;
        }

        /********** Sales Quotation Request **********/
        public function getSqRequestDTRows($data){
            $data['tableName'] = $this->se_trans;
            $data['select'] = "se_trans.id,se_trans.qty,se_master.id as trans_main_id,se_master.trans_number,DATE_FORMAT(se_master.trans_date,'%d-%m-%Y') as trans_date,se_master.party_id,se_trans.trans_status,se_master.lead_id,item_master.item_name,(CASE WHEN se_master.party_id > 0 THEN party_master.party_name ELSE lead_master.party_name END) AS party_name";
            $data['leftJoin']['se_master'] = "se_master.id = se_trans.trans_main_id";
            $data['leftJoin']['party_master'] = "party_master.id = se_master.party_id";
            $data['leftJoin']['lead_master'] = "lead_master.id = se_master.lead_id";
            $data['leftJoin']['item_master'] = "item_master.id = se_trans.item_id";
            $data['leftJoin']['employee_master'] = "employee_master.id = se_master.sales_executive";
            if(!empty($data['status'])):
                $data['where']['se_trans.trans_status'] = $data['status'];
            else:
                $data['where']['se_trans.trans_status'] = 0;
            endif;
            // 20-05-2024
            if(!in_array($this->userRole,[1,-1])):
                if($this->leadRights == 2): // Zone Wise Leads Rights
                    $data['where']['lead_master.sales_zone_id'] = $this->zoneId;
                    $data['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id ) >0 OR employee_master.id = '.$this->loginId.')';
                elseif($this->leadRights == 1):
                    $data['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id ) >0 OR employee_master.id = '.$this->loginId.')';
                endif;
            endif;
            $data['where']['se_master.trans_date >='] = $this->startYearDate;
            $data['where']['se_master.trans_date <='] = $this->endYearDate;
            $data['order_by']['se_master.trans_date'] = "DESC";
            $data['order_by']['se_master.id'] = "DESC";
            $data['group_by'][] = "se_trans.trans_main_id";
    
            $data['searchCol'][] = "";
            $data['searchCol'][] = "";
            $data['searchCol'][] = "se_master.trans_number";
            $data['searchCol'][] = "DATE_FORMAT(se_master.trans_date,'%d-%m-%Y')";
            $data['searchCol'][] = "se_master.party_name";
            $data['searchCol'][] = "item_master.item_name";
            $data['searchCol'][] = "se_trans.qty";
    
            $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
            if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
            
            return $this->pagingRows($data);
        }
    
        /********** Sales Quotation **********/
        public function getSalesQuotation($data){
            $queryData = array();
            $queryData['tableName'] = $this->sq_master;
            $queryData['select'] = "sq_master.*,employee_master.emp_name as created_name,party_master.executive_id";
            $queryData['leftJoin']['employee_master'] = "employee_master.id = sq_master.created_by";
            $queryData['leftJoin']['party_master'] = "party_master.id = sq_master.party_id";
            $queryData['where']['sq_master.id'] = $data['id'];
            $result = $this->row($queryData);
    
            if($data['itemList'] == 1):
                $result->itemList = $this->getSalesQuotationItems($data);
            endif;
    
            return $result;
        }
    
        public function getSalesQuotationItems($data){
            $queryData = array();
            $queryData['tableName'] = $this->sq_trans;
            $queryData['select'] = "sq_trans.*,item_master.item_name,item_master.item_code,item_master.unit_name";
            $queryData['leftJoin']['item_master'] = "item_master.id = sq_trans.item_id";
            $queryData['where']['sq_trans.trans_main_id'] = $data['id'];
            $result = $this->rows($queryData);
            return $result;
        }
    
        public function getSalesQuotationDTRows($data){
            $data['tableName'] = $this->sq_trans;
            $data['select'] = "sq_trans.id,sq_trans.qty,sq_trans.price,sq_trans.trans_status,sq_master.id as trans_main_id,sq_master.trans_number,DATE_FORMAT(sq_master.trans_date,'%d-%m-%Y') as trans_date,sq_master.party_id,sq_master.lead_id,item_master.item_name,(CASE WHEN sq_master.party_id > 0 THEN party_master.party_name ELSE lead_master.party_name END) AS party_name";
            $data['leftJoin']['sq_master'] = "sq_master.id = sq_trans.trans_main_id";
            $data['leftJoin']['party_master'] = "party_master.id = sq_master.party_id";
            $data['leftJoin']['lead_master'] = "lead_master.id = sq_master.lead_id";
            $data['leftJoin']['item_master'] = "item_master.id = sq_trans.item_id";
            $data['leftJoin']['employee_master'] = "employee_master.id = sq_master.sales_executive";
    
            if(!empty($data['status'])){ $data['where']['sq_trans.trans_status'] = $data['status']; }
            else{ $data['where']['sq_trans.trans_status'] = 0; }
            // 20-05-2024
            if(!in_array($this->userRole,[1,-1])):
                if($this->leadRights == 2): // Zone Wise Leads Rights
                    $data['where']['lead_master.sales_zone_id'] = $this->zoneId;
                    $data['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id ) >0 OR employee_master.id = '.$this->loginId.')';
                elseif($this->leadRights == 1):
                    $data['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id ) >0 OR employee_master.id = '.$this->loginId.')';
                endif;
            endif;
            $data['where']['sq_master.trans_date >='] = $this->startYearDate;
            $data['where']['sq_master.trans_date <='] = $this->endYearDate;
            $data['order_by']['sq_master.trans_date'] = "DESC";
            $data['order_by']['sq_master.id'] = "DESC";
            $data['group_by'][] = "sq_trans.id";
    
            $data['searchCol'][] = "";
            $data['searchCol'][] = "";
            $data['searchCol'][] = "sq_master.trans_number";
            $data['searchCol'][] = "DATE_FORMAT(sq_master.trans_date,'%d-%m-%Y')";
            $data['searchCol'][] = "(CASE WHEN sq_master.party_id > 0 THEN party_master.party_name ELSE lead_master.party_name END)";
            $data['searchCol'][] = "item_master.item_name";
            $data['searchCol'][] = "sq_trans.qty";
            $data['searchCol'][] = "sq_trans.price";
    
            $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
            if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
            
            return $this->pagingRows($data);
        }
    
        public function changeQuotStatus($data){
            try{
                $this->db->trans_begin();

                $date = ($data['trans_status'] == 1) ? date('Y-m-d H:i:s') : "";
                $isApprove =  ($data['trans_status'] == 1) ? $this->loginId : 0;
                $this->store($this->sq_trans, ['id'=> $data['id'], 'trans_status' => $data['trans_status'],'approve_by' => $isApprove, 'approve_at'=>$date]);

                if ($this->db->trans_status() !== FALSE):
                    $this->db->trans_commit();
                    return ['status' => 1, 'message' => 'Sales Quotation '. $data['msg'] . ' Successfully.'];
                endif;
            }catch(\Exception $e){
                $this->db->trans_rollback();
                return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
            } 
        }
    
        public function deleteSalesQuotation($id){
            try{
                $this->db->trans_begin();
    
                    $dataRow = $this->getSalesOrder(['id'=>$id,'itemList'=>1]);
                    foreach($dataRow->itemList as $row):
                        if(!empty($row->ref_id) && $row->from_entry_type == 1):
                            $setData = array();
                            $setData['tableName'] = $this->se_trans;
                            $setData['where']['id'] = $row->ref_id;
                            $setData['update']['trans_status'] = 0;
                            $this->setValue($setData);
                        endif;
                        
                        $this->trash($this->sq_trans,['id'=>$row->id]);
                    endforeach;
    
                    $this->trash($this->sq_trans,['trans_main_id'=>$id]);
                    $this->trash($this->sales_logs,['ref_id'=>$id,'log_type'=>5]);
    
                $result = $this->trash($this->sq_master,['id'=>$id],'Sales Order');
    
                if ($this->db->trans_status() !== FALSE):
                    $this->db->trans_commit();
                    return $result;
                endif;
            }catch(\Exception $e){
                $this->db->trans_rollback();
                return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
            }
        }

        public function sendQuotationRequest($data){ 
            try{
                $this->db->trans_begin();
    
                $result = $this->edit($this->se_trans,['trans_main_id'=>$data['ref_id']],['trans_status'=>1],'Sales Enquiry');
    
                $logData = [
                    'id' => '',
                    'log_type' =>9,
                    'lead_id' => $data['lead_id'],
                    'party_id' => $data['party_id'],
                    'ref_id' => $data['ref_id'],
                    'ref_date' => date("Y-m-d"),
                    'ref_no' => $data['trans_number'],
                    'executive_id' => $data['sales_executive'],
                    'notes' => $this->notes[9],
                    'created_by' => $this->loginId,
                    'created_at' => date("Y-m-d H:i:s")
                ];
                $this->saveSalesLogs($logData);
             
                $result = ['status'=>1,'message'=>'Quotation Request Sent Successfully'];
                
                if ($this->db->trans_status() !== FALSE):
                    $this->db->trans_commit();
                    return $result;
                endif;
            }catch(\Exception $e){
                $this->db->trans_rollback();
                return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
            }
        }
        /********** Sales Order **********/
        public function getSalesOrder($data){
            $queryData = array();
            $queryData['tableName'] = $this->so_master;
            $queryData['select'] = "so_master.*,employee_master.emp_name as created_name,party_master.contact_phone as contact_no,party_udf.party_email,party_udf.contact_person,party_master.executive_id,party_master.business_type";
            $queryData['leftJoin']['employee_master'] = "employee_master.id = so_master.created_by";
            $queryData['leftJoin']['party_master'] = "party_master.id = so_master.party_id";
            $queryData['leftJoin']['party_udf'] = "party_udf.id = so_master.party_id";
    
            $queryData['where']['so_master.id'] = $data['id'];
            $result = $this->row($queryData);
    
            if($data['itemList'] == 1):
                $result->itemList = $this->getSalesOrderItems(['trans_main_id'=>$data['id']]);
            endif;
    
            return $result;
        }
    
        public function getSalesOrderItems($data){
            $queryData = array();
            $queryData['tableName'] = $this->so_trans;
            $queryData['select'] = "so_trans.*,(CASE WHEN so_trans.from_entry_type = 1 THEN seMref.trans_number ELSE (CASE WHEN so_trans.from_entry_type = 2 THEN sqMref.trans_number ELSE '' END)  END) as ref_number,item_master.item_name,item_master.item_code,item_master.unit_name,(so_trans.qty - so_trans.dispatch_qty) as pending_qty,(CASE WHEN so_master.party_id>0 THEN party_master.party_name ELSE lead_master.party_name END) as party_name,(CASE WHEN so_master.party_id>0 THEN party_master.business_type  ELSE lead_master.business_type END) as business_type,so_master.trans_number,so_master.trans_date,so_master.doc_no";
            
            if(!empty($data['order_report'])){
                $queryData['select'] .= ",dispatch.ref_no,dispatch.dispatch_date,dispatch.dispatch_qty";
                $queryData['leftJoin']['(SELECT SUM(dispatch_trans.qty) as dispatch_qty,ref_no,dispatch_date,item_id,so_trans_id FROM dispatch_trans WHERE is_delete = 0 GROUP BY item_id,so_trans_id) as dispatch'] = "dispatch.so_trans_id = so_trans.id";
            }

            $queryData['leftJoin']['so_master'] = "so_master.id = so_trans.trans_main_id";
            $queryData['leftJoin']['party_master'] = "party_master.id = so_master.party_id";
            $queryData['leftJoin']['lead_master'] = "lead_master.id = so_master.lead_id";
            $queryData['leftJoin']['se_trans as seTref'] = "seTref.id = so_trans.ref_id";
            $queryData['leftJoin']['se_master as seMref'] = "so_trans.trans_main_id = seMref.id";
            $queryData['leftJoin']['sq_trans as sqTref'] = "sqTref.id = so_trans.ref_id";
            $queryData['leftJoin']['sq_master as sqMref'] = "so_trans.trans_main_id = sqMref.id";
            $queryData['leftJoin']['item_master'] = "item_master.id = so_trans.item_id";
            $queryData['leftJoin']['employee_master'] = "employee_master.id = so_master.sales_executive";
            if(!empty($data['trans_main_id'])){ $queryData['where']['so_trans.trans_main_id'] = $data['trans_main_id']; }
            if(!empty($data['party_id']) && $data['party_id'] != 'ALL') { $queryData['where']['so_master.party_id'] = $data['party_id']; }
            if(!empty($data['entry_type'])) { $queryData['where']['so_master.entry_type'] = $data['entry_type']; }
            if(!empty($data['customWhere'])) { $queryData['customWhere'][] = $data['customWhere']; }
            
            // 20-05-2024
            if(!in_array($this->userRole,[1,-1])):
                if($this->leadRights == 2): // Zone Wise Leads Rights
                    $queryData['where']['lead_master.sales_zone_id'] = $this->zoneId;
                    $queryData['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id ) >0 OR employee_master.id = '.$this->loginId.')';
                elseif($this->leadRights == 1):
                    $queryData['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id ) >0 OR employee_master.id = '.$this->loginId.')';
                endif;
            endif;

            if(!empty($data['group_by'])){
                $queryData['group_by'][] = $data['group_by'];
            }
            
            if(!empty($data['single_row'])):
                $result = $this->row($queryData);
            else:
                $result = $this->rows($queryData);
            endif;
            return $result;
        }

        public function getOrderCount($data){
            $queryData = array();
            $queryData['tableName'] = $this->so_trans;
            $queryData['select'] = "IFNULL(COUNT(so_trans.id),0) as total_so";
            $queryData['leftJoin']['so_master'] = "so_master.id = so_trans.trans_main_id";
            $queryData['leftJoin']['employee_master'] = "employee_master.id = so_master.sales_executive";
            if(!empty($data['entry_type'])) { $queryData['where']['so_master.entry_type'] = $data['entry_type']; }
            
            // 20-05-2024
            if(!in_array($this->userRole,[1,-1])):
                if($this->leadRights == 2): // Zone Wise Leads Rights
                    $queryData['where']['lead_master.sales_zone_id'] = $this->zoneId;
                    $queryData['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id ) >0 OR employee_master.id = '.$this->loginId.')';
                elseif($this->leadRights == 1):
                    $queryData['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id ) >0 OR employee_master.id = '.$this->loginId.')';
                endif;
            endif;

            $result = $this->row($queryData);
            return $result;
        }
        
        public function getSalesOrderDTRows($data){
            $data['tableName'] = $this->so_master;
            $data['select'] = "so_master.*,DATE_FORMAT(so_master.trans_date,'%d-%m-%Y') as trans_date,(CASE WHEN so_master.party_id > 0 THEN party_master.party_name ELSE lead_master.party_name END) AS party_name";
            $data['leftJoin']['party_master'] = "party_master.id = so_master.party_id";
            $data['leftJoin']['lead_master'] = "lead_master.id = so_master.lead_id";
            $data['leftJoin']['employee_master'] = "employee_master.id = so_master.sales_executive";
    
            if(!empty($data['status'])) { $data['where']['so_master.trans_status'] = $data['status']; }
            else { $data['where']['so_master.trans_status'] = 0; }
            
            // 20-05-2024
            if(!in_array($this->userRole,[1,-1])):
                if($this->leadRights == 2): // Zone Wise Leads Rights
                    $data['where']['lead_master.sales_zone_id'] = $this->zoneId;
                    $data['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id ) >0 OR employee_master.id = '.$this->loginId.')';
                elseif($this->leadRights == 1):
                    $data['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id ) >0 OR employee_master.id = '.$this->loginId.')';
                endif;
            endif;
    
            $data['where']['so_master.entry_type'] = 1;
            $data['where']['so_master.trans_date >='] = $this->startYearDate;
            $data['where']['so_master.trans_date <='] = $this->endYearDate;
            $data['order_by']['so_master.trans_date'] = "DESC";
            $data['order_by']['so_master.id'] = "DESC";
    
            $data['searchCol'][] = "";
            $data['searchCol'][] = "";            
            $data['searchCol'][] = "";
            $data['searchCol'][] = "so_master.trans_number";
            $data['searchCol'][] = "DATE_FORMAT(so_master.trans_date,'%d-%m-%Y')";
            $data['searchCol'][] = "(CASE WHEN so_master.party_id > 0 THEN party_master.party_name ELSE lead_master.party_name END)";
            $data['searchCol'][] = "so_master.taxable_amount";
            $data['searchCol'][] = "so_master.gst_amount";
            $data['searchCol'][] = "so_master.net_amount";
            
    
            $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
            if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
            
            return $this->pagingRows($data);
        }
    
        public function deleteSalesOrder($id){
            try{
                $this->db->trans_begin();
    
                $dataRow = $this->getSalesOrder(['id'=>$id,'itemList'=>1]);
                foreach($dataRow->itemList as $row):
                    if(!empty($row->ref_id) && $row->from_entry_type == 1):
                        $setData = array();
                        $setData['tableName'] = $this->se_trans;
                        $setData['where']['id'] = $row->ref_id;
                        $setData['update']['trans_status'] = 0;
                        $this->setValue($setData);
                    elseif(!empty($row->ref_id) && $row->from_entry_type == 2):
                        $setData = array();
                        $setData['tableName'] = $this->sq_trans;
                        $setData['where']['id'] = $row->ref_id;
                        $setData['update']['trans_status'] = 0;
                        $this->setValue($setData);
                    endif;
                    
                    $this->trash($this->so_trans,['id'=>$row->id]);
                endforeach;
    
                if($dataRow->entry_type == 1){
                    $this->trash($this->sales_logs,['ref_id'=>$id,'log_type'=>6]);
                }
                $this->trash($this->so_trans,['trans_main_id'=>$id]);
    
                $result = $this->trash($this->so_master,['id'=>$id],'Sales Order');
    
                if ($this->db->trans_status() !== FALSE):
                    $this->db->trans_commit();
                    return $result;
                endif;
            }catch(\Exception $e){
                $this->db->trans_rollback();
                return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
            }
        }

        public function changeOrderStatus($data) {
            try{
                $this->db->trans_begin();
    
                $soData = $this->getSalesOrder(['id'=>$data['id'], 'itemList'=>0]);
                $partyId = '';
                if(!empty($data['trans_status']) && $data['trans_status'] == 1)
                {
                    if(!empty($soData->lead_id)){
                        $leadData = $this->party->getLead(['id'=>$soData->lead_id]);
                        $partyData = [
                            'id' => '',
                            'lead_id' => $leadData->id,
                            'party_code' => $this->party->getPartyCode(1),
                            'party_type' =>1,
                            'party_name' => $leadData->party_name,
                            'party_address' => $leadData->party_address,
                            'party_email' => $leadData->party_email,
                            'gstin' => $leadData->gstin,
                            'contact_phone' => $leadData->contact_phone,
                            'whatsapp_no' => $leadData->whatsapp_no,
                            'business_type' => $leadData->business_type,
                            'contact_person' => $leadData->contact_person,
                            'registration_type' => $leadData->registration_type,
                            'executive_id' => $leadData->executive_id,
                            'sales_zone_id' => $leadData->sales_zone_id,
                            'statutory_id' => $leadData->statutory_id,
                            'source' => $leadData->source,
                            'is_active' => $leadData->is_active,
                            'created_by' => $this->loginId,
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                        $result = $this->party->saveParty($partyData);
                        $partyId = $result['insert_id'];
        
                        $this->store($this->leadMaster, ['id' => $leadData->id, 'party_id' => $partyId, 'party_type' => 1]);
                        
                        $this->edit($this->sales_logs, ['lead_id' => $leadData->id], ['party_id' => $partyId]);
                        
                        $this->store($this->so_master, ['id' => $data['id'], 'trans_status' => $data['trans_status'],'approve_by' => $this->loginId, 'approve_at'=>date('Y-m-d H:i:s'), 'party_id' => $partyId]);
                    }
                    else{
                        $this->store($this->so_master, ['id'=> $data['id'], 'trans_status' => $data['trans_status']]);                    
                    }
                }
                else{
                    $this->store($this->so_master, ['id'=> $data['id'], 'trans_status' => $data['trans_status']]);
                }
        
                $logData = [
                    'id' => '',
                    'log_type' => 6,
                    'lead_id' => (!empty($leadData->id) ? $leadData->id : 0),
                    'party_id' => $partyId,
                    'ref_id' => (!empty($leadData->id) ? $leadData->id : 0),
                    'ref_date' => date('Y-m-d H:i:s'),
                    'ref_no' => $this->party->getPartyCode(1),
                    'executive_id' => (!empty($leadData->executive_id) ? $leadData->executive_id : 0),
                    'notes' => $this->notes[6],
                    'created_by' => $this->loginId,
                    'created_at' => date("Y-m-d H:i:s")
                ];
                $this->store($this->sales_logs, $logData);
    
                if ($this->db->trans_status() !== FALSE):
                    $this->db->trans_commit();
                    return ['status' => 1, 'message' => 'Sales Order '. $data['msg'] . ' Successfully.'];
                endif;
            }catch(\Exception $e){
                $this->db->trans_rollback();
                return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
            }  
        }
    
        /********** Return Order **********/
        public function saveReturnOrder($data){
            try{
                $this->db->trans_begin();
    
                $masterData = [
                    'id' => $data['id'],
                    'entry_type' => $data['entry_type'],
                    'trans_prefix' => $data['trans_prefix'],
                    'trans_no' => $data['trans_no'],
                    'trans_number' => $data['trans_number'],
                    'trans_date' => $data['trans_date'],
                    'party_id' => $data['party_id'],
                    'remark' => $data['remark'],
                    'created_by' => $this->loginId,
                    'created_at' => date("Y-m-d H:i:s")
                ]; 
                $result = $this->store($this->so_master, $masterData, 'Return Order');
    
                $masterId = (!empty($data['id']) ? $data['id'] : $result['insert_id']);
                $transData = [
                    'id' => $data['trans_id'],
                    'trans_main_id' => $masterId,
                    'entry_type' => $data['entry_type'],
                    'item_id' => $data['item_id'],
                    'qty' => $data['qty'],
                    'item_remark' => $data['remark'],
                    'created_by' => $this->loginId,
                    'created_at' => date("Y-m-d H:i:s")
                ];
                $this->store($this->so_trans, $transData);
        
                if ($this->db->trans_status() !== FALSE):
                    $this->db->trans_commit();
                    return $result;
                endif;
            }catch(\Exception $e){
                $this->db->trans_rollback();
                return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
            }	
        }

        public function getReturnOrderDTRows($data){
            $data['tableName'] = $this->so_trans;
            $data['select'] = "so_trans.id,so_trans.trans_main_id,so_trans.qty,DATE_FORMAT(so_master.trans_date,'%d-%m-%Y') as trans_date,(CASE WHEN so_master.party_id > 0 THEN party_master.party_name ELSE lead_master.party_name END) AS party_name,so_master.trans_number,item_master.item_name,item_master.item_code,so_master.trans_status";
            $data['leftJoin']['so_master'] = "so_master.id = so_trans.trans_main_id";
            $data['leftJoin']['party_master'] = "party_master.id = so_master.party_id";
            $data['leftJoin']['lead_master'] = "lead_master.id = so_master.lead_id";
            $data['leftJoin']['item_master'] = "item_master.id = so_trans.item_id";
            $data['leftJoin']['employee_master'] = "employee_master.id = so_master.sales_executive";
    
            if(!empty($data['status'])) { $data['where']['so_master.trans_status'] = $data['status']; }
            else { $data['where']['so_master.trans_status'] = 0; }
            
            // 20-05-2024
            if(!in_array($this->userRole,[1,-1])):
                if($this->leadRights == 2): // Zone Wise Leads Rights
                    $data['where']['lead_master.sales_zone_id'] = $this->zoneId;
                    $data['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id ) >0 OR employee_master.id = '.$this->loginId.')';
                elseif($this->leadRights == 1):
                    $data['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id ) >0 OR employee_master.id = '.$this->loginId.')';
                endif;
            endif;
    
            $data['where']['so_master.entry_type'] = 2;
            $data['where']['so_master.trans_date >='] = $this->startYearDate;
            $data['where']['so_master.trans_date <='] = $this->endYearDate;
            $data['order_by']['so_master.trans_date'] = "DESC";
            $data['order_by']['so_master.id'] = "DESC";
    
            $data['searchCol'][] = "";
            $data['searchCol'][] = "";
            $data['searchCol'][] = "so_master.trans_number";
            $data['searchCol'][] = "DATE_FORMAT(so_master.trans_date,'%d-%m-%Y')";
            $data['searchCol'][] = "(CASE WHEN so_master.party_id > 0 THEN party_master.party_name ELSE lead_master.party_name END)";
            $data['searchCol'][] = "CONCAT('[ ',item_master.item_code,' ] ',item_master.item_name)";
            $data['searchCol'][] = "so_trans.qty";
    
            $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
            if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
            
            return $this->pagingRows($data);
        }

        /********** Dispatch From Order **********/
        public function saveDispatch($data){
            try {
                $this->db->trans_begin();
                
                unset($data['order_qty']);
                $result = $this->store($this->dispatch_trans, $data, 'Dispatch');
                
                $setData = array();
                $setData['tableName'] = $this->so_trans;
                $setData['where']['id'] = $data['so_trans_id'];
                $setData['set']['dispatch_qty'] = 'dispatch_qty, + '.$data['qty'];
                $this->setValue($setData);
    
                $soItemData = $this->getSalesOrderItems(['id'=>$data['so_main_id']]);
                $total_qty = array_sum(array_column($soItemData,'qty'));
                $dispatch_qty = array_sum(array_column($soItemData,'dispatch_qty'));
                $pending_qty = ($total_qty - $dispatch_qty);
    
                if($pending_qty <= 0){
                    $this->edit($this->so_master, ['id'=>$data['so_main_id']], ['trans_status'=>2], '');
                }
                
                if ($this->db->trans_status() !== FALSE) :
                    $this->db->trans_commit();
                    return $result;
                endif;
            } catch (\Exception $e) {
                $this->db->trans_rollback();
                return ['status' => 2, 'message' => "somthing is wrong. Error : " . $e->getMessage()];
            }
        }
    
        public function deleteDispatch($data){
            try{
                $this->db->trans_begin();
    
                $result = $this->trash($this->dispatch_trans, ['id'=>$data['id']], 'Dispatch');
    
                $setData = array();
                $setData['tableName'] = $this->so_trans;
                $setData['where']['id'] = $data['so_trans_id'];
                $setData['set']['dispatch_qty'] = 'dispatch_qty, - '.$data['qty'];
                $this->setValue($setData);
    
                if ($this->db->trans_status() !== FALSE):
                    $this->db->trans_commit();
                    return $result;
                endif;
            }catch(\Exception $e){
                $this->db->trans_rollback();
                return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
            }	
        }
    
        public function getDispatch($data){
            $data['tableName'] = $this->dispatch_trans;
            $data['select'] = "dispatch_trans.*,item_master.item_code,item_master.item_name";
            $data['leftJoin']['item_master'] = "item_master.id = dispatch_trans.item_id";
            $data['where']['dispatch_trans.so_main_id'] = $data['so_main_id'];
            return $this->rows($data);
        }    

        /********** Dashboard Chart **********/
        public function getSalesEnqForChart(){
            $queryData['tableName']=$this->sales_logs;
            $queryData['select'] = 'SUM(CASE WHEN MONTH(sales_logs.created_at) = 1 AND log_type = 1 THEN 1 ELSE 0 END) AS jan_new,
                                    SUM(CASE WHEN MONTH(sales_logs.created_at) = 2 AND log_type = 1 THEN 1 ELSE 0 END) AS feb_new,
                                    SUM(CASE WHEN MONTH(sales_logs.created_at) = 3 AND log_type = 1 THEN 1 ELSE 0 END) AS mar_new,
                                    SUM(CASE WHEN MONTH(sales_logs.created_at) = 4 AND log_type = 1 THEN 1 ELSE 0 END) AS apr_new,
                                    SUM(CASE WHEN MONTH(sales_logs.created_at) = 5 AND log_type = 1 THEN 1 ELSE 0 END) AS may_new,
                                    SUM(CASE WHEN MONTH(sales_logs.created_at) = 6 AND log_type = 1 THEN 1 ELSE 0 END) AS jun_new,
                                    SUM(CASE WHEN MONTH(sales_logs.created_at) = 7 AND log_type = 1 THEN 1 ELSE 0 END) AS jul_new,
                                    SUM(CASE WHEN MONTH(sales_logs.created_at) = 8 AND log_type = 1 THEN 1 ELSE 0 END) AS aug_new,
                                    SUM(CASE WHEN MONTH(sales_logs.created_at) = 9 AND log_type = 1 THEN 1 ELSE 0 END) AS sep_new,
                                    SUM(CASE WHEN MONTH(sales_logs.created_at) = 10 AND log_type = 1 THEN 1 ELSE 0 END) AS oct_new,
                                    SUM(CASE WHEN MONTH(sales_logs.created_at) = 11 AND log_type = 1 THEN 1 ELSE 0 END) AS nov_new,
                                    SUM(CASE WHEN MONTH(sales_logs.created_at) = 12 AND log_type = 1 THEN 1 ELSE 0 END) AS dec_new,

                                    SUM(CASE WHEN MONTH(sales_logs.created_at) = 1 AND log_type = 7 THEN 1 ELSE 0 END) AS jan_lost,
                                    SUM(CASE WHEN MONTH(sales_logs.created_at) = 2 AND log_type = 7 THEN 1 ELSE 0 END) AS feb_lost,
                                    SUM(CASE WHEN MONTH(sales_logs.created_at) = 3 AND log_type = 7 THEN 1 ELSE 0 END) AS mar_lost,
                                    SUM(CASE WHEN MONTH(sales_logs.created_at) = 4 AND log_type = 7 THEN 1 ELSE 0 END) AS apr_lost,
                                    SUM(CASE WHEN MONTH(sales_logs.created_at) = 5 AND log_type = 7 THEN 1 ELSE 0 END) AS may_lost,
                                    SUM(CASE WHEN MONTH(sales_logs.created_at) = 6 AND log_type = 7 THEN 1 ELSE 0 END) AS jun_lost,
                                    SUM(CASE WHEN MONTH(sales_logs.created_at) = 7 AND log_type = 7 THEN 1 ELSE 0 END) AS jul_lost,
                                    SUM(CASE WHEN MONTH(sales_logs.created_at) = 8 AND log_type = 7 THEN 1 ELSE 0 END) AS aug_lost,
                                    SUM(CASE WHEN MONTH(sales_logs.created_at) = 9 AND log_type = 7 THEN 1 ELSE 0 END) AS sep_lost,
                                    SUM(CASE WHEN MONTH(sales_logs.created_at) = 10 AND log_type = 7 THEN 1 ELSE 0 END) AS oct_lost,
                                    SUM(CASE WHEN MONTH(sales_logs.created_at) = 11 AND log_type = 7 THEN 1 ELSE 0 END) AS nov_lost,
                                    SUM(CASE WHEN MONTH(sales_logs.created_at) = 12 AND log_type = 7 THEN 1 ELSE 0 END) AS dec_lost,

                                    SUM(CASE WHEN MONTH(sales_logs.created_at) = 1 AND log_type = 8 THEN 1 ELSE 0 END) AS jan_won,
                                    SUM(CASE WHEN MONTH(sales_logs.created_at) = 2 AND log_type = 8 THEN 1 ELSE 0 END) AS feb_won,
                                    SUM(CASE WHEN MONTH(sales_logs.created_at) = 3 AND log_type = 8 THEN 1 ELSE 0 END) AS mar_won,
                                    SUM(CASE WHEN MONTH(sales_logs.created_at) = 4 AND log_type = 8 THEN 1 ELSE 0 END) AS apr_won,
                                    SUM(CASE WHEN MONTH(sales_logs.created_at) = 5 AND log_type = 8 THEN 1 ELSE 0 END) AS may_won,
                                    SUM(CASE WHEN MONTH(sales_logs.created_at) = 6 AND log_type = 8 THEN 1 ELSE 0 END) AS jun_won,
                                    SUM(CASE WHEN MONTH(sales_logs.created_at) = 7 AND log_type = 8 THEN 1 ELSE 0 END) AS jul_won,
                                    SUM(CASE WHEN MONTH(sales_logs.created_at) = 8 AND log_type = 8 THEN 1 ELSE 0 END) AS aug_won,
                                    SUM(CASE WHEN MONTH(sales_logs.created_at) = 9 AND log_type = 8 THEN 1 ELSE 0 END) AS sep_won,
                                    SUM(CASE WHEN MONTH(sales_logs.created_at) = 10 AND log_type = 8 THEN 1 ELSE 0 END) AS oct_won,
                                    SUM(CASE WHEN MONTH(sales_logs.created_at) = 11 AND log_type = 8 THEN 1 ELSE 0 END) AS nov_won,
                                    SUM(CASE WHEN MONTH(sales_logs.created_at) = 12 AND log_type = 8 THEN 1 ELSE 0 END) AS dec_won
                                    '; 
            $queryData['leftJoin']['employee_master'] = "employee_master.id = sales_logs.executive_id ";
            $queryData['where']['YEAR(sales_logs.created_at)'] = date("Y");
            
            // 20-05-2024
            if(!in_array($this->userRole,[1,-1])):
                if($this->leadRights == 2): // Zone Wise Leads Rights
                    $queryData['where']['lead_master.sales_zone_id'] = $this->zoneId;
                    $queryData['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id ) >0 OR employee_master.id = '.$this->loginId.')';
                elseif($this->leadRights == 1):
                    $queryData['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id ) >0 OR employee_master.id = '.$this->loginId.')';
                endif;
            endif;

            $queryData['group_by'][]='MONTH(sales_logs.created_at)';
            return $this->row($queryData);
        } 

        public function getTopSoForDashboard(){
            $data['tableName'] = $this->so_master;
            $data['select'] = "SUM(so_master.net_amount) as net_amount,employee_master.emp_name";
            $data['join']['employee_master'] = "employee_master.id = so_master.sales_executive";
            
            // 20-05-2024
            if(!in_array($this->userRole,[1,-1])):
                if($this->leadRights == 2): // Zone Wise Leads Rights
                    $data['where']['lead_master.sales_zone_id'] = $this->zoneId;
                    $data['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id ) >0 OR employee_master.id = '.$this->loginId.')';
                elseif($this->leadRights == 1):
                    $data['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id ) >0 OR employee_master.id = '.$this->loginId.')';
                endif;
            endif;

            $data['group_by'][] = "so_master.sales_executive";
            $data['order_by']['SUM(so_master.net_amount)'] = "DESC";
            $data['limit'] = 10; 
            return $this->rows($data);
        }
    /*** End Sales */

    /********** CRM Desk **********/
        public function getSalesLog($param=[]){
            $data['tableName'] = "sales_logs";
            $data['select'] = "sales_logs.*,employee_master.emp_name as creator,(CASE WHEN sales_logs.party_id > 0 THEN party_master.party_name ELSE lead_master.party_name END) AS party_name";
            $data['leftJoin']['employee_master'] = "employee_master.id = sales_logs.created_by";
            $data['leftJoin']['employee_master se'] = "se.id = sales_logs.executive_id";
            $data['leftJoin']['party_master'] = "party_master.id = sales_logs.party_id";
            $data['leftJoin']['lead_master'] = "lead_master.id = sales_logs.lead_id";
            if(!empty($param['created_by'])){ $data['where']['sales_logs.created_by'] = $param['created_by']; }
            if(!empty($param['party_id'])){ $data['where']['sales_logs.party_id'] = $param['party_id']; }
            if(!empty($param['lead_id'])){ $data['where']['sales_logs.lead_id'] = $param['lead_id']; }
            if(!empty($param['log_type'])){ 
                $data['where']['sales_logs.log_type'] = $param['log_type'];
                if($param['log_type'] == 3){
                    $data['where'] ['sales_logs.ref_date'] = date("Y-m-d");
                }
            }
            
            if(!empty($param['not_log_type'])){ 
                $data['where_not_in']['sales_logs.log_type'] = $param['not_log_type'];
            }
            if(!empty($param['executive_id'])){
                $data['select'] .= ",se.emp_name as executive";
                // $data['leftJoin']['employee_master ex'] = "ex.id = sales_logs.executive_id";
                $data['where']['sales_logs.executive_id'] = $param['executive_id'];
            }
            // 20-05-2024
            if(!in_array($this->userRole,[1,-1])):
                if($this->leadRights == 2): // Zone Wise Leads Rights
                    $data['where']['lead_master.sales_zone_id'] = $this->zoneId;
                    $data['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id ) >0 OR employee_master.id = '.$this->loginId.')';
                elseif($this->leadRights == 1):
                    $data['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id ) >0 OR employee_master.id = '.$this->loginId.')';
                endif;
            endif;

            
            if(!empty($param['order_by'])){
                $data['order_by']['sales_logs.id'] = "DESC";
            }else{
                $data['order_by']['sales_logs.id'] = "ASC";
            }            
           
            if(!empty($param['limit'])) { $data['limit'] = $param['limit']; $data['order_by']['sales_logs.id'] = "DESC"; }
            if(!empty($param['single_row'])):
                return $this->row($data);
            else:
                return $this->rows($data);
            endif;
        }

        public function getSalesLogCount($param=[]){
            $data['tableName'] = "sales_logs";
            $data['select'] = "SUM(CASE WHEN log_type = 1 THEN 1 ELSE  0 END) as new_lead,SUM(CASE WHEN log_type =6 THEN 1 ELSE  0 END) as new_order,SUM(CASE WHEN log_type = 3 AND remark IS NULL THEN 1 ELSE  0 END) as reminder,SUM(CASE WHEN log_type = 7 THEN 1 ELSE  0 END) as lost_lead";
            $data['leftJoin']['employee_master'] = "employee_master.id = sales_logs.executive_id";
            $data['customWhere'][]="DATE(sales_logs.created_at) BETWEEN '".$param['from_date']."' AND '".$param['to_date']."' ";
            if(!empty($param['created_by'])){ $data['where']['sales_logs.created_by'] = $param['created_by']; }
            else{
                // 20-05-2024
                if(!in_array($this->userRole,[1,-1])):
                    if($this->leadRights == 2): // Zone Wise Leads Rights
                        $data['where']['lead_master.sales_zone_id'] = $this->zoneId;
                        $data['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id ) >0 OR employee_master.id = '.$this->loginId.')';
                    elseif($this->leadRights == 1):
                        $data['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id ) >0 OR employee_master.id = '.$this->loginId.')';
                    endif;
                endif;
            }
            
            return $this->row($data);
        }  

        public function saveSalesLogs($data){
            try{
                $this->db->trans_begin();

                $result = $this->store($this->sales_logs,$data);

                if ($this->db->trans_status() !== FALSE):
                    $this->db->trans_commit();
                    return $result;
                endif;
            }catch(\Exception $e){
                $this->db->trans_rollback();
                return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
            }
        }

        // 20-05-2024
        public function getReminders($param=array()){
            $queryData = array();
            $queryData['tableName'] = $this->sales_logs;
            if(!empty($param['crmDesk'])):
                $queryData['select'] = "lm.*,emp.emp_name as executive,sales_logs.ref_date,lead_detail.contact_person,IF (sales_logs.lead_id >0,lm.party_name,party_master.party_name) as party_name";
            else:
                $queryData['select'] = "sales_logs.*,emp.emp_name as executive,lm.party_name,lm.source,lead_detail.contact_person,IF (sales_logs.lead_id >0,lm.party_name,party_master.party_name) as party_name";
            endif;
            $queryData['leftJoin']['lead_master lm'] = "lm.id = sales_logs.lead_id";
            $queryData['leftJoin']['lead_detail'] = "lead_detail.lead_id = lm.id";
            $queryData['leftJoin']['party_master'] = "party_master.id = sales_logs.party_id";
            $queryData['leftJoin']['employee_master emp'] = "emp.id = lm.executive_id";
            $queryData['where']['sales_logs.log_type'] = 3;

            if(!empty($param['status'])) {
                if($param['status'] == 1){$queryData['customWhere'][] = 'sales_logs.remark IS NULL';} // Pending Response
                if($param['status'] == 2){$queryData['customWhere'][] = 'sales_logs.remark IS NOT NULL';} // Response Given
            }

            if(!empty($param['ref_date'])){
                $queryData['where']['sales_logs.ref_date'] = $param['ref_date'];
            }                 
			
			// Search
            if(!empty($param['skey'])):
				$queryData['like']['lm.party_name'] = str_replace(" ", "%", $param['skey']);
				$queryData['like']['emp.emp_name'] = str_replace(" ", "%", $param['skey']);
				$queryData['like']['lead_detail.contact_person'] = str_replace(" ", "%", $param['skey']);
				$queryData['like']['lm.source'] = str_replace(" ", "%", $param['skey']);
			endif;

            if(!empty($param['executive_id'])) { $queryData['where']['sales_logs.created_by'] = $param['executive_id']; }
            if(!in_array($this->userRole,[1,-1])):
                if($this->leadRights == 2): // Zone Wise Leads Rights
                    $queryData['where']['lead_master.sales_zone_id'] = $this->zoneId;
                    $queryData['customWhere'][] = '(find_in_set("'.$this->loginId.'", emp.super_auth_id ) >0 OR emp.id = '.$this->loginId.' OR sales_logs.created_by='.$this->loginId.')';
                elseif($this->leadRights == 1):
                    $queryData['customWhere'][] = '(find_in_set("'.$this->loginId.'", emp.super_auth_id ) >0 OR emp.id = '.$this->loginId.' OR sales_logs.created_by='.$this->loginId.')';
                endif;
            endif;           
            /*if(!in_array($this->userRole,[1,-1])):
                $queryData['customWhere'][] = '(find_in_set("'.$this->loginId.'", emp.super_auth_id ) >0 OR emp.id = '.$this->loginId.')';
            endif;*/    
           	
            if(!empty($param['group_by'])) { $queryData['group_by'][] = $param['group_by']; }		
            if(!empty($param['limit'])) { $queryData['limit'] = $param['limit']; }
			if(isset($param['start'])) { $queryData['start'] = $param['start']; }
			if(!empty($param['length'])) { $queryData['length'] = $param['length']; }
            
            $queryData['order_by']['sales_logs.ref_date'] = "ASC";
            $queryData['order_by']['lm.party_name'] = "ASC";
            
            if(isset($param['count'])):
				$result = $this->numRows($queryData);
			else:
				$result = $this->rows($queryData);
			endif;
			//$this->printQuery();
			return  $result;
        }

        public function getLogCountForCustHistory($data){
            $data['tableName'] = "sales_logs";
            $data['select'] = "(CASE WHEN sales_logs.log_type = 1 THEN sales_logs.created_at ELSE 0 END) as lead_created,SUM(CASE WHEN sales_logs.log_type = 6 THEN 1 ELSE 0 END) as total_orders,soMaster.net_amount as total_ord_amt";
            $data['leftJoin']['(SELECT SUM(net_amount) as net_amount,party_id FROM so_master WHERE is_delete = 0 GROUP BY party_id) as soMaster'] = "soMaster.party_id = sales_logs.party_id";
            $data['where']['sales_logs.party_id'] = $data['party_id'];
            $data['order_by']['sales_logs.id'] = 'ASC';
            return $this->row($data);
        }
    /********** End CRM Desk **********/

    /********** Sales Target ************/
        public function saveTargets($data){
            try{
                $this->db->trans_begin();
                foreach($data['id'] as $key=>$id){
                    $targetData = [
                        'id'=>$id,
                        'emp_id'=>$data['emp_id'][$key],
                        'zone_id'=>$data['zone_id'][$key],
                        'target_month'=>$data['month'],
                        'new_lead'=>$data['new_lead'][$key],
                        'sales_amount'=>$data['sales_amount'][$key],
                    ];
                    // print_r($targetData);
                    $result = $this->store('executive_targets',$targetData,'Target');
                }
                if ($this->db->trans_status() !== FALSE):
                    $this->db->trans_commit();
                    return $result;
                endif;
                
            }catch(\Exception $e){
                $this->db->trans_rollback();
                return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
            }
        }

        public function getTargetData($param = []){
            $queryData['tableName'] = $this->executive_targets;
            $queryData['select'] = "executive_targets.*,employee_master.emp_name,employee_master.emp_code,GROUP_CONCAT(sales_zone.zone_name) as zone_name,salesLog.achieve_new_lead,soMaster.achieve_sales_amount";
            $queryData['leftJoin']['employee_master'] = "executive_targets.emp_id = employee_master.id ";
            $queryData['leftJoin']['sales_zone'] = ' find_in_set(sales_zone.id,employee_master.zone_id) > 0 ';
            $queryData['leftJoin']['(SELECT count(*) as achieve_new_lead,executive_id,lead_id,created_at FROM sales_logs WHERE log_type = 1 AND is_delete = 0 AND MONTH(created_at) = "'.date("m",strtotime($param['target_month'])).'" GROUP BY executive_id) as salesLog'] = "salesLog.executive_id = employee_master.id";
            $queryData['leftJoin']['(SELECT SUM(taxable_amount) as achieve_sales_amount,sales_executive FROM so_master  WHERE so_master.is_delete = 0 AND MONTH(so_master.trans_date) = "'.date("m",strtotime($param['target_month'])).'" GROUP BY sales_executive) as soMaster'] = "soMaster.sales_executive = employee_master.id";

            $queryData['where']['executive_targets.target_month'] = $param['target_month'];
            $queryData['group_by'][]='employee_master.id';
            if(!empty($data['zone_id'])):
                $queryData['where']['find_in_set("'.$data['zone_id'].'", executive_targets.zone_id ) >'] = 0;
            endif;
            if(!empty($param['emp_id'])){
                $queryData['where']['executive_targets.emp_id'] = $param['emp_id'];
            }
            else{
                // 20-05-2024
                if(!in_array($this->userRole,[1,-1])):
                    if($this->leadRights == 2): // Zone Wise Leads Rights
                        $queryData['where']['lead_master.sales_zone_id'] = $this->zoneId;
                        $queryData['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id ) >0 OR employee_master.id = '.$this->loginId.')';
                    elseif($this->leadRights == 1):
                        $queryData['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id ) >0 OR employee_master.id = '.$this->loginId.')';
                    endif;
                endif;
            }
            if(!empty($param['single_row'])){
                return $this->row($queryData);
            }else{
                return $this->rows($queryData);
            }
        }
    /********** End Sales Target *********/
}
?>