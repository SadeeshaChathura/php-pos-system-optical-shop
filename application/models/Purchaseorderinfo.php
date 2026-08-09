<?php
class Purchaseorderinfo extends CI_Model{
    public function Getsupplier(){
        $this->db->select('`idtbl_supplier`, `suppliername`');
        $this->db->from('tbl_supplier');
        $this->db->where('status', 1);

        return $respond=$this->db->get();
    }
    public function Getproductaccosupplier(){
        $recordID=$this->input->post('recordID');

        $sql="SELECT mi.idtbl_material_info, mi.materialinfocode, mi.materialname FROM tbl_material_info mi JOIN tbl_supplier_has_tbl_material_info smi ON mi.idtbl_material_info = smi.tbl_material_info_idtbl_material_info WHERE smi.tbl_supplier_idtbl_supplier = ? AND mi.status = ?";
        $respond=$this->db->query($sql, array($recordID, 1));

        echo json_encode($respond->result());
    }

    /**
     * Returns all active materials whose current stock is at/below their
     * reorderlevel (materials with reorderlevel = 0 are excluded, since
     * that's treated as "not tracked").
     */
    public function Getreorderpointitems(){
        $sql = "SELECT
                    mi.idtbl_material_info,
                    mi.materialinfocode,
                    mi.materialname,
                    mi.reorderlevel,
                    mc.categoryname,
                    COALESCE(s.currentstock, 0) AS currentstock,
                    COALESCE(recent.lastunitprice, 0) AS lastunitprice,
                    recent.tbl_supplier_idtbl_supplier AS lastsupplierid,
                    recent.suppliername AS lastsuppliername
                FROM tbl_material_info mi
                LEFT JOIN tbl_material_category mc
                    ON mc.idtbl_material_category = mi.tbl_material_category_idtbl_material_category
                LEFT JOIN (
                    SELECT tbl_material_info_idtbl_material_info, SUM(qty) AS currentstock
                    FROM tbl_stock
                    WHERE status = 1
                    GROUP BY tbl_material_info_idtbl_material_info
                ) s ON s.tbl_material_info_idtbl_material_info = mi.idtbl_material_info
                LEFT JOIN (
                    SELECT gd.tbl_material_info_idtbl_material_info,
                           gd.unitprice AS lastunitprice,
                           g.tbl_supplier_idtbl_supplier,
                           sup.suppliername
                    FROM tbl_grndetail gd
                    INNER JOIN tbl_grn g ON g.idtbl_grn = gd.tbl_grn_idtbl_grn
                    INNER JOIN tbl_supplier sup ON sup.idtbl_supplier = g.tbl_supplier_idtbl_supplier
                    INNER JOIN (
                        SELECT gd2.tbl_material_info_idtbl_material_info, MAX(gd2.idtbl_grndetail) AS maxid
                        FROM tbl_grndetail gd2
                        WHERE gd2.status = 1
                        GROUP BY gd2.tbl_material_info_idtbl_material_info
                    ) latest ON latest.tbl_material_info_idtbl_material_info = gd.tbl_material_info_idtbl_material_info
                            AND latest.maxid = gd.idtbl_grndetail
                    WHERE gd.status = 1
                ) recent ON recent.tbl_material_info_idtbl_material_info = mi.idtbl_material_info
                WHERE mi.status = 1
                  AND mi.reorderlevel > 0
                  AND COALESCE(s.currentstock, 0) <= mi.reorderlevel
                ORDER BY (mi.reorderlevel - COALESCE(s.currentstock,0)) DESC";

        $respond = $this->db->query($sql);

        $rows = $respond->result();

        foreach($rows as $row){
            $row->suggestedqty = ($row->reorderlevel * 2) - $row->currentstock;
            if($row->suggestedqty < 1){
                $row->suggestedqty = $row->reorderlevel;
            }

            // If this material was never received before, fall back to
            // any supplier currently linked to it.
            if(empty($row->lastsupplierid)){
                $fallback = $this->db->query(
                    "SELECT sup.idtbl_supplier, sup.suppliername
                     FROM tbl_supplier sup
                     INNER JOIN tbl_supplier_has_tbl_material_info smi
                        ON smi.tbl_supplier_idtbl_supplier = sup.idtbl_supplier
                     WHERE smi.tbl_material_info_idtbl_material_info = ?
                       AND sup.status = 1
                     LIMIT 1",
                    array($row->idtbl_material_info)
                )->row();

                $row->lastsupplierid = $fallback ? $fallback->idtbl_supplier : null;
                $row->lastsuppliername = $fallback ? $fallback->suppliername : null;
            }
        }

        echo json_encode($rows);
    }

    /**
     * All active suppliers linked to a given material, for the supplier
     * dropdown on the reorder-suggestions row.
     */
    public function Getsuppliersformaterial(){
        $recordID = $this->input->post('recordID');

        $sql = "SELECT sup.idtbl_supplier, sup.suppliername
                FROM tbl_supplier sup
                INNER JOIN tbl_supplier_has_tbl_material_info smi
                    ON smi.tbl_supplier_idtbl_supplier = sup.idtbl_supplier
                WHERE smi.tbl_material_info_idtbl_material_info = ?
                  AND sup.status = 1";

        $respond = $this->db->query($sql, array($recordID));

        echo json_encode($respond->result());
    }

