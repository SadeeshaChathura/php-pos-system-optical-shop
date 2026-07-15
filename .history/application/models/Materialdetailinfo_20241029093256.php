<?php
class Materialdetailinfo extends CI_Model{
    public function Getmaterialcategory(){
        $this->db->select('`idtbl_res_material_category`, `category`');
        $this->db->from('tbl_res_material_category');
        $this->db->where('status', 1);

        return $respond=$this->db->get();
    }

    public function Materialdetailinsertupdate(){
        $this->db->trans_begin();

        $userID=$_SESSION['userid'];

        $materialname=$this->input->post('name');
        $materialcode=$this->input->post('code');
        $rol=$this->input->post('rol');
        $materialcategory=$this->input->post('materialcategory');
        $comment=$this->input->post('comment');  
        

        $recordOption=$this->input->post('recordOption');
        if(!empty($this->input->post('recordID'))){$recordID=$this->input->post('recordID');}

        $updatedatetime=date('Y-m-d H:i:s');  

        if($recordOption==1){
            $data = array(
                'material'=> $materialname, 
                'materialinfocode'=> $materialcode, 
                'reorderlevel'=> $rol, 
                'comment'=> $comment, 
                'status'=> '1', 
                'insertdatetime'=> $updatedatetime, 
                'tbl_res_user_idtbl_res_user'=> $userID, 
                'tbl_res_material_category_idtbl_res_material_category'=> $materialcategory, 
            );

            $this->db->insert('tbl_res_material_info', $data);

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
                'material'=> $materialname, 
                'materialinfocode'=> $materialcode, 
                'reorderlevel'=> $rol, 
                'comment'=> $comment, 
                'updatedatetime'=> $updatedatetime, 
                'tbl_res_material_category_idtbl_res_material_category'=> $materialcategory
            );

            $this->db->where('idtbl_res_material_info', $recordID);
            $this->db->update('tbl_res_material_info', $data);

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

            $this->db->where('idtbl_res_material_info', $recordID);
            $this->db->update('tbl_res_material_info', $data);

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

            $this->db->where('idtbl_res_material_info', $recordID);
            $this->db->update('tbl_res_material_info', $data);

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

            $this->db->where('idtbl_res_material_info', $recordID);
            $this->db->update('tbl_res_material_info', $data);

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
        $this->db->from('tbl_res_material_info');
        $this->db->where('idtbl_res_material_info', $recordID);
        $this->db->where('status', 1);

        $respond=$this->db->get();

        $obj=new stdClass();
        $obj->id=$respond->row(0)->idtbl_res_material_info;
        $obj->materialname=$respond->row(0)->material;
        $obj->materialinfocode=$respond->row(0)->materialinfocode;
        $obj->rol=$respond->row(0)->reorderlevel;
        $obj->materialcategory=$respond->row(0)->tbl_res_material_category_idtbl_res_material_category ;
        $obj->comment=$respond->row(0)->comment;


        echo json_encode($obj);
    }

}