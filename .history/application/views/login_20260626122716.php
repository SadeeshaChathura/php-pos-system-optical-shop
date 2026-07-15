<?php include "include/header.php"; ?>

<div class="login-wrapper">

    <div class="login-card">

        <!-- LEFT SIDE -->
        <div class="left-panel">

            <img src="<?php echo base_url() ?>images/customer-logo.png">

            <h1>Welcome Back</h1>

            <p>Smart ERP Management System</p>

        </div>

        <!-- RIGHT SIDE -->
        <div class="right-panel">

            <div class="form-box">

                <h3>Sign In</h3>

                <form action="<?php echo base_url() ?>Welcome/LoginUser" method="post">

                    <input type="text" name="username" class="form-control" placeholder="Username">

                    <input type="password" name="password" class="form-control" placeholder="Password">

                    <select name="company" id="company" class="form-control">
                        <option value="">Select Company</option>
                        <?php foreach($companylist as $row){ ?>
                            <option value="<?php echo $row->idtbl_company ?>">
                                <?php echo $row->company ?>
                            </option>
                        <?php } ?>
                    </select>

                    <select name="branch" id="branch" class="form-control">
                        <option value="">Select Branch</option>
                    </select>

                    <button type="submit" class="btn-login">
                        LOGIN
                    </button>

                </form>

                <div class="powered">
                    <small>Powered by</small><br>
                    <img src="<?php echo base_url() ?>images/calcitex-logo.png">
                </div>

            </div>

        </div>

    </div>

</div>

<?php include "include/footerscripts.php"; ?>