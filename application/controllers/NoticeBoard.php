<?php
class NoticeBoard extends MY_Controller{
    private $index = "notice_board/index";
    private $form = "notice_board/form";

    public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Notice Board";
		$this->data['headData']->controller = "noticeBoard";
        $this->data['headData']->pageUrl = "noticeBoard";
	}
	
	public function index(){
        $this->data['tableHeader'] = getConfigDtHeader($this->data['headData']->controller);
        $this->load->view($this->index,$this->data);
    }
	
    public function getDTRows(){
        $data = $this->input->post();
        $result = $this->configuration->getNoticeBoardDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):          
            $row->sr_no = $i++;         
            $sendData[] = getNoticeBoardData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addNoticeBoard(){
		$data = $this->input->post();
        $this->load->view($this->form, $this->data);
    }

    public function save(){
        $data = $this->input->post();
		$errorMessage = array();

        if(empty($data['title'])){
            $errorMessage['title'] = "Title is required.";
        }
        if(empty($data['from_date'])){
            $errorMessage['from_date'] = "From date is required.";
        }
        if(empty($data['to_date'])){
            $errorMessage['to_date'] = "To Date is required.";
        }
        if(empty($data['description'])){
            $errorMessage['description'] = "Description is required.";
        }
		if($data['from_date'] > $data['to_date']){ $errorMessage['to_date'] = "Invalid Date"; }
		

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
			$this->printJson($this->configuration->saveNoticeBoard($data));
        endif;
    }

    public function edit(){     
        $data = $this->input->post();
        $this->data['dataRow'] = $this->configuration->getNoticeBoard($data);
        $this->load->view($this->form, $this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->configuration->trash('notice_board',['id'=>$id]));
        endif;
    }
}
?>