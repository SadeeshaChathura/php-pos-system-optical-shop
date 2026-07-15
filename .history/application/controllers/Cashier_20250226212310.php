<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Directsale extends CI_Controller {
    public function index(){
        $this->load->model('Commeninfo');
        $this->load->model('Directsaleinfo');
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$this->load->view('cashier', $result);
	}
}