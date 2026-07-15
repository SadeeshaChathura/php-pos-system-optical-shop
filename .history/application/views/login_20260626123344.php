<?php include "include/header.php"; ?>

<style>
html,body{
    height:100%;
    margin:0;
    font-family:'Segoe UI',sans-serif;
    overflow:hidden;
}

/* Animated background */
body{
    background: linear-gradient(-45deg,#0f2027,#203a43,#2c5364,#12c2e9);
    background-size: 400% 400%;
    animation: gradientBG 12s ease infinite;
}

@keyframes gradientBG{
    0%{background-position:0% 50%}
    50%{background-position:100% 50%}
    100%{background-position:0% 50%}
}

/* Main wrapper */
.login-wrapper{
    height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
}

/* Glass container */
.login-card{
    width:100%;
    max-width:1100px;
    height:600px;
    display:flex;
    border-radius:20px;
    overflow:hidden;
    backdrop-filter: blur(20px);
    background: rgba(255,255,255,0.08);
    box-shadow: 0 25px 60px rgba(0,0,0,0.3);
    animation: popIn 1s ease;
}

@keyframes popIn{
    from{transform:scale(0.9);opacity:0}
    to{transform:scale(1);opacity:1}
}

/* LEFT PANEL */
.left-panel{
    flex:1.2;
    color:#fff;
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
    padding:60px;
    text-align:center;
    animation: slideLeft 1.2s ease;
}

@keyframes slideLeft{
    from{transform:translateX(-60px);opacity:0}
    to{transform:translateX(0);opacity:1}
}

.left-panel img{
    width:220px;
    margin-bottom:20px;
    filter: drop-shadow(0 10px 20px rgba(0,0,0,0.4));
}

.left-panel h1{
    font-size:40px;
    margin-bottom:10px;
}

.left-panel p{
    opacity:.9;
}

/* RIGHT PANEL */
.right-panel{
    flex:1;
    background: rgba(255,255,255,0.12);
    display:flex;
    align-items:center;
    justify-content:center;
    animation: slideRight 1.2s ease;
}

@keyframes slideRight{
    from{transform:translateX(60px);opacity:0}
    to{transform:translateX(0);opacity:1}
}

/* Login box */
.form-box{
    width:80%;
    max-width:380px;
    color:#fff;
}

.form-box h3{
    text-align:center;
    margin-bottom:25px;
}

/* Inputs */
.form-control, .form-select{
    height:45px;
    border-radius:10px;
    border:none;
    margin-bottom:12px;
    padding:10px;
}

/* Button */
.btn-login{
    width:100%;
    height:45px;
    border:none;
    border-radius:10px;
    background: linear-gradient(45deg,#12c2e9,#c471ed,#f64f59);
    color:#fff;
    font-weight:bold;
    cursor:pointer;
    transition:0.4s;
    background-size: 200% 200%;
}

.btn-login:hover{
    background-position:right;
    transform:translateY(-2px);
    box-shadow:0 10px 20px rgba(0,0,0,0.3);
}

/* ========================= */
/* POWERED BY (UPDATED) */
/* ========================= */

.powered{
    text-align:center;
    margin-top:22px;
    opacity:.9;
}

.powered-wrap{
    display:flex;
    align-items:center;
    justify-content:center;
    gap:18px;
}

.powered-item img{
    height:34px;
    filter: drop-shadow(0 6px 10px rgba(0,0,0,0.3));
    transition:0.3s;
}

.powered-item img:hover{
    transform:scale(1.08);
}

.powered-text small{
    color:#fff;
    font-size:12px;
    opacity:.8;
    letter-spacing:1px;
}

/* Responsive */
@media(max-width:768px){
    .login-card{
        flex-direction:column;
        height:auto;
    }
    .left-panel{
        display:none;
    }
}
</style>

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

                <!-- UPDATED POWERED BY -->
                <div class="powered">

                    <div class="powered-wrap">

                        <div class="powered-item">
                            <img src="<?php echo base_url() ?>images/customer-logo.png">
                        </div>

                        <div class="powered-text">
                            <small>Powered by</small>
                        </div>

                        <div class="powered-item">
                            <img src="<?php echo base_url() ?>images/calcitex-logo.png">
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<?php include "include/footerscripts.php"; ?>