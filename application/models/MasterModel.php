<?php /* Master Modal Ver. : 1.2  */
class MasterModel extends CI_Model{
    
    public function mapConditions($data){
		if(isset($data['select'])):
            if(!empty($data['select'])):
                $this->db->select($data['select']);
            endif;
        endif;

        if(isset($data['join'])):
            if(!empty($data['join'])):
                foreach($data['join'] as $key=>$value):
                    $this->db->join($key,$value);
                endforeach;
            endif;
        endif;

        if(isset($data['leftJoin'])):
            if(!empty($data['leftJoin'])):
                foreach($data['leftJoin'] as $key=>$value):
                    $this->db->join($key,$value,'left',false);
                endforeach;
            endif;
        endif;

        if(isset($data['where'])):
            if(!empty($data['where'])):
                foreach($data['where'] as $key=>$value):
                    $this->db->where($key,$value);
                endforeach;
            endif;            
        endif;

        if(isset($data['where_or'])):
            if(!empty($data['where_or'])):
                $i=1;
                $this->db->group_start();
                foreach($data['where_or'] as $key=>$value):
                    if($i == 1):
                        $this->db->where($key,$value);
                    else:
                        $this->db->or_where($key,$value);
                    endif;
                    $i++;
                endforeach;
                $this->db->group_end();
            endif;
        endif;
        
        if(isset($data['whereFalse'])):
            if(!empty($data['whereFalse'])):
                foreach($data['whereFalse'] as $key=>$value):
                    $this->db->where($key,$value,false); 
                endforeach;
            endif;            
        endif;
        
        if(isset($data['customWhere'])):
            if(!empty($data['customWhere'])):
                foreach($data['customWhere'] as $value):
                    $this->db->where($value);
                endforeach;
            endif;
        endif;

        if(isset($data['where_in'])):
            if(!empty($data['where_in'])):
                foreach($data['where_in'] as $key=>$value):
                    $this->db->where_in($key,$value,false);
                endforeach;
            endif;
        endif;

        if(isset($data['where_not_in'])):
            if(!empty($data['where_not_in'])):
                foreach($data['where_not_in'] as $key=>$value):
                    $this->db->where_not_in($key,$value,false);
                endforeach;
            endif;
        endif;

        if (isset($data['having'])) :
			if (!empty($data['having'])) :
				foreach ($data['having'] as $value) :
					$this->db->having($value);
				endforeach;
			endif;
		endif;

        if(isset($data['like'])):
            if(!empty($data['like'])):
                $i=1;
                $this->db->group_start();
                foreach($data['like'] as $key=>$value):
                    if($i == 1):
                        $this->db->like($key,$value,'both',false);
                    else:
                        $this->db->or_like($key,$value,'both',false);
                    endif;
                    $i++;
                endforeach;
                $this->db->group_end();
            endif;
        endif;

        if(isset($data['columnSearch'])):
            if(!empty($data['columnSearch'])):                
                $this->db->group_start();
                foreach($data['columnSearch'] as $key=>$value):
                    $this->db->like($key,$value);
                endforeach;
                $this->db->group_end();
            endif;
        endif;

        if(isset($data['order_by'])):
            if(!empty($data['order_by'])):
                foreach($data['order_by'] as $key=>$value):
                    $this->db->order_by($key,$value);
                endforeach;
            endif;
        endif;

        if(isset($data['order_by_field'])):
            if(!empty($data['order_by_field'])):
                foreach($data['order_by_field'] as $key=>$value):
                    $this->db->order_by("FIELD(".$key.", ".implode(",",$value).")", '', false);
                endforeach;
            endif;
        endif;

        if(isset($data['group_by'])):
            if(!empty($data['group_by'])):
                foreach($data['group_by'] as $key=>$value):
                    $this->db->group_by($value);
                endforeach;
            endif;
        endif;

		if(isset($data['limit'])):
            if(!empty($data['limit'])):
                $this->db->limit($data['limit']);
            endif;
        endif;

        if(isset($data['start']) && isset($data['length'])):
            if(!empty($data['length'])):
                $this->db->limit($data['length'],$data['start']);
            endif;
        endif;
	}

