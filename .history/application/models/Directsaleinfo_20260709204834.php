<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Directsaleinfo extends CI_Model {

    /* =========================================================
     * Reference data
     * ========================================================= */

    public function Getmaterial(){
        $this->db->select('`idtbl_material_category`, `categoryname`');
        $this->db->from('tbl_material_category');
        $this->db->where('status', 1);
        $this->db->order_by('categoryname', 'ASC');

        return $this->db->get();
    }

    public function Getlocation(){
        $this->db->select('`idtbl_location`, `location`');
        $this->db->from('tbl_location');
        $this->db->where('tbl_location.status', 1);

        return $this->db->get();
    }

    public function Getcustomer(){
        $this->db->select('`idtbl_customer`, `name`');
        $this->db->from('tbl_customer');
        $this->db->where('status', 1);

        return $this->db->get();
    }

    /* =========================================================
     * Product grid / search / barcode / category
     * ========================================================= */

    public function Getproductlist() {

        $categoryID  = $this->input->post('categoryID');
        $searchTerm  = $this->input->post('searchTerm');
        $stockFilter = $this->input->post('stockFilter');

        $this->db->select("
            `tbl_material_info`.`idtbl_material_info`,
            `tbl_material_info`.`materialinfocode`,
            `tbl_material_info`.`materialname`,
            `tbl_material_category`.`idtbl_material_category`,
            `tbl_material_category`.`categoryname`
        ");
        $this->db->from('tbl_material_info');
        $this->db->join('tbl_material_category', 'tbl_material_category.idtbl_material_category = tbl_material_info.tbl_material_category_idtbl_material_category', 'left');
        $this->db->where('tbl_material_info.status', 1);

        if (!empty($categoryID)) {
            $this->db->where('tbl_material_category.idtbl_material_category', $categoryID);
        }
        if (!empty($searchTerm)) {
            $this->db->group_start();
            $this->db->like('tbl_material_info.materialname', $searchTerm);
            $this->db->or_like('tbl_material_info.materialinfocode', $searchTerm);
            $this->db->group_end();
        }

        $this->db->order_by('tbl_material_info.materialname', 'ASC');
        $respond = $this->db->get();

        $list = array();

        foreach ($respond->result() as $row) {

            $productID = $row->idtbl_material_info;

            /*
             * FIX: SUM qty across ALL batches so a product with one empty
             * batch and one non-empty batch is never shown as out of stock.
             * saleprice and batchno are taken from the FIRST batch that
             * still has qty > 0 (used only as the display price on the
             * product card — the batch picker lets the cashier choose later).
             */
            $sqlstockcheck = "
                SELECT
                    SUM(`qty`) AS sumqty,
                    MIN(CASE WHEN `qty` > 0 THEN `saleprice` END) AS saleprice,
                    MIN(CASE WHEN `qty` > 0 THEN `batchno`   END) AS batchno
                FROM `tbl_stock`
                WHERE `tbl_material_info_idtbl_material_info` = ?
                  AND `status` = 1
            ";
            $stockRespond = $this->db->query($sqlstockcheck, array($productID));

            $stockcount = 0;
            $saleprice  = 0;
            $batchno    = '';

            if ($stockRespond->num_rows() > 0) {
                $stockcount = (float) $stockRespond->row(0)->sumqty;
                $saleprice  = (float) $stockRespond->row(0)->saleprice;
                $batchno    = $stockRespond->row(0)->batchno;
            }

            // Apply stock filter against the true total qty across all batches
            if ($stockFilter === 'in'  && !($stockcount > 5))               continue;
            if ($stockFilter === 'low' && !($stockcount > 0 && $stockcount <= 5)) continue;
            if ($stockFilter === 'out' && !($stockcount <= 0))              continue;

            $list[] = array(
                'id'           => $productID,
                'name'         => $row->materialname,
                'code'         => $row->materialinfocode,
                'categoryID'   => $row->idtbl_material_category,
                'categoryName' => $row->categoryname,
                'price'        => $saleprice,
                'stock'        => $stockcount,
                'batchno'      => $batchno,
            );
        }

        echo json_encode($list);
    }

    public function Getcategorylist(){
        $this->db->select('`idtbl_material_category`, `categoryname`');
        $this->db->from('tbl_material_category');
        $this->db->where('status', 1);
        $this->db->order_by('categoryname', 'ASC');
        $respond = $this->db->get();

        echo json_encode($respond->result());
    }

    public function Getproductlistaccobarcode(){

        $barcode = $this->input->post('barcode');

        $this->db->select("
            `tbl_material_info`.`idtbl_material_info`,
            `tbl_material_info`.`materialinfocode`,
            `tbl_material_info`.`materialname`,
            `tbl_material_info`.`barcode`
        ");
        $this->db->from('tbl_material_info');
        $this->db->where('tbl_material_info.barcode', $barcode);
        $this->db->where('tbl_material_info.status', 1);
        $respond = $this->db->get();

        $obj = new stdClass();

        if ($respond->num_rows() == 0) {
            $obj->found = false;
            echo json_encode($obj);
            return;
        }

        $productID = $respond->row(0)->idtbl_material_info;

        /*
         * Same fix as Getproductlist: SUM across all batches so a product
         * with one zero-qty batch and one non-zero batch is not blocked.
         */
        $sqlstockcheck = "
            SELECT
                SUM(`qty`) AS sumqty,
                MIN(CASE WHEN `qty` > 0 THEN `saleprice` END) AS saleprice,
                MIN(CASE WHEN `qty` > 0 THEN `batchno`   END) AS batchno
            FROM `tbl_stock`
            WHERE `tbl_material_info_idtbl_material_info` = ?
              AND `status` = 1
        ";
        $stockRespond = $this->db->query($sqlstockcheck, array($productID));

        $obj->found       = true;
        $obj->id          = $productID;
        $obj->productcode = $respond->row(0)->materialinfocode;
        $obj->productname = $respond->row(0)->materialname;
        $obj->barcode     = $respond->row(0)->barcode;
        $obj->price       = !empty($stockRespond->row(0)->saleprice) ? (float)$stockRespond->row(0)->saleprice : 0;
        $obj->stock       = !empty($stockRespond->row(0)->sumqty)    ? (float)$stockRespond->row(0)->sumqty    : 0;

        echo json_encode($obj);
    }

    /**
     * Returns all batches with qty > 0 for a given product.
     * The batch picker modal uses this so the cashier can choose
     * which batch to sell from and sees the correct per-batch price.
     */
    public function Getproductbatches(){
        $productID = $this->input->post('productID');

        $sql = "
            SELECT `idtbl_stock`, `batchno`, `qty`, `saleprice`, `costprice`, `insertdatetime`
            FROM `tbl_stock`
            WHERE `tbl_material_info_idtbl_material_info` = ?
              AND `status` = 1
              AND `qty` > 0
            ORDER BY `idtbl_stock` ASC
        ";
        $result = $this->db->query($sql, array($productID));

        $batches = array();
        foreach ($result->result() as $row) {
            $batches[] = array(
                'stockID'   => $row->idtbl_stock,
                'batchno'   => $row->batchno,
                'qty'       => (float)$row->qty,
                'saleprice' => (float)$row->saleprice,
                'costprice' => (float)$row->costprice,
                'date'      => $row->insertdatetime,
            );
        }
        echo json_encode($batches);
    }

    public function Getproductavalaibleqty(){

        $product      = $this->input->post('product');
        $inserted_qty = $this->input->post('qty');

        $this->db->select_sum('qty');
        $this->db->from('tbl_stock');
        $this->db->where('tbl_material_info_idtbl_material_info', $product);
        $this->db->where('status', 1);
        $respond = $this->db->get();

        $availableqty = 0;

        if ($respond->num_rows() > 0) {
            $result = $respond->row();
            $availableqty = $result->qty ? $result->qty : 0;
        }

        $obj = new stdClass();
        $obj->checkqty    = ($inserted_qty > $availableqty) ? 1 : 0;
        $obj->availableqty = $availableqty;
        echo json_encode($obj);
    }

    /* =========================================================
     * Customer search / quick-add
     * ========================================================= */

    public function Getcustomersearch(){
        $searchTerm = $this->input->post('searchTerm');

        $this->db->select('`idtbl_customer`, `customercode`, `name`, `contact`');
        $this->db->from('tbl_customer');
        $this->db->where('status', 1);
        $this->db->where('idtbl_customer !=', 1);

        if (!empty($searchTerm)) {
            $this->db->group_start();
            $this->db->like('name', $searchTerm);
            $this->db->or_like('contact', $searchTerm);
            $this->db->or_like('customercode', $searchTerm);
            $this->db->group_end();
        } else {
            $this->db->order_by('idtbl_customer', 'DESC');
            $this->db->limit(15);
        }

        $respond = $this->db->get();

        $data = array();
        foreach ($respond->result() as $row) {
            $data[] = array(
                'id'     => $row->idtbl_customer,
                'name'   => $row->name,
                'mobile' => $row->contact,
                'code'   => $row->customercode,
            );
        }

        echo json_encode($data);
    }

    public function Getcustomerlist(){
        $searchTerm = $this->input->post('searchTerm');

        if (empty($searchTerm)) {
            $sql = "SELECT `idtbl_customer`, `name` FROM `tbl_customer` WHERE `status`=? AND `idtbl_customer` != 1 LIMIT 5";
            $respond = $this->db->query($sql, array(1));
        } else {
            $sql = "SELECT `idtbl_customer`, `name` FROM `tbl_customer` WHERE `status`=? AND `idtbl_customer` != 1 AND `name` LIKE ?";
            $respond = $this->db->query($sql, array(1, $searchTerm . '%'));
        }

        $data = array();
        foreach ($respond->result() as $row) {
            $data[] = array("id" => $row->idtbl_customer, "text" => $row->name);
        }

        echo json_encode($data);
    }

    public function Quickaddcustomer(){

        $name    = trim($this->input->post('name'));
        $contact = trim($this->input->post('contact'));
        $userID  = isset($_SESSION['userid']) ? $_SESSION['userid'] : null;

        $obj = new stdClass();

        if ($name === '' || $contact === '') {
            $obj->success = false;
            $obj->message = 'Name and contact number are required.';
            echo json_encode($obj);
            return;
        }

        $sqlLastCode = "SELECT `idtbl_customer` FROM `tbl_customer` ORDER BY `idtbl_customer` DESC LIMIT 1";
        $lastRespond = $this->db->query($sqlLastCode);
        $nextNumber  = ($lastRespond->num_rows() > 0) ? ($lastRespond->row(0)->idtbl_customer + 1) : 1;
        $customercode = 'CUS-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        $data = array(
            'customercode'        => $customercode,
            'customertype'        => 1,
            'name'                => $name,
            'contact'             => $contact,
            'status'              => 1,
            'insertdatetime'      => date('Y-m-d H:i:s'),
            'tbl_user_idtbl_user' => $userID,
        );

        $this->db->insert('tbl_customer', $data);
        $newID = $this->db->insert_id();

        if ($newID) {
            $obj->success = true;
            $obj->id      = $newID;
            $obj->name    = $name;
            $obj->mobile  = $contact;
            $obj->code    = $customercode;
        } else {
            $obj->success = false;
            $obj->message = 'Could not save customer. Please try again.';
        }

        echo json_encode($obj);
    }

    /* =========================================================
     * Sale insert / update
     * =========================================================
     * billtype: 1 = Cash, 2 = Card, 3 = Credit
     *
     * Payment table columns (col_1 … col_6):
     *   col_1 = method (Cash / Card)
     *   col_2 = bank / card type
     *   col_3 = branch
     *   col_4 = chequeno / last 4 digits
     *   col_5 = chequedate
     *   col_6 = amount
     *
     * warrantystatus: '1' if Lifetime Warranty checkbox ticked, else '0'.
     */
    public function Directsaleinsertupdate()
    {
        $this->db->trans_begin();

        $userID    = isset($_SESSION['userid']) ? $_SESSION['userid'] : null;
        $companyid = isset($_SESSION['companyid']) ? $_SESSION['companyid'] : null;
        $branchid  = isset($_SESSION['branchid']) ? $_SESSION['branchid'] : null;

        $tableData       = $this->input->post('tableData');
        $tableDataPay    = $this->input->post('tableDataPay');
        $total           = $this->input->post('total');
        $distotal        = $this->input->post('distotal');
        $nettotal        = $this->input->post('nettotal');
        $billtype        = $this->input->post('billtype');
        $paytotal        = $this->input->post('paytotal');
        $customer        = $this->input->post('customer');
        $priceeditstatus = $this->input->post('priceeditstatus');
        $billapproveuser = $this->input->post('billapproveuser');
        $warrantystatus  = $this->input->post('warrantystatus');
        $ordertype       = $this->input->post('ordertype'); // 1 normal, 2 advance order
        $duedate         = $this->input->post('duedate');    // 'YYYY-MM-DD' or null

        if (empty($ordertype)) $ordertype = 1;

        // Advance Order requires a saved customer + a due date, just like Credit
        if ($billtype == 4 && ($customer == 1 || empty($duedate))) {
            $obj = new stdClass();
            $obj->actiontype = '0';
            $obj->invoiceid  = '0';
            $obj->billtype   = $billtype;
            $obj->action     = json_encode($this->_buildNotify(
                'fas fa-exclamation-triangle',
                'Advance orders require a saved customer and a due date.',
                'danger'
            ));
            echo json_encode($obj);
            return;
        }

        if (empty($customer)) {
            $customer = 1;
        }

        if ($billtype == 3 && $customer == 1) {
            $obj = new stdClass();
            $obj->actiontype = '0';
            $obj->invoiceid  = '0';
            $obj->billtype   = $billtype;
            $obj->action     = json_encode($this->_buildNotify(
                'fas fa-exclamation-triangle',
                'Credit sales require a saved customer.',
                'danger'
            ));
            echo json_encode($obj);
            return;
        }

        $insertdatetime = date('Y-m-d H:i:s');
        $invdate        = date('Y-m-d H:i:s');
        $paycomplete = (in_array($billtype, [3, 4])) ? (($paytotal >= $nettotal) ? 1 : 0) : (($paytotal >= $nettotal) ? 1 : 0);
        // (kept identical logic — advance orders are simply allowed to be < nettotal)
        // =========================
        // PAYMENT LOGIC FIX
        // =========================
        $paymentgiven = 0;
        $changegiven  = 0;

        if ($billtype == 1) {
            // CASH
            $paymentgiven = $paytotal;
            $changegiven  = max(0, $paytotal - $nettotal);
        }

        if ($billtype == 2) {
            // CARD (IMPORTANT FIX)
            $paymentgiven = $nettotal;
            $changegiven  = 0;
        }

$data = array(
            'invdate'                                 => $invdate,
            'grosstotal'                              => $total,
            'discount'                                => $distotal,
            'nettotal'                                => $nettotal,
            'invtype'                                 => ($billtype == 3 || $billtype == 4) ? 2 : 1,
            'ordertype'                               => ($billtype == 4) ? 2 : 1,
            'duedate'                                 => ($billtype == 4) ? $duedate : null,
            'paycomplete'                             => $paycomplete,
            'paymentgiven'                            => $paymentgiven,
            'changegiven'                             => $changegiven,
            'warrantystatus'                          => ($warrantystatus == '1') ? 1 : 0,
            'status'                                  => 1,
            'insertdatetime'                          => $insertdatetime,
            'tbl_user_idtbl_user'                     => $userID,
            'tbl_company_idtbl_company'               => $companyid,
            'tbl_company_branch_idtbl_company_branch' => $branchid,
            'tbl_customer_idtbl_customer'             => $customer,
        );

        $this->db->insert('tbl_invoice', $data);
        $invoiceID = $this->db->insert_id();

        // =========================
        // INVOICE DETAILS
        // =========================
        foreach ($tableData as $rowtabledata) {

            $data = array(
                'qty'                                   => $rowtabledata['col_2'],
                'saleprice'                             => $rowtabledata['col_3'],
                'discount'                              => $rowtabledata['col_4'],
                'total'                                 => $rowtabledata['col_5'],
                'status'                                => 1,
                'insertdatetime'                        => $insertdatetime,
                'tbl_invoice_idtbl_invoice'             => $invoiceID,
                'tbl_material_info_idtbl_material_info' => $rowtabledata['col_6'],
                'batchno'                               => isset($rowtabledata['col_8']) ? $rowtabledata['col_8'] : ''
            );

            $this->db->insert('tbl_invoice_detail', $data);

            // STOCK REDUCTION (FIFO)
            $qtyToReduce = $rowtabledata['col_2'];

            $this->db->select('idtbl_stock, qty');
            $this->db->from('tbl_stock');
            $this->db->where('tbl_material_info_idtbl_material_info', $rowtabledata['col_6']);
            $this->db->where('status', 1);
            $this->db->order_by('idtbl_stock', 'ASC');
            $stockRows = $this->db->get()->result_array();

            foreach ($stockRows as $stockrow) {

                $currentQty = $stockrow['qty'];

                if ($currentQty >= $qtyToReduce) {
                    $finalqty = $currentQty - $qtyToReduce;

                    $this->db->where('idtbl_stock', $stockrow['idtbl_stock']);
                    $this->db->update('tbl_stock', array('qty' => $finalqty));
                    break;
                } else {
                    $qtyToReduce -= $currentQty;

                    $this->db->where('idtbl_stock', $stockrow['idtbl_stock']);
                    $this->db->update('tbl_stock', array('qty' => 0));
                }
            }
        }

        // =========================
        // PAYMENT INSERT
        // =========================
        if ($billtype == 1 || $billtype == 2) {

            $paymentData = array(
                'paydate'                                 => $invdate,
                'nettotal'                                => $nettotal,
                'balance'                                 => 0,
                'status'                                  => 1,
                'insertdatetime'                          => $insertdatetime,
                'tbl_user_idtbl_user'                     => $userID,
                'tbl_company_idtbl_company'               => $companyid,
                'tbl_company_branch_idtbl_company_branch' => $branchid,
            );

            $this->db->insert('tbl_invoice_payment', $paymentData);
            $invoicepayID = $this->db->insert_id();

            $this->db->insert('tbl_invoice_payment_has_tbl_invoice', array(
                'tbl_invoice_payment_idtbl_invoice_payment' => $invoicepayID,
                'tbl_invoice_idtbl_invoice'                 => $invoiceID,
            ));

            if (!empty($tableDataPay) && is_array($tableDataPay)) {

                foreach ($tableDataPay as $rowPay) {

                    $paymentDetail = array(
                        'method' => $billtype,
                        'amount' => $nettotal,
                        'bank'   => isset($rowPay['col_2']) ? $rowPay['col_2'] : '',
                        'branch' => isset($rowPay['col_3']) ? $rowPay['col_3'] : '',
                        'chequeno'   => isset($rowPay['col_4']) ? $rowPay['col_4'] : '',
                        'chequedate' => isset($rowPay['col_5']) ? $rowPay['col_5'] : '',
                        'status'     => 1,
                        'insertdatetime' => $insertdatetime,
                        'tbl_user_idtbl_user' => $userID,
                        'tbl_invoice_payment_idtbl_invoice_payment' => $invoicepayID,
                    );

                    $this->db->insert('tbl_invoice_payment_detail', $paymentDetail);
                }
            }
        }

        // =========================
        // TRANSACTION FINALIZE
        // =========================
        $this->db->trans_complete();

        $obj = new stdClass();

        if ($this->db->trans_status() === TRUE) {
            $this->db->trans_commit();

            $obj->action     = json_encode($this->_buildNotify('fas fa-save', 'Sale completed successfully', 'success'));
            $obj->actiontype = '1';
            $obj->invoiceid  = $invoiceID;
            $obj->billtype   = $billtype;
        } else {
            $this->db->trans_rollback();

            $obj->action     = json_encode($this->_buildNotify('fas fa-exclamation-triangle', 'Could not complete sale. Please try again.', 'danger'));
            $obj->actiontype = '0';
            $obj->invoiceid  = '0';
            $obj->billtype   = $billtype;
        }

        echo json_encode($obj);
    }

    private function _buildNotify($icon, $message, $type){
        $o = new stdClass();
        $o->icon    = $icon;
        $o->title   = '';
        $o->message = $message;
        $o->url     = '';
        $o->target  = '_blank';
        $o->type    = $type;
        return $o;
    }
}