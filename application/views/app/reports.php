<?php
    $this->load->view('app/includes/header'); 

    $today = new DateTime();
	$today->modify('first day of this month');$first_day = date('Y-m-d');
	$today->modify('last day of this month');$last_day = date("t",strtotime($today->format('Y-m-d')));
	$monthArr = ['Apr-'.$startYear=>'01-04-'.$startYear,'May-'.$startYear=>'01-05-'.$startYear,'Jun-'.$startYear=>'01-06-'.$startYear,'Jul-'.$startYear=>'01-07-'.$startYear,'Aug-'.$startYear=>'01-08-'.$startYear,'Sep-'.$startYear=>'01-09-'.$startYear,'Oct-'.$startYear=>'01-10-'.$startYear,'Nov-'.$startYear=>'01-11-'.$startYear,'Dec-'.$startYear=>'01-12-'.$startYear,'Jan-'.$endYear=>'01-01-'.$endYear,'Feb-'.$endYear=>'01-02-'.$endYear,'Mar-'.$endYear=>'01-03-'.$endYear];
?>
<header class="header">
    <div class="main-bar bg-primary-2">
        <div class="container">
            <div class="header-content">
                <div class="left-content">
                    <a href="javascript:void(0);" class="menu-toggler me-2">
                        <svg class="text-dark" xmlns="http://www.w3.org/2000/svg" height="30px" viewBox="0 0 24 24" width="30px" fill="#000000"><path d="M13 14v6c0 .55.45 1 1 1h6c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1h-6c-.55 0-1 .45-1 1zm-9 7h6c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1zM3 4v6c0 .55.45 1 1 1h6c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1zm12.95-1.6L11.7 6.64c-.39.39-.39 1.02 0 1.41l4.25 4.25c.39.39 1.02.39 1.41 0l4.25-4.25c.39-.39.39-1.02 0-1.41L17.37 2.4c-.39-.39-1.03-.39-1.42 0z"></path></svg>
                    </a>
                    <h5 class="title mb-0 text-nowrap">Reports</h5>
                </div>
                <div class="mid-content"></div>
                <div class="right-content"></div>
            </div>
        </div>
    </div>
</header>

