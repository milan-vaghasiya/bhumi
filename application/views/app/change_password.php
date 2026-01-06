
<div class="offcanvas offcanvas-bottom m-3 rounded" tabindex="-1" id="change-psw" aria-modal="true" role="dialog">
    <div class="offcanvas-body small">
        <form id="changePSW">
            <div class="mb-3">
                <label for="old_password">Old Password</label>
                <input type="password" name="old_password" id="old_password" class="form-control" placeholder="Old Password" />
                <div class="error old_password"></div>
            </div>

            <div class="mb-3">
                <label for="new_password">New Password</label>
                <div class="input-group"> 
                    <input type="password" name="new_password" id="new_password" class="form-control pswType" placeholder="Enter Password" value="">
                    <div class="input-group-append">
                        <button type="button" class="btn waves-effect waves-light btn-outline-primary pswHideShow"><i class="fa fa-eye"></i></button>
                    </div>
                </div>
                <div class="error new_password"></div>
            </div>

            <div class="mb-3">
                <label for="cpassword">Confirm Password</label>
                <input type="text" name="cpassword" id="cpassword" class="form-control" placeholder="Confirm Password" />
                <div class="error cpassword"></div>
            </div>

            <div class="">
                <button type="button" class="btn btn-success btn-round btn-outline-dashed btn-block changePsw">Save</button>
            </div>
        </form>
    </div>
</div>

