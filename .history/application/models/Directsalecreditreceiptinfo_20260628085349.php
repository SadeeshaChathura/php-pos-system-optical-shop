<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Directsalecreditreceiptinfo extends CI_Model {

    public function Getcreditprintbill($x){

        $recordID = $x;
        $cashier  = $_SESSION['name'];

        // Credit sales always carry a real tbl_customer_idtbl_customer (never the Cash Customer row),
        // so this join is reliable for credit invoices.
        $sqlinvoiceinfo = "SELECT `tbl_invoice`.`idtbl_invoice`, `tbl_invoice`.`invdate`, `tbl_invoice`.`grosstotal`,
                                   `tbl_invoice`.`discount`, `tbl_invoice`.`nettotal`, `tbl_invoice`.`paycomplete`,
                                   `tbl_customer`.`name`, `tbl_customer`.`address`, `tbl_customer`.`contact`,
                                   `tbl_customer`.`customercode`
                            FROM `tbl_invoice`
                            LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer` = `tbl_invoice`.`tbl_customer_idtbl_customer`
                            WHERE `tbl_invoice`.`status`=1 AND `tbl_invoice`.`idtbl_invoice`=?";
        $invoiceinforespond = $this->db->query($sqlinvoiceinfo, array($recordID));

        if ($invoiceinforespond->num_rows() == 0) {
            echo 'Invoice not found.';
            return;
        }

        $invdate    = $invoiceinforespond->row(0)->invdate;
        $invid      = $invoiceinforespond->row(0)->idtbl_invoice;
        $name       = $invoiceinforespond->row(0)->name;
        $address    = $invoiceinforespond->row(0)->address;
        $contact    = $invoiceinforespond->row(0)->contact;
        $custcode   = $invoiceinforespond->row(0)->customercode;
        $grosstotal = $invoiceinforespond->row(0)->grosstotal;
        $discount   = $invoiceinforespond->row(0)->discount;
        $nettotal   = $invoiceinforespond->row(0)->nettotal;

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

        // Advance payment, if any was taken at time of credit sale.
        $sqlpayment = "SELECT SUM(`tbl_invoice_payment`.`nettotal`) AS sumpayment
                        FROM `tbl_invoice_payment`
                        LEFT JOIN `tbl_invoice_payment_has_tbl_invoice` ON `tbl_invoice_payment_has_tbl_invoice`.`tbl_invoice_payment_idtbl_invoice_payment` = `tbl_invoice_payment`.`idtbl_invoice_payment`
                        WHERE `tbl_invoice_payment_has_tbl_invoice`.`tbl_invoice_idtbl_invoice`=?";
        $paymentrespond = $this->db->query($sqlpayment, array($recordID));

        $totalpay = ($paymentrespond->num_rows() > 0 && $paymentrespond->row(0)->sumpayment)
            ? $paymentrespond->row(0)->sumpayment
            : 0;

        $balancedue = $nettotal - $totalpay;

        $tblinvoice = '';
        foreach ($productrespond->result() as $rowlist) {
            $tblinvoice .= '
                <tr>
                    <td style="font-size:9px;" class="text-left">' . $rowlist->materialname . ' (' . $rowlist->materialinfocode . ')</td>
                    <td style="font-size:9px;" class="text-right">' . $rowlist->qty . '</td>
                    <td style="font-size:9px;" class="text-right">' . number_format($rowlist->saleprice, 2) . '</td>
                    <td style="font-size:9px;" class="text-right">' . number_format(($rowlist->qty * $rowlist->saleprice), 2) . '</td>
                </tr>
            ';
        }

        $html = '
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css2?family=Fira+Mono&display=swap" rel="stylesheet">
    <title>Credit Invoice</title>
    <style media="print">
        * { font-family: "Cutive Mono", monospace; }
        table,tr,th,td { font-family: "Cutive Mono", monospace; }
        img { width:200px; height:100px; }
    </style>
    <style>
        * { font-family: "Cutive Mono", monospace; }
        table,tr,th,td { font-family: "Cutive Mono", monospace; }
        img { width:100px; height:100px; }
        #DivIdToPrint { border: 1px solid black; padding: 10px; }
    </style>
</head>
<body>
<div id="DivIdToPrint">
    <table style="width:100%;">
        <tr>
            <td style="text-align: center; font-size:16px;" colspan="2">
                <img src="' . base_url() . 'images/Ch.jpg" alt="">
                No-63, Jaya Mawatha, Ratmalana, 011-2635185 <br><br>
                <h3 style="margin-top:0;margin-bottom:0;">CREDIT INVOICE</h3><br>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; font-size:14px;">Date: ' . $invdate . '</td>
            <td style="text-align: right; font-size:14px;">Invoice No: INV-0000' . $invid . '</td>
        </tr>
        <tr>
            <td style="text-align: left; font-size:14px;">Cashier: ' . $cashier . '</td>
            <td style="text-align: right; font-size:14px;">Cust Code: ' . $custcode . '</td>
        </tr>
        <tr>
            <td style="text-align: left; font-size:14px;" colspan="2">
                Customer: ' . $name . ' &nbsp; | &nbsp; ' . $contact . '
                ' . ($address ? '<br>' . $address : '') . '
            </td>
        </tr>
        <tr>
            <td style="text-align: center;" colspan="2">
                <br>
                <table style="table-layout: fixed; width: 100%">
                    <tr style="font-weight:bold;">
                        <td style="text-align: left; font-size:14px;border-bottom:1px dotted black;">Item</td>
                        <td style="text-align: right; font-size:14px;border-bottom:1px dotted black;">Qty</td>
                        <td style="text-align: right; font-size:14px;border-bottom:1px dotted black;">Price</td>
                        <td style="text-align: right; font-size:14px;border-bottom:1px dotted black;">Total</td>
                    </tr>
                    <tbody>' . $tblinvoice . '</tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                <table style="width:100%;">
                    <tr>
                        <td style="text-align: left; font-size:16px;font-weight: bold;">Total</td>
                        <td style="text-align: left; font-size:16px;font-weight: bold;">:</td>
                        <td style="text-align: right; font-size:16px;font-weight: bold;">' . number_format($grosstotal, 2) . '</td>
                    </tr>
                    <tr>
                        <td style="text-align: left; font-size:16px;font-weight: bold;">Total Discount</td>
                        <td style="text-align: left; font-size:16px;font-weight: bold;">:</td>
                        <td style="text-align: right; font-size:16px;font-weight: bold;">' . number_format($discount, 2) . '</td>
                    </tr>
                    <tr>
                        <td style="text-align: left; font-size:16px;font-weight: bold;">Net Total</td>
                        <td style="text-align: left; font-size:16px;font-weight: bold;">:</td>
                        <td style="text-align: right; font-size:16px;font-weight: bold; border-bottom:1px double black; border-top:1px solid black;">' . number_format($nettotal, 2) . '</td>
                    </tr>
                    <tr>
                        <td style="text-align: left; font-size:16px;font-weight: bold;">Advance Paid</td>
                        <td style="text-align: left; font-size:16px;font-weight: bold;">:</td>
                        <td style="text-align: right; font-size:16px;font-weight: bold;">' . number_format($totalpay, 2) . '</td>
                    </tr>
                    <tr>
                        <td style="text-align: left; font-size:16px;font-weight: bold; padding-top: 10px;">Balance Due (Credit)</td>
                        <td style="text-align: left; font-size:16px;font-weight: bold; padding-top: 10px;">:</td>
                        <td style="text-align: right; font-size:16px;font-weight: bold; padding-top: 10px; border-top:1px solid black;">' . number_format($balancedue, 2) . '</td>
                    </tr>
                    <tr>
                        <td style="text-align: left; font-size:16px;font-weight: bold;">No. of Items</td>
                        <td style="text-align: left; font-size:16px;font-weight: bold;">:</td>
                        <td style="text-align: right; font-size:16px;font-weight: bold;">' . $itemcount . '</td>
                    </tr>
                    <tr>
                        <td style="text-align: left; font-size:16px;font-weight: bold;">No. of Quantity</td>
                        <td style="text-align: left; font-size:16px;font-weight: bold;">:</td>
                        <td style="text-align: right; font-size:16px;font-weight: bold;">' . $sumqty . '</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: center; font-size:10px; padding-top: 10px;" colspan="2">
                <span style="font-size:16px;">Important Notice: This is a credit sale. Please settle the balance due within the agreed credit period.</span>
            </td>
        </tr>
        <tr>
            <td style="text-align: center; font-size:10px; padding-top: 25px;" colspan="2">
                <span style="font-size:16px;">UNISTAR INTERNATIONAL PVT LTD<br>53, 3rd LANE, RATMALANA, 011-2635185</span><br>
                Copyright &copy; ERav Technology
            </td>
        </tr>
    </table>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</body>
</html>';

        $this->load->library('pdf');
        $this->pdf->loadHtml($html);
        $this->pdf->render();
        $this->pdf->stream("CREDIT-INVOICE-" . $invid . ".pdf", array("Attachment" => 0));
    }
}