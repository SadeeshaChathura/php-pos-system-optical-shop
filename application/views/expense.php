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
                            <div class="page-header-icon"><i class="fas fa-money-bill-wave"></i></div>
                            <span>Expenses</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="row erp-stack-row">

                    <div class="erp-panel-form w-100">
                        <div class="card">
                            <div class="card-header">Expense Details</div>
                            <div class="card-body">
                                <form action="<?php echo base_url() ?>Expense/Expenseinsertupdate" method="post" autocomplete="off">

                                    <div class="erp-form-grid">
                                        <div class="form-group">
                                            <label class="small font-weight-bold">Expense No</label>
                                            <input type="text" class="form-control form-control-sm" name="expenseno" id="expenseno" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label class="small font-weight-bold">Expense Date*</label>
                                            <input type="date" class="form-control form-control-sm" name="expensedate" id="expensedate" value="<?php echo date('Y-m-d'); ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="small font-weight-bold">Expense Type*</label>
                                            <select class="form-control form-control-sm" name="expensetype" id="expensetype" required>
                                                <option value="">Select</option>
                                                <?php foreach($expensetype->result() as $row){ ?>
                                                <option value="<?php echo $row->idtbl_expense_type ?>"><?php echo $row->expensetypename ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="small font-weight-bold">Amount*</label>
                                            <input type="number" step="0.01" class="form-control form-control-sm" name="amount" id="amount" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="small font-weight-bold">Payment Method*</label>
                                            <select class="form-control form-control-sm" name="paymentmethod" id="paymentmethod" required>
                                                <option value="">Select</option>
                                                <option value="1">Cash</option>
                                                <option value="2">Card / Bank Transfer</option>
                                                <option value="3">Cheque</option>
                                            </select>
                                        </div>
                                        <div class="form-group chequefield d-none">
                                            <label class="small font-weight-bold">Bank</label>
                                            <input type="text" class="form-control form-control-sm" name="bank" id="bank">
                                        </div>
                                        <div class="form-group chequefield d-none">
                                            <label class="small font-weight-bold">Bank Branch</label>
                                            <input type="text" class="form-control form-control-sm" name="branch" id="branch">
                                        </div>
                                        <div class="form-group chequefield d-none">
                                            <label class="small font-weight-bold">Cheque No</label>
                                            <input type="text" class="form-control form-control-sm" name="chequeno" id="chequeno">
                                        </div>
                                        <div class="form-group chequefield d-none">
                                            <label class="small font-weight-bold">Cheque Date</label>
                                            <input type="date" class="form-control form-control-sm" name="chequedate" id="chequedate">
                                        </div>

                                        <div class="form-group full-width">
                                            <label class="small font-weight-bold">Remark</label>
                                            <textarea class="form-control form-control-sm" name="remark" id="remark" rows="2"></textarea>
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

                    <div class="erp-panel-table w-100">
                        <div class="card">
                            <div class="card-header">Expense List</div>
                            <div class="card-body p-0 p-2">
                                <div class="scrollbar pb-3" id="style-2">
                                    <table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Expense No</th>
                                                <th>Date</th>
                                                <th>Expense Type</th>
                                                <th>Amount</th>
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

        $('#paymentmethod').on('change', function(){
            if($(this).val()=='3'){
                $('.chequefield').removeClass('d-none');
            }else{
                $('.chequefield').addClass('d-none');
                $('#bank, #branch, #chequeno, #chequedate').val('');
            }
        });

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
                { extend: 'csv', className: 'btn btn-ocean-blue btn-sm', title: 'Expense Information', text: '<i class="fas fa-file-csv mr-2"></i> CSV' },
                { extend: 'pdf', className: 'btn btn-ocean-blue btn-sm', title: 'Expense Information', text: '<i class="fas fa-file-pdf mr-2"></i> PDF' },
                { 
                    extend: 'print', 
                    title: 'Expense Information',
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
                url: "<?php echo base_url() ?>scripts/expenselist.php",
                type: "POST",
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                { "data": "idtbl_expense" },
                { "data": "expenseno" },
                { "data": "expensedate" },
                { "data": "expensetypename" },
                { "data": "amount" },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var button='';
                        button+='<button class="btn btn-primary btn-sm btnEdit mr-1 ';if(editcheck!=1){button+='d-none';}button+='" id="'+full['idtbl_expense']+'" data-toggle="tooltip" title="Edit"><i class="fas fa-edit"></i></button>';
                        if(full['status']==1){
                            button+='<a href="<?php echo base_url() ?>Expense/Expensestatus/'+full['idtbl_expense']+'/2" onclick="return deactive_confirm()" target="_self" class="btn btn-success btn-sm mr-1 ';if(statuscheck!=1){button+='d-none';}button+='" data-toggle="tooltip" title="Deactivate"><i class="fas fa-check-square"></i></a>';
                        }else{
                            button+='<a href="<?php echo base_url() ?>Expense/Expensestatus/'+full['idtbl_expense']+'/1" onclick="return active_confirm()" target="_self" class="btn btn-warning btn-sm mr-1 ';if(statuscheck!=1){button+='d-none';}button+='" data-toggle="tooltip" title="Activate"><i class="fas fa-times-circle"></i></a>';
                        }
                        button+='<a href="<?php echo base_url() ?>Expense/Expensestatus/'+full['idtbl_expense']+'/3" onclick="return delete_confirm()" target="_self" class="btn btn-danger btn-sm ';if(deletecheck!=1){button+='d-none';}button+='" data-toggle="tooltip" title="Delete"><i class="fas fa-trash-alt"></i></a>';
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
                    data: { recordID: id },
                    url: '<?php echo base_url() ?>Expense/Expenseedit',
                    success: function(result) {
                        var obj = JSON.parse(result);
                        $('#recordID').val(obj.id);
                        $('#expenseno').val(obj.expenseno);
                        $('#expensedate').val(obj.expensedate);
                        $('#expensetype').val(obj.expensetype);
                        $('#amount').val(obj.amount);
                        $('#paymentmethod').val(obj.paymentmethod).trigger('change');
                        $('#bank').val(obj.bank);
                        $('#branch').val(obj.branch);
                        $('#chequeno').val(obj.chequeno);
                        $('#chequedate').val(obj.chequedate);
                        $('#remark').val(obj.remark);

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
        $('#expensedate').val('<?php echo date('Y-m-d'); ?>');
        $('#expensetype').val('');
        $('#amount').val('');
        $('#paymentmethod').val('').trigger('change');
        $('#remark').val('');
        $('#submitBtn').html('<i class="far fa-save"></i>&nbsp;Add');
        loadNextCode();
    }

    function loadNextCode() {
        $.ajax({
            type: "POST",
            url: '<?php echo base_url() ?>Expense/Expensegeneratecode',
            success: function(result) {
                var obj = JSON.parse(result);
                $('#expenseno').val(obj.code);
            }
        });
    }

    function deactive_confirm() { return confirm("Are you sure you want to deactive this?"); }
    function active_confirm() { return confirm("Are you sure you want to active this?"); }
    function delete_confirm() { return confirm("Are you sure you want to remove this?"); }
</script>
<?php include "include/footer.php"; ?>