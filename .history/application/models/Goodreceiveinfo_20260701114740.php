<?php
class Goodreceiveinfo extends CI_Model{

    public function Getsupplier(){
        $this->db->select('`idtbl_supplier`, `suppliername`');
        $this->db->from('tbl_supplier');
        $this->db->where('status', 1);

        return $respond=$this->db->get();
    }
    public function Getporder(){
        $this->db->select('`idtbl_porder`');
        $this->db->from('tbl_porder');
        $this->db->where('status', 1);
        $this->db->where('confirmstatus', 1);
        $this->db->where('grnconfirm', 0);

        return $respond=$this->db->get();
    }
    public function Getproductaccosupplier(){
        $recordID=$this->input->post('recordID');

        $sql="SELECT `tbl_material_info`.`idtbl_material_info`, `tbl_material_info`.`materialinfocode`, `tbl_material_info`.`materialname` FROM `tbl_material_info` WHERE `tbl_material_info`.`status`=? AND `tbl_material_info`.`tbl_material_category_idtbl_material_category` IN (SELECT `tbl_material_category_idtbl_material_category` FROM `tbl_supplier_has_tbl_material_category` WHERE `tbl_supplier_idtbl_supplier`=?)";
        $respond=$this->db->query($sql, array(1, $recordID));

        echo json_encode($respond->result());
    }
    public function Getgoodreceiveid(){
        $recordID=$this->input->post('recordID');

        $sql="SELECT `idtbl_grn` FROM `tbl_grn` WHERE `idtbl_grn`=? AND `status`=1";
        $respond=$this->db->query($sql, array($recordID));

        echo json_encode($respond->result());
    }
    public function Goodreceiveinsertupdate(){
        $this->db->trans_begin();

        // Get the user ID from the session
        $userID = $_SESSION['userid'];

        // Retrieve the form data
        $tableData = $this->input->post('tableData');
        $grndate = $this->input->post('grndate');
        $total = $this->input->post('total');
        $remark = $this->input->post('remark');
        $supplier = $this->input->post('supplier');
        $batchno = $this->input->post('batchno');
        $invoice = $this->input->post('invoice');
        $dispatch = $this->input->post('dispatch');
        if(!empty($this->input->post('porder'))){$porder=$this->input->post('porder');$grntype=1;}
        else{$porder=1;$grntype=2;}

        $updatedatetime = date('Y-m-d H:i:s');

        // Prepare the data for insertion
        $data = array(
            'batchno' => $batchno,
            'grndate' => $grndate,
            'total' => $total,
            'invoicenum' => $invoice,
            'dispatchnum' => $dispatch,
            'remark' => $remark,
            'approvestatus' => '0',
            'status' => '1',
            'insertdatetime' => $updatedatetime,
            'tbl_user_idtbl_user' => $userID,
            'tbl_supplier_idtbl_supplier' => $supplier,
            'tbl_porder_idtbl_porder' => $porder
        );

        // Insert the data into tbl_grn table
        $this->db->insert('tbl_grn', $data);

        $grnID = $this->db->insert_id();

        // Insert the details into tbl_grndetail table
        foreach ($tableData as $rowtabledata) {
            $comment = $rowtabledata['col_2'];
            $materialID = $rowtabledata['col_3'];
            $unitprice = $rowtabledata['col_4'];
            $saleprice = $rowtabledata['col_6'];
            $qty = $rowtabledata['col_8'];
            $nettotal = $rowtabledata['col_9'];

            $dataone = array(
                'date' => $grndate,
                'qty' => $qty,
                'unitprice'=> $unitprice, 
                'saleprice'=> $saleprice, 
                'total'=> $nettotal, 
                'comment'=> $comment,  
                'status'=> '1', 
                'insertdatetime'=> $updatedatetime, 
                'tbl_grn_idtbl_grn'=> $grnID, 
                'tbl_material_info_idtbl_material_info'=> $materialID
            );

            $this->db->insert('tbl_grndetail', $dataone);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE) {
            $this->db->trans_commit();
            
            $actionObj=new stdClass();
            $actionObj->icon='fas fa-save';
            $actionObj->title='';
            $actionObj->message='Record Added Successfully';
            $actionObj->url='';
            $actionObj->target='_blank';
            $actionObj->type='success';

            $actionJSON=json_encode($actionObj);

            $obj=new stdClass();
            $obj->status=1;          
            $obj->action=$actionJSON;  
            
            echo json_encode($obj);
        } else {
            $this->db->trans_rollback();

            $actionObj=new stdClass();
            $actionObj->icon='fas fa-exclamation-triangle';
            $actionObj->title='';
            $actionObj->message='Record Error';
            $actionObj->url='';
            $actionObj->target='_blank';
            $actionObj->type='danger';

            $actionJSON=json_encode($actionObj);

            $obj=new stdClass();
            $obj->status=0;          
            $obj->action=$actionJSON;  
            
            echo json_encode($obj);
        }
    }
    public function Goodreceiveview(){

        $recordID = $this->input->post('recordID');

        // ---------------- HEADER DATA ----------------
        $sql = "SELECT u.*, ua.suppliername, ua.primarycontactno, ua.secondarycontactno, ua.address, ua.email
                FROM tbl_grn AS u
                LEFT JOIN tbl_supplier AS ua 
                    ON ua.idtbl_supplier = u.tbl_supplier_idtbl_supplier
                WHERE u.status=? AND u.idtbl_grn=?";

        $respond = $this->db->query($sql, [1, $recordID]);

        if ($respond->num_rows() == 0) {
            echo '<div class="alert alert-danger">Record not found</div>';
            return;
        }

        $grn = $respond->row(0);

        // ---------------- DETAIL DATA ----------------
        $this->db->select('d.*, m.materialinfocode, m.materialname');
        $this->db->from('tbl_grndetail d');
        $this->db->join('tbl_material_info m', 'm.idtbl_material_info = d.tbl_material_info_idtbl_material_info', 'left');
        $this->db->where('d.tbl_grn_idtbl_grn', $recordID);
        $this->db->where('d.status', 1);

        $items = $this->db->get()->result();

        // ---------------- SUPPLIER CONTACT ----------------
        $contact = trim($grn->primarycontactno . ' / ' . $grn->secondarycontactno, ' / ');

        // ---------------- HTML START ----------------
        $html = '

        <style>
            .grn-card {
                padding:15px;
                background:#fff;
                border-radius:8px;
                border:1px solid #e5e5e5;
            }

            .grn-header {
                display:flex;
                justify-content:space-between;
                margin-bottom:10px;
            }

            .supplier-box {
                text-align:right;
                font-size:13px;
                color:#333;
                line-height:1.6;
            }

            .grn-title {
                font-size:16px;
                font-weight:600;
                color:#254F96;
            }

            .meta-box {
                margin-top:10px;
                font-size:13px;
                color:#555;
            }

            .table-grn {
                width:100%;
                border-collapse:collapse;
                margin-top:10px;
            }

            .table-grn th {
                background:#254F96;
                color:#fff;
                padding:8px;
                font-size:13px;
            }

            .table-grn td {
                padding:8px;
                border-bottom:1px solid #000;
                font-size:13px;
            }

            .text-right { text-align:right; }
            .text-center { text-align:center; }

            .grn-total {
                text-align:right;
                font-size:20px;
                font-weight:600;
                margin-top:15px;
                color:#000;
            }

            .badge-info {
                display:inline-block;
                background:#f1f5ff;
                color:#000;
                padding:4px 8px;
                border-radius:5px;
                margin-right:5px;
                font-size:12px;
            }
        </style>

        <div class="grn-card">

            <div class="grn-header">
                <div>
                    <div class="grn-title">GOODS RECEIVED NOTE</div>
                    <div class="meta-box">
                        <span class="badge-info">Invoice: ' . $grn->invoicenum . '</span>
                        <span class="badge-info">Dispatch: ' . $grn->dispatchnum . '</span>
                        <span class="badge-info">Batch: ' . $grn->batchno . '</span>
                    </div>
                </div>

                <div class="supplier-box">
                    <b>' . $grn->suppliername . '</b><br>
                    ' . $contact . '<br>
                    ' . $grn->address . '<br>
                    ' . $grn->email . '
                </div>
            </div>

            <table class="table-grn">
                <thead>
                    <tr>
                        <th>Material</th>
                        <th class="text-center">Qty</th>
                        <th class="text-right">Unit Price</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>';

                foreach ($items as $row) {

                    $total = $row->qty * $row->unitprice;

                    $html .= '
                    <tr>
                        <td>
                            <b>' . $row->materialname . '</b><br>
                            <small>' . $row->materialinfocode . '</small>
                        </td>
                        <td class="text-center">' . $row->qty . '</td>
                        <td class="text-right">' . number_format($row->unitprice, 2) . '</td>
                        <td class="text-right">' . number_format($total, 2) . '</td>
                    </tr>';
                }

        $html .= '
                </tbody>
            </table>

            <div class="grn-total">
                TOTAL: Rs. ' . number_format($grn->total, 2) . '
            </div>

        </div>';

        echo $html;
    }
    public function Goodreceivestatus($idtbl_grn, $status, $porder_id) {
        $this->db->trans_begin();
    
        $userID = $_SESSION['userid'];
        $recordID = $idtbl_grn;
        $type = $status;
        $updatedatetime = date('Y-m-d H:i:s');
    
        if($type == 1) {
            $data = array(
                'approvestatus' => '1',
                'updateuser' => $userID,
                'updatedatetime' => $updatedatetime
            );
    
            // Update tbl_grn
            $this->db->where('idtbl_grn', $recordID);
            $this->db->update('tbl_grn', $data);
    
            // Update tbl_porder with porder_id
            $datagrn = array(
                'grnconfirm' => '1',
                'updateuser' => $userID,
                'updatedatetime' => $updatedatetime
            );
    
            $this->db->where('idtbl_porder', $porder_id);
            $this->db->update('tbl_porder', $datagrn);

            $this->db->select('`tbl_grndetail`.`qty`, `tbl_grndetail`.`unitprice`, `tbl_grndetail`.`saleprice`, `tbl_grndetail`.`tbl_material_info_idtbl_material_info`,`tbl_grn`.`batchno`');
            $this->db->from('tbl_grndetail');
            $this->db->join('tbl_grn', 'tbl_grn.idtbl_grn = tbl_grndetail.tbl_grn_idtbl_grn', 'left');
            $this->db->where('tbl_grndetail.status', 1);
            $this->db->where('tbl_grn_idtbl_grn', $recordID);

            $respond=$this->db->get();

            foreach($respond->result() as $grnproductlist){
                $batchno = $respond->row(0)->batchno;
                $qty = $grnproductlist->qty;
                $unitprice = $grnproductlist->unitprice;
                $saleprice = $grnproductlist->saleprice;
                $materialID = $grnproductlist->tbl_material_info_idtbl_material_info;
            
                $sqlcheck = "SELECT `qty` FROM `tbl_stock` WHERE `tbl_material_info_idtbl_material_info` = ? AND `batchno` = ? AND `status` = ?";
                $respondcheck = $this->db->query($sqlcheck, array($materialID, $batchno, 1));
            
                if($respondcheck->num_rows() == 0) {
                    $data = array(
                        'batchno' => $batchno,
                        'qty' => $qty,
                        'costprice' => $unitprice,
                        'saleprice' => $saleprice,
                        'status' => '1',
                        'insertdatetime' => $updatedatetime,
                        'tbl_user_idtbl_user' => $userID,
                        'tbl_material_info_idtbl_material_info' => $materialID
                    );
                    $this->db->insert('tbl_stock', $data);
                } else {
                    $existingQty = $respondcheck->row(0)->qty;
                    $newQty = $existingQty + $qty;
            
                    $data = array(
                        'qty' => $newQty,
                        'costprice' => $costunitprice,
                        'saleprice' => $saleprice,
                        'updateuser' => $userID,
                        'updatedatetime' => $updatedatetime
                    );
                    $this->db->where('batchno', $batchno);
                    $this->db->where('tbl_material_info_idtbl_material_info', $materialID);
                    $this->db->update('tbl_stock', $data);
                }
            }
    
            $this->db->trans_complete();
    
            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
    
                $actionObj = new stdClass();
                $actionObj->icon = 'fas fa-check';
                $actionObj->title = '';
                $actionObj->message = 'Order Confirm Successfully';
                $actionObj->url = '';
                $actionObj->target = '_blank';
                $actionObj->type = 'success';
    
                $actionJSON = json_encode($actionObj);
    
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('Goodreceive');
            } else {
                $this->db->trans_rollback();
    
                $actionObj = new stdClass();
                $actionObj->icon = 'fas fa-warning';
                $actionObj->title = '';
                $actionObj->message = 'Record Error';
                $actionObj->url = '';
                $actionObj->target = '_blank';
                $actionObj->type = 'danger';
    
                $actionJSON = json_encode($actionObj);
    
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('Goodreceive');
            }
        } else if($type == 3) {
            $data = array(
                'status' => '3',
                'updateuser' => $userID,
                'updatedatetime' => $updatedatetime
            );
    
            // Update tbl_grn
            $this->db->where('idtbl_grn', $recordID);
            $this->db->update('tbl_grn', $data);
    
            $this->db->trans_complete();
    
            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
    
                $actionObj = new stdClass();
                $actionObj->icon = 'fas fa-trash-alt';
                $actionObj->title = '';
                $actionObj->message = 'Record Reject Successfully';
                $actionObj->url = '';
                $actionObj->target = '_blank';
                $actionObj->type = 'danger';
    
                $actionJSON = json_encode($actionObj);
    
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('Goodreceive');
            } else {
                $this->db->trans_rollback();
    
                $actionObj = new stdClass();
                $actionObj->icon = 'fas fa-warning';
                $actionObj->title = '';
                $actionObj->message = 'Record Error';
                $actionObj->url = '';
                $actionObj->target = '_blank';
                $actionObj->type = 'danger';
    
                $actionJSON = json_encode($actionObj);
    
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('Goodreceive');
            }
        }
    }
    
    public function Getsupplieraccoporder(){
        $recordID=$this->input->post('recordID');

        $this->db->select('`tbl_supplier_idtbl_supplier`');
        $this->db->from('tbl_porder');
        $this->db->where('status', 1);
        $this->db->where('idtbl_porder', $recordID);

        $respond=$this->db->get();

        echo $respond->row(0)->tbl_supplier_idtbl_supplier;
    }
    public function Getproductaccoporder(){
        $recordID=$this->input->post('recordID');

        $sql="SELECT `tbl_material_info`.`idtbl_material_info`, `tbl_material_info`.`materialinfocode`,`tbl_material_info`.`materialname`
         FROM `tbl_porder_detail` LEFT JOIN `tbl_material_info` ON `tbl_material_info`.`idtbl_material_info`=`tbl_porder_detail`.`tbl_material_info_idtbl_material_info` WHERE `tbl_material_info`.`status`=? AND `tbl_porder_detail`.`tbl_porder_idtbl_porder`=?";
        $respond=$this->db->query($sql, array(1, $recordID));

        echo json_encode($respond->result());
    }
    public function Getproductinfoaccoproduct(){
        $recordID=$this->input->post('recordID');
        $porderID=$this->input->post('porderID');

        $this->db->select('`qty`, `unitprice`, `comment`');
        $this->db->from('tbl_porder_detail');
        $this->db->where('status', 1);
        $this->db->where('tbl_material_info_idtbl_material_info', $recordID);
        $this->db->where('tbl_porder_idtbl_porder', $porderID);

        $respond=$this->db->get();

        if($respond->num_rows()>0){
            $obj=new stdClass();
            $obj->qty=$respond->row(0)->qty;
            $obj->unitprice=$respond->row(0)->unitprice;
            $obj->comment=$respond->row(0)->comment;
        }

        else{
            $obj=new stdClass();
            $obj->qty=0;
            $obj->unitprice=0;
            $obj->comment='';
        }
        echo json_encode($obj);
    }
    public function Getexpdateaccoquater(){
        $recordID=$this->input->post('recordID');
        $mfdate=$this->input->post('mfdate');

        if($recordID==1){$addmonth=3;}
        else if($recordID==2){$addmonth=6;}
        else if($recordID==3){$addmonth=9;}
        else if($recordID==4){$addmonth=12;}
        else if($recordID==5){$addmonth=18;}
        else if($recordID==6){$addmonth=24;}

        echo date('Y-m-d', strtotime("+$addmonth months", strtotime($mfdate)));
    }
    public function Getbatchnoaccosupplier() {
        $recordID = $this->input->post('recordID');
    
        if (!empty($recordID)) {
            $this->db->select('tbl_supplier.suppliercode');
            $this->db->from('tbl_supplier');
            $this->db->join('tbl_supplier_has_tbl_material_category', 'tbl_supplier_has_tbl_material_category.tbl_supplier_idtbl_supplier = tbl_supplier.idtbl_supplier', 'left');
            $this->db->join('tbl_material_category', 'tbl_material_category.idtbl_material_category = tbl_supplier_has_tbl_material_category.tbl_material_category_idtbl_material_category', 'left');
            $this->db->where('tbl_supplier.idtbl_supplier', $recordID);
            $this->db->where('tbl_supplier.status', 1);
    
            $responddetail = $this->db->get();
    
            if ($responddetail->num_rows() > 0) {
                $suppliercode = $responddetail->row(0)->suppliercode;
            } else {
                $suppliercode = '';
            }
    
            $sql = "SELECT COUNT(*) AS count FROM tbl_grn";
            $respond = $this->db->query($sql);
    
            if ($respond->num_rows() > 0 && $respond->row(0)->count > 0) {
                $count = '000' . ($respond->row(0)->count + 1);
                $count = substr($count, -3);
            } else {
                $count = '001';
            }
    
            $batchno = date('dmY') . $count;
    
            echo $suppliercode . $batchno;
        } else {
            echo '';
        }
    } 
    
    public function Getlastgrnprice(){
        $recordID = $this->input->post('recordID');

        $this->db->select('costprice, saleprice');
        $this->db->from('tbl_stock');
        $this->db->where('tbl_material_info_idtbl_material_info', $recordID);
        $this->db->where('status', 1);
        $this->db->order_by('idtbl_stock', 'DESC');
        $this->db->limit(1);

        $respond = $this->db->get();

        if($respond->num_rows() > 0){
            $obj = new stdClass();
            $obj->costprice = $respond->row(0)->costprice;
            $obj->saleprice = $respond->row(0)->saleprice;
        } else {
            $obj = new stdClass();
            $obj->costprice = 0;
            $obj->saleprice = 0;
        }

        echo json_encode($obj);
    }
}