    /* Get All Rows */
	public function getData($param,$selectType = "rows"){
		
		$this->mapConditions($param);
		
		if(isset($param['all'])):
            if(!empty($param['all'])):
                foreach($param['all'] as $key=>$value):
                    $this->db->where_in($key,$value,false);
                endforeach;
            endif;
        else:
            $this->db->where($param['tableName'].'.is_delete',0);
        endif;
        
		
		if($selectType == "rows"):
			return $this->db->get($param['tableName'])->result();
		endif;
		
		if($selectType == "row"):
			return $this->db->get($param['tableName'])->row();
		endif;
		
		if($selectType == "numRows"):
			return $this->db->get($param['tableName'])->num_rows();
		endif;
		
        //print_r($this->db->last_query());
        return $result;
	}
    
    
    /* Get Paging Rows */
    public function pagingRows($data){
        $draw = $data['draw'];
		$start = $data['start'];
		$rowperpage = $data['length']; // Rows display per page
		$searchValue = $data['search']['value'];		
		
		/********** Total Records without Filtering ***********/
		{
            if(isset($data['select'])):
                if(!empty($data['select'])):
                    $this->db->select($data['select']);
                endif;
            endif;
    
            if(isset($data['join'])):
                if(!empty($data['join'])):
                    foreach($data['join'] as $key=>$value):
                        $this->db->join($key,$value);
                    endforeach;
                endif;
            endif;
    
            if(isset($data['leftJoin'])):
                if(!empty($data['leftJoin'])):
                    foreach($data['leftJoin'] as $key=>$value):
                        $this->db->join($key,$value,'left');
                    endforeach;
                endif;
            endif;
    
            if(isset($data['where'])):
                if(!empty($data['where'])):
                    foreach($data['where'] as $key=>$value):
                        $this->db->where($key,$value);
                    endforeach;
                endif;            
            endif;
            if(isset($data['whereFalse'])):
                if(!empty($data['whereFalse'])):
                    foreach($data['whereFalse'] as $key=>$value):
                        $this->db->where($key,$value,false);
                    endforeach;
                endif;            
            endif;
            if(isset($data['customWhere'])):
                if(!empty($data['customWhere'])):
                    foreach($data['customWhere'] as $value):
                        $this->db->where($value);
                    endforeach;
                endif;
            endif;
            $this->db->where($data['tableName'].'.is_delete',0);
            if(!empty($data['cm_id'])):
                $this->db->where_in($data['tableName'].'.cm_id',[$data['cm_id'],0]);
            else:
                
    		    if(!empty($this->CMID)){ $this->db->where_in($data['tableName'].'.cm_id',[$this->CMID,0]);}
                
            endif;
            if(isset($data['where_in'])):
                if(!empty($data['where_in'])):
                    foreach($data['where_in'] as $key=>$value):
                        $this->db->where_in($key,$value,false);
                    endforeach;
                endif;
            endif;

            if(isset($data['where_not_in'])):
                if(!empty($data['where_not_in'])):
                    foreach($data['where_not_in'] as $key=>$value):
                        $this->db->where_not_in($key,$value,false);
                    endforeach;
                endif;
            endif;

    		if (isset($data['having'])) :
				if (!empty($data['having'])) :
					foreach ($data['having'] as $value) :
						$this->db->having($value);
					endforeach;
				endif;
			endif;

    		if(isset($data['group_by'])):
                if(!empty($data['group_by'])):
                    foreach($data['group_by'] as $key=>$value):
                        $this->db->group_by($value);
                    endforeach;
                endif;
            endif;
    		
            $totalRecords = $this->db->get($data['tableName'])->num_rows();
            
		}
        /********** End Count Total Records without Filtering ***********/
        
        
        
        /********** Count Total Records with Filtering ***********/
        {
    		if(isset($data['select'])):
                if(!empty($data['select'])):
                    $this->db->select($data['select']);
                endif;
            endif;
    
            if(isset($data['join'])):
                if(!empty($data['join'])):
                    foreach($data['join'] as $key=>$value):
                        $this->db->join($key,$value);
                    endforeach;
                endif;
            endif;
    
            if(isset($data['leftJoin'])):
                if(!empty($data['leftJoin'])):
                    foreach($data['leftJoin'] as $key=>$value):
                        $this->db->join($key,$value,'left');
                    endforeach;
                endif;
            endif;
    
            if(isset($data['where'])):
                if(!empty($data['where'])):
                    foreach($data['where'] as $key=>$value):
                        $this->db->where($key,$value);
                    endforeach;
                endif;            
            endif;

			if(isset($data['whereFalse'])):
				if(!empty($data['whereFalse'])):
					foreach($data['whereFalse'] as $key=>$value):
						$this->db->where($key,$value);
					endforeach;
				endif;            
			endif;

            if(isset($data['customWhere'])):
                if(!empty($data['customWhere'])):
                    foreach($data['customWhere'] as $value):
                        $this->db->where($value);
                    endforeach;
                endif;
            endif;
            $this->db->where($data['tableName'].'.is_delete',0);
    
            if(!empty($data['cm_id'])):
                $this->db->where_in($data['tableName'].'.cm_id',[$data['cm_id'],0]);
            else:
    		    if(!empty($this->CMID)){$this->db->where_in($data['tableName'].'.cm_id',[$this->CMID,0]);}
            endif;

            if(isset($data['where_in'])):
                if(!empty($data['where_in'])):
                    foreach($data['where_in'] as $key=>$value):
                        $this->db->where_in($key,$value,false);
                    endforeach;
                endif;
            endif;

            if(isset($data['where_not_in'])):
                if(!empty($data['where_not_in'])):
                    foreach($data['where_not_in'] as $key=>$value):
                        $this->db->where_not_in($key,$value,false);
                    endforeach;
                endif;
            endif;

    		if (isset($data['having'])) :
				if (!empty($data['having'])) :
					foreach ($data['having'] as $value) :
						$this->db->having($value);
					endforeach;
				endif;
			endif;
			
    		$c=0;
    		// General Search
    		if(!empty($searchValue)):
                if(isset($data['searchCol'])):
                    if(!empty($data['searchCol'])):
                        $this->db->group_start();
    						foreach($data['searchCol'] as $key=>$value):
    						    if(!empty($value)){
        							if($key == 0):
        								$this->db->like($value,str_replace(" ", "%", $searchValue),'both',false);
        							else:
        								$this->db->or_like($value,str_replace(" ", "%", $searchValue),'both',false);
        							endif;
    						    }
    						endforeach;
                        $this->db->group_end();
                    endif;
                endif;
    		endif;
    		
    		// Column Search
    		if(isset($data['searchCol'])):
    			if(!empty($data['searchCol'])):
    				foreach($data['searchCol'] as $key=>$value):
    					if(!empty($value)){
    						$csearch = $data['columns'][$key]['search']['value'];
    						if(!empty($csearch)){$this->db->like($value,$csearch);}
    					}
    				endforeach;
    			endif;
    		endif;
    		
    		if(isset($data['group_by'])):
                if(!empty($data['group_by'])):
                    foreach($data['group_by'] as $key=>$value):
                        $this->db->group_by($value);
                    endforeach;
                endif;
            endif;
    		
    		$totalRecordwithFilter = $this->db->get($data['tableName'])->num_rows();
    		//print_r($this->db->last_query());
        }
        /********** End Count Total Records with Filtering ***********/
		
		
        /********** Total Records with Filtering ***********/
        {
            if(isset($data['select'])):
                if(!empty($data['select'])):
                    $this->db->select($data['select']);
                endif;
            endif;
    
            if(isset($data['join'])):
                if(!empty($data['join'])):
                    foreach($data['join'] as $key=>$value):
                        $this->db->join($key,$value);
                    endforeach;
                endif;
            endif;  
            
            if(isset($data['leftJoin'])):
                if(!empty($data['leftJoin'])):
                    foreach($data['leftJoin'] as $key=>$value):
                        $this->db->join($key,$value,'left');
                    endforeach;
                endif;
            endif;
    
            if(isset($data['where'])):
                if(!empty($data['where'])):
                    foreach($data['where'] as $key=>$value):
                        $this->db->where($key,$value);
                    endforeach;
                endif;            
            endif;

			if(isset($data['whereFalse'])):
				if(!empty($data['whereFalse'])):
					foreach($data['whereFalse'] as $key=>$value):
						$this->db->where($key,$value);
					endforeach;
				endif;            
			endif;
            
            if(isset($data['customWhere'])):
                if(!empty($data['customWhere'])):
                    foreach($data['customWhere'] as $value):
                        $this->db->where($value);
                    endforeach;
                endif;
            endif;

            $this->db->where($data['tableName'].'.is_delete',0);
    
            if(!empty($data['cm_id'])):
                $this->db->where_in($data['tableName'].'.cm_id',[$data['cm_id'],0]);
            else:
    		    if(!empty($this->CMID)){$this->db->where_in($data['tableName'].'.cm_id',[$this->CMID,0]);}
            endif;

            if(isset($data['where_in'])):
                if(!empty($data['where_in'])):
                    foreach($data['where_in'] as $key=>$value):
                        $this->db->where_in($key,$value,false);
                    endforeach;
                endif;
            endif;

            if(isset($data['where_not_in'])):
                if(!empty($data['where_not_in'])):
                    foreach($data['where_not_in'] as $key=>$value):
                        $this->db->where_not_in($key,$value,false);
                    endforeach;
                endif;
            endif;
    
    		$c=0;
    		// General Search
    		if(!empty($searchValue)):
                if(isset($data['searchCol'])):
                    if(!empty($data['searchCol'])):
                        $this->db->group_start();
                        foreach($data['searchCol'] as $key=>$value):
                            if(!empty($value)){
                                if($key == 0):
                                    $this->db->like($value,str_replace(" ", "%", $searchValue),'both',false);
                                else:
                                    $this->db->or_like($value,str_replace(" ", "%", $searchValue),'both',false);
                                endif;
                            }
                        endforeach;
                        $this->db->group_end();
                    endif;
                endif;
    		endif;
    		
    		// Column Search
    		if(isset($data['searchCol'])):
    			if(!empty($data['searchCol'])):
    				foreach($data['searchCol'] as $key=>$value):
    					if(!empty($value)){
    						$csearch = $data['columns'][$key]['search']['value'];
    						if(!empty($csearch)){$this->db->like($value,$csearch);}
    					}
    				endforeach;
    			endif;
    		endif;
            
            if(isset($data['order_by'])):
                if(!empty($data['order_by'])):
                    foreach($data['order_by'] as $key=>$value):
                        $this->db->order_by($key,$value);
                    endforeach;
                endif;
            endif;
    
            
    		if (isset($data['having'])) :
				if (!empty($data['having'])) :
					foreach ($data['having'] as $value) :
						$this->db->having($value);
					endforeach;
				endif;
			endif;


            if(isset($data['group_by'])):
                if(!empty($data['group_by'])):
                    foreach($data['group_by'] as $key=>$value):
                        $this->db->group_by($value);
                    endforeach;
                endif;
            endif;
    
            $resultData = $this->db->limit($rowperpage, $start)->get($data['tableName'])->result();
        }
        /********** End Total Records with Filtering ***********/
        
        $response = [
            "draw" => intval($draw),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecordwithFilter,
            "data" => $resultData
        ]; 
        return $response;
    }

