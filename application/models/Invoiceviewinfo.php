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

        $recordID = $this->input->post('recordID');

        $sql = "SELECT 
                    `tbl_invoice_detail`.`idtbl_invoice_detail`, 
                    `tbl_invoice_detail`.`qty`, 
                    `tbl_invoice_detail`.`saleprice`, 
                    `tbl_invoice_detail`.`discount`, 
                    `tbl_invoice_detail`.`total`,
                    `tbl_material_info`.`materialname`
                FROM `tbl_invoice_detail`
                LEFT JOIN `tbl_invoice`  
                    ON `tbl_invoice`.`idtbl_invoice` = `tbl_invoice_detail`.`tbl_invoice_idtbl_invoice`
                LEFT JOIN `tbl_material_info`  
                    ON `tbl_material_info`.`idtbl_material_info` = `tbl_invoice_detail`.`tbl_material_info_idtbl_material_info`
                WHERE `tbl_invoice`.`idtbl_invoice`=? 
                AND `tbl_invoice_detail`.`status`=?";

        $respond = $this->db->query($sql, array($recordID, 1)); 

        $grossTotal = 0;
        $discountTotal = 0;
        $netTotal = 0;

        $html .= '
        <table class="table table-bordered table-striped table-sm nowrap" id="tblInvoicelist">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Finish Good</th>
                    <th class="text-right">Qty.</th>
                    <th class="text-right">Gross Total</th>
                    <th class="text-right">Discount</th>
                    <th class="text-right">Net Total</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($respond->result() as $invoicelist) {

            $grossTotal += $invoicelist->saleprice;
            $discountTotal += $invoicelist->discount;
            $netTotal += $invoicelist->total;

            $html .= '
                <tr>
                    <td>'.$invoicelist->idtbl_invoice_detail.'</td>
                    <td>'.$invoicelist->materialname.'</td>
                    <td class="text-right">'.$invoicelist->qty.'</td>
                    <td class="text-right">'.number_format($invoicelist->saleprice,2).'</td>
                    <td class="text-right">'.number_format($invoicelist->discount,2).'</td>
                    <td class="text-right">'.number_format($invoicelist->total,2).'</td>
                </tr>';
        }

        $html .= '
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-right">Total:</th>
                    <th class="text-right">'.number_format($grossTotal,2).'</th>
                    <th class="text-right">'.number_format($discountTotal,2).'</th>
                    <th class="text-right">'.number_format($netTotal,2).'</th>
                </tr>
            </tfoot>
        </table>';

        echo $html;
    }

}