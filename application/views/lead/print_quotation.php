<html>
    <body>
        <div class="row">
            <div class="col-12">
                <table>
                    <tr>
                        <td>
                            <img src="<?=$letter_head?>" class="img">
                        </td>
                    </tr>
                </table>

                <table class="table bg-light-grey">
                    <tr class="" style="letter-spacing: 2px;font-weight:bold;padding:2px !important; border-bottom:1px solid #000000;">
                        <td style="width:33%;" class="fs-18 text-left">
                            GSTIN: <?=$companyData->company_gst_no?>
                        </td>
                        <td style="width:33%;" class="fs-18 text-center">Sales Quotation</td>
                        <td style="width:33%;" class="fs-18 text-right"></td>
                    </tr>
                </table>
                
                <table class="table item-list-bb fs-22" style="margin-top:5px;">
                    <tr>
                        <td style="width:60%; vertical-align:top;">
                            <b>M/S. <?=$partyData->party_name?></b><br>
                            <?=(!empty($dataRow->ship_address) ? $dataRow->ship_address ." - ".$dataRow->ship_pincode : '')?><br>
                            <b>Kind. Attn. : <?=$partyData->contact_person?></b> <br>
                            <b>Contact No. : </b><?=$partyData->contact_phone?><br>
                            <b>Email : </b><?=$partyData->party_email?><br><br>
                        </td>
                        <td style="width:40%; vertical-align:top;">
                            <b>Qtn. No.</b> : <?=$dataRow->trans_number?><br>
                            <b>Qtn. Date</b> : <?=formatDate($dataRow->trans_date)?><br>
                            <b>Valid till</b> : <?=formatDate($dataRow->delivery_date)?><br>
                            <b>GSTIN : <?=(!empty($partyData->gstin)) ? $partyData->gstin : ""?></b>
                        </td>
                    </tr>
                </table>
                
                <table class="table item-list-bb" style="margin-top:10px;">
                    <thead>
                        <tr>
                            <th style="width:40px;">No.</th>
                            <th class="text-left">Item Description</th>
                            <th style="width:100px;">Qty</th>
                            <th style="width:60px;">Rate<br><small>(<?=(!empty($partyData->currency) ? $partyData->currency : "INR")?>)</small></th>
                            <th style="width:60px;">GST <small>(%)</small></th>
                            <th style="width:110px;">Amount<br><small>(<?=(!empty($partyData->currency) ? $partyData->currency : "INR")?>)</small></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $i=1;$totalQty = 0;$migst=0;$mcgst=0;$msgst=0;$taxable_amount=0;$sub_total=0;$gstAmt=0;
                            if(!empty($dataRow->itemList)):
                                foreach($dataRow->itemList as $row):	
                                    $taxable_amount = $row->qty * $row->price;	
                                    $gstAmt = ($taxable_amount * $row->gst_per) / 100;	
                                    if(!empty($row->gst_per)){
                                        $taxable_amount = $gstAmt + $taxable_amount;
                                    }
                                    echo '<tr>';
                                        echo '<td class="text-center">'.$i++.'</td>';
                                        echo '<td>'.$row->item_name.'</td>';
                                        echo '<td class="text-right">'.floatVal($row->qty).' <small>'.$row->unit_name.'</small></td>';
                                        echo '<td class="text-right">'.floatVal($row->price).'</td>';
                                        echo '<td class="text-center">'.floatval($row->gst_per).' %</td>';
                                        echo '<td class="text-right">'.$taxable_amount.'</td>';
                                    echo '</tr>';
                                    
                                    $totalQty += $row->qty;
                                    if($row->gst_per > $migst){$migst=$row->gst_per;$mcgst=$row->cgst_per;$msgst=$row->sgst_per;}
                                    $sub_total += $taxable_amount;
                                endforeach;
                            endif;

                            $blankLines = (15 - $i);
                            if($blankLines > 0):
                                for($j=1;$j<=$blankLines;$j++):
                                    echo '<tr>
                                        <td style="border-top:none;border-bottom:none;">&nbsp;</td>
                                        <td style="border-top:none;border-bottom:none;"></td>
                                        <td style="border-top:none;border-bottom:none;"></td>
                                        <td style="border-top:none;border-bottom:none;"></td>
                                        <td style="border-top:none;border-bottom:none;"></td>
                                        <td style="border-top:none;border-bottom:none;"></td>
                                    </tr>';
                                endfor;
                            endif;
                        ?>
                        <tr>
                            <th colspan="2" class="text-right">Total Qty.</th>
                            <th class="text-right"><?=floatval($totalQty)?></th>
                            <th colspan="2" class="text-right">Total Amount</th>
                            <th class="text-right"><?=($sub_total)?></th>
                        </tr>
                        </tr>
                    
                    </tbody>
                </table>

                <table class="table item-list-bb" style="margin-top:10px;">
					<tr>
						<th class="text-left">Terms & Conditions : </th>						
					</tr>
					<tr>
						<td><?=(!empty($dataRow->conditions) ? $dataRow->conditions : "")?></td>
					</tr>
				</table>
                
                <htmlpagefooter name="lastpage">
                    <table class="table top-table" style="margin-top:10px;border-top:1px solid #545454;">
                        <tr>
                            <td style="width:50%;"></td>
                            <td style="width:20%;"></td>
                            <th class="text-center">For, <?=$companyData->company_name?></th>
                        </tr>
                        <tr>
                            <td colspan="3" height="50"></td>
                        </tr>
                        <tr>
                            <td><br>This is a computer-generated quotation.</td>
                            <td class="text-center"><?=$dataRow->created_name?><br>Prepared By</td>
                            <td class="text-center"><br>Authorised By</td>
                        </tr>
                    </table>
                    <table class="table top-table" style="margin-top:10px;border-top:1px solid #545454;">
						<tr>
							<td style="width:25%;">SQ No. & Date : <?=$dataRow->trans_number.' ['.formatDate($dataRow->trans_date).']'?></td>
							<td style="width:25%;"></td>
							<td style="width:25%;text-align:right;">Page No. {PAGENO}/{nbpg}</td>
						</tr>
					</table>
                </htmlpagefooter>
				<sethtmlpagefooter name="lastpage" value="on" /> 
            </div>
        </div>        
    </body>
</html>