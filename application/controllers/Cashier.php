<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Cashier extends CI_Controller {
    public function index(){
        $this->load->model('Commeninfo');
        $this->load->model('Cashierinfo');
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$this->load->view('cashier', $result);
	}
}