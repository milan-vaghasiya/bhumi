<?php
class Dashboard extends MY_ApiController{

	public function __construct(){
		parent::__construct();
        $this->data['headData']->pageTitle = "Dashboard";
        $this->data['headData']->pageUrl = "api/dashboard";
        $this->data['headData']->base_url = base_url();
        $this->title_bg = ['#49919A','#5190C3','#70A796','#A99761','#B88B8B','#828DA9','#6E988A','#AF7E55','#7B7CB4','#67A096'];
        $this->content_bg = ['#ACDEE4','#A9CCE8','#BDE7DA','#FAEFCE','#FAE9E9','#E1E4EC','#C1D8D0','#F8D5B8','#C3C4F0','#EFFBF9'];
	}

    public function index(){
		$bannerPath = base_url('assets/uploads/app_banner/');
		$bannerDir = realpath(APPPATH . '../assets/uploads/app_banner/');
        $banner_files = scandir($bannerDir);
        $this->data['baners'] = Array();
        foreach ($banner_files as $file) {
            if ($file !== '.' && $file !== '..') {
                $this->data['baners'][] = base_url('assets/uploads/app_banner/'.$file);
            }
        }	
		$this->data['lastAutoPunchId'] = 0;
		$this->data['lastAutoPunch'] = "";
		
		$autoPunch = New StdClass;
		$autoPunch = $this->usersModel->checkLastAutoPunch(['emp_id'=>$this->loginId]);
		
		$this->data['lastAutoPunchId'] = (!empty($autoPunch->id) ? $autoPunch->id : 0);
		$this->data['lastAutoPunch'] = (!empty($autoPunch->punch_date) ? formatDate($autoPunch->punch_date,'j M Y H:i A') : '');
		
		$punchCount = $this->usersModel->getDatewiseAttendanceSummary(['from_date'=>date('Y-m-01'),'to_date'=>date('Y-m-t'),'emp_id'=>$this->loginId]);
		$this->data['present'] = (!empty($punchCount->present_days) ? $punchCount->present_days : 0);//	.'/'.intval(date('d'));
		
		$visitCount = $this->visit->getVisitCount(['from_date'=>date('Y-m-01'),'to_date'=>date('Y-m-t'),'emp_id'=>$this->loginId]);
		$this->data['visits'] = (!empty($visitCount) ? $visitCount : 0);
		
		$expCount = $this->expense->getExpCount(['from_date'=>date('Y-m-01'),'to_date'=>date('Y-m-t'),'emp_id'=>$this->loginId]);
		$this->data['approved_Expense'] = (!empty($expCount->approved_exp) ? $expCount->approved_exp : 0);
		$this->data['unapproved_Expense'] = (!empty($expCount->unapproved_exp) ? $expCount->unapproved_exp : 0);
		
		$noticeList = $this->dashboard->getNoticeBoardData();
		
		if(!empty($noticeList))
		{
			$clr = 0;
			foreach ($noticeList as $row) {
				$row->from_date = formatDate($row->from_date,'j M Y');
				$row->to_date = formatDate($row->to_date,'j M Y');
				$row->title_bg = $this->title_bg[$clr];
				$row->content_bg = $this->content_bg[$clr];
				$clr++;
			}
		}
		$this->data['noticeList'] = $noticeList;

        $this->printJson(['status'=>1,'message'=>'Data Found.','data'=>$this->data]);
    }

    public function getReminderData(){
		$this->data['reminderList'] = $this->sales->getReminders(['status'=>1,'executive_id'=>(!in_array($this->userRole,[1,-1])?$this->loginId:'')]);

        $this->printJson(['status'=>1,'message'=>'Data Found.','data'=>$this->data]);
    }
}
?>