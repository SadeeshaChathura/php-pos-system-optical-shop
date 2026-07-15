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
                                    <div class="page-header-icon"><i class="fas fa-chart-line"></i></div>
                                    <span>Sales Report</span>
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
                            <div class="col-12 col-md-2">
                                <label class="small mb-1">Group By</label>
                                <select class="form-control form-control-sm" id="groupby">
                                    <option value="detail">Detail (line items)</option>
                                    <option value="day">Day</option>
                                    <option value="month">Month</option>
                                    <option value="year">Year</option>
                                    <option value="product">Product-wise</option>
                                    <option value="customer">Customer-wise</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-2">
                                <label class="small mb-1">Customer</label>
                                <select class="form-control form-control-sm" id="customerfilter" style="width:100%">
                                    <option value="">All Customers</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-2">
                                <label class="small mb-1">Product</label>
                                <select class="form-control form-control-sm" id="productfilter" style="width:100%">
                                    <option value="">All Products</option>
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
                            <table class="table table-striped table-bordered table-sm nowrap" id="salesTable" style="width:100%">
                                <thead class="table-warning" id="salesThead"></thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr id="salesTfoot"></tr>
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
let salesTable;

// Column definitions per group-by mode
const columnSets = {
    detail: {
        head: ['#', 'Date', 'Invoice No', 'Customer', 'Product', 'Qty', 'Unit Price', 'Discount', 'Line Total'],
        columns: [
            { data: "idtbl_invoice_detail" },
            { data: "invdate" },
            { data: "idtbl_invoice" },
            { data: "name" },
            { data: "materialname" },
            { data: "qty", className: 'text-right' },
            { data: null, className: 'text-right', render: (d, t, f) => addCommas(parseFloat(f.saleprice).toFixed(2)) },
            { data: null, className: 'text-right', render: (d, t, f) => addCommas(parseFloat(f.discount).toFixed(2)) },
            { data: null, className: 'text-right', render: (d, t, f) => addCommas(parseFloat(f.total).toFixed(2)) },
        ],
        order: [[1, "desc"]],
        footLabel: 'Totals', footSpan: 5
    },
    day: {
        head: ['Day', 'Invoices', 'Total Qty', 'Total Sales'],
        columns: [
            { data: "period" },
            { data: "invoicecount" },
            { data: "totalqty", className: 'text-right' },
            { data: null, className: 'text-right', render: (d, t, f) => addCommas(parseFloat(f.totalsales).toFixed(2)) },
        ],
        order: [[0, "desc"]],
        footLabel: 'Totals', footSpan: 1
    },
    month: {
        head: ['Month', 'Invoices', 'Total Qty', 'Total Sales'],
        columns: [
            { data: "period" },
            { data: "invoicecount" },
            { data: "totalqty", className: 'text-right' },
            { data: null, className: 'text-right', render: (d, t, f) => addCommas(parseFloat(f.totalsales).toFixed(2)) },
        ],
        order: [[0, "desc"]],
        footLabel: 'Totals', footSpan: 1
    },
    year: {
        head: ['Year', 'Invoices', 'Total Qty', 'Total Sales'],
        columns: [
            { data: "period" },
            { data: "invoicecount" },
            { data: "totalqty", className: 'text-right' },
            { data: null, className: 'text-right', render: (d, t, f) => addCommas(parseFloat(f.totalsales).toFixed(2)) },
        ],
        order: [[0, "desc"]],
        footLabel: 'Totals', footSpan: 1
    },
    product: {
        head: ['Product', 'Invoices', 'Total Qty', 'Total Sales'],
        columns: [
            { data: "materialname" },
            { data: "invoicecount" },
            { data: "totalqty", className: 'text-right' },
            { data: null, className: 'text-right', render: (d, t, f) => addCommas(parseFloat(f.totalsales).toFixed(2)) },
        ],
        order: [[3, "desc"]],
        footLabel: 'Totals', footSpan: 1
    },
    customer: {
        head: ['Customer', 'Invoices', 'Total Qty', 'Total Sales'],
        columns: [
            { data: "customername" },
            { data: "invoicecount" },
            { data: "totalqty", className: 'text-right' },
            { data: null, className: 'text-right', render: (d, t, f) => addCommas(parseFloat(f.totalsales).toFixed(2)) },
        ],
        order: [[3, "desc"]],
        footLabel: 'Totals', footSpan: 1
    }
};

$(document).ready(function () {

    $('#customerfilter').select2({
        placeholder: "All Customers",
        allowClear: true,
        ajax: {
            url: "scripts/getcustomerlist.php",
            type: "POST",
            dataType: "json",
            delay: 250,
            data: function (params) { return { term: params.term }; },
            processResults: function (data) { return { results: data.results }; }
        },
        minimumInputLength: 0
    });

    $('#productfilter').select2({
        placeholder: "All Products",
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

    buildTable($('#groupby').val());

    $('#btnapply').on('click', function () { buildTable($('#groupby').val()); });

    $('#btnreset').on('click', function () {
        $('#quickrange').val('');
        $('#datefrom').val('');
        $('#dateto').val('');
        $('#groupby').val('detail');
        $('#customerfilter').val(null).trigger('change');
        $('#productfilter').val(null).trigger('change');
        buildTable('detail');
    });
});

function buildTable(mode) {
    let cfg = columnSets[mode];

    // rebuild thead
    let theadHtml = '<tr>' + cfg.head.map(h => '<th>' + h + '</th>').join('') + '</tr>';
    $('#salesThead').html(theadHtml);

    // rebuild tfoot
    let footCells = '<th colspan="' + cfg.footSpan + '" class="text-right">' + cfg.footLabel + '</th>';
    if (mode === 'detail') {
        footCells += '<th id="foot_qty" class="text-right"></th><th></th><th></th><th id="foot_total" class="text-right"></th>';
    } else {
        footCells += '<th></th><th id="foot_qty" class="text-right"></th><th id="foot_total" class="text-right"></th>';
    }
    $('#salesTfoot').html(footCells);

    if (salesTable) { salesTable.destroy(); $('#salesTable tbody').empty(); }

    salesTable = $('#salesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "scripts/rptsaleslist.php",
            type: "POST",
            data: function (d) {
                d.date_from = $('#datefrom').val();
                d.date_to = $('#dateto').val();
                d.customer_id = $('#customerfilter').val();
                d.product_id = $('#productfilter').val();
                d.groupby = mode;
            },
            dataSrc: function (json) {
                $('#foot_qty').text(addCommas(parseFloat(json.sum_qty || 0).toFixed(2)));
                $('#foot_total').text(addCommas(parseFloat(json.sum_total || 0).toFixed(2)));
                return json.data;
            }
        },
        order: cfg.order,
        columns: cfg.columns,
        dom: "<'row'<'col-sm-4'B><'col-sm-3'l><'col-sm-5'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        responsive: true,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'All']],
        buttons: [
            { extend: 'csv', className: 'btn btn-ocean-blue btn-sm', filename: 'Sales Report ' + today, text: '<i class="fas fa-file-csv mr-2"></i> CSV' },
            { extend: 'excel', className: 'btn btn-ocean-blue btn-sm', filename: 'Sales Report ' + today, text: '<i class="fas fa-file-excel mr-2"></i> EXCEL' },
            { extend: 'pdf', className: 'btn btn-ocean-blue btn-sm', filename: 'Sales Report ' + today, text: '<i class="fas fa-file-pdf mr-2"></i> PDF', orientation: 'landscape' },
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