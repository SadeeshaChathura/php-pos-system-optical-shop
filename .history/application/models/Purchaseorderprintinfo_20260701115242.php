<?php
class Purchaseorderprintinfo extends CI_Model{
    
    /**
     * Generates an A4 PDF for a Purchase Order, styled around the
     * Kannadiya Optical Services brand mark (navy #254F96). Streamed
     * inline via dompdf so it opens in a new tab as a real PDF
     * (Attachment => 0).
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
            'po_no'            => 'KND/PO-' . str_pad($row->idtbl_porder, 6, '0', STR_PAD_LEFT),
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
     * A4 Purchase Order PDF shell, redesigned around the real
     * Kannadiya Optical Services logo mark (navy #254F96), which
     * already contains the Sinhala wordmark baked into the artwork
     * — so no separate Sinhala text rendering is needed anywhere.
     */
   private function _renderPorderShell($d){

    // Business details (hardcoded brand info shown in header & footer)
    $bizPhone = '0719622717 / 0703955682';
    $bizEmail = 'Kannadiyaopticalservice@gmail.com';
    $bizAddr  = 'Kannadiya Pvt LTD, Thalawa Road, Kekirawa';

    // Logo: dompdf needs a real, reachable FILESYSTEM path (not a base_url()
    // http path) to embed an image reliably. Drop the logo file at
    // application/../assets/images/logo.png (adjust FCPATH below if your
    // folder layout differs). The logo artwork already contains the
    // Sinhala wordmark + "OPTICAL SERVICES" subtitle, so it stands alone
    // as the brand mark — no separate text name is rendered next to it.
    $bizLogoPath = FCPATH . 'images/clientlogo.png';

    $headerBrandBlock = '
    <div class="brand-block">
        <img src="' . $bizLogoPath . '" 
            alt="Logo"
            style="height:70px; width:auto; display:block;">
    </div>';

    $footerBrandBlock = '
    <div class="brand-block">
        <img src="' . $bizLogoPath . '" 
            alt="Logo"
            style="height:40px; width:auto; display:block;">
    </div>';

    return '
        <!doctype html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <title>Purchase Order ' . $d['po_no'] . '</title>
            <style>
                @page { size: A4; margin: 0; }
                * { margin:0; padding:0; box-sizing:border-box; font-family: "Helvetica", Arial, sans-serif; }
                body { color:#1c2b33; width:210mm; }

                /* Full-bleed top accent bar in the brand navy */
                .top-bar { width:210mm; height:7px; background:#254F96; }

                .page { width:210mm; padding:10mm 16mm 4mm 16mm; }

                /* ---------------- STICKY-BOTTOM FOOTER WRAPPER ----------------
                   position:fixed proved unreliable in dompdf (it silently
                   failed to render at all on a single-page document). This
                   uses a plain two-row HTML table instead: the table has an
                   explicit total height matching the printable page area,
                   the footer row has a fixed height, and the content row is
                   left auto — so per the standard CSS2.1 table height
                   algorithm, all the leftover vertical space is absorbed by
                   the content row, which naturally pushes the footer row to
                   sit right at the bottom of the page. This is a much more
                   dompdf-reliable technique than position:fixed. */
                .page-content-table { width:178mm; height:270mm; border-collapse:collapse; }
                .page-content-table td { padding:0; }
                .content-cell { vertical-align:top; }
                .footer-cell { height:62mm; vertical-align:bottom; }

                /* ---------------- HEADER ---------------- */
                .header-table { width:178mm; border-collapse:collapse; margin-bottom:14px; }
                .header-table td { vertical-align:middle; padding:0; }
                .header-left  { width:100mm; }
                .header-right { width:78mm; text-align:right; vertical-align:top; }

                .logo-img { max-height:34mm; max-width:95mm; display:block; }
                .brand-name-fallback { font-size:20px; font-weight:800; color:#254F96; line-height:1.3; }
                .brand-name-fallback span { display:block; font-size:10px; font-weight:600; letter-spacing:2px; color:#5c7099; }

                .inv-title-badge { display:inline-block; background:#254F96; color:#fff; font-size:12px; font-weight:800; letter-spacing:2px; padding:6px 16px; border-radius:5px; margin-bottom:10px; }

                .inv-meta-table { font-size:10.5px; width:100%; border-collapse:collapse; }
                .inv-meta-table td { padding:2px 0; }
                .inv-meta-table td.lbl { color:#6b7d85; text-align:right; padding-right:8px; white-space:nowrap; }
                .inv-meta-table td.val { font-weight:700; color:#1c2b33; text-align:right; white-space:nowrap; }

                .status-pill { display:inline-block; margin-top:10px; margin-bottom:6px; padding:5px 16px; border-radius:999px; font-size:10.5px; font-weight:700; background:#e7f8ee; color:#1f9d55; }
                .status-pill.credit { background:#fff3df; color:#b9790a; }

                .header-divider { width:178mm; height:3px; background:#254F96; border-radius:2px; margin-bottom:20px; }

                .biz-strip { width:178mm; font-size:9.5px; color:#6b7d85; margin-bottom:18px; line-height:1.6; }

                /* ---------------- INFO CARDS (v2 style) ----------------
                   Single unified panel, split into two columns by a thin
                   vertical rule, with a small uppercase label chip at the
                   top of each column instead of a colored left stripe. */
                .info-panel { width:178mm; border:1px solid #dde3ee; border-radius:10px; margin-bottom:20px; border-collapse:collapse; table-layout:fixed; overflow:hidden; }
                .info-panel td { vertical-align:top; padding:16px 20px; word-wrap:break-word; overflow-wrap:break-word; }
                .info-col-a { width:89mm; border-right:1px solid #e5e9f2; }
                .info-col-b { width:89mm; }
                .info-chip { display:inline-block; background:#254F96; color:#fff; font-size:8.5px; font-weight:800; text-transform:uppercase; letter-spacing:.6px; padding:3px 9px; border-radius:4px; margin-bottom:9px; }
                .info-panel .val { font-size:13px; font-weight:700; color:#1c2b33; margin-bottom:4px; }
                .info-panel .sub { font-size:10px; color:#6b7d85; line-height:1.7; }

                /* ---------------- ITEMS TABLE ---------------- */
                table.items { width:178mm; border-collapse:collapse; margin-bottom:20px; table-layout:fixed; }
                table.items thead th { background:#254F96; color:#fff; font-size:10px; text-transform:uppercase; letter-spacing:.5px; padding:10px 8px; text-align:left; }
                table.items thead th.num { text-align:right; }
                table.items thead th:first-child { border-radius:6px 0 0 0; }
                table.items thead th:last-child { border-radius:0 6px 0 0; }
                table.items tbody td { padding:9px 8px; font-size:11px; border-bottom:1px solid #eef1f7; word-wrap:break-word; }
                table.items tbody td.num { text-align:right; font-weight:600; }
                table.items tbody tr:nth-child(even) { background:#F7F9FC; }
                table.items tbody td.idx { color:#8091a8; width:22px; }
                table.items tbody td.pname { font-weight:700; }
                table.items tbody td.pcode { color:#8091a8; font-size:9.5px; font-family:monospace; }
                table.items tbody td.pcomment { color:#8a7a13; font-size:9.5px; font-style:italic; margin-top:2px; }

                /* ---------------- TOTALS ---------------- */
                .totals-table { width:178mm; border-collapse:collapse; margin-bottom:18px; }
                .totals-table td { vertical-align:top; padding:0; }
                .totals-spacer { width:88mm; }
                .totals-box { width:90mm; background:#F3F6FB; border-radius:8px; padding:14px 18px; }
                .totals-box table { width:100%; border-collapse:collapse; }
                .totals-box .row td { font-size:11.5px; padding:5px 0; color:#475a61; }
                .totals-box .row td.val { text-align:right; font-weight:700; color:#1c2b33; }
                .totals-box .row.discount td.val { color:#d6394a; }
                .totals-box .grand td { padding-top:10px; border-top:2px dashed #254F96; }
                .totals-box .grand .lbl { font-size:13px; font-weight:800; color:#254F96; }
                .totals-box .grand .val { font-size:20px; font-weight:800; color:#254F96; text-align:right; }

                .remark-box { background:#F3F6FB; border-left:4px solid #254F96; border-radius:8px; padding:12px 16px; margin-bottom:18px; font-size:11px; color:#475a61; width:178mm; }
                .remark-box .lbl { font-size:9px; text-transform:uppercase; letter-spacing:.6px; color:#254F96; font-weight:800; margin-bottom:4px; }

                /* ---------------- FOOTER ----------------
                   NOTE: an earlier version used position:fixed to pin this
                   to the bottom of every physical page. That is a well-known
                   dompdf trick, but it renders unreliably on single-page
                   documents (it simply did not show up in testing). This
                   version places the footer in normal document flow
                   instead, directly after the PO content, which always
                   renders, and still reads clearly as a footer band thanks
                   to the top accent bar and solid navy background. */
                .page-footer { width:178mm; margin-top:26px; }
                .footer-accent { width:178mm; height:3px; background:#254F96; border-radius:2px; margin-bottom:16px; }

                .sign-table { width:178mm; margin-bottom:16px; border-collapse:collapse; }
                .sign-col { width:89mm; text-align:center; vertical-align:top; }
                .sign-line { border-top:1px solid #1c2b33; width:70%; margin:0 auto 6px; padding-top:1px; }
                .sign-label { font-size:10px; color:#6b7d85; }

                .footer-contact { width:178mm; background:#254F96; border-radius:8px; padding:10px 16px; border-collapse:collapse; margin-bottom:6px; }
                .footer-contact td { vertical-align:middle; }
                .footer-logo-cell { width:26mm; text-align:center; }
                .footer-logo-img { max-height:9mm; max-width:24mm; }
                .footer-brand-fallback { color:#fff; font-weight:800; font-size:10px; letter-spacing:1px; }
                .footer-text-cell { font-size:9px; color:#dbe4f5; line-height:1.7; padding-left:12px; }
                .footer-thanks { font-size:9.5px; font-weight:700; color:#fff; text-align:right; padding-right:2px; }
            </style>
        </head>
        <body>
        <div class="top-bar"></div>
        <div class="page">
        <table class="page-content-table">
        <tr><td class="content-cell">

            <table class="header-table">
                <tr>
                    <td class="header-left">
                        ' . $headerBrandBlock . '
                    </td>
                    <td class="header-right">
                        <div class="inv-title-badge">PURCHASE ORDER</div>
                        <table class="inv-meta-table">
                            <tr><td class="lbl">PO No.</td><td class="val">' . $d['po_no'] . '</td></tr>
                            <tr><td class="lbl">Order Date</td><td class="val">' . $d['orderdate'] . '</td></tr>
                            <tr><td class="lbl">Due Date</td><td class="val">' . $d['duedate'] . '</td></tr>
                        </table>
                        <div class="status-pill ' . $d['confirm_cls'] . '">' . $d['confirm_text'] . '</div>
                    </td>
                </tr>
            </table>

            <div class="header-divider"></div>

            <div class="biz-strip">' . htmlspecialchars($bizAddr) . ' &middot; ' . htmlspecialchars($bizPhone) . ' &middot; ' . htmlspecialchars($bizEmail) . '</div>

            <table class="info-panel">
                <tr>
                    <td class="info-col-a">
                        <div class="info-chip">Supplier</div>
                        <div class="val">' . htmlspecialchars($d['sup_name']) . '</div>
                        <div class="sub">' . $d['sup_meta'] . '</div>
                    </td>
                    <td class="info-col-b">
                        <div class="info-chip">Order Summary</div>
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

        </td></tr>
        <tr><td class="footer-cell">

            <div class="page-footer">
                <table class="sign-table">
                    <tr>
                        <td class="sign-col">
                            <div class="sign-line"></div><br>
                            <div class="sign-label">Authorized Signature</div>
                        </td>
                        <td class="sign-col">
                            <div class="sign-line"></div><br>
                            <div class="sign-label">Supplier Acknowledgement</div>
                        </td>
                    </tr>
                </table>

                <table class="footer-contact">
                    <tr>
                        <td class="footer-text-cell">
                            ' . htmlspecialchars($bizAddr) . '<br>
                            Tel: ' . htmlspecialchars($bizPhone) . ' &middot; ' . htmlspecialchars($bizEmail) . '
                        </td>
                        <td class="footer-thanks">Thank you for<br>your business</td>
                    </tr>
                </table>
            </div>

        </td></tr>
        </table>
        </div>
        </body>
        </html>';
    }
    
}