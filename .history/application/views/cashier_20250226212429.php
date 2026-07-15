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
			<div class="container-fluid p-0 p-2">
				<div class="card" style="margin-top: 2rem">
					<div class="card-body">

					</div>
				</div>
				<div class="card mt-2">
					<div class="card-body">

					</div>
				</div>
			</div>
		</main>
		<?php include "include/footerbar.php"; ?>
	</div>
</div>

<?php include "include/footerscripts.php"; ?>

<script type="text/javascript">
$(document).ready(function () {
	var btnCash = $('#btncash');
	var btnCredit = $('#btncredit');

	$("#customer").select2({
		width: '100%',
		ajax: {
			url: "<?php echo base_url() ?>Directsale/Getcustomerlist",
			type: "post",
			dataType: 'json',
			delay: 250,
			data: function (params) {
				return {
					searchTerm: params.term // search term
				};
			},
			processResults: function (response) {
				return {
					results: response
				};
			},
			cache: true
		}
	});

	btnCash.focus();

	$(document).keydown(function (e) {
		var arrowKey = e.which;

		if (arrowKey === 38) {
			e.preventDefault();
			btnCredit.focus();
		}

		if (arrowKey === 40) {
			e.preventDefault();
			btnCash.focus();
		}
	});

	// Arrow Keys (38, 40, 37, 39) - Navigate between elements
	$(document).keydown(function (e) {
		var key = e.which;
		var current = $('.categorydiv:focus');
		var next, prev;
		if (key == 40) { // Down arrow key
			next = current.next('.categorydiv');
			if (next.length) {
				next.focus();
			}
			return false;
		} else if (key == 38) { // Up arrow key
			prev = current.prev('.categorydiv');
			if (prev.length) {
				prev.focus();
			}
			return false;
		} else if (key == 39) { // Right arrow key
			next = current.next('.categorydiv');
			if (next.length) {
				next.focus();
			} else {
				$('.categorydiv:first').focus();
			}
			return false;
		} else if (key == 37) { // Left arrow key
			prev = current.prev('.categorydiv');
			if (prev.length) {
				prev.focus();
			} else {
				$('.categorydiv:last').focus();
			}
			return false;
		}
	});

	// Escape Key (27) - Close modals
	$(document).keydown(function (e) {
		if (e.keyCode === 27) { // Escape key
			$('.modal').modal('hide');
		}
	});

	// Ctrl + S (83) - Save or submit the form
	$(document).keydown(function (e) {
		if (e.ctrlKey && e.keyCode === 83) { // Ctrl + S
			e.preventDefault();
			$('#paymentbtn').click();
		}
	});

	// Ctrl + P (80) - Trigger print function
	$(document).keydown(function (e) {
		if (e.ctrlKey && e.keyCode === 80) { // Ctrl + P
			e.preventDefault();
			print();
		}
	});
});

