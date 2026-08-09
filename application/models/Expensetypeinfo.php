<?php
class Expensetypeinfo extends CI_Model{
    public function generateExpenseTypeCode(){
        $this->db->select("MAX(
            CASE 
                WHEN expensetypecode REGEXP '^ET-' THEN CAST(SUBSTRING(expensetypecode, 4) AS UNSIGNED)
                ELSE CAST(expensetypecode AS UNSIGNED)
            END
        ) as maxcode", false);
        $this->db->from('tbl_expense_type');
        $query = $this->db->get();
        $row = $query->row();

        $nextNumber = ($row && $row->maxcode) ? $row->maxcode + 1 : 1;

        return 'ET-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    public function Expensetypeinsertupdate(){
        $this->db->trans_begin();

        $userID=$_SESSION['userid'];

        $expensetypename=$this->input->post('name');
        $expensetypecode=$this->input->post('code');

        $recordOption=$this->input->post('recordOption');
        if(!empty($this->input->post('recordID'))){$recordID=$this->input->post('recordID');}

        $updatedatetime=date('Y-m-d H:i:s');

        if($recordOption==1){
            $data = array(
                'expensetypename'=> $expensetypename,
                'expensetypecode'=> $expensetypecode,
                'status'=> '1',
                'insertdatetime'=> $updatedatetime,
                'tbl_user_idtbl_user'=> $userID,
            );

            $this->db->insert('tbl_expense_type', $data);

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
                redirect('Expensetype');
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
                redirect('Expensetype');
            }
        }
        else{
            $data = array(
                'expensetypename'=> $expensetypename,
                'expensetypecode'=> $expensetypecode,
                'updatedatetime'=> $updatedatetime,
            );

            $this->db->where('idtbl_expense_type', $recordID);
            $this->db->update('tbl_expense_type', $data);

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
                redirect('Expensetype');
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
                redirect('Expensetype');
            }
        }
    }

    public function Expensetypestatus($x, $y){
        $this->db->trans_begin();

        $recordID=$x;
        $type=$y;
        $updatedatetime=date('Y-m-d H:i:s');

        $data = array(
            'status' => $type,
            'updatedatetime'=> $updatedatetime
        );

        $this->db->where('idtbl_expense_type', $recordID);
        $this->db->update('tbl_expense_type', $data);

        $this->db->trans_complete();

        $icon = ($type==1)?'fas fa-check':(($type==2)?'fas fa-times':'fas fa-trash-alt');
        $msg = ($type==1)?'Record Activate Successfully':(($type==2)?'Record Deactivate Successfully':'Record Remove Successfully');
        $btntype = ($type==1)?'success':(($type==2)?'warning':'danger');

        if ($this->db->trans_status() === TRUE) {
            $this->db->trans_commit();

            $actionObj=new stdClass();
            $actionObj->icon=$icon;
            $actionObj->title='';
            $actionObj->message=$msg;
            $actionObj->url='';
            $actionObj->target='_blank';
            $actionObj->type=$btntype;

            $actionJSON=json_encode($actionObj);

            $this->session->set_flashdata('msg', $actionJSON);
            redirect('Expensetype');
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
            redirect('Expensetype');
        }
    }

    public function Expensetypeedit(){
        $recordID=$this->input->post('recordID');

        $this->db->select('*');
        $this->db->from('tbl_expense_type');
        $this->db->where('idtbl_expense_type', $recordID);
        $this->db->where('status', 1);

        $respond=$this->db->get();

        $obj=new stdClass();
        $obj->id=$respond->row(0)->idtbl_expense_type;
        $obj->expensetypename=$respond->row(0)->expensetypename;
        $obj->expensetypecode=$respond->row(0)->expensetypecode;

        echo json_encode($obj);
    }
}