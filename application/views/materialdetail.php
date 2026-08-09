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
                                            <label class="small font-weight-bold">Barcode</label>
                                            <input type="text" class="form-control form-control-sm" name="barcode" id="barcode" placeholder="Scan or type barcode">
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
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <span>Product List</span>
                                <button type="button" class="btn btn-dark btn-sm" id="btnprintselected" disabled>
                                    <i class="fas fa-barcode mr-2"></i>Print Selected Labels
                                </button>
                            </div>
                            <div class="card-body p-0 p-2">
                                <div class="scrollbar pb-3" id="style-2">
                                    <table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="checkallmaterials"></th>
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
<!-- Label Print Modal -->
<div class="modal fade" id="labelmodal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Print Barcode Labels</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered" id="labelsetuptable">
                        <thead>
                            <tr>
                                <th>Material</th>
                                <th>Code</th>
                                <th style="width:120px">Price (Rs.)</th>
                                <th style="width:100px">Copies</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="form-group">
                    <label class="small font-weight-bold">Label Size</label>
                    <select class="form-control form-control-sm" id="labelsize" style="width:200px">
                        <option value="50x25">50mm x 25mm (Standard)</option>
                        <option value="40x30">40mm x 30mm</option>
                        <option value="38x25">38mm x 25mm (Small)</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm" id="btngeneratelabels"><i class="fas fa-print mr-2"></i>Generate & Print</button>
            </div>
        </div>
    </div>
</div>

<!-- Printable area, hidden on screen -->
<div id="printlabelsarea" style="display:none;"></div>

<style>
@media print {
    body * { visibility: hidden; }
    #printlabelsarea, #printlabelsarea * { visibility: visible; }
    #printlabelsarea {
        display: block !important;
        position: absolute;
        top: 0;
        left: 0;
    }
    .label-item {
        page-break-inside: avoid;
        display: inline-block;
        box-sizing: border-box;
        padding: 2mm;
        text-align: center;
        overflow: hidden;
    }
    .label-item .lbl-name {
        font-size: 8px;
        font-weight: bold;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .label-item .lbl-price {
        font-size: 9px;
        font-weight: bold;
    }
    .label-item svg { width: 100%; height: auto; }
}
</style>
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
            "order": [[ 1, "desc" ]],
            "columns": [
                {
                    "targets": 0,
                    "data": null,
                    "orderable": false,
                    "className": 'text-center',
                    "render": function(data, type, full) {
                        return '<input type="checkbox" class="chkmaterial" value="' + full['idtbl_material_info'] + '" data-name="' + full['materialname'] + '" data-code="' + full['materialinfocode'] + '">';
                    }
                },
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
                        button+='<button class="btn btn-dark btn-sm btnprintone mr-1" data-id="'+full['idtbl_material_info']+'" data-toggle="tooltip" title="Print Barcode Label"><i class="fas fa-barcode"></i></button>';
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
                        $('#barcode').val(obj.barcode); 
                        $('#rol').val(obj.rol);  
                        $('#comment').val(obj.comment);                     

                        $('#recordOption').val('2');
                        $('#submitBtn').html('<i class="far fa-save"></i>&nbsp;Update');
                    }
                });
            }
        });

        $('#barcode').on('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
            }
        });

        $('#resetBtn').on('click', function() {
            resetForm();
        });

        $('#checkallmaterials').change(function () {
            $('.chkmaterial').prop('checked', $(this).is(':checked'));
            toggleBulkPrintBtn();
        });

        $(document).on('change', '.chkmaterial', function () {
            toggleBulkPrintBtn();
        });

        function toggleBulkPrintBtn() {
            $('#btnprintselected').prop('disabled', $('.chkmaterial:checked').length === 0);
        }

        function openLabelModal(items) {
            var rows = '';
            items.forEach(function (item) {
                rows += '<tr data-id="' + item.id + '" data-code="' + item.code + '">';
                rows += '<td>' + item.name + '</td>';
                rows += '<td>' + item.code + '</td>';
                rows += '<td><input type="text" class="form-control form-control-sm lblprice" value="' + (item.price || 0) + '"></td>';
                rows += '<td><input type="number" class="form-control form-control-sm lblqty" value="1" min="1"></td>';
                rows += '</tr>';
            });
            $('#labelsetuptable tbody').html(rows);
            $('#labelmodal').modal('show');
        }

        $('#btnprintselected').click(function () {
            var ids = $('.chkmaterial:checked').map(function () { return $(this).val(); }).get();

            $.ajax({
                type: "POST",
                data: { materialIDs: ids },
                url: '<?php echo base_url() ?>Materialdetail/Getbarcodedata',
                success: function (result) {
                    var items = JSON.parse(result);
                    openLabelModal(items);
                }
            });
        });

        $(document).on('click', '.btnprintone', function () {
            var id = $(this).data('id');

            $.ajax({
                type: "POST",
                data: { materialIDs: [id] },
                url: '<?php echo base_url() ?>Materialdetail/Getbarcodedata',
                success: function (result) {
                    var items = JSON.parse(result);
                    openLabelModal(items);
                }
            });
        });

        $('#btngeneratelabels').click(function () {
            var size = $('#labelsize').val().split('x'); // e.g. ['50','25']
            var labelW = size[0] + 'mm';
            var labelH = size[1] + 'mm';

            var labelsHtml = '';
            $('#labelsetuptable tbody tr').each(function () {
                var $row = $(this);
                var code = $row.data('code');
                var name = $row.closest('tr').find('td').eq(0).text();
                var price = $row.find('.lblprice').val();
                var qty = parseInt($row.find('.lblqty').val()) || 1;

                for (var i = 0; i < qty; i++) {
                    var svgId = 'bc_' + code + '_' + i;
                    labelsHtml += '<div class="label-item" style="width:' + labelW + ';height:' + labelH + ';">';
                    labelsHtml += '<div class="lbl-name">' + name + '</div>';
                    labelsHtml += '<svg id="' + svgId + '"></svg>';
                    labelsHtml += '<div class="lbl-price">Rs. ' + parseFloat(price).toFixed(2) + '</div>';
                    labelsHtml += '</div>';
                }
            });

            $('#printlabelsarea').html(labelsHtml);

            // render each barcode after the SVGs exist in the DOM
            $('#printlabelsarea svg').each(function () {
                var svgId = $(this).attr('id');
                var code = svgId.split('_')[1];
                JsBarcode('#' + svgId, code, {
                    format: "CODE128",
                    width: 1.5,
                    height: 30,
                    displayValue: true,
                    fontSize: 10,
                    margin: 2
                });
            });

            $('#labelmodal').modal('hide');

            setTimeout(function () {
                window.print();
            }, 300); // small delay so barcodes finish rendering before print dialog opens
        });
    });

    function resetForm() {
        $('#recordID').val('');
        $('#recordOption').val('1');
        $('#name').val('');
        $('#materialcategory').val('');
        $('#barcode').val('');
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