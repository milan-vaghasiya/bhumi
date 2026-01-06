<?php
class Migration extends MY_Controller{
    public function __construct(){
        parent::__construct();
    }

	//Migration/addColumnInTable
    /*public function addColumnInTable(){
        $result = $this->db->query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'u399487905_jaracrm' AND TABLE_NAME NOT IN ( SELECT TABLE_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME = 'cm_id' AND TABLE_SCHEMA = 'u399487905_jaracrm' )")->result();

        foreach($result as $row):
                $this->db->query("ALTER TABLE ".$row->TABLE_NAME." ADD `cm_id` INT NOT NULL DEFAULT '0' AFTER `is_delete`;");
        endforeach;

        echo "success";exit;
    }*/

	/*** By : NYN @09.03.2024 Migration/migrateFgData ***/    
    public function migrateFgData(){
        try{
            $this->db->trans_begin();
            
            $this->db->reset_query();
            $result = $this->db->get('item_master')->result();
            
            foreach($result as $row):
            
                $this->db->reset_query();
                $udfData = [
                    'item_id'=>$row->id,
                    'f1'=>trim($row->remark)
                ];
                $this->db->insert('item_udf',$udfData);
                
                $itData = array();
                $itData['remark'] = NULL;
                
                $this->db->reset_query();
                $this->db->where('id',$row->id);
                $this->db->update('item_master',$itData);
            endforeach;
            exit;
            if($this->db->trans_status() !== FALSE):
                //$this->db->trans_commit();
                echo "Migration Successfully.";
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            echo $e->getMessage();exit;
        }
    }

    /**
     * Created By : Mansee 07/08/2024
     * App Permission to all employee
     */

    public function migrateAppPermission(){
        try{
            $this->db->trans_begin();
            
            $this->db->reset_query();
            $empList = $this->db->query('SELECT id FROM employee_master WHERE is_delete = 0')->result();

            $this->db->reset_query();
            $menuList = $this->permission->getPermission(0,2);
            $i = 0;
            foreach($empList as $emp){
                $permissionArray = [];
                $permissionArray['emp_id'] = $emp->id;
                $permissionArray['menu_type'] = 2;
                foreach($menuList as $menu){
                    $permissionArray['menu_id'][] =$menu->id;
                    $permissionArray['is_master'][] = 0;
                    $permissionArray['main_id'][] = $menu->id;
                   
                    foreach($menu->subMenus as $row){
                        if(!in_array($row->id,[91,87])){
                            $permissionArray['sub_menu_id_'.$menu->id][] = $row->id;
                            $permissionArray['sub_menu_read_'.$row->id.'_'.$menu->id][0] =1;
                            $permissionArray['sub_menu_write_'.$row->id.'_'.$menu->id][0] =1;
                            $permissionArray['sub_menu_modify_'.$row->id.'_'.$menu->id][0] =1;
                            $permissionArray['sub_menu_delete_'.$row->id.'_'.$menu->id][0] =1;
                        }
                       
                    }
                }
                $this->db->reset_query();
                $this->permission->save($permissionArray);
                $i++;
            }
            exit;
            if($this->db->trans_status() !== FALSE):
                // $this->db->trans_commit();
                echo "Migration Successfully.".$i;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            echo $e->getMessage();exit;
        }
        
    }

	/* NYN Migration/migrateShiftInAttendace */
	public function migrateShiftInAttendace(){
        try{
            $this->db->trans_begin();
            
            $this->db->reset_query();
            $empList = $this->db->query('SELECT 
					employee_master.id,employee_master.shift_id,shift_master.shift_start,shift_master.shift_end
				FROM 
					employee_master 
				LEFT JOIN shift_master ON shift_master.id = employee_master.shift_id
				WHERE 
					employee_master.is_delete = 0')->result();
            
            foreach($empList as $row):
				$emData = [
					'shift_id'=>$row->shift_id
				];
                $this->db->reset_query();
				$this->db->where('emp_id',$row->id);
                $this->db->update('attendance_log',$emData);
            endforeach;
			
            exit;
			
            if($this->db->trans_status() !== FALSE):
                //$this->db->trans_commit();
                echo "Migration Successfully.";
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            echo $e->getMessage();exit;
        }
    }
}
?>