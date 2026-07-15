<?php
class Purchaseorderprintinfo extends CI_Model{

    public function Getporderprintpdf($x){

        $recordID = $x;

        // ---------------- HEADER QUERY ----------------
        $sql = "SELECT tbl_porder.idtbl_porder, tbl_porder.orderdate, tbl_porder.duedate,
                        tbl_porder.subtotal, tbl_porder.discount, tbl_porder.discountamount,
                        tbl_porder.nettotal, tbl_porder.confirmstatus, tbl_porder.grnconfirm,
                        tbl_porder.remark,
                        tbl_supplier.suppliername, tbl_supplier.suppliercode,
                        tbl_supplier.primarycontactno, tbl_supplier.secondarycontactno,
                        tbl_supplier.address, tbl_supplier.email
                FROM tbl_porder
                LEFT JOIN tbl_supplier 
                    ON tbl_supplier.idtbl_supplier = tbl_porder.tbl_supplier_idtbl_supplier
                WHERE tbl_porder.status=1 
                AND tbl_porder.idtbl_porder=?";

        $porderrespond = $this->db->query($sql, [$recordID]);

        if ($porderrespond->num_rows() == 0) {
            echo 'Purchase Order not found.';
            return;
        }

        $row = $porderrespond->row(0);

        // ---------------- DETAIL QUERY ----------------
        $sqldetail = "SELECT tbl_porder_detail.qty, tbl_porder_detail.unitprice,
                            tbl_porder_detail.discountamount,
                            tbl_porder_detail.comment,
                            tbl_material_info.materialinfocode,
                            tbl_material_info.materialname
                    FROM tbl_porder_detail
                    LEFT JOIN tbl_material_info 
                        ON tbl_material_info.idtbl_material_info = tbl_porder_detail.tbl_material_info_idtbl_material_info
                    WHERE tbl_porder_detail.tbl_porder_idtbl_porder=?
                    AND tbl_porder_detail.status=1";

        $detailrespond = $this->db->query($sqldetail, [$recordID]);

        // ---------------- SUMMARY ----------------
        $itemcount = 0;
        $sumqty = 0;

        // ---------------- FAST HTML BUILD (IMPORTANT OPTIMIZATION) ----------------
        ob_start();

        $rowIdx = 1;
        foreach ($detailrespond->result() as $d) {

            $linetotal = ($d->qty * $d->unitprice) - (float)$d->discountamount;
            $itemcount++;
            $sumqty += $d->qty;
            ?>
            <tr>
                <td class="idx"><?= $rowIdx ?></td>
                <td>
                    <div class="pname"><?= htmlspecialchars($d->materialname) ?></div>
                    <div class="pcode"><?= htmlspecialchars($d->materialinfocode) ?></div>
                    <?php if ($d->comment) { ?>
                        <div class="pcomment"><?= htmlspecialchars($d->comment) ?></div>
                    <?php } ?>
                </td>
                <td class="num"><?= $d->qty ?></td>
                <td class="num"><?= number_format($d->unitprice, 2) ?></td>
                <td class="num"><?= number_format($linetotal, 2) ?></td>
            </tr>
            <?php
            $rowIdx++;
        }

        $tblitems = ob_get_clean();

        // ---------------- STATUS ----------------
        $confirmStatusText = ($row->confirmstatus == 1)
            ? '&#10003; CONFIRMED'
            : '&#8987; PENDING CONFIRMATION';

        $confirmStatusCls = ($row->confirmstatus == 1) ? '' : 'credit';

        // ---------------- CONTACT ----------------
        $contactNumbers = [];
        if (!empty($row->primarycontactno)) $contactNumbers[] = $row->primarycontactno;
        if (!empty($row->secondarycontactno)) $contactNumbers[] = $row->secondarycontactno;

        $contactLine = !empty($contactNumbers) ? implode(' / ', $contactNumbers) : '-';

        $supMeta = htmlspecialchars($contactLine);
        if ($row->suppliercode) $supMeta .= ' · ' . htmlspecialchars($row->suppliercode);
        if ($row->address) $supMeta .= '<br>' . htmlspecialchars($row->address);
        if ($row->email) $supMeta .= '<br>' . htmlspecialchars($row->email);

        // ---------------- DATA ----------------
        $data = [
            'po_no'       => 'KND/PO-' . str_pad($row->idtbl_porder, 6, '0', STR_PAD_LEFT),
            'orderdate'   => date('d M Y', strtotime($row->orderdate)),
            'duedate'     => date('d M Y', strtotime($row->duedate)),
            'confirm_cls' => $confirmStatusCls,
            'confirm_text'=> $confirmStatusText,
            'sup_name'    => $row->suppliername ?: 'Supplier',
            'sup_meta'    => $supMeta,
            'itemcount'   => $itemcount,
            'sumqty'      => $sumqty,
            'items_html'  => $tblitems,
            'subtotal'    => $row->subtotal,
            'discount'    => $row->discountamount,
            'nettotal'    => $row->nettotal,
            'remark'      => $row->remark,
        ];

        // ---------------- LOAD VIEW HTML (FASTER THAN HUGE STRING) ----------------
        $html = $this->_renderPorderShell($data);

        // ---------------- PDF GENERATION ----------------
        $this->load->library('pdf');

        $options = new Dompdf\Options();
        $options->set('isRemoteEnabled', false);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultFont', 'Helvetica');

        $this->pdf->setOptions($options);

        $this->pdf->loadHtml($html);
        $this->pdf->render();
        $this->pdf->stream('PO-' . $row->idtbl_porder . '.pdf', ['Attachment' => 0]);
    }

    // ---------------- HTML TEMPLATE ----------------
    private function _renderPorderShell($d){

        $bizPhone = '0719622717 / 0703955682';
        $bizEmail = 'Kannadiyaopticalservice@gmail.com';
        $bizAddr  = 'Kannadiya Pvt LTD, Thalawa Road, Kekirawa';

        $logoPath = FCPATH . 'images/clientlogo.png';

        $headerBrandBlock = '
        <img src="'.$logoPath.'" style="height:70px;width:auto;display:block;">';

        $footerBrandBlock = '
        <img src="'.$logoPath.'" style="height:40px;width:auto;display:block;">';

        return '
        <!doctype html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>PO '.$d['po_no'].'</title>
            <style>
                @page { size:A4; margin:0; }
                body { font-family:Helvetica; font-size:12px; }

                table { width:100%; border-collapse:collapse; }
                .items td, .items th { border-bottom:1px solid #eee; padding:6px; }
                .num { text-align:right; }
            </style>
        </head>
        <body>

        <div style="padding:20px;">

            <table>
                <tr>
                    <td>'.$headerBrandBlock.'</td>
                    <td style="text-align:right;">
                        <h3>PURCHASE ORDER</h3>
                        <div>'.$d['po_no'].'</div>
                        <div>'.$d['orderdate'].'</div>
                    </td>
                </tr>
            </table>

            <hr>

            <table class="items">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Item</th>
                        <th class="num">Qty</th>
                        <th class="num">Price</th>
                        <th class="num">Total</th>
                    </tr>
                </thead>
                <tbody>
                    '.$d['items_html'].'
                </tbody>
            </table>

            <h4>Total: Rs. '.number_format($d['nettotal'],2).'</h4>

        </div>

        </body>
        </html>';
    }
}