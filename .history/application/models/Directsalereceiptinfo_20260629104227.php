<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Handles POS A4 invoice rendering for Cash and Card sales.
 *
 * IMPORTANT: Card payments re-use existing tbl_invoice_payment_detail columns:
 *   - `bank`      holds the card type label (Visa / MasterCard / etc.) when method = 'Card'
 *   - `chequeno`  holds the last 4 digits of the card when method = 'Card'
 * This model checks `method` before printing those fields, so a card sale never
 * shows "Bank:" / "Cheque No:" labels — it shows "Card Type:" / "Card (last 4):" instead.
 *
 * WARRANTY: tbl_invoice.warrantystatus (0/1) drives whether the Lifetime Warranty
 * box is shown on the invoice. It is set at time of sale via a checkbox in the
 * payment-complete modal and is never editable afterward from this screen.
 */
class Directsalereceiptinfo extends CI_Model {

    public function Getposprintbill($x){

        $recordID = $x;
        $cashier  = $_SESSION['name'];

        $sqlinvoiceinfo = "SELECT `tbl_invoice`.`idtbl_invoice`, `tbl_invoice`.`invdate`, `tbl_invoice`.`grosstotal`,
                                   `tbl_invoice`.`discount`, `tbl_invoice`.`nettotal`, `tbl_invoice`.`paycomplete`,
                                   `tbl_invoice`.`invtype`, `tbl_invoice`.`warrantystatus`,
                                   `tbl_customer`.`name` AS customername, `tbl_customer`.`contact` AS customercontact,
                                   `tbl_customer`.`customercode` AS customercode
                            FROM `tbl_invoice`
                            LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer` = `tbl_invoice`.`tbl_customer_idtbl_customer`
                            WHERE `tbl_invoice`.`status`=1 AND `tbl_invoice`.`idtbl_invoice`=?";
        $invoiceinforespond = $this->db->query($sqlinvoiceinfo, array($recordID));

        if ($invoiceinforespond->num_rows() == 0) {
            echo 'Invoice not found.';
            return;
        }

        $invdate       = $invoiceinforespond->row(0)->invdate;
        $invid         = $invoiceinforespond->row(0)->idtbl_invoice;
        $grosstotal    = $invoiceinforespond->row(0)->grosstotal;
        $discount      = $invoiceinforespond->row(0)->discount;
        $nettotal      = $invoiceinforespond->row(0)->nettotal;
        $invtype       = $invoiceinforespond->row(0)->invtype;     // 1 Cash, 2 Card, 3 Credit
        $warrantystatus= $invoiceinforespond->row(0)->warrantystatus;
        $custname      = $invoiceinforespond->row(0)->customername;
        $custcontact   = $invoiceinforespond->row(0)->customercontact;
        $custcode      = $invoiceinforespond->row(0)->customercode;

        $sqlproduct = "SELECT `tbl_material_info`.`materialinfocode`, `tbl_material_info`.`materialname`,
                               `tbl_invoice_detail`.`qty`, `tbl_invoice_detail`.`saleprice`, `tbl_invoice_detail`.`discount` AS linediscount
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

        $sqlpayment = "SELECT `tbl_invoice_payment_detail`.`method`, `tbl_invoice_payment`.`amount`, `tbl_invoice_payment`.`baance`,
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
        $cashReceived = 0;
        $changeGiven  = 0;
        $cardTypeLabel = '';
        $cardLast4     = '';

        if ($paymentrespond->num_rows() > 0) {
            $totalpay = $paymentrespond->row(0)->paynettotal ? $paymentrespond->row(0)->paynettotal : 0;
            $balance  = $paymentrespond->row(0)->balance ? $paymentrespond->row(0)->balance : 0;
            $paymentLines = $paymentrespond->result();

            foreach ($paymentLines as $payLine) {
                if (strtolower($payLine->method) === 'cash') {
                    $cashReceived = $payLine->amount;
                    $changeGiven  = max(0, $cashReceived - $nettotal);
                }
                if (strtolower($payLine->method) === 'card') {
                    $cardTypeLabel = $payLine->bank;
                    $cardLast4     = $payLine->chequeno;
                }
            }
        }

        $tblinvoice = '';
        $rowIdx = 1;
        foreach ($productrespond->result() as $rowlist) {
            $linetotal = ($rowlist->qty * $rowlist->saleprice) - (float)$rowlist->linediscount;
            $discpct = ($rowlist->qty * $rowlist->saleprice) > 0
                ? round(($rowlist->linediscount / ($rowlist->qty * $rowlist->saleprice)) * 100, 1)
                : 0;

            $tblinvoice .= '
                <tr>
                    <td class="idx">' . $rowIdx . '</td>
                    <td><div class="pname">' . htmlspecialchars($rowlist->materialname) . '</div><div class="pcode">' . htmlspecialchars($rowlist->materialinfocode) . '</div></td>
                    <td class="num">' . $rowlist->qty . '</td>
                    <td class="num">' . number_format($rowlist->saleprice, 2) . '</td>
                    <td class="num">' . $discpct . '%</td>
                    <td class="num">' . number_format($linetotal, 2) . '</td>
                </tr>
            ';
            $rowIdx++;
        }

        $invTypeLabel = ($invtype == 1) ? 'Cash Sale' : (($invtype == 2) ? 'Card Sale' : 'Credit Sale');
        $statusPillClass = ($invtype == 1 || $invtype == 2) ? '' : 'credit';
        $statusPillText  = ($invtype == 1 || $invtype == 2) ? '&#10003; PAID' : '&#8987; CREDIT';

        $payStripHtml = '';
        if ($invtype == 1) {
            $payStripHtml = '
                <div class="pay-strip">
                    <span class="pay-badge">CASH</span>
                    Full payment received at time of sale.
                </div>';
        } elseif ($invtype == 2) {
            $payStripHtml = '
                <div class="pay-strip">
                    <span class="pay-badge">CARD</span>
                    ' . htmlspecialchars($cardTypeLabel) . ($cardLast4 ? ' ending **** ' . htmlspecialchars($cardLast4) : '') . ' &mdash; payment approved.
                </div>';
        }

        $extraTotalsHtml = '';
        if ($invtype == 1) {
            $extraTotalsHtml = '
                <div class="paid-row"><span>Cash Received</span><span>Rs. ' . number_format($cashReceived, 2) . '</span></div>
                <div class="change-row"><span>Change Given</span><span>Rs. ' . number_format($changeGiven, 2) . '</span></div>
            ';
        } elseif ($invtype == 2) {
            $extraTotalsHtml = '
                <div class="paid-row"><span>Amount Charged</span><span>Rs. ' . number_format($totalpay, 2) . '</span></div>
            ';
        }

        // Warranty box only renders when warrantystatus = 1 for this invoice.
        $warrantyHtml = ($warrantystatus == 1) ? $this->_warrantyBoxHtml() : '';

        $html = $this->_renderInvoiceShell(array(
            'doc_title'        => 'INVOICE',
            'invoice_no'       => 'INV-' . str_pad($invid, 6, '0', STR_PAD_LEFT),
            'invdate'          => date('d M Y, h:i A', strtotime($invdate)),
            'cashier'          => $cashier,
            'status_pill_cls'  => $statusPillClass,
            'status_pill_text' => $statusPillText,
            'cust_name'        => $custname ? $custname : 'Cash Customer',
            'cust_meta'        => $custcontact ? ($custcontact . ' &middot; ' . ($custcode ? $custcode : 'CASH')) : 'Walk-in &middot; No account',
            'sale_type_label'  => $invTypeLabel,
            'sale_type_sub'    => ($invtype == 3) ? 'Settle within agreed credit period' : 'Walk-in counter sale',
            'itemcount'        => $itemcount,
            'sumqty'           => $sumqty,
            'items_html'       => $tblinvoice,
            'subtotal'         => $grosstotal,
            'discount'         => $discount,
            'nettotal'         => $nettotal,
            'extra_totals_html'=> $extraTotalsHtml,
            'pay_strip_html'   => $payStripHtml,
            'notice_html'      => '',
            'warranty_html'    => $warrantyHtml,
        ));

        echo $html;
        ?>
        <script>
            window.print();
            setTimeout(() => { window.close(); }, 5000);
        </script>
        <?php
    }

    /**
     * Lifetime Warranty box — only ever included in the HTML when warrantystatus = 1.
     * Placed after the totals block and before the closing footer hr/divider.
     */
    private function _warrantyBoxHtml(){
        return '
            <div class="warranty-box">
                <div class="w-logo">
                    <svg width="28" height="28" viewBox="0 0 64 64" fill="none">
                        <circle cx="18" cy="34" r="13" stroke="#ffffff" stroke-width="4"/>
                        <circle cx="46" cy="34" r="13" stroke="#ffffff" stroke-width="4"/>
                        <path d="M31 32 L33 32" stroke="#ffffff" stroke-width="4" stroke-linecap="round"/>
                        <path d="M5 30 L8 26" stroke="#ffffff" stroke-width="4" stroke-linecap="round"/>
                        <path d="M59 30 L56 26" stroke="#ffffff" stroke-width="4" stroke-linecap="round"/>
                    </svg>
                </div>
                <div class="w-text">
                    <div class="w-title">Frame Warranty <span class="badge">Lifetime</span></div>
                    <div class="w-desc">
                        This frame is covered by කණ්ණාඩිය Optical Lifetime Warranty against manufacturing
                        defects. Present this invoice for free repair or replacement assessment. Excludes accidental damage, misuse, and normal wear.
                    </div>
                </div>
            </div>
        ';
    }

    /**
     * Shared A4 invoice shell used by both Cash/Card and Credit invoices,
     * so branding/header/footer stay perfectly consistent across all bill types.
     */
    private function _renderInvoiceShell($d){

        return '
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>' . $d['doc_title'] . ' ' . $d['invoice_no'] . '</title>
    <style>
        @page { size: A4; margin: 0; }
        * { margin:0; padding:0; box-sizing:border-box; font-family: "Helvetica", Arial, sans-serif; }
        body { color:#1c2b33; width:210mm; }
        .page { width:210mm; min-height:297mm; padding:14mm 16mm; position:relative; display:flex; flex-direction:column; }

        .inv-header { display:flex; justify-content:space-between; align-items:flex-start; border-bottom:3px solid #0d7890; padding-bottom:14px; margin-bottom:18px; }
        .brand-block { display:flex; align-items:center; gap:12px; }
        .logo-circle { width:58px; height:58px; border-radius:50%; background:linear-gradient(135deg,#0d7890,#1fb3c4); display:flex; align-items:center; justify-content:center; flex:none; }
        .brand-name { font-size:22px; font-weight:800; color:#075363; letter-spacing:.2px; }
        .brand-sub { font-size:10.5px; color:#6b7d85; margin-top:2px; }

        .inv-meta {
            text-align: right;
        }

        .inv-meta-table {
            margin-top: 8px;
            font-size: 11px;
            margin-left: auto; /* keeps table pushed right */
        }

        .inv-meta-table td {
            padding: 2px 0;
            text-align: left; /* 👈 THIS is the key fix */
        }

        .inv-title { font-size:24px; font-weight:800; color:#0d7890; letter-spacing:1px; }
        .inv-meta-table td.lbl { color:#6b7d85; padding-right:10px; text-align:right; }
        .inv-meta-table td.val { font-weight:700; color:#1c2b33; text-align:right; }

        .status-pill { display:inline-block; margin-top:8px; padding:4px 14px; border-radius:999px; font-size:11px; font-weight:700; background:#e7f8ee; color:#1f9d55; }
        .status-pill.credit { background:#fff3df; color:#b9790a; }

        .info-strip { display:flex; gap:16px; margin-bottom:18px; }
        .info-box { flex:1; background:#f7fafb; border:1px solid #dde6e9; border-radius:10px; padding:12px 14px; }
        .info-box .lbl { font-size:9.5px; text-transform:uppercase; letter-spacing:.5px; color:#6b7d85; font-weight:700; margin-bottom:4px; }
        .info-box .val { font-size:13px; font-weight:700; color:#1c2b33; }
        .info-box .sub { font-size:10.5px; color:#6b7d85; margin-top:2px; }

        table.items { width:100%; border-collapse:collapse; margin-bottom:18px; }
        table.items thead th { background:#0d7890; color:#fff; font-size:10.5px; text-transform:uppercase; letter-spacing:.4px; padding:9px 10px; text-align:left; }
        table.items thead th.num { text-align:right; }
        table.items tbody td { padding:9px 10px; font-size:12px; border-bottom:1px solid #eef2f3; }
        table.items tbody td.num { text-align:right; font-weight:600; }
        table.items tbody tr:nth-child(even) { background:#fafdfd; }
        table.items tbody td.idx { color:#6b7d85; width:28px; }
        table.items tbody td.pname { font-weight:700; }
        table.items tbody td.pcode { color:#6b7d85; font-size:10px; font-family:monospace; }

        .totals-wrap { display:flex; justify-content:flex-end; margin-bottom:18px; }
        .totals-box { width:280px; }
        .totals-box .row { display:flex; justify-content:space-between; font-size:12px; padding:5px 0; color:#475a61; }
        .totals-box .row b { color:#1c2b33; font-weight:700; }
        .totals-box .row.discount b { color:#d6394a; }
        .totals-box .grand { display:flex; justify-content:space-between; align-items:baseline; margin-top:6px; padding-top:10px; border-top:2px dashed #0d7890; }
        .totals-box .grand .lbl { font-size:13px; font-weight:800; color:#075363; }
        .totals-box .grand .val { font-size:22px; font-weight:800; color:#0d7890; }
        .totals-box .paid-row { margin-top:10px; padding:8px 12px; border-radius:8px; background:#e7f8ee; display:flex; justify-content:space-between; font-weight:700; color:#1f9d55; font-size:12px;}
        .totals-box .change-row { margin-top:6px; padding:8px 12px; border-radius:8px; background:#e6f7f9; display:flex; justify-content:space-between; font-weight:700; color:#075363; font-size:12px;}
        .totals-box .due-row { margin-top:6px; padding:10px 12px; border-radius:8px; background:#fdebed; display:flex; justify-content:space-between; font-weight:800; color:#d6394a; font-size:14px; border:1.5px solid #f4c6cc;}

        .pay-strip { display:flex; align-items:center; gap:10px; background:#f7fafb; border:1px solid #dde6e9; border-radius:10px; padding:10px 14px; margin-bottom:18px; font-size:11.5px; color:#475a61; }
        .pay-strip .pay-badge { background:#0d7890; color:#fff; font-size:10.5px; font-weight:700; padding:3px 10px; border-radius:6px; }

        .credit-notice { background:#fff3df; border:1px solid #f0d9a8; border-radius:10px; padding:12px 14px; margin-bottom:18px; font-size:11.5px; color:#8a5a13; display:flex; align-items:center; gap:10px; }
        .credit-notice b { color:#b9790a; }

        /* ===== WARRANTY BOX ===== */
        .warranty-box {
            display:flex; align-items:center; gap:16px;
            background:linear-gradient(135deg,#e6f7f9,#f0fbfc);
            border:1.5px solid #1fb3c4;
            border-radius:12px;
            padding:16px 18px;
            margin-bottom:18px;
        }
        .warranty-box .w-logo { width:52px; height:52px; border-radius:50%; background:linear-gradient(135deg,#0d7890,#1fb3c4); display:flex; align-items:center; justify-content:center; flex:none; }
        .warranty-box .w-text .w-title { font-size:14px; font-weight:800; color:#075363; display:flex; align-items:center; gap:6px; }
        .warranty-box .w-text .w-title .badge { background:#0d7890; color:#fff; font-size:9.5px; font-weight:700; padding:2px 8px; border-radius:999px; text-transform:uppercase; letter-spacing:.4px; }
        .warranty-box .w-text .w-desc { font-size:10.5px; color:#475a61; margin-top:4px; line-height:1.5; }

        /* ===== FOOTER ===== */
        .footer-area { margin-top:auto; padding-top:14px; border-top:1px solid #dde6e9; }
        .footer-thanks-row { display:flex; align-items:center; justify-content:center; gap:10px; margin-bottom:8px; }
        .footer-thanks-row .f-logo { width:30px; height:30px; border-radius:50%; background:linear-gradient(135deg,#0d7890,#1fb3c4); display:flex; align-items:center; justify-content:center; flex:none; }
        .footer-thanks-row .f-text { font-size:14px; font-weight:800; color:#0d7890; }
        .footer-sub-text { text-align:center; font-size:10.5px; color:#6b7d85; margin-bottom:8px; }
        .footer-terms { font-size:9px; color:#9aa7ac; text-align:center; line-height:1.5; margin-bottom:10px; }
        .footer-contact { text-align:center; font-size:9.5px; color:#475a61; background:#f7fafb; border-radius:8px; padding:8px 12px; }
        .footer-contact b { color:#0d7890; }
    </style>
</head>
<body>
<div class="page">

    <div class="inv-header">
        <div class="brand-block">
            <img src="' . base_url('images/clientlogo.png') . '" 
                alt="Logo"
                style="height:70px; width:auto; display:block;">
        </div>
        <div class="inv-meta">
            <div class="inv-title">' . $d['doc_title'] . '</div>
            <table class="inv-meta-table">
                <tr><td class="lbl">Invoice No.</td><td class="val">' . $d['invoice_no'] . '</td></tr>
                <tr><td class="lbl">Date</td><td class="val">' . $d['invdate'] . '</td></tr>
                <tr><td class="lbl">Cashier</td><td class="val">' . htmlspecialchars($d['cashier']) . '</td></tr>
            </table>
            <div class="status-pill ' . $d['status_pill_cls'] . '">' . $d['status_pill_text'] . '</div>
        </div>
    </div>

    <div class="info-strip">
        <div class="info-box">
            <div class="lbl">Billed To</div>
            <div class="val">' . htmlspecialchars($d['cust_name']) . '</div>
            <div class="sub">' . $d['cust_meta'] . '</div>
        </div>
        <div class="info-box">
            <div class="lbl">Sale Type</div>
            <div class="val">' . htmlspecialchars($d['sale_type_label']) . '</div>
            <div class="sub">' . htmlspecialchars($d['sale_type_sub']) . '</div>
        </div>
        <div class="info-box">
            <div class="lbl">Items / Qty</div>
            <div class="val">' . $d['itemcount'] . ' items</div>
            <div class="sub">' . $d['sumqty'] . ' total quantity</div>
        </div>
    </div>

    <table class="items">
        <thead>
            <tr>
                <th style="width:28px;">#</th>
                <th>Item</th>
                <th class="num" style="width:60px;">Qty</th>
                <th class="num" style="width:90px;">Price</th>
                <th class="num" style="width:70px;">Disc%</th>
                <th class="num" style="width:100px;">Total</th>
            </tr>
        </thead>
        <tbody>' . $d['items_html'] . '</tbody>
    </table>

    <div class="totals-wrap">
        <div class="totals-box">
            <div class="row"><span>Subtotal</span><b>Rs. ' . number_format($d['subtotal'], 2) . '</b></div>
            <div class="row discount"><span>Discount</span><b>- Rs. ' . number_format($d['discount'], 2) . '</b></div>
            <div class="grand">
                <span class="lbl">Net Total</span>
                <span class="val">Rs. ' . number_format($d['nettotal'], 2) . '</span>
            </div>
            ' . $d['extra_totals_html'] . '
        </div>
    </div>

    ' . $d['pay_strip_html'] . '
    ' . $d['notice_html'] . '
    ' . $d['warranty_html'] . '

    <div class="footer-area">
        <div class="footer-thanks-row">
            <div class="f-text">Thank you for choosing කණ්ණාඩිය Optical!</div>
        </div>
        <div class="footer-contact">
            <b>කණ්ණාඩිය Pvt Ltd</b> &middot; Thalawa Road, Kekirawa &middot; 0719622717 / 0703955682 &middot; Kannadiyaopticalservice@gmail.com
        </div>
    </div>
</div>
</body>
</html>';
    }
}