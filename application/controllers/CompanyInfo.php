<?php
class CompanyInfo extends MY_Controller{
    private $indexPage = "company_info";
    
	public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Company Info";
		$this->data['headData']->controller = "companyInfo";
        $this->data['headData']->pageUrl = "companyInfo";
	}
	
	public function index(){
        $this->data['dataRow'] = $this->masterModel->getCompanyInfo();
        $this->data['countryData'] = $this->configuration->getCountries();
        $this->load->view($this->indexPage,$this->data);
    }

    public function save(){
        $data = $this->input->post();	
        $errorMessage = array();
    
        if(empty($data['company_name']))
            $errorMessage['company_name'] = "Company Name is required.";

        if(empty($data['company_email']))
            $errorMessage['company_email'] = "Company Email is required.";

        if(empty($data['company_contact']))
            $errorMessage['company_contact'] = "Contact No. is required.";

        if(empty($data['company_statutory_id']))
            $errorMessage['company_statutory_id'] = "Taluka Name is required.";

        if(empty($data['company_address']))
            $errorMessage['company_address'] = "Address is required.";
            
        if(empty($data['company_pincode']))
            $errorMessage['company_pincode'] = "Pincode is required.";
       
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            if($_FILES['company_logo']['name'] != null || !empty($_FILES['company_logo']['name'])):
                $this->load->library('upload');
                $_FILES['userfile']['name']     = $_FILES['company_logo']['name'];
                $_FILES['userfile']['type']     = $_FILES['company_logo']['type'];
                $_FILES['userfile']['tmp_name'] = $_FILES['company_logo']['tmp_name'];
                $_FILES['userfile']['error']    = $_FILES['company_logo']['error'];
                $_FILES['userfile']['size']     = $_FILES['company_logo']['size'];

                $imagePath = realpath(APPPATH . '../assets/images/');
                $config = ['file_name' => "logo",'allowed_types' => '*','max_size' => 10240,'overwrite' => FALSE, 'upload_path'	=>$imagePath];

                $this->upload->initialize($config);
                if (!$this->upload->do_upload()):
                    $errorMessage['item_image'] = $this->upload->display_errors();
                    $this->printJson(["status"=>0,"message"=>$errorMessage]);
                else:
                    $uploadData = $this->upload->data();
                    $data['company_logo'] = $uploadData['file_name'];
                endif;
            else:
                unset($data['company_logo']);
            endif;

            if($_FILES['company_letterhead']['name'] != null || !empty($_FILES['company_letterhead']['name'])):
                $this->load->library('upload');
                $_FILES['userfile']['name']     = $_FILES['company_letterhead']['name'];
                $_FILES['userfile']['type']     = $_FILES['company_letterhead']['type'];
                $_FILES['userfile']['tmp_name'] = $_FILES['company_letterhead']['tmp_name'];
                $_FILES['userfile']['error']    = $_FILES['company_letterhead']['error'];
                $_FILES['userfile']['size']     = $_FILES['company_letterhead']['size'];

                $imagePath = realpath(APPPATH . '../assets/images/');
                $config = ['file_name' => "letterhead",'allowed_types' => '*','max_size' => 10240,'overwrite' => FALSE, 'upload_path'	=>$imagePath];

                $this->upload->initialize($config);
                if (!$this->upload->do_upload()):
                    $errorMessage['item_image'] = $this->upload->display_errors();
                    $this->printJson(["status"=>0,"message"=>$errorMessage]);
                else:
                    $uploadData = $this->upload->data();
                    $data['company_letterhead'] = $uploadData['file_name'];
                endif;
            else:
                unset($data['company_letterhead']);
            endif;

            $data['created_by'] = $this->loginId;
            $cLogo = $this->masterModel->saveCompanyInfo($data);
            $this->printJson(['status'=>1,'tcReportData'=>$cLogo]);
        endif;
    }
}
?>