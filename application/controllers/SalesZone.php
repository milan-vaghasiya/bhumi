<?php
class SalesZone extends MY_Controller{
    private $index = "sales_zone/index";
    private $form = "sales_zone/form";
    private $statutory_detail = "sales_zone/statutory_detail";
    private $zoneTypeArray = [1=>'Manual',3=>'State',4=>'District',5=>'Taluka'];

    public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Sales Zone";
		$this->data['headData']->controller = "salesZone";
        $this->data['headData']->pageUrl = "salesZone";
	}
	
	public function index(){
        $this->data['tableHeader'] = getConfigDtHeader($this->data['headData']->controller);
        $this->load->view($this->index,$this->data);
    }
	
    public function getDTRows(){
        $data = $this->input->post();
        $result = $this->configuration->getSalesZoneDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):          
            $row->sr_no = $i++;         
            $row->zone_type = $this->zoneTypeArray[$row->type];
            $sendData[] = getSalesZoneData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addSalesZone(){
        $this->data['zoneTypeArray'] = $this->zoneTypeArray;
        $this->load->view($this->form, $this->data);
    }

    public function save(){
        $data = $this->input->post(); 
		$errorMessage = array();

        if(empty($data['zone_name'])){
			$errorMessage['zone_name'] = "Zone Name is required.";
        }   
    
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->configuration->saveSalesZone($data));
        endif;
    }

    public function edit(){     
        $data = $this->input->post();
        $this->data['dataRow']  = $dataRow = $this->configuration->getSalesZone($data);
        $this->data['zoneTypeArray'] = $this->zoneTypeArray;       
        $this->load->view($this->form, $this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $checkData['columnName'] = ['sales_zone_id'];
            $checkData['value'] = $id;
            $checkUsed = $this->configuration->checkUsage($checkData);

            if($checkUsed == true):
                return ['status'=>0,'message'=>'The Zone is currently in use. you cannot delete it.'];
            endif;
            $this->printJson($this->configuration->trash('sales_zone',['id'=>$id]));
        endif;
    }

    public function getStateWiseOptions(){
        $data = $this->input->post();
        if($data['state_id'] == 'ALL'){
            $stateList = $this->party->getStates($data);
            $html = '';
            $thead = '<tr class="text-center">
                        <th>#</th>
                        <th>States</th>
                    </tr>';
            foreach ($stateList as $row) :
                $checked = "";
                $html.='<tr>
                    <td class="text-center">
                        <input type="checkbox" id="sub_state_id'.$row->id.'" name="sub_state_id[]" class="filled-in chk-col-success"  value="' . $row->id . '" '.$checked.'><label for="sub_state_id'.$row->id.'" class="mr-3"></label>
                    </td>
                    <td class="text-left">'.$row->name.'</td>
                </tr>';
            endforeach;
        }else{
            $cityList = $this->party->getCities($data);
            $html = '';
            $thead = '<tr class="text-center">
                        <th>#</th>
                        <th>Cities</th>
                    </tr>';
            foreach ($cityList as $row) :
                $checked = "";
                $html.='<tr>
                    <td class="text-center">
                        <input type="checkbox" id="city_id'.$row->id.'" name="city_id[]" class="filled-in chk-col-success"  value="' . $row->id . '" '.$checked.'><label for="city_id'.$row->id.'" class="mr-3"></label>
                    </td>
                    <td class="text-left">'.$row->name.'</td>
                </tr>';
            endforeach;
        }
        $this->printJson(['status'=>1, 'tbodyData'=>$html,'thead'=>$thead]);
    }

    public function addStatutoryDetail(){
        $data = $this->input->post();
        $this->data['dataRow'] = $data;
       
        $this->data['stateList'] = $this->configuration->getStatutoryDetail(['group_by'=>'state']);
        if($data['type'] != 3 && !empty($data['statutory_id']) && !empty($data['state_id'])){
            $dataRow = $this->configuration->getSalesZone($data);
            $this->data['html']=$this->getDistrictList(['type'=>$data['type'],'state'=>$data['state_id'],'statutory_id'=>$data['statutory_id']]);
        }
        $this->load->view($this->statutory_detail, $this->data);
    }

    public function getDistrictList($param=[]){
       
        $data = !empty($param)?$param:$this->input->post();
        $resultlist = $this->configuration->getStatutoryDetail(['state'=>$data['state'],'group_by'=>'district']);
        
        $statutoryArray = !empty($data['statutory_id'])?explode(",",$data['statutory_id']):[];
        $html="";
        $statutoryData =  !empty($data['statutory_id'])?$this->configuration->getStatutoryDetail(['ids'=>$statutoryArray]):[];
        foreach ($resultlist as $row) :
            $cls=($data['type'] == 5)?'getTaluka':'';
            $html .= '<li class="list-group-item " style="width:100%">';
                if($data['type'] == 4){
                    $checked = (!empty($statutoryArray) && in_array($row->id,$statutoryArray))?'checked':'';
                    $html.='<input type="checkbox" id="statutory_id'.$row->id.'" name="statutory_id[]" class="filled-in chk-col-success" '.$checked.' value="'.$row->id.'"><label for="statutory_id'.$row->id.'" class="mr-0"></label>';
                }elseif($data['type'] == 5){
                    $checked="";
                    if(!empty($statutoryArray)){
                        $checked= (!empty($statutoryData) && in_array($row->district,array_column($statutoryData,'district')))?'checked':'';
                    }
                    $html.='<input type="checkbox" id="statutory'.$row->district.'" name="district[]" class="filled-in chk-col-success" value="'.$row->district.'" '.$checked.'><label for="statutory'.$row->district.'"  class="mr-0"></label>';
                }
              
            $html .= '<a  href="javascript:void(0)" data-district="'.$row->district.'"  data-state="'.$row->state.'" class="mt-0 fs-13  fw-bold ">'.$row->district.'</a>
            </li>';
        endforeach;
        if(!empty($param)){
            return $html;
        }else{
            $this->printJson(['status'=>1, 'html'=>$html]);
        }
        
    }

    public function saveStatutoryDetail(){
        $data = $this->input->post(); 
		$errorMessage = array();
             
        if(empty($data['statutory_id'])){
            $errorMessage['general_error'] = $this->zoneTypeArray[$data['type']]."  required.";
        }       
    
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            if(!empty($data['statutory_id'])){
                $data['statutory_id'] = implode(",",$data['statutory_id']);
            }
            $this->printJson($this->configuration->saveSalesZone($data));
        endif;
    }

    public function getTalukaList(){
        $data = $this->input->post();
        $resultlist = $this->configuration->getStatutoryDetail(['state'=>$data['state'],'districts'=>$data['district']]);
        $html="";
        $statutoryArray = !empty($data['statutory_id'])?explode(",",$data['statutory_id']):[];
        foreach ($resultlist as $row) :
            $checked = (!empty($statutoryArray) && in_array($row->id,$statutoryArray))?'checked':'';
            $html .= '<li class="list-group-item">
                        <input type="checkbox" id="statutory_id'.$row->id.'" name="statutory_id[]" class="filled-in chk-col-success" '.$checked.'  value="'.$row->id.'" data-taluka="'.$row->taluka.'"><label for="statutory_id'.$row->id.'" class="mr-0"></label>
                        <a href="javascript:void(0)" class="mt-0 fs-13 fw-bold " >'.$row->taluka.'</a>
                    </li>';
        endforeach;
        
        $this->printJson(['status'=>1, 'html'=>$html]);
    }
}
?>