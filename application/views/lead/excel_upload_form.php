<form>
    <div class="col-md-12">
        <!-- Excel Config Section Start -->
        <div class="row" id="input_excel_column">
            <h6> Excel Config.</h6>
            <div class="table-responsive">
                <table class="table jpExcelTable">
                    <thead class="thead-info">
                        <tr class="text-center">
                            <th>Company/Trade Name</th>
                            <th>Source</th>
                            <th>Business Type</th>
                            <th>Contact Person</th>
                            <th>Contact No.</th>
                            <th>Whatsapp No.</th>
                            <th>Email</th>
                            <th>Party GSTIN</th>
                            <th>State</th>
                            <th>District</th>
                            <th>Taluka</th>
                            <th>Address</th>
                            <th>Start Row</th>
                        </tr>                    
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="text" id="party_name_column" class="form-control text-center" value="A"></td>
                            <td><input type="text" id="source_column" class="form-control text-center" value="B"></td>
                            <td><input type="text" id="business_type_column" class="form-control text-center" value="C"></td>
                            <td><input type="text" id="contact_person_column" class="form-control text-center" value="D"></td>
                            <td><input type="text" id="contact_phone_column" class="form-control text-center" value="E"></td>
                            <td><input type="text" id="whatsapp_no_column" class="form-control text-center" value="F"></td>
                            <td><input type="text" id="party_email_column" class="form-control text-center" value="G"></td>
                            <td><input type="text" id="gstin_column" class="form-control text-center" value="H"></td>
                            <td><input type="text" id="state_column" class="form-control text-center" value="I"></td>
                            <td><input type="text" id="district_column" class="form-control text-center" value="J"></td>
                            <td><input type="text" id="taluka_column" class="form-control text-center" value="K"></td>
                            <td><input type="text" id="address_column" class="form-control text-center" value="L"></td>
                            <td><input type="text" id="start_row" class="form-control text-center numericOnly" value="2"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Excel Config Section End -->
        <hr>

        <div class="row">
            <input type="hidden" id="id" value="">
            <div class="col-md-6 form-group">
                <label for="">Select File</label>
                <div class="input-group">
                    <a href="<?=base_url("assets/uploads/defualt/lead_excel.xlsx")?>" class="btn btn-outline-info" title="Download Example File" download><i class="fa fa-download"></i></a>
                    <div class="input-group-append">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input form-control" id="excelFile" accept=".xlsx, .xls">
                        </div>
                    </div>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" id="readButton" type="button">Read Excel</button>
                    </div>
                </div>
                <div class="error excel_file"></div>
            </div>
        </div>
    </div>
    
    <hr>

    <div class="col-md-12">
        <div class="error itemData"></div>
        <p class="text-primary font-bold text-right">Can not save duplicate party. duplicate parties are shown with red color.</p>
        <div class="table-responsive">
            <table id="leadDetails" class="table jpExcelTable">
                <thead class="thead-info">
                    <tr class="text-center">
                        <th>#</th>
                        <th>Company/Trade Name</th>
                        <th>Source</th>
                        <th>Business Type</th>
                        <th>Contact Person</th>
                        <th>Contact No.</th>
                        <th>Whatsapp No.</th>
                        <th>Email</th>
                        <th>Party GSTIN</th>
                        <th>State</th>
                        <th>District</th>
                        <th>Taluka</th>
                        <th>Address</th>
                    </tr>                    
                </thead>
                <tbody>
                    <tr id="noData">
                        <td colspan="13" class="text-center">No data available in table</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</form>

<script src="<?php echo base_url(); ?>assets/js/xlsx.full.min.js?v=<?=time()?>"></script>
<script>
var clickedTr = 0;

