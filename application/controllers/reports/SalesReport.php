<?php
class SalesReport extends MY_Controller
{
    private $empActivity = "report/sales_report/emp_activity";
    private $visitHistory = "report/sales_report/visit_history";
    private $order_monitoring = "report/sales_report/order_monitoring";
    private $sales_register = "report/sales_report/sales_register";
    private $sales_analysis = "report/sales_report/sales_analysis";
    private $lead_register = "report/sales_report/lead_register";
    private $executive_analysis = "report/sales_report/executive_analysis"; 
    private $sales_target = "report/sales_report/sales_target"; 
    private $customerHistory = "report/sales_report/customer_history";
    
    public function __construct(){
		parent::__construct();
		$this->isLoggedin();
		$this->data['headData']->pageTitle = "Sales Report";
		$this->data['headData']->controller = "reports/salesReport";
	}
	
    /* Employee Activity Report */
    public function empActivity(){
        $this->data['pageHeader'] = 'EMPLOYEE ACTIVITY';
        $this->data['empList'] = $this->usersModel->getEmployeeList();
		$this->data['API_KEY'] = 'AIzaSyACJW3ouSsTuZserlw3FRHIC2MWbppIuJ4';
        $this->load->view($this->empActivity,$this->data);
    }

    public function getEmpActivity(){
        $data = $this->input->post();
        $errorMessage = array();
		if(empty($data['emp_id']))
			$errorMessage['emp_id'] = "Employee is Required";
		if(empty($data['activity_date']))
			$errorMessage['activity_date'] = "Date is Required";

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $logData = $this->locationLog->getLocationLogs($data['activity_date'],$data['emp_id']);
			$activityLog = '';$locationLog = Array();$letterPoint = 'B';$tp = ['','Check In','Check Out','Visit Start','Visit End'];
            if(!empty($logData))
			{
				foreach($logData as $row)
				{
					$activityTime = date('d M Y H:i:s',strtotime($row->log_time));
					$title = (!empty($row->party_name)) ? $row->party_name : $tp[$row->log_type];
					$tpText = (!in_array($row->log_type,[1,2])) ? '<span class="float-end" style="width:15%">'.$tp[$row->log_type].'</span>' : '';
					$activityLog .= '<div class="activity-info">
                                        <div class="icon-info-activity"><i class="fas fa-map-marker-alt1 bg-soft-danger">'.$letterPoint.'</i></div>
                                        <div class="activity-info-text mt-1">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="m-0 text-uppercase text-primary">'.$title.'</h6>
                                                '.$tpText.'
                                            </div>
                                            <h5 class="text-muted fs-12"><i class="fas fa-clock"></i> '.$activityTime.'</h5>
                                            <p class="text-muted fs-12">'.$row->address.'</p>
                                        </div>
                                    </div>';
					$locationLog[] = $row->location;
					$letterPoint++;
				}
			}
			$this->printJson(['status'=>1, 'locationLog'=>$locationLog,'activityLog'=>$activityLog]);
        endif;
    }

    /* Visit History Report */
	public function visitHistory(){
        $this->data['pageHeader'] = 'VISIT HISTORY';
		$this->data['empList'] = $this->usersModel->getEmployeeList();
        $this->load->view($this->visitHistory,$this->data);
    }

