<?php
class Purchaseorderprintinfo extends CI_Model{
    
    /**
     * Generates an A4 PDF for a Purchase Order, styled to match the
     * POS invoice color scheme (#0d7890 teal). Streamed inline via dompdf
     * so it opens in a new tab as a real PDF (Attachment => 0).
     */
    public function Getporderprintpdf($x){

        $recordID = $x;

        $sql = "SELECT `tbl_porder`.`idtbl_porder`, `tbl_porder`.`orderdate`, `tbl_porder`.`duedate`,
                    `tbl_porder`.`subtotal`, `tbl_porder`.`discount`, `tbl_porder`.`discountamount`,
                    `tbl_porder`.`nettotal`, `tbl_porder`.`confirmstatus`, `tbl_porder`.`grnconfirm`,
                    `tbl_porder`.`remark`,
                    `tbl_supplier`.`suppliername`, `tbl_supplier`.`suppliercode`,
                    `tbl_supplier`.`primarycontactno`, `tbl_supplier`.`secondarycontactno`,
                    `tbl_supplier`.`address`, `tbl_supplier`.`email`
                FROM `tbl_porder`
                LEFT JOIN `tbl_supplier` ON `tbl_supplier`.`idtbl_supplier` = `tbl_porder`.`tbl_supplier_idtbl_supplier`
                WHERE `tbl_porder`.`status`=1 AND `tbl_porder`.`idtbl_porder`=?";
        $porderrespond = $this->db->query($sql, array($recordID));

        if ($porderrespond->num_rows() == 0) {
            echo 'Purchase Order not found.';
            return;
        }

        $row = $porderrespond->row(0);

        $sqldetail = "SELECT `tbl_porder_detail`.`qty`, `tbl_porder_detail`.`unitprice`,
                            `tbl_porder_detail`.`discount`, `tbl_porder_detail`.`discountamount`,
                            `tbl_porder_detail`.`comment`,
                            `tbl_material_info`.`materialinfocode`, `tbl_material_info`.`materialname`
                    FROM `tbl_porder_detail`
                    LEFT JOIN `tbl_material_info` ON `tbl_material_info`.`idtbl_material_info` = `tbl_porder_detail`.`tbl_material_info_idtbl_material_info`
                    WHERE `tbl_porder_detail`.`tbl_porder_idtbl_porder`=? AND `tbl_porder_detail`.`status`=1";
        $detailrespond = $this->db->query($sqldetail, array($recordID));

        $itemcount = 0;
        $sumqty    = 0;
        $tblitems  = '';
        $rowIdx    = 1;

        foreach ($detailrespond->result() as $d) {
            $linetotal = ($d->qty * $d->unitprice) - (float)$d->discountamount;
            $itemcount++;
            $sumqty += $d->qty;

            $tblitems .= '
                <tr>
                    <td class="idx">' . $rowIdx . '</td>
                    <td>
                        <div class="pname">' . htmlspecialchars($d->materialname) . '</div>
                        <div class="pcode">' . htmlspecialchars($d->materialinfocode) . '</div>
                        ' . ($d->comment ? '<div class="pcomment">' . htmlspecialchars($d->comment) . '</div>' : '') . '
                    </td>
                    <td class="num">' . $d->qty . '</td>
                    <td class="num">' . number_format($d->unitprice, 2) . '</td>
                    <td class="num">' . number_format($linetotal, 2) . '</td>
                </tr>
            ';
            $rowIdx++;
        }

        $confirmStatusText = ($row->confirmstatus == 1) ? '&#10003; CONFIRMED' : '&#8987; PENDING CONFIRMATION';
        $confirmStatusCls  = ($row->confirmstatus == 1) ? '' : 'credit';

        $contactNumbers = array();
        if (!empty($row->primarycontactno))   { $contactNumbers[] = $row->primarycontactno; }
        if (!empty($row->secondarycontactno)) { $contactNumbers[] = $row->secondarycontactno; }
        $contactLine = !empty($contactNumbers) ? implode(' / ', $contactNumbers) : '-';

        $supMeta = htmlspecialchars($contactLine);
        if ($row->suppliercode) { $supMeta .= ' &middot; ' . htmlspecialchars($row->suppliercode); }
        if ($row->address)      { $supMeta .= '<br>' . htmlspecialchars($row->address); }
        if ($row->email)        { $supMeta .= '<br>' . htmlspecialchars($row->email); }

        $html = $this->_renderPorderShell(array(
            'po_no'            => 'CALX/POD-' . str_pad($row->idtbl_porder, 6, '0', STR_PAD_LEFT),
            'orderdate'        => date('d M Y', strtotime($row->orderdate)),
            'duedate'          => date('d M Y', strtotime($row->duedate)),
            'confirm_cls'      => $confirmStatusCls,
            'confirm_text'     => $confirmStatusText,
            'sup_name'         => $row->suppliername ? $row->suppliername : 'Supplier',
            'sup_meta'         => $supMeta,
            'itemcount'        => $itemcount,
            'sumqty'           => $sumqty,
            'items_html'       => $tblitems,
            'subtotal'         => $row->subtotal,
            'discount'         => $row->discountamount,
            'nettotal'         => $row->nettotal,
            'remark'           => $row->remark,
        ));

        $this->load->library('pdf');
        $this->pdf->loadHtml($html);
        $this->pdf->render();
        $this->pdf->stream('PO-' . $row->idtbl_porder . '.pdf', array('Attachment' => 0));
    }

