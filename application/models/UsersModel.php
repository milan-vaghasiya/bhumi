<?php
class UsersModel extends MasterModel{
    private $designationMaster = "emp_designation";
    private $empMaster = "employee_master";
    private $attendance_log = "attendance_log";
    private $leaveMaster = "leave_master";
	private $departmentMaster = "department_master";
	private $empDetail = "emp_detail";
    private $interviewLogs = "interview_logs";
	private $empVacancy = "emp_vacancy";
	private $staffSkill = "staff_skill";
	private $empDocuments = "emp_docs";
	

    /********** Designation **********/
        public function getDesignationDTRows($data){
            $data['tableName'] = $this->designationMaster;
            
            $data['searchCol'][] = "";
            $data['searchCol'][] = "";
            $data['searchCol'][] = "title";

            $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;

            if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
            return $this->pagingRows($data);
        }

        public function getDesignations($data=array()){
            $queryData['tableName'] = $this->designationMaster;
            return $this->rows($queryData);
        }

        public function getDesignation($data){
            $queryData['tableName'] = $this->designationMaster;
            $queryData['where']['id'] = $data['id'];
            return $this->row($queryData);
        }

        public function saveDesignation($data){
            try{
                $this->db->trans_begin();

                $data['checkDuplicate'] = ['title'];
                $result = $this->store($this->designationMaster,$data,'Designation');

                if ($this->db->trans_status() !== FALSE):
                    $this->db->trans_commit();
                    return $result;
                endif;
            }catch(\Exception $e){
                $this->db->trans_rollback();
                return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
            }	
        }
    
		public function deleteDesignation($id){
			try{
				$this->db->trans_begin();

				$checkData['columnName'] = ['emp_designation'];
				$checkData['value'] = $id;
				$checkUsed = $checkUsage($checkData);

				if($checkUsed == true):
					return ['status'=>0,'message'=>'The Designation is currently in use. you cannot delete it.'];
				endif;

				$result = $this->trash($this->designationMaster,['id'=>$id],'Designation');

				if ($this->db->trans_status() !== FALSE):
					$this->db->trans_commit();
					return $result;
				endif;
			}catch(\Throwable $e){
				$this->db->trans_rollback();
				return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
			}
		}
	/********** End Designation **********/

