<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class CustomerPrescription extends CI_Controller {
    public function index(){
        $this->load->model('Commeninfo');
        $this->load->model('CustomerPrescriptioninfo');
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$this->load->view('customerPrescription', $result);
	}

}