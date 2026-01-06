<form">
    <?php
    $profile_pic = 'male_user.png';
    if(!empty($empData->emp_profile)){$profile_pic = $empData->emp_profile;}
    else
    {
        if(!empty($empData->emp_gender) and $empData->emp_gender=="Female"):
            $profile_pic = 'female_user.png';
        else:
            $profile_pic = 'male_user.png';
        endif;
    }
    ?>
    <div class="row">
        <div class="col-lg-4 text-center align-self-center">
            <div class="met-profile-main ">
                <div class="met-profile-main-pic text-center ">
                    <img id="profilePic" class="rounded-circle" height="110" src="<?= base_url('assets/uploads/emp_profile/'.$profile_pic) ?>">
                </div>
            </div>
            <h4 class="text-center p-1" ><?= (!empty($empData->emp_code)) ? $empData->emp_code : "-"; ?></h4>

        </div><!--end col-->
        <div class="col-lg-8">
            <h5><?= (!empty($empData->emp_name)) ? $empData->emp_name : "-"; ?></h5>
            <p class="text-muted"><strong>Phone</strong> : <?= (!empty($empData->emp_contact)) ? $empData->emp_contact : "-"; ?></p>

            <p class="text-muted"><strong>Gender</strong> : <?= (!empty($empData->emp_gender)) ? $empData->emp_gender : "-" ?></p>

            <p class="text-muted"><strong>Date Of Birth</strong> : <?= (!empty($empData->emp_birthdate)) ? date("d-m-Y", strtotime($empData->emp_birthdate)) : "-"; ?></p>

            <p class="text-muted"><strong>Joining Date</strong> : <?= (!empty($empData->emp_joining_date)) ? date("d-m-Y", strtotime($empData->emp_joining_date)) : "-"; ?></p>
        </div><!--end col-->
    </div>
</form>	