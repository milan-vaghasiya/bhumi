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
						<td class="fs-18 text-center">Sales Enquiry</td>
					</tr>
				</table>               
                
                <table class="table item-list-bb fs-22" style="margin-top:5px;">
                    <tr >
                        <td rowspan="2" style="width:67%;vertical-align:top;">
                            <b>M/S. <?=$partyData->party_name?></b><br>
                            <?=(!empty($dataRow->ship_address) ? $dataRow->ship_address ." - ".$dataRow->ship_pincode : '')?><br>
                            <b>Kind. Attn. : <?=$partyData->contact_person?></b> <br>
                            Contact No. : <?=$partyData->contact_phone?><br>
                            Email : <?=$partyData->party_email?><br><br>
                            <b>GSTIN : <?=$partyData->gstin?></b>
                        </td>
                        <td>
                            <b>SE. No.</b>
                        </td>
                        <td>
                            <?=$dataRow->trans_number?>
                        </td>
                    </tr>
                    <tr>
				        <th class="text-left">SE Date</th>
                        <td><?=formatDate($dataRow->trans_date)?></td>
                    </tr>
                </table>
                
                <table class="table item-list-bb" style="margin-top:10px;">
					<thead>
						<tr>
							<th>No.</th>
							<th class="text-left">Item Description</th>
							<th>Qty</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$i=1;$totalQty = 0;
							if(!empty($dataRow->itemList)):
								foreach($dataRow->itemList as $row):
									$indent = (!empty($row->ref_id)) ? '<br>Reference No:'.$row->ref_number : '';
									$delivery_date = (!empty($row->delivery_date)) ? '<br>Delivery Date :'.formatDate($row->delivery_date) : '';
									
									$item_remark=(!empty($row->item_remark))?'<br><small>Remark:.'.$row->item_remark.'</small>':'';
									
									echo '<tr>';
										echo '<td class="text-center">'.$i++.'</td>';
										echo '<td>'.$row->item_name.$indent.$delivery_date.'<br><i>Notes:</i> '.$row->item_remark.'</td>';
										echo '<td class="text-right">'.floatval($row->qty).' <small>'.$row->unit_name.'</small></td>';
									echo '</tr>';
									$totalQty += $row->qty;
								endforeach;
							endif;
						?>
						<tr>
							<th colspan="2" class="text-right">Total Qty.</th>
							<th class="text-right"><?=floatval($totalQty)?></th>
						</tr>
					</tbody>
                </table>
                
				<htmlpagefooter name="lastpage">
					<table class="table top-table" style="margin-top:10px;border-top:1px solid #545454;">
						<tr>
							<td style="width:50%;" rowspan="4"></td>
							<th colspan="2">For, <?=$companyData->company_name?></th>
						</tr>
						<tr>
							<td style="width:25%;" class="text-center"><?=$dataRow->prepareBy?></td>
							<td style="width:25%;" class="text-center"><?=$dataRow->approveBy?></td>
						</tr>
						<tr>
							<td style="width:25%;" class="text-center"><b>Prepared By</b></td>
							<td style="width:25%;" class="text-center"><b>Authorised By</b></td>
						</tr>
					</table>
					<table class="table top-table" style="margin-top:10px;border-top:1px solid #545454;">
						<tr>
							<td style="width:25%;">SE No. & Date : <?=$dataRow->trans_number.' ['.formatDate($dataRow->trans_date).']'?></td>
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
