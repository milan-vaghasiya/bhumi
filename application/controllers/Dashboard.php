<?php
class Dashboard extends MY_Controller{

	public function __construct()	{
		parent::__construct();
		$this->isLoggedin();
		$this->data['headData']->pageTitle = "Dashboard";
		$this->data['headData']->controller = "dashboard";
	}
	
	public function index(){
		//$this->usersModel->confirmAttendance(); 
		$widgetPermission = $this->permission->getEditDashPermission($this->loginId);
		$this->data['widgetPermission'] =$widget_class  =!empty($widgetPermission)?array_column($widgetPermission,'sys_class'):[];

		$this->data['empList'] = $this->usersModel->getEmployeeList();
		$this->data['totalPresent'] = $this->usersModel->getTodayPresentData();
		$this->data['totalLeave'] = $this->leave->getTodayLeaveData();
	    $this->data['appointmentIcon'] = $this->appointmentIcon;
		$this->data['pLeaveList'] = $this->leave->getLeaveList(['status'=>1,'limit'=>20]);
		$this->data['pAtdList'] = $this->usersModel->getAttendanceData(['approve_by'=>1,'limit'=>20]);
		$this->data['pExpList'] = $this->expense->getExpenseData(['approve_by'=>1,'limit'=>20]);

		$this->data['empChartData'] = $this->usersModel->getEmpForChart();
		$this->data['pChartData'] = $this->usersModel->getAttandanceForChart();
		
        $this->load->view('dashboard',$this->data);
    }
	

    public function loadAnnouncements(){
		$noticeList = $this->dashboard->getNoticeBoardData();
		$announcement = [];
		if(!empty($noticeList))
		{
			foreach($noticeList as $row)
			{
				$annItem = Array();
				$annItem['date'] = '';
				$annItem['prefix'] = (!empty($row->from_date) ? formatDate($row->from_date,'j M Y') : '').' To '.(!empty($row->to_date) ? formatDate($row->to_date,'j M Y') : '');
				$annItem['heading'] = (!empty($row->title) ? $row->title : '');
				$annItem['url'] = '';
				
				$announcement[] = $annItem;
			}
		}

        $this->printJson(['status'=>1,'message'=>'Data Found.','announcement'=>$announcement]);
    }
}
?>