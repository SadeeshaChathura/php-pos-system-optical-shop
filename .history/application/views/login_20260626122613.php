<?php include "include/header.php"; ?>

<style>
html,body{
    height:100%;
    margin:0;
    font-family:Segoe UI, sans-serif;
    background:#f4f7fb;
}

.login-wrapper{
    height:100vh;
    display:flex;
}

.left-side{
    flex:1.2;
    background:linear-gradient(135deg,#12c2e9,#0f5b78);
    color:#fff;
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
    padding:60px;
    text-align:center;
}

.left-side img{
    width:220px;
    margin-bottom:20px;
}

.left-side h1{
    font-size:38px;
    margin:10px 0;
}

.left-side p{
    opacity:.9;
    font-size:16px;
}

.right-side{
    flex:1;
    display:flex;
    justify-content:center;
    align-items:center;
    background:#fff;
}

.login-box{
    width:100%;
    max-width:420px;
    padding:40px;
}

.form-control, .form-select{
    height:45px;
    border-radius:8px;
}

.btn-login{
    width:100%;
    background:#12c2e9;
    border:none;
    height:45px;
    border-radius:8px;
    color:#fff;
    font-weight:bold;
}

.btn-login:hover{
    background:#0ea5c6;
}

.powered{
    text-align:center;
    margin-top:25px;
}

.powered img{
    height:35px;
}

@media(max-width:768px){
    .login-wrapper{
        flex-direction:column;
    }
    .left-side{
        display:none;
    }
}
</style>

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