
<ul class="dz-list message-list">
    <?php
    $wa_text = urlencode('Hello');
    if(!empty($dataRow->contact_person) || !empty($dataRow->contact_phone)){
        //Contact No
        $no ="'tel: ".$dataRow->contact_phone."'";
        $contact_no = (!empty($dataRow->contact_phone) ? str_replace('-','',str_replace('+','',str_replace(' ','',$dataRow->contact_phone))) : '');
        //Whatsapp No
        $wno ="'tel: ".$dataRow->whatsapp_no."'";
        $ws_no = (!empty($dataRow->whatsapp_no) ? str_replace('-','',str_replace('+','',str_replace(' ','',$dataRow->whatsapp_no))) : '');
        ?>
        <li class="istItem">
            <a href="javascript:void(0)" class="position-relative">
                <div class="media-content">
                    <div>
                        <h6 class="name"><?=$dataRow->contact_person?></h6>
                        <span class="name">Contact No. : <?=$dataRow->contact_phone?></span><br>
                        <span class="name">Whatsapp No. : <?=$dataRow->whatsapp_no?></span>

                    </div>
                </div>
            </a>
            <div class="left-content">
                <div class="d-flex mt-2">
                    <a role="button" href="https://wa.me/<?=$contact_no?>/?text='<?=$wa_text?>" target="_blank" class="text-left p-1" ><i class="fab fa-whatsapp text-success font-20" ></i></a>
                    <a role="button" href="javascript:void(0)" class=" text-left p-1" onclick="document.location.href = <?=$no?>"><i class="fas fa-phone text-primary font-20"  ></i></a>
                </div>
                <div class="d-flex mt-2">
                    <a role="button" href="https://wa.me/<?=$ws_no?>/?text='<?=$wa_text?>" target="_blank" class="text-left p-1" ><i class="fab fa-whatsapp text-success font-20" ></i></a>
                    <a role="button" href="javascript:void(0)" class=" text-left p-1" onclick="document.location.href = <?=$wno?>"><i class="fas fa-phone text-primary font-20"  ></i></a>
                </div>
            </div>
        </li>
        <?php
    }
    ?>       
</ul>
    