<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Expensetype extends CI_Controller {
    public function index(){
        $this->load->model('Commeninfo');
        $result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
        $this->load->view('expensetype', $result);
    }
    public function Expensetypeinsertupdate(){
        $this->load->model('Expensetypeinfo');
        $result=$this->Expensetypeinfo->Expensetypeinsertupdate();
    }
    public function Expensetypestatus($x, $y){
        $this->load->model('Expensetypeinfo');
        $result=$this->Expensetypeinfo->Expensetypestatus($x, $y);
    }
    public function Expensetypeedit(){
        $this->load->model('Expensetypeinfo');
        $result=$this->Expensetypeinfo->Expensetypeedit();
    }
    public function Expensetypegeneratecode(){
        $this->load->model('Expensetypeinfo');
        $code = $this->Expensetypeinfo->generateExpenseTypeCode();
        echo json_encode(array('code' => $code));
    }
}