    /* Get All Rows */
    public function rows($data){
		
		$this->db->reset_query();

		$this->mapConditions($data);
        
		if(isset($data['all'])):
            if(!empty($data['all'])):
                foreach($data['all'] as $key=>$value):
                    $this->db->where_in($key,$value,false);
                endforeach;
            endif;
        else:
            $this->db->where($data['tableName'].'.is_delete',0);
        endif;
        
        if(!empty($data['cm_id'])):
            $this->db->where_in($data['tableName'].'.cm_id',[$data['cm_id'],0]);
        else:
		    if(!empty($this->CMID)){$this->db->where_in($data['tableName'].'.cm_id',[$this->CMID,0]);}
        endif;

        $result = $this->db->get($data['tableName'])->result();
        return $result;
    }

    /* Get Single Row */
    public function row($data){
        if(isset($data['select'])):
            if(!empty($data['select'])):
                $this->db->select($data['select']);
            endif;
        endif;

        if(isset($data['join'])):
            if(!empty($data['join'])):
                foreach($data['join'] as $key=>$value):
                    $this->db->join($key,$value);
                endforeach;
            endif;
        endif;

        if(isset($data['leftJoin'])):
            if(!empty($data['leftJoin'])):
                foreach($data['leftJoin'] as $key=>$value):
                    $this->db->join($key,$value,'left');
                endforeach;
            endif;
        endif;

        if(isset($data['where'])):
            if(!empty($data['where'])):
                foreach($data['where'] as $key=>$value):
                    $this->db->where($key,$value);
                endforeach;
            endif;            
        endif;

        if(isset($data['customWhere'])):
            if(!empty($data['customWhere'])):
                foreach($data['customWhere'] as $value):
                    $this->db->where($value);
                endforeach;
            endif;
        endif;
        $this->db->where($data['tableName'].'.is_delete',0);

        if(!empty($data['cm_id'])):
            $this->db->where_in($data['tableName'].'.cm_id',[$data['cm_id'],0]);
        else:
		    if(!empty($this->CMID)){$this->db->where_in($data['tableName'].'.cm_id',[$this->CMID,0]);}
        endif;

        if(isset($data['where_in'])):
            if(!empty($data['where_in'])):
                foreach($data['where_in'] as $key=>$value):
                    $this->db->where_in($key,$value,false);
                endforeach;
            endif;
        endif;

        if(isset($data['where_not_in'])):
            if(!empty($data['where_not_in'])):
                foreach($data['where_not_in'] as $key=>$value):
                    $this->db->where_not_in($key,$value,false);
                endforeach;
            endif;
        endif;

        if(isset($data['like'])):
            if(!empty($data['like'])):
                $i=1;
                $this->db->group_start();
                foreach($data['like'] as $key=>$value):
                    if($i == 1):
                        $this->db->like($key,$value);
                    else:
                        $this->db->or_like($key,$value);
                    endif;
                    $i++;
                endforeach;
                $this->db->group_end();
            endif;
        endif;

        if (isset($data['having'])) :
            if (!empty($data['having'])) :
                foreach ($data['having'] as $value) :
                    $this->db->having($value);
                endforeach;
            endif;
        endif;

        if(isset($data['order_by'])):
            if(!empty($data['order_by'])):
                foreach($data['order_by'] as $key=>$value):
                    $this->db->order_by($key,$value);
                endforeach;
            endif;
        endif;

        if(isset($data['group_by'])):
            if(!empty($data['group_by'])):
                foreach($data['group_by'] as $key=>$value):
                    $this->db->group_by($value);
                endforeach;
            endif;
        endif;

		if(isset($data['limit'])):
            if(!empty($data['limit'])):
                $this->db->limit($data['limit']);
            endif;
        endif;
		
		$result = $this->db->get($data['tableName'])->row();
 		//print_r($this->db->last_query());
        return $result;
    }