	public function getVisitHistory($jsonData=''){
        if(!empty($jsonData)){$postData = (Array) json_decode(urldecode(base64_decode($jsonData)));}
        else{$postData = $this->input->post();}
        $orderData = $this->salesReportModel->getVisitHistory($postData);

        $i=1; $tbody="";
        foreach($orderData as $row):
            $d1 = new DateTime($row->start_at);
            $d2 = new DateTime($row->end_at);
            $interval = $d1->diff($d2);
            $diffInSeconds = $interval->s;
            $diffInMinutes = $interval->i; 
            $diffInHours   = $interval->h;
            $duration=($diffInHours*60)+$diffInMinutes+($diffInSeconds/60);

            $imgFile = '';
    	    /*if(!empty($row->img_file)):
    	        $imgPath = base_url('assets/uploads/visit_log/'.$row->img_file);
                $imgFile='<div class="picture-item" >
					<a href="'.$imgPath.'" class="lightbox" >
						<img src="'.$imgPath.'" alt="" class="img-fluid"  width="20" height="20"   style="border-radius:0%;border: 0px solid #ccc;padding:3px;"/>
					</a> 
				</div> ';
    		endif;*/

            $status='';
            if($row->approve_by > 0){
                $status = '<span class="badge rounded-pill bg-success">Approved</span>';
            }

            $tbody .= '<tr>
                <td>'.$i++.'</td>
                <td>'.date("d-m-Y h:i:s A",strtotime($row->start_at)).'</td>
                <td>'.$row->emp_code.'</td>
                <td>'.$row->emp_name.'</td>
                <td>'.$row->party_name.'</td>
                <td>'.$row->contact_person.'</td>
                <td>'.$row->visit_type.'</td>
                <td>'.$row->purpose.'</td>
                <td>'. number_format($duration,2).'</td>
                <td>'.$status.'</td>
                <td>'.(!empty($row->apr_by_name) ? $row->apr_by_name.'<hr style="margin:0px;">'.formatDate($row->approve_at) : '').'</td>';                
            $tbody .= '</tr>';
        endforeach;     
     
        $reportTitle = 'VISIT HISTORY';
        $report_date = date('d-m-Y',strtotime($postData['from_date'])).' to '.date('d-m-Y',strtotime($postData['to_date']));
        $thead = (empty($jsonData)) ? '<tr class="text-center"><th colspan="8">'.$reportTitle.' ('.$report_date.')</th></tr>' : '';
        $thead .= '<tr>
                        <th style="min-width:25px;" height="30">#</th>
                        <th style="min-width:25px;">Date</th>
                        <th style="min-width:25px;">Party Name</th>
                        <th style="min-width:100px;">Contact Person</th>
                        <th style="min-width:100px;">Visit Type</th>		
                        <th style="min-width:100px;">Purpose</th>		
                        <th style="min-width:100px;">Duration<br><small>(Minutes)</small></th>
                        <th style="min-width:50px;">Status</th>		
                        <th style="min-width:100px;">Approve By</th>				
                </tr>';

        $companyData = $this->salesReportModel->getCompanyInfo();
        $logoFile = (!empty($companyData->company_logo)) ? $companyData->company_logo : 'logo.png';
        $logo = base_url('assets/images/' . $logoFile);
        
        $pdfData = '<table id="commanTable" class="table table-bordered poItemList" repeat_header="1">
                            <thead class="thead-info" id="theadData">'.$thead.'</thead>
                            <tbody id="receivableData">'.$tbody.'</tbody>
                        </table>';
        $htmlHeader = '<table class="table" style="border-bottom:1px solid #036aae;">
                        <tr>
                            <td class="text-uppercase text-left" style="font-size:1rem;width:30%">'.$reportTitle.'</td>
                            <td class="org_title text-uppercase text-center" style="font-size:1.3rem;width:40%">'.$companyData->company_name.'</td>
                            <td class="text-uppercase text-right" style="font-size:1rem;width:30%">Date : '.$report_date.'</td>
                        </tr>
                    </table>
                    <table class="table" style="border-bottom:1px solid #036aae;margin-bottom:2px;">
                        <tr><td class="org-address text-center" style="font-size:13px;">'.$companyData->company_address.'</td></tr>
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
            $filePath = realpath(APPPATH . '../assets/uploads/');
            $pdfFileName = $filePath.'/CashBook.pdf';
            $stylesheet = file_get_contents(base_url('assets/css/pdf_style.css'));
            $mpdf->WriteHTML($stylesheet, 1);
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->SetWatermarkImage($logo, 0.08, array(120, 120));
            $mpdf->showWatermarkImage = true;
            $mpdf->SetTitle($reportTitle);
            $mpdf->SetHTMLHeader($htmlHeader);
            $mpdf->SetHTMLFooter($htmlFooter);
            $mpdf->AddPage('L','','','','',5,5,20,10,3,3,'','','','','','','','','','A4-L');
            $mpdf->WriteHTML($pdfData); 
            ob_clean();
            $mpdf->Output($pdfFileName, 'I');
        }else { $this->printJson(['status'=>1, 'tbody'=>$tbody]); }
    }
	
    public function getPartyList(){
        $data = $this->input->post();
        $partyData=""; 
        if($data['party_type'] == 2){
            $partyData = $this->party->getLeadList();
        }else{
            $partyData = $this->party->getPartyList(['party_type'=>1]);
        }
        $options = '<option value="">All Party</option>';
        if(!empty($partyData)){
            foreach($partyData as $row){
                $options .= '<option value="'.$row->id.'">'.$row->party_name.'</option>';
            }
        }
        $this->printJson(['status'=>1, 'options'=>$options]);
    }
    
    /* Order Monitoring Report */
    public function orderMonitoring(){
        $this->data['pageHeader'] = 'ORDER MONITORING REPORT';
        $this->data['startDate'] = getFyDate(date("Y-m-01"));
        $this->data['endDate'] = getFyDate(date("Y-m-t"));
        $this->data['partyList'] = $this->party->getPartyList(['party_category'=>1]);
        $this->load->view($this->order_monitoring,$this->data);
    }

    public function getOrderMonitoringData(){
        $postData = $this->input->post(); $postData['order_report'] = 1; $postData['group_by'] = 'so_trans.id';
        $postData['customWhere'] = "so_master.trans_date BETWEEN '".$postData['from_date']."' AND '".$postData['to_date']."'";
        $result = $this->sales->getSalesOrderItems($postData);

        $i=1; $tbody="";
        foreach($result as $row):
            $tbody .= '<tr>
                <td>'.$i.'</td>
                <td>'.formatDate($row->trans_date).'</td>
                <td>'.$row->trans_number.'</td>
                <td>'.$row->party_name.'</td>
                <td>'.$row->item_name.'</td>
                <td>'.floatval($row->qty).'</td>
                <td>'.formatDate($row->dispatch_date).'</td>
                <td>'.$row->ref_no.'</td>
                <td>'.(!empty($row->dispatch_qty) ? floatval($row->dispatch_qty) : '').'</td>
            </tr>';
            $i++;
        endforeach; 
    
        $this->printJson(['status'=>1, 'tbody'=>$tbody]);
    }

    /* Sales Register Report */
    public function salesRegister($startDate="",$endDate=""){
        $this->data['pageHeader'] = 'SALES REGISTER REPORT';
        $this->data['startDate'] = (!empty($startDate))?$startDate:getFyDate(date("Y-m-01"));
        $this->data['endDate'] = (!empty($endDate))?$endDate:getFyDate(date("Y-m-d"));
        $this->data['stateList'] = $this->configuration->getStatutoryDetail(['group_by'=>'state']);
        $this->load->view($this->sales_register,$this->data);
    }

    public function getSalesRegisterData(){
        $data = $this->input->post();
        $result = ($data['report_type'] == 1)?$this->salesReportModel->getSalesRegisterData($data):$this->salesReportModel->getSalesRegisterDataItemWise($data);

        $thead = '<tr>
            <th>#</th>';

        if($data['report_type'] == 2):
            $thead .= '<th>Item Name</th>';
            $thead .= '<th>Qty.</th>';
            $thead .= '<th>Price</th>';
            $thead .= '<th>Amount</th>';
        else:
            $thead .= '<th>SO Date</th>';
            $thead .= '<th>SO No.</th>';
            $thead .= '<th>Party Name</th>';
            $thead .= '<th>Gst No.</th>';
            $thead .= '<th>Total Amount</th>';
        endif;

        $thead .= '<th>Disc. Amount</th>
            <th>Taxable Amount</th>
            <th>GST Amount</th>
            <th>Net Amount</th>
        </tr>';

        $tbody=''; $i=1;        
        $totalAmount = $totalDiscAmount = $totalTaxableAmount = $totalGstAmount = $totalNetAmount = 0;

        if($data['report_type'] == 1):
            foreach($result as $row):
                $tbody .= '<tr>
                    <td>'.$i++.'</td>
                    <td>'.formatDate($row->trans_date).'</td>
                    <td>'.$row->trans_number.'</td>
                    <td class="text-left">'.$row->party_name.'</td>
                    <td class="text-left">'.$row->gstin.'</td>
                    <td>'.floatVal($row->total_amount).'</td>
                    <td>'.floatVal($row->disc_amount).'</td>
                    <td>'.floatVal($row->taxable_amount).'</td>
                    <td>'.floatVal($row->gst_amount).'</td>
                    <td>'.floatVal($row->net_amount).'</td>
                </tr>';

                $totalAmount += $row->total_amount;
                $totalDiscAmount += $row->disc_amount;
                $totalTaxableAmount += $row->taxable_amount;
                $totalGstAmount += $row->gst_amount;
                $totalNetAmount += $row->net_amount;
            endforeach;
        else:
            foreach($result as $row):
                $tbody .= '<tr>
                    <td>'.$i++.'</td>
                    <td class="text-left">'.$row->item_name.'</td>
                    <td>'.floatVal($row->qty).'</td>
                    <td>'.floatVal($row->price).'</td>
                    <td>'.floatVal($row->amount).'</td>
                    <td>'.floatVal($row->disc_amount).'</td>
                    <td>'.floatVal($row->taxable_amount).'</td>
                    <td>'.floatVal($row->gst_amount).'</td>
                    <td>'.floatVal($row->net_amount).'</td>
                </tr>';

                $totalAmount += $row->amount;
                $totalDiscAmount += $row->disc_amount;
                $totalTaxableAmount += $row->taxable_amount;
                $totalGstAmount += $row->gst_amount;
                $totalNetAmount += $row->net_amount;
            endforeach;
        endif;

        $tfoot = '<tr>
            <th colspan="'.(($data['report_type'] == 1)?5:4).'" class="text-right">Total</th>
            <th>'.floatVal($totalAmount).'</th>
            <th>'.floatVal($totalDiscAmount).'</th>
            <th>'.floatVal($totalTaxableAmount).'</th>
            <th>'.floatVal($totalGstAmount).'</th>
            <th>'.floatVal($totalNetAmount).'</th>
        </tr>';

        $this->printJson(['status'=>1,'thead'=>$thead,'tbody'=>$tbody,'tfoot'=>$tfoot]);
    }

    /* Sales Analysis Report */
    public function salesAnalysis(){
        $this->data['pageHeader'] = 'SALES ANALYSIS REPORT';
        $this->data['startDate'] = getFyDate(date("Y-m-01"));
        $this->data['endDate'] = getFyDate(date("Y-m-t"));
        $this->data['salesExecutives'] = $this->usersModel->getEmployeeList();
        $this->data['bTypeList'] = $this->configuration->getBusinessTypeList(); 
        $this->load->view($this->sales_analysis,$this->data);
    }

    public function getSalesAnalysisData(){
        $data = $this->input->post();
        $result = $this->salesReportModel->getSalesAnalysisData($data);

        $thead = $tbody = $tfoot = ''; $i=1;
        if($data['report_type'] == 1):
            $thead .= '<tr>
                <th>#</th>
                <th class="text-left">Customer Name</th>
                <th class="text-right">Taxable Amount</th>
                <th class="text-right">GST Amount</th>
                <th class="text-right">Net Amount</th>
            </tr>';

            $taxableAmount = $gstAmount = $netAmount = 0;
            foreach($result as $row):
                $tbody .= '<tr>
                    <td>'.$i.'</td>
                    <td class="text-left">'.$row->party_name.'</td>
                    <td class="text-right">'.floatval($row->taxable_amount).'</td>
                    <td class="text-right">'.floatval($row->gst_amount).'</td>
                    <td class="text-right">'.floatval($row->net_amount).'</td>
                </tr>';
                $i++;
                $taxableAmount += floatval($row->taxable_amount);
                $gstAmount += floatval($row->gst_amount);
                $netAmount += floatval($row->net_amount);
            endforeach;

            $tfoot .= '<tr>
                <th colspan="2" class="text-right">Total</th>
                <th class="text-right">'.$taxableAmount.'</th>
                <th class="text-right">'.$gstAmount.'</th>
                <th class="text-right">'.$netAmount.'</th>
            </tr>';
        else:
            $thead .= '<tr>
                <th>#</th>
                <th class="text-left">Item Name</th>
                <th class="text-right">Qty.</th>
                <th class="text-right">Price</th>
                <th class="text-right">Taxable Amount</th>
            </tr>';

            $totalQty = $taxableAmount = 0;
            foreach($result as $row):
                $tbody .= '<tr>
                    <td>'.$i.'</td>
                    <td class="text-left">'.$row->item_name.'</td>
                    <td class="text-right">'.floatVal($row->qty).'</td>
                    <td class="text-right">'.floatVal($row->price).'</td>
                    <td class="text-right">'.floatVal($row->taxable_amount).'</td>
                </tr>';
                $i++;
                $totalQty += floatval($row->qty);
                $taxableAmount += floatval($row->taxable_amount);
            endforeach;

            $tfoot .= '<tr>
                <th colspan="2" class="text-right">Total</th>
                <th class="text-right">'.$totalQty.'</th>
                <th></th>
                <th class="text-right">'.$taxableAmount.'</th>
            </tr>';
        endif;

        $this->printJson(['status'=>1,'thead'=>$thead,'tbody'=>$tbody,'tfoot'=>$tfoot]);
    }

    /****** Lead Register */
	public function leadRegister(){
		$this->data['headData']->pageTitle = "Lead Register";
        $this->data['pageHeader'] = 'Lead Register';
        $this->data['salesExecutives'] = $this->usersModel->getEmployeeList();
        $this->data['stateList'] = $this->configuration->getStatutoryDetail(['group_by'=>'state']);
        $this->data['bTypeList'] = $this->configuration->getBusinessTypeList();
        $this->data['leadStages'] = $this->configuration->getLeadStagesList();
        $this->load->view($this->lead_register,$this->data);
    }

    public function getLeadRegister(){
        $postData = $this->input->post();
        $parameter = [
            'party_type'=>$postData['party_type'],
            'executive_id'=>(($postData['executive_id'] != 'ALL')?$postData['executive_id']:''),
            'business_type'=>(($postData['business_type'] != 'ALL')?$postData['business_type']:''),
            'state'=>(($postData['state'] != 'ALL')?$postData['state']:''),
            'district'=>(($postData['district'] != 'ALL')?$postData['district']:''),
            'statutory_id'=>(($postData['statutory_id'] != 'ALL')?$postData['statutory_id']:''),
        ];
        $leadList = $this->party->getLeadList($parameter);
        $i=1; $tbody="";
        foreach($leadList as $row):
            $tbody .= '<tr>
                <td>'.$i++.'</td>
                <td>'.$row->party_name.'</td>
                <td>'.$row->executive.'</td>
                <td>'.$row->contact_person.'</td>
                <td>'.$row->contact_phone.'</td>
                <td>'.$row->business_type.'</td>
                <td class="text-wrap text-left">'.$row->state.', '.$row->district.'</td>
                <td >'.$row->taluka.'</td>';                
            $tbody .= '</tr>';
        endforeach;     
     
        $this->printJson(['status'=>1, 'tbody'=>$tbody]);
    }  
    /****** End  Register*/

    /* Executive Analysis Report */
    public function executiveAnalysis(){
		$this->data['headData']->pageTitle = "EXECUTIVE ANALYSIS REPORT";
        $this->data['startDate'] = getFyDate(date("Y-m-01"));
        $this->data['endDate'] = getFyDate(date("Y-m-t"));
        $this->data['salesExecutives'] = $this->usersModel->getEmployeeList();
        $this->data['bTypeList'] = $this->configuration->getBusinessTypeList();
        $this->data['stateList'] = $this->configuration->getStatutoryDetail(['group_by'=>'state']); 
        $this->load->view($this->executive_analysis,$this->data);
    }

    public function getExecutiveAnalysisData($jsonData=""){
        if(!empty($jsonData)):
            $data = (Array) decodeURL($jsonData);
        else:
            $data = $this->input->post();
        endif;

        $result = $this->salesReportModel->getExecutiveAnalysisData($data);
        $i=1; $tbody='';
        if(!empty($result)):
            foreach($result as $row):
                $tbody .= '<tr>
                    <td>'.$row->emp_name.'</td>
                    <td>'.$row->total_visit.'</td>
                    <td>'.$row->total_new_lead.'</td>
                    <td>'.$row->total_enq.'</td>
                    <td>'.$row->total_ord.'</td>';                
                $tbody .= '</tr>';
            endforeach;   
        endif;  
     
        if($data['type'] == 1){
			$thead = '<tr>
                            <th>Executive</th>
                            <th>Visit</th>
                            <th>New Lead</th>
                            <th>Sales Enquiry</th>
                            <th>Sales Order</th>
                        </tr>';
			
			$pdfData = '<table id="reportTable" class="table item-list-bb">
			<thead class="thead-info" id="theadData">'.$thead.'</thead>
			<tbody class="text-center">'.$tbody.'</tbody>
			</table>';
            
		    $logo= base_url('assets/images/logo.png');
			$htmlHeader = '<table>
								<tr>
									<td style="width:25%"><img src="'.$logo.'" class="img" style="height:60px;"></td>
									<th style="width:50%" class="text-center">EXECUTIVE ANALYSIS REPORT</th>
                                    <td style="width:25%" class="text-right fs-14">From : '.formatDate($data["from_date"]).'<br> To : '.formatDate($data["to_date"]).'</td>
								</tr>
						   </table> <hr>';
            
			$mpdf = new \Mpdf\Mpdf();
			$pdfFileName='Executive_report_'.date('Y-m-d').'.pdf';
			$stylesheet = file_get_contents(base_url('assets/css/pdf_style.css'));
			$mpdf->WriteHTML($stylesheet,1);
			$mpdf->SetDisplayMode('fullpage');		   
			$mpdf->SetProtection(array('print'));
			
			$mpdf->SetHTMLHeader($htmlHeader);
			$mpdf->SetHTMLFooter("");
			$mpdf->AddPage('P','','','','',5,5,30,5,5,5,'','','','','','','','','','A4-P');
			$mpdf->WriteHTML($pdfData);
			$mpdf->Output($pdfFileName,'I');
		}else{
			$this->printJson(['status'=>1, 'tbody'=>$tbody]);
		}
    }    
    
	public function targetVsAchieve(){
        $this->data['pageHeader'] = 'Target V/S Achievement ';
        $this->data['zoneList'] = $this->configuration->getSalesZoneList();
        $this->data['monthData'] = $this->getMonthListFY();
        $this->load->view($this->sales_target, $this->data);
    }

    public function getTargetRows(){
		$postData = $this->input->post();
        $errorMessage = array();
		
        if(empty($postData['target_month']))
            $errorMessage['target_month'] = "Month is required.";

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $postData['zone_id'] = (!empty($postData['zone_id']) && $postData['zone_id'] == 'ALL')?'':$postData['zone_id'];
			$resultData = $this->sales->getTargetData($postData);
            $targetData = "";
			if(!empty($resultData)):
                $i=1;
				foreach($resultData as $row):
                    $total_new_lead = !empty($row->new_lead)?$row->new_lead:0;
                    $sales_amount = !empty($row->sales_amount)?$row->sales_amount:0;
                    $leadAcheive = !empty($row->achieve_new_lead)?$row->achieve_new_lead:0;
                    $salesAcheive = !empty($row->achieve_sales_amount)?$row->achieve_sales_amount:0;
                  
                    $leadRatio = 0;$salesRatio = 0;
                    if($leadAcheive > 0 && $total_new_lead > 0){ $leadRatio = ($leadAcheive*100)/$total_new_lead; }
                    if($salesAcheive > 0 && $salesAcheive > 0){ $salesRatio = ($salesAcheive*100)/$sales_amount; }
                    
                    $targetData .= '<tr>';
                    $targetData .= '<td>'.$i++.'</td>';
                    $targetData .= '<td>'.'['.$row->emp_code.'] '.$row->emp_name.'</td>';
                    $targetData .= '<td>'.$row->zone_name.'</td>';
                    $targetData .= '<td>'.$total_new_lead.'</td>';
                    $targetData .= '<td>'.$leadAcheive.'</td>';
                    $targetData .= '<td>'.round($leadRatio,2).'%</td>';
                    $targetData .= '<td>'.$sales_amount.'</td>';
                    $targetData .= '<td>'.$salesAcheive.'</td>';
                    $targetData .= '<td>'.round($salesRatio,2).'%</td>';
                    $targetData .= '</tr>';
                       
				endforeach;
			endif;
            $this->printJson(['status'=>1,'targetData'=>$targetData]);
		endif;
    }

    /* Customer History Report */
	public function customerHistory(){
        $this->data['pageHeader'] = 'CUSTOMER HISTORY';
		$this->data['customerData'] = $this->party->getpartyList(['party_type'=>1]);
        $this->load->view($this->customerHistory,$this->data);
    }

    public function getCustomerHistory(){
        $postData = $this->input->post();
        $slData = $this->sales->getSalesLog(['party_id'=>$postData['party_id'], 'order_by'=>1]); 
        $html="";
        if(!empty($slData))
		{
			foreach($slData as $row)
			{
				$reminderRes="";
                if($row->log_type == 3 && !empty($row->remark)){
                    $reminderRes = '<p class="text-muted font-11">Res : '.$row->remark.'</p>';
                }
                $link = '';
                if($row->log_type == 3){
                    $link = '<p class="text-muted fs-11"><strong>'.$row->mode.' : </strong> '.date("d M Y H:i A",strtotime($row->ref_date." ".$row->reminder_time)).'</p>';
                }
                elseif($row->log_type == 4){
                    $link = '<a href="'.base_url('lead/printEnquiry/'.$row->ref_id).'" class="fw-bold text-primary" target="_blank">'.$row->ref_no.'</a>';
                }
                elseif($row->log_type == 5){
                    $link = '<a href="'.base_url('lead/printQuotation/'.$row->ref_id).'" class="fw-bold text-primary" target="_blank">'.$row->ref_no.'</a>';
                }
                elseif($row->log_type == 6){
                    $link = '<a href="'.base_url('lead/printOrder/'.$row->ref_id).'" class="fw-bold text-primary" target="_blank">'.$row->ref_no.'</a>';
                }
                elseif($row->log_type == 7){
                    $link = "Lost Lead";
                }
                elseif($row->log_type == 9){
                    $link = '<a href="'.base_url('lead/printEnquiry/'.$row->ref_id).'" class="fw-bold text-primary" target="_blank">'.$row->ref_no.'</a>';
                }

				$html .= '<div class="activity-info">
								<div class="icon-info-activity"><i class="'.$this->iconClass[$row->log_type].'"></i></div>
								<div class="activity-info-text">
									<div class="d-flex justify-content-between align-items-center">
										<h6 class="m-0 fs-13">'.$this->logTitle[$row->log_type].'</h6>
                                       
										<span class="text-muted w-30 d-block font-12">
										'.date("d F",strtotime($row->created_at)).'</span>
									</div>
									<p class=" m-1 font-12"><i class="fa fa-user"></i> '.$row->creator.'</p>
									<p class="text-muted m-1 font-13">'.$row->notes.$link.'</p>
                                    '.$reminderRes.'
								</div>
							</div>';
			}
		}
        $html2="";
        $logCountData = $this->sales->getLogCountForCustHistory($postData); 

        if(!empty($logCountData)){            
            $html2 = '<div class="activity">
                        <div class="activity-info-text">
                            <div class="justify-content-between align-items-center">
                                <p><h6 class="m-0 fs-16">Lead Created At : '.(!empty($logCountData->lead_created) ? date('d-m-Y H:i:s',strtotime($logCountData->lead_created)) : '').'</h6></p>    
                                <p><h6 class="m-0 fs-16">Total Orders : '.(!empty($logCountData->total_orders) ? $logCountData->total_orders : '').'</h6></p>    
                                <p><h6 class="m-0 fs-16">Total Amount : '.(!empty($logCountData->total_ord_amt) ? floatval($logCountData->total_ord_amt) : '').'</h6></p>    
                            </div>
                        </div>
                    </div>';
        }
        $this->printJson(['status'=>1, 'html'=>$html, 'html2'=>$html2]);
    } 
}
?>