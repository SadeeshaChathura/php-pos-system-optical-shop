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
                            <div class="page-header-icon"><i class="fas fa-truck"></i></div>
                            <span>Purchase Order</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span>Purchase Orders</span>
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#staticBackdrop" <?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-plus mr-2"></i>Create Purchase Order</button>
                    </div>
                    <div class="card-body p-0 p-2">
                        <div class="scrollbar pb-3" id="style-2">
                            <table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>PO Number</th>
                                        <th>Date</th>
                                        <th>Supplier</th>
                                        <th>Total</th>
                                        <th>Confirm Status</th>
                                        <th>GRN Issue Status</th>
                                        <th>Remark</th>
                                        <th class="text-right">Actions</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card mt-2">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span><i class="fas fa-exclamation-triangle text-warning mr-2"></i>Reorder Suggestions</span>
                        <button type="button" class="btn btn-primary btn-sm" id="btnrefreshreorder">
                            <i class="fas fa-sync mr-1"></i>Refresh
                        </button>
                    </div>
                    <div class="card-body p-0 p-2">
                        <div class="scrollbar pb-3" id="style-2">
                            <table class="table table-bordered table-striped table-sm nowrap" id="reorderTable">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="checkallreorder"></th>
                                        <th>Material</th>
                                        <th>Category</th>
                                        <th class="text-center">Current Stock</th>
                                        <th class="text-center">Reorder Level</th>
                                        <th class="text-center">Suggested Qty</th>
                                        <th class="text-right">Unit Price</th>
                                        <th>Supplier</th>
                                    </tr>
                                </thead>
                                <tbody id="reorderTableBody">
                                    <tr><td colspan="8" class="text-center text-muted">Loading...</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="button" class="btn btn-primary btn-sm px-4" id="btngenerateauto" <?php if($addcheck==0){echo 'disabled';} ?>>
                            <i class="fas fa-magic mr-1"></i>Generate Purchase Order(s)
                        </button>
                    </div>
                </div>
            </div>
        </main>
        <?php include "include/footerbar.php"; ?>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1"
	aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="staticBackdropLabel">Create Purchase Order</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

                <!-- ================= entry form (landscape grid, full width) ================= -->
                <form id="createorderform" autocomplete="off">
                    <div class="erp-form-grid">
                        <div class="form-group">
                            <label class="small font-weight-bold text-dark">Order Date*</label>
                            <input type="date" class="form-control form-control-sm" name="orderdate" id="orderdate" value="<?php echo date('Y-m-d') ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="small font-weight-bold text-dark">Due Date*</label>
                            <input type="date" class="form-control form-control-sm" name="duedate" id="duedate" value="<?php echo date('Y-m-d') ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="small font-weight-bold text-dark">Supplier*</label>
                            <select style="width:100%" class="form-control form-control-sm" name="supplier" id="supplier" required>
                                <option value="">Select</option>
                                <?php foreach($supplierlist->result() as $rowsupplierlist){ ?>
                                <option value="<?php echo $rowsupplierlist->idtbl_supplier ?>"><?php echo $rowsupplierlist->suppliername ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="small font-weight-bold text-dark">Product*</label>
                            <select style="width:100%" class="form-control form-control-sm" name="product" id="product" required>
                                <option value="">Select</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="small font-weight-bold text-dark">Qty*</label>
                            <input type="text" id="newqty" name="newqty" class="form-control form-control-sm" value="0" required>
                        </div>
                        <div class="form-group">
                            <label class="small font-weight-bold text-dark">Unit Price</label>
                            <input type="text" id="unitprice" name="unitprice" class="form-control form-control-sm" value="0">
                        </div>
                        <div class="form-group full-width">
                            <label class="small font-weight-bold text-dark">Comment</label>
                            <textarea name="comment" id="comment" class="form-control form-control-sm" rows="1"></textarea>
                        </div>
                    </div>
                    <div class="form-group mb-3 text-right">
                        <button type="button" id="formsubmit" class="btn btn-primary btn-sm px-4" <?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-plus"></i>&nbsp;Add to list</button>
                        <input name="submitBtn" type="submit" value="Save" id="submitBtn" class="d-none">
                    </div>
                    <input type="hidden" name="refillprice" id="refillprice" value="">
                </form>

                <!-- ================= order lines + totals (own card, full width, below the form) ================= -->
                <div class="card mb-3">
                    <div class="card-header">Order Items</div>
                    <div class="card-body p-0 p-2">
                        <table class="table table-striped table-bordered table-sm small mb-0" id="tableorder">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Comment</th>
                                    <th class="d-none">ProductID</th>
                                    <th class="d-none">Unitprice</th>
                                    <th class="text-center">Qty</th>
                                    <th class="d-none">HideTotal</th>
                                    <th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <span class="text-muted small">Tap a row to remove it</span>
                        <h5 class="font-weight-700 mb-0" id="divtotal">Rs. 0.00</h5>
                    </div>
                </div>
                <input type="hidden" id="hidetotalorder" value="0">

                <div class="erp-form-grid">
                    <div class="form-group full-width">
                        <label class="small font-weight-bold text-dark">Remark</label>
                        <textarea name="remark" id="remark" class="form-control form-control-sm" rows="2"></textarea>
                    </div>
                </div>

                <div class="text-right">
                    <button type="button" id="btncreateorder" class="btn btn-primary btn-sm px-4"><i class="fas fa-save"></i>&nbsp;Create Order</button>
                </div>

			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="porderviewmodal" data-backdrop="static" data-keyboard="false" tabindex="-1"
	aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
			<div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="printorder">
                <div id="viewhtml"></div>
			</div>
		</div>
	</div>
</div>
<?php include "include/footerscripts.php"; ?>
<script>
    $(document).ready(function() {
        var addcheck='<?php echo $addcheck; ?>';
        var editcheck='<?php echo $editcheck; ?>';
        var statuscheck='<?php echo $statuscheck; ?>';
        var deletecheck='<?php echo $deletecheck; ?>';

        $('#product').select2();
        $('#supplier').select2();

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
                { extend: 'csv', className: 'btn btn-sm', title: 'Purchase Order Information', text: '<i class="fas fa-file-csv mr-2"></i> CSV' },
                { extend: 'pdf', className: 'btn btn-sm', title: 'Purchase Order Information', text: '<i class="fas fa-file-pdf mr-2"></i> PDF' },
                { 
                    extend: 'print', 
                    title: 'Purchase Order Information',
                    className: 'btn btn-sm', 
                    text: '<i class="fas fa-print mr-2"></i> Print',
                    customize: function (win) {
                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    }, 
                },
            ],
            ajax: {
                url: "<?php echo base_url() ?>scripts/purchaseorderlist.php",
                type: "POST", // you can use GET
                // data: function(d) {}
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "idtbl_porder"
                },
                {
                    "data": "idtbl_porder",
                    "render": function (data, type, row) {
                        return 'CX/PO-' + String(data).padStart(6, '0');
                    }
                },
                {
                    "data": "orderdate"
                },
                {
                    "data": "suppliername"
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        return addCommas(parseFloat(full['nettotal']).toFixed(2));
                    }
                },
                {
                    "targets": -1,
                    "className": '',
                    "data": null,
                    "render": function(data, type, full) {
                        if(full['confirmstatus']==1){return '<span class="badge badge-success"><i class="fas fa-check mr-1"></i>Confirm Order</span>';}
                        else{return '<span class="badge badge-warning">Not Confirm Order</span>';}
                    }
                },
                {
                    "targets": -1,
                    "className": '',
                    "data": null,
                    "render": function(data, type, full) {
                        if(full['grnconfirm']==1){return '<span class="badge badge-success"><i class="fas fa-check mr-1"></i>Issue GRN</span>';}
                        else{return '<span class="badge badge-warning">Not Issue GRN</span>';}
                    }
                },
                {
                    "data": "remark"
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var button='';
                        button+='<button class="btn btn-dark btn-sm btnview mr-1" data-toggle="tooltip" data-placement="bottom" title="View Purchase Order" id="'+full['idtbl_porder']+'"><i class="fas fa-eye"></i></button>';
                        button+='<button class="btn btn-danger btn-sm btnprintpo mr-1" data-toggle="tooltip" data-placement="bottom" title="Print PDF" data-id="'+full['idtbl_porder']+'"><i class="fas fa-file-pdf"></i></button>';
                        if(full['confirmstatus']==1){
                            button+='<button class="btn btn-success btn-sm mr-1 ';if(statuscheck!=1){button+='d-none';}button+='" data-toggle="tooltip" title="Confirmed"><i class="fas fa-check-square"></i></button>';
                        }else{
                            button+='<a href="<?php echo base_url() ?>Purchaseorder/Purchaseorderstatus/'+full['idtbl_porder']+'/1" onclick="return active_confirm()" target="_self" data-toggle="tooltip" data-placement="bottom" title="Approve Purchase Order" class="btn btn-warning btn-sm mr-1 ';if(statuscheck!=1){button+='d-none';}button+='"><i class="fas fa-times-circle"></i></a>';
                        }
                        
                        return button;
                    }
                }
            ],
            drawCallback: function(settings) {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });
        $('#dataTable tbody').on('click', '.btnview', function() {
            var id = $(this).attr('id');
            $('#procode').html(id);
            $.ajax({
                type: "POST",
                data: {
                    recordID: id
                },
                url: '<?php echo base_url() ?>Purchaseorder/Purchaseorderview',
                success: function(result) { //alert(result);

                    $('#porderviewmodal').modal('show');
                    $('#viewhtml').html(result);
                }
            });
        });    
        $('#dataTable tbody').on('click', '.btnprintpo', function() {
            var id = $(this).data('id');
            window.open('<?php echo base_url() ?>Purchaseorder/Getporderprintpdf/' + id, '_blank');
        });
        $('#supplier').change(function () {
            let supplierID = $(this).val()

            $.ajax({
                type: "POST",
                data: {
                    recordID: supplierID
                },
                url: 'Purchaseorder/Getproductaccosupplier',
                success: function (result) { //alert(result);
                    var obj = JSON.parse(result);
                    var html1 = '';
                    html1 += '<option value="">Select</option>';
                    $.each(obj, function (i, item) {
                        html1 += '<option value="' + obj[i].idtbl_material_info + '">';
                        html1 += obj[i].materialname + ' / ' + obj[i].materialinfocode;
                        html1 += '</option>';
                    });
                    $('#product').empty().append(html1);
                }
            });
        });
        $("#formsubmit").click(function () {
            if (!$("#createorderform")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#submitBtn").click();
            } else {
                var productID = $('#product').val();
                var comment = $('#comment').val();
                var product = $("#product option:selected").text();
                var unitprice = parseFloat($('#unitprice').val());
                var newqty = parseFloat($('#newqty').val());

                var newtotal = parseFloat(unitprice * newqty);

                var total = parseFloat(newtotal);
                var showtotal = addCommas(parseFloat(total).toFixed(2));

                $('#tableorder > tbody:last').append('<tr class="pointer"><td>' + product + '</td><td>' + comment + '</td><td class="d-none">' + productID + '</td><td class="d-none">' + unitprice + '</td><td class="text-center">' + newqty + '</td><td class="total d-none">' + total + '</td><td class="text-right">' + showtotal + '</td></tr>');

                $('#product').val('').trigger('change');
                $('#unitprice').val('0');
                $('#saleprice').val('0');
                $('#comment').val('');
                $('#newqty').val('0');

                var sum = 0;
                $(".total").each(function () {
                    sum += parseFloat($(this).text());
                });

                var showsum = addCommas(parseFloat(sum).toFixed(2));

                $('#divtotal').html('Rs. ' + showsum);
                $('#hidetotalorder').val(sum);
                $('#product').focus();
            }
        });
        $('#tableorder').on('click', 'tr', function () {
            var r = confirm("Are you sure, You want to remove this product ? ");
            if (r == true) {
                $(this).closest('tr').remove();

                var sum = 0;
                $(".total").each(function () {
                    sum += parseFloat($(this).text());
                });

                var showsum = addCommas(parseFloat(sum).toFixed(2));

                $('#divtotal').html('Rs. ' + showsum);
                $('#hidetotalorder').val(sum);
                $('#product').focus();
            }
        });
        $('#btncreateorder').click(function () { //alert('IN');
            $('#btncreateorder').prop('disabled', true).html('<i class="fas fa-circle-notch fa-spin mr-2"></i> Create Order')
            var tbody = $("#tableorder tbody");

            if (tbody.children().length > 0) {
                jsonObj = [];
                $("#tableorder tbody tr").each(function () {
                    item = {}
                    $(this).find('td').each(function (col_idx) {
                        item["col_" + (col_idx + 1)] = $(this).text();
                    });
                    jsonObj.push(item);
                });
                // console.log(jsonObj);

                var orderdate = $('#orderdate').val();
                var duedate = $('#duedate').val();
                var remark = $('#remark').val();
                var total = $('#hidetotalorder').val();
                var supplier = $('#supplier').val();
                var location = $('#location').val();
                // alert(orderdate);
                $.ajax({
                    type: "POST",
                    data: {
                        tableData: jsonObj,
                        orderdate: orderdate,
                        duedate: duedate,
                        total: total,
                        remark: remark,
                        supplier: supplier,
                        location: location
                    },
                    url: 'Purchaseorder/Purchaseorderinsertupdate',
                    success: function (result) { //alert(result);
                        // console.log(result);
                        var obj = JSON.parse(result);
                        if(obj.status==1){
                            $('#modalgrnadd').modal('hide');
                            setTimeout(window.location.reload(), 3000);
                        }
                        action(obj.action);
                    }
                });
            }

        });

        function loadReorderSuggestions() {
            $('#reorderTableBody').html('<tr><td colspan="8" class="text-center text-muted">Loading...</td></tr>');

            $.ajax({
                type: "POST",
                url: '<?php echo base_url() ?>Purchaseorder/Reorderpointitems',
                success: function (result) {
                    var items = JSON.parse(result);

                    if (items.length === 0) {
                        $('#reorderTableBody').html('<tr><td colspan="8" class="text-center text-muted">No materials are at/below their reorder level right now.</td></tr>');
                        return;
                    }

                    var html = '';
                    items.forEach(function (row) {
                        html += '<tr data-materialid="' + row.idtbl_material_info + '">';
                        html += '<td><input type="checkbox" class="chkreorderitem"></td>';
                        html += '<td>' + row.materialname + '<br><small class="text-muted">' + row.materialinfocode + '</small></td>';
                        html += '<td>' + (row.categoryname || '-') + '</td>';
                        html += '<td class="text-center">' + row.currentstock + '</td>';
                        html += '<td class="text-center">' + row.reorderlevel + '</td>';
                        html += '<td class="text-center"><input type="text" class="form-control form-control-sm text-center suggestedqty" value="' + row.suggestedqty + '"></td>';
                        html += '<td class="text-right"><input type="text" class="form-control form-control-sm text-right unitprice" value="' + row.lastunitprice + '"></td>';
                        html += '<td><select class="form-control form-control-sm supplierselect"><option value="">Loading...</option></select></td>';
                        html += '</tr>';
                    });

                    $('#reorderTableBody').html(html);

                    // populate supplier dropdown per row
                    $('#reorderTableBody tr').each(function () {
                        var $row = $(this);
                        var materialID = $row.data('materialid');
                        var preselect = items.find(i => i.idtbl_material_info == materialID);

                        $.ajax({
                            type: "POST",
                            data: { recordID: materialID },
                            url: '<?php echo base_url() ?>Purchaseorder/Getsuppliersformaterial',
                            success: function (result2) {
                                var suppliers = JSON.parse(result2);
                                var opts = '<option value="">Select Supplier</option>';
                                suppliers.forEach(function (s) {
                                    var selected = (preselect && preselect.lastsupplierid == s.idtbl_supplier) ? 'selected' : '';
                                    opts += '<option value="' + s.idtbl_supplier + '" ' + selected + '>' + s.suppliername + '</option>';
                                });
                                $row.find('.supplierselect').html(opts);
                            }
                        });
                    });
                }
            });
        }

        $('#btnrefreshreorder').click(function () {
            loadReorderSuggestions();
        });

        $('#checkallreorder').change(function () {
            $('.chkreorderitem').prop('checked', $(this).is(':checked'));
        });

        $('#btngenerateauto').click(function () {
            var checkedRows = $('.chkreorderitem:checked').closest('tr');

            if (checkedRows.length === 0) {
                alert('Please select at least one item.');
                return;
            }

            var groupsBySupplier = {};
            var missingSupplier = false;

            checkedRows.each(function () {
                var $row = $(this);
                var supplierID = $row.find('.supplierselect').val();
                var materialID = $row.data('materialid');
                var qty = parseFloat($row.find('.suggestedqty').val());
                var unitprice = parseFloat($row.find('.unitprice').val());

                if (!supplierID) {
                    missingSupplier = true;
                    return;
                }

                if (!groupsBySupplier[supplierID]) {
                    groupsBySupplier[supplierID] = [];
                }

                groupsBySupplier[supplierID].push({
                    materialID: materialID,
                    qty: qty,
                    unitprice: unitprice,
                    comment: 'Auto reorder'
                });
            });

            if (missingSupplier) {
                alert('One or more selected items have no supplier chosen. Please select a supplier for each checked row.');
                return;
            }

            var groups = [];
            Object.keys(groupsBySupplier).forEach(function (supplierID) {
                groups.push({ supplier: supplierID, items: groupsBySupplier[supplierID] });
            });

            $('#btngenerateauto').prop('disabled', true).html('<i class="fas fa-circle-notch fa-spin mr-2"></i>Generating...');

            $.ajax({
                type: "POST",
                data: { groups: groups },
                url: '<?php echo base_url() ?>Purchaseorder/Autocreatepo',
                success: function (result) {
                    var obj = JSON.parse(result);
                    action(obj.action);
                    $('#btngenerateauto').prop('disabled', false).html('<i class="fas fa-magic mr-1"></i>Generate Purchase Order(s)');
                    if (obj.status == 1) {
                        loadReorderSuggestions();
                        $('#dataTable').DataTable().ajax.reload();
                    }
                }
            });
        });

        // initial load
        loadReorderSuggestions();
    });

    function printpos() {
        printJS({
            printable: 'printorder',
            type: 'html',
            targetStyles: ['*']
        })
    }


    function deactive_confirm() {
        return confirm("Are you sure you want to deactive this?");
    }

    function active_confirm() {
        return confirm("Are you sure you want to confirm this purchase order?");
    }

    function delete_confirm() {
        return confirm("Are you sure you want to remove this?");
    }

    function addCommas(nStr) {
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
    }

    function action(data) { //alert(data);
        var obj = JSON.parse(data);
        $.notify({
            // options
            icon: obj.icon,
            title: obj.title,
            message: obj.message,
            url: obj.url,
            target: obj.target
        }, {
            // settings
            element: 'body',
            position: null,
            type: obj.type,
            allow_dismiss: true,
            newest_on_top: false,
            showProgressbar: false,
            placement: {
                from: "top",
                align: "center"
            },
            offset: 100,
            spacing: 10,
            z_index: 1031,
            delay: 5000,
            timer: 1000,
            url_target: '_blank',
            mouse_over: null,
            animate: {
                enter: 'animated fadeInDown',
                exit: 'animated fadeOutUp'
            },
            onShow: null,
            onShown: null,
            onClose: null,
            onClosed: null,
            icon_type: 'class',
            template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
                '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                '<span data-notify="icon"></span> ' +
                '<span data-notify="title">{1}</span> ' +
                '<span data-notify="message">{2}</span>' +
                '<div class="progress" data-notify="progressbar">' +
                '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
                '</div>' +
                '<a href="{3}" target="{4}" data-notify="url"></a>' +
                '</div>'
        });
    }
</script>
<?php include "include/footer.php"; ?>