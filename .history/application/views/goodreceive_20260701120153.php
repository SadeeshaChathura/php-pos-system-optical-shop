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
                            <span>Good Receive Note</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-12 text-right">
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#staticBackdrop" <?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-plus mr-2"></i>Create Good Receive Note</button>
                                <hr>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="scrollbar pb-3" id="style-2">
                                    <table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>GRN Number</th>
                                                <th>PO Number</th>
                                                <th>Date</th>
                                                <th>Batch No</th>
                                                <th>Supplier</th>
                                                <th>Invoice No</th>
                                                <th>Dispatch No</th>
                                                <th>Total</th>
                                                <th>Approved Status</th>
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
<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1"
	aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="staticBackdropLabel">Create Good Receive Note</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
                        <form id="createorderform" autocomplete="off">
                            <div class="form-row mb-1">
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Order Date*</label>
                                    <input type="date" class="form-control form-control-sm" placeholder="" name="grndate" id="grndate" value="<?php echo date('Y-m-d') ?>" required>
                                </div>
                                <div class="col">
                                <label class="small font-weight-bold text-dark">Purchase Order</label>
                                    <select class="form-control form-control-sm" name="porder" id="porder">
                                        <option value="">Select</option>
                                        <?php foreach($porderlist->result() as $rowporderlist){ ?>
                                        <option value="<?php echo $rowporderlist->idtbl_porder ?>"><?php echo 'PO000'.$rowporderlist->idtbl_porder ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row mb-1">
                                <div class="col-12">
                                <label class="small font-weight-bold text-dark">Supplier*</label>
                                    <select style="width:100%" class="form-control form-control-sm" name="supplier" id="supplier" required>
                                        <option value="">Select</option>
                                        <?php foreach($supplierlist->result() as $rowsupplierlist){ ?>
                                        <option value="<?php echo $rowsupplierlist->idtbl_supplier ?>"><?php echo $rowsupplierlist->suppliername ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row mb-1">
                            <div class="col">
                                <label class="small font-weight-bold text-dark">Product*</label>
                                    <select style="width:100%" class="form-control form-control-sm" name="product" id="product" required>
                                        <option value="">Select</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Qty*</label>
                                    <input type="text" id="newqty" name="newqty" class="form-control form-control-sm" required>
                                </div>
                            </div>
                            <div class="form-row mb-1">
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Unit Price</label>
                                    
                                    <input type="text" id="unitprice" name="unitprice" class="form-control form-control-sm" value="0">
                                    <input type="hidden" id="refillprice" value="">

                                    <small id="lastgrnpricehint" class="text-muted">
                                        Last GRN cost will appear here after selecting product
                                    </small>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Sale Price</label>
                                    <input type="text" id="saleprice" name="saleprice" class="form-control form-control-sm" value="0">
                                </div>
                            </div>
                            <div class="form-group mb-1">
                                <label class="small font-weight-bold text-dark">Comment</label>
                                <textarea name="comment" id="comment" class="form-control form-control-sm"></textarea>
                            </div>
                            <div class="form-group mb-1">
                                <label class="small font-weight-bold text-dark">Batch No</label>
                                <input type="text" id="batchno" name="batchno" class="form-control form-control-sm" readonly>
                            </div>
                            <div class="form-row mb-1">
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Invoice No</label>
                                    <input type="text" id="invoice" name="invoice" class="form-control form-control-sm">
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Dispatch No</label>
                                    <input type="text" id="dispatch" name="dispatch" class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="form-group mt-3 text-right">
                                <button type="button" id="formsubmit" class="btn btn-primary btn-sm px-4" <?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-plus"></i>&nbsp;Add to list</button>
                                <input name="submitBtn" type="submit" value="Save" id="submitBtn" class="d-none">
                            </div>
                            <input type="hidden" name="refillprice" id="refillprice" value="">
                        </form>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-8 col-xl-8">
                        <div class="scrollbar pb-3" id="style-3">
                            <table class="table table-striped table-bordered table-sm small" id="tableorder">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Comment</th>
                                        <th class="d-none">Quater</th>
                                        <th class="d-none">ProductID</th>
                                        <th class="d-none">Unitprice</th>
                                        <th>Unitprice</th>
                                        <th class="d-none">Saleprice</th>
                                        <th>Saleprice</th>
                                        <th class="text-center">Qty</th>
                                        <th class="d-none">HideTotal</th>
                                        <th class="text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col text-right">
                                <h1 class="font-weight-600" id="divtotal">Rs. 0.00</h1>
                            </div>
                            <input type="hidden" id="hidetotalorder" value="0">
                        </div>
                        <hr>
                        <div class="form-group">
                            <label class="small font-weight-bold text-dark">Remark</label>
                            <textarea name="remark" id="remark" class="form-control form-control-sm"></textarea>
                        </div>
                        <div class="form-group mt-2">
                            <button type="button" id="btncreateorder"
                                class="btn btn-outline-primary btn-sm fa-pull-right"><i
                                    class="fas fa-save"></i>&nbsp;Create
                                Good Receive Note</button>
                        </div>
                    </div>
                </div>
			</div>
		</div>
	</div>
</div>
<!-- Modal -->
<div class="modal fade" id="viewmodal" data-backdrop="static" data-keyboard="false" tabindex="-1"
	aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
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
                { extend: 'csv', className: 'btn btn-ocean-blue btn-sm', title: 'Good Receive Information', text: '<i class="fas fa-file-csv mr-2"></i> CSV' },
                { extend: 'pdf', className: 'btn btn-ocean-blue btn-sm', title: 'Good Receive Information', text: '<i class="fas fa-file-pdf mr-2"></i> PDF' },
                { 
                    extend: 'print', 
                    title: 'Good Receive Information',
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
                url: "<?php echo base_url() ?>scripts/goodreceivelist.php",
                type: "POST", // you can use GET
                // data: function(d) {}
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "idtbl_grn"
                },
                {
                    "data": "idtbl_grn",
                    "render": function (data, type, row) {
                        return 'KND/GRN-' + String(data).padStart(6, '0');
                    }
                },
                {
                    "data": "tbl_porder_idtbl_porder",
                    "render": function (data, type, row) {
                        return data && data != 1 ? 'KND/PO-' + String(data).padStart(6, '0') : 'Direct GRN (no PO)';
                    }
                },
                {
                    "data": "grndate"
                },
                {
                    "data": "batchno"
                },
                {
                    "data": "suppliername"
                },
                {
                    "data": "invoicenum"
                },
                {
                    "data": "dispatchnum"
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        return addCommas(parseFloat(full['total']).toFixed(2));
                    }
                },
                {
                    "targets": -1,
                    "className": '',
                    "data": null,
                    "render": function(data, type, full) {
                        if(full['approvestatus']==1){return '<i class="fas fa-check text-success mr-2"></i>Approved GRN';}
                        else{return 'Not Approved GRN';}
                    }
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var button='';
                        button+='<button class="btn btn-dark btn-sm btnview mr-1" data-toggle="tooltip" data-placement="bottom" title="View GRN" id="'+full['idtbl_grn']+'"><i class="fas fa-eye"></i></button>';
                        button+='<a href="<?php echo base_url() ?>Goodreceive/Getgrnprintpdf/'+full['idtbl_grn']+'" target="_blank" class="btn btn-danger btn-sm mr-1" data-toggle="tooltip" data-placement="bottom" title="Print GRN"><i class="fas fa-print"></i></a>';
                        if(full['approvestatus']==1){
                            button+='<button class="btn btn-success btn-sm mr-1 ';if(statuscheck!=1){button+='d-none';}button+='"><i class="fas fa-check-square"></i></button>';
                        }else{
                            button += '<a href="<?php echo base_url() ?>Goodreceive/Goodreceivestatus/' 
                                    + full['idtbl_grn'] 
                                    + '/1?porder_id=' 
                                    + full['tbl_porder_idtbl_porder'] 
                                    + '" onclick="return active_confirm()" target="_self" data-toggle="tooltip" data-placement="bottom" title="Approve GRN" class="btn btn-warning btn-sm mr-1 ';
                            if (statuscheck != 1) {
                                button += 'd-none';
                            }
                            button += '"><i class="fas fa-times-circle"></i></a>';
                        }
                        if(full['approvestatus']==0){
                            button+='<a href="<?php echo base_url() ?>Goodreceive/Goodreceivestatus/'+full['idtbl_grn']+'/3" onclick="return delete_confirm()" data-toggle="tooltip" data-placement="bottom" title="Cancel GRN" target="_self" class="btn btn-danger btn-sm mr-1 ';if(statuscheck!=1){button+='d-none';}button+='"><i class="fas fa-trash-alt"></i></a>';
                        }
                        
                        return button;
                    }
                }
            ],
            drawCallback: function(settings) {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });

        $('#dataTable tbody').on('click', '.btnAddCosting', function() {
            var id = $(this).attr('id');
            $.ajax({
                type: "POST",
                data: {
                    recordID: id
                },
                url: '<?php echo base_url() ?>Goodreceive/Getgoodreceiveid',
                success: function(result) { //alert(result);
                    var obj = JSON.parse(result);
                    $('#addCostModal').modal('show');
                    $('#grnid').val(obj[0].idtbl_grn);
                    $('#grnid').html(obj[0].idtbl_grn);

                    preparelist(obj[0].idtbl_grn);

                }
            });
        }); 
        $('#dataTable tbody').on('click', '.btnview', function() {
            var id = $(this).attr('id');
            $('#grncode').html(id);
            $.ajax({
                type: "POST",
                data: {
                    recordID: id
                },
                url: '<?php echo base_url() ?>Goodreceive/Goodreceiveview',
                success: function(result) { //alert(result);
                    $('#viewmodal').modal('show');
                    $('#viewhtml').html(result);
                }
            });
        });    
        $('#supplier').change(function () {
            let supplierID = $(this).val();
            
            $.ajax({
                type: "POST",
                data: { recordID: supplierID },
                url: 'Purchaseorder/Getproductaccosupplier',
                success: function (result) {
                    var obj = JSON.parse(result);
                    var html1 = '<option value="">Select</option>';
                    
                    $.each(obj, function (i, item) {
                        html1 += '<option value="' + item.idtbl_material_info + '">';
                        html1 += item.materialname + ' / ' + item.materialinfocode;
                        html1 += '</option>';
                    });

                    $('#product').empty().append(html1);

                    // Now call getbatchno() after setting products
                    getbatchno(supplierID);
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
                var saleprice = parseFloat($('#saleprice').val());
                var newqty = parseFloat($('#newqty').val());
                var mfdate = $('#mfdate').val();
                var quater = $('#quater').val();
                var expdate = $('#expdate').val();

                var newtotal = parseFloat(unitprice * newqty);

                var total = parseFloat(newtotal);
                var showtotal = addCommas(parseFloat(total).toFixed(2));

                $('#tableorder > tbody:last').append('<tr class="pointer"><td>' + product + '</td><td>' + comment + '</td><td class="d-none">' + productID + '</td><td class="d-none">' + unitprice + '</td><td>' + addCommas(parseFloat(unitprice).toFixed(2)) + '</td><td class="d-none">' + saleprice + '</td><td>' + addCommas(parseFloat(saleprice).toFixed(2)) + '</td><td class="text-center">' + newqty + '</td><td class="total d-none">' + total + '</td><td class="text-right">' + showtotal + '</td></tr>');

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
        $('#tblcost').on('click', 'tr', function () {
            var r = confirm("Are you sure, You want to remove this cost? ");
            if (r == true) {
                $(this).closest('tr').remove();

                var sum = 0;
                $(".totalamount").each(function () {
                    sum += parseFloat($(this).text());
                });

                var showsum = addCommas(parseFloat(sum).toFixed(2));

                $('#labelcosttotal').html('Rs. ' + showsum);
                $('#totalcost').val(sum);
            }
        });
        $('#btncreateorder').click(function () { //alert('IN');
            $('#btncreateorder').prop('disabled', true).html('<i class="fas fa-circle-notch fa-spin mr-2"></i> Create Good Receive Note')
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
                console.log(jsonObj);

                var grndate = $('#grndate').val();
                var remark = $('#remark').val();
                var total = $('#hidetotalorder').val();
                var supplier = $('#supplier').val();
                var porder = $('#porder').val();
                var batchno = $('#batchno').val();
                var invoice = $('#invoice').val();
                var dispatch = $('#dispatch').val();
                // alert(batchno);
                $.ajax({
                    type: "POST",
                    data: {
                        tableData: jsonObj,
                        grndate: grndate,
                        total: total,
                        remark: remark,
                        supplier: supplier,
                        porder: porder,
                        invoice: invoice,
                        dispatch: dispatch,
                        batchno: batchno
                    },
                    url: 'Goodreceive/Goodreceiveinsertupdate',
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
        $('#porder').change(function () {
            var porderID = $(this).val();

            $.ajax({
                type: "POST",
                data: { recordID: porderID },
                url: 'Goodreceive/Getsupplieraccoporder',
                success: function (result) {
                    var supplierID = result.trim(); // Ensure proper formatting
                    $('#supplier').val(supplierID);
                    $('#supplier').val(supplierID).trigger('change.select2');
                    $('#supplier option').each(function () {
                        if ($(this).val() !== supplierID) {
                            $(this).attr('disabled', true);
                        } else {
                            $(this).removeAttr('disabled');
                        }
                    });

                    // Call getbatchno() after the supplier is set
                    getbatchno(supplierID);
                }
            });

            $.ajax({
                type: "POST",
                data: { recordID: porderID },
                url: 'Goodreceive/Getproductaccoporder',
                success: function (result) {
                    var obj = JSON.parse(result);
                    var html1 = '<option value="">Select</option>';
                    
                    $.each(obj, function (i, item) {
                        html1 += '<option value="' + item.idtbl_material_info + '">';
                        html1 += item.materialname + ' / ' + item.materialinfocode;
                        html1 += '</option>';
                    });

                    $('#product').empty().append(html1);
                }
            });
        });
        $('#product').change(function () {
            var productID = $(this).val();
            var porderID = $('#porder').val();

            let grnPrice = null;
            let poData = null;

            $('#lastgrnpricehint')
                .html('Loading last GRN price...')
                .removeClass('text-success')
                .addClass('text-muted');

            // ================= GRN AJAX =================
            $.ajax({
                type: "POST",
                data: { recordID: productID },
                url: 'Goodreceive/Getlastgrnprice',
                success: function (result) {

                    var obj = JSON.parse(result);

                    if (obj.costprice > 0) {
                        grnPrice = obj.costprice;

                        $('#unitprice').val(obj.costprice);
                        $('#refillprice').val(obj.costprice);

                        $('#lastgrnpricehint')
                            .html('Last GRN Cost: <b>' + obj.costprice + '</b>')
                            .removeClass('text-muted')
                            .addClass('text-danger');
                    }
                }
            });

            // ================= PO AJAX =================
            $.ajax({
                type: "POST",
                data: {
                    recordID: productID,
                    porderID: porderID
                },
                url: 'Goodreceive/Getproductinfoaccoproduct',
                success: function (result) {

                    var obj = JSON.parse(result);
                    poData = obj;

                    // ONLY apply PO if GRN not available
                    if (!grnPrice) {
                        $('#newqty').val(obj.qty);
                        $('#unitprice').val(obj.unitprice);
                        $('#saleprice').val(obj.unitprice);
                        $('#comment').val(obj.comment);
                    }
                }
            });
        });
        $('#quater').change(function(){
            var quaterID=$(this).val();
            var mfdate=$('#mfdate').val();

            $.ajax({
                type: "POST",
                data: {
                    recordID: quaterID,
                    mfdate: mfdate
                },
                url: 'Goodreceive/Getexpdateaccoquater',
                success: function (result) { //alert(result);
                    $('#expdate').val(result);
                }
            });
        });
    });

    function deactive_confirm() {
        return confirm("Are you sure you want to deactive this?");
    }

    function active_confirm() {
        return confirm("Are you sure you want to approve this good receive note?");
    }

    function delete_confirm() {
        return confirm("Are you sure you want to reject this good receive note?");
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

    function getbatchno(supplierID) {
        $.ajax({
            type: "POST",
            data: { recordID: supplierID },
            url: 'Goodreceive/Getbatchnoaccosupplier',
            success: function (result) {
                $('#batchno').val(result.trim());
            }
        });
    }
</script>
<?php include "include/footer.php"; ?>