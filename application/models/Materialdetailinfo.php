<?php
class Materialdetailinfo extends CI_Model{
    public function generateMaterialCode(){
        $this->db->select("MAX(
            CASE 
                WHEN materialinfocode REGEXP '^MAT-' THEN CAST(SUBSTRING(materialinfocode, 5) AS UNSIGNED)
                ELSE CAST(materialinfocode AS UNSIGNED)
            END
        ) as maxcode", false);
        $this->db->from('tbl_material_info');
        $query = $this->db->get();
        $row = $query->row();

        $nextNumber = ($row && $row->maxcode) ? $row->maxcode + 1 : 100;

        return 'PRO-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
    public function Getmaterialcategory(){
        $this->db->select('`idtbl_material_category`, `categoryname`');
        $this->db->from('tbl_material_category');
        $this->db->where('status', 1);

        return $respond=$this->db->get();
    }

    public function Materialdetailinsertupdate(){
        $this->db->trans_begin();

        $userID=$_SESSION['userid'];

        $materialname=$this->input->post('name');
        $materialcode=$this->input->post('code');
        $barcode=$this->input->post('barcode'); 
        $rol=$this->input->post('rol');
        $materialcategory=$this->input->post('materialcategory');
        $comment=$this->input->post('comment');  
        

        $recordOption=$this->input->post('recordOption');
        if(!empty($this->input->post('recordID'))){$recordID=$this->input->post('recordID');}

        $updatedatetime=date('Y-m-d H:i:s');  

        if($recordOption==1){
            $data = array(
                'materialname'=> $materialname, 
                'materialinfocode'=> $materialcode, 
                'barcode'=> $barcode,  
                'reorderlevel'=> $rol, 
                'comment'=> $comment, 
                'status'=> '1', 
                'insertdatetime'=> $updatedatetime, 
                'tbl_user_idtbl_user'=> $userID, 
                'tbl_material_category_idtbl_material_category'=> $materialcategory, 
            );

            $this->db->insert('tbl_material_info', $data);

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
                
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('Materialdetail');                
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
                redirect('Materialdetail');
            }
        }
        else{
            $data = array(
                'materialname'=> $materialname, 
                'materialinfocode'=> $materialcode, 
                'barcode'=> $barcode,
                'reorderlevel'=> $rol, 
                'comment'=> $comment, 
                'updatedatetime'=> $updatedatetime, 
                'tbl_material_category_idtbl_material_category'=> $materialcategory
            );

            $this->db->where('idtbl_material_info', $recordID);
            $this->db->update('tbl_material_info', $data);

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                
                $actionObj=new stdClass();
                $actionObj->icon='fas fa-save';
                $actionObj->title='';
                $actionObj->message='Record Update Successfully';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='primary';

                $actionJSON=json_encode($actionObj);
                
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('Materialdetail');                
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
                redirect('Materialdetail');
            }
        }
    }
    public function Materialdetailstatus($x, $y){
        $this->db->trans_begin();

        $userID=$_SESSION['userid'];
        $recordID=$x;
        $type=$y;
        $updatedatetime=date('Y-m-d H:i:s');

        if($type==1){
            $data = array(
                'status' => '1',
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_material_info', $recordID);
            $this->db->update('tbl_material_info', $data);

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                
                $actionObj=new stdClass();
                $actionObj->icon='fas fa-check';
                $actionObj->title='';
                $actionObj->message='Record Activate Successfully';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='success';

                $actionJSON=json_encode($actionObj);
                
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('Materialdetail');                
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
                redirect('Materialdetail');
            }
        }
        else if($type==2){
            $data = array(
                'status' => '2',
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_material_info', $recordID);
            $this->db->update('tbl_material_info', $data);

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                
                $actionObj=new stdClass();
                $actionObj->icon='fas fa-times';
                $actionObj->title='';
                $actionObj->message='Record Deactivate Successfully';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='warning';

                $actionJSON=json_encode($actionObj);
                
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('Materialdetail');                
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
                redirect('Materialdetail');
            }
        }
        else if($type==3){
            $data = array(
                'status' => '3',
                'updatedatetime'=> $updatedatetime
            );

            $this->db->where('idtbl_material_info', $recordID);
            $this->db->update('tbl_material_info', $data);

            $this->db->trans_complete();

            if ($this->db->trans_status() === TRUE) {
                $this->db->trans_commit();
                
                $actionObj=new stdClass();
                $actionObj->icon='fas fa-trash-alt';
                $actionObj->title='';
                $actionObj->message='Record Remove Successfully';
                $actionObj->url='';
                $actionObj->target='_blank';
                $actionObj->type='danger';

                $actionJSON=json_encode($actionObj);
                
                $this->session->set_flashdata('msg', $actionJSON);
                redirect('Materialdetail');                
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
                redirect('Materialdetail');
            }
        }
    }
    public function Materialdetailedit(){
        $recordID=$this->input->post('recordID');

        $this->db->select('*');
        $this->db->from('tbl_material_info');
        $this->db->where('idtbl_material_info', $recordID);
        $this->db->where('status', 1);

        $respond=$this->db->get();

        $obj=new stdClass();
        $obj->id=$respond->row(0)->idtbl_material_info;
        $obj->materialname=$respond->row(0)->materialname;
        $obj->materialinfocode=$respond->row(0)->materialinfocode;
        $obj->barcode=$respond->row(0)->barcode;
        $obj->rol=$respond->row(0)->reorderlevel;
        $obj->materialcategory=$respond->row(0)->tbl_material_category_idtbl_material_category ;
        $obj->comment=$respond->row(0)->comment;


        echo json_encode($obj);
    }

    public function Getbarcodedata(){
        $materialIDs = $this->input->post('materialIDs');

        $this->db->select('mi.idtbl_material_info, mi.materialname, mi.materialinfocode, mi.barcode, s.saleprice');
        $this->db->from('tbl_material_info mi');
        $this->db->join(
            '(SELECT ts1.tbl_material_info_idtbl_material_info, ts1.saleprice
            FROM tbl_stock ts1
            INNER JOIN (
                SELECT tbl_material_info_idtbl_material_info, MAX(idtbl_stock) AS maxid
                FROM tbl_stock
                WHERE status = 1
                GROUP BY tbl_material_info_idtbl_material_info
            ) ts2 ON ts2.maxid = ts1.idtbl_stock) s',
            's.tbl_material_info_idtbl_material_info = mi.idtbl_material_info',
            'left'
        );
        $this->db->where_in('mi.idtbl_material_info', $materialIDs);

        $respond = $this->db->get();

        $result = array();
        foreach($respond->result() as $row){
            $result[] = array(
                'id'    => $row->idtbl_material_info,
                'name'  => $row->materialname,
                'code'  => !empty($row->barcode) ? $row->barcode : $row->materialinfocode,
                'price' => $row->saleprice ? $row->saleprice : 0
            );
        }

        echo json_encode($result);
    }

}