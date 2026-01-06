<html>
    <body>
        <div class="row">
            <div class="col-12">

                <table class="table item-list-bb" style="margin-top:5px;">
                    <tr>
                        <td colspan='3' rowspan='2' style='width:40%;font-size:1.5rem;' class='text-center'> <?=$companyData->company_name?> </td>
                        <td colspan='5' style='width:60%;' class='text-center'>TRAVELLING EXPENSES BILL</td>
                    </tr>
                    <tr>
                        <td colspan='2' style='width:25%;'> Name : <?=$empData->emp_name?> </td>
                        <td colspan='2' style='width:25%;'> Designation : <?=$empData->designation_name?> </td>
                        <td colspan='1' style='width:10%;'> Checked : </td>
                    </tr>
                    <tr>
                        <td rowspan='3' class='text-center' style='width:15%;vertical-align:top;'> DAILY ALLOWANCE WITHOUT BILLS </td>
                        <td rowspan='3' class='text-center' style='width:15%;vertical-align:top;'> HALTING ALLOWANCE WITH BILLS </td>
                        <td rowspan='3' class='text-center' style='width:10%;vertical-align:top;'> SIGNATURE </td>
                        <td class='text-center' style='width:14%;'>TRAVEL DETAILS</td>
                        <td class='text-center' style='width:13%;'>DATE</td>
                        <td class='text-center' style='width:13%;'>TIME</td>
                        <td rowspan='3' class='text-center' style='width:10%;vertical-align:top;'>VERIFIED</td>
                        <td rowspan='3' class='text-center' style='width:10%;vertical-align:top;'>APPROVED</td>
                    </tr>
                    <tr>
                        <td class='text-center'>COMMENCED ON</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class='text-center'>COMPLETED</td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>

                <table class="table item-list-bb" style="margin-top:10px;">
                    <?=$tableData?>
                </table>

                <!-- <table class="table item-list-bb" style="margin-top:10px;">
                    <tr>
                        <td colspan='4'>NATURE OF BUSINESS</td>
                        <td colspan='3' class='text-center'>VISIT REPORT NO.</td>
                        <td colspan='5' class='text-center'>ADVANCE DRAWN/COST PAID BY THE COMPANY ETC.</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <td colspan='7'>JOINT TOURE &nbsp;&nbsp;&nbsp; <input type="checkbox"> YES <input type="checkbox"> NO </td>
                        <td colspan='3'></td>
                        <td colspan='2' rowspan='2' class='text-center' style='font-size:1.5rem'>Grand Total</td>
                        <td rowspan='2'>0</td>
                    </tr>
                    <tr>
                        <td colspan='7'>if yes with whom: </td>
                        <td colspan='3'></td>
                    </tr>
                    <tr>
                        <td colspan='7'></td>
                        <td colspan='6'>BALANCE REFUNDABLE TO COMPANY / EMPLOYEE</td>
                    </tr>
                    <tr>
                        <td colspan='7'></td>
                        <td colspan='6' class='text-center' style='vertical-align:bottom;'>Bill settled vide Receipt No./Pay Order No...................................Date...................................</td>
                    </tr>
                </table> -->

            </div>
        </div>        
    </body>
</html>
