<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Expense extends CI_Controller {
    public function index(){
        $this->load->model('Commeninfo');
        $this->load->model('Expenseinfo');
        $result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
        $result['expensetype']=$this->Expenseinfo->Getexpensetype();
        $this->load->view('expense', $result);
    }
    public function Expenseinsertupdate(){
        $this->load->model('Expenseinfo');
        $result=$this->Expenseinfo->Expenseinsertupdate();
    }
    public function Expensestatus($x, $y){
        $this->load->model('Expenseinfo');
        $result=$this->Expenseinfo->Expensestatus($x, $y);
    }
    public function Expenseedit(){
        $this->load->model('Expenseinfo');
        $result=$this->Expenseinfo->Expenseedit();
    }
    public function Expensegeneratecode(){
        $this->load->model('Expenseinfo');
        $code = $this->Expenseinfo->generateExpenseNo();
        echo json_encode(array('code' => $code));
    }
}