    /* Get Specific Row. Like : SUM,MAX,MIN,COUNT ect... */
    public function specificRow($data){
        if(isset($data['select'])):
            if(!empty($data['select'])):
                $this->db->select($data['select']);
            endif;
        endif;

        if(isset($data['join'])):
            if(!empty($data['join'])):
                foreach($data['join'] as $key=>$value):
                    $this->db->join($key,$value);
                endforeach;
            endif;
        endif;

        if(isset($data['leftJoin'])):
            if(!empty($data['leftJoin'])):
                foreach($data['leftJoin'] as $key=>$value):
                    $this->db->join($key,$value,'left');
                endforeach;
            endif;
        endif;

        if(isset($data['where'])):
            if(!empty($data['where'])):
                foreach($data['where'] as $key=>$value):
                    $this->db->where($key,$value);
                endforeach;
            endif;            
        endif;

        if(isset($data['customWhere'])):
            if(!empty($data['customWhere'])):
                foreach($data['customWhere'] as $value):
                    $this->db->where($value);
                endforeach;
            endif;
        endif;
        
        $this->db->where($data['tableName'].'.is_delete',0);
        if(!empty($data['cm_id'])):
            $this->db->where_in($data['tableName'].'.cm_id',[$data['cm_id'],0]);
        else:
		    if(!empty($this->CMID)){$this->db->where_in($data['tableName'].'.cm_id',[$this->CMID,0]);}
        endif;
        if(isset($data['where_in'])):
            if(!empty($data['where_in'])):
                foreach($data['where_in'] as $key=>$value):
                    $this->db->where_in($key,$value,false);
                endforeach;
            endif;
        endif;

        if(isset($data['where_not_in'])):
            if(!empty($data['where_not_in'])):
                foreach($data['where_not_in'] as $key=>$value):
                    $this->db->where_not_in($key,$value,false);
                endforeach;
            endif;
        endif;

        if(isset($data['like'])):
            if(!empty($data['like'])):
                $i=1;
                $this->db->group_start();
                foreach($data['like'] as $key=>$value):
                    if($i == 1):
                        $this->db->like($key,$value);
                    else:
                        $this->db->or_like($key,$value);
                    endif;
                    $i++;
                endforeach;
                $this->db->group_end();
            endif;
        endif;

        if (isset($data['having'])) :
            if (!empty($data['having'])) :
                foreach ($data['having'] as $value) :
                    $this->db->having($value);
                endforeach;
            endif;
        endif;

        if(isset($data['order_by'])):
            if(!empty($data['order_by'])):
                foreach($data['order_by'] as $key=>$value):
                    $this->db->order_by($key,$value);
                endforeach;
            endif;
        endif;

        if(isset($data['group_by'])):
            if(!empty($data['group_by'])):
                foreach($data['group_by'] as $key=>$value):
                    $this->db->group_by($value);
                endforeach;
            endif;
        endif;
            
        if(isset($data['resultType'])):
            if($data['resultType'] == "numRows")
                return $this->db->get($data['tableName'])->num_rows();            
            if($data['resultType'] == "resultRows")
                return $this->db->get($data['tableName'])->result();
        endif;

        $result =  $this->db->get($data['tableName'])->row();
		// print_r($this->db->last_query());
		return $result;
    }

