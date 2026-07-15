<?php
class Productioninfo extends CI_Model{
    public function Productiondetailaccoproduction(){
        $recordID=$this->input->post('recordID');

        $sql="SELECT `tbl_production_order_detail`.`idtbl_production_order_detail`, `tbl_material_code`.`materialname`, `tbl_product`.`productcode` FROM `tbl_production_order_detail` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_production_order_detail`.`tbl_product_idtbl_product` LEFT JOIN `tbl_material_code` ON `tbl_material_code`.`idtbl_material_code`=`tbl_product`.`materialid` WHERE `tbl_production_order_detail`.`tbl_production_order_idtbl_production_order`=? AND `tbl_production_order_detail`.`status`=?";
        $respond=$this->db->query($sql, array($recordID, 1));

        echo json_encode($respond->result());
    }
    public function Getfactorylist(){
        $this->db->select('`idtbl_factory`, `factoryname`, `factorycode`');
        $this->db->from('tbl_factory');
        $this->db->where('status', 1);

        return $respond=$this->db->get();
    }
    public function Getmachineaccofactory(){
        $recordID=$this->input->post('recordID');

        $sql="SELECT `tbl_machine`.`idtbl_machine`, `tbl_machine`.`machine`, `tbl_machine`.`machinecode` FROM `tbl_machine` LEFT JOIN `tbl_factory_has_tbl_machine` ON `tbl_factory_has_tbl_machine`.`tbl_machine_idtbl_machine`=`tbl_machine`.`idtbl_machine` WHERE `tbl_factory_has_tbl_machine`.`tbl_factory_idtbl_factory`=? AND `tbl_machine`.`status`=?";
        $respond=$this->db->query($sql, array($recordID, 1));

        echo json_encode($respond->result());
    }
    public function Productionstepone(){
        $this->db->trans_begin();

        $userID=$_SESSION['userid'];

        $tableData=$this->input->post('tableData');
        $productionorder=$this->input->post('productionorder');

        $updatedatetime=date('Y-m-d H:i:s');
        $today=date('Y-m-d');

        $data = array(
            'approvestatus'=> '1', 
            'productionstep'=> '1', 
            'updateuser'=> $userID, 
            'updatedatetime' => $updatedatetime
        );

        $this->db->where('idtbl_production_order', $productionorder);
        $this->db->update('tbl_production_order', $data);

        foreach($tableData as $rowtabledata){
            $qty=$rowtabledata['col_5'];
            $productiondetailID=$rowtabledata['col_6'];
            $factoryID=$rowtabledata['col_7'];
            $machineID=$rowtabledata['col_8'];
            $batchno=$rowtabledata['col_9'];
            $materialID=$rowtabledata['col_10'];

            $this->db->select('`tbl_product_idtbl_product`');
            $this->db->from('tbl_production_order_detail');
            $this->db->where('idtbl_production_order_detail', $productiondetailID);
            $this->db->where('status', 1);

            $respond=$this->db->get();

            $dataone = array(
                'type'=> '1', 
                'date'=> $today, 
                'qty'=> $qty, 
                'batchno'=> $batchno,
                'status'=> '1', 
                'insertdatetime'=> $updatedatetime,
                'tbl_production_order_idtbl_production_order'=> $productionorder, 
                'tbl_production_order_detail_idtbl_production_order_detail'=> $productiondetailID,
                'tbl_user_idtbl_user'=> $userID, 
                'tbl_machine_idtbl_machine'=> $machineID, 
                'tbl_factory_idtbl_factory'=> $factoryID, 
                'tbl_product_idtbl_product'=> $respond->row(0)->tbl_product_idtbl_product,
                'tbl_material_info_idtbl_material_info'=> $materialID, 
            );
            $this->db->insert('tbl_production_machine', $dataone);

            $updatestock="UPDATE `tbl_stock` SET `qty`=(`qty`-'$qty'),`updateuser`='$userID',`updatedatetime`='$updatedatetime' WHERE `batchno`=? AND tbl_material_info_idtbl_material_info=?";
            $respond=$this->db->query($updatestock, array($batchno, $materialID));
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
    public function Getbatchnoaccoproductiondetail(){
        $recordID=$this->input->post('recordID');

        $sql="SELECT
            `tbl_product_bom`.`tbl_material_info_idtbl_material_info`,
            `tbl_material_code`.`materialname`,
            `tbl_material_info`.`materialinfocode`,
            `tbl_stock`.`batchno`
        FROM
            `tbl_production_order_detail`
        LEFT JOIN `tbl_product_bom` ON `tbl_product_bom`.`tbl_product_idtbl_product` = `tbl_production_order_detail`.`tbl_product_idtbl_product`
        LEFT JOIN `tbl_stock` ON `tbl_stock`.`tbl_material_info_idtbl_material_info` = `tbl_product_bom`.`tbl_material_info_idtbl_material_info`
        LEFT JOIN `tbl_material_info` ON `tbl_material_info`.`idtbl_material_info` = `tbl_product_bom`.`tbl_material_info_idtbl_material_info`
        LEFT JOIN `tbl_material_code` ON `tbl_material_code`.`idtbl_material_code` = `tbl_material_info`.`tbl_material_code_idtbl_material_code`
        WHERE
            `tbl_production_order_detail`.`idtbl_production_order_detail` = ? AND `tbl_product_bom`.`status` = ?";
        $respond=$this->db->query($sql, array($recordID, 1));

        // print_r($this->db->last_query());  

        echo json_encode($respond->result());
    }
    public function Getqtyinfoaccoproductiondetail(){
        $recordID=$this->input->post('recordID');

        $sql="SELECT `dprodetail`.`qty`, `dpromachine`.`sumqty` FROM (SELECT `qty`, `tbl_product_idtbl_product` FROM `tbl_production_order_detail` WHERE `idtbl_production_order_detail`=? AND `status`=?) AS `dprodetail` LEFT JOIN (SELECT SUM(`qty`) AS `sumqty`, `tbl_product_idtbl_product` FROM `tbl_production_machine` WHERE `tbl_production_order_detail_idtbl_production_order_detail`=? AND `status`=?) AS `dpromachine` ON `dpromachine`.`tbl_product_idtbl_product`=`dprodetail`.`tbl_product_idtbl_product`";
        $respond=$this->db->query($sql, array($recordID, 1, $recordID, 1));

        echo json_encode($respond->result());
    }
    public function Checkavabilitystock(){
        $qty=$this->input->post('qty');
        $materialID=$this->input->post('materialID');

        $sql="SELECT `qty` FROM `tbl_stock` WHERE `tbl_material_info_idtbl_material_info`=? AND `status`=?";
        $respond=$this->db->query($sql, array($materialID, 1));

        if($respond->row(0)->qty>=$qty){
            echo '1';
        }
        else{
            echo '0';
        }
    }
}