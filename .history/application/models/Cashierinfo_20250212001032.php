<?php
class Directsaleinfo extends CI_Model{
    public function Getmaterial(){
        $this->db->select('`idtbl_material_category`, `categoryname`');
        $this->db->from('tbl_material_category');
        $this->db->where('status', 1);

        return $respond=$this->db->get();
    }

    public function Getlocation(){
        $this->db->select('`idtbl_location`, `location`');
        $this->db->from('tbl_location');
        $this->db->where('tbl_location.status', 1);

        return $respond=$this->db->get();
    }

    public function Getcustomer(){
        $this->db->select('`idtbl_customer`, `name`');
        $this->db->from('tbl_customer');
        $this->db->where('status', 1);

        return $respond=$this->db->get();
    }

    public function Getproductlist() {
        $html = '';
    
        $hiddenmaterialID = $this->input->post('categoryID');
    
        $sql = "SELECT `tbl_material_info`.`idtbl_material_info`, `tbl_material_info`.`materialinfocode`,  `tbl_material_info`.`materialname` FROM `tbl_material_info` WHERE `tbl_material_info`.`tbl_material_category_idtbl_material_category` = $hiddenmaterialID AND `tbl_material_info`.`status` = 1";
        $respond = $this->db->query($sql);
    
        if ($respond->num_rows() > 0) {
            foreach ($respond->result() as $rowlist) {
                $productID = $rowlist->idtbl_material_info;
                $sqlstockcheck = "SELECT SUM(`qty`) AS sumqty, `saleprice`, `batchno` FROM `tbl_stock` WHERE `tbl_material_info_idtbl_material_info`='$productID'  AND `qty` != '0'"; 
        
                $respondstockcheck = $this->db->query($sqlstockcheck);
        
                if (!empty($respondstockcheck->row(0)->sumqty) || !empty($respondstockcheck->row(0)->saleprice)|| !empty($respondstockcheck->row(0)->batchno)) {
                    $stockcount = $respondstockcheck->row(0)->sumqty;
                    $saleprice = $respondstockcheck->row(0)->saleprice;
                    $batchno = $respondstockcheck->row(0)->batchno;

                    $html .= '
                        <tr tabindex="0" class=' . ($stockcount == 0 ? 'table-danger' : 'pointer') . ' id="' . $rowlist->idtbl_material_info . '">
                            <td class="d-none">' . $rowlist->idtbl_material_info . '</td>
                            <td>' . $rowlist->materialname . '</td>
                            <td>' . $rowlist->materialinfocode . '</td>
                            <td>' . $batchno . '</td>
                            <td></td>
                            <td class="d-none">' . $saleprice . '</td>
                            <td>' . number_format($saleprice, 2) . '</td>
                            <td>' . $stockcount . '</td>
                        </tr>';
                }
            }
        } else {
            $html .= '
                <tr>
                    <td colspan="7" class="text-center">No Item To Show</td>
                </tr>
            ';
        }
        echo $html;
    }
    

    public function Getproductlistaccobarcode(){

        $barcode=$this->input->post('barcode');

        $this->db->select('`tbl_product`.`idtbl_product`, `tbl_product`.`productcode`,  `tbl_product`.`barcode`, `tbl_product`.`retailprice`, `tbl_product`.`wholesaleprice`');
        $this->db->from('tbl_product');
        $this->db->join('tbl_material_code', 'tbl_material_code.idtbl_material_code = tbl_product.materialid', 'left');
        $this->db->where('tbl_product.barcode', $barcode);
        $this->db->where('tbl_product.status', 1);

        $respond=$this->db->get();

        $obj=new stdClass();
        $obj->id=$respond->row(0)->idtbl_product;
        $obj->productcode=$respond->row(0)->productcode;
        $obj->price=$respond->row(0)->retailprice ;
        $obj->barcode=$respond->row(0)->barcode;


        echo json_encode($obj);


    }


