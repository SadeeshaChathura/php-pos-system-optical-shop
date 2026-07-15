<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Customer extends CI_Controller {
    public function index(){
        $this->load->model('Commeninfo');
        $this->load->model('Customerinfo');
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$this->load->view('Customer', $result);
	}

}