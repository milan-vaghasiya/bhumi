<?php
class HeadQuater extends MY_ApiController{	
	public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "HQ Change Request";
		$this->data['headData']->pageUrl = "api/headQuater";
        $this->data['headData']->base_url = base_url();
	}

    public function getRequestList(){
        $data = $this->input->post();
        $hqReqList = $this->configuration->getNewHeadQuarterList($data);

        // Columns to keep
        $columnsToKeep = ['id',  'emp_code', 'emp_name', 'department_name', 'designation_name', 'hq_name', 'new_hq_name', 'notes', 'status', 'sts_label'];

        // Retain only specific columns in the result
        $hqReqList = array_map(function($row) use ($columnsToKeep) {
			$row->sts_label = "Pending";
			if(!empty($row->status))
			{
				$row->sts_label = (($row->status == 1) ? "Approved" : (($row->status == 2) ? "Rejected" : "Cancelled"));
			}
            return array_intersect_key((array) $row, array_flip($columnsToKeep));
        }, $hqReqList);

        $this->printJson(['status'=>1,'data'=>['dataList'=>$hqReqList]]);
    }

    public function getHeadQuaterList(){
        $headQuarterList = $this->configuration->getHeadQuarterList(); 

        // Columns to keep
        $columnsToKeep = ['id',  'name'];

        // Retain only specific columns in the result
        $headQuarterList = array_map(function($row) use ($columnsToKeep) {
            return array_intersect_key((array) $row, array_flip($columnsToKeep));
        }, $headQuarterList);
        $this->data['headQuarterList'] = $headQuarterList; 
        $this->printJson(['status'=>1,'message'=>'Data Found.','data'=>$this->data]);
    }

    public function saveNewHeadQuarter(){
        $data = $this->input->post();
        $errorMessage = array();

        if(empty($data['new_hq_id']))
            $errorMessage['new_hq_id'] = "New Head Quarter is required.";
       
      
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->configuration->saveNewHeadQuarter($data));
        endif;
    }

    public function changeHqRequestStatus(){
        $data = $this->input->post(); 
        if (empty($data['id'])) :
            $this->printJson(['status' => 0, 'message' => 'Somthing went wrong...Please try again.']);
        else :
            $this->printJson($this->configuration->changeHqRequest($data));
        endif;
    }

}
?>