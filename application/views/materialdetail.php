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
                            <div class="page-header-icon"><i class="fas fa-shopping-basket"></i></div>
                            <span>Product Info</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="row erp-stack-row">

                    <!-- ================= FORM CARD (top, full width, landscape) ================= -->
                    <div class="erp-panel-form w-100">
                        <div class="card">
                            <div class="card-header">Product Details</div>
                            <div class="card-body">
                                <form action="<?php echo base_url() ?>Materialdetail/Materialdetailinsertupdate" method="post" autocomplete="off">

                                    <div class="erp-form-grid">
                                        <div class="form-group">
                                            <label class="small font-weight-bold">Item Name*</label>
                                            <input type="text" class="form-control form-control-sm" name="name" id="name" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="small font-weight-bold">Item Code</label>
                                            <input type="text" class="form-control form-control-sm" name="code" id="code" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label class="small font-weight-bold">Item Category*</label>
                                            <select class="form-control form-control-sm" name="materialcategory" id="materialcategory" required>
                                                <option value="">Select</option>
                                                <?php foreach($materialcategory->result() as $rowmaterialcategory){ ?>
                                                <option value="<?php echo $rowmaterialcategory->idtbl_material_category ?>">
                                                    <?php echo $rowmaterialcategory->categoryname ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="small font-weight-bold">Reorder Level</label>
                                            <input type="text" class="form-control form-control-sm" name="rol" id="rol">
                                        </div>

                                        <div class="form-group full-width">
                                            <label class="small font-weight-bold">Comment</label>
                                            <textarea class="form-control form-control-sm" name="comment" id="comment" rows="2"></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group mt-2 text-right">
                                        <button type="button" id="resetBtn" class="btn btn-secondary btn-sm px-4 mr-2">
                                            <i class="fas fa-undo"></i>&nbsp;Reset
                                        </button>
                                        <button type="submit" id="submitBtn" class="btn btn-primary btn-sm px-4"
                                            <?php if($addcheck==0){echo 'disabled';} ?>><i class="far fa-save"></i>&nbsp;Add</button>
                                    </div>
                                    <input type="hidden" name="recordOption" id="recordOption" value="1">
                                    <input type="hidden" name="recordID" id="recordID" value="">
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- ================= TABLE CARD (bottom, full width) ================= -->
                    <div class="erp-panel-table w-100">
                        <div class="card">
                            <div class="card-header">Product List</div>
                            <div class="card-body p-0 p-2">
                                <div class="scrollbar pb-3" id="style-2">
                                    <table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Material</th>
                                                <th>Material Code</th>
                                                <th>Reorder Level</th>
                                                <th>Material Category</th>
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
<script>
    $(document).ready(function() {
        var addcheck='<?php echo $addcheck; ?>';
        var editcheck='<?php echo $editcheck; ?>';
        var statuscheck='<?php echo $statuscheck; ?>';
        var deletecheck='<?php echo $deletecheck; ?>';

        loadNextCode();

        $('#dataTable').DataTable({
            "destroy": true,
            "processing": true,
            "serverSide": true,
            dom: "<'row'<'col-sm-5'B><'col-sm-2'l><'col-sm-5'f>>" + 
                "<'row'<'col-sm-12'tr>>" + 
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            responsive: true,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, 'All'],
            ],
            buttons: [
                { extend: 'csv', className: 'btn btn-ocean-blue btn-sm', title: 'Material Detail Information', text: '<i class="fas fa-file-csv mr-2"></i> CSV' },
                { extend: 'pdf', className: 'btn btn-ocean-blue btn-sm', title: 'Material Detail Information', text: '<i class="fas fa-file-pdf mr-2"></i> PDF' },
                { 
                    extend: 'print', 
                    title: 'Material Detail Information',
                    className: 'btn btn-ocean-blue btn-sm', 
                    text: '<i class="fas fa-print mr-2"></i> Print',
                    customize: function (win) {
                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    }, 
                },
            ],
            ajax: {
                url: "<?php echo base_url() ?>scripts/materialdetaillist.php",
                type: "POST", // you can use GET
                // data: function(d) {}
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "idtbl_material_info"
                },
                {
                    "data": "materialname"
                },
                {
                    "data": "materialinfocode"
                },
                {
                    "data": "reorderlevel"
                },
                {
                    "data": "categoryname"
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var button='';
                        button+='<button class="btn btn-primary btn-sm btnEdit mr-1 ';if(editcheck!=1){button+='d-none';}button+='" id="'+full['idtbl_material_info']+'" data-toggle="tooltip" title="Edit"><i class="fas fa-edit"></i></button>';
                        if(full['status']==1){
                            button+='<a href="<?php echo base_url() ?>Materialdetail/Materialdetailstatus/'+full['idtbl_material_info']+'/2" onclick="return deactive_confirm()" target="_self" class="btn btn-success btn-sm mr-1 ';if(statuscheck!=1){button+='d-none';}button+='" data-toggle="tooltip" title="Deactivate"><i class="fas fa-check-square"></i></a>';
                        }else{
                            button+='<a href="<?php echo base_url() ?>Materialdetail/Materialdetailstatus/'+full['idtbl_material_info']+'/1" onclick="return active_confirm()" target="_self" class="btn btn-warning btn-sm mr-1 ';if(statuscheck!=1){button+='d-none';}button+='" data-toggle="tooltip" title="Activate"><i class="fas fa-times-circle"></i></a>';
                        }
                        button+='<a href="<?php echo base_url() ?>Materialdetail/Materialdetailstatus/'+full['idtbl_material_info']+'/3" onclick="return delete_confirm()" target="_self" class="btn btn-danger btn-sm ';if(deletecheck!=1){button+='d-none';}button+='" data-toggle="tooltip" title="Delete"><i class="fas fa-trash-alt"></i></a>';
                        
                        return button;
                    }
                }
            ],
            drawCallback: function(settings) {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });

        $('#dataTable tbody').on('click', '.btnEdit', function() {
            var r = confirm("Are you sure, You want to Edit this ? ");
            if (r == true) {
                var id = $(this).attr('id');
                $.ajax({
                    type: "POST",
                    data: {
                        recordID: id
                    },
                    url: '<?php echo base_url() ?>Materialdetail/Materialdetailedit',
                    success: function(result) { //alert(result);
                        var obj = JSON.parse(result);
                        $('#recordID').val(obj.id);
                        $('#name').val(obj.materialname); 
                        $('#materialcategory').val(obj.materialcategory);                       
                        $('#code').val(obj.materialinfocode);  
                        $('#rol').val(obj.rol);  
                        $('#comment').val(obj.comment);                     

                        $('#recordOption').val('2');
                        $('#submitBtn').html('<i class="far fa-save"></i>&nbsp;Update');
                    }
                });
            }
        });

        $('#resetBtn').on('click', function() {
            resetForm();
        });
    });

    function resetForm() {
        $('#recordID').val('');
        $('#recordOption').val('1');
        $('#name').val('');
        $('#code').val('');
        $('#materialcategory').val('');
        $('#rol').val('');
        $('#comment').val('');
        $('#submitBtn').html('<i class="far fa-save"></i>&nbsp;Add');
    }

    function loadNextCode() {
        $.ajax({
            type: "POST",
            url: '<?php echo base_url() ?>Materialdetail/Materialdetailgeneratecode',
            success: function(result) {
                var obj = JSON.parse(result);
                $('#code').val(obj.code);
            }
        });
    }

    function deactive_confirm() {
        return confirm("Are you sure you want to deactive this?");
    }

    function active_confirm() {
        return confirm("Are you sure you want to active this?");
    }

    function delete_confirm() {
        return confirm("Are you sure you want to remove this?");
    }
</script>
<?php include "include/footer.php"; ?>