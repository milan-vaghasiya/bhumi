$(document).ready(function(){

	$(document).on('click','.addItem',function(){
        var formData = {};
		var gst_per = parseFloat($("#item_id :selected").data('gst_per')) || 0;
		var disc_per = parseFloat($("#regular_disc").val()) || 0;
        var price = parseFloat($("#price").val()) || 0;
	
        formData.gst_per = gst_per;
		formData.id = $("#id").val();
        formData.row_index = $("#row_index").val();
        formData.item_id = $("#item_id").val();
        formData.item_name = $("#item_id :selected").text();
        formData.qty = parseFloat($("#qty").val());
        formData.price = price;
        formData.disc_per = disc_per;
        formData.item_remark = $("#item_remark").val();
        formData.from_entry_type = $("#from_entry_type").val();
        formData.ref_id = $("#ref_id").val();
        $(".error").html("");
        if(formData.item_id == ""){ 
            $('.item_id').html("Item Name is required.");
        }
        if(formData.qty == "" || parseFloat(formData.qty) == 0){ 
            $('.qty').html("Qty is required.");
        }
		if($("#module_type").val() != 1){
			if(formData.price == "" || parseFloat(formData.price) == 0){ 
				$('.price').html("Price is required.");
			}
		}
		
        
        var errorCount = $('.error:not(:empty)').length;
		if(errorCount == 0){           
            AddRow(formData);
			$("#itemForm input").each(function(){
				if($(this).data('resetval')){$(this).val($(this).data('resetval'));}else{$(this).val('');}
			});
			$("#itemForm").find('select').val('');
			$("#itemForm").find('textarea').val('');
			$("#item_id").select2();
        }
    });
	
	$(document).on('change','#item_id',function(){
        var item_id = $(this).val();
		var price = ($("#item_id :selected").data('price')) || 0;
		$("#price").val(price);
    });

    /* Updated By :- Sweta @02-04-2024 */
	$(document).on('change','#business_type',function(){
        var business_type = $(this).val();
        getPartyList({"business_type":business_type});
        initSelect2("right_modal_lg");
        
    });
});

function AddRow(data) {
    var tblName = "salesOrderItems";

    //Remove blank line.
	$('table#'+tblName+' tr#noData').remove();

	//Get the reference of the Table's TBODY element.
	var tBody = $("#" + tblName + " > TBODY")[0];

	//Add Row.
	if (data.row_index != "") {
		var trRow = data.row_index;
		//$("tr").eq(trRow).remove();
		$("#" + tblName + " tbody tr:eq(" + trRow + ")").remove();
	}
	var ind = (data.row_index == "") ? -1 : data.row_index;
	row = tBody.insertRow(ind);

    //Add index cell
	var countRow = (data.row_index == "") ? ($('#' + tblName + ' tbody tr:last').index() + 1) : (parseInt(data.row_index) + 1);
	var cell = $(row.insertCell(-1));
	cell.html(countRow);
	cell.attr("style", "width:5%;");

    var itemIdInput = $("<input/>",{type:"hidden",name:"itemData["+countRow+"][item_id]", class:"item_id",value:data.item_id});
	var transIdInput = $("<input/>",{type:"hidden",name:"itemData["+countRow+"][id]",value:data.id});
	var refIdInput = $("<input/>",{type:"hidden",name:"itemData["+countRow+"][ref_id]",value:data.ref_id});
	var formEntryTypeInput = $("<input/>",{type:"hidden",name:"itemData["+countRow+"][from_entry_type]",value:data.from_entry_type});
	cell = $(row.insertCell(-1));
	cell.html(data.item_name);
	cell.append(itemIdInput);
	cell.append(transIdInput);
	cell.append(formEntryTypeInput);
	cell.append(refIdInput);
	
	var qtyInput = $("<input/>",{type:"hidden",name:"itemData["+countRow+"][qty]",value:data.qty});
	cell = $(row.insertCell(-1));
	cell.html(data.qty);
	cell.append(qtyInput);

	var priceInput = $("<input/>",{type:"hidden",name:"itemData["+countRow+"][price]",value:data.price});
	var gstPerInput = $("<input/>",{type:"hidden",name:"itemData["+countRow+"][gst_per]",value:data.gst_per});
	var discPerInput = $("<input/>",{type:"hidden",name:"itemData["+countRow+"][disc_per]",value:data.disc_per});
	
	cell = $(row.insertCell(-1));
	cell.html(data.price);
	cell.append(priceInput);
	cell.append(gstPerInput);
	cell.append(discPerInput);
	cell.append('<div class="error price'+countRow+'"></div>');
	if($("#module_type").val() == 1){cell.attr("hidden", true);}

	var itemRemarkInput = $("<input/>",{type:"hidden",name:"itemData["+countRow+"][item_remark]",value:data.item_remark});
	cell = $(row.insertCell(-1));
	cell.html(data.item_remark);
	cell.append(itemRemarkInput);

    //Add Button cell.
	cell = $(row.insertCell(-1));
	var btnRemove = $('<button><i class="mdi mdi-trash-can-outline"></i></button>');
	btnRemove.attr("type", "button");
	btnRemove.attr("onclick", "Remove(this);");
	btnRemove.attr("style", "margin-left:4px;");
	btnRemove.attr("class", "btn btn-sm btn-outline-danger waves-effect waves-light");
	
	var btnEdit = $('<button><i class="mdi mdi-square-edit-outline"></i></button>');
	btnEdit.attr("type", "button");
	btnEdit.attr("onclick", "EditRow(" + JSON.stringify(data) + ",this);");
	btnEdit.attr("class", "btn btn-sm btn-outline-warning waves-effect waves-light");

	cell.append(btnEdit);
	cell.append(btnRemove);
	cell.attr("class", "text-center");
	cell.attr("style", "width:10%;");
}

function EditRow(data, button) {
	console.log(data);
	var row_index = $(button).closest("tr").index();
	$("#right_modal_lg").modal('show');
	$(".btn-close").hide();
	$.each(data, function (key, value) {
		$("#right_modal_lg #" + key).val(value);
	});
	initSelect2('right_modal_lg');
	$("#item_id").trigger('change');
	setTimeout(function(){ $("#order_unit").val(data.order_unit);initSelect2('right_modal_lg'); }, 100);
	$("#right_modal_lg #row_index").val(row_index);
}

function Remove(button) {
    var tableId = "salesOrderItems";
	//Determine the reference of the Row using the Button.
	var row = $(button).closest("TR");
	var table = $("#"+tableId)[0];
	table.deleteRow(row[0].rowIndex);
	$('#'+tableId+' tbody tr td:nth-child(1)').each(function (idx, ele) {
		ele.textContent = idx + 1;
	});
	var countTR = $('#'+tableId+' tbody tr:last').index() + 1;
	if (countTR == 0) {
		$("#tempItem").html('<tr id="noData"><td colspan="14" align="center">No data available in table</td></tr>');
	}

	// claculateColumn();
}

function resSaveOrder(data,formId){
    if(data.status==1){
        $('#'+formId)[0].reset();
		Swal.fire({ icon: 'success', title: data.message});
        window.location = base_url + controller;
    }else{
        if(typeof data.message === "object"){
            $(".error").html("");
            $.each( data.message, function( key, value ) {$("."+key).html(value);});
        }else{
			Swal.fire({ icon: 'error', title: data.message });
        }			
    }	
}

