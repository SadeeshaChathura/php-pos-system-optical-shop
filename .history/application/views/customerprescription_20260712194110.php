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
                            <div class="page-header-icon"><i class="fas fa-file"></i></div>
                            <span>Customer Prescription</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">

                <!-- Select Customer -->
                <div class="card mb-2">
                    <div class="card-header">Select Customer</div>
                    <div class="card-body">
                        <div class="row align-items-end">
                            <div class="col-md-8">
                                <label for="customer_select">Customer</label>
                                <select id="customer_select" class="form-control" style="width:100%"></select>
                            </div>
                            <div class="col-md-4 mt-2 mt-md-0 text-md-right">
                                <button type="button" id="btnAddPrescription" class="btn btn-primary" disabled data-toggle="modal" data-target="#prescriptionModal">
                                    <i class="fas fa-plus"></i> Add New Prescription
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Prescription History -->
                <div class="card">
                    <div class="card-header">Prescription History</div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0" id="prescriptionTable">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Frame Model</th>
                                        <th>Lens</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">Select a customer to view prescription history.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </main>
        <?php include "include/footerbar.php"; ?>
    </div>
</div>

<!-- ================= Add / Edit Prescription Modal ================= -->
<div class="modal fade" id="prescriptionModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form id="frmPrescription" method="post" action="<?php echo base_url('CustomerPrescription/Customerprescriptioninsertupdate'); ?>">
                <div class="modal-header">
                    <h5 class="modal-title" id="prescriptionModalTitle">Add New Prescription</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">

                    <input type="hidden" name="customerid" id="frm_customerid" value="<?php echo isset($selectedcustomerid) ? $selectedcustomerid : ''; ?>">
                    <input type="hidden" name="recordOption" id="frm_recordOption" value="1">
                    <input type="hidden" name="recordID" id="frm_recordID" value="">

                    <!-- Date -->
                    <div class="rx-section">
                        <div class="form-row align-items-end">
                            <div class="form-group col-md-3 mb-0">
                                <label class="rx-label">Date</label>
                                <input type="date" class="form-control" name="prescriptiondate" id="prescriptiondate" required>
                            </div>
                        </div>
                    </div>

                    <!-- Sph / Cyl / Axis grid -->
                    <div class="rx-section">
                        <h6 class="rx-section-title"><i class="fas fa-eye"></i> Prescription (Sph / Cyl / Axis)</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered text-center rx-grid mb-0">
                                <thead>
                                    <tr>
                                        <th class="rx-grid-corner"></th>
                                        <th colspan="3" class="rx-eye-head">Right Eye</th>
                                        <th colspan="3" class="rx-eye-head">Left Eye</th>
                                    </tr>
                                    <tr>
                                        <th class="rx-grid-corner"></th>
                                        <th>Sph</th><th>Cyl</th><th>Axis</th>
                                        <th>Sph</th><th>Cyl</th><th>Axis</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th class="align-middle rx-row-label">Distance</th>
                                        <td><input type="text" class="form-control form-control-sm text-center" name="distance_r_sph"></td>
                                        <td><input type="text" class="form-control form-control-sm text-center" name="distance_r_cyl"></td>
                                        <td><input type="text" class="form-control form-control-sm text-center" name="distance_r_axis"></td>
                                        <td><input type="text" class="form-control form-control-sm text-center" name="distance_l_sph"></td>
                                        <td><input type="text" class="form-control form-control-sm text-center" name="distance_l_cyl"></td>
                                        <td><input type="text" class="form-control form-control-sm text-center" name="distance_l_axis"></td>
                                    </tr>
                                    <tr>
                                        <th class="align-middle rx-row-label">Read ADD</th>
                                        <td><input type="text" class="form-control form-control-sm text-center" name="readadd_r_sph"></td>
                                        <td><input type="text" class="form-control form-control-sm text-center" name="readadd_r_cyl"></td>
                                        <td><input type="text" class="form-control form-control-sm text-center" name="readadd_r_axis"></td>
                                        <td><input type="text" class="form-control form-control-sm text-center" name="readadd_l_sph"></td>
                                        <td><input type="text" class="form-control form-control-sm text-center" name="readadd_l_cyl"></td>
                                        <td><input type="text" class="form-control form-control-sm text-center" name="readadd_l_axis"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Lens type / material -->
                    <div class="rx-section">
                        <h6 class="rx-section-title"><i class="fas fa-circle-notch"></i> Lens</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="rx-label">Lens Type</label>
                                <div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input class="custom-control-input" type="radio" name="lens_vision" id="lv_sv" value="S/V">
                                        <label class="custom-control-label" for="lv_sv">S/V</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input class="custom-control-input" type="radio" name="lens_vision" id="lv_bf" value="Bi Focal">
                                        <label class="custom-control-label" for="lv_bf">Bi Focal</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input class="custom-control-input" type="radio" name="lens_vision" id="lv_pg" value="Progressive">
                                        <label class="custom-control-label" for="lv_pg">Progressive</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="rx-label">Lens Material</label>
                                <div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input class="custom-control-input" type="radio" name="lens_material" id="lm_cr39" value="CR-39">
                                        <label class="custom-control-label" for="lm_cr39">CR-39</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input class="custom-control-input" type="radio" name="lens_material" id="lm_glass" value="Glass">
                                        <label class="custom-control-label" for="lm_glass">Glass</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Frame details -->
                    <div class="rx-section">
                        <h6 class="rx-section-title"><i class="fas fa-glasses"></i> Frame Details</h6>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label class="rx-label">Frame Model</label>
                                <input type="text" class="form-control" name="frame_model">
                            </div>
                            <div class="form-group col-md-3">
                                <label class="rx-label">Rim Bridge Size</label>
                                <input type="text" class="form-control" name="rim_bridge_size">
                            </div>
                            <div class="form-group col-md-3">
                                <label class="rx-label">Colour</label>
                                <input type="text" class="form-control" name="colour">
                            </div>
                            <div class="form-group col-md-3">
                                <label class="rx-label">Length of Temple to Bend</label>
                                <input type="text" class="form-control" name="temple_length">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3 mb-0">
                                <label class="rx-label">Wt.</label>
                                <input type="text" class="form-control" name="weight">
                            </div>
                            <div class="form-group col-md-9 mb-0">
                                <label class="rx-label d-block">Coating</label>
                                <div>
                                    <div class="custom-control custom-checkbox custom-control-inline">
                                        <input class="custom-control-input" type="checkbox" name="coat_a2" id="coat_a2" value="1">
                                        <label class="custom-control-label" for="coat_a2">A-2</label>
                                    </div>
                                    <div class="custom-control custom-checkbox custom-control-inline">
                                        <input class="custom-control-input" type="checkbox" name="coat_sp20" id="coat_sp20" value="1">
                                        <label class="custom-control-label" for="coat_sp20">SP-20</label>
                                    </div>
                                    <div class="custom-control custom-checkbox custom-control-inline">
                                        <input class="custom-control-input" type="checkbox" name="coat_hmc" id="coat_hmc" value="1">
                                        <label class="custom-control-label" for="coat_hmc">H.M.C</label>
                                    </div>
                                    <div class="custom-control custom-checkbox custom-control-inline">
                                        <input class="custom-control-input" type="checkbox" name="coat_pgx" id="coat_pgx" value="1">
                                        <label class="custom-control-label" for="coat_pgx">P.G.X</label>
                                    </div>
                                    <div class="custom-control custom-checkbox custom-control-inline">
                                        <input class="custom-control-input" type="checkbox" name="coat_pbx" id="coat_pbx" value="1">
                                        <label class="custom-control-label" for="coat_pbx">P.B.X</label>
                                    </div>
                                    <div class="custom-control custom-checkbox custom-control-inline">
                                        <input class="custom-control-input" type="checkbox" name="coat_bluecut" id="coat_bluecut" value="1">
                                        <label class="custom-control-label" for="coat_bluecut">Blue Cut</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Remarks -->
                    <div class="rx-section rx-section-last">
                        <h6 class="rx-section-title"><i class="fas fa-sticky-note"></i> Remarks</h6>
                        <textarea class="form-control" name="remarks" rows="2" placeholder="Any additional notes..."></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Prescription</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ================= View / Print Prescription Modal ================= -->
