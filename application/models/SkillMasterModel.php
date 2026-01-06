<?php
class SkillMasterModel extends MasterModel{
    private $skillMaster = "skill_master";
    private $skillSet = "skill_set"; 
    private $staffSkill = "staff_skill"; 

    public function getDTRows($data){		
        $data['tableName'] = $this->skillMaster;

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "skill_name";

		$columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
		if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
		return $this->pagingRows($data);
    }

    public function getSkill($data){
        $queryData['tableName'] = $this->skillMaster;
        $queryData['where']['id'] = $data['id'];
        return $this->row($queryData);
    }

    public function getSkillList($data=array()){
        $queryData['tableName'] = $this->skillMaster;
        return $this->rows($queryData);
    }

    public function save($data){
		try {
            $this->db->trans_begin();

            $result = $this->store($this->skillMaster,$data,'Skill');
			
            if ($this->db->trans_status() !== FALSE) :
                $this->db->trans_commit();
                return $result;
            endif;
        }catch (\Throwable $e) {
            $this->db->trans_rollback();
            return ['status' => 2, 'message' => "somthing is wrong. Error : " . $e->getMessage()];
        }        
    }

    public function delete($id){
		try{
            $this->db->trans_begin();

            $result = $this->trash($this->skillMaster,['id'=>$id],'Skill');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    /* Start Skill Set */
    public function getSkillSetDTRows($data){		
        $data['tableName'] = $this->skillSet;

        $data['select'] = "skill_set.id,skill_set.set_name,skill_master.skill_name,count(skill_set.id) as skill_count,department_master.name as dept_name,emp_designation.title as dsg_title";
        $data['leftJoin']['skill_master'] = "skill_master.id = skill_set.skill_id";
		$data['leftJoin']['department_master'] = "department_master.id = skill_set.emp_dept_id";
        $data['leftJoin']['emp_designation'] = "emp_designation.id = skill_set.emp_designation";
        $data['group_by'][] = 'skill_set.set_name';

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "skill_set.set_name";
        $data['searchCol'][] = "skill_master.skill_name";

		$columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
		if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
		return $this->pagingRows($data);
    }

    public function getSkillSet($data=array()){
        $queryData['tableName'] = $this->skillSet;
        $queryData['select'] = "skill_set.*,skill_master.skill_name,department_master.name as dept_name,emp_designation.title as dsg_title";
        $queryData['leftJoin']['skill_master'] = "skill_master.id = skill_set.skill_id";
		$queryData['leftJoin']['department_master'] = "department_master.id = skill_set.emp_dept_id";
        $queryData['leftJoin']['emp_designation'] = "emp_designation.id = skill_set.emp_designation";
        if(!empty($data['set_name'])){
            $queryData['where']['skill_set.set_name'] = $data['set_name'];
        }
        if(!empty($data['group_by'])){
            $queryData['group_by'][] = $data['group_by'];
        }

        return $this->rows($queryData);
    }

    public function saveSkillSet($data){
		try {
            $this->db->trans_begin();
            
            $result = $this->store($this->skillSet,$data,'Skill');
			
            if ($this->db->trans_status() !== FALSE) :
                $this->db->trans_commit();
                return $result;
            endif;
        }catch (\Throwable $e) {
            $this->db->trans_rollback();
            return ['status' => 2, 'message' => "somthing is wrong. Error : " . $e->getMessage()];
        }        
    }

    public function checkSkillSetDuplicate($data){
        $queryData['tableName'] = $this->skillSet;
        $queryData['where']['set_name'] = $data['set_name'];
        $queryData['where']['skill_id'] = $data['skill_id'];
        
        if(!empty($data['id']))
            $queryData['where']['id !='] = $data['id'];
        
        $queryData['resultType'] = "numRows";
        return $this->specificRow($queryData);
    }

    public function deleteSet($data){
		try{
            $this->db->trans_begin();

            $result = $this->trash($this->skillSet,['set_name'=>$data['set_name']],'Skill Set');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    public function deleteSkillSet($data){
		try{
            $this->db->trans_begin();
            $result = $this->trash($this->skillSet,['id'=>$data['id']],'Skill Set');
            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }
	
	public function getStaffSkillData($data = array()){
		$queryData['tableName'] = $this->staffSkill;
        $queryData['select'] = "staff_skill.*, skill_master.skill_name, skill_set.skill_per, skill_set.set_name";
		$queryData['leftJoin']['skill_set'] = "skill_set.id = staff_skill.set_id";
		$queryData['leftJoin']['skill_master'] = "skill_master.id = skill_set.skill_id";
		
        if(!empty($data['emp_id'])){ $queryData['where']['staff_skill.emp_id'] = $data['emp_id']; }
		if(!empty($data['type'])){ $queryData['where']['staff_skill.type'] = $data['type']; }
		
        return $this->rows($queryData);
	}
	
	public function getSkillSetList($data = array()) {
		$staffSkill = array();
        $squery['tableName'] = $this->skillSet;
        $squery['select'] = "DISTINCT(set_name)";
        $setList = $this->rows($squery);
		
        if(!empty($setList)){ $i=0;
            foreach($setList as $set){
                $staffSkill[$i]['set_name'] = $set->set_name;
				
                $data['tableName'] = $this->skillSet;
				$data['select'] = "skill_set.*,skill_master.skill_name";
				$data['leftJoin']['skill_master'] = "skill_master.id = skill_set.skill_id";
                $data['where']['set_name'] = $set->set_name;
                if(!empty($data['emp_dept_id'])){$data['where']['emp_dept_id'] = $data['emp_dept_id'];}
                if(!empty($data['emp_designation'])){$data['where']['emp_designation'] = $data['emp_designation'];}
                $staffSkill[$i++]['skill'] =  $this->rows($data);
            }
        }
		return $staffSkill;
	}
    /* End Skill Set */

}
?>