    public function Directsaleinsertupdate(){

        $this->db->trans_begin();

        $userID=$_SESSION['userid'];
        $companyid=$_SESSION['companyid'];
        $branchid=$_SESSION['branchid'];

        $tableData=$this->input->post('tableData');
        $tableDataPay=$this->input->post('tableDataPay');
        $total=$this->input->post('total');
        $distotal=$this->input->post('distotal');
        $nettotal=$this->input->post('nettotal');
        $billtype=$this->input->post('billtype');
        $paytotal=$this->input->post('paytotal');
        $orderid=$this->input->post('orderid');
        $customer=$this->input->post('customer');

        $balance = max(0, $paytotal - $nettotal);

        $insertdatetime=date('Y-m-d H:i:s');
        $invdate=date('Y-m-d H:i:s');

        $data = array(
            'invdate'=> $invdate, 
            'grosstotal'=> $total, 
            'discount'=> $distotal, 
            'nettotal'=> $nettotal, 
            'invtype'=> '0', 
            'paycomplete'=> '0', 
            'status'=> '1', 
            'insertdatetime'=> $insertdatetime, 
            'tbl_user_idtbl_user'=> $userID,
            'tbl_company_idtbl_company'=> $companyid,
            'tbl_company_branch_idtbl_company_branch'=> $branchid,
            'tbl_customer_idtbl_customer'=> $customer
        );

        $this->db->insert('tbl_invoice', $data);

        $invoiceID=$this->db->insert_id();

        foreach($tableData as $rowtabledata){
            $data = array(
                'qty'=> $rowtabledata['col_2'], 
                'saleprice'=> $rowtabledata['col_3'], 
                'discount'=> $rowtabledata['col_4'], 
                'total'=> $rowtabledata['col_5'],  
                'status'=> '1', 
                'insertdatetime'=> $insertdatetime,
                'tbl_invoice_idtbl_invoice'=> $invoiceID, 
                'tbl_material_info_idtbl_material_info'=> $rowtabledata['col_6']
            );

                $this->db->insert('tbl_invoice_detail', $data);

                $qtyToReduce = $rowtabledata['col_2'];

                $this->db->select('idtbl_stock, qty');
                $this->db->from('tbl_stock');
                $this->db->where('tbl_material_info_idtbl_material_info', $rowtabledata['col_6']);
                $this->db->order_by('idtbl_stock', 'ASC');
                $respond2 = $this->db->get();
                $rows = $respond2->result_array();

                foreach ($rows as $row) {
                    $currentQty = $row['qty'];

                    if ($currentQty >= $qtyToReduce) {
                        $finalqty = $currentQty - $qtyToReduce;

                        $data2 = array(
                            'qty' => $finalqty,
                        );
                        $this->db->where('idtbl_stock', $row['idtbl_stock']);
                        $this->db->set($data2);
                        $this->db->update('tbl_stock');
                        
                        break;
                    } else {
                        $qtyToReduce -= $currentQty;

                        $data2 = array(
                            'qty' => 0,
                        );
                        $this->db->where('idtbl_stock', $row['idtbl_stock']);
                        $this->db->set($data2);
                        $this->db->update('tbl_stock');
                    }
                }
        }

        if($billtype==1){
            $data = array(
                'paydate'=> $invdate, 
                'nettotal'=> $paytotal, 
                'balance'=> $balance, 
                'status'=> '1', 
                'insertdatetime'=> $insertdatetime, 
                'tbl_user_idtbl_user'=> $userID,
                'tbl_company_idtbl_company'=> $companyid,
                'tbl_company_branch_idtbl_company_branch'=> $branchid
            );
    
            $this->db->insert('tbl_invoice_payment', $data);

            $invoicepayID=$this->db->insert_id();

            foreach ($tableDataPay as $rowtableDataPay) {
                $dataone = array(
                    'method' => $rowtableDataPay['col_1'],
                    'amount' => $rowtableDataPay['col_7'], // Correct amount
                    'bank' => $rowtableDataPay['col_3'],
                    'branch' => $rowtableDataPay['col_4'],
                    'chequeno' => $rowtableDataPay['col_5'],
                    'chequedate' => $rowtableDataPay['col_6'],
                    'status' => '1',
                    'insertdatetime' => $insertdatetime,
                    'tbl_user_idtbl_user' => $userID,
                    'tbl_invoice_payment_idtbl_invoice_payment' => $invoicepayID,
                );
            
                $this->db->insert('tbl_invoice_payment_detail', $dataone);

                $datatwo = array(
                    'tbl_invoice_payment_idtbl_invoice_payment'=> $invoicepayID, 
                    'tbl_invoice_idtbl_invoice'=> $invoiceID, 
                );
        
                $this->db->insert('tbl_invoice_payment_has_tbl_invoice', $datatwo);
            }            
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
            $obj->action=json_encode($actionObj);
            $obj->actiontype='1';
            $obj->invoiceid=$invoiceID;
            $obj->billtype=$billtype;
            
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
            $obj->action=json_encode($actionObj);
            $obj->actiontype='0';
            $obj->invoiceid='0';
            $obj->billtype=$billtype;
            
            echo json_encode($obj);
        }

    }

    public function Getproductavalaibleqty(){

        $product=$this->input->post('product');
        $inserted_qty=$this->input->post('qty');

        $this->db->select_sum('qty');
        $this->db->from('tbl_stock');
        $this->db->where('tbl_material_info_idtbl_material_info', $product);
        $this->db->where('status', 1);
        $respond=$this->db->get();
        
        $availableqty = 0;
            
        if ($respond->num_rows() > 0) {
            $result = $respond->row();
            $availableqty = $result->qty;
        }
    
        $obj = new stdClass();
        if ($inserted_qty > $availableqty) {
            $qtyresult = 1;
        } else {
            $qtyresult = 0;
        }
        $obj->checkqty = $qtyresult;
        echo json_encode($obj);

    }

    public function Getcustomerlist(){
        $searchTerm=$this->input->post('searchTerm');

        if(!isset($searchTerm)){
            $sql="SELECT `idtbl_customer`, `name` FROM `tbl_customer` WHERE `status`=? LIMIT 5";
            $respond=$this->db->query($sql, array(1));                       
        }
        else{            
            if(!empty($searchTerm)){
                $sql="SELECT `idtbl_customer`, `name` FROM `tbl_customer` WHERE `status`=? AND `name` LIKE '$searchTerm%'";
                $respond=$this->db->query($sql, array(1));    
            }
            else{
                $sql="SELECT `idtbl_customer`, `name` FROM `tbl_customer` WHERE `status`=? LIMIT 5";
                $respond=$this->db->query($sql, array(1));                
            }
        }
        
        $data=array();
        
        foreach ($respond->result() as $row) {
            $data[]=array("id"=>$row->idtbl_customer, "text"=>$row->name);
        }
        
        echo json_encode($data);
    }
}