<div class="modal fade" id="viewPrescriptionModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Prescription Details</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="printablePrescription">
                <!-- filled by JS -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="window.print()"><i class="fas fa-print"></i> Print</button>
            </div>
        </div>
    </div>
</div>

<?php include "include/footerscripts.php"; ?>
<style>
    #prescriptionModal .modal-content {
        border: none;
        border-radius: 6px;
    }
    #prescriptionModal .modal-header {
        background: var(--qp-accent, #0d7890);
        color: #fff;
        border-radius: 6px 6px 0 0;
    }
    #prescriptionModal .modal-header .close {
        color: #fff;
        opacity: .85;
        text-shadow: none;
    }
    #prescriptionModal .modal-header .close:hover {
        opacity: 1;
    }
    #prescriptionModal .modal-body {
        background: #f7f9fa;
        padding: 1.5rem;
    }

    .rx-section {
        background: #fff;
        border: 1px solid #e3e6ea;
        border-radius: 6px;
        padding: 1rem 1.25rem;
        margin-bottom: 1rem;
    }
    .rx-section-last {
        margin-bottom: 0;
    }
    .rx-section-title {
        font-size: .85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .03em;
        color: var(--qp-accent, #0d7890);
        margin-bottom: .85rem;
        padding-bottom: .5rem;
        border-bottom: 1px solid #eef1f3;
    }
    .rx-section-title i {
        margin-right: .4rem;
    }
    .rx-label {
        font-size: .8rem;
        font-weight: 600;
        color: #5a6472;
        margin-bottom: .35rem;
    }

    .rx-grid thead th {
        background: #eef4f6;
        font-size: .8rem;
        font-weight: 600;
        color: #45525c;
        vertical-align: middle;
        padding: .5rem;
    }
    .rx-grid .rx-eye-head {
        background: var(--qp-accent, #0d7890);
        color: #fff;
    }
    .rx-grid .rx-grid-corner {
        background: #fff;
        border: none;
    }
    .rx-grid .rx-row-label {
        background: #f7f9fa;
        font-size: .85rem;
        color: #45525c;
        white-space: nowrap;
    }
    .rx-grid td {
        padding: .4rem;
        vertical-align: middle;
    }

    .custom-control-inline {
        margin-right: 1.5rem;
    }

    #prescriptionModal .modal-footer {
        background: #fff;
        border-top: 1px solid #e3e6ea;
    }

    @media print {
        body * { visibility: hidden; }
        #printablePrescription, #printablePrescription * { visibility: visible; }
        #printablePrescription { position: absolute; top: 0; left: 0; width: 100%; }
    }
</style>
<script>
    var BASE_URL = "<?php echo base_url(); ?>";

    // If we've redirected back to a specific customer after save, pre-seed the select2 option
    <?php if (!empty($selectedcustomer)): ?>
    var PRESELECTED_CUSTOMER = {
        id: "<?php echo $selectedcustomer->idtbl_customer; ?>",
        text: "<?php echo addslashes($selectedcustomer->name.' - '.$selectedcustomer->contact.' ('.$selectedcustomer->customercode.')'); ?>"
    };
    <?php else: ?>
    var PRESELECTED_CUSTOMER = null;
    <?php endif; ?>

    $(function () {

        // Customer search (Select2)
        $('#customer_select').select2({
            placeholder: 'Search customer by name, contact or code...',
            allowClear: true,
            ajax: {
                url: BASE_URL + 'CustomerPrescription/Customersearch',
                type: 'POST',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return { term: params.term || '' };
                },
                processResults: function (data) {
                    return data;
                }
            }
        });

        if (PRESELECTED_CUSTOMER) {
            var opt = new Option(PRESELECTED_CUSTOMER.text, PRESELECTED_CUSTOMER.id, true, true);
            $('#customer_select').append(opt).trigger('change');
        }

        $('#customer_select').on('change', function () {
            var customerId = $(this).val();
            $('#frm_customerid').val(customerId);
            $('#btnAddPrescription').prop('disabled', !customerId);
            loadPrescriptionList(customerId);
        });

        function loadPrescriptionList(customerId) {
            if (!customerId) {
                $('#prescriptionTable tbody').html('<tr><td colspan="4" class="text-center text-muted">Select a customer to view prescription history.</td></tr>');
                return;
            }
            $.post(BASE_URL + 'CustomerPrescription/Customerprescriptionlist', { customerid: customerId }, function (data) {
                var rows = '';
                if (data && data.length) {
                    $.each(data, function (i, p) {
                        rows += '<tr>' +
                            '<td>' + p.prescriptiondate + '</td>' +
                            '<td>' + (p.frame_model || '-') + '</td>' +
                            '<td>' + (p.lens_vision || '-') + (p.lens_material ? ' / ' + p.lens_material : '') + '</td>' +
                            '<td class="text-center">' +
                                '<button type="button" class="btn btn-sm btn-info btnView" data-id="' + p.idtbl_customer_prescription + '"><i class="fas fa-eye"></i></button> ' +
                                '<button type="button" class="btn btn-sm btn-primary btnEdit" data-id="' + p.idtbl_customer_prescription + '"><i class="fas fa-edit"></i></button> ' +
                                '<a href="' + BASE_URL + 'CustomerPrescription/Customerprescriptionstatus/' + p.idtbl_customer_prescription + '/3" class="btn btn-sm btn-danger" onclick="return delete_confirm();"><i class="fas fa-trash-alt"></i></a>' +
                            '</td>' +
                            '</tr>';
                    });
                } else {
                    rows = '<tr><td colspan="4" class="text-center text-muted">No prescriptions found for this customer.</td></tr>';
                }
                $('#prescriptionTable tbody').html(rows);
            }, 'json');
        }

        // Reset + default date when opening the modal for a new record
        $('#btnAddPrescription').on('click', function () {
            $('#frmPrescription')[0].reset();
            $('#frm_recordOption').val('1');
            $('#frm_recordID').val('');
            $('#frm_customerid').val($('#customer_select').val());
            $('#prescriptiondate').val(new Date().toISOString().slice(0, 10));
            $('#prescriptionModalTitle').text('Add New Prescription');
        });

        // Edit prescription - prefill the same modal, switch to update mode
        $(document).on('click', '.btnEdit', function () {
            var id = $(this).data('id');
            $.post(BASE_URL + 'CustomerPrescription/Customerprescriptionedit', { recordID: id }, function (obj) {
                var frm = $('#frmPrescription')[0];
                frm.reset();

                $('#frm_recordOption').val('2');
                $('#frm_recordID').val(obj.id);
                $('#frm_customerid').val(obj.customerid);
                $('#prescriptionModalTitle').text('Edit Prescription');

                $('#prescriptiondate').val(obj.prescriptiondate);

                $.each(['distance_r_sph','distance_r_cyl','distance_r_axis','distance_l_sph','distance_l_cyl','distance_l_axis',
                        'readadd_r_sph','readadd_r_cyl','readadd_r_axis','readadd_l_sph','readadd_l_cyl','readadd_l_axis',
                        'frame_model','rim_bridge_size','colour','temple_length','weight','remarks'], function (i, field) {
                    $('[name="' + field + '"]').val(obj[field]);
                });

                $('input[name="lens_vision"][value="' + obj.lens_vision + '"]').prop('checked', true);
                $('input[name="lens_material"][value="' + obj.lens_material + '"]').prop('checked', true);

                $('#coat_a2').prop('checked', obj.coat_a2 == 1);
                $('#coat_sp20').prop('checked', obj.coat_sp20 == 1);
                $('#coat_hmc').prop('checked', obj.coat_hmc == 1);
                $('#coat_pgx').prop('checked', obj.coat_pgx == 1);
                $('#coat_pbx').prop('checked', obj.coat_pbx == 1);
                $('#coat_bluecut').prop('checked', obj.coat_bluecut == 1);

                $('#prescriptionModal').modal('show');
            }, 'json');
        });

        // View prescription (print-friendly)
        $(document).on('click', '.btnView', function () {
            var id = $(this).data('id');
            $.post(BASE_URL + 'CustomerPrescription/Customerprescriptionview', { recordID: id }, function (p) {
                $('#printablePrescription').html(buildPrintHtml(p));
                $('#viewPrescriptionModal').modal('show');
            }, 'json');
        });

        function buildPrintHtml(p) {
            var coats = [];
            if (parseInt(p.coat_a2)) coats.push('A-2');
            if (parseInt(p.coat_sp20)) coats.push('SP-20');
            if (parseInt(p.coat_hmc)) coats.push('H.M.C');
            if (parseInt(p.coat_pgx)) coats.push('P.G.X');
            if (parseInt(p.coat_pbx)) coats.push('P.B.X');
            if (parseInt(p.coat_bluecut)) coats.push('Blue Cut');

            var html = '';
            html += '<h5>' + p.customername + ' <small class="text-muted">(' + p.customercode + ')</small></h5>';
            html += '<p class="mb-1">Contact: ' + (p.contact || '-') + '</p>';
            html += '<p class="mb-3">Date: ' + p.prescriptiondate + '</p>';

            html += '<table class="table table-bordered table-sm text-center">';
            html += '<thead><tr><th></th><th colspan="3">Right Eye</th><th colspan="3">Left Eye</th></tr>';
            html += '<tr><th></th><th>Sph</th><th>Cyl</th><th>Axis</th><th>Sph</th><th>Cyl</th><th>Axis</th></tr></thead><tbody>';
            html += '<tr><th>Distance</th><td>' + dash(p.distance_r_sph) + '</td><td>' + dash(p.distance_r_cyl) + '</td><td>' + dash(p.distance_r_axis) + '</td><td>' + dash(p.distance_l_sph) + '</td><td>' + dash(p.distance_l_cyl) + '</td><td>' + dash(p.distance_l_axis) + '</td></tr>';
            html += '<tr><th>Read ADD</th><td>' + dash(p.readadd_r_sph) + '</td><td>' + dash(p.readadd_r_cyl) + '</td><td>' + dash(p.readadd_r_axis) + '</td><td>' + dash(p.readadd_l_sph) + '</td><td>' + dash(p.readadd_l_cyl) + '</td><td>' + dash(p.readadd_l_axis) + '</td></tr>';
            html += '</tbody></table>';

            html += '<p><strong>Lens:</strong> ' + dash(p.lens_vision) + ' / ' + dash(p.lens_material) + '</p>';
            html += '<p><strong>Frame Model:</strong> ' + dash(p.frame_model) + ' &nbsp; <strong>Rim Bridge Size:</strong> ' + dash(p.rim_bridge_size) + '</p>';
            html += '<p><strong>Colour:</strong> ' + dash(p.colour) + ' &nbsp; <strong>Temple Length:</strong> ' + dash(p.temple_length) + ' &nbsp; <strong>Wt.:</strong> ' + dash(p.weight) + '</p>';
            html += '<p><strong>Coating:</strong> ' + (coats.length ? coats.join(', ') : '-') + '</p>';
            html += '<p><strong>Remarks:</strong> ' + dash(p.remarks) + '</p>';

            return html;
        }

        function dash(v) {
            return (v === null || v === undefined || v === '') ? '-' : v;
        }
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
</script>
<?php include "include/footer.php"; ?>