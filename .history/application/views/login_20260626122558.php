<?php include "include/header.php"; ?>

<div class="login-wrapper">

    <!-- LEFT SIDE (CUSTOMER BRANDING) -->
    <div class="left-side">

        <img src="<?php echo base_url() ?>images/customer-logo.png" alt="Customer Logo">

        <h1>Welcome Back</h1>

        <p>Customer ERP Management System</p>

    </div>

    <!-- RIGHT SIDE (LOGIN FORM) -->
    <div class="right-side">

        <div class="login-box">

            <h3 class="mb-4 text-center">Login</h3>

            <form action="<?php echo base_url() ?>Welcome/LoginUser" method="post">

                <div class="form-group mb-2">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control">
                </div>

                <div class="form-group mb-2">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control">
                </div>

                <div class="form-group mb-2">
                    <label>Company</label>
                    <select name="company" id="company" class="form-control">
                        <option value="">Select</option>
                        <?php foreach($companylist as $row){ ?>
                            <option value="<?php echo $row->idtbl_company ?>">
                                <?php echo $row->company ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label>Branch</label>
                    <select name="branch" id="branch" class="form-control">
                        <option value="">Select</option>
                    </select>
                </div>

                <button type="submit" class="btn-login">
                    Login
                </button>

            </form>

            <!-- POWERED BY -->
            <div class="powered">

                <small class="text-muted">Powered by</small><br>

                <img src="<?php echo base_url() ?>images/calcitex-logo.png">

            </div>

        </div>

    </div>

</div>

<?php include "include/footerscripts.php"; ?>