    /* Save and Update Row */
    public function storeOld($tableName,$data,$msg = "Record"){
        $id = $data['id'];
        unset($data['id']);
        if(empty($id)):
            $data['created_by'] = (isset($data['created_by']))?$data['created_by']:$this->loginId;
            $data['created_at'] = date("Y-m-d H:i:s");
            //print_r($data);exit;
            $this->db->insert($tableName,$data);
            $insert_id = $this->db->insert_id();
            $result = ['status'=>1,'message'=>$msg." saved Successfully.",'insert_id'=>$insert_id,'id'=>$insert_id];
        else:
            unset($data['created_by'],$data['created_at']);
            $data['updated_by'] = $this->loginId;
            $data['updated_at'] = date("Y-m-d H:i:s");
            
            $this->db->where('id',$id);
            $this->db->update($tableName,$data);
            $result = ['status'=>1,'message'=>$msg." updated Successfully.",'insert_id'=>-1,'id'=>$id];
        endif;

        return $result;
    }

    public function store($tableName,$data,$msg = "Record"){
        
        $checkDupli = 0;
        if(isset($data['checkDuplicate']) AND !empty($data['checkDuplicate']))
        {
            $dArr = Array();$firstKey = '';
            $dArr['tableName'] = $tableName;
            if(isset($data['checkDuplicate']['customWhere'])):
                $firstKey =$data['checkDuplicate']['first_key'];
                if(!empty($data['checkDuplicate']['customWhere'])):
                    $dArr['customWhere'][] = $data['checkDuplicate']['customWhere'];
                endif;
            else:
                foreach($data['checkDuplicate'] as $key):
                    if(empty($firstKey)){$firstKey = $key;}
                    $dArr['where'][$tableName.'.'.$key] = $data[$key];
                endforeach;
            endif;
            if(!empty($data['id'])){$dArr['where']['id !='] = $data['id'];}
            if(!empty($data['cm_id'])):
                $this->db->where_in($dArr['tableName'].'.cm_id',[$data['cm_id'],0]);
            else:
                if(!empty($this->CMID)){$this->db->where_in($dArr['tableName'].'.cm_id',[$this->CMID,0]);}
            endif;
            $checkDupli = $this->numRows($dArr);
            unset($data['checkDuplicate']);
        }
        if($checkDupli > 0)
        {
            return ['status'=>0,'message'=>[$firstKey => $msg." is Duplicate."]];
        }
        else
        {
            $id = $data['id'];
            unset($data['id']);
            if(empty($id)):
                $data['created_by'] = (isset($data['created_by']))?$data['created_by']:$this->loginId;
                $data['created_at'] = date("Y-m-d H:i:s");
                $data['cm_id'] = (isset($data['cm_id']))?$data['cm_id']:$this->CMID;
                $this->db->insert($tableName,$data);
                $insert_id = $this->db->insert_id();
                return ['status'=>1,'message'=>$msg." saved Successfully.",'insert_id'=>$insert_id,'id'=>$insert_id];
            else:
                unset($data['created_by'],$data['created_at']);
                $data['updated_by'] = $this->loginId;
                $data['updated_at'] = date("Y-m-d H:i:s");
                $this->db->where('id',$id);
                $this->db->update($tableName,$data);
                return ['status'=>1,'message'=>$msg." updated Successfully.",'insert_id'=>-1,'id'=>$id];
            endif;
        }
    }

