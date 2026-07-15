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

/* Floating particles effect */
body::before{
    content:"";
    position:absolute;
    width:100%;
    height:100%;
    background-image: radial-gradient(rgba(255,255,255,0.08) 1px, transparent 1px);
    background-size: 50px 50px;
    animation: floatBG 20s linear infinite;
    pointer-events:none;
}

@keyframes floatBG{
    from{transform:translateY(0px)}
    to{transform:translateY(-200px)}
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
    border-radius:22px;
    overflow:hidden;
    backdrop-filter: blur(25px);
    background: rgba(255,255,255,0.08);
    box-shadow: 0 30px 80px rgba(0,0,0,0.35);
    animation: popIn 0.9s ease;
    border:1px solid rgba(255,255,255,0.1);
}

@keyframes popIn{
    from{transform:scale(0.92);opacity:0}
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
    animation: slideLeft 1s ease;
}

@keyframes slideLeft{
    from{transform:translateX(-80px);opacity:0}
    to{transform:translateX(0);opacity:1}
}

.left-panel img{
    width:210px;
    margin-bottom:20px;
    filter: drop-shadow(0 15px 25px rgba(0,0,0,0.5));
    transition:0.3s;
}

.left-panel img:hover{
    transform:scale(1.05);
}

.left-panel h1{
    font-size:42px;
    margin-bottom:10px;
    font-weight:700;
    letter-spacing:1px;
}

.left-panel p{
    opacity:.85;
    font-size:16px;
}

/* RIGHT PANEL */
.right-panel{
    flex:1;
    background: rgba(255,255,255,0.12);
    display:flex;
    align-items:center;
    justify-content:center;
    animation: slideRight 1s ease;
}

@keyframes slideRight{
    from{transform:translateX(80px);opacity:0}
    to{transform:translateX(0);opacity:1}
}

/* FORM BOX */
.form-box{
    width:80%;
    max-width:380px;
    color:#fff;
}

.form-box h3{
    text-align:center;
    margin-bottom:25px;
    font-weight:600;
    letter-spacing:1px;
}

/* INPUTS */
.form-control, .form-select{
    height:46px;
    border-radius:12px;
    border:none;
    margin-bottom:14px;
    padding:10px 14px;
    outline:none;
    transition:0.3s;
    background: rgba(255,255,255,0.9);
}

.form-control:focus, .form-select:focus{
    box-shadow:0 0 0 3px rgba(18,194,233,0.3);
    transform:scale(1.01);
}

/* BUTTON */
.btn-login{
    width:100%;
    height:46px;
    border:none;
    border-radius:12px;
    background: linear-gradient(45deg,#12c2e9,#c471ed,#f64f59);
    color:#fff;
    font-weight:bold;
    cursor:pointer;
    transition:0.4s;
    background-size: 200% 200%;
    letter-spacing:1px;
}

.btn-login:hover{
    background-position:right;
    transform:translateY(-2px);
    box-shadow:0 15px 25px rgba(0,0,0,0.35);
}

/* Powered by */
.powered{
    text-align:center;
    margin-top:22px;
    opacity:.85;
}

.powered img{
    height:34px;
    margin-top:6px;
    filter: drop-shadow(0 8px 15px rgba(0,0,0,0.3));
}

/* Responsive */
@media(max-width:768px){
    .login-card{
        flex-direction:column;
        height:auto;
        margin:20px;
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

                    <select name="company" id="company" class="form-select">
                        <option value="">Select Company</option>
                        <?php foreach($companylist as $row){ ?>
                            <option value="<?php echo $row->idtbl_company ?>">
                                <?php echo $row->company ?>
                            </option>
                        <?php } ?>
                    </select>

                    <select name="branch" id="branch" class="form-select">
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