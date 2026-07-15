<?php
class Commeninfo extends CI_Model{
    public function Getmenuprivilege(){
        $userID=$_SESSION['userid'];

        $menuprivilegearray=array();
            
        $sql="SELECT `idtbl_menu_list`, `menu` FROM `tbl_menu_list` WHERE `status`=?";
        $respond=$this->db->query($sql, array(1));
        
        foreach($respond->result() as $row){
            $menucheckID=$row->idtbl_menu_list;
            $menuname=str_replace(" ","_",$row->menu);
            
            $sqlprivilegecheck="SELECT `add`, `edit`, `statuschange`, `remove`, `access_status`, `tbl_menu_list_idtbl_menu_list` FROM `tbl_user_privilege` WHERE `tbl_user_idtbl_user`=? AND `tbl_menu_list_idtbl_menu_list`=? AND `status`=?";
            $respondprivilegecheck=$this->db->query($sqlprivilegecheck, array($userID, $menucheckID, 1));
            
            if($respondprivilegecheck->num_rows()>0){
                $objmenu=new stdClass();
                $objmenu->add=$respondprivilegecheck->row(0)->add;
                $objmenu->edit=$respondprivilegecheck->row(0)->edit;
                $objmenu->statuschange=$respondprivilegecheck->row(0)->statuschange;
                $objmenu->remove=$respondprivilegecheck->row(0)->remove;
                $objmenu->access_status=$respondprivilegecheck->row(0)->access_status;
                $objmenu->menuid=$respondprivilegecheck->row(0)->tbl_menu_list_idtbl_menu_list;
                array_push($menuprivilegearray, $objmenu);
            }
        }

        return $menuprivilegearray;
    }
    public function Chartxline(){
        $sql = "SELECT DISTINCT MONTH(`orderdate`) AS month_number, DATE_FORMAT(`orderdate`, '%M') AS month_name
        FROM `tbl_customer_porder`
        WHERE YEAR(`orderdate`) = YEAR(CURDATE()) AND MONTH(`orderdate`) <= MONTH(CURDATE())
        ORDER BY month_number";
        
        $respond = $this->db->query($sql);
        
        $list = array();
        
        foreach ($respond->result() as $rowlist) {
            $list[] = $rowlist->month_name;
        }
        
        return json_encode($list);
    }
    
    public function Chartyline(){
        $sql = "SELECT YEAR(`orderdate`) AS order_year, MONTH(`orderdate`) AS order_month, SUM(`nettotal`) AS total_nettotal
        FROM `tbl_customer_porder`
        GROUP BY order_year, order_month
        ORDER BY order_year, order_month;
        ";
        
        $respond = $this->db->query($sql);
        
        $list = array();
        
        foreach ($respond->result() as $rowlist) {
            $list[] = $rowlist->total_nettotal;
        }
        
        return json_encode($list);
    }
    
    public function Chartxlinedaily() {
        $sql = "SELECT
                    DAY(`orderdate`) AS day_number,
                    DATE_FORMAT(`orderdate`, '%Y-%m-%d') AS date
                FROM `tbl_customer_porder`
                WHERE MONTH(`orderdate`) = MONTH(CURDATE()) AND YEAR(`orderdate`) = YEAR(CURDATE())
                GROUP BY day_number
                ORDER BY date";
    
        $respond = $this->db->query($sql);
    
        $list = array(); // Initialize an empty array
    
        foreach ($respond->result() as $rowlist) {
            $list[] = $rowlist->date;
        }
    
        return json_encode($list);
    }
    
    public function Chartylinedaily() {
        $sql = "SELECT DAY(`orderdate`) AS order_day, SUM(`nettotal`) AS total_nettotal
                FROM `tbl_customer_porder`
                WHERE MONTH(`orderdate`) = MONTH(CURDATE()) AND YEAR(`orderdate`) = YEAR(CURDATE())
                GROUP BY order_day
                ORDER BY order_day";
    
        $respond = $this->db->query($sql);
    
        $list = array(); // Initialize an empty array
    
        foreach ($respond->result() as $rowlist) {
            $list[] = $rowlist->total_nettotal;
        }
    
        return json_encode($list);
    }
    
    
    
    public function salepricechartxline(){
        $sql = "SELECT `desc` FROM `tbl_product` WHERE `status`=?";
        $respond=$this->db->query($sql, array(1));

        foreach($respond->result() as $rowlist){
            $list[]=$rowlist->desc;
        }

        return json_encode($list);
    }
    public function salepricechartyline(){
        $sql = "SELECT `qty` FROM `tbl_product_stock` WHERE `status`=?";
        $respond=$this->db->query($sql, array(1));

        foreach($respond->result() as $rowlist){
            $list[]=$rowlist->qty;
        }

        return json_encode($list);
    }

    public function matstockchartxline(){
        $sql = "SELECT `materialinfocode` FROM `tbl_material_info` WHERE `status`=?";
        $respond=$this->db->query($sql, array(1));

        foreach($respond->result() as $rowlist){
            $list[]=$rowlist->materialinfocode;
        }

        return json_encode($list);
    }
    public function matstockchartyline(){
        $sql = "SELECT `qty` FROM `tbl_stock` WHERE `status`=?";
        $respond=$this->db->query($sql, array(1));

        foreach($respond->result() as $rowlist){
            $list[]=$rowlist->qty;
        }

        return json_encode($list);
    }
}