    /* Update Row */
    public function edit($tableName,$where,$data,$msg = "Record"){
        $data['updated_by'] = $this->loginId;
        $data['updated_at'] = date("Y-m-d H:i:s");

        if(!empty($where)):
            foreach($where as $key=>$value):
                $this->db->where($key,$value);
            endforeach;
        endif;
        //$where['cm_id'] = (isset($where['cm_id']))?$where['cm_id']:$this->CMID;
        //$this->db->where('cm_id',$where['cm_id']);
        $this->db->update($tableName,$data);
        return ['status'=>1,'message'=>$msg." updated Successfully.",'insert_id'=>-1];
    }

    /* Update Row */
    public function editCustom($tableName,$customWhere,$data,$where=Array()){
        $data['updated_by'] = $this->loginId;
        $data['updated_at'] = date("Y-m-d H:i:s");

        if(!empty($where)):
            foreach($where as $key=>$value):
                $this->db->where($key,$value);
            endforeach;
        endif;

		if(isset($customWhere)):
            if(!empty($customWhere)):
                foreach($customWhere as $value):
                    $this->db->where($value);
                endforeach;
            endif;
        endif;
        $where['cm_id'] = (isset($where['cm_id']))?$where['cm_id']:$this->CMID;
        $this->db->where('cm_id',$where['cm_id']);

        $this->db->update($tableName,$data);
        return ['status'=>1,'message'=>"Record updated Successfully.",'insert_id'=>-1];
    }

    /* Set Deleteed Flage */
    public function trash($tableName,$where,$msg = "Record"){
        $data['updated_by'] = $this->loginId;
        $data['updated_at'] = date("Y-m-d H:i:s");
        $data['is_delete'] = 1;

        if(!empty($where)):
            foreach($where as $key=>$value):
                $this->db->where($key,$value);
            endforeach;
        endif;
        $where['cm_id'] = (isset($where['cm_id']))?$where['cm_id']:$this->CMID;
        $this->db->where('cm_id',$where['cm_id']);

        $this->db->update($tableName,$data);
        return ['status'=>1,'message'=>$msg." deleted Successfully."];
    }

    /* Delete Recored Permanent */
    public function remove($tableName,$where,$msg = ""){
        if(!empty($where)):
            foreach($where as $key=>$value):
                $this->db->where($key,$value);
            endforeach;
        endif;
        $where['cm_id'] = (isset($where['cm_id']))?$where['cm_id']:$this->CMID;
        $this->db->where('cm_id',$where['cm_id']);
        $this->db->delete($tableName);
        return ['status'=>1,'message'=>$msg." deleted Successfully."];
    }  
    
    /* Custom Set OR Update Row */
    public function setValue($data){
		if(!empty($data['where'])):
			if(isset($data['where'])):
				if(!empty($data['where'])):
					foreach($data['where'] as $key=>$value):
						$this->db->where($key,$value);
					endforeach;
				endif;            
			endif;

            if(isset($data['where_in'])):
                if(!empty($data['where_in'])):
                    foreach($data['where_in'] as $key=>$value):
                        $this->db->where_in($key,$value,false);
                    endforeach;
                endif;
            endif;

            if(isset($data['where_not_in'])):
                if(!empty($data['where_not_in'])):
                    foreach($data['where_not_in'] as $key=>$value):
                        $this->db->where_not_in($key,$value,false);
                    endforeach;
                endif;
            endif;

            if(isset($data['order_by'])):
                if(!empty($data['order_by'])):
                    foreach($data['order_by'] as $key=>$value):
                        $this->db->order_by($key,$value);
                    endforeach;
                endif;
            endif;
			
			if(isset($data['set'])):
				if(!empty($data['set'])):
					foreach($data['set'] as $key=>$value):
						$v = explode(',',$value);
						$setVal = "`".$v[0]."` ".$v[1];
						$this->db->set($key, $setVal, FALSE);
					endforeach;
				endif;            
			endif;

            if(isset($data['update'])):
				if(!empty($data['update'])):
					foreach($data['update'] as $key=>$value):
						$this->db->set($key, $value, FALSE);
					endforeach;
				endif;            
			endif;
            $data['cm_id'] = (isset($data['cm_id']))?$data['cm_id']:$this->CMID;
            $this->db->where('cm_id',$data['cm_id']);
            $this->db->update($data['tableName']);
            return ['status'=>1,'message'=>"Record updated Successfully.",'qry'=>$this->db->last_query()];
        endif;
		return ['status'=>0,'message'=>"Record updated Successfully.",'qry'=>"Query not fired"];
    }

	/* Print Executed Query */
    public function printQuery(){  print_r($this->db->last_query());exit; }	

