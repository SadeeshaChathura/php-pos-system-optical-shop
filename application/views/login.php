<?php include "include/header.php"; ?>

<div class="login-wrapper">

    <div class="login-card">

        <!-- LEFT SIDE -->
        <div class="left-panel">

            <div class="brand-mid">
                <img src="<?php echo base_url() ?>images/QP.png" class="brand-logo">
                <h1>Run your business,<br>all in one place.</h1>
                <p>Multi-branch ERP for inventory, sales, finance and reporting built for teams that move fast.</p>
            </div>

            <div class="left-footer">
                <span class="copyright">&copy; <?php echo date('Y'); ?> QuantumPay ERP</span>
                <div class="powered">
                    <small>Powered by</small>
                    <small>calcite X Global</small>
                </div>
            </div>

        </div>

        <!-- RIGHT SIDE -->
        <div class="right-panel">

            <div class="form-box">

                <div class="form-header">
                    <h2>Welcome back</h2>
                    <p>Sign in to continue to your dashboard</p>
                </div>

                <form action="<?php echo base_url() ?>Welcome/LoginUser" method="post" id="loginForm">

                    <div class="qp-field">
                        <label for="username">Username</label>
                        <div class="input-wrap">
                            <svg class="input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                            <input type="text" id="username" name="username" class="form-control" placeholder="Enter your username" autocomplete="username" required>
                        </div>
                    </div>

                    <div class="qp-field">
                        <label for="password">Password</label>
                        <div class="input-wrap">
                            <svg class="input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="11" width="18" height="11" rx="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                            <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" autocomplete="current-password" required>
                            <button type="button" class="toggle-password" id="togglePassword" aria-label="Show password">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="qp-row">
                        <div class="qp-field">
                            <label for="company">Company</label>
                            <select name="company" id="company" class="form-control" required>
                                <option value="">Select Company</option>
                                <?php foreach($companylist as $row){ ?>
                                    <option value="<?php echo $row->idtbl_company ?>">
                                        <?php echo $row->company ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="qp-field">
                            <label for="branch">Branch</label>
                            <select name="branch" id="branch" class="form-control" required>
                                <option value="">Select Branch</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-options">
                        <label class="checkbox-wrap">
                            <input type="checkbox" name="remember" id="remember">
                            <span>Remember me</span>
                        </label>
                        <a href="#" class="forgot-link">Forgot password?</a>
                    </div>

                    <button type="submit" class="btn-login">
                        Sign In
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

<style>
:root{
    --qp-primary:#4f46e5;
    --qp-primary-dark:#4338ca;
    --qp-bg:#f4f5f9;
    --qp-panel-dark:#0d1220;
    --qp-text:#1f2330;
    --qp-text-muted:#6b7280;
    --qp-border:#e3e5ec;
    --qp-radius:16px;
}

html,body{
    height:100%;
    margin:0;
    font-family:'Segoe UI',system-ui,-apple-system,sans-serif;
    background:var(--qp-bg);
}

.login-wrapper{
    min-height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:24px;
    box-sizing:border-box;
}

.login-card{
    width:100%;
    max-width:1040px;
    min-height:640px;
    display:flex;
    border-radius:var(--qp-radius);
    overflow:hidden;
    background:#fff;
    box-shadow:0 20px 50px rgba(17,24,39,0.12);
    animation:fadeUp .5s ease;
}

@keyframes fadeUp{
    from{opacity:0;transform:translateY(16px)}
    to{opacity:1;transform:translateY(0)}
}

/* LEFT PANEL */
.left-panel{
    flex:1.05;
    background:
        radial-gradient(circle at 85% 8%, rgba(79,70,229,0.45), transparent 45%),
        radial-gradient(circle at -10% 95%, rgba(99,102,241,0.25), transparent 40%),
        linear-gradient(165deg,var(--qp-panel-dark) 0%,#161d30 100%);
    color:#fff;
    display:flex;
    flex-direction:column;
    justify-content:space-between;
    padding:44px 48px 36px;
    position:relative;
    overflow:hidden;
    box-sizing:border-box;
}

.left-panel::after{
    content:'';
    position:absolute;
    inset:0;
    background-image: radial-gradient(rgba(255,255,255,0.07) 1px, transparent 1px);
    background-size: 22px 22px;
    mask-image: linear-gradient(180deg, rgba(0,0,0,0.9), rgba(0,0,0,0.15));
    pointer-events:none;
}

.brand-mid{
    position:relative;
    z-index:1;
    margin:auto;
    max-width:400px;
    text-align:center;
    display:flex;
    flex-direction:column;
    align-items:center;
}

.brand-logo{
    height:80px;
    width:auto;
    object-fit:contain;
    margin-bottom:28px;
}

.brand-mid h1{
    font-size:36px;
    font-weight:700;
    line-height:1.25;
    margin:0 0 14px;
    letter-spacing:-0.3px;
}

.brand-mid p{
    color:#9ca3af;
    font-size:12px;
    line-height:1.2;
    width:75%;
    margin:0;
}

.left-footer{
    position:relative;
    z-index:1;
    display:flex;
    flex-direction:row;
    align-items:center;
    justify-content:space-between;
    gap:14px;
    padding-top:20px;
    margin-bottom:14px;
    border-top:1px solid rgba(255,255,255,0.1);
}

.powered{
    display:flex;
    align-items:center;
    gap:8px;
}

.powered small{
    color:#9ca3af;
    font-size:11px;
    letter-spacing:1px;
    text-transform:uppercase;
    white-space:nowrap;
}

.powered small:last-child{
    color:#e5e7eb;
    font-weight:600;
}

.powered-logo{
    height:20px;
    width:auto;
}

.copyright{
    font-size:12px;
    color:#6b7280;
    white-space:nowrap;
}

/* RIGHT PANEL */
.right-panel{
    flex:1;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:48px;
    box-sizing:border-box;
}

.form-box{
    width:100%;
    max-width:380px;
}

.form-header{
    margin-bottom:28px;
}

.form-header h2{
    font-size:24px;
    font-weight:700;
    color:var(--qp-text);
    margin:0 0 6px;
    line-height:1.3;
}

.form-header p{
    color:var(--qp-text-muted);
    font-size:14px;
    margin:0;
}

.qp-field{
    margin:0 0 18px 0;
    flex:1;
    min-width:0;
}

.qp-row{
    display:flex;
    gap:12px;
    margin:0 0 18px 0;
}

.qp-row .qp-field{
    margin-bottom:0;
}

.qp-field label{
    display:block;
    width:100%;
    font-size:13px;
    font-weight:600;
    color:var(--qp-text);
    margin:0 0 8px 0;
    padding:0;
    line-height:1.2;
}

.input-wrap{
    position:relative;
    display:block;
    width:100%;
}

.input-icon{
    position:absolute;
    top:50%;
    left:14px;
    transform:translateY(-50%);
    color:var(--qp-text-muted);
    pointer-events:none;
}

.form-control{
    display:block;
    width:100%;
    height:46px;
    border-radius:10px;
    border:1.5px solid var(--qp-border);
    padding:10px 14px;
    margin:0;
    font-size:14px;
    color:var(--qp-text);
    background:#fafafa;
    box-sizing:border-box;
    transition:border-color .2s, box-shadow .2s, background .2s;
}

.input-wrap .form-control{
    padding-left:42px;
}

.toggle-password{
    position:absolute;
    top:50%;
    right:12px;
    transform:translateY(-50%);
    background:none;
    border:none;
    color:var(--qp-text-muted);
    cursor:pointer;
    padding:4px;
    display:flex;
}

.toggle-password:hover{
    color:var(--qp-text);
}

.form-control:focus{
    outline:none;
    border-color:var(--qp-primary);
    background:#fff;
    box-shadow:0 0 0 4px rgba(79,70,229,0.12);
}

select.form-control{
    padding-left:14px;
    appearance:none;
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath fill='%236b7280' d='M1 1l5 5 5-5'/%3E%3C/svg%3E");
    background-repeat:no-repeat;
    background-position:right 14px center;
}

.form-options{
    display:flex;
    align-items:center;
    justify-content:space-between;
    margin:6px 0 22px;
    font-size:13px;
}

.checkbox-wrap{
    display:flex;
    align-items:center;
    gap:8px;
    color:var(--qp-text-muted);
    cursor:pointer;
    margin:0;
}

.checkbox-wrap input{
    accent-color:var(--qp-primary);
    width:15px;
    height:15px;
    margin:0;
}

.forgot-link{
    color:var(--qp-primary);
    text-decoration:none;
    font-weight:500;
}

.forgot-link:hover{
    text-decoration:underline;
}

.btn-login{
    width:100%;
    height:46px;
    border:none;
    border-radius:10px;
    background:var(--qp-primary);
    color:#fff;
    font-weight:600;
    font-size:15px;
    cursor:pointer;
    transition:background .2s, transform .15s, box-shadow .2s;
}

.btn-login:hover{
    background:var(--qp-primary-dark);
    box-shadow:0 8px 18px rgba(79,70,229,0.3);
}

.btn-login:active{
    transform:translateY(1px);
}

@media(max-width:768px){
    .login-card{
        flex-direction:column;
        min-height:auto;
    }
    .left-panel{
        display:none;
    }
    .right-panel{
        padding:32px 24px;
    }
    .qp-row{
        flex-direction:column;
        gap:0;
    }
    .qp-row .qp-field{
        margin-bottom:18px;
    }
}
</style>

<?php include "include/footerscripts.php"; ?>
<script>
    $(document).ready(function(){

        $('#company').change(function(){
            var id = $(this).val();
            $.ajax({
                type: "POST",
                data: {
                    recordID: id
                },
                url: '<?php echo base_url() ?>Welcome/Getbranchaccocompany',
                success: function(result) {
                    var obj = JSON.parse(result);
                    var html = '';
                    html += '<option value="">Select</option>';
                    $.each(obj, function (i, item) {
                        html += '<option value="' + obj[i].idtbl_company_branch + '">';
                        html += obj[i].branch;
                        html += '</option>';
                    });
                    $('#branch').empty().append(html);
                }
            });
        });

        $('#togglePassword').click(function(){
            var input = $('#password');
            var type = input.attr('type') === 'password' ? 'text' : 'password';
            input.attr('type', type);
            $(this).attr('aria-label', type === 'password' ? 'Show password' : 'Hide password');
        });

    });
</script>
<?php include "include/footer.php"; ?>