$(document).ready(function () {
	var currentRow = null;


	// Show modal on Enter press
	$("#tableproductpricelist").on('keydown', 'tbody tr', function (e) {
		if (e.keyCode === 13) {
			e.preventDefault();
			currentRow = $(this);
			$('#modalqty').modal('show');
			$('#modalqty').on('shown.bs.modal', function () {
				$('#qtycount').focus();
				var productID = currentRow.children("td:eq(0)").text();
				var product = currentRow.children("td:eq(1)").text();
				var productcode = currentRow.children("td:eq(2)").text();
				var batchno = currentRow.children("td:eq(3)").text();
				var barcode = currentRow.children("td:eq(4)").text();
				var sale = currentRow.children("td:eq(5)").text();

				$("#discountpresentage").attr('max', maxdiscount);

				$('#hideproductid').val(productID);
				$('#hideproduct').val(product);
				$('#hideproductcode').val(productcode);
				$('#hideproductsale').val(sale);
				$('#salepriceedit').val(sale);

				$('#selectproduct').html(product);
			});
		}
	});


	// Do something with the modal data
	$("#modalqty").on('hide.bs.modal', function () {
		$('#qtycount').focus();
		$('#btnaddtolist').prop('disabled', false);
		$('#qtycount').val('');
	});
});
$(document).ready(function () {

	$('.locationdiv').click(function () {
		$('#modalretailwholesale').modal('hide');
	});

	var dataTable = $('#alreadyCustomerTable').DataTable({
		"destroy": true,
		"processing": true,
		"serverSide": true,
		"bFilter": false,
		ajax: {
			url: "scripts/alreadycustomerlist.php",
			type: "POST", // you can use GET
			data: function (d) {
				d.searchtype = $('#searchtype').val(),
					d.searchbox = $('#externalsearch').val()
			}
		},
		"order": [
			[0, "desc"]
		],
		"columns": [{
				"data": "idtbl_customer"
			},
			{
				"data": "name"
			},
			{
				"data": "customercode"
			},
			{
				"data": "contact"
			},
			{
				"data": "nicno"
			},
		]
	});
	// stock quntity check with user enterd quntity      
	var count
	$(document).on("keyup", "#qtycount", function (event) {
		event.preventDefault();
		var productid = $('#hideproductid').val();
		var enterqty = $('#qtycount').val();
		var location = $('#locationID').val();

		// $('#btnaddtolist').prop('disabled', false);

		$.ajax({
			type: "POST",
			data: {
				product: productid,
				qty: enterqty,
				location: location

			},
			url: "<?php echo base_url() ?>Directsale/Getproductavalaibleqty",
			success: function (result) { //alert(result);
				var obj = JSON.parse(result);
				count = obj.checkqty;

				if (count == '1') {
					/*if the quantity is greater than the stock*/
					alert('Warning !! The Quantity you Entered is not Available in stock !!');
					$('#btnaddtolist').prop('disabled', true);
				} else {
					$('#btnaddtolist').prop('disabled', false);
				}

			}
		});
	});
	$('#customersearchsubmit').click(function () {
		if (!$("#alreadycusform")[0].checkValidity()) {
			// If the form is invalid, submit it. The form won't actually submit;
			// this will just cause the browser to display the native HTML5 error messages.
			$("#hidecustomersearchsubmit").click();
		} else {
			dataTable.draw();
		}
	});
	$('.categorydiv').click(function () {
		var categoryID = $(this).attr('id');
		$('#hiddenmaterialID').val(categoryID);
		var locationType = $('#locationID').val();

		$("#collapseFour").collapse('show');

		$.ajax({
			method: "POST",
			data: {
				categoryID: categoryID,
				locationType: locationType
			},
			url: '<?php echo base_url() ?>Directsale/Getproductlist',
			success: function (result) { //alert(result)
				$('#tableproductpricelist > tbody').empty().append(result);
				productlistoption();
			}
		});
	});
	$('.locationdiv').click(function () {
		var locationType = $(this).attr('id');
		$('#barcode').focus();
		$('#locationID').val(locationType);
		// $('#hiddenlocationID').val(locationType);
	});
	$("#barcode").keypress(function (event) {
		if (event.keyCode === 13) { // check if the pressed key is the Enter key
			event.preventDefault(); // prevent form submission
			var barcode = $(this).val();
			if (barcode !== '') { // check if barcode field is not empty
				$("#collapseFour").collapse('show');
				$.ajax({
					method: "POST",
					data: {
						barcode: barcode
					},
					url: '<?php echo base_url() ?>Directsale/Getproductlistaccobarcode',
					success: function (result) {
						$('#modalqty').modal('show');
						$('#modalqty').on('shown.bs.modal', function () {
							var obj = JSON.parse(result);
							$('#qtycount').focus();

							// $("#discountpresentage").attr('max', maxdiscount);

							$('#hideproductid').val(obj.id);
							$('#hideproduct').val(obj.barcode);
							$('#hideproductcode').val(obj.productcode);
							$('#hideproductsale').val(obj.price);
							$('#salepriceedit').val(obj.price);

							$('#selectproduct').html(obj.productcode);
						});
					}
				});
			}
		}
	});

	$('#btnbackthree').click(function () {
		$("#collapseOne").collapse('show');
	});

	$('#btnaddtolist').click(function (e) {
		if (e.which == 13) {
			e.preventDefault(); // prevent form submission
			$('#paymentbtn').trigger('click'); // trigger click event on #paymentbtn
		}
	});
	$('#salepriceedit').keypress(function (e) {
		var key = e.which;
		if (key == 13) {
			$('#qtycount').focus();
			return false;
		}
	});
	$('#qtycount').keypress(function (e) {
		var key = e.which;
		if (key == 13) {
			$('#discountpresentage').focus();
			return false;
		}
	});
	$('#barcode').keydown(function (e) {
		var key = e.which;
		var current = $('.categorydiv:focus');
		var next, prev;
		if (key == 40) { // Down arrow key
			next = current.next('.categorydiv');
			if (next.length) {
				next.focus();
			}
			return false;
		} else if (key == 38) { // Up arrow key
			prev = current.prev('.categorydiv');
			if (prev.length) {
				prev.focus();
			}
			return false;
		} else if (key == 39) { // Right arrow key
			next = current.next('.categorydiv');
			if (next.length) {
				next.focus();
			} else {
				$('.categorydiv:first').focus();
			}
			return false;
		} else if (key == 37) { // Left arrow key
			prev = current.prev('.categorydiv');
			if (prev.length) {
				prev.focus();
			} else {
				$('.categorydiv:last').focus();
			}
			return false;
		}
	});
	$('.categorydiv').keypress(function (e) {
		var categoryID = $(this).attr('id');
		var saletype = $('#saletype').val();
		$('#hiddenmaterialID').val(categoryID);

		$("#collapseFour").collapse('show');
		var key = e.which;
		if (key == 13) {
			$.ajax({
				method: "POST",
				data: {
					categoryID: categoryID,
					saletype: saletype
				},
				url: '<?php echo base_url() ?>Directsale/Getproductlist',
				success: function (result) { //alert(result)
					$('#tableproductpricelist > tbody').empty().append(result);
					productlistoption();
				}
			});

		}
	});
	$('#tableproductpricelist').on('click', 'tr', function (e) {
		var key = e.which;
		if (key == 13) {
			$("#modalqty").modal('show');
			return false;
		}
	});
	$('#discountpresentage').keypress(function (e) {
		var key = e.which;
		if (key == 13) {
			$("#btnaddtolist").click();
			return false;
		}
	});
	$("#btnaddtolist").click(function () {
		if (!$("#formqtyadd")[0].checkValidity()) {
			// If the form is invalid, submit it. The form won't actually submit;
			// this will just cause the browser to display the native HTML5 error messages.
			$("#btnhideqtysubmit").click();
		} else {
			var productID = $('#hideproductid').val();
			var product = $('#hideproduct').val();
			var productcode = $('#hideproductcode').val();
			var unit = parseFloat($('#hideproductunit').val());
			var sale = parseFloat($('#hideproductsale').val());
			var qty = parseFloat($('#qtycount').val());
			var salepriceedit = parseFloat($('#salepriceedit').val());
			var discountpresentage = parseFloat($('#discountpresentage').val());

			if (salepriceedit != sale) {
				$('#priceeditstatus').val('1');

				var finalsaleamount = salepriceedit;
				var total = salepriceedit * qty;
				var total = parseFloat(total);
				var discountamount = parseFloat((total * discountpresentage) / 100);
				var totalwithdis = parseFloat(total - discountamount);
				var showtotal = parseFloat(totalwithdis).toFixed(2);
				var classname = 'table-info';
				var editstatus = '1';
			} else {
				var finalsaleamount = sale;
				var total = sale * qty;
				var total = parseFloat(total);
				var discountamount = parseFloat((total * discountpresentage) / 100);
				var totalwithdis = parseFloat(total - discountamount);
				var showtotal = parseFloat(totalwithdis).toFixed(2);
				var classname = '';
				var editstatus = '0';
			}

			$('#carttable > tbody:last').append('<tr class="pointer ' + classname + '"><td>' + productcode + '</td><td class="text-center">' + qty + '</td><td class="text-right">' + parseFloat(finalsaleamount).toFixed(2) + '</td><td class="text-right">' + parseFloat(discountamount).toFixed(2) + '</td><td class="text-right">' + showtotal + '</td><td class="d-none">' + productID + '</td><td class="d-none">' + productcode + '</td><td class="d-none">' + finalsaleamount + '</td><td class="d-none">' + unit + '</td><td class="total d-none">' + total + '</td><td class="d-none">' + discountpresentage + '</td><td class="distotal d-none">' + discountamount + '</td><td class="nettotal d-none">' + totalwithdis + '</td><td class="d-none">' + editstatus + '</td></tr>');

			var sum = 0;
			$(".total").each(function () {
				sum += parseFloat($(this).text());
			});

			var showsum = parseFloat(sum).toFixed(2);

			var dissum = 0;
			$(".distotal").each(function () {
				dissum += parseFloat($(this).text());
			});

			var showdissum = parseFloat(dissum).toFixed(2);

			var netsum = 0;
			$(".nettotal").each(function () {
				netsum += parseFloat($(this).text());
			});

			var shownetsum = parseFloat(netsum).toFixed(2);

			$('#labeltotal').html('Gross Amount: ' + showsum);
			$('#labeldistotal').html('Discount: ' + showdissum);
			$('#labelnettotal').html(shownetsum);
			$('#htmlbillamount').html('Rs. ' + shownetsum);
			$('#hiddenfulltotal').val(sum);
			$('#hiddenfulldistotal').val(dissum);
			$('#hiddenfullnettotal').val(netsum);
			$('#btnhideqtyreset').click();
			$('#modalqty').modal('hide');
			$('#barcode').val('');
			$('#selectproduct').val('');
			$('#salepriceedit').val('');
			$("#collapseOne").collapse('show');
		}
	});
	$('#carttable').on('click', 'tr', function () {
		var r = confirm("Are you sure, You want to remove this product ? ");
		if (r == true) {
			$(this).closest('tr').remove();

			var sum = 0;
			$(".total").each(function () {
				sum += parseFloat($(this).text());
			});

			var showsum = addCommas(parseFloat(sum).toFixed(2));

			$('#labeltotal').html('Rs. ' + showsum);
			$('#hiddenfulltotal').val(sum);
			$('#btnhideqtyreset').click();
			$('#modalqty').modal('hide');
			$("#collapseOne").collapse('show');
		}
	});
	$('#paymentbtn').click(function () {
		$('#modalcashcredit').modal('show');
	});
	$('#btncash').click(function () {
		$('#paymentModal').modal('show');
	});

	$('#receivedAmount').on('input', function () {
		var fulltotal = parseFloat($('#hiddenfullnettotal').val());
		var receivedAmount = parseFloat($(this).val());

		if (!isNaN(receivedAmount) && receivedAmount >= 0) {
			var balance = receivedAmount - fulltotal;
			$('#balanceAmount').text(balance.toFixed(2));
		} else {
			$('#balanceAmount').text('0.00');
		}
	});

	$('#confirmPayment').click(function () {
		var fulltotal = parseFloat($('#hiddenfullnettotal').val());
		var receivedAmount = parseFloat($('#receivedAmount').val());

		if (isNaN(receivedAmount) || receivedAmount <= 0) {
			alert('Please enter a valid payment amount.');
			return;
		}

		if (receivedAmount < fulltotal) {
			alert('Insufficient payment. Please enter an amount equal to or greater than ' + fulltotal.toFixed(2));
			return;
		}

		var balance = receivedAmount - fulltotal;
		$('#balanceAmount').text(balance.toFixed(2));
		$('#hidepaymenttotal').val(receivedAmount);

		$('#tablepayment > tbody:last').append(
			'<tr class="pointer">' +
			'<td class="d-none">1</td>' +
			'<td>Cash</td>' +
			'<td>&nbsp;</td>' +
			'<td>&nbsp;</td>' +
			'<td>&nbsp;</td>' +
			'<td class="d-none paytotal">' + receivedAmount + '</td>' +
			'<td class="text-right">' + receivedAmount.toFixed(2) + '</td>' +
			'</tr>'
		);

		$('#paymentModal').modal('hide'); // Close the modal
		createinvoice(); // Proceed with invoice creation
	});
	$('#btncard').click(function () {
		$('#cardPaymentModal').modal('show');
	});

	$('#confirmCardPayment').click(function () {
		var fulltotal = $('#hiddenfullnettotal').val();
		var last4Digits = $('#last4digits').val().trim();

		if (!/^\d{4}$/.test(last4Digits)) {
			alert("Please enter exactly 4 digits.");
			return;
		}

		$('#tablepayment > tbody:last').append(
			'<tr class="pointer">' +
			'<td class="d-none">2</td>' +
			'<td>Card</td>' +
			'<td>&nbsp;</td>' +
			'<td>&nbsp;</td>' +
			'<td>' + last4Digits + '</td>' +
			'<td class="d-none paytotal">' + fulltotal + '</td>' +
			'<td class="text-right">' + parseFloat(fulltotal).toFixed(2) + '</td>' +
			'</tr>'
		);

		$('#cardPaymentModal').modal('hide');

		updatePaymentTotal();
		createinvoice();
	});

	// Function to update payment total field
	function updatePaymentTotal() {
		var totalPayment = 0;
		$('.paytotal').each(function () {
			totalPayment += parseFloat($(this).text()) || 0;
		});

		$('#hidepaymenttotal').val(totalPayment.toFixed(2)); // Update hidden total field
	}

	$('#btncredit').click(function () {
		$('#modalcashcredit').modal('hide');
		$('#modalpayment').modal('show');
		$('#modalpayment').on('shown.bs.modal', function () {
			$('#amount').focus();
		})
	});

	// Payment start
	$('#billtype').change(function () {
		if (this.value == '3') {
			$('#alreadyCustomerModal').modal('show');
			$('#collapsecustomerinfo').collapse('show');
			$('#amount').prop('readonly', true);
			$('#paymentcomplete').prop('disabled', false);
		} else if (this.value == '2') {
			$('#alreadyCustomerModal').modal('show');
			$('#collapsecustomerinfo').collapse('show');
		} else {
			$('#cusname').val('');
			$('#cusnic').val('');
			$('#cusmobile').val('');
			$('#collapsecustomerinfo').collapse('hide');
			$('#amount').focus();
		}
	});
	$('#paymentmethod').change(function () {
		if (this.value == '1') {
			$('#bank').prop('readonly', true).prop('required', false);
			$('#branch').prop('readonly', true).prop('required', false);
			$('#chequeno').prop('readonly', true).prop('required', false);
			$('#chequedate').prop('readonly', true).prop('required', false);
			$('#amount').focus();
		} else if (this.value == '3') {
			$('#bank').prop('readonly', false).prop('required', true);
			$('#branch').prop('readonly', false).prop('required', true);
			$('#chequeno').prop('readonly', false).prop('required', true);
			$('#chequedate').prop('readonly', false).prop('required', true);
		}
	});
	$('#amount').keypress(function (e) {
		var key = e.which;
		if (key == 13) {
			var paymentmethod = $("#paymentmethod").val();
			if (paymentmethod < 3) {
				$("#btnpayaddlist").click();
				return false;
			}
		}
	});
	$('#btnpayaddlist').click(function () {
		if (!$("#paymentform")[0].checkValidity()) {
			// If the form is invalid, submit it. The form won't actually submit;
			// this will just cause the browser to display the native HTML5 error messages.
			$("#btnhidepayaddlist").click();
		} else {
			var amount = $('#amount').val();
			var bank = $('#bank').val();
			var branch = $('#branch').val();
			var chequeno = $('#chequeno').val();
			var chequedate = $('#chequedate').val();
			var paymentmethod = $("#paymentmethod").val();
			var billtype = $('#billtype').val();

			if (paymentmethod == 1) {
				var paymethod = 'Cash';
			} else if (paymentmethod == 2) {
				var paymethod = 'Credit Card';
			} else if (paymentmethod == 3) {
				var paymethod = 'Cheque';
			}


			$('#tablepayment > tbody:last').append('<tr class="pointer"><td class="d-none">' + paymentmethod + '</td><td>' + paymethod + '</td><td>' + bank + '</td><td>' + branch + '</td><td>' + chequeno + '</td><td>' + chequedate + '</td><td class="d-none paytotal">' + amount + '</td><td class="text-right">' + addCommas(parseFloat(amount).toFixed(2)) + '</td></tr>');

			var sum = 0;
			$(".paytotal").each(function () {
				sum += parseFloat($(this).text());
			});

			var netbilltotal = parseFloat($('#hiddenfullnettotal').val());
			var showsum = addCommas(parseFloat(sum).toFixed(2));

			var baltotal = netbilltotal - sum;
			baltotal = addCommas(baltotal.toFixed(2));

			if (billtype == 2) {
				$('#paymentcomplete').prop('disabled', false);
			} else if (sum >= netbilltotal) {
				$('#paymentcomplete').prop('disabled', false);
			} else {
				$('#paymentcomplete').prop('disabled', true);
			}

			$('#paynettotal').html('Rs. ' + showsum);
			$('#paybalance').html('Rs. ' + baltotal);
			$('#hidepaymenttotal').val(sum);
			if (billtype == 1) {
				$('#btnhidepayresetlist').click();
			} else if (billtype == 2) {
				$('#amount').val('');
				$('#bank').val('');
				$('#branch').val('');
				$('#chequeno').val('');
				$('#chequedate').val('');
			}
			$("input[type=radio][name='paymentmethod']").prop('checked', false).parent().removeClass('active');
			$('#bank').prop('readonly', true).prop('required', false);
			$('#branch').prop('readonly', true).prop('required', false);
			$('#chequeno').prop('readonly', true).prop('required', false);
			$('#chequedate').prop('readonly', true).prop('required', false);
		}
	});
	$('#paymentcomplete').click(function () {
		var checkapproval = $('#priceeditstatus').val();

		if (checkapproval == 1) {
			$('#modalapprovebill').modal('show');
		} else {
			createinvoice();
		}
	});

	// Payment end

	$('#alreadyCustomerTable tbody').on('click', 'tr', function () { //alert('IN');
		if ($(this).hasClass('table-primary')) {
			$(this).removeClass('table-primary');
		} else {
			dataTable.$('tr.table-primary').removeClass('table-primary');
			$(this).addClass('table-primary');

			var data = $('#alreadyCustomerTable').DataTable().row('.table-primary').data();
			// console.log(data);
			$('#hidecustomerID').val(data.idtbl_customer);
			$('#cusname').val(data.name);
			$('#cusnic').val(data.nicno);
			$('#cusmobile').val(data.contact);
			$('#alreadyCustomerModal').modal('hide');
		}
	});
	$('#btnAddToDB').click(function () {
		$('#alreadyCustomerModal').modal('hide');
		$('#cusname').focus();
	});
	document.getElementById('btnreceiptprint').addEventListener("click", print);
	document.getElementById('btnreceiptprintpos').addEventListener("click", printpos);
	$('#modalinvoicereceiptpos').on('hidden.bs.modal', function (e) {
		location.reload();
	});
	$('#modalinvoicereceipt').on('hidden.bs.modal', function (e) {
		location.reload();
	});
	$('#alreadyCustomerModal').on('hidden.bs.modal', function (e) {
		$('#cusname').focus();
	});

	$('#approveusername').keypress(function (e) {
		var key = e.which;
		if (key == 13) {
			$('#approvepassword').focus();
			return false;
		}
	});
	$('#approvepassword').keypress(function (e) {
		var key = e.which;
		if (key == 13) {
			$('#btnbillapprove').click();
			return false;
		}
	});
});

