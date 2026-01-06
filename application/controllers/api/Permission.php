<?php
class Permission extends MY_ApiController{
    public function __construct(){
		parent::__construct();
	}

    public function getUserPermission(){
        $this->data['userPermission'] = $this->permission->getEmployeeAppMenuList();
        $this->printJson(['status'=>1,'message'=>"Data Found",'data'=>$this->data]);
    }
}
?>