    /********** Users **********/
		public function getEmployeeDTRows($data){
            $data['tableName'] = $this->empMaster;
            $data['select'] = "employee_master.*,department_master.name as dept_name,emp_designation.title as emp_designation,head_quarter.name as quarter_name,(select count(*) from emp_docs where employee_master.id = emp_docs.emp_id AND emp_docs.is_delete = 0) as total_docs";
            $data['leftJoin']['department_master'] = "employee_master.emp_dept_id = department_master.id";
			$data['leftJoin']['emp_designation'] = "employee_master.emp_designation = emp_designation.id";
            $data['leftJoin']['head_quarter'] = "employee_master.quarter_id = head_quarter.id";
            $data['where']['employee_master.emp_role !='] = "-1";
			
			if($data['status'] == 0):
				$data['where']['employee_master.status']= 1;
				$data['where']['employee_master.is_active']= 1;
			elseif($data['status'] == 1):
				$data['where']['employee_master.status']= 1;
				$data['where']['employee_master.is_active']= 0;
			else:		
				if(isset($data['status'])):
					$data['where']['employee_master.status']= $data['status'];
				endif;
			endif;
			
            /*if(!in_array($this->userRole,[1,-1])):
                if($this->leadRights == 2): // Zone Wise Leads Rights
                    $data['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id ) >0 OR employee_master.id = '.$this->loginId.')';
                elseif($this->leadRights == 1):
                    $data['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id ) >0 OR employee_master.id = '.$this->loginId.')';
                endif;
            endif;*/
			if(!in_array($this->userRole,[1,-1])):
				$data['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id) > 0)';
			endif;
			
			if($data['status'] >= 2 && $data['status'] <= 6):
		
				$data['select'] .= ",emp_detail.rec_source,emp_detail.ref_by";
				$data['leftJoin']['emp_detail'] = "emp_detail.emp_id = employee_master.id";
			
				$data['searchCol'][] = "";
				$data['searchCol'][] = "";
				$data['searchCol'][] = "employee_master.emp_name";
				$data['searchCol'][] = "employee_master.emp_contact";
				$data['searchCol'][] = "department_master.name";
				$data['searchCol'][] = "emp_designation.title";
				$data['searchCol'][] = "emp_detail.rec_source";
				$data['searchCol'][] = "emp_detail.ref_by";
				
			elseif($data['status'] == 7):
				$data['select'] .= ",emp_detail.rec_source,emp_detail.ref_by,interview_logs.from_stage,reject.emp_name as reject_name,interview_logs.created_at as reject_at";
				$data['leftJoin']['emp_detail'] = "emp_detail.emp_id = employee_master.id";
				$data['leftJoin']['interview_logs'] = "interview_logs.emp_id = employee_master.id AND interview_logs.log_type = 7";
				$data['leftJoin']['employee_master reject'] = "reject.id = interview_logs.created_by";
			
				$data['searchCol'][] = "";
				$data['searchCol'][] = "";
				$data['searchCol'][] = "employee_master.emp_name";
				$data['searchCol'][] = "employee_master.emp_contact";
				$data['searchCol'][] = "department_master.name";
				$data['searchCol'][] = "emp_designation.title";
				$data['searchCol'][] = "emp_detail.rec_source";
				$data['searchCol'][] = "emp_detail.ref_by";
				$data['searchCol'][] = "emp_detail.from_stage";
				$data['searchCol'][] = "reject.emp_name";
			else:
				$data['searchCol'][] = "";
				$data['searchCol'][] = "";
				$data['searchCol'][] = "employee_master.emp_name";
				$data['searchCol'][] = "employee_master.emp_code";
				$data['searchCol'][] = "department_master.name";
				$data['searchCol'][] = "emp_designation.title";
				$data['searchCol'][] = "employee_master.emp_contact";
				$data['searchCol'][] = "head_quarter.name";
				
				$data['order_by']['employee_master.emp_code'] = "ASC";
			endif;
            
            $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
            if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
            
            return $this->pagingRows($data);
        }

        public function getEmployeeList($data=array()){
            $queryData['tableName'] = $this->empMaster;
            
            if(empty($data['selectBox'])){
    			$queryData['select'] = "employee_master.*,department_master.name as dept_name,emp_designation.title as emp_designation,head_quarter.name as quarter_name";
    			$queryData['leftJoin']['department_master'] = "employee_master.emp_dept_id = department_master.id";
    			$queryData['leftJoin']['emp_designation'] = "employee_master.emp_designation = emp_designation.id";
                $queryData['leftJoin']['head_quarter'] = "employee_master.quarter_id = head_quarter.id";
            }
            else{
                $queryData['select'] = "employee_master.id,employee_master.emp_code,employee_master.emp_name";
            }
            
			if(!empty($data['executive_target'])){
                $queryData['select'] .= "executive_targets.id as target_id, executive_targets.new_lead, executive_targets.sales_amount, GROUP_CONCAT(sales_zone.zone_name) as zone_name";
                $queryData['leftJoin']['executive_targets'] = "executive_targets.emp_id = employee_master.id AND executive_targets.target_month = '".$data['month']."'";
                $queryData['leftJoin']['sales_zone'] = ' find_in_set(sales_zone.id,employee_master.zone_id) > 0 ';
               
                $queryData['group_by'][]='employee_master.id';
            }

            if(!empty($data['emp_id'])):
                $queryData['where']['employee_master.id'] = $data['emp_id'];
            endif;

            if(!empty($data['id'])):
                $queryData['where']['employee_master.id'] = $data['id'];
            endif;

            if(!empty($data['emp_code'])):
                $queryData['where']['employee_master.emp_code'] = $data['emp_code'];
            endif;
			
            if(!empty($data['emp_role'])):
                $queryData['where_in']['employee_master.emp_role'] = $data['emp_role'];
            endif;

            if(!empty($data['emp_not_role'])):
                $queryData['where_not_in']['employee_master.emp_role'] = $data['emp_not_role'];
            endif;

            if(!empty($data['emp_sys_desc_id'])):
                $queryData['where']['find_in_set("'.$data['emp_sys_desc_id'].'", employee_master.emp_sys_desc_id) >'] = 0;
            endif;

            if(!empty($data['emp_designation'])):
                $queryData['where']['employee_master.emp_designation'] = $data['emp_designation'];
            endif;

            if(!empty($data['is_active'])):
                $queryData['where']['employee_master.is_active'] = $data['is_active'];
            endif;
            if(!empty($data['attendance_status'])):
                $queryData['where']['employee_master.attendance_status'] = $data['attendance_status'];
            endif;

            if(empty($data['all'])):
                $queryData['where']['employee_master.emp_role !='] = "-1";
            endif;
			
			if(!empty($data['is_zone'])):
				$zone_id = (isset($data['zone_id']) ? $data['zone_id'] : 0);
				$queryData['select'] .= ' ,GROUP_CONCAT(sales_zone.zone_name SEPARATOR ", ") as zone_name';
				$queryData['leftJoin']['sales_zone'] = 'find_in_set(sales_zone.id,employee_master.zone_id) > 0 ';
               
				if(isset($data['zone_id']) && !empty($zone_id)):
					$queryData['customWhere'][] = 'find_in_set('.$zone_id.',employee_master.zone_id) > 0';
				endif;
				
                $queryData['group_by'][]='employee_master.id';
			endif;

            if(!empty($data['is_se'])):
                $queryData['where']['employee_master.is_se'] = $data['is_se'];
            endif;

            if(!in_array($this->userRole,[1,-1])):
				$queryData['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id ) >0 OR employee_master.id = '.$this->loginId.')';
			endif;
            $result = $this->rows($queryData);
			//$this->printQuery();
			return $result; 
        }

		public function getEmployee($data){
            $queryData['tableName'] = $this->empMaster;
			$queryData['select'] = "employee_master.*,emp_designation.title as designation_name,head_quarter.hq_lat_lng as hq_location, head_quarter.hq_add, head_quarter.name as hq_name,department_master.name as department_name,emp_detail.father_name,emp_detail.emp_alt_contact,emp_detail.marital_status,emp_detail.emp_experience,emp_detail.rec_source,emp_detail.ref_by,emp_detail.permanent_address,emp_detail.id as emp_detail_id";
			$queryData['leftJoin']['emp_detail'] = "emp_detail.emp_id = employee_master.id";
			$queryData['leftJoin']['emp_designation'] = "employee_master.emp_designation = emp_designation.id";
			$queryData['leftJoin']['department_master'] = "employee_master.emp_dept_id = department_master.id";
            $queryData['leftJoin']['head_quarter'] = "employee_master.quarter_id = head_quarter.id";
            $queryData['where']['employee_master.id'] = $data['id'];
            return $this->row($queryData);
        }		
		
		public function getEmpCustomSelect($param = []){
            $queryData['tableName'] = $this->empMaster;
			
			if(!empty($param['select'])):
				$queryData['select'] = $param['select'];
			endif;
			
			if(!empty($param['id'])):
				$queryData['where']['employee_master.id'] = $param['id'];
				return $this->row($queryData);
			endif;
			
			if(!empty($param['auth_id'])):
				$queryData['where']['employee_master.auth_id'] = $param['auth_id'];
				return $this->rows($queryData);
			endif;
			return $this->row($queryData);
        }
		
		public function getEmpHq($data){
            $queryData['tableName'] = $this->empMaster;
            $queryData['select'] = "employee_master.id, employee_master.shift_id, shift_master.shift_start, shift_master.shift_end, employee_master.quarter_id, head_quarter.hq_lat_lng as hq_location, head_quarter.hq_add, head_quarter.name as hq_name";
            $queryData['leftJoin']['head_quarter'] = "employee_master.quarter_id = head_quarter.id";
			$queryData['leftJoin']['shift_master'] = "employee_master.shift_id = shift_master.id";
            $queryData['where']['employee_master.id'] = $data['emp_id'];
            return $this->row($queryData);
        }
		
		public function saveEmployee($data){
            try{
                $this->db->trans_begin();

				
				if($this->checkEmpDuplicate($data) > 0):
					$errorMessage['emp_contact'] = "Contact no. is duplicate.";
					return ['status'=>0,'message'=>$errorMessage];
				endif;
				
				$data['super_auth_id'] = "";
				if(!empty($data['auth_id'])){
					$authData = $this->getEmpCustomSelect(["select"=>"id, auth_id, super_auth_id","id"=>$data['auth_id']]);
					$saID1 = ((!empty($authData->super_auth_id))?$authData->super_auth_id.',':'').$data['auth_id'];
					$data['super_auth_id'] = implode(',',array_unique(explode(',',$saID1)));
				}
				
                if(empty($data['id'])):
                    $data['emp_psc'] = $data['emp_password'];
                    $data['emp_password'] = md5($data['emp_password']);
					
				else:
					//$empData = $this->getEmpCustomSelect(["select"=>"id, auth_id, super_auth_id","id"=>$data['id']]);
					//if($empData->super_auth_id != $data['super_auth_id']){$this->migrateAuthId(["auth_id"=>$data['id'], "super_auth_id"=>$data['super_auth_id']]);}
					$this->migrateAuthId(["auth_id"=>$data['id'], "super_auth_id"=>$data['super_auth_id']]);
                endif;

                
				
				$empDetails = (!empty($data['empDetails']))?$data['empDetails']:array(); 
				unset($data['empDetails']);
                $result =  $this->store($this->empMaster,$data,'Employee');
				
				if(empty($data['id'])){
					$empDetails['id'] = "";
					$empDetails['emp_id'] = $result['id'];
					$this->store($this->empDetail,$empDetails,'Employee');
				}else{
					$this->edit($this->empDetail,['emp_id' => $result['id']],$empDetails,'Employee');
				}
				
				if(!empty($result['id']) && empty($data['id'])):
					$this->interviewLogs(['log_type'=>2, 'from_stage'=>0, 'emp_id'=>$result['id'], 'notes'=>$this->interviewType[2]]);
				endif;

                if(empty($data['id'])){
                    $menuList = $this->permission->getPermission(0,2);
                    $permissionArray = [];
                    $permissionArray['emp_id'] = $result['id'];
                    $permissionArray['menu_type'] = 2;
                    foreach($menuList as $menu){
                        $permissionArray['menu_id'][] =$menu->id;
                        $permissionArray['is_master'][] = 0;
                        $permissionArray['main_id'][] = $menu->id;
                       
                        foreach($menu->subMenus as $row){
                            if(!in_array($row->id,[93,73])){
                                $permissionArray['sub_menu_id_'.$menu->id][] = $row->id;
                                $permissionArray['sub_menu_read_'.$row->id.'_'.$menu->id][0] =1;
                                $permissionArray['sub_menu_write_'.$row->id.'_'.$menu->id][0] =1;
                                $permissionArray['sub_menu_modify_'.$row->id.'_'.$menu->id][0] =1;
                                $permissionArray['sub_menu_delete_'.$row->id.'_'.$menu->id][0] =1;
                            }
							if(!in_array($row->id,[68,70,96])){
                                $permissionArray['sub_menu_id_'.$menu->id][] = $row->id;
                                $permissionArray['sub_menu_read_'.$row->id.'_'.$menu->id][0] =1;
                                $permissionArray['sub_menu_write_'.$row->id.'_'.$menu->id][0] =1;
                                $permissionArray['sub_menu_modify_'.$row->id.'_'.$menu->id][0] =1;
                            }
							if(!in_array($row->id,[71,97])){
                                $permissionArray['sub_menu_id_'.$menu->id][] = $row->id;
                                $permissionArray['sub_menu_read_'.$row->id.'_'.$menu->id][0] =1;
                            }
                        }
                    }
                    $this->permission->save($permissionArray);
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
		
		public function checkEmpDuplicate($data){
			$queryData['tableName'] = $this->empMaster;
			$queryData['where']['emp_name'] = $data['emp_name'];
			$queryData['where']['emp_contact'] = $data['emp_contact'];
			
			if(!empty($data['id']))
				$queryData['where']['id !='] = $data['id'];
			
			$queryData['resultType'] = "numRows";
			return $this->specificRow($queryData);
		}
		
        public function activeInactive($postData){
            try{
                $this->db->trans_begin();
				$postData['app_auth_token'] = "";
				
                $result = $this->store($this->empMaster,$postData,'');
                $result['message'] = "Employee ".(($postData['is_active'] == 1)?"Activated":"De-activated")." successfully.";
                
                if ($this->db->trans_status() !== FALSE):
                    $this->db->trans_commit();
                    return $result;
                endif;
            }catch(\Exception $e){
                $this->db->trans_rollback();
                return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
            }	
        }

        public function changePassword($data){
            try{
                $this->db->trans_begin();

                if(empty($data['id'])):
                    return ['status'=>2,'message'=>'Somthing went wrong...Please try again.'];
                endif;

                $empData = $this->getEmployee(['id'=>$data['id']]);
                if(md5($data['old_password']) != $empData->emp_password):
                   return ['status'=>0,'message'=>['old_password'=>"Old password not match."]];
                endif;

                $postData = ['id'=>$data['id'],'emp_password'=>md5($data['new_password']),'emp_psc'=>$data['new_password']];
                $result = $this->store($this->empMaster,$postData);
                $result['message'] = "Password changed successfully.";

                if($this->db->trans_status() !== FALSE):
                    $this->db->trans_commit();
                    return $result;
                endif;
            }catch(\Exception $e){
                $this->db->trans_rollback();
                return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
            }	
        }

        public function resetPassword($id){
            try{
                $this->db->trans_begin();

                $data['id'] = $id;
                $data['emp_psc'] = '123456';
                $data['emp_password'] = md5($data['emp_psc']); 
                
                $result = $this->store($this->empMaster,$data);
                $result['message'] = 'Password Reset successfully.';

                if ($this->db->trans_status() !== FALSE):
                    $this->db->trans_commit();
                    return $result;
                endif;
            }catch(\Exception $e){
                $this->db->trans_rollback();
                return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
            }	
        }
    		
		// Migrate Auth ID FROM EMP ID
        public function migrateAuthId($param=[]){
            try{
                $this->db->trans_begin();
				
				$result = [];
                if(!empty($param['auth_id'])){
					$updataData = Array();
					$saID = ((!empty($param['super_auth_id']))?$param['super_auth_id'].',':'').$param['auth_id'];
					$sa_id = implode(',',array_unique(explode(',',$saID)));
					
					$setData = array();
					$setData['tableName'] = $this->empMaster;
					$setData['where']['auth_id'] = $param['auth_id'];
					$setData['update']['super_auth_id'] = "'".$sa_id."'";
					$result = $this->setValue($setData);
					//$this->printQuery();
					
                    $empData1 = $this->getEmpCustomSelect(["select"=>"id, auth_id, super_auth_id","auth_id"=>$param['auth_id']]);
					if(!empty($empData1))
					{
						foreach($empData1 as $row1)
						{
							$saID1 = ((!empty($row1->super_auth_id))?$row1->super_auth_id.',':'').$row1->id;
							$sa_id1 = implode(',',array_unique(explode(',',$saID1)));
							
							$setData1 = array();
							$setData1['tableName'] = $this->empMaster;
							$setData1['where']['auth_id'] = $row1->id;
							$setData1['update']['super_auth_id'] = "'".$sa_id1."'";
							$result = $this->setValue($setData1);
							//$this->printQuery();
							
							$empData2 = $this->getEmpCustomSelect(["select"=>"id, auth_id, super_auth_id","auth_id"=>$row1->id]);
							if(!empty($empData2))
							{
								foreach($empData2 as $row2)
								{
									$saID2 = ((!empty($row2->super_auth_id))?$row2->super_auth_id.',':'').$row2->id;
									$sa_id2 = implode(',',array_unique(explode(',',$saID2)));
									
									$setData2 = array();
									$setData2['tableName'] = $this->empMaster;
									$setData2['where']['auth_id'] = $row2->id;
									$setData2['update']['super_auth_id'] = "'".$sa_id2."'";
									$result = $this->setValue($setData2);
									//$this->printQuery();
									
									$empData3 = $this->getEmpCustomSelect(["select"=>"id, auth_id, super_auth_id","auth_id"=>$row2->id]);
									if(!empty($empData3))
									{
										foreach($empData3 as $row3)
										{
											$saID3 = ((!empty($row3->super_auth_id))?$row3->super_auth_id.',':'').$row3->id;
											$sa_id3 = implode(',',array_unique(explode(',',$saID3)));
											
											$setData3 = array();
											$setData3['tableName'] = $this->empMaster;
											$setData3['where']['auth_id'] = $row3->id;
											$setData3['update']['super_auth_id'] = "'".$sa_id3."'";
											$result = $this->setValue($setData3);
											//$this->printQuery();
											
											
											$empData4 = $this->getEmpCustomSelect(["select"=>"id, auth_id, super_auth_id","auth_id"=>$row3->id]);
											if(!empty($empData4))
											{
												foreach($empData4 as $row4)
												{
													$saID4 = ((!empty($row4->super_auth_id))?$row4->super_auth_id.',':'').$row4->id;
													$sa_id4 = implode(',',array_unique(explode(',',$saID4)));
													
													$setData4 = array();
													$setData4['tableName'] = $this->empMaster;
													$setData4['where']['auth_id'] = $row4->id;
													$setData4['update']['super_auth_id'] = "'".$sa_id4."'";
													$result = $this->setValue($setData4);
													//$this->printQuery();
												}
											}
										}
									}
								}
							}
						}
					}
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
	/********** End Users **********/

    /********** Attendance **********/
        
		public function getAttendanceDTRows($data){ 
            $data['tableName'] = $this->attendance_log;
            $data['select'] = "attendance_log.id, attendance_log.punch_type, attendance_log.type, attendance_log.emp_id, attendance_log.attendance_date, attendance_log.punch_date, attendance_log.start_at, attendance_log.img_file, attendance_log.start_location, attendance_log.loc_add, attendance_log.approve_by, attendance_log.approve_at, attendance_log.notes, attendance_log.quarter_id, attendance_log.attendance_status, attendance_log.created_by, emp.emp_code,emp.emp_name,employee_master.emp_name as approve_name, IFNULL(hq.hq_lat_lng,'') as hq_lat_lng, IFNULL(hq.hq_add,'') as hq_add";
            $data['leftJoin']['employee_master emp'] = "emp.id = attendance_log.emp_id";
            $data['leftJoin']['employee_master'] = "employee_master.id = attendance_log.approve_by";
            $data['leftJoin']['head_quarter hq'] = "hq.id = emp.quarter_id";
			
			if(empty($data['status'])) {  
                $data['where']['attendance_log.approve_by'] = 0;
				$data['customWhere'][] = "DATE(attendance_log.punch_date) >= '2025-09-01'";
				//$data['customWhere'][] = "MONTH(attendance_log.punch_date) = ".date('m')." AND YEAR(attendance_log.punch_date) = ".date('Y');
                //$data['customWhere'][] = "DATE(attendance_log.punch_date) BETWEEN CURDATE() - INTERVAL 5 DAY AND CURDATE()";
            }
			elseif($data['status'] == 3){ //Late Approve
				$data['customWhere'][] = "MONTH(attendance_log.punch_date) = ".date('m')." AND YEAR(attendance_log.punch_date) = ".date('Y');
                $data['customWhere'][] = "DATE(attendance_log.punch_date) < CURDATE() - INTERVAL 5 DAY";
				$data['where']['attendance_log.approve_by'] = 0;

			}
			elseif($data['status'] == 1){ // Approve
				$data['customWhere'][] = "MONTH(attendance_log.punch_date) = ".date('m')." AND YEAR(attendance_log.punch_date) = ".date('Y');
				$data['where']['attendance_log.approve_by > '] = 0;
				$data['where']['attendance_log.punch_type != '] = 5;
				$data['where_in']['attendance_log.attendance_status'] ='1,3';
				$data['customWhere'][] = 'attendance_log.approve_by != attendance_log.emp_id';
			}
            elseif($data['status'] == 2){ //Reject
				$data['customWhere'][] = "MONTH(attendance_log.punch_date) = ".date('m')." AND YEAR(attendance_log.punch_date) = ".date('Y');
				$data['where']['attendance_log.reject_by > '] = 0;
				$data['where']['attendance_log.attendance_status'] = 2;
				$data['where']['attendance_log.punch_type != '] = 5;
			}
			elseif($data['status'] == 5){ //Auto Punch
				$data['customWhere'][] = "MONTH(attendance_log.punch_date) = ".date('m')." AND YEAR(attendance_log.punch_date) = ".date('Y');
				$data['where']['attendance_log.punch_type'] = 5;
			}
			
			if(!in_array($this->userRole,[1,-1])):
				$data['customWhere'][] = '(find_in_set("'.$this->loginId.'", emp.super_auth_id ) > 0 OR emp.id = '.$this->loginId.')';
            endif;
			
			$data['order_by']['attendance_log.punch_date'] = "DESC";
			$data['order_by']['attendance_log.emp_id'] = "DESC";
            
            $data['searchCol'][] = "";
            $data['searchCol'][] = "";
            $data['searchCol'][] = "emp.emp_code";
            $data['searchCol'][] = "emp.emp_name";
            $data['searchCol'][] = "attendance_log.type";
            $data['searchCol'][] = "attendance_log.punch_date";
            $data['searchCol'][] = "attendance_log.loc_add";
            $data['searchCol'][] = "employee_master.emp_name";

            $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;

            if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
            return $this->pagingRows($data);
        }
		
        public function getEmployeeData(){
            $data['tableName'] = $this->attendance_log;
            $data['where']['emp_id'] = $this->loginId;
            $data['order_by']['punch_date'] = "DESC";
            $data['limit'] = 1;
            return $this->row($data);
        }

        public function getEmpLogData($data = []){
            $queryData['tableName'] = $this->attendance_log;

            $queryData['select'] = "attendance_log.*,employee_master.emp_code,employee_master.emp_name,CONCAT(head_quarter.hq_lat,',',head_quarter.hq_long) as hq_location,head_quarter.name as hq_name";
            $queryData['leftJoin']['employee_master'] = "employee_master.id = attendance_log.emp_id";
            $queryData['leftJoin']['head_quarter'] = "employee_master.quarter_id = head_quarter.id";

            $queryData['where']['attendance_log.emp_id'] = $this->loginId;
            $queryData['order_by']['attendance_log.punch_date'] = "DESC";

            if(isset($data['attendance_status'])):
                $queryData['where']['attendance_log.attendance_status'] = $data['attendance_status'];
            endif;

            if(!empty($data['search'])):
                $queryData['like']['attendance_log.type'] = $data['search'];
                $queryData['like']['DATE_FORMAT(attendance_log.punch_date,"%d-%m-%Y")'] = $data['search'];
                $queryData['like']['attendance_log.loc_add'] = $data['search'];
                $queryData['like']['attendance_log.start_location'] = $data['search'];
            endif;
			
			if(!in_array($this->userRole,[1,-1])):
				if(!empty($data['self_punch']) AND $data['self_punch']==1):	// Self Punch
					$queryData['where']['attendance_log.emp_id'] = $this->loginId;
				elseif(!empty($data['self_punch']) AND $data['self_punch']==2):	// Punch To Be Approved
					$queryData['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id ) > 0 )';
				else:
					$queryData['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id ) > 0 OR attendance_log.emp_id = '.$this->loginId.')';
				endif;
			endif;
			
			
            if(isset($data['start']) && isset($data['length'])):
                $queryData['start'] = $data['start'];
                $queryData['length'] = $data['length'];
            endif;

            return $this->rows($queryData);
        }
		
        public function getAttendanceList($data = []){
            $queryData['tableName'] = $this->attendance_log;

            $queryData['select'] = "attendance_log.*,employee_master.emp_code,employee_master.emp_name,CONCAT(head_quarter.hq_lat,',',head_quarter.hq_long) as hq_location,head_quarter.name as hq_name";
			$queryData['select'] .= ", CASE WHEN MOD(ROW_NUMBER() OVER (PARTITION BY attendance_log.emp_id, attendance_log.attendance_date ORDER BY attendance_log.punch_date), 2) = 1 THEN 'IN' ELSE 'OUT' END AS in_out_flag";
            $queryData['leftJoin']['employee_master'] = "employee_master.id = attendance_log.emp_id";
            $queryData['leftJoin']['head_quarter'] = "employee_master.quarter_id = head_quarter.id";

            $queryData['where']['attendance_log.emp_id'] = $this->loginId;
            $queryData['order_by']['attendance_log.punch_date'] = "DESC";

            if(isset($data['attendance_status'])):
                $queryData['where']['attendance_log.attendance_status'] = $data['attendance_status'];
            endif;

            if(!empty($data['search'])):
                $queryData['like']['attendance_log.type'] = $data['search'];
                $queryData['like']['DATE_FORMAT(attendance_log.punch_date,"%d-%m-%Y")'] = $data['search'];
                $queryData['like']['attendance_log.loc_add'] = $data['search'];
                $queryData['like']['attendance_log.start_location'] = $data['search'];
            endif;
			
			if(!in_array($this->userRole,[1,-1])):
				if(!empty($data['self_punch']) AND $data['self_punch']==1):	// Self Punch
					$queryData['where']['attendance_log.emp_id'] = $this->loginId;
				elseif(!empty($data['self_punch']) AND $data['self_punch']==2):	// Punch To Be Approved
					$queryData['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id ) > 0 )';
				else:
					$queryData['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id ) > 0 OR attendance_log.emp_id = '.$this->loginId.')';
				endif;
			endif;
			
			
            if(isset($data['start']) && isset($data['length'])):
                $queryData['start'] = $data['start'];
                $queryData['length'] = $data['length'];
            endif;

            return $this->rows($queryData);
        }

        public function countEmpPunches($param = []){
            $queryData['tableName'] = $this->attendance_log;
            $queryData['select'] = "count(*) as total_punch";
            $queryData['where']['attendance_log.emp_id'] = (!empty($param['emp_id']) ? $param['emp_id'] : $this->loginId);
			if(!empty($param['from_date'])){ $queryData['where']['DATE(attendance_log.punch_date) >= '] = $param['from_date'];} 
            if(!empty($param['to_date'])){ $queryData['where']['DATE(attendance_log.punch_date) <= '] = $param['to_date'];}

            return $this->row($queryData);
        }

        public function saveAttendance($data){
            try{
                $this->db->trans_begin();
                
                //$empData = $this->getEmployee(['id'=>$this->loginId]);
                
                //if($this->checkDuplicateAttendance($data) > 0):
                //    return ['status'=>0,'message'=>"Attendance already added."];
                //endif;
				
                $result = $this->store($this->attendance_log,$data,'Attendance Log');
                
                // Insert Location Log
                $locLog = Array();
                $locLog['log_type'] = ($data['type'] == 'IN') ? 1 : 2;
				$locLog['ref_id'] = $result['insert_id'];
                $locLog['emp_id'] = $this->loginId;
                $locLog['hq_lat_lng'] = (!empty($data['hq_lat_lng']) ? $data['hq_lat_lng'] : '' );
                $locLog['log_time'] = $data['punch_date'];
                $locLog['location'] = (!empty($data['start_location']) ? $data['start_location'] :"");
                $locLog['address'] = $data['loc_add'];
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
		
		public function checkDuplicateAttendance($data){
            $queryData['tableName'] = $this->attendance_log;
            $queryData['where']['type'] = $data['type'];
            $queryData['where']['emp_id'] = $data['emp_id'];
            $queryData['where']['DATE(punch_date)'] = date('Y-m-d',strtotime($data['punch_date']));
            
            if(!empty($data['id'])) { $queryData['where']['id != '] = $data['id']; }
            
            $queryData['resultType'] = "numRows";
            return $this->specificRow($queryData);
        }

        public function getEmpPunchesByDate($param = []){
			$dateCondition = '';
			if(!empty($param['from_date'])){$dateCondition .= "AND DATE(al.punch_date) >= '".$param['from_date']."'";}
			if(!empty($param['to_date'])){$dateCondition .= "AND DATE(al.punch_date) <= '".$param['to_date']."'";}
			
            $data['tableName'] = $this->empMaster;
            $data['select'] = "employee_master.emp_code,employee_master.emp_name";
            $data['select'] .= ",( 
									SELECT GROUP_CONCAT(al.punch_date) FROM attendance_log as al WHERE al.emp_id = employee_master.id AND al.is_delete=0 ".$dateCondition." 
								) as punch_date,";
								
            if(!empty($param['emp_id'])){$data['where']['employee_master.id'] = $param['emp_id'];}
			$data['where']['employee_master.is_active'] = 1;
			$data['where']['employee_master.attendance_status'] = 1;
            
			// 20-05-2024
			if(!in_array($this->userRole,[1,-1])):
				$data['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id) > 0 OR employee_master.id = '.$this->loginId.')';
			endif;
			
			if(!empty($param['group_by'])):
                $data['group_by'][] = $param['group_by'];
            endif;

            if(!empty($param['having'])):
                $data['having'][] = $param['having'];
            endif;
			
            return $this->rows($data);
        }

        public function getPunchByDate($param = []){
            $data['tableName'] = $this->attendance_log;
            $data['select'] = "attendance_log.*,employee_master.emp_code,employee_master.emp_name,shift_master.shift_start,shift_master.shift_end,shift_master.let_in_time,shift_master.early_out_time";
            if(!empty($param['count'])){ $data['select'] .= ",count(attendance_log.id) as count_punch"; }
            $data['leftJoin']['employee_master'] = "employee_master.id = attendance_log.emp_id AND employee_master.is_active = 1";
            $data['leftJoin']['shift_master'] = "shift_master.id = attendance_log.shift_id";
            if(!empty($param['from_date'])){$data['where']['DATE(attendance_log.punch_date) >= '] = $param['from_date'];}
            if(!empty($param['to_date'])){$data['where']['DATE(attendance_log.punch_date) <= '] = $param['to_date'];}
            if(!empty($param['report_date'])){$data['where']['DATE(attendance_log.punch_date)'] = $param['report_date'];}
            if(!empty($param['emp_id'])){$data['where']['attendance_log.emp_id'] = $param['emp_id'];}
			
			$data['where']['attendance_log.approve_by > '] = 0;
            
			$data['where_in']['attendance_log.attendance_status'] ='1,3';
			
			if(!in_array($this->userRole,[1,-1])):
				$data['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id) > 0 OR employee_master.id = '.$this->loginId.')';
			endif;
			
			if(!empty($param['group_by'])):
                $data['group_by'][] = $param['group_by'];
            endif;

            if(!empty($param['having'])):
                $data['having'][] = $param['having'];
            endif;
			
			$data['order_by']['attendance_log.punch_date'] = 'ASC';

            $result = $this->rows($data);
			//$this->printQuery();
			return $result;
        }

		public function getPunchByDateNew($param = []){
            $data['tableName'] = $this->attendance_log;
            $data['select'] = "attendance_log.attendance_date, attendance_log.emp_id, group_concat(attendance_log.punch_date) as punch_date, employee_master.emp_code, employee_master.emp_name, shift_master.shift_start, shift_master.shift_end, shift_master.let_in_time, shift_master.early_out_time";
			
            $data['leftJoin']['employee_master'] = "employee_master.id = attendance_log.emp_id AND employee_master.is_active = 1";
            $data['leftJoin']['shift_master'] = "shift_master.id = attendance_log.shift_id";
			
			$data['where']['attendance_log.approve_by > '] = 0;
			
			$data['where_in']['attendance_log.attendance_status'] ='1,3';
            
			if(!empty($param['from_date'])){$data['where']['DATE(attendance_log.punch_date) >= '] = $param['from_date'];}
            if(!empty($param['to_date'])){$data['where']['DATE(attendance_log.punch_date) <= '] = $param['to_date'];}
            
			if(!in_array($this->userRole,[1,-1])):
				$data['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id) > 0 OR employee_master.id = '.$this->loginId.')';
			endif;
			
			$data['order_by']['attendance_log.emp_id'] = 'ASC';
			$data['order_by']['attendance_log.attendance_date'] = 'ASC';
			
			$data['group_by'][] = 'attendance_log.emp_id';
			$data['group_by'][] = 'attendance_log.attendance_date';
			
            $result = $this->rows($data);
			
			return $result;
        }

		public function getMonthlyAttendance($param = []){
            $queryData['tableName'] = $this->attendance_log;
            $queryData['select'] = "attendance_log.id, attendance_log.punch_type, attendance_log.type, attendance_log.emp_id, attendance_log.attendance_date, attendance_log.punch_date, attendance_log.start_at, attendance_log.img_file, attendance_log.start_location, attendance_log.loc_add, attendance_log.approve_by, attendance_log.approve_at, attendance_log.notes, attendance_log.quarter_id, attendance_log.attendance_status, attendance_log.created_by, emp.emp_code,emp.emp_name,employee_master.emp_name as approve_name, IFNULL(hq.hq_lat_lng,'') as hq_lat_lng, IFNULL(hq.hq_add,'') as hq_add";
            $data['leftJoin']['employee_master emp'] = "emp.id = attendance_log.emp_id";
            $data['leftJoin']['employee_master'] = "employee_master.id = attendance_log.approve_by";
            $data['leftJoin']['head_quarter hq'] = "hq.id = emp.quarter_id";
			
			if(empty($data['status'])) {  $data['where']['attendance_log.approve_by'] = 0; }
			elseif($data['status'] == 1){
				$queryData['customWhere'][] = "MONTH(attendance_log.punch_date) = ".date('m')." AND YEAR(attendance_log.punch_date) = ".date('Y');
				$queryData['where']['attendance_log.approve_by > '] = 0;
				$queryData['customWhere'][] = 'attendance_log.approve_by = attendance_log.emp_id';
			}
			elseif($data['status'] == 2){
				$queryData['customWhere'][] = "MONTH(attendance_log.punch_date) = ".date('m')." AND YEAR(attendance_log.punch_date) = ".date('Y');
				$queryData['where']['attendance_log.approve_by > '] = 0;
				$queryData['where']['attendance_log.punch_type != '] = 5;
				$queryData['customWhere'][] = 'attendance_log.approve_by != attendance_log.emp_id';
			}
			elseif($data['status'] == 5){
				$queryData['customWhere'][] = "MONTH(attendance_log.punch_date) = ".date('m')." AND YEAR(attendance_log.punch_date) = ".date('Y');
				$queryData['where']['attendance_log.punch_type'] = 5;
			}
			
			if(!in_array($this->userRole,[1,-1])):
				$queryData['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id ) >0 OR employee_master.id = '.$this->loginId.')';
			endif;

            if(!empty($param['emp_id'])) { $data['where']['employee_master.id'] = $param['emp_id']; }
            return $this->rows($data);
        }
		
		public function getAttendanceStats($param = []){
            $data['tableName'] = $this->empMaster;
            $data['select'] = "employee_master.*,DATE(aLog.punch_date) as punch_date,lm.start_date";

            $data['leftJoin']['(SELECT punch_date,emp_id FROM attendance_log WHERE is_delete = 0 AND DATE(punch_date) >= "'.$param['month'].'" AND MONTH(punch_date) = "'.date('m',strtotime($param['month'])).'" AND YEAR(punch_date) = "'.date('Y',strtotime($param['month'])).'" AND attendance_log.approve_by != 0 GROUP BY DATE(punch_date),emp_id) as aLog'] = "employee_master.id = aLog.emp_id AND employee_master.is_active = 1";

            $data['leftJoin']['(SELECT start_date,emp_id FROM leave_master WHERE is_delete = 0 AND approve_by > 0 AND start_date >= "'.$param['month'].'" AND MONTH(start_date) = "'.date('m',strtotime($param['month'])).'" AND YEAR(start_date) = "'.date('Y',strtotime($param['month'])).'" GROUP BY start_date,emp_id) as lm'] = "employee_master.id = lm.emp_id AND employee_master.is_active = 1";

            $data['where']['employee_master.emp_role !='] = "-1";
			
			if(!in_array($this->userRole,[1,-1])):
				$data['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id ) >0 OR employee_master.id = '.$this->loginId.')';
			endif;
			
            $data['order_by']['employee_master.emp_code'] = "ASC";

            if(!empty($param['emp_id'])) { $data['where']['employee_master.id'] = $param['emp_id']; }
            return $this->rows($data);
        }
    
        public function approveAttendance($data){
            try{
                $this->db->trans_begin();
                if($data['attendance_status'] == 2){ //Reject
                    $data['reject_by'] = $this->loginId;
                    $data['reject_at'] = date("Y-m-d H:i:s");
					
					$this->trashLocationLog(['ref_id'=>$data['id'],'log_type'=>'1,2']);
                }else{
                    $data['approve_by'] = $this->loginId;
                    $data['approve_at'] = date("Y-m-d H:i:s");
                }
                if(!isset($data['attendance_status'])): $data['attendance_status'] = 1; endif;
               
                $result = $this->store($this->attendance_log, $data,'Attendance Status');
                
                if ($this->db->trans_status() !== FALSE):
                    $this->db->trans_commit();
                    return $result;
                endif;
            }catch(\Exception $e){
                $this->db->trans_rollback();
                return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
            }	
        }
	
	    public function confirmAttendance(){
			return ['status'=>1,'message'=>"No Any Action Performed"];
            /*
			try{
                $this->db->trans_begin();
                $param['count'] = 1;
                $param['to_date'] = date("Y-m-d",strtotime(' -1 day'));
                $param['having'] = '(count_punch % 2) > 0';
                $param['group_by'] = 'attendance_log.emp_id,DATE(attendance_log.punch_date)';
                $attenData = $this->getPunchByDate($param);
                $cmInfo = $this->getCompanyInfo();
                foreach($attenData as $punch){ 
                    $end_time = date("Y-m-d H:i:s",strtotime(date("Y-m-d").' 23:30:00'));
                    
					if(date("Y-m-d",strtotime($punch->punch_date)) != date("Y-m-d")){
                        $end_time = date("Y-m-d H:i:s",strtotime(formatDate($punch->punch_date,'Y-m-d').' '.$cmInfo->punch_out_time));
                    }

                    if(date("Y-m-d",strtotime($punch->punch_date)) != date("Y-m-d") OR (date("Y-m-d",strtotime($punch->punch_date)) == date("Y-m-d") && date('H:i') >= date("H:i",strtotime($cmInfo->punch_out_time)))){
                        $punchData = [
                            'id'=>'',
                            'type'=> 'OUT',
                            'punch_type' => 5,
                            'emp_id' => $punch->emp_id,
                            'attendance_date'=>formatDate($end_time,'Y-m-d'),
                            'punch_date'=>$end_time,
                            'start_at' =>$end_time,
                            'hq_lat_lng' =>$punch->hq_lat_lng,
                            'hq_add' => $punch->hq_add,
                            'start_location' =>$punch->hq_lat_lng,
                            'loc_add' => $punch->hq_add,
                            'attendance_status' => 1,
                            'approve_by' => 1,
                            'approve_at'=> date("Y-m-d H:i:s")
                        ];
                    }
                }
                $result = ['status'=>1,'message'=>"Attendance Confirmed Successfully."];

                if ($this->db->trans_status() !== FALSE):
                    $this->db->trans_commit();
                    return $result;
                endif;
            }catch(\Exception $e){
                $this->db->trans_rollback();
                return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
            }
			*/
        }
		
		public function insertAutoPunch(){
            try{
                $this->db->trans_begin();
                
				$query = $this->db->query("INSERT INTO attendance_log (emp_id, punch_type , type, attendance_date, punch_date, is_delete)
													SELECT t.emp_id, 5 AS punch_type, 'OUT' AS type,t.punch_day as attendance_date, CONCAT(t.punch_day, ' 23:30:00') AS punch_date, 0 AS is_delete
													FROM (
														SELECT emp_id, DATE(punch_date) AS punch_day, COUNT(*) AS punch_count
														FROM attendance_log
														WHERE is_delete = 0 AND DATE(punch_date) <= CURDATE() - INTERVAL 1 DAY
														GROUP BY emp_id, DATE(punch_date)
														HAVING (COUNT(*) % 2) != 0
													) t
													WHERE NOT EXISTS (
														SELECT 1 FROM attendance_log al
														WHERE al.emp_id = t.emp_id
														  AND DATE(al.punch_date) = t.punch_day
														  AND al.punch_type = 5
														  AND TIME(al.punch_date) = '23:30:00'
														  AND al.is_delete = 0
													);");
													
				$result = ['status'=>1,'message'=>'Save Auto Punch Successfully.'];
				
                if ($this->db->trans_status() !== FALSE):
                    $this->db->trans_commit();
                    return $result;
                endif;
            }catch(\Exception $e){
                $this->db->trans_rollback();
                return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
            }	
        }
		
		public function getAttendanceData($data){
			$data['tableName'] = $this->attendance_log;
			$data['select'] = "attendance_log.*,emp.emp_code,emp.emp_name";
			$data['leftJoin']['employee_master emp'] = "emp.id = attendance_log.emp_id";
			$data['order_by']['attendance_log.id'] = 'DESC';
			
			if(!in_array($this->userRole,[1,-1])):
				$data['customWhere'][] = '(find_in_set("'.$this->loginId.'", emp.super_auth_id ) >0 OR emp.id = '.$this->loginId.')';
			endif;
			
			if(!empty($data['approve_by'])):
				$data['where']['attendance_log.approve_by'] = 0;
			endif;
			
			if(!empty($data['limit'])):
				$data['limit'] = $data['limit'];
			endif;
			
			return $this->rows($data);
		}

		public function checkLastAutoPunch($param){
			$queryData['tableName'] = $this->attendance_log;
			$queryData['select'] = "attendance_log.id, attendance_log.punch_date";
			$queryData['where']['emp_id'] = $param['emp_id'];
			$queryData['where']['punch_type'] = 5;
			//$queryData['customWhere'][] = "((notes IS NULL) AND (DATE(punch_date) >= CURDATE() - INTERVAL 1 DAY))";
			$queryData['customWhere'][] = "(notes IS NULL)";
			$queryData['order_by']['punch_date'] = 'DESC';
			$queryData['limit'] = 1;
			$result = $this->row($queryData);
			//$this->printQuery();
			return $result;
		}
		
        public function saveAutoPunchReason($data){
            try{
                $this->db->trans_begin();
                
                $result = $this->store($this->attendance_log,$data,'Attendance Log');

                if ($this->db->trans_status() !== FALSE):
                    $this->db->trans_commit();
                    return $result;
                endif;
            }catch(\Exception $e){
                $this->db->trans_rollback();
                return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
            }	
        }
		
		public function getTodayPunchData(){
			$data['tableName'] = $this->attendance_log;
			$data['customWhere'][] = "DATE(punch_date) = '".date('Y-m-d')."' && emp_id = ".$this->loginId." && type = 'OUT'";
			return $this->row($data);
		}

		public function getDatewiseAttendanceSummary($param){
			
			if(empty($param['from_date'])){$param['from_date'] = date('Y-m-d');}
            if(empty($param['to_date'])){$param['to_date'] = date('Y-m-d');}			
            if(empty($param['emp_id'])){$param['emp_id'] = $this->loginId;}		
			
			
			$queryData = "SELECT COUNT(DISTINCT DATE(alog.punch_date)) AS present_days FROM (SELECT attendance_date,punch_date,approve_by FROM `attendance_log` WHERE emp_id=".$param['emp_id']." AND attendance_date BETWEEN '".formatDate($param['from_date'],'Y-m-d')."' AND '".formatDate($param['to_date'],'Y-m-d')."' group by attendance_date ORDER by attendance_date) as alog WHERE alog.approve_by > 0";
			
			$result = $this->db->query($queryData)->row();
			//$this->printQuery();
			return $result;
		}
		
		// Migrate HQ ID FROM EMP ID
        public function migrateHQID($data){
            try{
                $this->db->trans_begin();
                
				if(!empty($data['emp_id'])){
                    $result = $this->edit($this->attendance_log, ['emp_id'=>$data['emp_id']], ['quarter_id'=>$data['quarter_id']], '');
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
	
	/********** End Attendance **********/

    /********** leave **********/
        public function getLeaveDTRows($data){
            $data['tableName'] = $this->leaveMaster;
            $data['select'] = "leave_master.id,employee_master.emp_name,select_master.label,leave_master.leave_date,leave_master.remark,leave_master.approve_by";
            $data['leftJoin']['employee_master'] = "employee_master.id = leave_master.emp_id";
            $data['leftJoin']['select_master'] = "select_master.id = leave_master.leave_type_id";

            if($data['login_emp_id'] != 1):
                $data['where']['leave_master.emp_id'] = $data['login_emp_id'];
            endif;

            if($data['status'] == 2){
                $data['where']['leave_master.approve_by >'] = 0;
            }else{
                $data['where']['leave_master.approve_by'] = 0;
            }

            if(!in_array($this->userRole,[1,-1])):
                $data['customWhere'][] = '(find_in_set("'.$this->loginId.'", employee_master.super_auth_id ) >0 OR employee_master.id = '.$this->loginId.')';
            endif;
            
            $data['searchCol'][] = "";
            $data['searchCol'][] = "";
            $data['searchCol'][] = "employee_master.emp_name";
            $data['searchCol'][] = "leave_master.leave_date";
            $data['searchCol'][] = "select_master.label";
            $data['searchCol'][] = "leave_master.remark";

            $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;

            if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
            $result = $this->pagingRows($data);
            return $result;
        }

        public function checkDuplicateLeave($leave_date,$emp_id,$id=""){
            $month = date('m',strtotime($leave_date));
            $year = date('Y',strtotime($leave_date));
            $data['tableName'] = $this->leaveMaster;
            $data['where']['leave_date'] = $leave_date;
            $data['where']['emp_id'] = $emp_id;
            $data['where']['MONTH(leave_master.leave_date)'] = $month;
            $data['where']['YEAR(leave_master.leave_date)'] = $year ;
            if(!empty($id))
                $data['where']['id !='] = $id;
            return $this->numRows($data);
        }

        public function getLeave($data){
            $queryData['tableName'] = $this->leaveMaster;
            $queryData['where']['id'] = $data['id'];
            return $this->row($queryData);
        }

        public function saveLeave($data){
            try{
                $this->db->trans_begin();

                if($this->checkDuplicateLeave($data['leave_date'],$data['emp_id'],$data['id']) > 0):
                    $errorMessage['leave_date'] = "Leave date is duplicate.";
                    return ['status'=>0,'message'=>$errorMessage];
                else:
                    $result = $this->store($this->leaveMaster,$data,'Leave');

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

        public function approveLeave($data){
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
    /********** End Leave**********/
		
    /********** Department **********/
    public function getDepartmentDTRows($data){
        $data['tableName'] = $this->departmentMaster;

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "name";
        $data['searchCol'][] = "description";

		$columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;

		if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        return $this->pagingRows($data);
    }

    public function getDepartmentList($data=array()){
        $queryData['tableName'] = $this->departmentMaster;
        return $this->rows($queryData);
    }

    public function getDepartment($data){
        $queryData['where']['id'] = $data['id'];
        $queryData['tableName'] = $this->departmentMaster;
        return $this->row($queryData);
    }

    public function save($data){
        try{
            $this->db->trans_begin();

            if($this->checkDuplicate($data) > 0):
                $errorMessage['name'] = "Department name is duplicate.";
                return ['status'=>0,'message'=>$errorMessage];
            endif;
            
            $result = $this->store($this->departmentMaster,$data,'Department');
            
            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

    public function checkDuplicate($data){
        $queryData['tableName'] = $this->departmentMaster;
        $queryData['where']['name'] = $data['name'];
        
        if(!empty($data['id']))
            $queryData['where']['id !='] = $data['id'];
        
        $queryData['resultType'] = "numRows";
        return $this->specificRow($queryData);
    }

    public function delete($id){
        try{
            $this->db->trans_begin();

            $result = $this->trash($this->departmentMaster,['id'=>$id],'Department');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }
    /********** End Department **********/
	
	public function getTodayPresentData(){
		$data['tableName'] = $this->attendance_log;
		$data['customWhere'][] = "DATE(attendance_log.punch_date) = '".date('Y-m-d')."' ";
        $data['group_by'][] = 'attendance_log.emp_id';
		return $this->rows($data);
	}

    public function getEmpForChart(){

        $empData = $this->db->query("SELECT 
                c.date,
            COUNT(DISTINCT a.id) AS totalEmp
            FROM 
                (SELECT DATE_FORMAT(now() ,'%Y-%m-01') + INTERVAL (n.n) DAY AS date
                FROM (SELECT 0 AS n UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 
                    UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 
                    UNION ALL SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL SELECT 12 UNION ALL SELECT 13 UNION ALL SELECT 14 UNION ALL SELECT 15 
                    UNION ALL SELECT 16 UNION ALL SELECT 17 UNION ALL SELECT 18 UNION ALL SELECT 19 
                    UNION ALL SELECT 20 UNION ALL SELECT 21 UNION ALL SELECT 22 UNION ALL SELECT 23 
                    UNION ALL SELECT 24 UNION ALL SELECT 25 UNION ALL SELECT 26 UNION ALL SELECT 27 
                    UNION ALL SELECT 28 UNION ALL SELECT 29 UNION ALL SELECT 30 UNION ALL SELECT 31) AS n
                WHERE DATE_FORMAT(NOW() ,'%Y-%m-01') + INTERVAL (n.n) DAY <= LAST_DAY(NOW())) AS c
            LEFT JOIN 
                employee_master a ON c.date = DATE(a.created_at)
            WHERE 
                MONTH(c.date) = MONTH(NOW()) AND YEAR(c.date) = YEAR(NOW())
            GROUP BY 
                c.date
            ORDER BY 
                c.date;")->result();
        return $empData;
    } 

    public function getAttandanceForChart(){

        $attenData = $this->db->query("SELECT 
                    c.date,
                COUNT(DISTINCT a.emp_id) AS present
                FROM 
                    (SELECT DATE_FORMAT(now() ,'%Y-%m-01') + INTERVAL (n.n) DAY AS date
                    FROM (SELECT 0 AS n UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 
                        UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 
                        UNION ALL SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL SELECT 12 UNION ALL SELECT 13 UNION ALL SELECT 14 UNION ALL SELECT 15 
                        UNION ALL SELECT 16 UNION ALL SELECT 17 UNION ALL SELECT 18 UNION ALL SELECT 19 
                        UNION ALL SELECT 20 UNION ALL SELECT 21 UNION ALL SELECT 22 UNION ALL SELECT 23 
                        UNION ALL SELECT 24 UNION ALL SELECT 25 UNION ALL SELECT 26 UNION ALL SELECT 27 
                        UNION ALL SELECT 28 UNION ALL SELECT 29 UNION ALL SELECT 30 UNION ALL SELECT 31) AS n
                    WHERE DATE_FORMAT(NOW() ,'%Y-%m-01') + INTERVAL (n.n) DAY <= LAST_DAY(NOW())) AS c
                LEFT JOIN 
                    attendance_log a ON c.date = DATE(a.punch_date)
                WHERE 
                    MONTH(c.date) = MONTH(NOW()) AND YEAR(c.date) = YEAR(NOW())
                GROUP BY 
                    c.date
                ORDER BY 
                    c.date;")->result();
        return $attenData;
    } 
	
	/* Add Employee Recruit Logs */
	public function interviewLogs($data){
		try{
            $this->db->trans_begin();
			
			if(!empty($data)):
				$logData = [
					'id' => "",
					'log_type' => $data['log_type'],
					'from_stage' => $data['from_stage'],
					'emp_id' => $data['emp_id'],
					'ref_date' => date("Y-m-d"),
					'notes' => $data['notes'],
					'reason' => (!empty($data['reason']) ? $data['reason'] : NULL),
					'created_by' => $this->loginId,
				];
				$result =  $this->store($this->interviewLogs,$logData,'Interview Logs');
			endif;
			
            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
	}
	
	/* Get Employee Log Data */
	public function getEmpLogs($data = array()){
		$queryData['tableName'] = $this->interviewLogs;
        $queryData['select'] = 'interview_logs.*, employee_master.emp_name,emp_type.emp_name as employee_name';
		$queryData['leftJoin']['employee_master'] = "employee_master.id = interview_logs.emp_id";
		$queryData['leftJoin']['employee_master emp_type'] = "emp_type.id = interview_logs.created_by";
        if(!empty($data['emp_id'])){ $queryData['where']['emp_id']=$data['emp_id']; }
        return $this->rows($queryData);
	}
	
	/* Get Employee Documents Data */
	public function getEmpDocuments($data){
        $queryData['tableName'] = $this->empDocuments;
        $queryData['select'] = 'emp_docs.*';
        $queryData['where']['emp_id']=$data['emp_id'];		
        return $this->rows($queryData);
    }
	
	/* Change Recruitment status & Add Employee Log Data */
	public function changeAppStatus($postData){
        try{
            $this->db->trans_begin();
			
			if(!empty($postData['id'])):
				$empData = $this->getEmployee(['id' => $postData['id']]);
				$empStatus = (!empty($empData->status) ? $empData->status : 0);				
				$this->interviewLogs(['log_type'=>$postData['status'], 'from_stage'=> $empStatus, 'emp_id'=>$postData['id'], 'reason' => $postData['reason'], 'notes'=>$this->interviewType[$postData['status']]]);
			endif;
			unset($postData['reason']);
            $result = $this->store($this->empMaster,$postData,'');
			
            $result['message'] = "Employee ".(($postData['status'] == 7)?"Rejected":"Approved")." successfully.";
            
            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }
	
	public function editProfile($data){
        try{
            $this->db->trans_begin();

            $form_type = $data['form_type']; unset($data['form_type'], $data['designationTitle']);

            if($form_type == "empDocs"):
                $result = $this->store($this->empDocuments,$data,'Employee Document');
            endif;

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }
	
	public function removeProfileDetails($data){
        try{
            $this->db->trans_begin();

            if($data['form_type'] == "empDocs"):
                if(empty($data['id'])):
                    return ['status'=>0,'message'=>'Somthing went wrong...Please try again.'];
                else:
                    $queryData = array();
                    $queryData['tableName'] = $this->empDocuments;
                    $queryData['where']['id'] = $data['id'];
                    $docDetail = $this->row($queryData);

                    $filePath = realpath(APPPATH . '../assets/uploads/emp_documents/');
                    if(!empty($docDetail->doc_file) && file_exists($filePath.'/'.$docDetail->doc_file)):
                        unlink($filePath.'/'.$docDetail->doc_file);
                    endif;

                    $result = $this->trash($this->empDocuments,['id'=>$data['id'],'emp_id'=>$data['emp_id']],"Employee Document");
                endif;
            endif;
			
            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }
	
	public function saveStaffSkill($data = array()){
		try{
            $this->db->trans_begin();
			
			if(!empty($data['act_per'])){
				$i = 0;
				$this->trash($this->staffSkill,['emp_id'=>$data['emp_id'], 'type' => $data['type']],'Employee');
				foreach($data['act_per'] as $row){
					if(!empty($row)){
						$storeData = [
							'id' => $data['id'][$i],
							'set_id' => $data['set_id'][$i],
							'emp_id' => $data['emp_id'],
							'act_per' => $row,
							'type' => $data['type'],
							'created_by' => $this->loginId,
							'is_delete' => 0
						];
						$this->store($this->staffSkill,$storeData,'Staff Skill');
					}
					$i++;
				}
			}
			
            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return ['status'=>1, 'message' => 'Staff Skill saved Successfully.'];
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
	}
	
	public function saveAppointedForm($data = array()){
		try{
            $this->db->trans_begin();
			
			$result = $this->store($this->empMaster,$data,'Appointed Interview');
			
            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
	}
	
	public function saveRejectEmployee($data = array()){
		try{
            $this->db->trans_begin();
			
			if(!empty($data['id'])):
				$empData = $this->getEmployee(['id' => $data['id']]);
				$empStatus = (!empty($empData->status) ? $empData->status : 0);
				$result = $this->store($this->empDetail,['id' => $empData->emp_detail_id, 'rejected_reason' => $data['reason']],'Employee Rejected');
				$result = $this->store($this->empMaster,['id' => $data['id'], 'status' => 7],'Employee Rejected');
				$this->interviewLogs(['log_type'=>7, 'from_stage'=>$empStatus, 'emp_id'=>$result['id'], 'reason' => $data['reason'], 'notes'=>$this->interviewType[7]]);
			endif;
			
            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
	}
	
	/* Start Vacancy */
    public function getVacancyDTRows($data){ 		
        $data['tableName'] = $this->empVacancy;

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "emp_vacancy.set_name";
        $data['searchCol'][] = "emp_vacancy.vacancy_no";
        $data['searchCol'][] = "emp_vacancy.notes";
        $data['searchCol'][] = "emp_vacancy.publish_to";

		$columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
		if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
		return $this->pagingRows($data);
    }

    public function getVacancyData($data){
        $queryData['tableName'] = $this->empVacancy;
        if(!empty($data['id'])){
            $queryData['where']['emp_vacancy.id'] = $data['id'];
        }
        return $this->row($queryData);
    }

    public function saveVacancy($data){
		try {
            $this->db->trans_begin();
            
            $result = $this->store($this->empVacancy,$data,'Vacancy');
			
            if ($this->db->trans_status() !== FALSE) :
                $this->db->trans_commit();
                return $result;
            endif;
        }catch (\Throwable $e) {
            $this->db->trans_rollback();
            return ['status' => 2, 'message' => "somthing is wrong. Error : " . $e->getMessage()];
        }        
    }

    public function deleteVacancy($data){
		try{
            $this->db->trans_begin();

            $result = $this->trash($this->empVacancy,['id'=>$data['id']],'Vacancy');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }
    /* End Vacancy */
}
?>