    /**
     * A4 Purchase Order PDF shell, styled with the same teal (#0d7890)
     * color scheme used across the POS invoices for brand consistency.
     */
   private function _renderPorderShell($d){

    return '
        <!doctype html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <title>Purchase Order ' . $d['po_no'] . '</title>
            <style>
                @page { size: A4; margin: 0; }
                * { margin:0; padding:0; box-sizing:border-box; font-family: "Helvetica", Arial, sans-serif; }
                body { color:#1c2b33; width:178mm; }
                .page { width:178mm; padding:14mm 16mm; }

                .header-table { width:178mm; border-collapse:collapse; margin-bottom:0; }
                .header-table td { vertical-align:top; padding:0; }
                .header-left  { width:90mm; }
                .header-right { width:88mm; text-align:right; }

                .inv-header { border-bottom:3px solid #0d7890; padding-bottom:14px; margin-bottom:18px; }
                .brand-name { font-size:20px; font-weight:800; color:#075363; letter-spacing:.2px; }
                .brand-sub { font-size:10px; color:#6b7d85; margin-top:2px; }

                .inv-meta-table { margin-top:8px; font-size:10.5px; width:100%; border-collapse:collapse; }
                .inv-meta-table td { padding:2px 0; }
                .inv-meta-table td.lbl { color:#6b7d85; text-align:right; padding-right:8px; white-space:nowrap; }
                .inv-meta-table td.val { font-weight:700; color:#1c2b33; text-align:right; white-space:nowrap; }

                .inv-title { font-size:20px; font-weight:800; color:#0d7890; letter-spacing:1px; }

                .status-pill { display:inline-block; margin-top:8px; padding:4px 14px; border-radius:999px; font-size:10.5px; font-weight:700; background:#e7f8ee; color:#1f9d55; }
                .status-pill.credit { background:#fff3df; color:#b9790a; }

                .info-strip-table { width:178mm; border-collapse:separate; border-spacing:8px 0; margin-bottom:18px; }
                .info-box { width:89mm; background:#f7fafb; border:1px solid #dde6e9; border-radius:10px; padding:12px 14px; vertical-align:top; }
                .info-box .lbl { font-size:9px; text-transform:uppercase; letter-spacing:.5px; color:#6b7d85; font-weight:700; margin-bottom:4px; }
                .info-box .val { font-size:12.5px; font-weight:700; color:#1c2b33; }
                .info-box .sub { font-size:10px; color:#6b7d85; margin-top:2px; line-height:1.5; }

                table.items { width:178mm; border-collapse:collapse; margin-bottom:18px; table-layout:fixed; }
                table.items thead th { background:#0d7890; color:#fff; font-size:10px; text-transform:uppercase; letter-spacing:.4px; padding:9px 8px; text-align:left; }
                table.items thead th.num { text-align:right; }
                table.items tbody td { padding:9px 8px; font-size:11px; border-bottom:1px solid #eef2f3; word-wrap:break-word; }
                table.items tbody td.num { text-align:right; font-weight:600; }
                table.items tbody tr:nth-child(even) { background:#fafdfd; }
                table.items tbody td.idx { color:#6b7d85; width:22px; }
                table.items tbody td.pname { font-weight:700; }
                table.items tbody td.pcode { color:#6b7d85; font-size:9.5px; font-family:monospace; }
                table.items tbody td.pcomment { color:#8a7a13; font-size:9.5px; font-style:italic; margin-top:2px; }

                .totals-table { width:178mm; border-collapse:collapse; margin-bottom:18px; }
                .totals-table td { vertical-align:top; padding:0; }
                .totals-spacer { width:98mm; }
                .totals-box { width:80mm; }
                .totals-box table { width:100%; border-collapse:collapse; }
                .totals-box .row td { font-size:11.5px; padding:5px 0; color:#475a61; }
                .totals-box .row td.val { text-align:right; font-weight:700; color:#1c2b33; }
                .totals-box .row.discount td.val { color:#d6394a; }
                .totals-box .grand td { padding-top:10px; border-top:2px dashed #0d7890; }
                .totals-box .grand .lbl { font-size:12.5px; font-weight:800; color:#075363; }
                .totals-box .grand .val { font-size:19px; font-weight:800; color:#0d7890; text-align:right; }

                .remark-box { background:#f7fafb; border:1px solid #dde6e9; border-radius:10px; padding:12px 14px; margin-bottom:18px; font-size:11px; color:#475a61; width:178mm; }
                .remark-box .lbl { font-size:9px; text-transform:uppercase; letter-spacing:.5px; color:#6b7d85; font-weight:700; margin-bottom:4px; }

                .footer-area { margin-top:30px; padding-top:14px; border-top:1px solid #dde6e9; width:178mm; }
                .footer-thanks-row { text-align:center; margin-bottom:8px; }
                .footer-thanks-row .f-text { font-size:13px; font-weight:800; color:#0d7890; }
                .footer-contact { text-align:center; font-size:9px; color:#475a61; background:#f7fafb; border-radius:8px; padding:8px 12px; }

                .sign-table { width:178mm; margin-top:50px; border-collapse:collapse; }
                .sign-col { width:89mm; text-align:center; vertical-align:top; }
                .sign-line { border-top:1px solid #1c2b33; width:75%; margin:0 auto 6px; padding-top:1px; }
                .sign-label { font-size:10px; color:#6b7d85; }
            </style>
        </head>
        <body>
        <div class="page">

            <table class="header-table">
                <tr>
                    <td class="header-left">
                        <div class="brand-name">' . htmlspecialchars($_SESSION['companyname'] ?? 'Company Name') . '</div>
                        <div class="brand-sub">Purchase Order Document</div>
                    </td>
                    <td class="header-right">
                        <div class="inv-title">PURCHASE ORDER</div>
                        <table class="inv-meta-table">
                            <tr><td class="lbl">PO No.</td><td class="val">' . $d['po_no'] . '</td></tr>
                            <tr><td class="lbl">Order Date</td><td class="val">' . $d['orderdate'] . '</td></tr>
                            <tr><td class="lbl">Due Date</td><td class="val">' . $d['duedate'] . '</td></tr>
                        </table>
                        <div class="status-pill ' . $d['confirm_cls'] . '">' . $d['confirm_text'] . '</div>
                    </td>
                </tr>
            </table>
            <div class="inv-header"></div>

            <table class="info-strip-table">
                <tr>
                    <td class="info-box">
                        <div class="lbl">Supplier</div>
                        <div class="val">' . htmlspecialchars($d['sup_name']) . '</div>
                        <div class="sub">' . $d['sup_meta'] . '</div>
                    </td>
                    <td class="info-box">
                        <div class="lbl">Order Summary</div>
                        <div class="val">' . $d['itemcount'] . ' items</div>
                        <div class="sub">' . $d['sumqty'] . ' total quantity</div>
                    </td>
                </tr>
            </table>

            <table class="items">
                <thead>
                    <tr>
                        <th style="width:22px;">#</th>
                        <th>Item</th>
                        <th class="num" style="width:50px;">Qty</th>
                        <th class="num" style="width:75px;">Unit Price</th>
                        <th class="num" style="width:85px;">Total</th>
                    </tr>
                </thead>
                <tbody>' . $d['items_html'] . '</tbody>
            </table>

            <table class="totals-table">
                <tr>
                    <td class="totals-spacer"></td>
                    <td class="totals-box">
                        <table>
                            <tr class="row"><td>Subtotal</td><td class="val">Rs. ' . number_format($d['subtotal'], 2) . '</td></tr>
                            <tr class="row discount"><td>Discount</td><td class="val">- Rs. ' . number_format($d['discount'], 2) . '</td></tr>
                            <tr class="grand"><td class="lbl">Net Total</td><td class="val">Rs. ' . number_format($d['nettotal'], 2) . '</td></tr>
                        </table>
                    </td>
                </tr>
            </table>

            ' . ($d['remark'] ? '
            <div class="remark-box">
                <div class="lbl">Remark</div>
                ' . htmlspecialchars($d['remark']) . '
            </div>' : '') . '

            <table class="sign-table">
                <tr>
                    <td class="sign-col">
                        <div class="sign-line"></div>
                        <div class="sign-label">Authorized Signature</div>
                    </td>
                    <td class="sign-col">
                        <div class="sign-line"></div>
                        <div class="sign-label">Supplier Acknowledgement</div>
                    </td>
                </tr>
            </table>

            <div class="footer-area">
                <div class="footer-thanks-row">
                    <div class="f-text">Thank you for your business</div>
                </div>
                <div class="footer-contact">
                    This is a system-generated Purchase Order. Please contact us for any clarification regarding this order.
                </div>
            </div>
        </div>
        </body>
        </html>';
    }
    
}