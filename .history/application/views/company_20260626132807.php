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

            <div class="container-fluid mt-2 p-0 p-2">

            	<div class="card">
            		<div class="card-body p-2">

            			<!-- 🔥 NEW STACKED LAYOUT -->
            			<div class="row">

            				<!-- ===================== -->
            				<!-- FORM (FULL TOP ROW)   -->
            				<!-- ===================== -->
            				<div class="col-12 mb-3">

            					<form action="<?php echo base_url() ?>Company/Companyinsertupdate" method="post"
            						autocomplete="off">

            						<div class="form-row mb-1">
            							<div class="col">
            								<label class="small font-weight-bold">Company Name*</label>
            								<input type="text" class="form-control form-control-sm" name="name"
            									id="name" required>
            							</div>

            							<div class="col">
            								<label class="small font-weight-bold">Company Code*</label>
            								<input type="text" class="form-control form-control-sm" name="code"
            									id="code" required>
            							</div>
            						</div>

            						<div class="form-row mb-1">
            							<div class="col">
            								<label class="small font-weight-bold">Address 1*</label>
            								<textarea class="form-control form-control-sm" name="address1" id="address1"
            									required></textarea>
            							</div>

            							<div class="col">
            								<label class="small font-weight-bold">Address 2</label>
            								<textarea class="form-control form-control-sm" name="address2"
            									id="address2"></textarea>
            							</div>
            						</div>

            						<div class="form-row mb-1">
            							<div class="col">
            								<label class="small font-weight-bold">Mobile*</label>
            								<input type="tel" class="form-control form-control-sm" name="contact"
            									id="contact" pattern="[0-9]{12}" required>
            							</div>

            							<div class="col">
            								<label class="small font-weight-bold">Phone</label>
            								<input type="tel" class="form-control form-control-sm" name="contact2"
            									id="contact2" pattern="[0-9]{12}">
            							</div>
            						</div>

            						<div class="form-row mb-1">
            							<div class="col-6">
            								<label class="small font-weight-bold">Email</label>
            								<input type="text" class="form-control form-control-sm" name="email"
            									id="email">
            							</div>
            						</div>

            						<div class="form-group mt-2 text-right">
            							<button type="submit" id="submitBtn" class="btn btn-primary btn-sm px-4"
            								<?php if($addcheck==0){echo 'disabled';} ?>>
            								<i class="far fa-save"></i>&nbsp;Add
            							</button>
            						</div>

            						<input type="hidden" name="recordOption" id="recordOption" value="1">
            						<input type="hidden" name="recordID" id="recordID" value="">

            					</form>

            				</div>

            				<!-- ===================== -->
            				<!-- TABLE (FULL BOTTOM)  -->
            				<!-- ===================== -->
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
            			<!-- END ROW -->

            		</div>
            	</div>

            </div>
        </main>

        <?php include "include/footerbar.php"; ?>
    </div>
</div>

<?php include "include/footerscripts.php"; ?>

<script>

$(function () {

    var addcheck='<?php echo $addcheck; ?>';
    var editcheck='<?php echo $editcheck; ?>';
    var statuscheck='<?php echo $statuscheck; ?>';
    var deletecheck='<?php echo $deletecheck; ?>';

    $('#companydataTable').DataTable({
        "destroy": true,
        "processing": true,
        "serverSide": true,
        dom: "<'row'<'col-sm-5'B><'col-sm-2'l><'col-sm-5'f>>" + 
             "<'row'<'col-sm-12'tr>>" + 
             "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        responsive: true,

        lengthMenu: [[10, 25, 50, -1],[10, 25, 50, 'All']],

        buttons: [
            { extend: 'csv', className: 'btn btn-ocean-blue btn-sm', text: 'CSV' },
            { extend: 'pdf', className: 'btn btn-ocean-blue btn-sm', text: 'PDF' },
            { extend: 'print', className: 'btn btn-ocean-blue btn-sm', text: 'Print' }
        ],

        ajax: {
            url: "<?php echo base_url() ?>scripts/companylist.php",
            type: "POST"
        },

        "order": [[ 0, "desc" ]],

        "columns": [
            { "data": "idtbl_company" },
            { "data": "company" },
            { "data": "code" },
            { "data": "mobile" },
            { "data": "phone" },
            { "data": "email" },
            { "data": "address1" },
            { "data": "address2" },
            {
                "className": 'text-right',
                "data": null,
                "render": function(data, type, full) {

                    var button='';

                    button+='<button class="btn btn-primary btn-sm btnEdit mr-1 ';if(editcheck!=1){button+='d-none';}button+='" id="'+full['idtbl_company']+'"><i class="fas fa-edit"></i></button>';

                    if(full['status']==1){
                        button+='<a href="<?php echo base_url() ?>Company/Companystatus/'+full['idtbl_company']+'/2" class="btn btn-success btn-sm mr-1 ';if(statuscheck!=1){button+='d-none';}button+='">✔</a>';
                    }else{
                        button+='<a href="<?php echo base_url() ?>Company/Companystatus/'+full['idtbl_company']+'/1" class="btn btn-warning btn-sm mr-1 ';if(statuscheck!=1){button+='d-none';}button+='">✖</a>';
                    }

                    button+='<a href="<?php echo base_url() ?>Company/Companystatus/'+full['idtbl_company']+'/3" class="btn btn-danger btn-sm ';if(deletecheck!=1){button+='d-none';}button+='">🗑</a>';

                    return button;
                }
            }
        ]
    });

    $('#companydataTable tbody').on('click', '.btnEdit', function() {

        var id = $(this).attr('id');

        $.ajax({
            type: "POST",
            data: { recordID: id },
            url: '<?php echo base_url() ?>Company/Companyedit',
            success: function(result) {

                var obj = JSON.parse(result);

                $('#recordID').val(obj.id);
                $('#name').val(obj.name);   
                $('#code').val(obj.code);   
                $('#contact').val(obj.contact);                       
                $('#contact2').val(obj.contact2);      
                $('#email').val(obj.email);   
                $('#address1').val(obj.address1);   
                $('#address2').val(obj.address2);   

                $('#recordOption').val('2');
                $('#submitBtn').html('<i class="far fa-save"></i>&nbsp;Update');
            }
        });

    });

});

</script>

<?php include "include/footer.php"; ?>