<div class="page-content bottom-content">
    <div class="container">
    
        <div class="row">
            <div class="col-4">
                <div class="mb-3">
                    <select name="month" id="month" class="form-control select2">
                        <?php
                            echo '<option value="">All Months</option>';
                            foreach($monthArr as $key=>$value):
                                $selected = (date('m') == date('m',strtotime($value))) ? "selected" : "";
                                echo '<option value="'.$value.'" '.$selected.'>'.$key.'</option>';
                            endforeach;
                        ?>
                    </select>  
                </div>
            </div>
            <div class="col-8">
                <div class="mb-3">
                    <select name="emp_id" id="emp_id" class="form-control select2">
                        <?php
                            echo '<option value="">All Employee</option>';
                            if(!empty($empData)):
                                foreach($empData as $row):
                                    $selected = ($row->id == $this->loginId)?"selected":"";
                                    echo '<option value="'.$row->id.'" '.$selected .'>'.$row->emp_name.'</option>';
                                endforeach;
                            endif;
                        ?>
                    </select>  
                </div>
            </div>
        </div>

        <div class="service-area mb-4">
            <div class="service-box" onclick="getMonthWiseExpenseData(1)">
                <div class="dz-icon mx-auto mb-2">
                    <svg clip-rule="evenodd" fill-rule="evenodd" height="24" stroke-linejoin="round" stroke-miterlimit="2" viewBox="0 0 32 32" width="24" xmlns="http://www.w3.org/2000/svg"><g id="Icon"><path d="m23 16c-3.863 0-7 3.137-7 7s3.137 7 7 7 7-3.137 7-7-3.137-7-7-7zm-19 10h9c.552 0 1-.448 1-1s-.448-1-1-1h-9v-19c0-.552.448-1 1-1h6v8c0 .347.179.668.474.851.295.182.663.198.973.043l3.553-1.776s3.553 1.776 3.553 1.776c.31.155.678.139.973-.043.295-.183.474-.504.474-.851v-8h6c.552 0 1 .448 1 1v10c0 .552.448 1 1 1s1-.448 1-1v-10c0-1.656-1.344-3-3-3h-22c-1.656 0-3 1.344-3 3v22c0 1.656 1.344 3 3 3h10c.552 0 1-.448 1-1s-.448-1-1-1h-10c-.552 0-1-.448-1-1zm19-8c2.76 0 5 2.24 5 5s-2.24 5-5 5-5-2.24-5-5 2.24-5 5-5zm-3.207 5.707 2 2c.39.391 1.024.391 1.414 0l3-3c.39-.39.39-1.024 0-1.414s-1.024-.39-1.414 0l-2.293 2.293s-1.293-1.293-1.293-1.293c-.39-.39-1.024-.39-1.414 0s-.39 1.024 0 1.414zm-.793-19.707h-6v6.382l2.553-1.276c.281-.141.613-.141.894 0 0 0 2.553 1.276 2.553 1.276z"/></g></svg>                    
                </div>
                <span class="font-14 d-block mb-2">Attendance</span>
            </div>
            <div class="service-box" onclick="getMonthWiseExpenseData(2)">
                <div class="dz-icon mx-auto mb-2">
                    <svg enable-background="new 0 0 100 100" height="24" viewBox="0 0 100 100" width="24" xmlns="http://www.w3.org/2000/svg"><path id="Product_Return" d="m98 50c0 26.467-21.533 48-48 48s-48-21.533-48-48c0-1.658 1.342-3 3-3s3 1.342 3 3c0 23.159 18.841 42 42 42s42-18.841 42-42-18.841-42-42-42c-11.163 0-21.526 4.339-29.322 12h11.322c1.658 0 3 1.342 3 3s-1.342 3-3 3h-18c-1.658 0-3-1.342-3-3v-18c0-1.658 1.342-3 3-3s3 1.342 3 3v10.234c8.851-8.448 20.481-13.234 33-13.234 26.467 0 48 21.533 48 48zm-21-12v27c0 1.251-.776 2.37-1.945 2.81l-24 9c-.34.126-.698.19-1.055.19s-.715-.064-1.055-.19l-24-9c-1.169-.44-1.945-1.559-1.945-2.81v-27c0-1.251.776-2.37 1.945-2.81l24-9c.68-.252 1.43-.252 2.109 0l24 9c1.17.44 1.946 1.559 1.946 2.81zm-42.457 0 15.457 5.795 15.457-5.795-15.457-5.795zm-5.543 24.92 18 6.75v-20.59l-18-6.75zm42 0v-20.59l-18 6.75v20.59z"/></svg>
                </div>
                <span class="font-14 d-block mb-2">Expense</span>
            </div>
            <div class="service-box" onclick="getMonthWiseExpenseData(3)">
                <div class="dz-icon mx-auto mb-2">
                    <svg height="24" viewBox="0 0 16 16" width="24" xmlns="http://www.w3.org/2000/svg" data-name="Layer 2"><path d="m14 .5h-12a1.5017 1.5017 0 0 0 -1.5 1.5v1a1.4977 1.4977 0 0 0 1 1.4079v7.5921a1.5017 1.5017 0 0 0 1.5 1.5h4.2618a4.4891 4.4891 0 1 0 7.2382-5.2935v-3.7986a1.4977 1.4977 0 0 0 1-1.4079v-1a1.5017 1.5017 0 0 0 -1.5-1.5zm-11 12a.501.501 0 0 1 -.5-.5v-7.5h11v2.7618a4.4725 4.4725 0 0 0 -6.7236 5.2382zm8 2a3.5 3.5 0 1 1 3.5-3.5 3.5042 3.5042 0 0 1 -3.5 3.5zm3.5-11.5a.501.501 0 0 1 -.5.5h-12a.501.501 0 0 1 -.5-.5v-1a.501.501 0 0 1 .5-.5h12a.501.501 0 0 1 .5.5z"/><path d="m11.5 10.793v-1.793a.5.5 0 0 0 -1 0v2a.4993.4993 0 0 0 .1465.3535l1 1a.5.5 0 0 0 .707-.707z"/></svg>
                    
                </div>
                <span class="font-14 d-block mb-2">Expense Register</span>
            </div>
        </div>	

    </div>
</div>  
<?php $this->load->view('app/includes/bottom_menu'); ?>
<?php $this->load->view('app/includes/footer'); ?>
<?php $this->load->view('app/includes/sidebar'); ?>

<script>
function getMonthWiseExpenseData(report_type)
{
    var month = $("#month").val();
    var emp_id = $("#emp_id").val();
	var type = 1;
	var file_type = 'PDF';
	var postData= { month:month, emp_id:emp_id, type:type, file_type:file_type };
	var u = window.btoa(JSON.stringify(postData)).replace(/=+$/, "");

    if(report_type == 1){
        var url = base_url +  'hr/attendance/getMonthlyReport/' + encodeURIComponent(u);
    }
    else if(report_type == 2){
        var url = base_url +  'expense/getMonthWiseExpenseData/' + encodeURIComponent(u);
    }
    else if(report_type == 3){
        var url = base_url +  'expense/getExpenseRegisterData/' + encodeURIComponent(u);
    }

	window.open(url);
}
</script>