	/* Company Information */
	public function getCompanyInfo(){
		$data['tableName'] = 'company_info';
        $data['select'] = "company_info.*,countries.name as company_country, statutory_detail.state as company_state, statutory_detail.state_code as company_state_code, statutory_detail.state as company_state,statutory_detail.district as company_district,,statutory_detail.taluka as company_taluka,statutory_detail.country_id";
        $data['leftJoin']['statutory_detail'] = "statutory_detail.id = company_info.company_statutory_id";
        $data['leftJoin']['countries'] = "statutory_detail.country_id = countries.id";
       
		$data['where']['company_info.id'] = 1;
		return $this->row($data);
	}

    /* Save Comapny Information */
    public function saveCompanyInfo($postData){
        try{
            $this->db->trans_begin();
            $postData['cm_id'] = $this->CMID;
            $result = $this->store('company_info',$postData,'Company Info');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

    /* 
    *   Created BY : Milan Chauhan
    *   Created AT : 05-05-2023
    *   Required Param : columnName (array)
    *   Not : if check any other condition on particular table and column then post data like this $data['table_condition']['{TABLE NAME}']['{CONDITION TYPE}']['{COLUMN NAME}'] = '{COLUMN VALUE}';
    *       CONDITION TYPE includs where,where_in and where_not_in
    */
    
	public function checkUsage($postData){
        if(!empty($postData['columnName'])):
            $columnName = implode("','",$postData['columnName']);
            $result = $this->db->query("SELECT DISTINCT TABLE_NAME,COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME IN ('$columnName') AND TABLE_SCHEMA='".MASTER_DB."'")->result();
            // print_r($this->db->last_query());exit;
            // print_r($result);exit;
            foreach($result as $row):
                $queryData = array();
                $queryData['tableName'] = $row->TABLE_NAME;
                $queryData['where'][$row->COLUMN_NAME] = $postData['value'];

                if(isset($postData['table_condition']) && !empty($postData['table_condition'])):
                    if(array_key_exists($row->TABLE_NAME, $postData['table_condition'])):

                        if(!empty($postData['table_condition'][$row->TABLE_NAME]['where']) && array_key_exists($row->COLUMN_NAME, $data['table_condition'][$row->TABLE_NAME]['where'])):
                            foreach($postData['table_condition'][$row->TABLE_NAME]['where'][$row->COLUMN_NAME] as $key=>$value):
                                $queryData['where'][$key] = $value;
                            endforeach;
                        endif;

                        if(!empty($postData['table_condition'][$row->TABLE_NAME]['where_in']) && array_key_exists($row->COLUMN_NAME, $postData['table_condition'][$row->TABLE_NAME]['where_in'])):
                            foreach($postData['table_condition'][$row->TABLE_NAME]['where_in'][$row->COLUMN_NAME] as $key=>$value):
                                $queryData['where_in'][$key] = $value;
                            endforeach;
                        endif;

                        if(!empty($postData['table_condition'][$row->TABLE_NAME]['where_not_in']) && array_key_exists($row->COLUMN_NAME, $postData['table_condition'][$row->TABLE_NAME]['where_not_in'])):
                            foreach($postData['table_condition'][$row->TABLE_NAME]['where_not_in'][$row->COLUMN_NAME] as $key=>$value):
                                $queryData['where_not_in'][$key] = $value;
                            endforeach;
                        endif;

                    endif;
                endif;

                $queryData['resultType'] = "numRows";
                $res = $this->specificRow($queryData);

                if($res > 0): break; endif;
            endforeach;
            //print_r($res);exit;
            if($res > 0): return true; endif;
        endif;
        return false;
    }
    
    /* Save Location Log in */
    public function saveLocationLog($postData){
        try{
            $this->db->trans_begin();
						
            $postData['id'] = '';
            $postData['created_by'] = $this->loginId;
            $postData['created_at'] = date("Y-m-d H:i:s");
            $postData['cm_id'] = (isset($postData['cm_id']))?$postData['cm_id']:$this->CMID;
            $result = $this->store('location_log',$postData,'Location Log');
			
			$this->calcDistance($postData);

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }
	
	/* Trash Location Log in */
    public function trashLocationLog($postData){
        try{
            $this->db->trans_begin();
			
			$queryData['tableName'] = 'location_log'; 
			$queryData['select'] = 'id, log_time, emp_id';
			$queryData['where']['ref_id'] = $postData['id'];
			$queryData['where_in']['log_type'] = $postData['log_type'];
			$logData = $this->row($queryData);
			
			$result = array();
			if(!empty($logData->id)){
				
				$result = $this->trash('location_log',['id'=>$logData->id],'Location Log');
				
				$sendData = [];
				$sendData['emp_id'] = $logData->emp_id;
				$sendData['log_time'] = $logData->log_time;
				
				$this->calcDistance($sendData);
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
	
	/* Update Travel Distance */
	public function calcDistance($postData){
		try{
			$queryData['tableName'] = 'location_log'; 
			$queryData['select'] = 'id, log_time, location';
			$queryData['where']['DATE(log_time)'] = date('Y-m-d', strtotime($postData['log_time']));
			$queryData['where']['emp_id'] = $postData['emp_id'];
			$queryData['customeWhere'][] = "(location IS NOT NULL OR location != '')";
			$queryData['order_by']['log_time'] = 'DESC';
			$locationLogData = $this->rows($queryData);
			
			$i=0; $prev_location='';
			
			foreach($locationLogData as $row):
			
				$travel_km = 0;
			
				if(!empty($prev_location)){
					$prev_location = $row->location;
					
					if($i>0){ $travel_km = getDistanceOpt($row->location,$prev_location); }
				}
				
				$updateData['travel_km'] = $travel_km;
				$updateData['updated_by'] = $this->loginId;
				$updateData['updated_at'] = date("Y-m-d H:i:s");
				
				$result = $this->edit('location_log',['id'=>$row->id],$updateData,'Update Distance');
			endforeach;
			
		
		    if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
	}

    /* Get Numbers of Rows */
    public function numRows($data){		
		$this->db->reset_query();
		$this->mapConditions($data);

        if(!empty($data['cm_id'])):
            $this->db->where_in($data['tableName'].'.cm_id',[$data['cm_id'],0]);
        else:
            if(!empty($this->CMID)){$this->db->where_in($data['tableName'].'.cm_id',[$this->CMID,0]);}
        endif;
		$result = $this->db->get($data['tableName'])->num_rows();
        //$this->printQuery();
        return $result;
    }
    
	/***** CONDITIONS MAPPING FUNCTION : created by : JP@11.04.2024 ****/
	public function mapConditions1($data){
		
		if(isset($data['select'])):
            if(!empty($data['select'])):
                $this->db->select($data['select']);
            endif;
        endif;

        if(isset($data['join'])):
            if(!empty($data['join'])):
                foreach($data['join'] as $key=>$value):
                    $this->db->join($key,$value);
                endforeach;
            endif;
        endif;

        if(isset($data['leftJoin'])):
            if(!empty($data['leftJoin'])):
                foreach($data['leftJoin'] as $key=>$value):
                    $this->db->join($key,$value,'left');
                endforeach;
            endif;
        endif;

        if(isset($data['where'])):
            if(!empty($data['where'])):
                foreach($data['where'] as $key=>$value):
                    $this->db->where($key,$value);
                endforeach;
            endif;            
        endif;
        
        if(isset($data['whereFalse'])):
            if(!empty($data['whereFalse'])):
                foreach($data['whereFalse'] as $key=>$value):
                    $this->db->where($key,$value,false); 
                endforeach;
            endif;            
        endif;
        
        if(isset($data['customWhere'])):
            if(!empty($data['customWhere'])):
                foreach($data['customWhere'] as $value):
                    $this->db->where($value);
                endforeach;
            endif;
        endif;

        if(isset($data['where_in'])):
            if(!empty($data['where_in'])):
                foreach($data['where_in'] as $key=>$value):
                    $this->db->where_in($key,$value,false);
                endforeach;
            endif;
        endif;

        if(isset($data['where_not_in'])):
            if(!empty($data['where_not_in'])):
                foreach($data['where_not_in'] as $key=>$value):
                    $this->db->where_not_in($key,$value,false);
                endforeach;
            endif;
        endif;

        if (isset($data['having'])) :
			if (!empty($data['having'])) :
				foreach ($data['having'] as $value) :
					$this->db->having($value);
				endforeach;
			endif;
		endif;

        if(isset($data['like'])):
            if(!empty($data['like'])):
                $i=1;
                $this->db->group_start();
                foreach($data['like'] as $key=>$value):
                    if($i == 1):
                        $this->db->like($key,$value,'both',false);
                    else:
                        $this->db->or_like($key,$value,'both',false);
                    endif;
                    $i++;
                endforeach;
                $this->db->group_end();
            endif;
        endif;

        if(isset($data['columnSearch'])):
            if(!empty($data['columnSearch'])):                
                $this->db->group_start();
                foreach($data['columnSearch'] as $key=>$value):
                    $this->db->like($key,$value);
                endforeach;
                $this->db->group_end();
            endif;
        endif;

        if(isset($data['order_by'])):
            if(!empty($data['order_by'])):
                foreach($data['order_by'] as $key=>$value):
                    $this->db->order_by($key,$value);
                endforeach;
            endif;
        endif;

        if(isset($data['order_by_field'])):
            if(!empty($data['order_by_field'])):
                foreach($data['order_by_field'] as $key=>$value):
                    $this->db->order_by("FIELD(".$key.", ".implode(",",$value).")", '', false);
                endforeach;
            endif;
        endif;

        if(isset($data['group_by'])):
            if(!empty($data['group_by'])):
                foreach($data['group_by'] as $key=>$value):
                    $this->db->group_by($value);
                endforeach;
            endif;
        endif;

		if(isset($data['limit'])):
            if(!empty($data['limit'])):
                $this->db->limit($data['limit']);
            endif;
        endif;

        if(isset($data['start']) && isset($data['length'])):
            if(!empty($data['length'])):
                $this->db->limit($data['length'],$data['start']);
            endif;
        endif;
		
		return true;
	}
}
?>