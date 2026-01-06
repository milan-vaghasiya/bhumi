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
						<td style="width:33%;" class="fs-18 text-center">Sales Order</td>
						<td style="width:33%;" class="fs-18 text-right"></td>
					</tr>
				</table>               
                
                <table class="table item-list-bb fs-22" style="margin-top:5px;">
                    <tr >
                        <td rowspan="4" style="width:67%;vertical-align:top;">
                            <b>M/S. <?=$partyData->party_name?></b><br>
                            <?=(!empty($dataRow->ship_address) ? $dataRow->ship_address ." - ".$dataRow->ship_pincode : '')?><br>
                            <b>Kind. Attn. : <?=$partyData->contact_person?></b> <br>
                            Contact No. : <?=$partyData->contact_phone?><br>
                            Email : <?=$partyData->party_email?><br><br>
                            GSTIN : <?=$dataRow->gstin?>
                        </td>
                        <td>
                            <b>SO. No.</b>
                        </td>
                        <td>
                            <?=$dataRow->trans_number?>
                        </td>
                    </tr>
                    <tr>
				        <th class="text-left">SO Date</th>
                        <td><?=formatDate($dataRow->trans_date)?></td>
                    </tr>
                    <tr>
                        <th class="text-left">Cust PO. No.</th>
                        <td><?=$dataRow->doc_no?></td>
                    </tr>
                    <tr>
                        <th class="text-left">Cust PO. Date</th>
                        <td><?=(!empty($dataRow->doc_date)) ? formatDate($dataRow->doc_date) : ""?></td>
                    </tr>
                </table>
                
                <table class="table item-list-bb" style="margin-top:10px;">
					<thead>
						<tr>
							<th style="width:5%;">No.</th>
							<th class="text-left" style="width:45%">Item Description</th>
							<th style="width:15%;">Qty</th>
							<th style="width:10%;">Rate</th>
                            <th style="width:10%;">GST <small>(%)</small></th>
							<th style="width:15%;">Taxable Amount</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$i=1;$totalQty = 0;$taxable_amount=0;$sub_total=0;$gstAmt=0;
							if(!empty($dataRow->itemList)):
								foreach($dataRow->itemList as $row):
									$taxable_amount = $row->qty * $row->price;
									$gstAmt = ($taxable_amount * $row->gst_per) / 100;	
                                    if(!empty($row->gst_per)){
                                        $taxable_amount = $gstAmt + $taxable_amount;
                                    }

									$indent = (!empty($row->ref_id)) ? '<br>Reference No:'.$row->ref_number : '';
									$delivery_date = (!empty($row->delivery_date)) ? '<br>Delivery Date :'.formatDate($row->delivery_date) : '';
									
									$item_remark=(!empty($row->item_remark))?'<br><small>Remark:.'.$row->item_remark.'</small>':'';
									
									echo '<tr>';
										echo '<td class="text-center" rowspan="2">'.$i++.'</td>';
										echo '<td>'.$row->item_name.$indent.$delivery_date.'</td>';
										echo '<td class="text-right">'.floatval($row->qty).' <small>'.$row->unit_name.'</small></td>';
										echo '<td class="text-center">'.$row->price.'</td>';
										echo '<td class="text-center">'.$row->gst_per.'</td>';
										echo '<td class="text-right">'.floatval($taxable_amount).'</td>';
									echo '</tr>';
									echo '<tr><td colspan="5"><i>Notes:</i> '.$row->item_remark.'</td></tr>';
									$totalQty += $row->qty;
									$sub_total += $taxable_amount;
								endforeach;
							endif;
						?>
						<tr>
							<th colspan="2" class="text-right">Total Qty.</th>
							<th class="text-right"><?=floatval($totalQty)?></th>
							<th colspan="2" class="text-right">Total Amount</th>
							<th class="text-right"><?=floatval($sub_total)?></th>
						</tr>
						<tr>
							<th class="text-left" colspan="6">
								<b>Note: </b> <?= $dataRow->remark?>
							</th>
						</tr>
						<tr>
							<th class="text-left" colspan="6">
								Amount In Words : <?=numToWordEnglish($sub_total)?>
							</th>
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
							<td style="width:50%;" rowspan="4"></td>
							<th colspan="2">For, <?=$companyData->company_name?></th>
						</tr>
						<tr>
							<td style="width:25%;" class="text-center"><?=$dataRow->prepareBy?></td>
							<td style="width:25%;" class="text-center"><?=$dataRow->approveBy?>'</td>
						</tr>
						<tr>
							<td style="width:25%;" class="text-center"><b>Prepared By</b></td>
							<td style="width:25%;" class="text-center"><b>Authorised By</b></td>
						</tr>
					</table>
					<table class="table top-table" style="margin-top:10px;border-top:1px solid #545454;">
						<tr>
							<td style="width:25%;">SO No. & Date : <?=$dataRow->trans_number.' ['.formatDate($dataRow->trans_date).']'?></td>
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