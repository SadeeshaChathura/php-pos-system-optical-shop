<?php
class Invoiceviewinfo extends CI_Model{
    public function locationlist(){
        $this->db->select('`idtbl_location, `location`');
        $this->db->from('tbl_location');
        $this->db->where('status', 1);

        return $respond=$this->db->get();
    }
    public function Getinvoicedetails(){
        $html = '';

        $recordID=$this->input->post('recordID');


        $sql = "SELECT `tbl_invoice_detail`.`idtbl_invoice_detail`, `tbl_invoice_detail`.`qty`, `tbl_invoice_detail`.`total`,`tbl_material_info`.`materialname`
        FROM `tbl_invoice_detail`
        LEFT JOIN `tbl_invoice`  ON `tbl_invoice`.`idtbl_invoice` = `tbl_invoice_detail`.`tbl_invoice_idtbl_invoice`
        LEFT JOIN `tbl_material_info`  ON `tbl_material_info`.`idtbl_material_info` = `tbl_invoice_detail`.`tbl_material_info_idtbl_material_info` WHERE `tbl_invoice`.`idtbl_invoice`=? AND `tbl_invoice_detail`.`status`=?";
        $respond = $this->db->query($sql, array($recordID, 1)); 

        $html.='
        <table class="table table-bordered table-striped table-sm nowrap" id="tblInvoicelist">
            <thead>
                <tr>
                <th scope="col">#</th>
                <th scope="col">Finish Good</th>
                <th scope="col">Qty.</th>
                <th scope="col">Total</th>
                </tr>
            </thead>
            <tbody>
        ';
        foreach ($respond->result() as $invoicelist) {
            $html .= '
                <tr>
                    <td>'.$invoicelist->idtbl_invoice_detail.'</td>
                    <td>'.$invoicelist->materialname.'</td>
                    <td>'.$invoicelist->qty.'</td>
                    <td>'.$invoicelist->total.'</td>
                </tr>';
        }
        $html.='</tbody>
        <tfoot>
                <tr>
                    <th colspan="2" class="text-right"></th>
                    <th class="text-left">Total:</th>
                    <th class="text-left"></th>
                </tr>
            </tfoot></table>';

        echo $html;
    }

    public function Getproductlisttoselectpicker(){
        $searchTerm=$this->input->post('searchTerm');

        if(!isset($searchTerm)){
            $sql="SELECT `idtbl_material_info`, `materialname` FROM `tbl_material_info` WHERE `status`=? LIMIT 5";
            $respond=$this->db->query($sql, array(1));                       
        }
        else{            
            if(!empty($searchTerm)){
                $sql="SELECT `idtbl_material_info`, `materialname` FROM `tbl_material_info` WHERE `status`=? AND `materialname` LIKE '$searchTerm%'";
                $respond=$this->db->query($sql, array(1));    
            }
            else{
                $sql="SELECT `idtbl_material_info`, `materialname` FROM `tbl_material_info` WHERE `status`=? LIMIT 5";
                $respond=$this->db->query($sql, array(1));                
            }
        }
        
        $data=array();
        
        foreach ($respond->result() as $row) {
            $data[]=array("id"=>$row->idtbl_material_info, "text"=>$row->materialname);
        }
        
        echo json_encode($data);
    }

}