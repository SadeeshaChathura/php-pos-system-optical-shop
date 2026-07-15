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
        $recordID=$this->input->post('recordID');

        $sql="SELECT `u`.*, `ua`.`suppliername`, `ua`.`primarycontactno`, `ua`.`secondarycontactno`, `ua`.`address`, `ua`.`email` FROM `tbl_porder` AS `u` LEFT JOIN `tbl_supplier` AS `ua` ON (`ua`.`idtbl_supplier` = `u`.`tbl_supplier_idtbl_supplier`) WHERE `u`.`status`=? AND `u`.`idtbl_porder`=?";
        $respond=$this->db->query($sql, array(1, $recordID));

        $this->db->select('tbl_porder_detail.*, tbl_material_info.materialinfocode, tbl_material_info.materialname');
        $this->db->from('tbl_porder_detail');
        $this->db->join('tbl_material_info', 'tbl_material_info.idtbl_material_info = tbl_porder_detail.tbl_material_info_idtbl_material_info', 'left');
        $this->db->where('tbl_porder_detail.tbl_porder_idtbl_porder', $recordID);
        $this->db->where('tbl_porder_detail.status', 1);

        $responddetail=$this->db->get();
        // print_r($this->db->last_query());

        $html='';
        $html.='
        <div class="row">
            <div class="col-7 text-left">
                <strong>කන්නාඩිය pvt ltd </strong><br>
                <strong>Contact: </strong> 0719622717 / 0703955682<br>
                Address: </strong> Kannadiya Pvt LTD, Thalawa Road, Kekirawa<br>
                <a href="mailto:Kannadiyaopticalservice@gmail.com">Kannadiyaopticalservice@gmail.com</a>
            </div>

            <div class="col-5 text-left">
                <strong>' . $respond->row(0)->suppliername . '</strong><br>

                <!-- Displaying contact numbers with labels -->
                <strong>Contact:</strong> ';
                
                $contactNumbers = [];
                if (!empty($respond->row(0)->primarycontactno)) {
                    $contactNumbers[] = $respond->row(0)->primarycontactno;
                }
                if (!empty($respond->row(0)->secondarycontactno)) {
                    $contactNumbers[] = $respond->row(0)->secondarycontactno;
                }

                if (!empty($contactNumbers)) {
                    $html .= implode(' / ', $contactNumbers) . '<br>';
                }

                $html .= '<strong>Address:</strong> ' . $respond->row(0)->address . '<br>

                <a href="mailto:' . $respond->row(0)->email . '">' . $respond->row(0)->email . '</a>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <hr>
                <table class="table table-striped table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Material Info</th>
                            <th>Unit Price</th>
                            <th>Qty</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>';
                    foreach($responddetail->result() as $roworderinfo){
                        $html.='<tr>
                            <td>'.$roworderinfo->materialname.' / '.$roworderinfo->materialinfocode.'</td>
                            <td>'.$roworderinfo->unitprice.'</td>
                            <td>'.$roworderinfo->qty.'</td>
                            <td class="text-right">'.number_format(($roworderinfo->qty*$roworderinfo->unitprice), 2).'</td>
                        </tr>';
                    }
                    $html.='</tbody>
                </table>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12 text-right"><h3 class="font-weight-normal">Rs. '.number_format(($respond->row(0)->nettotal), 2).'</h3></div>
        </div>
        ';

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