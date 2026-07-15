<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Handles POS (80mm) receipt rendering for Cash and Card sales.
 *
 * IMPORTANT: Card payments re-use existing tbl_invoice_payment_detail columns:
 *   - `bank`      holds the card type label (Visa / MasterCard / etc.) when method = 'Card'
 *   - `chequeno`  holds the last 4 digits of the card when method = 'Card'
 * This model checks `method` before printing those fields, so a card sale never
 * shows "Bank:" / "Cheque No:" labels — it shows "Card Type:" / "Card (last 4):" instead.
 */
class Directsalereceiptinfo extends CI_Model {

    public function Getposprintbill($x){

        $recordID = $x;
        $cashier  = $_SESSION['name'];

        $sqlinvoiceinfo = "SELECT `tbl_invoice`.`idtbl_invoice`, `tbl_invoice`.`invdate`, `tbl_invoice`.`grosstotal`,
                                   `tbl_invoice`.`discount`, `tbl_invoice`.`nettotal`, `tbl_invoice`.`paycomplete`,
                                   `tbl_invoice`.`invtype`,
                                   `tbl_customer`.`name` AS customername, `tbl_customer`.`contact` AS customercontact
                            FROM `tbl_invoice`
                            LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer` = `tbl_invoice`.`tbl_customer_idtbl_customer`
                            WHERE `tbl_invoice`.`status`=1 AND `tbl_invoice`.`idtbl_invoice`=?";
        $invoiceinforespond = $this->db->query($sqlinvoiceinfo, array($recordID));

        if ($invoiceinforespond->num_rows() == 0) {
            echo 'Invoice not found.';
            return;
        }

        $invdate     = $invoiceinforespond->row(0)->invdate;
        $invid       = $invoiceinforespond->row(0)->idtbl_invoice;
        $grosstotal  = $invoiceinforespond->row(0)->grosstotal;
        $discount    = $invoiceinforespond->row(0)->discount;
        $nettotal    = $invoiceinforespond->row(0)->nettotal;
        $invtype     = $invoiceinforespond->row(0)->invtype;     // 1 Cash, 2 Card, 3 Credit
        $custname    = $invoiceinforespond->row(0)->customername;

        $sqlproduct = "SELECT `tbl_material_info`.`materialinfocode`, `tbl_material_info`.`materialname`,
                               `tbl_invoice_detail`.`qty`, `tbl_invoice_detail`.`saleprice`
                        FROM `tbl_invoice_detail`
                        LEFT JOIN `tbl_material_info` ON `tbl_material_info`.`idtbl_material_info` = `tbl_invoice_detail`.`tbl_material_info_idtbl_material_info`
                        WHERE `tbl_invoice_detail`.`tbl_invoice_idtbl_invoice`=? AND `tbl_invoice_detail`.`status`=1";
        $productrespond = $this->db->query($sqlproduct, array($recordID));

        $sqlproductcount = "SELECT COUNT(`tbl_material_info`.`materialinfocode`) AS itemcount,
                                    SUM(`tbl_invoice_detail`.`qty`) AS sumqty
                             FROM `tbl_invoice_detail`
                             LEFT JOIN `tbl_material_info` ON `tbl_material_info`.`idtbl_material_info` = `tbl_invoice_detail`.`tbl_material_info_idtbl_material_info`
                             WHERE `tbl_invoice_detail`.`tbl_invoice_idtbl_invoice`=? AND `tbl_invoice_detail`.`status`=1";
        $productcountrespond = $this->db->query($sqlproductcount, array($recordID));

        $itemcount = $productcountrespond->row(0)->itemcount;
        $sumqty    = $productcountrespond->row(0)->sumqty;

        // Pull the payment detail row(s) tied to this invoice, including method/bank/chequeno
        // so we can correctly label Cash vs Card on the receipt.
        $sqlpayment = "SELECT `tbl_invoice_payment_detail`.`method`, `tbl_invoice_payment_detail`.`amount`,
                               `tbl_invoice_payment_detail`.`bank`, `tbl_invoice_payment_detail`.`chequeno`,
                               `tbl_invoice_payment`.`nettotal` AS paynettotal, `tbl_invoice_payment`.`balance`
                        FROM `tbl_invoice_payment_detail`
                        LEFT JOIN `tbl_invoice_payment` ON `tbl_invoice_payment`.`idtbl_invoice_payment` = `tbl_invoice_payment_detail`.`tbl_invoice_payment_idtbl_invoice_payment`
                        LEFT JOIN `tbl_invoice_payment_has_tbl_invoice` ON `tbl_invoice_payment_has_tbl_invoice`.`tbl_invoice_payment_idtbl_invoice_payment` = `tbl_invoice_payment`.`idtbl_invoice_payment`
                        WHERE `tbl_invoice_payment_has_tbl_invoice`.`tbl_invoice_idtbl_invoice`=?";
        $paymentrespond = $this->db->query($sqlpayment, array($recordID));

        $totalpay = 0;
        $balance  = 0;
        $paymentLines = array();

        if ($paymentrespond->num_rows() > 0) {
            $totalpay = $paymentrespond->row(0)->paynettotal ? $paymentrespond->row(0)->paynettotal : 0;
            $balance  = $paymentrespond->row(0)->balance ? $paymentrespond->row(0)->balance : 0;
            $paymentLines = $paymentrespond->result();
        }

        $tblinvoice = '';
        foreach ($productrespond->result() as $rowlist) {
            $tblinvoice .= '
                <tr>
                    <td style="text-align: left; font-size:14px;" colspan="4">' . $rowlist->materialname . '</td>
                </tr>
                <tr>
                    <td style="text-align: left; font-size:14px;">&nbsp;</td>
                    <td style="text-align: center; font-size:14px;">' . number_format($rowlist->saleprice, 2) . '</td>
                    <td style="text-align: center; font-size:14px;">' . $rowlist->qty . '</td>
                    <td style="text-align: right; font-size:14px;">' . number_format(($rowlist->qty * $rowlist->saleprice), 2) . '</td>
                </tr>
            ';
        }

        // Build the payment-method lines for the receipt footer.
        // Card sales show Card Type + last-4 instead of Bank/Cheque labels.
        $paymentHtml = '';
        foreach ($paymentLines as $payLine) {
            $methodLabel = $payLine->method;

            if (strtolower($payLine->method) === 'card' || $invtype == 2) {
                $methodLabel = 'Card (' . ($payLine->bank ? $payLine->bank : 'Card') . ')';
                $refLabel    = $payLine->chequeno ? '**** ' . $payLine->chequeno : '';
            } else {
                $refLabel = '';
            }

            $paymentHtml .= '
                <tr>
                    <td style="text-align: left; font-size:13px;">' . $methodLabel . ($refLabel ? ' ' . $refLabel : '') . '</td>
                    <td style="text-align: right; font-size:13px;">' . number_format($payLine->amount, 2) . '</td>
                </tr>
            ';
        }

        $invTypeLabel = ($invtype == 1) ? 'Cash Sale' : (($invtype == 2) ? 'Card Sale' : 'Credit Sale');

        $html = '
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css2?family=Fira+Mono&display=swap" rel="stylesheet">
    <title>Invoice</title>
    <style media="print">
        * { font-family: "Cutive Mono", monospace; font-weight: 600; }
        table,tr,th,td { font-family: "Cutive Mono", monospace; font-weight: 600; }
        img { width:200px; height:100px; }
    </style>
    <style>
        * { font-family: "Cutive Mono", monospace; font-weight: 600; }
        table,tr,th,td { font-family: "Cutive Mono", monospace; font-weight: 600; }
        img { width:100px; height:100px; }
    </style>
</head>
<body>
    <div id="DivIdToPrint">
        <img src="' . base_url() . 'images/test_company.jpg" alt="" style="width:100%; height:120px;">
        <table style="width:100%;">
            <tr>
                <td style="text-align: left; font-size:14px;">Date</td>
                <td style="text-align: right; font-size:14px;">' . $invdate . '</td>
            </tr>
            <tr>
                <td style="text-align: left; font-size:14px;">Invoice No.</td>
                <td style="text-align: right; font-size:14px;">INV/OT-000' . $invid . '</td>
            </tr>
            <tr>
                <td style="text-align: left; font-size:14px;">Sale Type</td>
                <td style="text-align: right; font-size:14px;">' . $invTypeLabel . '</td>
            </tr>
            <tr>
                <td style="text-align: left; font-size:14px;">Customer</td>
                <td style="text-align: right; font-size:14px;">' . ($custname ? $custname : 'Cash Customer') . '</td>
            </tr>
            <tr>
                <td style="text-align: left; font-size:14px;border-bottom:1px dotted black;">Cashier</td>
                <td style="text-align: right; font-size:14px;border-bottom:1px dotted black;">' . $cashier . '</td>
            </tr>
            <tr>
                <td style="text-align: center;" colspan="2">
                    <table style="width:100%;">
                        <tr style="text-align:right; font-weight:bold; font-size:5px;">
                            <td style="text-align: left; font-size:14px;border-bottom:1px dotted black;">Name</td>
                            <td style="text-align: center; font-size:14px;border-bottom:1px dotted black;">Price</td>
                            <td style="text-align: center; font-size:14px;border-bottom:1px dotted black;">Qty</td>
                            <td style="text-align: right; font-size:14px;border-bottom:1px dotted black;">Total</td>
                        </tr>
                        <tbody>
                            ' . $tblinvoice . '
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="text-align: left; font-size:14px;font-weight: bold;border-top:1px dotted black;">Total</td>
                <td style="text-align: right; font-size:14px;font-weight: bold;border-top:1px dotted black;">' . number_format($grosstotal, 2) . '</td>
            </tr>
            <tr>
                <td style="text-align: left; font-size:14px;font-weight: bold;">Total Discount</td>
                <td style="text-align: right; font-size:14px;font-weight: bold;">' . number_format($discount, 2) . '</td>
            </tr>
            <tr>
                <td style="text-align: left; font-size:14px;font-weight: bold;">Net Total</td>
                <td style="text-align: right; font-size:14px;font-weight: bold;">' . number_format($nettotal, 2) . '</td>
            </tr>
            ' . $paymentHtml . '
            <tr>
                <td style="text-align: left; font-size:14px;font-weight: bold;">Paid Amount</td>
                <td style="text-align: right; font-size:14px;font-weight: bold;">' . number_format($totalpay, 2) . '</td>
            </tr>
            <tr>
                <td style="text-align: left; font-size:14px;font-weight: bold;">Balance' . ($invtype == 3 ? ' Due' : ' / Change') . '</td>
                <td style="text-align: right; font-size:14px;font-weight: bold;">' . number_format($balance, 2) . '</td>
            </tr>
            <tr>
                <td style="text-align: left; font-size:14px;font-weight: bold;border-bottom:1px dotted black;">No. of Items</td>
                <td style="text-align: right; font-size:14px;font-weight: bold;border-bottom:1px dotted black;">' . $itemcount . '</td>
            </tr>
            <tr>
                <td style="text-align: center; font-size:10px;" colspan="2">
                    <span style="text-align: center; font-size:16px;font-weight: bold;">Thank You. Come again!</span><br>
                    Test Company(PVT)Ltd <br> 53, 3rd LANE, City, 011-2635185
                </td>
            </tr>
            <tr>
                <td style="text-align: center; font-size:5px;" colspan="2">
                    <span style="text-align: center; font-size:8px;font-weight: bold;">Copyright &copy; CalciteX IT Solutions</span>
                </td>
            </tr>
        </table>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script>
        window.print();
        setTimeout(() => { window.close(); }, 5000);
    </script>
</body>
</html>';

        echo $html;
    }
}