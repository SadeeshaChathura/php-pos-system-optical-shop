<?php 
include "include/header.php";  
include "include/topnavbar.php"; 
?>
<style>
.category-form-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    padding: 1.75rem;
    height: 100%;
}
.category-form-card .form-card-title {
    font-weight: 700;
    color: #333;
    margin-bottom: 1.25rem;
    display: flex;
    align-items: center;
    gap: .5rem;
    font-size: 1rem;
}
.category-form-card .form-card-title i {
    color: #0d7890;
}
.category-form-card label {
    font-size: .78rem;
    font-weight: 700;
    letter-spacing: .4px;
    text-transform: uppercase;
    color: #8a8f9a;
    margin-bottom: .4rem;
}
.category-form-card .form-control {
    border-radius: 10px;
    border: 1px solid #e5e7eb;
    padding: .65rem .9rem;
    font-size: .9rem;
    transition: border-color .2s ease, box-shadow .2s ease;
}
.category-form-card .form-control:focus {
    border-color: #0d7890;
    box-shadow: 0 0 0 .2rem rgba(13,120,144,.15);
}
.btn-teal {
    background: #0d7890;
    border: none;
    color: #fff;
    border-radius: 10px;
    font-weight: 700;
    padding: .6rem 1.5rem;
    transition: background .2s ease;
}
.btn-teal:hover, .btn-teal:focus {
    background: #0a6277;
    color: #fff;
}
.btn-teal:disabled {
    background: #adb5bd;
}

.category-table-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    padding: 1.5rem;
    height: 100%;
}
.category-table-card .table-card-title {
    font-weight: 700;
    color: #333;
    margin-bottom: 1.25rem;
    display: flex;
    align-items: center;
    gap: .5rem;
    font-size: 1rem;
}
.category-table-card .table-card-title i {
    color: #0d7890;
}

#dataTable thead th {
    background: #f8f9fa;
    color: #495057;
    font-size: .75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .4px;
    border-bottom: 2px solid #e9ecef;
    white-space: nowrap;
}
#dataTable tbody td {
    vertical-align: middle;
    font-size: .88rem;
    color: #333;
}
#dataTable tbody tr:hover {
    background: #f8fbfc;
}
#dataTable .btn-sm {
    border-radius: 8px;
}

.page-header.pro-header {
    background: linear-gradient(135deg, #0d7890 0%, #0a6277 100%);
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}
.page-header.pro-header .page-header-title {
    color: #fff;
}
.page-header.pro-header .page-header-icon {
    background: rgba(255,255,255,.15);
    color: #fff;
    border-radius: 12px;
}
</style>

<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <?php include "include/menubar.php"; ?>
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="page-header pro-header">
                <div class="container-fluid">
                    <div class="page-header-content py-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon mr-2"><i data-feather="list"></i></div>
                            <span>Product Category</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-4">
                <div class="row">
                    <div class="col-lg-3 mb-3">
                        <div class="category-form-card">
                            <div class="form-card-title"><i class="fas fa-plus-circle"></i> Add Category</div>
                            <form action="<?php echo base_url() ?>Materialcategory/Materialcategoryinsertupdate" method="post" autocomplete="off">
                                <div class="form-group mb-3">
                                    <label>Item Category*</label>
                                    <input type="text" class="form-control" name="category" id="category" placeholder="e.g. Frames, Lenses" required>
                                </div>
                                <div class="form-group mt-3 text-right mb-0">
                                    <button type="submit" id="submitBtn" class="btn btn-teal btn-block" <?php if($addcheck==0){echo 'disabled';} ?>>
                                        <i class="far fa-save mr-1"></i> Add
                                    </button>
                                </div>
                                <input type="hidden" name="recordOption" id="recordOption" value="1">
                                <input type="hidden" name="recordID" id="recordID" value="">
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-9 mb-3">
                        <div class="category-table-card">
                            <div class="table-card-title"><i class="fas fa-tags"></i> Category List</div>
                            <div class="table-responsive">
                                <table class="table table-hover table-sm nowrap" id="dataTable" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
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
                { extend: 'csv', className: 'btn btn-ocean-blue btn-sm', title: 'Material Category Information', text: '<i class="fas fa-file-csv mr-2"></i> CSV' },
                { extend: 'pdf', className: 'btn btn-ocean-blue btn-sm', title: 'Material Category Information', text: '<i class="fas fa-file-pdf mr-2"></i> PDF' },
                { 
                    extend: 'print', 
                    title: 'Material Category Information',
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
                url: "<?php echo base_url() ?>scripts/customerlist.php",
                type: "POST", // you can use GET
                // data: function(d) {}
            },
            ajax: {
                url: "<?php echo base_url() ?>scripts/materialcategorylist.php",
                type: "POST", // you can use GET
                // data: function(d) {}
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "idtbl_material_category"
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
                        button+='<button class="btn btn-primary btn-sm btnEdit mr-1 ';if(editcheck!=1){button+='d-none';}button+='" id="'+full['idtbl_material_category']+'"><i class="fas fa-edit"></i></button>';
                        if(full['status']==1){
                            button+='<a href="<?php echo base_url() ?>Materialcategory/Materialcategorystatus/'+full['idtbl_material_category']+'/2" onclick="return deactive_confirm()" target="_self" class="btn btn-success btn-sm mr-1 ';if(statuscheck!=1){button+='d-none';}button+='"><i class="fas fa-check-square"></i></a>';
                        }else{
                            button+='<a href="<?php echo base_url() ?>Materialcategory/Materialcategorystatus/'+full['idtbl_material_category']+'/1" onclick="return active_confirm()" target="_self" class="btn btn-warning btn-sm mr-1 ';if(statuscheck!=1){button+='d-none';}button+='"><i class="fas fa-times-circle"></i></a>';
                        }
                        button+='<a href="<?php echo base_url() ?>Materialcategory/Materialcategorystatus/'+full['idtbl_material_category']+'/3" onclick="return delete_confirm()" target="_self" class="btn btn-danger btn-sm ';if(deletecheck!=1){button+='d-none';}button+='"><i class="fas fa-trash-alt"></i></a>';
                        
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
                    url: '<?php echo base_url() ?>Materialcategory/Materialcategoryedit',
                    success: function(result) { //alert(result);
                        var obj = JSON.parse(result);
                        $('#recordID').val(obj.id);
                        $('#category').val(obj.categoryname);                       
                        $('#code').val(obj.categorycode);                       

                        $('#recordOption').val('2');
                        $('#submitBtn').html('<i class="far fa-save"></i>&nbsp;Update');
                    }
                });
            }
        });
    });

    function deactive_confirm() {
        return confirm("Are you sure you want to deactive this?");
    }

    function active_confirm() {
        return confirm("Are you sure you want to remove this?");
    }

    function delete_confirm() {
        return confirm("Are you sure you want to remove this?");
    }
</script>
<?php include "include/footer.php"; ?>