$(document).ready(function() {

    $(document).on("click",'#readButton',function() {
        var inputArray = [
            "party_name",
            "source",
            "business_type",
            "contact_person",
            "contact_phone",
            "whatsapp_no",
            "party_email",
            "gstin",
            "state",
            "district",
            "taluka",
            "address"
        ];

        const alphaVal = (s) => s.toLowerCase().charCodeAt(0) - 97 + 1;
        
        var start_row = $("#input_excel_column #start_row").val();

        $("#input_excel_column .error").html("");

        $.each(inputArray,function(key,column){
            var alpha_val = $("#"+column+"_column").val();
            var input_val = alphaVal(alpha_val);

            if(input_val == ""){ $("#input_excel_column ."+column+"_column").html("Please input column no."); }

            if(input_val == 0){ $("#input_excel_column ."+column+"_column").html("Please input column no."); }
        });

        if(start_row == ""){ $("#input_excel_column .start_row").html("Please input row no."); }
        if(start_row < 2){ $("#input_excel_column .start_row").html("Please input minimum row no. 2"); }

        var fileInput = document.getElementById('excelFile');
        var file = fileInput.files[0];
        $(".excel_file").html("");
        
        if(file){
            var errorCount = $('#input_excel_column .error:not(:empty)').length;

            if(errorCount == 0){
                var columnCount = $('table#leadDetails thead tr').first().children().length;
                $("table#leadDetails > TBODY").html('<tr><td id="noData" colspan="'+columnCount+'" class="text-center">Loading...</td></tr>'); 

                setTimeout(function(){
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var data = new Uint8Array(e.target.result);
                        var workbook = XLSX.read(data, { type: 'array' });

                        var sheetName = workbook.SheetNames[0]; // Assuming the first sheet
                        var worksheet = workbook.Sheets[sheetName];

                        var jsonData = XLSX.utils.sheet_to_json(worksheet, { header: 1 });
                        var fileData = [];
                        // Process the data or display it in the table

                        //Remove blank line.
                        $('table#leadDetails > TBODY').html("");              

                        var postData = [];
                        $.each(jsonData,function(ind,row){ 
                            postData = [];
                            if(ind >= (start_row - 1)){
                                var lead_id = "";
                                if(row[1]){
                                    row[1] = row[1] || -1;

                                    $.each(inputArray,function(key,column){
                                        var alpha_val = $("#"+column+"_column").val();
                                        var input_val = alphaVal(alpha_val);

                                        if(input_val != ""){ 
                                            postData[column] = row[input_val]  || "";
                                        }
                                    });

                                    $.ajax({
                                        url : base_url + 'lead/checkPartyDuplicate',
                                        type : 'post',
                                        data : { party_name : postData['party_name'], contact_phone : postData['contact_phone']},
                                        global:false,
                                        async:false,
                                        dataType:'json'
                                    }).done(function(res){
                                        lead_id = res.lead_id;
                                    }); 

                                    postData['lead_id'] = lead_id;
                                    postData = Object.assign({}, postData);
                                    AddRow(postData);
                                } 
                            } 
                        });
                    };

                    reader.readAsArrayBuffer(file); 
                },200);
            }
        }else{
            $(".excel_file").html("Please Select File.");
        }         
    });
});

function AddRow(data){

    var tblName = "leadDetails";

    //Remove blank line.
	$('table#'+tblName+' tr#noData').remove();

    //Get the reference of the Table's TBODY element.
	var tBody = $("#" + tblName + " > TBODY")[0];    
    var ind = -1 ;
	row = tBody.insertRow(ind);
    $(row).attr('style',((data.lead_id == "")?"background:#8ce1d3;":"background:#F88379;"));

    var disabled = ((data.lead_id == "")?false:true);
    
    //Add index cell
	var countRow = ($('#' + tblName + ' tbody tr:last').index() + 1);
	var cell = $(row.insertCell(-1));
	cell.html(countRow);
	cell.attr("style", "width:5%;");

    $(row).attr('id',countRow);

    var mainIdInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][id]",  value: $("#id").val() ,disabled:disabled});
    var partyNameInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][party_name]",  value: data.party_name ,disabled:disabled});
    cell = $(row.insertCell(-1));
    cell.html(data.party_name);
    cell.append(partyNameInput);
    cell.append(mainIdInput);

    var sourceInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][source]",  value: data.source,disabled:disabled });
    cell = $(row.insertCell(-1));
    cell.html(data.source);
    cell.append(sourceInput);

    var bTypeInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][business_type]",  value: data.business_type ,disabled:disabled});
    cell = $(row.insertCell(-1));
    cell.html(data.business_type);
    cell.append(bTypeInput);

    var cPersonInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][contact_person]",  value: data.contact_person ,disabled:disabled});
    cell = $(row.insertCell(-1));
    cell.html(data.contact_person);
    cell.append(cPersonInput);

    var cPhoneInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][contact_phone]",  value: data.contact_phone,disabled:disabled });
    cell = $(row.insertCell(-1));
    cell.html(data.contact_phone);
    cell.append(cPhoneInput);

    var wpNoInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][whatsapp_no]",  value: data.whatsapp_no ,disabled:disabled});
    cell = $(row.insertCell(-1));
    cell.html(data.whatsapp_no);
    cell.append(wpNoInput);

    var pEmailInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][party_email]",  value: data.party_email ,disabled:disabled});
    cell = $(row.insertCell(-1));
    cell.html(data.party_email);
    cell.append(pEmailInput);

    var gstInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][gstin]",  value: data.gstin,disabled:disabled });
    cell = $(row.insertCell(-1));
    cell.html(data.gstin);
    cell.append(gstInput);

    var stateInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][state]",  value: data.state ,disabled:disabled});
    cell = $(row.insertCell(-1));
    cell.html(data.state);
    cell.append(stateInput);

    var districtInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][district]",  value: data.district,disabled:disabled });
    cell = $(row.insertCell(-1));
    cell.html(data.district);
    cell.append(districtInput);

    var talukaInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][taluka]",  value: data.taluka,disabled:disabled });
    cell = $(row.insertCell(-1));
    cell.html(data.taluka);
    cell.append(talukaInput);

    var addressInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][address]",  value: data.address ,disabled:disabled});
    cell = $(row.insertCell(-1));
    cell.html(data.address);
    cell.append(addressInput);
}
</script>