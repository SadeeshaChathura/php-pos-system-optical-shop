<?php
class Expenseinfo extends CI_Model{

    public function generateExpenseNo(){
        $this->db->select("MAX(
            CASE 
                WHEN expenseno REGEXP '^EXP-' THEN CAST(SUBSTRING(expenseno, 5) AS UNSIGNED)
                ELSE CAST(expenseno AS UNSIGNED)
            END
        ) as maxcode", false);
        $this->db->from('tbl_expense');
        $query = $this->db->get();
        $row = $query->row();

        $nextNumber = ($row && $row->maxcode) ? $row->maxcode + 1 : 1;

        return 'EXP-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

    public function Getexpensetype(){
        $this->db->select('`idtbl_expense_type`, `expensetypename`');
        $this->db->from('tbl_expense_type');
        $this->db->where('status', 1);

        return $this->db->get();
    }

    public function Getcompany(){
        $this->db->select('`idtbl_company`, `company`');
        $this->db->from('tbl_company');
        $this->db->where('status', 1);

        return $this->db->get();
    }

    public function Getcompanybranch(){
        $this->db->select('`idtbl_company_branch`, `branch`, `tbl_company_idtbl_company`');
        $this->db->from('tbl_company_branch');
        $this->db->where('status', 1);

        return $this->db->get();
    }

    public function Expenseinsertupdate(){
        $this->db->trans_begin();

        $userID=$_SESSION['userid'];
        $companyID=$_SESSION['companyid'];
        $companyBranchID=$_SESSION['branchid'];

        $expenseno=$this->input->post('expenseno');
        $expensedate=$this->input->post('expensedate');
        $amount=$this->input->post('amount');
        $expensetype=$this->input->post('expensetype');
        $paymentmethod=$this->input->post('paymentmethod');
        $bank=$this->input->post('bank');
        $branch=$this->input->post('branch');
        $chequeno=$this->input->post('chequeno');
        $chequedate=!empty($this->input->post('chequedate')) ? $this->input->post('chequedate') : NULL;
        $remark=$this->input->post('remark');

        $recordOption=$this->input->post('recordOption');
        if(!empty($this->input->post('recordID'))){$recordID=$this->input->post('recordID');}

        $updatedatetime=date('Y-m-d H:i:s');

        if($recordOption==1){
            $data = array(
                'expenseno'=> $expenseno,
                'expensedate'=> $expensedate,
                'amount'=> $amount,
                'paymentmethod'=> $paymentmethod,
                'bank'=> $bank,
                'branch'=> $branch,
                'chequeno'=> $chequeno,
                'chequedate'=> $chequedate,
                'remark'=> $remark,
                'status'=> '1',
                'insertdatetime'=> $updatedatetime,
                'tbl_user_idtbl_user'=> $userID,
                'tbl_expense_type_idtbl_expense_type'=> $expensetype,
                'tbl_company_idtbl_company'=> $companyID,
                'tbl_company_branch_idtbl_company_branch'=> $companyBranchID,
            );

            $this->db->insert('tbl_expense', $data);

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
                redirect('Expense');
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
                redirect('Expense');
            }
        }
        else{
            $data = array(
                'expensedate'=> $expensedate,
                'amount'=> $amount,
                'paymentmethod'=> $paymentmethod,
                'bank'=> $bank,
                'branch'=> $branch,
                'chequeno'=> $chequeno,
                'chequedate'=> $chequedate,
                'remark'=> $remark,
                'updatedatetime'=> $updatedatetime,
                'tbl_expense_type_idtbl_expense_type'=> $expensetype,
                'tbl_company_idtbl_company'=> $companyID,
                'tbl_company_branch_idtbl_company_branch'=> $companyBranchID,
            );

            $this->db->where('idtbl_expense', $recordID);
            $this->db->update('tbl_expense', $data);

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
                redirect('Expense');
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
                redirect('Expense');
            }
        }
    }

    public function Expensestatus($x, $y){
        $this->db->trans_begin();

        $recordID=$x;
        $type=$y;
        $updatedatetime=date('Y-m-d H:i:s');

        $data = array(
            'status' => $type,
            'updatedatetime'=> $updatedatetime
        );

        $this->db->where('idtbl_expense', $recordID);
        $this->db->update('tbl_expense', $data);

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
            redirect('Expense');
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
            redirect('Expense');
        }
    }

    public function Expenseedit(){
        $recordID=$this->input->post('recordID');

        $this->db->select('*');
        $this->db->from('tbl_expense');
        $this->db->where('idtbl_expense', $recordID);
        $this->db->where('status', 1);

        $respond=$this->db->get();

        $obj=new stdClass();
        $obj->id=$respond->row(0)->idtbl_expense;
        $obj->expenseno=$respond->row(0)->expenseno;
        $obj->expensedate=$respond->row(0)->expensedate;
        $obj->amount=$respond->row(0)->amount;
        $obj->expensetype=$respond->row(0)->tbl_expense_type_idtbl_expense_type;
        $obj->company=$respond->row(0)->tbl_company_idtbl_company;
        $obj->companybranch=$respond->row(0)->tbl_company_branch_idtbl_company_branch;
        $obj->paymentmethod=$respond->row(0)->paymentmethod;
        $obj->bank=$respond->row(0)->bank;
        $obj->branch=$respond->row(0)->branch;
        $obj->chequeno=$respond->row(0)->chequeno;
        $obj->chequedate=$respond->row(0)->chequedate;
        $obj->remark=$respond->row(0)->remark;

        echo json_encode($obj);
    }
}