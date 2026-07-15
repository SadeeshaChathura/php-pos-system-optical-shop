<?php 
include "include/header.php"; 

include "include/topnavbar.php"; 
?>

<style>
    content-display {
        display: none;
    }
</style>


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
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <h1 class="page-header-title">
                                    <div class="page-header-icon"><i class="fas fa-file-alt"></i></div>
                                    <span>Invoice View</span>
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
            	<div class="card">
            		<div class="card-body">

            			<div class="row">
            				<div class="col-12">
            					<div class="scrollbar pb-3" id="style-2">
            						<table class="table table-striped table-bordered table-sm nowrap" id="invoicetable"
            							style="width:100%">
            							<thead class="table-warning">
            								<tr>
            									<th>#</th>
            									<th>Invoice Date</th>
                                                <th>Invoice No.</th>
            									<th>Customer</th>
            									<th>Net Total</th>
            									<th>Action</th>
            								</tr>
            							</thead>
            							<tbody>
            							</tbody>
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
<!-- Modal -->
<div class="modal fade" id="viewinvoicedetails" data-backdrop="static" data-keyboard="false" tabindex="-1"
	aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalScrollableTitle">Update Batch Number</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="col-12">
					<form id="createorderform2" autocomplete="off">
						<div class="form-row mb-1">
							<label class="small font-weight-bold">Product*</label><br>
							<select class="form-control form-control-sm" name="productlist[]" id="productlist" required style="width: 100%;">
							</select>
						</div>
                        <div class="form-row mb-1">
                        	<label class="small font-weight-bold">Location*</label>
                        	<select class="form-control form-control-sm" name="location" id="location">
                        		<option value="">Select</option>
                        		<?php foreach($location->result() as $rowlocationlist){ ?>
                        		<option value="<?php echo $rowlocationlist->idtbl_location ?>">
                        			<?php echo $rowlocationlist->location ?></option>
                        		<?php } ?>
                        	</select>
                        </div>
						<div class="form-row mb-1">
							<label class="small font-weight-bold">Batch No.*</label><br>
							<select class="form-control form-control-sm" name="batchlist[]" id="batchlist" required
								multiple style="width: 100%;">
							</select>
						</div>
                        <input type="hidden" id="invoice_id" name="invoice_id">
						<div class="form-group mt-3 text-right">
							<button type="button" id="formsubmit2" class="btn btn-primary btn-sm px-4"
								<?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-edit"></i>&nbsp;Update Invoice</button>
							<input name="submitBtn" type="submit2" value="Save" id="submitBtn" class="d-none">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Modal -->
<div class="modal fade" id="viewInvoicelist" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
	aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalScrollableTitle">View Invoice List</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="viewdata"></div>
			</div>
		</div>
	</div>
</div>
<?php include "include/footerscripts.php"; ?>


