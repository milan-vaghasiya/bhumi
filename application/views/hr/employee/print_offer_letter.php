<div style="padding:0px 10px;text-align:justify;font-family:Times New Roman;font-size:15px;">
	<table>
		<tr>
			<td style="width:100%;text-align:right;"><?= date("M d, Y")?></td>
		</tr>
		<tr>
			<td style="width:100%;">
				<p><?= $empData->emp_name;?></p>
				<p>Employee Code: <?= !empty($empData->emp_code) ? $empData->emp_code : "-";?></p>
				<p><?= $empData->emp_address;?></p>
				<p>Ph: <?= $empData->emp_contact;?></p><br/>
				<p>Dear <?= $empData->emp_name;?>,</p>
			</td>
		</tr>
	</table>
	
	<div style="text-align:center; margin: 10px 0;">
		<span style="border-bottom:1px solid #000;"><b>Subject: Offer of Employment</b></span>
	</div>
	
	<p>Congratulations! Further to your application for employment with us, and the subsequent selection process, we are delighted to offer you a Role of <b><?= $empData->department_name?></b>. Your Role Designation will be <b><?= $empData->designation_name?></b>.</p>
	
	<p>The location of your initial reporting will be <b><?= $empData->emp_address?></b>. The date of your joining would be <b><?= date("M d, Y",strtotime($empData->emp_joining_date))?></b>.</p>
	
	<p>Your Total Gross Salary as applicable has been detailed below to this letter. On your joining, you are expected to enter into an agreement, which details the scope, terms and conditions of your employment, the necessary contractual obligation to be with <b><?= $companyData->company_name?></b>. On successful completion of the probation, your employment with the company will stand confirmed subject to the terms and conditions as per Company policies.</p>
	
	<p>Company will solely reserve the right to make any further changes to the date of joining.</p>
	
	<p>Your employment will be governed by the rules, regulations and policies of the Company.</p>
	
	<p>The terms of this offer letter shall remain confidential and are not to be disclosed to any third party.</p>
	
	<p>We would request you to please carry a signed copy of the offer letter on the day of your joining as a token of your acceptance.</p>
	
	<p>Welcome to <b><?= $companyData->company_name?></b> We wish you a long, rewarding and fulfilling career and look forward to your joining us.</p>
	
	<p>Yours sincerely,</p><br/>
	
	<p><b>ANIKET KULKARNI</b><br/>GROUP HEAD - HUMAN RESOURCES</p>
	
	<small>I have read, understood and agree to the terms and conditions as set forth in this offer letter and the annexure to the same.</small><br/><br/>
	
	<table>
		<tr>
			<td style="width:10%;">Date:</td>
			<td style="width:25%;" style="border-bottom:1px solid #000;"></td>
			<td style="width:15%;"></td>
			<td style="width:25%;" style="border-bottom:1px solid #000;"></td>
			<td style="width:10%;"></td>
		</tr>
		<tr>
			<td style="width:10%;"></td>
			<td style="width:25%;"></td>
			<td style="width:15%;"></td>
			<td style="width:25%;" class="text-center">Candidate Name (capital letters)</td>
			<td style="width:10%;"></td>
		</tr>
	</table>
	<table style="margin-top:35px;">
		<tr>
			<td style="width:10%;">Location:</td>
			<td style="width:25%;" style="border-bottom:1px solid #000;"></td>
			<td style="width:15%;"></td>
			<td style="width:25%;" style="border-bottom:1px solid #000;"></td>
			<td style="width:10%;"></td>
		</tr>
		<tr>
			<td style="width:10%;"></td>
			<td style="width:25%;"></td>
			<td style="width:15%;"></td>
			<td style="width:25%;" class="text-center">Candidate Signature</td>
			<td style="width:10%;"></td>
		</tr>
	</table>
</div>