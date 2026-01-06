<?php
class Expense extends MY_Controller{
    private $indexPage = "expense/index";
    private $form = "expense/form";
    private $approve_form = "expense/approve_form";
    private $download_file = "expense/download_file";
    private $emp_wise_exp_report = "expense/emp_wise_exp_report"; 
    private $expense_register = "expense/expense_register";
	private $month_wise_exp_report = "expense/month_wise_exp_report";
    private $view_expense_detail = "expense/view_expense_detail";
    private $vehicle_exp = "expense/vehicle_exp"; //27-09-25
    private $vehicle_exp_approve = "expense/vehicle_exp_approve"; //27-09-25

	public function __construct(){
		parent::__construct();
		$this->isLoggedin();
		$this->data['headData']->controller = "expense";
		$this->data['headData']->pageUrl = "expense";
	}
	
	public function index(){
		$this->data['headData']->pageTitle = "Expense"; //27-09-25
        $this->data['tableHeader'] = getMasterDtHeader($this->data['headData']->controller);
        $this->data['customerData'] = $this->party->getPartyList(['party_type'=>1]);
        $this->data['empData'] = $this->usersModel->getEmployeeList();
        $this->load->view($this->indexPage,$this->data);
    }

    public function getDTRows($status=0,$party_id=0,$emp_id=0,$from_date="",$to_date=""){
        $data = $this->input->post(); $data['status'] = $status;
        $data['emp_id'] = $emp_id; $data['from_date'] = $from_date; $data['to_date'] = $to_date;
        $result = $this->expense->getExpenseDTRows($data);
        $sendData = array();$i=($data['start'] + 1);
        foreach($result['data'] as $row):
            $row->sr_no = $i++;
            $sendData[] = getExpenseData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addExpense(){	
        $this->data['exp_prefix'] = "EXP".n2y(date('Y')).n2m(date('m'));  
        $this->data['exp_no'] = $this->expense->getNextExpNo();
        $this->data['expTypeList'] = $this->configuration->getSelectOptionList(['type'=>3,'calc_type'=>'MANUAL']);
        $this->data['empList'] = $this->usersModel->getEmployeeList();	
        $this->data['custList'] = $this->party->getPartyList(['party_type'=>1]);	
		$this->load->view($this->form, $this->data);
    }

	public function save(){
        $data = $this->input->post();
        $errorMessage = array();
		$edValdMsg = ['ABSENT'=>'Employee is Absent', 'LEAVE'=>'Employee is on Leave'];

        if(empty($data['exp_number'])){
            $errorMessage['exp_number'] = "Expense Number is required.";
        }  
        if(empty($data['exp_date']))
		{
            $errorMessage['exp_date'] = "Expense date is required.";
        }
		else
		{
			$ed_validation = $this->expense->hasValidExpDate($data);
			if(!empty($ed_validation) AND $ed_validation->expense_status != "OK"){$errorMessage['exp_date'] = $edValdMsg[$ed_validation->expense_status];}
		}
        if(empty($data['exp_by_id'])){
            $errorMessage['exp_by_id'] = "Employee is required.";
        }

        $is_valid = 0;
        foreach ($data['expTrans'] as $row) {
            if(!empty($row)){ $is_valid = 1; }
        }
        if(empty($is_valid)){
            $errorMessage['exp_type_data'] = "Expense type is required.";
        }
      
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
			if(!empty($_FILES['proof_file']['name'][0])):
                foreach ($_FILES['proof_file']['name'] as $key => $value) {
                    if($value != null || !empty($value)):
                        $this->load->library('upload');
                        $_FILES['userfile']['name']     = $value;
                        $_FILES['userfile']['type']     = $_FILES['proof_file']['type'][$key];
                        $_FILES['userfile']['tmp_name'] = $_FILES['proof_file']['tmp_name'][$key];
                        $_FILES['userfile']['error']    = $_FILES['proof_file']['error'][$key];
                        $_FILES['userfile']['size']     = $_FILES['proof_file']['size'][$key];
                        
                        $imagePath = realpath(APPPATH . '../assets/uploads/expense/');
                        $config = ['file_name' => time()."_order_item_".$_FILES['userfile']['name'][$key]."_".$key, 'allowed_types' => '*','max_size' => 10240,'overwrite' => FALSE, 'upload_path'  =>$imagePath];
        
                        $this->upload->initialize($config);
                        if (!$this->upload->do_upload()):
                            $errorMessage['proof_file'] = $this->upload->display_errors();
                            $this->printJson(["status"=>0,"message"=>$errorMessage]);
                        else:
                            $uploadData = $this->upload->data();
                            $data['proof_file'][] = $uploadData['file_name'];
                        endif;
                    endif;
                }
                $data['proof_file'] = implode(',', $data['proof_file']);
            endif;
			$this->printJson($this->expense->saveExpense($data));
        endif;
    }
	
    public function edit(){
        $data = $this->input->post(); 
        $this->data['dataRow'] = $dataRow = $this->expense->getExpense($data);
        $this->data['expTransData'] = $this->expense->getExpenseTransData(['exp_id' => $dataRow->id]);
        $this->data['expTypeList'] = $this->configuration->getSelectOptionList(['type'=>3,'calc_type'=>'MANUAL']);
        $this->data['empList'] = $this->usersModel->getEmployeeList();
   
		$empData = $this->usersModel->getEmployeeList();
		$options = "";
		if(!empty($empData)){
			foreach($empData as $row){
				$selected = (!empty($dataRow->exp_by_id) && $dataRow->exp_by_id == $row->id) ? "selected" : "";
				$options .= '<option value="'.$row->id.'" '.$selected.'>'.$row->emp_name.'</option>';
			}
		}
		
        $this->data['options'] = $options;
        $this->load->view($this->form,$this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->expense->trash('expense_trans',['exp_id'=>$id]);
            $this->printJson($this->expense->trash('expense_manager',['id'=>$id]));
        endif;
    }
    
    public function getApprovedData(){
        $data = $this->input->post();
        $this->data['dataRow'] = $dataRow = $this->expense->getExpense($data);
        $this->data['expTransData'] = $this->expense->getExpenseTransData(['exp_id' => $dataRow->id,'type'=>3]);
      
		$empData = $this->usersModel->getEmployeeList();
		$options = "";
		if(!empty($empData)){
			foreach($empData as $row){
				$selected = (!empty($dataRow->exp_by_id) && $dataRow->exp_by_id == $row->id) ? "selected" : "";
				$options .= '<option value="'.$row->id.'" '.$selected.'>'.$row->emp_name.'</option>';
			}
		}
        $this->data['options'] = $options;
        $this->load->view($this->approve_form,$this->data);
    }

    public function saveApprovedData(){
        $data = $this->input->post();
        $errorMessage = array();
        $i=1;
        foreach($data['expTrans'] as $row) {
            if(($row['approve_amount'] <= 0) && !empty($row['id']) && empty($row['approve_remark'])){ 
                $errorMessage['approve_remark'.$i] = "Approve Remark is required.";
            }
            $i++;
        }
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else: 
            if(empty($data['id'])):
                $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
            else:
                $this->printJson($this->expense->saveApprovedData($data));
            endif;
        endif;
    }

    /* View Expense Details */
    public function viewExpenseDetails(){
        $data = $this->input->post();
        $this->data['expenseData'] = $this->expense->getExpenseTransData(['exp_id' => $data['id']]);
        $this->load->view($this->view_expense_detail,$this->data);        
    }

    public function getExpenseByOptions(){
        $data = $this->input->post();
      
		$empData = $this->usersModel->getEmployeeList();
		$options = "";
		if(!empty($empData)){
			foreach($empData as $row){
				$options .= '<option value="'.$row->id.'">'.$row->emp_name.'</option>';
			}
		}
		
        $this->printJson(['status'=>1, 'options'=>$options]);
    }

    public function rejectExpense() {
        $data = $this->input->post();
        if(empty($data['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->expense->changeExpenseStatus($data));
        endif;
    }

    public function printData($exp_id=0,$jsonData="") {

        $postData = array();
        if(empty($exp_id)){
            $postData = (Array) decodeURL($jsonData);
        } else {
            $postData = array('exp_id'=>$exp_id);
        }

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->data['dataRow'] = $dataRow = $this->expense->getExpenseData($postData);
            $this->data['companyData'] = $this->masterModel->getCompanyInfo();
            $emp_id = (!empty($postData['emp_id'])) ? $postData['emp_id'] : $dataRow[0]->exp_by_id ;
            $this->data['empData'] = $this->usersModel->getEmployee(['id'=>$emp_id]);
            $this->data['expTypeList'] = $expTypeList = $this->expense->getExpenseTypeList();

            $tableData=''; $typeList=''; $lastRowSum=''; $grandTotal=0;

            $width = 30/count($expTypeList);
            foreach ($expTypeList as $row) {
                $typeList .= '<th style="width:'.$width.'%">'.$row->label.'</th>';
            }

            $tableData .= "<tr>
                            <th style='width:7%'>Exp No.</th>
                            <th style='width:7%'>FROM DATE</th>
                            <th style='width:7%'>TO DATE</th>
                            <th style='width:20%'>DESCRIPTION</th>
                            <th style='width:12%'>BILL BOUCHER NO.</th>
                            <th style='width:10%'>TICKET RS.</th>
                            ".$typeList."
                            <th style='width:7%'>TOTAL RS.</th>
                        </tr>";

            if(!empty($dataRow)){

                $type_sum = [];
                foreach ($dataRow as $row){

                    $amountData=''; $rowSum=0;
                    $expTransData = $this->expense->getExpenseTransData(['exp_id'=>$row->id]);
                    $typeIds = array_column($expTransData, 'exp_type_id');
                    $typeAmt = array_column($expTransData, 'amount');

                    $i=1;
                    foreach ($expTypeList as $typeRow) {
                        if(in_array($typeRow->id, $typeIds)){
                            $amt = $typeAmt[array_search($typeRow->id, $typeIds)];
                            $amountData .= "<td class='text-center'>".floatval($amt)."</td>";
                        } else {
                            $amt = 0;
                            $amountData .= "<td class='text-center'> ".$amt." </td>";
                        }
                        $rowSum += $amt;
                        $type_sum['type_'.$i][] = $amt;
                        $i++;
                    }

                    $tableData .= "<tr>
                                    <td class='text-center'>".$row->exp_number."</td>
                                    <td class='text-center'>".$row->exp_from_date."</td>
                                    <td class='text-center'>".$row->exp_to_date."</td>
                                    <td class='text-center'>".$row->notes."</td>
                                    <td class='text-center'>".$row->voucher_no."</td>
                                    <td class='text-center'>".$row->ticket_no."</td>
                                    ".$amountData."
                                    <th>".floatval($rowSum)."</th>
                                </tr>";
                }

                for($i=1;$i <= count($type_sum);$i++) {
                    $colSum = array_sum($type_sum['type_'.$i]);
                    $lastRowSum .= "<th class='text-center'>".$colSum."</th>";
                    $grandTotal += $colSum;
                }

                $tableData .= "<tr>
                                <th colspan='6'>Grand Total</th>
                                ".$lastRowSum."
                                <th class='text-center'>".$grandTotal."</th>
                            </tr>";
            } else {
                $tableData .= "<tr><td colspan='".(7 + count($expTypeList))."' class='text-center'> No Data </td></tr>";
            }
        
            $this->data['tableData'] = $tableData;
            $pdfData = $this->load->view('expense/print', $this->data, true);


            $mpdf = new \Mpdf\Mpdf();
            $pdfFileName = str_replace(["/","-"],"_",$this->data['empData']->emp_name) .'_'. date('Ymdhis') . '.pdf';
            $stylesheet = file_get_contents(base_url('assets/css/pdf_style.css'));
            $mpdf->WriteHTML($stylesheet, 1);
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->AddPage('L','','','','',5,5,5,15,5,5,'','','','','','','','','','A4-L');
            $mpdf->WriteHTML($pdfData);
            $mpdf->Output($pdfFileName, 'I');
        endif;
    }

	public function downloadFiles() {
        $data = $this->input->post();
        $this->data['dataRow'] = $dataRow = $this->expense->getExpense($data);
        $this->load->view($this->download_file,$this->data);
    }

    /* Employee Wise Expense Report */
    public function empWiseExpenseReport(){
        $this->data['empData'] = $this->usersModel->getEmployeeList();
        $this->load->view($this->emp_wise_exp_report,$this->data);
    }

    public function getExpenseReportData($jsonData=''){
        if(!empty($jsonData)){$postData = (Array) decodeURL($jsonData);}
        else{$postData = $this->input->post();}
        $postData['status'] = '0,1';
        $expData = $this->expense->getExpenseData($postData);

        $i=1; $tbody="";
        foreach($expData as $row):
            $tbody .= '<tr class="text-center">
                <td>'.$i++.'</td>
                <td>'.date("d-m-Y h:i:s A",strtotime($row->exp_date)).'</td>
                <td>'.$row->exp_number.'</td>
                <td>'.$row->expense_label.'</td>
                <td>'.$row->emp_name.'</td>
				<td>'.floatval($row->amount).'</td>
                <td>'.$row->notes.'</td>
            </tr>';
        endforeach;     
     
        $reportTitle = 'Employee Wise Expense Report';
        $report_date = date('d-m-Y',strtotime($postData['from_date'])).' to '.date('d-m-Y',strtotime($postData['to_date']));
        $thead = '<tr class="text-center">
                    <th style="min-width:25px;">#</th>
                    <th style="min-width:25px;">Expense Date</th>
                    <th style="min-width:25px;">Expense No.</th>
                    <th style="min-width:25px;">Expense Type</th>
                    <th style="min-width:100px;">Employee Name</th>
                    <th style="min-width:100px;">Amount</th>
                    <th style="min-width:100px;">Notes</th>
                </tr>';

        $pdfData = '<table id="commanTable" class="table table-bordered itemList" repeat_header="1">
                            <thead class="thead-info" id="theadData">'.$thead.'</thead>
                            <tbody id="receivableData">'.$tbody.'</tbody>
                        </table>';
        $htmlHeader = '<table class="table" style="border-bottom:1px solid #036aae;">
                        <tr>
                            <td class="text-uppercase text-left" style="font-size:1rem;width:30%"></td>
                            <td class="org_title text-uppercase text-center" style="font-size:1.3rem;width:40%">'.$reportTitle.'</td>
                            <td class="text-uppercase text-right" style="font-size:1rem;width:30%">Date : '.$report_date.'</td>
                        </tr>
                    </table>';
        $htmlFooter = '<table class="table top-table" style="margin-top:10px;border-top:1px solid #545454;">
                    <tr>
                        <td style="width:50%;font-size:12px;">Printed On ' . date('d-m-Y') . '</td>
                        <td style="width:50%;text-align:right;font-size:12px;">Page No. {PAGENO}/{nbpg}</td>
                    </tr>
                </table>';
			
        if(!empty($postData['file_type'] == 'PDF'))
        {
            $mpdf = new \Mpdf\Mpdf();
            $pdfFileName = 'ExpenseReport_'.str_replace(["/","-"],"_",date('d-m-Y')).'.pdf';          
            $stylesheet = file_get_contents(base_url('assets/css/pdf_style.css'));
            $mpdf->WriteHTML($stylesheet, 1);
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->SetTitle($reportTitle);
            $mpdf->SetHTMLHeader($htmlHeader);
            $mpdf->SetHTMLFooter($htmlFooter);
            $mpdf->AddPage('L','','','','',5,5,15,10,3,3,'','','','','','','','','','A4-L');
            $mpdf->WriteHTML($pdfData);	
            ob_clean();	
            $mpdf->Output($pdfFileName, 'I');	
        }
        else { $this->printJson(['status'=>1, 'tbody'=>$tbody]); }
    } 

    /* Expense Register Report */
    public function expenseRegister(){
        $this->data['empData'] = $this->usersModel->getEmployeeList();
        $this->load->view($this->expense_register,$this->data);
    }

	public function getExpenseRegisterData($jsonData=''){
        if(!empty($jsonData)){$postData = (Array) decodeURL($jsonData);}
        else{$postData = $this->input->post();}
        $expType = $this->configuration->getSelectOptionList(['type'=>3]);   
        $expData = $this->expense->getExpenseForReport($postData);
        $companyData = $this->masterModel->getCompanyInfo();
        $empData = $this->usersModel->getEmployee(['id'=>$postData['emp_id']]);

		$groupedResult = array_reduce($expData, function($expenses, $row) {
            if(!isset($expenses[date('d', strtotime($row->exp_date)).'#'.$row->exp_type_id])):
                $row->{date('d', strtotime($row->exp_date)).'#'.$row->exp_type_id} = $row->approve_amount;

                $expenses[date('d', strtotime($row->exp_date)).'#'.$row->exp_type_id] = $row;
            else:
                $expenses[date('d', strtotime($row->exp_date)).'#'.$row->exp_type_id]->{date('d', strtotime($row->exp_date)).'#'.$row->exp_type_id} = $row->approve_amount;
            endif;
            
            return $expenses;
        },); 
		
        $lastDay = intVal(date('t-m-Y',strtotime($postData['month']))); 
        if(!empty($postData['file_type'] == 'PDF')){
                $thead ='<tr style="background:#dddddd;"><th class="text-left">Expense Type</th>';
        }
        else{
            $thead ='<tr style="background:#dddddd;">
                    <th colspan="'.($lastDay + 2).'" class="text-center"> 
                        '.$empData->emp_name.'
                    </th>
                </tr>
                <tr style="background:#dddddd;"><th class="text-left">Expense Type</th>';
        }

        for($d=1;$d<=$lastDay;$d++):	
            $thead.='<th class="text-center" style="min-width:30px;">'.$d.'</th>'; 
        endfor;
        $thead.='<th class="text-center">Total</th>';
        $thead.='</tr>';  
       
        $visitHead='<tr>
                    <th style="min-width:25px;">#</th>
                    <th style="min-width:25px;">Date</th>	
                    <th style="min-width:25px;">Party Name</th>
                    <th style="min-width:100px;">Contact Person</th>
                    <th style="min-width:100px;">Visit Type</th>
                    <th style="min-width:100px;">Purpose</th>
                </tr>';  
        $postData['report_data'] = 1;
        $visitData = $this->visit->getVisitList($postData);

        $j=1; $visitTbody = "";
        foreach($visitData as $row):
            $visitTbody .= '<tr>
                <td>'.$j++.'</td>
                <td>'.date("d-m-Y h:i:s A",strtotime($row->start_at)).'</td>
                <td>'.$row->party_name.'</td>
                <td>'.$row->contact_person.'</td>
                <td>'.$row->visit_type.'</td>
                <td>'.$row->purpose.'</td>
            </tr>';
        endforeach;         


        $tbody='';$i=0; $totalExp=0; $tfoot=''; $totalAmt = 0; $colTotalAmt = array_fill(0, ($lastDay), 0);
        foreach($expType as $row):
            $i++; 
            $tbody.='<tr>';
            $tbody.='<td>'.$row->label.'</td>';

            $rowTotalAmt=0;$j=0;
            for($d=1;$d<=$lastDay;$d++): 
                $amount=0; 
                if(isset($groupedResult[str_pad($d,2,0,STR_PAD_LEFT).'#'.$row->id]->{str_pad($d,2,0,STR_PAD_LEFT).'#'.$row->id})){
                    $amount = $groupedResult[str_pad($d,2,0,STR_PAD_LEFT).'#'.$row->id]->{str_pad($d,2,0,STR_PAD_LEFT).'#'.$row->id};
                }
                $tbody .= (!empty($amount) AND $amount > 0) ? '<td class="text-center">'.floatval($amount).'</td>':'<td class="text-center"></td>'; 
                $rowTotalAmt += $amount; 
                $colTotalAmt[$j++] += $amount;
            endfor;     
            $tbody .= (!empty($rowTotalAmt) AND $rowTotalAmt > 0) ? '<th class="text-center" style="width:45px;">'.floatval($rowTotalAmt).'</th>':'<th class="text-center"></th>'; 
            $tbody .= '</tr>';
            $totalAmt += $rowTotalAmt;
        endforeach;        

        $tfoot = '<tr class="thead-info" style="background:#dddddd;">
                    <th class="text-left">Total</th>';
                    foreach($colTotalAmt as $amount){
                        $tfoot .= (!empty($amount) AND $amount > 0) ? '<th class="text-center">'.$amount.'</th>':'<th class="text-center"></th>';
                    }
                    $tfoot .= (!empty($totalAmt) AND $totalAmt > 0) ? '<th class="text-center">'.$totalAmt.'</th>':'<th class="text-center"></th>
                </tr>';
        
        $reportTitle = 'Expense Register Report';
        $report_date = $postData['month'].' to '.date('t-m-Y',strtotime($postData['month']));
		$logo = base_url('assets/images/logo.png');

        $pdfData = '<table id="commanTable" class="table table-bordered itemList" repeat_header="1">
                    <tr>
                        <td style="width:40%;"><b>Employee Name : </b>'.$empData->emp_name.'</td>
                        <td style="width:20%;"><b>Contact No. :</b> '.$empData->emp_contact.'</td>
                        <td style="width:20%;"><b>Department :</b>'.$empData->department_name.'</td>
                        <td style="width:20%;"><b>Designation :</b>'.$empData->designation_name.'</td>
                    </tr>
                    <tr>
                        <td colspan="2"><b>Address :</b>'.$empData->emp_address .'</td>
                        <td colspan="2"><b>Email : </b>'.$empData->emp_email.'</td>
                    </tr>
                </table>
                <table id="commanTable" class="table table-bordered itemList" repeat_header="1">
                            <thead class="thead-info" id="theadData">'.$thead.'</thead>
                            <tbody id="receivableData">'.$tbody.'</tbody>
                            <tfoot id="receivableData">'.$tfoot.'</tfoot>
                        </table>';
        $pdfData .='<hr><h4>Visit History :</h4>';
        $pdfData .=' <table id="commanTable" class="table table-bordered itemList" style="margin-top:10px;" repeat_header="1">
                            <thead class="thead-info" id="theadData">'.$visitHead.'</thead>
                            <tbody id="receivableData">'.$visitTbody.'</tbody>
                        </table>';
        $htmlHeader = '<table class="table" style="border-bottom:1px solid #036aae;">
                        <tr>
                            <td class="text-uppercase text-left"><img src="'.$logo.'" class="img" style="height:30px;"></td>
                            <td class="org_title text-uppercase text-center" style="font-size:1.3rem;width:40%">'.$reportTitle.'</td>
                            <td class="text-uppercase text-right" style="font-size:0.8rem;width:30%">Date : '.$report_date.'</td>
                        </tr>
                    </table>';
        $htmlFooter = '<table class="table top-table" style="margin-top:10px;border-top:1px solid #545454;">
                    <tr>
                        <td style="width:50%;font-size:12px;">Printed On ' . date('d-m-Y') . '</td>
                        <td style="width:50%;text-align:right;font-size:12px;">Page No. {PAGENO}/{nbpg}</td>
                    </tr>
                </table>';
			
        if(!empty($postData['file_type'] == 'PDF'))
        {
            $mpdf = new \Mpdf\Mpdf();
            $pdfFileName = 'ExpenseReport_'.str_replace(["/","-"],"_",date('d-m-Y')).'.pdf';          
            $stylesheet = file_get_contents(base_url('assets/css/pdf_style.css'));
            $mpdf->WriteHTML($stylesheet, 1);
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->SetTitle($reportTitle);
            $mpdf->SetHTMLHeader($htmlHeader);
            $mpdf->SetHTMLFooter($htmlFooter);
            $mpdf->AddPage('L','','','','',5,5,15,10,3,3,'','','','','','','','','','A4-L');
            $mpdf->WriteHTML($pdfData);	
			
			$proofArr = array_unique(array_column($expData,'proof_file'));
			$mediaHtml = '';$m=0;
			if(!empty($proofArr))
			{
				$mediaHtml = '<table class="table" style="width:100%;border-collapse:collapse;"><tr>';
				foreach($proofArr as $key=>$proof_file)
				{
					if(!empty($proof_file)){
						if($m%2==0 AND $m > 0){$mediaHtml .= '</tr><tr>';}
						$mediaHtml .= '<td style="width:25%;text-align:center;border:1px solid #000000;font-size:12px;" align="center">
							<img src="'.base_url('assets/uploads/expense/'.$proof_file).'" style="width:200px;height:200px;border:1px solid #000000;border-radius:10px;" >
						</td>';
						$m++;
					}
				}
				$mediaHtml .= '</tr></table>';
				$mpdf->AddPage('L','','','','',5,5,15,10,3,3,'','','','','','','','','','A4-L');
                $mpdf->WriteHTML($mediaHtml);
			}
			
            ob_clean();	
            if(!empty($postData['type']) && $postData['type'] == 3){
                $mpdf->Output($pdfFileName, 'I');
            }else{
                $mpdf->Output($pdfFileName, 'D');
            }	
        }
        else { $this->printJson(['status'=>1,'thead'=>$thead, 'tbody'=>$tbody, 'tfoot'=>$tfoot]); }
	}

    /*Monthly Employee Wise Expense Report */
    public function monthWiseExpenseReport(){
        $this->data['expTypeList'] = $this->configuration->getSelectOptionList(['type'=>3]);
        $this->load->view($this->month_wise_exp_report,$this->data);
    }

    public function getMonthWiseExpenseData($jsonData=''){
        if(!empty($jsonData)){$postData = (Array) decodeURL($jsonData);}
        else{$postData = $this->input->post();}
        $expData = $this->expense->getMonthWiseExpenseData($postData);
        $expTypeList = $this->configuration->getSelectOptionList(['type'=>3]);

        $expTotal = array_fill(0, (count($expTypeList)+1), 0);
		$groupedResult = array_reduce($expData, function($itemData, $row) {
            if(!isset($itemData[$row->id])):
                $row->{"amount_".$row->exp_type_id} = $row->approve_amount;
                unset($row->exp_type_id,$row->approve_amount);
                $itemData[$row->id] = $row;
            else:
                $itemData[$row->id]->{"amount_".$row->exp_type_id} = $row->approve_amount;
            endif;
            
            return $itemData;
        }, []);
        $i=1; $tbody="";
        foreach ($groupedResult as $row):                
            $amtTD = '';  $totalAmt = 0;$j=0;
            foreach($expTypeList as $exp):
                $amount = (isset($row->{"amount_".$exp->id})) ? $row->{"amount_".$exp->id} : 0;  
                $amtTD .= (!empty($amount) AND $amount > 0) ? '<td>'.floatVal($amount).'</td>':'<td >-</td>'; 
                $totalAmt += $amount;
                $expTotal[$j++] += $amount;
            endforeach;   
            $tbody .= '<tr class="text-center">
                <td>'.$i++.'</td>
                <td>'.$row->emp_code.'</td>
                <td>'.$row->emp_name.'</td>
                <td>'.$row->zone_name.'</td>
                '.$amtTD.'
                <td>'.$totalAmt.'</td>
            </tr>';
            $expTotal[$j] += $totalAmt;
        endforeach;  
      	    $exp_total=''; $K=0;
			foreach($expTotal as $total){$exp_total .= '<th class="text-center">'.floatVal($total).'</th>';}
			
            $tfoot = '<tr>
                <th colspan="'.($K+4).'" class="text-right">Total</th>
               '.$exp_total.'
            </tr>';
       
        $reportTitle = 'Employee Wise Expense Report';
        $report_date = $postData['month'].' to '.date('t-m-Y',strtotime($postData['month']));
		$logo = base_url('assets/images/logo.png');
        $th=''; $k=0;
        foreach($expTypeList as $row):  
            $th .= '<th>'.$row->label.'</th>'; $k++;
        endforeach;
                   
        $thead = '<tr class="text-center">
                    <th style="min-width:25px;">#</th>
                    <th style="min-width:100px;">Employee Name</th>
                    '.$th.'  
                     <th style="min-width:100px;">Total</th>
                </tr>';

        $pdfData = '<table id="commanTable" class="table table-bordered itemList" repeat_header="1">
                            <thead class="thead-info" id="theadData">'.$thead.'</thead>
                            <tbody id="receivableData">'.$tbody.'</tbody>
                            <tfoot id="receivableData">'.$tfoot.'</tfoot>
                        </table>';
        $htmlHeader = '<table class="table" style="border-bottom:1px solid #036aae;">
                        <tr>
                            <td class="text-uppercase text-left"><img src="'.$logo.'" class="img" style="height:30px;"></td>
                            <td class="org_title text-uppercase text-center" style="font-size:1.3rem;width:40%">'.$reportTitle.'</td>
                            <td class="text-uppercase text-right" style="font-size:1rem;width:30%">Date : '.$report_date.'</td>
                        </tr>
                    </table>';
        $htmlFooter = '<table class="table top-table" style="margin-top:10px;border-top:1px solid #545454;">
                    <tr>
                        <td style="width:50%;font-size:12px;">Printed On ' . date('d-m-Y') . '</td>
                        <td style="width:50%;text-align:right;font-size:12px;">Page No. {PAGENO}/{nbpg}</td>
                    </tr>
                </table>';
			
        if(!empty($postData['file_type'] == 'PDF'))
        {
            $mpdf = new \Mpdf\Mpdf();
            $pdfFileName = 'ExpenseReport_'.str_replace(["/","-"],"_",date('d-m-Y')).'.pdf';          
            $stylesheet = file_get_contents(base_url('assets/css/pdf_style.css'));
            $mpdf->WriteHTML($stylesheet, 1);
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->SetTitle($reportTitle);
            $mpdf->SetHTMLHeader($htmlHeader);
            $mpdf->SetHTMLFooter($htmlFooter);
            $mpdf->AddPage('L','','','','',5,5,15,10,3,3,'','','','','','','','','','A4-L');
            $mpdf->WriteHTML($pdfData);	
            ob_clean();	
            if(!empty($postData['type'] == 1)){
                $mpdf->Output($pdfFileName, 'D');
            }else{
                $mpdf->Output($pdfFileName, 'I');
            }	
        }
        else { $this->printJson(['status'=>1, 'tbody'=>$tbody, 'tfoot' => $tfoot]); }
    } 

    /*Vehicle Expnese */ //27-09-25
    public function vehicleExpense($status = 0){ 
        $this->data['status'] = $status;
        $this->data['headData']->pageTitle = "Vehicle Expense";

        if($status == 0){
            $this->data['tableHeader'] = getMasterDtHeader("vehicleExpense");
        }else{
            $this->data['tableHeader'] = getMasterDtHeader("approveExpense");
        }
        $this->load->view($this->vehicle_exp,$this->data);
    }

    public function getVehicleExpDTRows($status = 0){
        $data = $this->input->post();
        $data['status'] = $status;
        if($status == 0):
		    $result = $this->expense->getVehicleExpDTRows($data);
        else:
            $result = $this->expense->getExpenseApproveDTRows($data);
        endif;

        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):
            $row->sr_no = $i++;
            if($status == 0):
                $sendData[] = getVehicleExpenseData($row);
            else:
                $sendData[] = getExpenseApproveData($row);
			endif;
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function getVehicleExpApprovedData(){
        $data = $this->input->post();
        $this->data['emp_id'] = $data['emp_id'];
        $this->data['log_time'] = $data['log_time'];
        $this->data['vehicle_id'] = $data['vehicle_id'];
        $this->data['expData'] = $this->expense->getVehicleExpenseData(['emp_id'=>$data['emp_id'],'log_time'=>$data['log_time'],'vehicle_id'=>$data['vehicle_id']]);
        $this->load->view($this->vehicle_exp_approve,$this->data);
    }

    public function saveVehicleExpApprovedData(){
        $data = $this->input->post(); 
        $errorMessage = array();
       
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else: 
            if(empty($data['emp_id'])):
                $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
            else:
                $this->printJson($this->expense->saveVehicleExpApprovedData($data));
            endif;
        endif;
    }

     

}
?>