function productlistoption() {
	$("#tableproductpricelist").delegate("tr.pointer", "click", function () {
		var productID = $(this).children("td:eq(0)").text();
		var product = $(this).children("td:eq(1)").text();
		var productcode = $(this).children("td:eq(2)").text();
		var batchno = $(this).children("td:eq(3)").text();
		var sale = $(this).children("td:eq(5)").text();

		$('#hideproductid').val(productID);
		$('#hideproduct').val(product);
		$('#hideproductcode').val(productcode);
		$('#hideproductsale').val(sale);
		$('#salepriceedit').val(sale);

		$('#selectproduct').html(product);

		$('#modalqty').modal('show');
		$('#modalqty').on('shown.bs.modal', function () {
			$('#qtycount').focus();
		})
	});
}

function createinvoice() {
	var tbody = $('#carttable tbody');
	if (tbody.children().length > 0) {
		jsonObj = []
		$("#carttable tbody tr").each(function () {
			item = {}
			$(this).find('td').each(function (col_idx) {
				item["col_" + (col_idx + 1)] = $(this).text();
			});
			jsonObj.push(item);
		});
	}
	// console.log(jsonObj);

	var tbodysecond = $('#tablepayment tbody');
	jsonObjPay = []
	if (tbodysecond.children().length > 0) {
		$("#tablepayment tbody tr").each(function () {
			item = {}
			$(this).find('td').each(function (col_idx) {
				item["col_" + (col_idx + 1)] = $(this).text();
			});
			jsonObjPay.push(item);
		});
	}
	var total = $('#hiddenfulltotal').val();
	var distotal = $('#hiddenfulldistotal').val();
	var nettotal = $('#hiddenfullnettotal').val();
	var paytotal = $('#hidepaymenttotal').val();
	var billtype = $("#billtype").val();
	var cusname = $('#cusname').val();
	var cusnic = $('#cusnic').val();
	var cusmobile = $('#cusmobile').val();
	var cusID = $('#hidecustomerID').val();
	var saletype = $('#saletype').val();
	var priceeditstatus = $('#priceeditstatus').val();
	var billapproveuser = $('#hideapproveuser').val();
	var productid = $('#hideproductid').val();
	var locationID = $('#locationID').val();
	var customer = $('#customer').val();
	// console.log(jsonObjPay);

	if (tbodysecond.children().length > 0 && billtype == 1) {
		var paystatus = '1';
	} else if (tbodysecond.children().length > 0 && billtype == 2) {
		var paystatus = '1';
	} else if (tbodysecond.children().length == 0 && billtype == 2) {
		var paystatus = '1';
	} else if (tbodysecond.children().length == 0 && billtype == 3) {
		var paystatus = '1';
	} else {
		var paystatus = '0';
	}

	if (paystatus == 1) {
		$.ajax({
			type: "POST",
			data: {
				tableData: jsonObj,
				tableDataPay: jsonObjPay,
				total: total,
				distotal: distotal,
				nettotal: nettotal,
				paytotal: paytotal,
				billtype: billtype,
				cusname: cusname,
				cusnic: cusnic,
				cusmobile: cusmobile,
				cusID: cusID,
				saletype: saletype,
				priceeditstatus: priceeditstatus,
				productid: productid,
				billapproveuser: billapproveuser,
				locationID: locationID,
				customer: customer

			},
			url: '<?php echo base_url() ?>Directsale/Directsaleinsertupdate',
			success: function (result) { //alert(result);
				// console.log(result);
				var objfirst = JSON.parse(result);
				if (objfirst.actiontype == 1) {
					$('#modalpayment').modal('hide');
					action(objfirst.action);
					if (objfirst.billtype == 1) {
						posprintbill(objfirst.invoiceid);
					} else {
						creditprintbill(objfirst.invoiceid);
					}
				} else {
					action(objfirst.action);
				}
			}
		});
	}
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

function posprintbill(invoiceid) {
	window.open('<?php echo base_url() ?>Directsale/Getposprintbill/' + invoiceid, "_blank");
	setTimeout(window.location.reload(), 3000);


}

function creditprintbill(invoiceid) {
	window.open('<?php echo base_url() ?>Directsale/Getcreditprintbill/' + invoiceid, "_blank");
	setTimeout(window.location.reload(), 3000);
}

function print() {
	printJS({
		printable: 'viewreceiptprint',
		type: 'html',
		style: '@page { size: A5 portrait; margin:0.25cm; }',
		targetStyles: ['*']
	})
}

function printpos() {
	printJS({
		printable: 'viewreceiptprintpos',
		type: 'html',
		// style: '@page { size: A5 portrait; margin:0.25cm; }',
		targetStyles: ['*']
	})
}
</script>

<?php include "include/footer.php"; ?>
