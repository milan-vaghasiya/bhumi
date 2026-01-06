<form>
    <div class="col-md-12">
        <div class="row">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th>Lead Name</th>
                        <td colspan="3"><?=(!empty($leadData->party_name) ? $leadData->party_name : '')?></td>
                    </tr>
                    <tr>
                        <th style="width:25%">Contact Person</th>
                        <td style="width:25%"><?=(!empty($leadData->contact_person) ? $leadData->contact_person : '')?></td>
                        <th style="width:25%">Contact No.</th>
                        <td style="width:25%"><?=(!empty($leadData->contact_phone) ? $leadData->contact_phone : '')?></td>
                    </tr>
                    <tr>
                        <th>Whatsapp No.</th>
                        <td><?=(!empty($leadData->whatsapp_no) ? $leadData->whatsapp_no : '')?></td>
                        <th>State</th>
                        <td><?=(!empty($leadData->state) ? $leadData->state : '')?></td>
                    </tr>
                    <tr>
                        <th>District</th>
                        <td><?=(!empty($leadData->district) ? $leadData->district : '')?></td>
                        <th>Taluka</th>
                        <td><?=(!empty($leadData->taluka) ? $leadData->taluka : '')?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</form>