    /**
     * Creates one Purchase Order per supplier group, exactly like the
     * manual flow in Purchaseorderinsertupdate(), but driven from the
     * reorder-suggestions screen. Expects POST 'groups' as:
     * [{ supplier: id, items: [{ materialID, qty, unitprice, comment }] }]
     */
    public function Autocreatepo(){
        $this->db->trans_begin();

        $userID = $_SESSION['userid'];
        $groups = $this->input->post('groups');
        $updatedatetime = date('Y-m-d H:i:s');
        $today = date('Y-m-d');

        $createdCount = 0;

        foreach($groups as $group){
            $supplierID = $group['supplier'];
            $items = $group['items'];

            if(empty($supplierID) || empty($items)){
                continue;
            }

            $subtotal = 0;
            foreach($items as $it){
                $subtotal += (float)$it['qty'] * (float)$it['unitprice'];
            }

            $data = array(
                'orderdate'      => $today,
                'duedate'        => $today,
                'subtotal'       => $subtotal,
                'discount'       => '0',
                'discountamount' => '0',
                'nettotal'       => $subtotal,
                'confirmstatus'  => '0',
                'grnconfirm'     => '0',
                'remark'         => 'Auto-generated: Reorder Point Suggestion',
                'status'         => '1',
                'insertdatetime' => $updatedatetime,
                'tbl_supplier_idtbl_supplier' => $supplierID,
                'tbl_user_idtbl_user'         => $userID
            );

            $this->db->insert('tbl_porder', $data);
            $porderID = $this->db->insert_id();

            foreach($items as $it){
                $dataone = array(
                    'qty'             => $it['qty'],
                    'unitprice'       => $it['unitprice'],
                    'discount'        => '0',
                    'discountamount'  => '0',
                    'comment'         => isset($it['comment']) ? $it['comment'] : 'Auto reorder',
                    'status'          => '1',
                    'insertdatetime'  => $updatedatetime,
                    'tbl_porder_idtbl_porder' => $porderID,
                    'tbl_material_info_idtbl_material_info' => $it['materialID']
                );

                $this->db->insert('tbl_porder_detail', $dataone);
            }

            $createdCount++;
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE && $createdCount > 0) {
            $this->db->trans_commit();

            $actionObj = new stdClass();
            $actionObj->icon = 'fas fa-save';
            $actionObj->title = '';
            $actionObj->message = $createdCount . ' Purchase Order(s) Created from Reorder Suggestions';
            $actionObj->url = '';
            $actionObj->target = '_blank';
            $actionObj->type = 'success';

            $obj = new stdClass();
            $obj->status = 1;
            $obj->action = json_encode($actionObj);

            echo json_encode($obj);
        } else {
            $this->db->trans_rollback();

            $actionObj = new stdClass();
            $actionObj->icon = 'fas fa-exclamation-triangle';
            $actionObj->title = '';
            $actionObj->message = 'No Purchase Orders Created';
            $actionObj->url = '';
            $actionObj->target = '_blank';
            $actionObj->type = 'danger';

            $obj = new stdClass();
            $obj->status = 0;
            $obj->action = json_encode($actionObj);

            echo json_encode($obj);
        }
    }

