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
            <div class="page-header page-header-light bg-white shadow">
                <div class="container-fluid">
                    <div class="page-header-content py-3">
                        <h1 class="page-header-title font-weight-light">
                            <div class="page-header-icon"><i class="fas fa-truck-loading"></i></div>
                            <span>Approve & Start</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-12">
                                <div class="scrollbar pb-3" id="style-2">
                                    <table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Customer</th>
                                                <th>Type</th>
                                                <th>Order Date</th>
                                                <th>Due Date</th>
                                                <th>Net Total</th>
                                                <th>Remark</th>
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
				<h5 class="modal-title" id="staticBackdropLabel">Material, factory & machine set to production</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
                        <form id="productionsteponeform" autocomplete="off">
                            <div class="form-row mb-1">                                
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Production Product*</label>
                                    <select class="form-control form-control-sm" name="proproduct" id="proproduct" required>
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row mb-1">
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Factory*</label>
                                    <select class="form-control form-control-sm" name="factory" id="factory" required>
                                        <option value="">Select</option>
                                        <?php foreach($factorylist->result() as $rowfactorylist){ ?>
                                        <option value="<?php echo $rowfactorylist->idtbl_factory ?>"><?php echo $rowfactorylist->factoryname.' - '.$rowfactorylist->factorycode ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Machine*</label>
                                    <select class="form-control form-control-sm" name="machine" id="machine" required>
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row mb-1">
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Stock Batch no*</label>
                                    <select class="form-control form-control-sm" name="batchno" id="batchno" required>
                                        <option value="">Select</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row mb-1">
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Qty*</label>
                                    <input type="text" name="qty" id="qty" class="form-control form-control-sm">
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Order Qty*</label>
                                    <input type="text" name="orderqty" id="orderqty" class="form-control form-control-sm" readonly>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Issue Qty*</label>
                                    <input type="text" name="issueqty" id="issueqty" class="form-control form-control-sm" readonly>
                                </div>
                            </div>
                            <div class="form-group mt-3 text-right">
                                <button type="button" id="formsubmit" class="btn btn-primary btn-sm px-4" <?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-plus"></i>&nbsp;Add to list</button>
                                <input name="submitBtn" type="submit" value="Save" id="submitBtn" class="d-none">
                            </div>
                            <input type="hidden" name="productionorder" id="productionorder" value="">
                        </form>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-8 col-xl-8">
                        <table class="table table-striped table-bordered table-sm small" id="tablestartproduction">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Factory</th>
                                    <th>Machine</th>
                                    <th>Batch No</th>
                                    <th>Qty</th>
                                    <th class="d-none">ProductiondetailID</th>
                                    <th class="d-none">FactoryID</th>
                                    <th class="d-none">MachineID</th>
                                    <th class="d-none">BatchNo</th>
                                    <th class="d-none">MaterialID</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <div class="form-group mt-2">
                            <button type="button" id="btnapproveproduction" class="btn btn-outline-primary btn-sm fa-pull-right"><i class="fas fa-save"></i>&nbsp;Approve & Start Production</button>
                        </div>
                    </div>
                </div>
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

        $('#dataTable').DataTable({
            "destroy": true,
            "processing": true,
            "serverSide": true,
            dom: "<'row'<'col-sm-5'B><'col-sm-2'l><'col-sm-5'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            responsive: true,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, 'All'],
            ],
            "buttons": [
                { extend: 'csv', className: 'btn btn-success btn-sm', title: 'Brand Information', text: '<i class="fas fa-file-csv mr-2"></i> CSV', },
                { extend: 'pdf', className: 'btn btn-danger btn-sm', title: 'Brand Information', text: '<i class="fas fa-file-pdf mr-2"></i> PDF', },
                { 
                    extend: 'print', 
                    title: 'Brand Information',
                    className: 'btn btn-primary btn-sm', 
                    text: '<i class="fas fa-print mr-2"></i> Print',
                    customize: function ( win ) {
                        $(win.document.body).find( 'table' )
                            .addClass( 'compact' )
                            .css( 'font-size', 'inherit' );
                    }, 
                },
                // 'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            ajax: {
                url: "<?php echo base_url() ?>scripts/pendingproduction.php",
                type: "POST", // you can use GET
                // data: function(d) {}
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "idtbl_production_order"
                },
                {
                    "data": "name"
                },
                {
                    "targets": -1,
                    "className": '',
                    "data": null,
                    "render": function(data, type, full) {
                        if(full['customertype']){
                            return 'Local Customer';
                        }
                        else{
                            return 'International Customer';
                        }
                    }
                },
                {
                    "data": "orderdate"
                },
                {
                    "data": "duedate"
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
                    "data": "remark"
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var button='';
                        if(full['approvestatus']==0){
                            button+='<button type="button" class="btn btn-danger btn-sm mr-1 btnApprove ';if(statuscheck!=1){button+='d-none';}button+='" id="'+full['idtbl_production_order']+'"><i class="fas fa-times"></i></button>';
                        }
                        else{
                            button+='<button type="button" class="btn btn-dark btn-sm mr-1 btnproduction ';if(statuscheck!=1){button+='d-none';}button+='" id="'+full['idtbl_production_order']+'"><i class="fas fa-list"></i></button>';
                        }
                        
                        return button;
                    }
                }
            ],
            drawCallback: function(settings) {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });
        $('#dataTable tbody').on('click', '.btnApprove', function() {
            var r = confirm("Are you sure, You want to Approve this ? ");
            if (r == true) {
                var id = $(this).attr('id');
                $('#productionorder').val(id);
                
                $.ajax({
                    type: "POST",
                    data: {
                        recordID: id
                    },
                    url: '<?php echo base_url() ?>Production/Productiondetailaccoproduction',
                    success: function(result) { //alert(result);
                        var obj = JSON.parse(result);
                        var html1 = '';
                        html1 += '<option value="">Select</option>';
                        $.each(obj, function (i, item) {
                            html1 += '<option value="' + obj[i].idtbl_production_order_detail + '">';
                            html1 += obj[i].materialname + ' - ' + obj[i].productcode;
                            html1 += '</option>';
                        });
                        $('#proproduct').empty().append(html1);

                        $('#staticBackdrop').modal('show');
                    }
                });
            }
        });
        $('#dataTable tbody').on('click', '.btnproduction', function() {
            var id = $(this).attr('id');
            $('#productionorder').val(id);
            
            $.ajax({
                type: "POST",
                data: {
                    recordID: id
                },
                url: '<?php echo base_url() ?>Production/Productiondetailaccoproduction',
                success: function(result) { //alert(result);
                    var obj = JSON.parse(result);
                    var html1 = '';
                    html1 += '<option value="">Select</option>';
                    $.each(obj, function (i, item) {
                        html1 += '<option value="' + obj[i].idtbl_production_order_detail + '">';
                        html1 += obj[i].materialname + ' - ' + obj[i].productcode;
                        html1 += '</option>';
                    });
                    $('#proproduct').empty().append(html1);

                    $('#staticBackdrop').modal('show');
                }
            });
        });
        $('#factory').change(function(){
            var id = $(this).val();

            $.ajax({
                type: "POST",
                data: {
                    recordID: id
                },
                url: '<?php echo base_url() ?>Production/Getmachineaccofactory',
                success: function(result) { //alert(result);
                    var obj = JSON.parse(result);
                    var html2 = '';
                    html2 += '<option value="">Select</option>';
                    $.each(obj, function (i, item) {
                        html2 += '<option value="' + obj[i].idtbl_machine + '">';
                        html2 += obj[i].machine + ' - ' + obj[i].machinecode;
                        html2 += '</option>';
                    });
                    $('#machine').empty().append(html2);
                }
            });
        });
        $("#qty").keyup(function(){
            var qty = $(this).val();
            var materialID = $('#batchno').val();

            $.ajax({
                type: "POST",
                data: {
                    qty: qty,
                    materialID: materialID
                },
                url: '<?php echo base_url() ?>Production/Checkavabilitystock',
                success: function(result) { //alert(result);
                    if(result==0){
                        $("#qty").addClass('bg-danger text-white');
                        $('#formsubmit').prop('disabled', true);
                    }
                    else{
                        $("#qty").removeClass('bg-danger text-white');
                        $('#formsubmit').prop('disabled', false);
                    }
                }
            });
        });
        $("#formsubmit").click(function () {
            if (!$("#productionsteponeform")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#submitBtn").click();
            } else {
                var productiondetailID = $('#proproduct').val();
                var product = $("#proproduct option:selected").text();
                var factoryID = $('#factory').val();
                var factory = $("#factory option:selected").text();
                var machineID = $('#machine').val();
                var machine = $("#machine option:selected").text();
                var materialID = $('#batchno').val();
                var material = $("#batchno option:selected").text();
                var batchno = material.match(/\(([^)]+)\)/)[1];
                var qty = $('#qty').val();

                $('#tablestartproduction > tbody:last').append('<tr class="pointer"><td>' + product + '</td><td>' + factory + '</td><td>' + machine + '</td><td>' + material + '</td><td>' + qty + '</td><td class="d-none">' + productiondetailID + '</td><td class="d-none">' + factoryID + '</td><td class="d-none">' + machineID + '</td><td class="d-none">' + batchno + '</td><td class="d-none">' + materialID + '</td></tr>');

                $('#proproduct').val('');
                $('#factory').val('');
                $('#batchno').val('');
                $('#qty').val('');
                $('#orderqty').val('');
                $('#issueqty').val('');
                var html2 = '<option value="">Select</option>';
                $('#machine').empty().append(html2);
            }
        });
        $('#btnapproveproduction').click(function () { //alert('IN');
            $('#btnapproveproduction').prop('disabled', true).html('<i class="fas fa-circle-notch fa-spin mr-2"></i> Approve & Start Production')
            var tbody = $("#tablestartproduction tbody");

            if (tbody.children().length > 0) {
                jsonObj = [];
                $("#tablestartproduction tbody tr").each(function () {
                    item = {}
                    $(this).find('td').each(function (col_idx) {
                        item["col_" + (col_idx + 1)] = $(this).text();
                    });
                    jsonObj.push(item);
                });
                // console.log(jsonObj);

                var productionorder = $('#productionorder').val();
                // alert(orderdate);
                $.ajax({
                    type: "POST",
                    data: {
                        tableData: jsonObj,
                        productionorder: productionorder
                    },
                    url: 'Production/Productionstepone',
                    success: function (result) { //alert(result);
                        // console.log(result);
                        var obj = JSON.parse(result);
                        if(obj.status==1){
                            $('#staticBackdrop').modal('hide');
                            setTimeout(window.location.reload(), 3000);
                        }
                        action(obj.action);
                    }
                });
            }

        });
        $('#proproduct').change(function(){
            var id = $(this).val();

            $.ajax({
                type: "POST",
                data: {
                    recordID: id
                },
                url: '<?php echo base_url() ?>Production/Getbatchnoaccoproductiondetail',
                success: function(result) { //alert(result);
                    var obj = JSON.parse(result);
                    var html2 = '';
                    html2 += '<option value="">Select</option>';
                    $.each(obj, function (i, item) {
                        html2 += '<option value="' + obj[i].tbl_material_info_idtbl_material_info + '">';
                        html2 += obj[i].materialname + ' - ' + obj[i].materialinfocode + ' (' + obj[i].batchno + (')');
                        html2 += '</option>';
                    });
                    $('#batchno').empty().append(html2);
                }
            });
            $.ajax({
                type: "POST",
                data: {
                    recordID: id
                },
                url: '<?php echo base_url() ?>Production/Getqtyinfoaccoproductiondetail',
                success: function(result) { //alert(result);
                    var obj = JSON.parse(result);
                    
                    $.each(obj, function (i, item) {
                        $('#orderqty').val(obj[i].qty);
                        $('#issueqty').val(obj[i].sumqty);

                        if(obj[i].qty==obj[i].sumqty){
                            $('#qty').prop('readonly', true);
                        }
                        else{
                            $('#qty').prop('readonly', false);
                        }
                    });
                }
            });
        });
    });

    function deactive_confirm() {
        return confirm("Are you sure you want to deactive this?");
    }

    function active_confirm() {
        return confirm("Are you sure you want to active this?");
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
</script>
<?php include "include/footer.php"; ?>
