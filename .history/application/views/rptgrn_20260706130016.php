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
                        <div class="row">
                            <div class="col-sm-12">
                                <h1 class="page-header-title">
                                    <div class="page-header-icon"><i class="fas fa-truck"></i></div>
                                    <span>GRN Report</span>
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body">

                        <!-- FILTERS -->
                        <div class="row mb-2">
                            <div class="col-12 col-md-2">
                                <label class="small mb-1">Quick Range</label>
                                <select class="form-control form-control-sm" id="quickrange">
                                    <option value="">Custom</option>
                                    <option value="today">Today</option>
                                    <option value="week">This Week</option>
                                    <option value="month">This Month</option>
                                    <option value="year">This Year</option>
                                </select>
                            </div>
                            <div class="col-6 col-md-2">
                                <label class="small mb-1">Date From</label>
                                <input type="date" class="form-control form-control-sm" id="datefrom">
                            </div>
                            <div class="col-6 col-md-2">
                                <label class="small mb-1">Date To</label>
                                <input type="date" class="form-control form-control-sm" id="dateto">
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="small mb-1">Supplier</label>
                                <select class="form-control form-control-sm" id="supplierfilter" style="width:100%">
                                    <option value="">All Suppliers</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="small mb-1">Material</label>
                                <select class="form-control form-control-sm" id="materialfilter" style="width:100%">
                                    <option value="">All Materials</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-12 text-right">
                                <button type="button" class="btn btn-sm btn-secondary" id="btnreset"><i class="fas fa-undo mr-1"></i>Reset</button>
                                <button type="button" class="btn btn-sm btn-primary" id="btnapply"><i class="fas fa-filter mr-1"></i>Apply Filter</button>
                            </div>
                        </div>

                        <hr class="border-dark">

                        <div class="scrollbar pb-3" id="style-2">
                            <table class="table table-striped table-bordered table-sm nowrap" id="grnTable" style="width:100%">
                                <thead class="table-warning">
                                    <tr>
                                        <th></th>
                                        <th>#</th>
                                        <th>GRN Date</th>
                                        <th>GRN No</th>
                                        <th>Batch No</th>
                                        <th>Supplier</th>
                                        <th>Material</th>
                                        <th>Qty</th>
                                        <th>Unit Price</th>
                                        <th>Sale Price</th>
                                        <th>Line Total</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th colspan="6" class="text-right">Totals</th>
                                        <th id="foot_qty" class="text-right"></th>
                                        <th></th>
                                        <th></th>
                                        <th id="foot_total" class="text-right"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </main>
        <?php include "include/footerbar.php"; ?>
    </div>
</div>
<?php include "include/footerscripts.php"; ?>

<script type="text/javascript">
let today = new Date().toISOString().slice(0, 10);
let grnTable;

$(document).ready(function () {

    // Supplier select2 (ajax search)
    $('#supplierfilter').select2({
        placeholder: "All Suppliers",
        allowClear: true,
        ajax: {
            url: "scripts/getsupplierlist.php",
            type: "POST",
            dataType: "json",
            delay: 250,
            data: function (params) { return { term: params.term }; },
            processResults: function (data) { return { results: data.results }; }
        },
        minimumInputLength: 0
    });

    // Material select2 (ajax search)
    $('#materialfilter').select2({
        placeholder: "All Materials",
        allowClear: true,
        ajax: {
            url: "scripts/getmateriallist.php",
            type: "POST",
            dataType: "json",
            delay: 250,
            data: function (params) { return { term: params.term }; },
            processResults: function (data) { return { results: data.results }; }
        },
        minimumInputLength: 0
    });

    $('#quickrange').on('change', function () {
        let val = $(this).val();
        let now = new Date();
        let from = '', to = today;
        if (val === 'today') { from = today; }
        else if (val === 'week') {
            let d = new Date(); let day = d.getDay() === 0 ? 6 : d.getDay() - 1;
            let monday = new Date(d); monday.setDate(d.getDate() - day);
            from = monday.toISOString().slice(0, 10);
        }
        else if (val === 'month') { from = now.toISOString().slice(0, 8) + '01'; }
        else if (val === 'year') { from = now.getFullYear() + '-01-01'; }
        if (val) { $('#datefrom').val(from); $('#dateto').val(to); }
    });

    buildTable();

    $('#btnapply').on('click', function () { grnTable.ajax.reload(); });

    $('#btnreset').on('click', function () {
        $('#quickrange').val('');
        $('#datefrom').val('');
        $('#dateto').val('');
        $('#supplierfilter').val(null).trigger('change');
        $('#materialfilter').val(null).trigger('change');
        grnTable.ajax.reload();
    });
});

function buildTable() {
    grnTable = $('#grnTable').DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: "scripts/rptgrnlist.php",
            type: "POST",
            data: function (d) {
                d.date_from = $('#datefrom').val();
                d.date_to = $('#dateto').val();
                d.supplier_id = $('#supplierfilter').val();
                d.material_id = $('#materialfilter').val();
            },
            dataSrc: function (json) {
                $('#foot_qty').text(addCommas(parseFloat(json.sum_qty || 0).toFixed(2)));
                $('#foot_total').text(addCommas(parseFloat(json.sum_total || 0).toFixed(2)));
                return json.data;
            }
        },
        order: [[2, "desc"]],
        columns: [
            { data: "idtbl_grndetail", visible: false },
            { data: "idtbl_grn" },
            { data: "grndate" },
            { data: "invoicenum" },
            { data: "batchno" },
            { data: "suppliername" },
            { data: "materialname" },
            { data: "qty", className: 'text-right' },
            {
                data: null, className: 'text-right',
                render: function (d, t, full) { return addCommas(parseFloat(full.unitprice).toFixed(2)); }
            },
            {
                data: null, className: 'text-right',
                render: function (d, t, full) { return addCommas(parseFloat(full.saleprice).toFixed(2)); }
            },
            {
                data: null, className: 'text-right',
                render: function (d, t, full) { return addCommas(parseFloat(full.total).toFixed(2)); }
            },
        ],
        dom: "<'row'<'col-sm-4'B><'col-sm-3'l><'col-sm-5'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        responsive: true,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'All']],
        buttons: [
            { extend: 'csv',  title: 'Kannadiya Pvt Ltd', className: 'btn btn-ocean-blue btn-sm', filename: 'GRN Report ' + today, text: '<i class="fas fa-file-csv mr-2"></i> CSV' },
            { extend: 'excel',  title: 'Kannadiya Pvt Ltd', className: 'btn btn-ocean-blue btn-sm', filename: 'GRN Report ' + today, text: '<i class="fas fa-file-excel mr-2"></i> EXCEL' },
            { extend: 'pdf',  title: 'Kannadiya Pvt Ltd', className: 'btn btn-ocean-blue btn-sm', filename: 'GRN Report ' + today, text: '<i class="fas fa-file-pdf mr-2"></i> PDF'},
            { extend: 'print', className: 'btn btn-ocean-blue btn-sm', text: '<i class="fas fa-print mr-2"></i> PRINT' }
        ]
    });
}

function addCommas(nStr) {
    nStr += '';
    let x = nStr.split('.');
    let x1 = x[0];
    let x2 = x.length > 1 ? '.' + x[1] : '';
    let rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) { x1 = x1.replace(rgx, '$1' + ',' + '$2'); }
    return x1 + x2;
}
</script>

<?php include "include/footer.php"; ?>