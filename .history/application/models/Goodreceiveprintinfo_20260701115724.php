<?php
class Goodreceiveprintinfo extends CI_Model{

    /**
     * Generates an A4 PDF for a Goods Received Note (GRN), styled with
     * the same navy (#254F96) brand system as the Purchase Order print,
     * but laid out differently to suit GRN-specific fields (batch no,
     * invoice no, dispatch no, linked PO, approval status).
     * Streamed inline via dompdf so it opens in a new tab (Attachment => 0).
     */
    public function Getgrnprintpdf($x){

        $recordID = $x;

        $sql = "SELECT `tbl_grn`.`idtbl_grn`, `tbl_grn`.`batchno`, `tbl_grn`.`grndate`,
                    `tbl_grn`.`total`, `tbl_grn`.`invoicenum`, `tbl_grn`.`dispatchnum`,
                    `tbl_grn`.`remark`, `tbl_grn`.`approvestatus`, `tbl_grn`.`insertdatetime`,
                    `tbl_grn`.`tbl_porder_idtbl_porder`,
                    `tbl_supplier`.`suppliername`, `tbl_supplier`.`suppliercode`,
                    `tbl_supplier`.`primarycontactno`, `tbl_supplier`.`secondarycontactno`,
                    `tbl_supplier`.`address`, `tbl_supplier`.`email`
                FROM `tbl_grn`
                LEFT JOIN `tbl_supplier` ON `tbl_supplier`.`idtbl_supplier` = `tbl_grn`.`tbl_supplier_idtbl_supplier`
                WHERE `tbl_grn`.`status`=1 AND `tbl_grn`.`idtbl_grn`=?";
        $grnrespond = $this->db->query($sql, array($recordID));

        if ($grnrespond->num_rows() == 0) {
            echo 'Goods Received Note not found.';
            return;
        }

        $row = $grnrespond->row(0);

        $sqldetail = "SELECT `tbl_grndetail`.`qty`, `tbl_grndetail`.`unitprice`,
                            `tbl_grndetail`.`saleprice`, `tbl_grndetail`.`total`,
                            `tbl_grndetail`.`comment`,
                            `tbl_material_info`.`materialinfocode`, `tbl_material_info`.`materialname`
                    FROM `tbl_grndetail`
                    LEFT JOIN `tbl_material_info` ON `tbl_material_info`.`idtbl_material_info` = `tbl_grndetail`.`tbl_material_info_idtbl_material_info`
                    WHERE `tbl_grndetail`.`tbl_grn_idtbl_grn`=? AND `tbl_grndetail`.`status`=1";
        $detailrespond = $this->db->query($sqldetail, array($recordID));

        $itemcount = 0;
        $sumqty    = 0;
        $tblitems  = '';
        $rowIdx    = 1;

        foreach ($detailrespond->result() as $d) {
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
                    <td class="num">' . number_format($d->total, 2) . '</td>
                </tr>
            ';
            $rowIdx++;
        }

        $approveStatusText = ($row->approvestatus == 1) ? '&#10003; APPROVED' : '&#8987; PENDING APPROVAL';
        $approveStatusCls  = ($row->approvestatus == 1) ? '' : 'credit';

        $contactNumbers = array();
        if (!empty($row->primarycontactno))   { $contactNumbers[] = $row->primarycontactno; }
        if (!empty($row->secondarycontactno)) { $contactNumbers[] = $row->secondarycontactno; }
        $contactLine = !empty($contactNumbers) ? implode(' / ', $contactNumbers) : '-';

        $supMeta = htmlspecialchars($contactLine);
        if ($row->suppliercode) { $supMeta .= ' &middot; ' . htmlspecialchars($row->suppliercode); }
        if ($row->address)      { $supMeta .= '<br>' . htmlspecialchars($row->address); }
        if ($row->email)        { $supMeta .= '<br>' . htmlspecialchars($row->email); }

        // A GRN linked to a Purchase Order has tbl_porder_idtbl_porder set to
        // the real PO id; a "direct" GRN (no PO) falls back to id = 1 at
        // insert time (see Goodreceiveinfo::Goodreceiveinsertupdate).
        $linkedPo = ($row->tbl_porder_idtbl_porder && $row->tbl_porder_idtbl_porder != 1)
            ? 'KND/PO-' . str_pad($row->tbl_porder_idtbl_porder, 6, '0', STR_PAD_LEFT)
            : 'Direct GRN (no PO)';

        $html = $this->_renderGrnShell(array(
            'grn_no'           => 'KND/GRN-' . str_pad($row->idtbl_grn, 6, '0', STR_PAD_LEFT),
            'grndate'          => date('d M Y', strtotime($row->grndate)),
            'approve_cls'      => $approveStatusCls,
            'approve_text'     => $approveStatusText,
            'sup_name'         => $row->suppliername ? $row->suppliername : 'Supplier',
            'sup_meta'         => $supMeta,
            'batchno'          => $row->batchno ? $row->batchno : '-',
            'invoicenum'       => $row->invoicenum ? $row->invoicenum : '-',
            'dispatchnum'      => $row->dispatchnum ? $row->dispatchnum : '-',
            'linked_po'        => $linkedPo,
            'itemcount'        => $itemcount,
            'sumqty'           => $sumqty,
            'items_html'       => $tblitems,
            'total'            => $row->total,
            'remark'           => $row->remark,
        ));

        $this->load->library('pdf');
        $this->pdf->loadHtml($html);
        $this->pdf->render();
        $this->pdf->stream('GRN-' . $row->idtbl_grn . '.pdf', array('Attachment' => 0));
    }