<script type="text/javascript">
    let today = new Date().toISOString().slice(0, 10)


    $(document).ready(function () {

        var addcheck='<?php echo $addcheck; ?>';
        var editcheck='<?php echo $editcheck; ?>';
        var statuscheck='<?php echo $statuscheck; ?>';
        var deletecheck='<?php echo $deletecheck; ?>';

        $("#batchlist").select2();
        $("#productlist").select2();

        $('#invoicetable').DataTable({
            "destroy": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "scripts/invoiceviewlist.php",
                type: "POST", // you can use GET
            },
            "order": [
                [0, "desc"]
            ],
            "columns": [{
                        "data": null,
                        "render": function(data, type, full, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        "data": "invdate"
                    },
                    { data: 'idtbl_invoice', render: function(d){ return 'INV-' + String(d).padStart(6,'0'); } },
                    {
                        "data": "name",
                        "render": function(data, type, full) {
                            if (full['tbl_customer_idtbl_customer'] == 0) {
                                return "Walking Customer";
                            } else {
                                return  data;
                            }
                        }
                    },
                    {
                        "targets": -1,
                        "className": 'text-left',
                        "data": null,
                        "render": function(data, type, full) {
                            return addCommas(parseFloat(full['nettotal']).toFixed(2));
                        }
                    },
{
    "targets": -1,
    "className": 'text-right',
    "data": null,
    "render": function (data, type, full) {

        var button = '';

        button += '<button class="btn btn-secondary btn-sm btnviewList mr-1 ';
        if (editcheck != 1) { button += 'd-none'; }
        button += '" id="' + full['idtbl_invoice'] + '"><i class="fas fa-list"></i></button>';

        // =========================
        // PRINT BUTTON SWITCH
        // =========================
        var printUrl = '';

        if (full['invtype'] == 1) {
            printUrl = '<?php echo base_url() ?>Directsale/Getposprintbill/' + full['idtbl_invoice'];
        } else if (full['invtype'] == 2) {
            printUrl = '<?php echo base_url() ?>Directsale/Getcreditprintbill/' + full['idtbl_invoice'];
        } else {
            printUrl = '<?php echo base_url() ?>Invoiceview/printreportpos/' + full['idtbl_invoice'];
        }

        button += '<a href="' + printUrl + '" target="_blank" class="btn btn-primary btn-sm">' +
                   '<i class="fas fa-file-invoice"></i></a>';

        return button;
    }
}


            ],

            dom: "<'row'<'col-sm-5'B><'col-sm-2'l><'col-sm-5'f>>" + 
                "<'row'<'col-sm-12'tr>>" + 
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            responsive: true,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, 'All'],
            ],
            buttons: [
                { extend: 'csv', className: 'btn btn-ocean-blue btn-sm', title: 'Invoice List Information', text: '<i class="fas fa-file-csv mr-2"></i> CSV' },
                { extend: 'pdf', className: 'btn btn-ocean-blue btn-sm', title: 'Invoice List Information', text: '<i class="fas fa-file-pdf mr-2"></i> PDF' },
                { 
                    extend: 'print', 
                    title: 'Invoice List Information',
                    className: 'btn btn-ocean-blue btn-sm', 
                    text: '<i class="fas fa-print mr-2"></i> Print',
                    customize: function (win) {
                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    }, 
                },
            ],

                drawCallback: function (settings) {
                    $('[data-toggle="tooltip"]').tooltip();
                }
        });

    $(document).on("click", ".btnviewList", function () {
	var id = $(this).attr('id');
    $('#viewInvoicelist').modal('show');
	// alert(id);
	$.ajax({
		type: "POST",
		data: {
			recordID: id
		},
		url: '<?php echo base_url() ?>Invoiceview/Getinvoicedetails',
		success: function (result) { //alert(result);

    		$('#viewdata').html(result);
    		$('#tblInvoicelist').DataTable({
    			"ordering": false,
    			dom: "<'row'<'col-sm-5'B><'col-sm-2'l><'col-sm-5'f>>" + 
                    "<'row'<'col-sm-12'tr>>" + 
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                responsive: true,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, 'All'],
                ],
                buttons: [
                    { extend: 'csv', className: 'btn btn-ocean-blue btn-sm', title: 'Invoice List Information', text: '<i class="fas fa-file-csv mr-2"></i> CSV' },
                    { extend: 'pdf', className: 'btn btn-ocean-blue btn-sm', title: 'Invoice List Information', text: '<i class="fas fa-file-pdf mr-2"></i> PDF' },
                    { 
                        extend: 'print', 
                        title: 'Invoice List Information',
                        className: 'btn btn-ocean-blue btn-sm', 
                        text: '<i class="fas fa-print mr-2"></i> Print',
                        customize: function (win) {
                            $(win.document.body).find('table')
                                .addClass('compact')
                                .css('font-size', 'inherit');
                        }, 
                    },
                ],
                footerCallback: function (row, data, start, end, display) {
                    var api = this.api();

                    // Remove the formatting to get integer data for summation
                    var intVal = function (i) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                            i : 0;
                    };

                    // Total over all pages
                    total = api
                        .column(3)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    // Total over this page
                    pageTotal = api
                        .column(3, {
                            page: 'current'
                        })
                        .data()
                        .reduce(function (a, b) {
                            return parseFloat(intVal(a) + intVal(b)).toFixed(2);
                        }, 0);

                    // Update footer
                    $(api.column(3).footer()).html(
                        // pageTotal=parseFloat(pageTotal).toFixed(2);
                        'Rs. ' + pageTotal
                    );

                },
                drawCallback: function (settings) {
                    $('[data-toggle="tooltip"]').tooltip();
                }
    		});;


		}
	});
});

$('#invoicetable tbody').on('click', '.btnUpdatebatch', function() {
        var r = confirm("Are you sure, You want to Edit this?");
        if (r == true) {
            var id = $(this).attr('id');
            $('#invoice_id').val(id); // Set the invoice ID
            $('#viewinvoicedetails').modal('show');
            $.ajax({
                type: "POST",
                data: { invoice_id: id },
                url: '<?php echo base_url() ?>Invoiceview/GetProductsByInvoiceID',
                success: function(result) {
                    var products = JSON.parse(result);
                    $('#productlist').empty(); // Clear the dropdown
                    $.each(products, function(index, product) {
                        $('#productlist').append(new Option(product.text, product.id));
                    });
                    $('#productlist').trigger('change'); // Refresh the Select2 picker
                }
            });
        }
    });

    $('#productlist').on('change', function() {
        var product_id = $(this).val();
        var location_id = $('#location').val();
        if (product_id && location_id) {
            $.ajax({
                type: "POST",
                data: {
                    product_id: product_id,
                    location_id: location_id
                },
                url: '<?php echo base_url() ?>Invoiceview/GetBatchesByProductAndLocation',
                success: function(result) {
                    var batches = JSON.parse(result);
                    $('#batchlist').empty();
                    $.each(batches, function(index, batch) {
                        $('#batchlist').append(new Option(batch.text, batch.fgbatchno));
                    });
                    $('#batchlist').trigger('change');
                }
            });
        }
    });

    $('#formsubmit2').on('click', function() {
        var invoice_id = $('#invoice_id').val();
        var product_id = $('#productlist').val();
        var batchnos = $('#batchlist').val();

        $.ajax({
            type: "POST",
            data: {
                invoice_id: invoice_id,
                product_id: product_id,
                batchnos: batchnos
            },
            url: '<?php echo base_url() ?>Invoiceview/UpdateBatchNumbers',
            success: function(result) {
                var obj = JSON.parse(result);
                if (obj.status === 'success') {
                    alert('Batch numbers updated successfully');
                    $('#viewinvoicedetails').modal('hide');
                } else {
                    alert('Failed to update batch numbers');
                }
            }
        });
    });

    $('#location').on('change', function () {
    	$('#productlist').trigger('change');
    });

    $('#productlist').select2();
    $('#batchlist').select2();

});

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