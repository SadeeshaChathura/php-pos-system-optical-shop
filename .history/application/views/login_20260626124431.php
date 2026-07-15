<?php include "include/header.php"; ?>
<style>
#layoutAuthentication_content { min-height: 100vh; display: flex; align-items: center; justify-content: center; }
.login-card-wrap { width: 100%; max-width: 900px; }
</style>
<div id="layoutAuthentication">
    <div id="layoutAuthentication_content">
        <main class="w-100">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="login-card-wrap">
                        <div class="card shadow-lg rounded-5 overflow-hidden" style="background-color: #1fb3c4">
                            <div class="card-body p-0">
                                <div class="row no-gutters align-items-stretch">
                                    <div class="col-md-5 d-flex align-items-center justify-content-center p-4">
                                        <img src="<?php echo base_url() ?>images/QP logo transparent.png" class="img-fluid" alt="" style="max-height: 140px;">
                                    </div>
                                    <div class="col-md-7 p-4">
                                        <form action="<?php echo base_url() ?>Welcome/LoginUser" method="post" autocomplete="off">
                                            <div class="row">
                                                <div class="col-6 form-group mb-2">
                                                    <label class="small mb-1 text-light font-weight-bold" for="inputEmailAddress"><i class="fas fa-user-tie mr-2"></i>Username</label>
                                                    <input class="form-control form-control-sm rounded-2 py-2" id="username" name="username" type="text" placeholder="username" autofocus />
                                                </div>
                                                <div class="col-6 form-group mb-2">
                                                    <label class="small mb-1 text-light font-weight-bold" for="inputPassword"><i class="fas fa-key mr-2"></i>Password</label>
                                                    <input class="form-control form-control-sm rounded-2 py-2" id="password" name="password" type="password" placeholder="****" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6 form-group mb-2">
                                                    <label class="small mb-1 text-light font-weight-bold" for="inputCompany"><i class="fas fa-warehouse mr-2"></i>Company</label>
                                                    <select name="company" id="company" class="form-control form-control-sm rounded-2" required>
                                                        <option value="">Select</option>
                                                        <?php foreach($companylist as $rowcompanylist){ ?>
                                                        <option value="<?php echo $rowcompanylist->idtbl_company ?>"><?php echo $rowcompanylist->company ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="col-6 form-group mb-2">
                                                    <label class="small mb-1 text-light font-weight-bold" for="inputBranch"><i class="fas fa-warehouse mr-2"></i>Branch</label>
                                                    <select name="branch" id="branch" class="form-control form-control-sm rounded-2" required>
                                                        <option value="">Select</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group d-flex align-items-center justify-content-end mt-3 mb-0">
                                                <button type="submit" class="btn btn-dark rounded-2 btn-sm px-4"><i class="fas fa-lock mr-2"></i>Login</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer py-2">
                                <div class="small text-center text-light">&copy; CalciteX Global. All Rights Reseved <?php echo date('Y') ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
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
                success: function(result) { //alert(result);
                    var obj = JSON.parse(result);
                    var html = '';
                    html += '<option value="">Select</option>';
                    $.each(obj, function (i, item) {
                        html += '<option value="' + obj[i].idtbl_company_branch + '">';
                        html += obj[i].branch ;
                        html += '</option>';
                    });
                    $('#branch').empty().append(html);   

                    if(value!=''){
                        $('#branch').val(value);
                    }
                }
            });
        });
    });
</script>
<?php include "include/footer.php"; ?>