    /**
     * A4 GRN PDF shell. Uses the same navy (#254F96) brand system and
     * logo as the Purchase Order print for consistency, but a different
     * structural layout:
     *  - a horizontal "meta strip" of labeled chips (Supplier / Batch No /
     *    Invoice No / Dispatch No / Linked PO) instead of the PO's
     *    two-column bordered panel
     *  - a single Total Received Value highlight instead of a
     *    Subtotal/Discount/Net-Total breakdown (GRNs don't carry a discount)
     *  - "Received By" / "Checked By" signature labels instead of
     *    "Authorized Signature" / "Supplier Acknowledgement"
     */
   private function _renderGrnShell($d){

    $bizPhone = '0719622717 / 0703955682';
    $bizEmail = 'Kannadiyaopticalservice@gmail.com';
    $bizAddr  = 'Kannadiya Pvt LTD, Thalawa Road, Kekirawa';

    // Logo: dompdf needs a real, reachable FILESYSTEM path (not a base_url()
    // http path) to embed an image reliably. Uses the same logo file as
    // the Purchase Order print — the artwork already contains the Sinhala
    // wordmark, so no separate text name is rendered next to it.
    $bizLogoPath = FCPATH . 'assets/images/logo.png';
    $bizLogoTag  = '';
    if (file_exists($bizLogoPath)) {
        $bizLogoTag = '<img src="' . $bizLogoPath . '" class="logo-img" alt="Kannadiya Optical Services">';
    }
    $headerBrandBlock = $bizLogoTag
        ? $bizLogoTag
        : '<div class="brand-name-fallback">KANNADIYA<br><span>OPTICAL SERVICES</span></div>';
    $footerBrandBlock = $bizLogoTag
        ? '<img src="' . $bizLogoPath . '" class="footer-logo-img" alt="logo">'
        : '<div class="footer-brand-fallback">KANNADIYA OPTICAL SERVICES</div>';

    return '
        <!doctype html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <title>Goods Received Note ' . $d['grn_no'] . '</title>
            <style>
                @page { size: A4; margin: 0; }
                * { margin:0; padding:0; box-sizing:border-box; font-family: "Helvetica", Arial, sans-serif; }
                body { color:#1c2b33; width:210mm; }

                .top-bar { width:210mm; height:7px; background:#254F96; }
                .page { width:210mm; padding:10mm 16mm 4mm 16mm; }

                /* sticky-bottom footer wrapper — same reliable table
                   technique used on the PO print (position:fixed is not
                   dependable in dompdf on single-page documents) */
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

                /* ---------------- META STRIP (GRN-specific layout) ----------------
                   Instead of the PO print two-column card panel, this uses a
                   single horizontal strip of labeled chips split by thin
                   vertical rules — fits GRN\'s extra fields (batch/invoice/
                   dispatch/linked PO) more naturally than a two-box layout. */
                .meta-strip { width:178mm; border:1px solid #dde3ee; border-radius:10px; margin-bottom:16px; border-collapse:collapse; table-layout:fixed; }
                .meta-strip td { vertical-align:top; padding:13px 14px; word-wrap:break-word; overflow-wrap:break-word; border-right:1px solid #e5e9f2; }
                .meta-strip td:last-child { border-right:none; }
                .meta-strip .lbl { font-size:8px; text-transform:uppercase; letter-spacing:.5px; color:#254F96; font-weight:800; margin-bottom:5px; }
                .meta-strip .val { font-size:11px; font-weight:700; color:#1c2b33; }

                .supplier-panel { width:178mm; border:1px solid #dde3ee; border-radius:10px; margin-bottom:20px; padding:16px 20px; table-layout:fixed; border-collapse:collapse; }
                .supplier-panel td { vertical-align:top; word-wrap:break-word; overflow-wrap:break-word; }
                .supplier-panel .info-chip { display:inline-block; background:#254F96; color:#fff; font-size:8.5px; font-weight:800; text-transform:uppercase; letter-spacing:.6px; padding:3px 9px; border-radius:4px; margin-bottom:9px; }
                .supplier-panel .val { font-size:13px; font-weight:700; color:#1c2b33; margin-bottom:4px; }
                .supplier-panel .sub { font-size:10px; color:#6b7d85; line-height:1.7; }

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

                /* ---------------- TOTAL (single value, no discount for GRN) ---------------- */
                .total-table { width:178mm; border-collapse:collapse; margin-bottom:18px; }
                .total-table td { vertical-align:middle; padding:0; }
                .total-spacer { width:98mm; }
                .total-box { width:80mm; background:#F3F6FB; border-radius:8px; padding:14px 18px; text-align:right; }
                .total-box .lbl { font-size:11px; font-weight:700; color:#254F96; text-transform:uppercase; letter-spacing:.5px; margin-bottom:4px; }
                .total-box .val { font-size:22px; font-weight:800; color:#254F96; }

                .remark-box { background:#F3F6FB; border-left:4px solid #254F96; border-radius:8px; padding:12px 16px; margin-bottom:18px; font-size:11px; color:#475a61; width:178mm; }
                .remark-box .lbl { font-size:9px; text-transform:uppercase; letter-spacing:.6px; color:#254F96; font-weight:800; margin-bottom:4px; }

                /* ---------------- FOOTER ---------------- */
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
                        <div class="inv-title-badge">GOODS RECEIVED NOTE</div>
                        <table class="inv-meta-table">
                            <tr><td class="lbl">GRN No.</td><td class="val">' . $d['grn_no'] . '</td></tr>
                            <tr><td class="lbl">GRN Date</td><td class="val">' . $d['grndate'] . '</td></tr>
                            <tr><td class="lbl">Linked PO</td><td class="val">' . htmlspecialchars($d['linked_po']) . '</td></tr>
                        </table>
                        <div class="status-pill ' . $d['approve_cls'] . '">' . $d['approve_text'] . '</div>
                    </td>
                </tr>
            </table>

            <div class="header-divider"></div>

            <div class="biz-strip">' . htmlspecialchars($bizAddr) . ' &middot; ' . htmlspecialchars($bizPhone) . ' &middot; ' . htmlspecialchars($bizEmail) . '</div>

            <table class="meta-strip">
                <tr>
                    <td style="width:44.5mm;">
                        <div class="lbl">Batch No</div>
                        <div class="val">' . htmlspecialchars($d['batchno']) . '</div>
                    </td>
                    <td style="width:44.5mm;">
                        <div class="lbl">Invoice No</div>
                        <div class="val">' . htmlspecialchars($d['invoicenum']) . '</div>
                    </td>
                    <td style="width:44.5mm;">
                        <div class="lbl">Dispatch No</div>
                        <div class="val">' . htmlspecialchars($d['dispatchnum']) . '</div>
                    </td>
                    <td style="width:44.5mm;">
                        <div class="lbl">Items / Qty</div>
                        <div class="val">' . $d['itemcount'] . ' items &middot; ' . $d['sumqty'] . ' qty</div>
                    </td>
                </tr>
            </table>

            <table class="supplier-panel">
                <tr>
                    <td>
                        <div class="info-chip">Supplier</div>
                        <div class="val">' . htmlspecialchars($d['sup_name']) . '</div>
                        <div class="sub">' . $d['sup_meta'] . '</div>
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

            <table class="total-table">
                <tr>
                    <td class="total-spacer"></td>
                    <td class="total-box">
                        <div class="lbl">Total Received Value</div>
                        <div class="val">Rs. ' . number_format($d['total'], 2) . '</div>
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
                <div class="footer-accent"></div>
                <table class="sign-table">
                    <tr>
                        <td class="sign-col">
                            <div class="sign-line"></div>
                            <div class="sign-label">Received By</div>
                        </td>
                        <td class="sign-col">
                            <div class="sign-line"></div>
                            <div class="sign-label">Checked By</div>
                        </td>
                    </tr>
                </table>

                <table class="footer-contact">
                    <tr>
                        <td class="footer-logo-cell">' . $footerBrandBlock . '</td>
                        <td class="footer-text-cell">
                            ' . htmlspecialchars($bizAddr) . '<br>
                            Tel: ' . htmlspecialchars($bizPhone) . ' &middot; ' . htmlspecialchars($bizEmail) . '
                        </td>
                        <td class="footer-thanks">Goods Received<br>&amp; Verified</td>
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