    public function Purchaseorderinsertupdate(){
        $this->db->trans_begin();

        $userID=$_SESSION['userid'];

        $tableData=$this->input->post('tableData');
        $orderdate=$this->input->post('orderdate');
        $duedate=$this->input->post('duedate');
        $total=$this->input->post('total');
        $remark=$this->input->post('remark');
        $supplier=$this->input->post('supplier');

        $updatedatetime=date('Y-m-d H:i:s');

        $data = array(
            'orderdate'=> $orderdate, 
            'duedate'=> $duedate, 
            'subtotal'=> $total, 
            'discount'=> '0', 
            'discountamount'=> '0', 
            'nettotal'=> $total, 
            'confirmstatus'=> '0', 
            'grnconfirm'=> '0', 
            'remark'=> $remark, 
            'status'=> '1', 
            'insertdatetime'=> $updatedatetime, 
            'tbl_supplier_idtbl_supplier'=> $supplier,
            'tbl_user_idtbl_user'=> $userID

        );

        $this->db->insert('tbl_porder', $data);

        $porderID=$this->db->insert_id();

        foreach($tableData as $rowtabledata){
            $materialname=$rowtabledata['col_1'];
            $comment=$rowtabledata['col_2'];
            $materialID=$rowtabledata['col_3'];
            $unit=$rowtabledata['col_4'];
            $qty=$rowtabledata['col_5'];
            $nettotal=$rowtabledata['col_6'];

            $dataone = array(
                'qty'=> $qty, 
                'unitprice'=> $unit, 
                'discount'=> '0', 
                'discountamount'=> '0', 
                'comment'=> $comment, 
                'status'=> '1', 
                'insertdatetime'=> $updatedatetime,
                'tbl_porder_idtbl_porder'=> $porderID, 
                'tbl_material_info_idtbl_material_info'=> $materialID
            );

            $this->db->insert('tbl_porder_detail', $dataone);
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
    public function Purchaseorderview(){

            $recordID = $this->input->post('recordID');

            // ---------------- HEADER ----------------
            $sql = "SELECT u.*, ua.suppliername, ua.primarycontactno, ua.secondarycontactno,
                            ua.address, ua.email
                    FROM tbl_porder AS u
                    LEFT JOIN tbl_supplier AS ua 
                        ON ua.idtbl_supplier = u.tbl_supplier_idtbl_supplier
                    WHERE u.status=? AND u.idtbl_porder=?";

            $respond = $this->db->query($sql, [1, $recordID]);

            if ($respond->num_rows() == 0) {
                echo '<div class="alert alert-danger">Record not found</div>';
                return;
            }

            $po = $respond->row(0);

            // ---------------- DETAIL ----------------
            $this->db->select('d.*, m.materialinfocode, m.materialname');
            $this->db->from('tbl_porder_detail d');
            $this->db->join('tbl_material_info m', 'm.idtbl_material_info = d.tbl_material_info_idtbl_material_info', 'left');
            $this->db->where('d.tbl_porder_idtbl_porder', $recordID);
            $this->db->where('d.status', 1);

            $items = $this->db->get()->result();

            // ---------------- CONTACT ----------------
            $contact = [];
            if (!empty($po->primarycontactno)) $contact[] = $po->primarycontactno;
            if (!empty($po->secondarycontactno)) $contact[] = $po->secondarycontactno;

            $contactLine = !empty($contact) ? implode(' / ', $contact) : '-';

            // ---------------- HTML ----------------
            $html = '

        <style>
        .po-card{
            padding:15px;
            background:#fff;
            border-radius:8px;
            border:1px solid #e5e5e5;
        }

        .po-header{
            display:flex;
            justify-content:space-between;
            margin-bottom:10px;
        }

        .company-box{
            font-size:13px;
            line-height:1.6;
            color:#333;
        }

        .supplier-box{
            text-align:right;
            font-size:13px;
            line-height:1.6;
        }

        .po-title{
            font-size:18px;
            font-weight:600;
            color:#254F96;
        }

        .po-table{
            width:100%;
            border-collapse:collapse;
            margin-top:10px;
        }

        .po-table th{
            background:#254F96;
            color:#fff;
            padding:8px;
            font-size:13px;
        }

        .po-table td{
            padding:8px;
            border-bottom:1px solid #eee;
            font-size:13px;
        }

        .text-right{ text-align:right; }
        .text-center{ text-align:center; }

        .total-box{
            text-align:right;
            font-size:22px;
            font-weight:600;
            margin-top:15px;
            color:#000;
        }

        .badge{
            display:inline-block;
            background:#f1f5ff;
            padding:4px 8px;
            border-radius:5px;
            font-size:12px;
        }
        </style>

        <div class="po-card">

            <div class="po-header">

                <div class="company-box">
                    <div class="po-title">PURCHASE ORDER</div>
                    <strong>කන්නාඩිය Pvt Ltd</strong><br>
                    0719622717 / 0703955682<br>
                    Thalawa Road, Kekirawa<br>
                    <a href="mailto:Kannadiyaopticalservice@gmail.com">Kannadiyaopticalservice@gmail.com</a>
                </div>

                <div class="supplier-box">
                    <strong>' . $po->suppliername . '</strong><br>
                    <span class="badge">Contact</span> ' . $contactLine . '<br>
                    <span class="badge">Address</span> ' . $po->address . '<br>
                    <a href="mailto:' . $po->email . '">' . $po->email . '</a>
                </div>

            </div>

            <table class="po-table">
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

            <div class="total-box">
                TOTAL: Rs. ' . number_format($po->nettotal, 2) . '
            </div>

        </div>';

            echo $html;
    }
    public function Purchaseorderstatus($x, $y){
        $this->db->trans_begin();

        $userID=$_SESSION['userid'];
        $recordID=$x;
        $type=$y;
        $updatedatetime=date('Y-m-d H:i:s');

        if($type==1){
            $data = array(
                'confirmstatus' => '1',
                'updateuser'=> $userID, 
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_porder', $recordID);
            $this->db->update('tbl_porder', $data);

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                
                $actionObj=new stdClass();
                $actionObj->icon='fas fa-check';
                $actionObj->title='';
                $actionObj->message='Order Confirm Successfully';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='success';

                $actionJSON=json_encode($actionObj);
                
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('Purchaseorder');                
            } else {
                $this->db->trans_rollback();

                $actionObj=new stdClass();
                $actionObj->icon='fas fa-warning';
                $actionObj->title='';
                $actionObj->message='Record Error';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='danger';

                $actionJSON=json_encode($actionObj);
                
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('Purchaseorder');
            }
        }
    }

    
}