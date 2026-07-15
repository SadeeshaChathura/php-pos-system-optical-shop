<?php 
include "include/header.php";  
include "include/topnavbar.php"; 
?>

<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <?php include "include/menubar.php"; ?>
    </div>

    <div id="layoutSidenav_content">
        <main>

            <div class="page-header shadow">
                <div class="container-fluid">
                    <div class="page-header-content py-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i class="fas fa-building"></i></div>
                            <span>Company</span>
                        </h1>
                    </div>
                </div>
            </div>

            <div class="container-fluid mt-2 p-2">

                <div class="card">
                    <div class="card-body p-2">

                        <div class="row">

                            <!-- ================= FORM TOP ================= -->
                            <div class="col-12 mb-3">

                                <form action="<?php echo base_url() ?>Company/Companyinsertupdate" method="post" autocomplete="off">

                                    <div class="form-row">

                                        <div class="col-4 mb-2">
                                            <label class="small font-weight-bold">Company Name*</label>
                                            <input type="text" class="form-control form-control-sm" name="name" id="name" required>
                                        </div>

                                        <div class="col-4 mb-2">
                                            <label class="small font-weight-bold">Company Code*</label>
                                            <input type="text" class="form-control form-control-sm" name="code" id="code" required>
                                        </div>

                                        <div class="col-4 mb-2">
                                            <label class="small font-weight-bold">Mobile*</label>
                                            <input type="tel" class="form-control form-control-sm" name="contact" id="contact" pattern="[0-9]{12}" required>
                                        </div>

                                        <div class="col-4 mb-2">
                                            <label class="small font-weight-bold">Phone</label>
                                            <input type="tel" class="form-control form-control-sm" name="contact2" id="contact2" pattern="[0-9]{12}">
                                        </div>

                                        <div class="col-4 mb-2">
                                            <label class="small font-weight-bold">Email</label>
                                            <input type="text" class="form-control form-control-sm" name="email" id="email">
                                        </div>

                                        <div class="col-4 mb-2">
                                            <label class="small font-weight-bold">Address 1*</label>
                                            <textarea class="form-control form-control-sm" name="address1" id="address1" required></textarea>
                                        </div>

                                        <div class="col-4 mb-2">
                                            <label class="small font-weight-bold">Address 2</label>
                                            <textarea class="form-control form-control-sm" name="address2" id="address2"></textarea>
                                        </div>

                                    </div>

                                    <div class="text-right mt-2">
                                        <button type="submit" id="submitBtn" class="btn btn-primary btn-sm px-4"
                                            <?php if($addcheck==0){echo 'disabled';} ?>>
                                            <i class="far fa-save"></i>&nbsp;Add
                                        </button>
                                    </div>

                                    <input type="hidden" name="recordOption" id="recordOption" value="1">
                                    <input type="hidden" name="recordID" id="recordID" value="">

                                </form>

                            </div>

                            <!-- ================= TABLE BOTTOM ================= -->
                            <div class="col-12">

                                <div class="scrollbar pb-3" id="style-2">

                                    <table class="table table-bordered table-striped table-sm nowrap"
                                           id="companydataTable">

                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Company</th>
                                                <th>Company Code</th>
                                                <th>Mobile</th>
                                                <th>Phone</th>
                                                <th>Email</th>
                                                <th>Address 1</th>
                                                <th>Address 2</th>
                                                <th class="text-right">Actions</th>
                                            </tr>
                                        </thead>

                                    </table>

                                </div>

                            </div>

                        </div>

                    </div>
                </div>

            </div>

        </main>

        <?php include "include/footerbar.php"; ?>
    </div>
</div>

<?php include "include/footerscripts.php"; ?>